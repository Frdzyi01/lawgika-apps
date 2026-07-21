<?php

namespace App\Services;

use App\Models\Correspondence;
use App\Models\MeetingRoomBooking;
use App\Models\Order;
use App\Models\PodcastRoomBooking;
use App\Models\RoomBenefit;
use App\Models\WhatsappLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * WhatsAppService
 *
 * Isolated service layer for WhatsApp notifications via Fonnte API.
 * Contains ALL message-building logic — controllers remain thin.
 *
 * Rules:
 *  - Only called after a successful DB commit
 *  - If sending fails, data is already saved — only log status = FAILED
 *  - Phone number validated before sending
 */
class WhatsAppService
{
    // ── Core: Send ────────────────────────────────────────────────────────────

    /**
     * Send a WhatsApp message via Fonnte API and log the result.
     *
     * @return WhatsappLog|null  Null if phone is empty/invalid
     */
    public function send(string $phone, string $message, ?int $clientId = null, ?int $orderId = null): ?WhatsappLog
    {
        // ── Guard: empty phone ────────────────────────────────────────────────
        $phone = $this->normalizePhone($phone);
        if (empty($phone)) {
            Log::warning('WhatsAppService: Nomor telepon kosong, WA tidak dikirim.', [
                'client_id' => $clientId,
                'order_id'  => $orderId,
            ]);
            return null;
        }

        // ── Guard: no token configured ────────────────────────────────────────
        $token = config('services.fonnte.token');
        if (empty($token)) {
            Log::warning('WhatsAppService: FONNTE_TOKEN belum dikonfigurasi.');
            return WhatsappLog::create([
                'client_id'    => $clientId,
                'order_id'     => $orderId,
                'phone_number' => $phone,
                'message'      => $message,
                'status'       => WhatsappLog::STATUS_FAILED,
                'response'     => 'FONNTE_TOKEN not configured',
            ]);
        }

        // ── Send via Fonnte API ───────────────────────────────────────────────
        try {
            $response = Http::withHeaders([
                'Authorization' => $token,
            ])->post(config('services.fonnte.url'), [
                'target'  => $phone,
                'message' => $message,
            ]);

            $body   = $response->body();
            $status = $response->successful() ? WhatsappLog::STATUS_SUCCESS : WhatsappLog::STATUS_FAILED;

            // Fonnte returns JSON with "status" field — double-check
            $json = $response->json();
            if (isset($json['status']) && $json['status'] === false) {
                $status = WhatsappLog::STATUS_FAILED;
            }

            return WhatsappLog::create([
                'client_id'    => $clientId,
                'order_id'     => $orderId,
                'phone_number' => $phone,
                'message'      => $message,
                'status'       => $status,
                'response'     => $body,
            ]);
        } catch (\Exception $e) {
            Log::error('WhatsAppService: Gagal mengirim WA.', [
                'error'     => $e->getMessage(),
                'client_id' => $clientId,
                'order_id'  => $orderId,
            ]);

            return WhatsappLog::create([
                'client_id'    => $clientId,
                'order_id'     => $orderId,
                'phone_number' => $phone,
                'message'      => $message,
                'status'       => WhatsappLog::STATUS_FAILED,
                'response'     => $e->getMessage(),
            ]);
        }
    }

    // ── Public: Notify Methods ────────────────────────────────────────────────

    /**
     * Notify client when admin creates a new Order (Pendirian PT, Virtual Office, dll).
     */
    public function notifyOrderCreated(Order $order): ?WhatsappLog
    {
        $order->loadMissing(['user', 'roomBenefits']);

        $phone = $order->user->phone ?? ($order->form_data['pic_phone'] ?? null);
        if (empty($phone)) return null;

        $message = $this->buildOrderMessage($order);
        $clientName = $order->user->pic_name ?? $order->user->name ?? 'Client';

        return $this->send($phone, $message, $order->user_id, $order->id);
    }

    /**
     * Notify client when admin creates a Meeting Room reservation.
     */
    public function notifyMeetingRoomCreated(MeetingRoomBooking $booking): ?WhatsappLog
    {
        $booking->loadMissing('user');

        $phone = $booking->user->phone ?? null;
        if (empty($phone)) return null;

        $message = $this->buildMeetingRoomMessage($booking);

        return $this->send($phone, $message, $booking->user_id);
    }

    /**
     * Notify client when admin creates a Podcast Room reservation.
     */
    public function notifyPodcastRoomCreated(PodcastRoomBooking $booking): ?WhatsappLog
    {
        $booking->loadMissing('user');

        $phone = $booking->user->phone ?? null;
        if (empty($phone)) return null;

        $message = $this->buildPodcastRoomMessage($booking);

        return $this->send($phone, $message, $booking->user_id);
    }

    /**
     * Notify client when admin creates a Correspondence (Surat Menyurat).
     */
    public function notifyCorrespondenceCreated(Correspondence $correspondence): ?WhatsappLog
    {
        $correspondence->loadMissing('user');

        $phone = $correspondence->user->phone ?? null;
        if (empty($phone)) return null;

        $message = $this->buildCorrespondenceMessage($correspondence);

        return $this->send($phone, $message, $correspondence->user_id);
    }

    // ── Private: Message Builders ─────────────────────────────────────────────

