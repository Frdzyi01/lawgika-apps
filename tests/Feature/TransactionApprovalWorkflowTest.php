<?php

namespace Tests\Feature;

use App\Http\Controllers\Admin\VirtualOfficeController;
use App\Models\Document;
use App\Models\Order;
use App\Models\OrderAuditLog;
use App\Models\OrderChangeRequest;
use App\Models\OrderHistory;
use App\Models\RoomBenefit;
use App\Models\User;
use App\Services\RoomBenefitService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TransactionApprovalWorkflowTest extends TestCase
{
    use DatabaseTransactions;

    private User $admin1;
    private User $admin3;
    private User $customer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin1 = User::where('role', 'admin1')->first() ?? User::create([
            'name'     => 'Admin Order 1',
            'email'    => 'test_admin1_' . uniqid() . '@example.com',
            'password' => Hash::make('password'),
            'role'     => 'admin1',
        ]);

        $this->admin3 = User::where('role', 'admin3')->first() ?? User::create([
            'name'     => 'Admin Verifikasi 3',
            'email'    => 'test_admin3_' . uniqid() . '@example.com',
            'password' => Hash::make('password'),
            'role'     => 'admin3',
        ]);

        $this->customer = User::create([
            'name'         => 'PT Uji Transaksi',
            'email'        => 'test_client_' . uniqid() . '@example.com',
            'password'     => Hash::make('password'),
            'role'         => 'customer',
            'company_name' => 'PT Uji Transaksi Nusantara',
            'phone'        => '081234567890',
        ]);
    }

    public function test_admin1_submit_forces_pending_approval_and_blocks_direct_benefit_or_edit(): void
    {
        $response = $this->actingAs($this->admin1)->post(route('admin.orders.store'), [
            'user_id'          => $this->customer->id,
            'service'          => 'pendirian-pt',
            'package'          => 'enterprise',
            'transaction_date' => '2026-11-01',
            'invoice_number'   => 'INV-TEST-001',
            'payment_method'   => 'Transfer Bank (BCA)',
            'payment_status'   => 'verified',
            'total_price'      => 10000000,
            'status'           => 'approved', // Attempt bypass
            'notes'            => 'Order uji coba Admin 1',
        ]);

        $response->assertRedirect(route('admin.orders.index'));

        $order = Order::where('invoice_number', 'INV-TEST-001')->firstOrFail();
        $this->assertSame('pending_approval', $order->status);
        $this->assertSame('2026-11-01', $order->transaction_date->toDateString());
        $this->assertSame('Transfer Bank (BCA)', $order->payment_method);
        $this->assertNotNull($order->submitted_at);
        $this->assertNull($order->approved_by);

        // No benefits active while pending_approval
        $this->assertSame(0, RoomBenefit::where('order_id', $order->id)->count());
        $this->assertSame(0, $this->customer->active_orders->count());
        $this->assertSame(0, $this->customer->meeting_quota_summary['total']);

        // Admin 1 blocked from approving, editing, deleting, or activating benefit
        $this->actingAs($this->admin1)->post(route('admin.orders.approve', $order->id))->assertStatus(403);
        $this->actingAs($this->admin1)->post(route('admin.orders.reject', $order->id), ['rejection_reason' => 'Test'])->assertStatus(403);
        $this->actingAs($this->admin1)->get(route('admin.orders.edit', $order->id))->assertStatus(403);
        $this->actingAs($this->admin1)->delete(route('admin.orders.destroy', $order->id))->assertStatus(403);
        $this->actingAs($this->admin1)->post(route('admin.orders.approve-benefit', $order->id))->assertStatus(403);
    }

    public function test_backdated_transaction_date_stays_unchanged_and_marks_benefit_expired_without_extending(): void
    {
        $this->actingAs($this->admin1)->post(route('admin.orders.store'), [
            'user_id'          => $this->customer->id,
            'service'          => 'virtual-office',
            'package'          => 'enterprise',
            'transaction_date' => '2025-08-17',
            'invoice_number'   => 'INV-BACKDATE-2025',
            'payment_method'   => 'Transfer Bank (Mandiri)',
            'payment_status'   => 'verified',
            'total_price'      => 5500000,
        ])->assertRedirect(route('admin.orders.index'));

        $order = Order::where('invoice_number', 'INV-BACKDATE-2025')->firstOrFail();
        $this->assertSame('pending_approval', $order->status);

        // Admin 3 approves on current date (2026-11-13)
        $this->actingAs($this->admin3)
            ->post(route('admin.orders.approve', $order->id))
            ->assertRedirect();

        $order->refresh();
        $this->assertSame('approved', $order->status);
        $this->assertSame($this->admin3->id, $order->approved_by);
        $this->assertNotNull($order->approved_at);
        // transaction_date MUST remain 2025-08-17
        $this->assertSame('2025-08-17', $order->transaction_date->toDateString());

        $benefits = RoomBenefit::where('order_id', $order->id)->get();
        $this->assertCount(2, $benefits);

        foreach ($benefits as $benefit) {
            $this->assertSame('2025-08-17', $benefit->started_at->toDateString());
            $this->assertSame('2026-08-17', $benefit->expired_at->toDateString());
            $this->assertTrue($benefit->is_expired);
            $this->assertSame('Expired', $benefit->status_label);
            $this->assertSame($this->admin3->id, $benefit->approved_by);
        }

        // Virtual Office view data reflects actual transaction dates & Expired status
        $this->actingAs($this->admin3)
            ->get(route('admin.virtual-office.show', $order->id))
            ->assertStatus(200)
            ->assertSee('17 August 2025')
            ->assertSee('17 August 2026')
            ->assertSee('Expired');
    }

    public function test_active_order_reject_revise_resubmit_and_idempotent_reapprove_flow(): void
    {
        $this->actingAs($this->admin1)->post(route('admin.orders.store'), [
            'user_id'          => $this->customer->id,
            'service'          => 'pendirian-yayasan',
            'package'          => 'enterprise',
            'transaction_date' => '2026-10-15',
            'invoice_number'   => 'INV-REV-001',
            'payment_method'   => 'Virtual Account / QRIS',
            'payment_status'   => 'unpaid',
            'total_price'      => 8000000,
        ]);

        $order = Order::where('invoice_number', 'INV-REV-001')->firstOrFail();

        // 1. Admin 3 rejects with reason
        $this->actingAs($this->admin3)
            ->post(route('admin.orders.reject', $order->id), [
                'rejection_reason' => 'Tanggal transaksi perlu dikoreksi ke 1 Nov 2026 dan bukti bayar diverifikasi.',
            ])
            ->assertRedirect();

        $order->refresh();
        $this->assertSame('rejected', $order->status);
        $this->assertSame($this->admin3->id, $order->rejected_by);
        $this->assertSame(0, RoomBenefit::where('order_id', $order->id)->count());

        // Admin 1 sees rejection reason & revision button in UI
        $this->actingAs($this->admin1)
            ->get(route('admin.orders.show', $order->id))
            ->assertStatus(200)
            ->assertSee('Tanggal transaksi perlu dikoreksi ke 1 Nov 2026')
            ->assertSee('Revisi Pesanan');

        // 2. Admin 1 revises & resubmits
        $this->actingAs($this->admin1)
            ->post(route('admin.orders.resubmit', $order->id), [
                'invoice_number'   => 'INV-REV-001-R1',
                'transaction_date' => '2026-11-01',
                'service'          => 'pendirian-yayasan',
                'package'          => 'enterprise',
                'total_price'      => 8000000,
                'payment_method'   => 'Virtual Account / QRIS',
                'payment_status'   => 'verified',
                'revision_note'    => 'Tanggal sudah diperbaiki ke 1 Nov 2026.',
            ])
            ->assertRedirect(route('admin.orders.show', $order->id));

        $order->refresh();
        $this->assertSame('pending_approval', $order->status);
        $this->assertSame(1, (int) $order->revision_count);
        $this->assertSame('2026-11-01', $order->transaction_date->toDateString());

        // Prior rejection history is still preserved
        $this->assertTrue(OrderHistory::where('order_id', $order->id)->where('action', 'rejected')->exists());
        $this->assertTrue(OrderHistory::where('order_id', $order->id)->where('action', 'resubmitted')->exists());

        // 3. Admin 3 re-approves
        $this->actingAs($this->admin3)->post(route('admin.orders.approve', $order->id))->assertRedirect();
        $order->refresh();
        $this->assertSame('approved', $order->status);

        $benefits = RoomBenefit::where('order_id', $order->id)->get();
        $this->assertCount(2, $benefits);
        $meetingBenefit = $benefits->where('type', 'meeting')->first();
        $this->assertSame('2026-11-01', $meetingBenefit->started_at->toDateString());
        $this->assertSame('2027-11-01', $meetingBenefit->expired_at->toDateString());
        $this->assertFalse($meetingBenefit->is_expired);

        // Second approve call cannot duplicate benefit records/hours
        $this->actingAs($this->admin3)->post(route('admin.orders.approve', $order->id));
        $this->assertCount(2, RoomBenefit::where('order_id', $order->id)->get());

        // Customer views show consistent start & expiry dates
        $this->actingAs($this->customer)
            ->get(route('customer.benefits.detail', $meetingBenefit->id))
            ->assertStatus(200)
            ->assertSee('01 Nov 2026')
            ->assertSee('01 Nov 2027');

        $this->actingAs($this->customer)
            ->get(route('customer.dashboard'))
            ->assertStatus(200)
            ->assertSee('01 Nov 2026')
            ->assertSee('01 Nov 2027');

        // Benefit checkin & checkout compatibility check (existing RoomBenefitService flow)
        $benefitService = app(RoomBenefitService::class);
        $benefitService->checkin($meetingBenefit, 'meeting');
        $benefitService->checkout($meetingBenefit, 'meeting', 60);
        $meetingBenefit->refresh();
        $this->assertSame(60, $meetingBenefit->used_minutes);
        $this->assertSame('2026-11-01', $meetingBenefit->started_at->toDateString());
        $this->assertSame('2027-11-01', $meetingBenefit->expired_at->toDateString());
    }

    public function test_admin3_permissions_change_requests_and_audit_trail_on_edit_and_delete(): void
    {
        // Admin 3 blocked from content and user/role management
        $this->actingAs($this->admin3)->get(route('admin.berita.index'))->assertStatus(403);
        $this->actingAs($this->admin3)->get(route('admin.promo.index'))->assertStatus(403);
        $this->actingAs($this->admin3)->get(route('admin.event-upcoming.index'))->assertStatus(403);
        $this->actingAs($this->admin3)->get(route('admin.peraturan-kbli.index'))->assertStatus(403);
        $this->actingAs($this->admin3)->get(route('admin.users.index'))->assertStatus(403);

        // Admin 3 can access orders list
        $this->actingAs($this->admin3)->get(route('admin.orders.index'))->assertStatus(200);

        // Create & approve order
        $this->actingAs($this->admin1)->post(route('admin.orders.store'), [
            'user_id'          => $this->customer->id,
            'service'          => 'pendirian-pt',
            'package'          => 'enterprise',
            'transaction_date' => '2026-11-05',
            'invoice_number'   => 'INV-AUD-001',
            'payment_method'   => 'Transfer Bank (BCA)',
            'payment_status'   => 'verified',
            'total_price'      => 9500000,
        ]);
        $order = Order::where('invoice_number', 'INV-AUD-001')->firstOrFail();
        $this->actingAs($this->admin3)->post(route('admin.orders.approve', $order->id));

        // Admin 1 submits edit change request
        $this->actingAs($this->admin1)->post(route('admin.orders.change-requests.store', $order->id), [
            'request_type'     => 'edit',
            'invoice_number'   => 'INV-AUD-001-FIX',
            'transaction_date' => '2026-11-10',
            'total_price'      => 9800000,
            'reason'           => 'Penyesuaian tanggal transaksi sesuai rekening koran.',
        ])->assertRedirect();

        $cr = OrderChangeRequest::where('order_id', $order->id)->firstOrFail();
        $this->actingAs($this->admin3)->post(route('admin.change-requests.approve', $cr->id))->assertRedirect();

        $order->refresh();
        $this->assertSame('INV-AUD-001-FIX', $order->invoice_number);
        $this->assertSame('2026-11-10', $order->transaction_date->toDateString());

        $benefit = RoomBenefit::where('order_id', $order->id)->firstOrFail();
        $this->assertSame('2026-11-10', $benefit->started_at->toDateString());
        $this->assertSame('2027-11-10', $benefit->expired_at->toDateString());

        // Admin 3 directly edits and soft-deletes order
        $this->actingAs($this->admin3)->put(route('admin.orders.update', $order->id), [
            'invoice_number'   => 'INV-AUD-001-FIX',
            'transaction_date' => '2026-11-11',
            'service'          => 'pendirian-pt',
            'package'          => 'enterprise',
            'total_price'      => 9900000,
            'payment_method'   => 'Transfer Bank (BCA)',
            'payment_status'   => 'verified',
            'status'           => 'approved',
            'edit_reason'      => 'Koreksi langsung oleh Admin 3.',
        ])->assertRedirect(route('admin.orders.show', $order->id));

        $this->actingAs($this->admin3)->delete(route('admin.orders.destroy', $order->id), [
            'delete_reason' => 'Transaksi dibatalkan klien.',
        ])->assertRedirect(route('admin.orders.index'));

        $deletedOrder = Order::withTrashed()->findOrFail($order->id);
        $this->assertNotNull($deletedOrder->deleted_at);
        $this->assertSame($this->admin3->id, $deletedOrder->deleted_by);
        $this->assertSame('Transaksi dibatalkan klien.', $deletedOrder->delete_reason);

        // Linked benefits deactivated on soft-delete
        $this->assertSame(0, RoomBenefit::where('order_id', $order->id)->where('is_active', true)->count());

        // Full audit logs preserved and viewable by Admin 3
        $auditActions = OrderAuditLog::where('order_id', $order->id)->pluck('action')->toArray();
        $this->assertContains('create', $auditActions);
        $this->assertContains('approve', $auditActions);
        $this->assertContains('update', $auditActions);
        $this->assertContains('delete', $auditActions);

        $this->actingAs($this->admin3)
            ->get(route('admin.orders.show', $order->id))
            ->assertStatus(200)
            ->assertDontSee('Audit Trail Transaksi')
            ->assertDontSee('entri audit log')
            ->assertSee('Koreksi langsung oleh Admin 3.')
            ->assertSee('Transaksi dibatalkan klien.');
    }

    public function test_collapsible_client_data_and_document_edit_upload_work(): void
    {
        Storage::fake('public');

        $order = Order::create([
            'order_number'     => 'ORD-DOC-' . strtoupper(uniqid()),
            'invoice_number'   => 'INV-DOC-001',
            'user_id'          => $this->customer->id,
            'created_by'       => $this->admin1->id,
            'submitted_at'     => now(),
            'transaction_date' => '2026-11-10',
            'service_name'     => 'Pendirian PT Perorangan – Business Package',
            'status'           => 'pending_approval',
            'payment_method'   => 'Virtual Account / QRIS',
            'payment_status'   => 'unpaid',
            'total_price'      => 5800000,
            'form_data'        => [
                'director_name' => 'Firdziyansyah',
                'company_name'  => 'PT Firdzi Maju',
            ],
        ]);

        Storage::disk('public')->put('documents/' . $order->id . '/old_ktp.pdf', 'old');
        $doc = Document::create([
            'order_id'      => $order->id,
            'user_id'       => $this->customer->id,
            'document_type' => 'KTP_DIREKTUR',
            'type'          => 'ktp_direktur',
            'filename'      => 'old_ktp.pdf',
            'original_name' => 'old_ktp.pdf',
            'path'          => 'documents/' . $order->id . '/old_ktp.pdf',
            'status'        => Document::STATUS_APPROVED,
        ]);

        // Detail page renders Data Client collapsed by default and Edit button on document; Admin 1 cannot see Approve/Reject document buttons
        $this->actingAs($this->admin1)
            ->get(route('admin.orders.show', $order->id))
            ->assertStatus(200)
            ->assertSee('data-bs-target="#clientDataCollapse"', false)
            ->assertSee('id="clientDataCollapse" class="collapse"', false)
            ->assertSee('openEditDocumentModal', false)
            ->assertDontSee(route('admin.documents.reject', $doc->id), false)
            ->assertDontSee(route('admin.documents.approve', $doc->id), false);

        // Admin 1 blocked from approving / rejecting / resetting documents (403)
        $this->actingAs($this->admin1)->post(route('admin.documents.approve', $doc->id))->assertStatus(403);
        $this->actingAs($this->admin1)->post(route('admin.documents.reject', $doc->id), ['rejection_reason' => 'Ditolak admin1'])->assertStatus(403);
        $this->actingAs($this->admin1)->post(route('admin.documents.reset', $doc->id))->assertStatus(403);
        $this->actingAs($this->admin1)->post(route('admin.documents.status', $doc->id), ['status' => 'approved'])->assertStatus(403);

        // Admin 3 sees Approve/Reject document buttons and can reject/approve
        $this->actingAs($this->admin3)
            ->get(route('admin.orders.show', $order->id))
            ->assertStatus(200)
            ->assertSee(route('admin.documents.reject', $doc->id), false);
        $this->actingAs($this->admin3)
            ->post(route('admin.documents.reject', $doc->id), ['rejection_reason' => 'Dokumen perlu perbaikan'])
            ->assertRedirect();
        $this->actingAs($this->admin3)
            ->post(route('admin.documents.approve', $doc->id))
            ->assertRedirect();

        // Replace document file via Edit endpoint (status resets to pending, order status preserved)
        $replacementFile = UploadedFile::fake()->create('ktp_revisi.pdf', 120, 'application/pdf');
        $this->actingAs($this->admin1)
            ->put(route('admin.documents.update', $doc->id), [
                'document_type' => 'NPWP_DIREKTUR',
                'file'          => $replacementFile,
                'reason'        => 'Scan KTP lama buram, diganti NPWP.',
            ])
            ->assertRedirect();

        $doc->refresh();
        $order->refresh();
        $this->assertSame('NPWP_DIREKTUR', $doc->document_type);
        $this->assertSame('ktp_revisi.pdf', $doc->original_name);
        $this->assertSame(Document::STATUS_PENDING, $doc->status);
        $this->assertSame('pending_approval', $order->status);
        Storage::disk('public')->assertMissing('documents/' . $order->id . '/old_ktp.pdf');
        Storage::disk('public')->assertExists($doc->path);

        // Upload additional document as Admin 3
        $newFile = UploadedFile::fake()->create('akta_pendirian.pdf', 150, 'application/pdf');
        $this->actingAs($this->admin3)
            ->post(route('admin.documents.store', $order->id), [
                'document_type' => 'KTP_DIREKTUR',
                'file'          => $newFile,
                'reason'        => 'Upload dokumen persyaratan baru.',
            ])
            ->assertRedirect();

        $this->assertSame(2, $order->documents()->count());
        $auditActions = OrderAuditLog::where('order_id', $order->id)->pluck('action')->toArray();
        $this->assertContains('document_update', $auditActions);
        $this->assertContains('document_upload', $auditActions);
    }

    public function test_orders_index_renew_h30_rule_and_vo_notifications_work(): void
    {
        // 1) Validated Virtual Office expiring in 60 days -> NOT renewable (> H-30)
        $txNotDue = Carbon::now()->subYear()->addDays(60)->toDateString();
        $orderNotDue = Order::create([
            'order_number'     => 'ORD-VO-NOTDUE-' . strtoupper(uniqid()),
            'invoice_number'   => 'INV-VO-NOTDUE-' . strtoupper(uniqid()),
            'user_id'          => $this->customer->id,
            'created_by'       => $this->admin1->id,
            'submitted_at'     => now(),
            'transaction_date' => $txNotDue,
            'service_name'     => 'Virtual Office – Business Package',
            'status'           => 'approved',
            'payment_method'   => 'Virtual Account / QRIS',
            'payment_status'   => 'verified',
            'total_price'      => 5800000,
            'form_data'        => ['service' => 'virtual-office', 'package' => 'enterprise'],
        ]);
        RoomBenefit::create([
            'user_id'       => $this->customer->id,
            'order_id'      => $orderNotDue->id,
            'paket'         => 'Business Package',
            'total_minutes' => 2880,
            'used_minutes'  => 0,
            'type'          => 'meeting',
            'is_active'     => true,
            'started_at'    => Carbon::parse($txNotDue)->startOfDay(),
            'expired_at'    => Carbon::now()->startOfDay()->addDays(60),
        ]);

        // 2) Validated Virtual Office at H-30 -> ACTIVE Renew
        $txH30 = Carbon::now()->subYear()->addDays(30)->toDateString();
        $orderH30 = Order::create([
            'order_number'     => 'ORD-VO-H30-' . strtoupper(uniqid()),
            'invoice_number'   => 'INV-VO-H30-' . strtoupper(uniqid()),
            'user_id'          => $this->customer->id,
            'created_by'       => $this->admin1->id,
            'submitted_at'     => now(),
            'transaction_date' => $txH30,
            'service_name'     => 'Virtual Office – Basic Package',
            'status'           => 'approved',
            'payment_method'   => 'Virtual Account / QRIS',
            'payment_status'   => 'verified',
            'total_price'      => 2800000,
            'form_data'        => ['service' => 'virtual-office', 'package' => 'premium'],
        ]);
        RoomBenefit::create([
            'user_id'       => $this->customer->id,
            'order_id'      => $orderH30->id,
            'paket'         => 'Basic Package',
            'total_minutes' => 720,
            'used_minutes'  => 0,
            'type'          => 'meeting',
            'is_active'     => true,
            'started_at'    => Carbon::parse($txH30)->startOfDay(),
            'expired_at'    => Carbon::now()->startOfDay()->addDays(30),
        ]);

        // 3) Pending approval Virtual Office within H-15 -> NOT renewable until approved
        $orderPending = Order::create([
            'order_number'     => 'ORD-VO-PEND-' . strtoupper(uniqid()),
            'invoice_number'   => 'INV-VO-PEND-' . strtoupper(uniqid()),
            'user_id'          => $this->customer->id,
            'created_by'       => $this->admin1->id,
            'submitted_at'     => now(),
            'transaction_date' => Carbon::now()->subYear()->addDays(15)->toDateString(),
            'service_name'     => 'Virtual Office – Basic Package',
            'status'           => 'pending_approval',
            'payment_method'   => 'Virtual Account / QRIS',
            'payment_status'   => 'unpaid',
            'total_price'      => 2800000,
            'form_data'        => ['service' => 'virtual-office', 'package' => 'premium'],
        ]);

        $this->assertFalse($orderNotDue->isRenewable());
        $this->assertTrue($orderH30->isRenewable());
        $this->assertFalse($orderPending->isRenewable());

        // Orders index shows Surat Masuk, Tamu Datang, enabled Renew for H-30, and disabled Renew for >H-30
        $this->actingAs($this->admin1)
            ->get(route('admin.orders.index'))
            ->assertStatus(200)
            ->assertSee('btn-mail-notification', false)
            ->assertSee('btn-guest-notification', false)
            ->assertSee('renew_order_id=' . $orderH30->id, false)
            ->assertDontSee('renew_order_id=' . $orderNotDue->id, false)
            ->assertDontSee('renew_order_id=' . $orderPending->id, false);

        // Direct URL access to renew before H-30 is blocked; at H-30 is allowed
        $this->actingAs($this->admin1)
            ->get(route('admin.orders.create', ['service' => 'virtual-office', 'renew_order_id' => $orderNotDue->id]))
            ->assertRedirect(route('admin.orders.index'));

        $this->actingAs($this->admin1)
            ->get(route('admin.orders.create', ['service' => 'virtual-office', 'renew_order_id' => $orderH30->id]))
            ->assertStatus(200)
            ->assertSee('Mode Renew');

        // Surat Masuk & Tamu Datang AJAX endpoints work for admin1
        $this->actingAs($this->admin1)
            ->postJson(route('admin.virtual-office.mail-notification.store', $orderH30->id), [
                'received_date' => now()->toDateString(),
                'received_time' => '10:30',
                'sender_name'   => 'Kurir JNE',
                'document_type' => 'Surat',
            ])
            ->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->actingAs($this->admin1)
            ->postJson(route('admin.virtual-office.guest-notification.store', $orderH30->id), [
                'guest_name'    => 'Budi Tamu',
                'guest_phone'   => '081234567890',
                'guest_company' => 'PT Tamu',
                'arrival_time'  => '11:00',
                'purpose'       => 'Meeting',
            ])
            ->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    public function test_create_order_without_payment_method_two_payment_statuses_and_duration_bonus_rules(): void
    {
        $spv = User::whereIn('role', ['admin', 'spv'])->first() ?? User::create([
            'name'     => 'Admin SPV Test',
            'email'    => 'test_spv_' . uniqid() . '@example.com',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);

        // Form Create across admin, admin1, admin3 hides Metode Pembayaran, shows duration_months, and has only 2 payment_status options
        foreach ([$spv, $this->admin1, $this->admin3] as $actor) {
            $this->actingAs($actor)
                ->get(route('admin.orders.create'))
                ->assertStatus(200)
                ->assertDontSee('name="payment_method"', false)
                ->assertSee('name="duration_months"', false)
                ->assertSee('<option value="unpaid"', false)
                ->assertSee('<option value="verified"', false)
                ->assertDontSee('<option value="pending_verification"', false)
                ->assertDontSee('<option value="rejected"', false);
        }

        // Reject invalid payment_status on store
        $this->actingAs($this->admin1)
            ->post(route('admin.orders.store'), [
                'user_id'          => $this->customer->id,
                'service'          => 'virtual-office',
                'package'          => 'enterprise',
                'duration_months'  => 12,
                'transaction_date' => '2026-08-01',
                'payment_status'   => 'pending_verification',
            ])
            ->assertSessionHasErrors('payment_status');

        // Case A: < 12 months (6 months) -> clamped to 12 months (1 year), 12-month benefit (48h meeting, 12h podcast)
        $invShort = 'INV-DUR-6-' . strtoupper(uniqid());
        $this->actingAs($this->admin1)->post(route('admin.orders.store'), [
            'user_id'          => $this->customer->id,
            'service'          => 'virtual-office',
            'package'          => 'enterprise',
            'duration_months'  => 6,
            'transaction_date' => '2026-08-01',
            'invoice_number'   => $invShort,
            'payment_status'   => 'verified',
        ])->assertRedirect();

        $orderShort = Order::where('invoice_number', $invShort)->firstOrFail();
        $this->assertNull($orderShort->payment_method);
        $this->assertSame(12, $orderShort->effective_duration_months);
        $this->assertSame(1, $orderShort->benefit_years);
        $this->assertSame(0, $orderShort->bonus_months);
        $this->actingAs($this->admin3)->post(route('admin.orders.approve', $orderShort->id));
        $mShort = RoomBenefit::where('order_id', $orderShort->id)->where('type', 'meeting')->firstOrFail();
        $pShort = RoomBenefit::where('order_id', $orderShort->id)->where('type', 'podcast')->firstOrFail();
        $this->assertSame(2880, $mShort->total_minutes);
        $this->assertSame(720, $pShort->total_minutes);
        $this->assertSame('2027-08-01', $mShort->expired_at->toDateString());

        // Case B: 13 months -> 1 year + 1 month bonus Lawgika; benefit remains 12-month quota (48h / 12h), expires +13 months
        $inv13 = 'INV-DUR-13-' . strtoupper(uniqid());
        $this->actingAs($this->admin3)->post(route('admin.orders.store'), [
            'user_id'          => $this->customer->id,
            'service'          => 'virtual-office',
            'package'          => 'enterprise',
            'duration_months'  => 13,
            'transaction_date' => '2026-08-01',
            'invoice_number'   => $inv13,
            'payment_status'   => 'verified',
            'status'           => 'approved',
        ])->assertRedirect();

        $order13 = Order::where('invoice_number', $inv13)->firstOrFail();
        $this->assertSame(13, $order13->effective_duration_months);
        $this->assertSame(1, $order13->benefit_years);
        $this->assertSame(1, $order13->bonus_months);
        $this->assertSame(5800000, (int) $order13->total_price);
        $m13 = RoomBenefit::where('order_id', $order13->id)->where('type', 'meeting')->firstOrFail();
        $this->assertSame(2880, $m13->total_minutes);
        $this->assertSame('2027-09-01', $m13->expired_at->toDateString());

        // Case C: 24 months -> 2 years (auto 2x annual price & 2x room quota: 96h meeting / 24h podcast)
        $inv24 = 'INV-DUR-24-' . strtoupper(uniqid());
        $this->actingAs($spv)->post(route('admin.orders.store'), [
            'user_id'          => $this->customer->id,
            'service'          => 'virtual-office',
            'package'          => 'enterprise',
            'duration_months'  => 24,
            'transaction_date' => '2026-08-01',
            'invoice_number'   => $inv24,
            'payment_status'   => 'verified',
            'status'           => 'approved',
        ])->assertRedirect();

        $order24 = Order::where('invoice_number', $inv24)->firstOrFail();
        $this->assertSame(24, $order24->effective_duration_months);
        $this->assertSame(2, $order24->benefit_years);
        $this->assertSame(0, $order24->bonus_months);
        $this->assertSame(11600000, (int) $order24->total_price);
        $m24 = RoomBenefit::where('order_id', $order24->id)->where('type', 'meeting')->firstOrFail();
        $p24 = RoomBenefit::where('order_id', $order24->id)->where('type', 'podcast')->firstOrFail();
        $this->assertSame(5760, $m24->total_minutes);
        $this->assertSame(1440, $p24->total_minutes);
        $this->assertSame('2028-08-01', $m24->expired_at->toDateString());

        // Case D: 26 months -> 2 years + 2 months bonus Lawgika; 2-year quota, expires +26 months
        $inv26 = 'INV-DUR-26-' . strtoupper(uniqid());
        $this->actingAs($this->admin3)->post(route('admin.orders.store'), [
            'user_id'          => $this->customer->id,
            'service'          => 'virtual-office',
            'package'          => 'enterprise',
            'duration_months'  => 26,
            'transaction_date' => '2026-08-01',
            'invoice_number'   => $inv26,
            'payment_status'   => 'verified',
            'status'           => 'approved',
        ])->assertRedirect();

        $order26 = Order::where('invoice_number', $inv26)->firstOrFail();
        $this->assertSame(26, $order26->effective_duration_months);
        $this->assertSame(2, $order26->benefit_years);
        $this->assertSame(2, $order26->bonus_months);
        $this->assertSame(11600000, (int) $order26->total_price);
        $m26 = RoomBenefit::where('order_id', $order26->id)->where('type', 'meeting')->firstOrFail();
        $this->assertSame(5760, $m26->total_minutes);
        $this->assertSame('2028-10-01', $m26->expired_at->toDateString());
    }

    public function test_admin3_approve_reject_buttons_stay_visible_until_clicked_and_outer_edit_button_removed(): void
    {
        $inv = 'INV-KEEP-BTN-' . strtoupper(uniqid());
        $this->actingAs($this->admin1)->post(route('admin.orders.store'), [
            'user_id'          => $this->customer->id,
            'service'          => 'pendirian-pt',
            'package'          => 'enterprise',
            'duration_months'  => 12,
            'transaction_date' => '2026-11-10',
            'invoice_number'   => $inv,
            'payment_status'   => 'verified',
        ])->assertRedirect();

        $order = Order::where('invoice_number', $inv)->firstOrFail();

        $doc = Document::create([
            'order_id'      => $order->id,
            'user_id'       => $this->customer->id,
            'service_id'    => $order->service_id,
            'document_type' => 'KTP_DIREKTUR',
            'type'          => 'ktp_direktur',
            'filename'      => 'ktp.pdf',
            'original_name' => 'ktp.pdf',
            'path'          => 'documents/ktp.pdf',
            'status'        => Document::STATUS_PENDING,
        ]);

        // Admin 3 approves/rejects/resets individual document before approving transaction -> order stays pending_approval
        $this->actingAs($this->admin3)->post(route('admin.documents.approve', $doc->id))->assertRedirect();
        $this->assertSame('pending_approval', $order->fresh()->status);

        $this->actingAs($this->admin3)->post(route('admin.documents.reject', $doc->id), ['rejection_reason' => 'Cek ulang berkas'])->assertRedirect();
        $this->assertSame('pending_approval', $order->fresh()->status);

        $this->actingAs($this->admin3)->post(route('admin.documents.approve', $doc->id))->assertRedirect();
        $this->assertSame('pending_approval', $order->fresh()->status);

        // Approve & Reject buttons remain visible on index and detail; outer Edit button is absent
        $this->actingAs($this->admin3)
            ->get(route('admin.orders.index'))
            ->assertStatus(200)
            ->assertDontSee(route('admin.orders.edit', $order->id), false)
            ->assertSee(route('admin.orders.approve', $order->id), false)
            ->assertSee('rejectOrderModal' . $order->id, false);

        $this->actingAs($this->admin3)
            ->get(route('admin.orders.show', $order->id))
            ->assertStatus(200)
            ->assertSee(route('admin.orders.approve', $order->id), false)
            ->assertSee('rejectOrderDetailModal', false);

        // Edge case: unapproved order with status='verified' (approved_at=null) still shows Approve/Reject until clicked
        $order->update(['status' => 'verified', 'approved_at' => null]);
        $this->assertTrue($order->fresh()->needsTransactionApproval());
        $this->assertFalse($order->fresh()->isValidated());

        $this->actingAs($this->admin3)
            ->get(route('admin.orders.show', $order->id))
            ->assertStatus(200)
            ->assertSee(route('admin.orders.approve', $order->id), false)
            ->assertSee('rejectOrderDetailModal', false);

        // After Admin 3 clicks Approve Transaksi, buttons disappear and benefit activates
        $this->actingAs($this->admin3)->post(route('admin.orders.approve', $order->id))->assertRedirect();
        $order->refresh();
        $this->assertSame('approved', $order->status);
        $this->assertFalse($order->needsTransactionApproval());
        $this->assertTrue($order->isValidated());

        $this->actingAs($this->admin3)
            ->get(route('admin.orders.show', $order->id))
            ->assertStatus(200)
            ->assertDontSee(route('admin.orders.approve', $order->id), false)
            ->assertDontSee('data-bs-target="#rejectOrderDetailModal"', false);
    }
}
