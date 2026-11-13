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
            'payment_status'   => 'pending_verification',
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

        // Detail page renders Data Client collapsed by default and Edit button on document
        $this->actingAs($this->admin1)
            ->get(route('admin.orders.show', $order->id))
            ->assertStatus(200)
            ->assertSee('data-bs-target="#clientDataCollapse"', false)
            ->assertSee('id="clientDataCollapse" class="collapse"', false)
            ->assertSee('openEditDocumentModal', false);

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
}