    private function buildOrderMessage(Order $order): string
    {
        $clientName  = $order->user->pic_name ?? $order->user->name ?? 'Client';
        $formData    = $order->form_data ?? [];
        $packageKey  = $formData['package'] ?? '';
        $packageLabel = \App\Http\Controllers\UniversalOrderController::$packages[$packageKey]
            ?? \Illuminate\Support\Str::title(str_replace('-', ' ', $packageKey));
        $serviceLabel = \App\Http\Controllers\UniversalOrderController::$services[$formData['service'] ?? '']['label']
            ?? \Illuminate\Support\Str::title(str_replace('-', ' ', $formData['service'] ?? ''));
        $statusLabel = Order::STATUS_MAP[$order->status]['label'] ?? ucfirst($order->status);

        $lines = [
            "Halo Bapak/Ibu {$clientName},",
            "",
            "Terima kasih telah mempercayakan Lawgika.",
            "",
            "Pesanan Anda telah berhasil dibuat.",
            "",
            "━━━━━━━━━━━━━━━━━━",
            "",
            "Nomor Order",
            $order->order_number,
            "",
            "Layanan",
            $serviceLabel,
            "",
            "Paket",
            $packageLabel,
            "",
            "Total Pembayaran",
            "Rp " . number_format($order->total_price, 0, ',', '.'),
            "",
            "Status",
            $statusLabel,
        ];

        // ── Benefit section (from database) ───────────────────────────────────
        $benefits = $order->roomBenefits;
        if ($benefits && $benefits->isNotEmpty()) {
            $lines[] = "";
            $lines[] = "=================================";
            $lines[] = "";
            $lines[] = "Benefit Anda";
            $lines[] = "";

            foreach ($benefits as $benefit) {
                $typeLabel = $benefit->type === 'meeting' ? 'Meeting Room' : 'Podcast Room';
                $hours     = (int) ($benefit->total_minutes / 60);
                $lines[]   = "{$typeLabel}";
                $lines[]   = "{$hours} Jam / Tahun";
                $lines[]   = "";
            }

            $lines[] = "=================================";
        }

        $lines[] = "";
        $lines[] = "Silakan login ke Dashboard Client untuk melihat perkembangan pesanan.";
        $lines[] = "";
        $lines[] = "Terima kasih.";
        $lines[] = "Lawgika.co.id";

        return implode("\n", $lines);
    }

    private function buildMeetingRoomMessage(MeetingRoomBooking $booking): string
    {
        $clientName = $booking->user->pic_name ?? $booking->user->name ?? $booking->name;
        $tanggal    = $booking->date ? \Carbon\Carbon::parse($booking->date)->format('d M Y') : '-';
        $jam        = $booking->start_time ? \Carbon\Carbon::parse($booking->start_time)->format('H:i') : '-';

        $lines = [
            "Halo Bapak/Ibu {$clientName},",
            "",
            "Reservasi Meeting Room berhasil dibuat.",
            "",
            "━━━━━━━━━━━━━━━━━━",
            "",
            "Tanggal: {$tanggal}",
            "Waktu: {$jam}",
            "Ruangan: " . ($booking->room_name ?? 'Meeting Room'),
            "Paket: Meeting Room Package ({$booking->duration} Jam)",
            "Jumlah Peserta: " . ($booking->participants ?? 1) . " Orang",
            "Status: " . ucfirst($booking->status),
            "Catatan: " . ($booking->notes ?? '-'),
            "",
            "━━━━━━━━━━━━━━━━━━",
            "",
            "Silakan login ke Dashboard Client untuk detail selengkapnya.",
            "",
            "Terima kasih.",
            "Lawgika.co.id",
        ];

        return implode("\n", $lines);
    }

    private function buildPodcastRoomMessage(PodcastRoomBooking $booking): string
    {
        $clientName = $booking->user->pic_name ?? $booking->user->name ?? $booking->name;
        $tanggal    = $booking->date ? \Carbon\Carbon::parse($booking->date)->format('d M Y') : '-';

        $lines = [
            "Halo Bapak/Ibu {$clientName},",
            "",
            "Reservasi Ruang Podcast berhasil dibuat.",
            "",
            "━━━━━━━━━━━━━━━━━━",
            "",
            "Tanggal: {$tanggal}",
            "Ruangan: " . ($booking->room_name ?? 'Ruang Podcastroom Utama'),
            "Durasi: {$booking->duration} Jam",
            "Status: " . ucfirst($booking->status),
            "Catatan: " . ($booking->notes ?? '-'),
            "",
            "━━━━━━━━━━━━━━━━━━",
            "",
            "Silakan login ke Dashboard Client untuk detail selengkapnya.",
            "",
            "Terima kasih.",
            "Lawgika.co.id",
        ];

        return implode("\n", $lines);
    }

    private function buildCorrespondenceMessage(Correspondence $correspondence): string
    {
        $clientName = $correspondence->user->pic_name ?? $correspondence->user->name ?? 'Client';
        $statusLabel = match($correspondence->status) {
            'pending' => 'Menunggu',
            'replied' => 'Dibalas',
            'done'    => 'Selesai',
            default   => ucfirst($correspondence->status),
        };

        $lines = [
            "Halo Bapak/Ibu {$clientName},",
            "",
            "Surat baru telah dikirimkan kepada Anda.",
            "",
            "━━━━━━━━━━━━━━━━━━",
            "",
            "Jenis Surat: {$correspondence->title}",
            "Status: {$statusLabel}",
            "",
            "━━━━━━━━━━━━━━━━━━",
            "",
            "Silakan login ke Dashboard Client untuk melihat dokumen surat.",
            "",
            "Terima kasih.",
            "Lawgika.co.id",
        ];

        return implode("\n", $lines);
    }

    // ── Private: Helpers ──────────────────────────────────────────────────────

    /**
     * Normalize phone number to Indonesian format (62xxx).
     */
    private function normalizePhone(?string $phone): string
    {
        if (empty($phone)) return '';

        // Strip non-digit characters
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Convert leading 0 to 62
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }

        // Already starts with 62? Keep as is. Otherwise prepend 62.
        if (!str_starts_with($phone, '62')) {
            $phone = '62' . $phone;
        }

        return $phone;
    }
}
