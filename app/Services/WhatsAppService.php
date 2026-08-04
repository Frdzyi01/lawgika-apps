<?php

namespace App\Services;

use App\Models\Correspondence;
use App\Models\MeetingRoomBooking;
use App\Models\Order;
use App\Models\PodcastRoomBooking;
use App\Models\RoomBenefit;
use App\Models\UserRoomQuota;
use App\Models\VirtualOfficeMailNotification;
use App\Models\VirtualOfficeGuestNotification;
use App\Models\WhatsappLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * WhatsAppService
 *
 * Isolated service layer for WhatsApp notifications.
 * Contains ALL message-building logic — controllers remain thin.
 *
 * Supports two sending modes:
 *  1. Pancake Plain Text  — send() via action: reply_inbox
 *  2. Botcake Official WABA API — sendTemplateById() via POST /pages/{page_id}/flows/send_content
 *
 * Rules:
 *  - Only called after a successful DB commit
 *  - If sending fails, data is already saved — only log status = FAILED
 *  - Phone number validated before sending
 *
 * Botcake Official WABA API (Template Message):
 *  - Endpoint: POST https://botcake.io/api/public_api/v1/pages/{page_id}/flows/send_content
 *  - Auth: Header 'access-token' (JWT token)
 *  - PSID: wa_628xxx (otomatis diformat dari nomor HP)
 *  - Body: { psid, data: { version: "v2", content: { messages: [{ type, template_id, language, category, components }] } } }
 *
 * Pancake API (Plain Text — legacy, tetap dipertahankan untuk non-template):
 *  - Endpoint: POST {base_url}/pages/{page_id}/conversations/{conv_id}/messages
 *  - Auth: page_access_token as query parameter
 *  - Body: { "action": "reply_inbox", "message": "..." }
 */
class WhatsAppService
{
    // ── Core: Send ────────────────────────────────────────────────────────────

    /**
     * Send a WhatsApp message via Pancake API and log the result.
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
        $token  = config('services.pancake.page_access_token');
        $pageId = config('services.pancake.page_id');
        if (empty($token) || empty($pageId)) {
            Log::warning('WhatsAppService: PANCAKE_PAGE_ACCESS_TOKEN atau PANCAKE_PAGE_ID belum dikonfigurasi.');
            return WhatsappLog::create([
                'client_id'    => $clientId,
                'order_id'     => $orderId,
                'phone_number' => $phone,
                'message'      => $message,
                'status'       => WhatsappLog::STATUS_FAILED,
                'response'     => 'PANCAKE credentials not configured',
            ]);
        }

        // ── Build Pancake conversation ID (auto-create format) ────────────────
        $conversationId = $pageId . '_' . $phone;
        $baseUrl = config('services.pancake.base_url', 'https://pages.fm/api/public_api/v1');
        $url = "{$baseUrl}/pages/{$pageId}/conversations/{$conversationId}/messages";

        // ── Send via Pancake API ──────────────────────────────────────────────
        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post($url . '?' . http_build_query(['page_access_token' => $token]), [
                'action'  => 'reply_inbox',
                'message' => $message,
            ]);

            $body   = $response->body();
            $json   = $response->json();
            $status = ($response->successful() && ($json['success'] ?? false))
                ? WhatsappLog::STATUS_SUCCESS
                : WhatsappLog::STATUS_FAILED;

            return WhatsappLog::create([
                'client_id'    => $clientId,
                'order_id'     => $orderId,
                'phone_number' => $phone,
                'message'      => $message,
                'status'       => $status,
                'response'     => $body,
            ]);
        } catch (\Exception $e) {
            Log::error('WhatsAppService: Gagal mengirim WA via Pancake.', [
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

    /**
     * Send a WhatsApp Template Message via Botcake Official WABA API.
     *
     * Endpoint: POST https://botcake.io/api/public_api/v1/pages/{page_id}/flows/send_content
     * Auth: Header 'access-token' (JWT)
     * PSID: wa_628xxx (otomatis diformat dari nomor HP)
     *
     * Payload mengikuti 100% spesifikasi OpenAPI Botcake (document.json).
     *
     * @param  string   $phone        Nomor HP penerima (otomatis diformat ke PSID wa_628xxx)
     * @param  string   $templateId   Template ID numerik dari Botcake/Meta (bukan template_name)
     * @param  string   $category     Category template: 'UTILITY' | 'MARKETING' | 'AUTHENTICATION'
     * @param  array    $parameters   Array parameter body berurutan [{{1}}, {{2}}, ...]
     * @param  int|null $clientId     Untuk logging ke whatsapp_logs
     * @param  int|null $orderId      Untuk logging ke whatsapp_logs
     * @param  string   $language     Language code (default: 'id')
     * @return WhatsappLog|null       Null jika phone kosong
     */
    public function sendTemplateById(
        string $phone,
        string $templateId,
        string $category,
        array $parameters,
        ?int $clientId = null,
        ?int $orderId = null,
        string $language = 'id'
    ): ?WhatsappLog {
        // ── Guard: empty phone ────────────────────────────────────────────────
        $phone = $this->normalizePhone($phone);
        if (empty($phone)) {
            Log::warning('WhatsAppService::sendTemplateById - Nomor telepon kosong, template tidak dikirim.', [
                'client_id'   => $clientId,
                'template_id' => $templateId,
            ]);
            return null;
        }

        // ── Guard: template_id kosong ──────────────────────────────────────────
        if (empty($templateId)) {
            Log::error('WhatsAppService::sendTemplateById - template_id kosong.', [
                'phone' => $phone,
            ]);
            return WhatsappLog::create([
                'client_id'    => $clientId,
                'order_id'     => $orderId,
                'phone_number' => $phone,
                'message'      => '[TEMPLATE] template_id kosong',
                'status'       => WhatsappLog::STATUS_FAILED,
                'response'     => 'template_id is empty',
            ]);
        }

        // ── Read config ───────────────────────────────────────────────────────
        $accessToken = config('services.botcake.access_token');
        $apiUrl      = config('services.botcake.api_url', 'https://botcake.io/api/public_api/v1');
        $pageId      = config('services.botcake.page_id');

        // ── Validation: Meta 1024 Character Body Limit Validator ──────────────
        $templateText = $this->getTemplateBodyText((string)$templateId);
        
        $renderedText = '';
        if (!empty($templateText)) {
            $renderedText = $templateText;
            foreach ($parameters as $index => $val) {
                $paramNumber = is_int($index) ? (string)($index + 1) : (string)$index;
                $renderedText = str_replace('{{' . $paramNumber . '}}', (string)$val, $renderedText);
            }
        } else {
            // Fallback jika template text tidak terdaftar
            $paramTotalLength = array_reduce($parameters, fn($carry, $item) => $carry + mb_strlen((string)$item), 0);
            $renderedText = str_repeat('x', $paramTotalLength);
        }

        $bodyLength     = mb_strlen($renderedText);
        $allowedLength  = 1024;
        $remainingChars = $allowedLength - $bodyLength;

        // ── Build log message ─────────────────────────────────────────────────
        $logMessage = "[TEMPLATE ID: {$templateId}] Params: " . json_encode($parameters, JSON_UNESCAPED_UNICODE) . " | Body Length: {$bodyLength}/{$allowedLength} (Remaining: {$remainingChars})";

        // ── Guard: Meta 1024 Character Limit Exceeded ─────────────────────────
        if ($bodyLength > $allowedLength) {
            $reason = "Body template melebihi batas Meta (1024 karakter).";

            Log::error('WhatsAppService::sendTemplateById - Meta 1024 Limit Exceeded (ABORTED SENDING)', [
                'template_id'          => $templateId,
                'body_length'          => $bodyLength,
                'allowed_length'       => $allowedLength,
                'remaining_characters' => $remainingChars,
                'phone'                => $phone,
                'reason'               => $reason,
            ]);

            return WhatsappLog::create([
                'client_id'    => $clientId,
                'order_id'     => $orderId,
                'phone_number' => $phone,
                'message'      => $logMessage,
                'status'       => WhatsappLog::STATUS_FAILED,
                'response'     => "{$reason} (Length: {$bodyLength}/{$allowedLength}, Remaining: {$remainingChars})",
            ]);
        }

        // ── Guard: access_token kosong ─────────────────────────────────────────
        if (empty($accessToken)) {
            Log::error('WhatsAppService::sendTemplateById - BOTCAKE_ACCESS_TOKEN belum dikonfigurasi.', [
                'template_id' => $templateId,
                'phone'       => $phone,
            ]);
            return WhatsappLog::create([
                'client_id'    => $clientId,
                'order_id'     => $orderId,
                'phone_number' => $phone,
                'message'      => $logMessage,
                'status'       => WhatsappLog::STATUS_FAILED,
                'response'     => 'BOTCAKE_ACCESS_TOKEN not configured',
            ]);
        }

        // ── Guard: page_id kosong ──────────────────────────────────────────────
        if (empty($pageId)) {
            Log::error('WhatsAppService::sendTemplateById - BOTCAKE_PAGE_ID belum dikonfigurasi.', [
                'template_id' => $templateId,
            ]);
            return WhatsappLog::create([
                'client_id'    => $clientId,
                'order_id'     => $orderId,
                'phone_number' => $phone,
                'message'      => $logMessage,
                'status'       => WhatsappLog::STATUS_FAILED,
                'response'     => 'BOTCAKE_PAGE_ID not configured',
            ]);
        }

        // ── Build PSID ────────────────────────────────────────────────────────
        $devPhone = env('DEV_OVERRIDE_PHONE');
        if (!empty($devPhone)) {
            $phone = $devPhone;
        }

        $psid = $this->formatPsid($phone);

        // ── Build Endpoint URL ────────────────────────────────────────────────
        $url = rtrim($apiUrl, '/') . "/pages/{$pageId}/flows/send_content";

        // ── Format body params sesuai OpenAPI Botcake ──────────────────────────
        // Setiap param: { "key": "{{1}}", "parameter_name": "1", "value": "..." }
        $formattedParams = [];
        foreach ($parameters as $index => $val) {
            $paramNumber = is_int($index) ? (string)($index + 1) : (string)$index;
            $formattedParams[] = [
                'key'            => '{{' . $paramNumber . '}}',
                'parameter_name' => $paramNumber,
                'value'          => (string)$val,
            ];
        }

        // ── Build Payload 100% sesuai OpenAPI spec ────────────────────────────
        $payload = [
            'psid' => $psid,
            'data' => [
                'version' => 'v2',
                'content' => [
                    'messages' => [
                        [
                            'type'        => 'whatsapp_message_template',
                            'template_id' => (string)$templateId,
                            'language'    => $language,
                            'category'    => $category,
                            'components'  => [
                                [
                                    'type'   => 'BODY',
                                    'params' => $formattedParams,
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        // ── HTTP Headers sesuai OpenAPI spec ───────────────────────────────────
        $headers = [
            'Accept'       => 'application/json',
            'Content-Type' => 'application/json',
            'access-token' => $accessToken,
        ];

        // ── Send Request & Measure Execution Time ─────────────────────────────
        $startTime = microtime(true);

        try {
            $response     = Http::withHeaders($headers)->post($url, $payload);
            $responseTime = round((microtime(true) - $startTime) * 1000, 2);

            $statusCode = $response->status();
            $rawBody    = $response->body();
            $json       = $response->json();

            $isSuccess = $response->successful() && ($json['success'] ?? false) === true;
            $status    = $isSuccess ? WhatsappLog::STATUS_SUCCESS : WhatsappLog::STATUS_FAILED;

            // ── Comprehensive Logging ─────────────────────────────────────────
            Log::info('WhatsAppService::sendTemplateById - Botcake WABA API Call', [
                'url'            => $url,
                'method'         => 'POST',
                'headers'        => [
                    'Accept'       => 'application/json',
                    'Content-Type' => 'application/json',
                    'access-token' => substr($accessToken, 0, 20) . '...[MASKED]',
                ],
                'payload'        => $payload,
                'template_id'    => $templateId,
                'psid'           => $psid,
                'phone'          => $phone,
                'status_code'    => $statusCode,
                'execution_time' => $responseTime . ' ms',
                'raw_response'   => $rawBody,
                'is_success'     => $isSuccess,
            ]);

            if (!$isSuccess) {
                Log::error('WhatsAppService::sendTemplateById - Botcake WABA API FAILED', [
                    'template_id'    => $templateId,
                    'psid'           => $psid,
                    'phone'          => $phone,
                    'status_code'    => $statusCode,
                    'execution_time' => $responseTime . ' ms',
                    'response'       => $json ?? $rawBody,
                ]);
            }

            // ── Simpan raw response mentah ke whatsapp_logs ───────────────────
            return WhatsappLog::create([
                'client_id'    => $clientId,
                'order_id'     => $orderId,
                'phone_number' => $phone,
                'message'      => $logMessage,
                'status'       => $status,
                'response'     => $rawBody,
            ]);
        } catch (\Exception $e) {
            $responseTime = round((microtime(true) - $startTime) * 1000, 2);

            Log::error('WhatsAppService::sendTemplateById - Exception', [
                'error'          => $e->getMessage(),
                'template_id'    => $templateId,
                'psid'           => $psid ?? $this->formatPsid($phone),
                'phone'          => $phone,
                'execution_time' => $responseTime . ' ms',
            ]);

            return WhatsappLog::create([
                'client_id'    => $clientId,
                'order_id'     => $orderId,
                'phone_number' => $phone,
                'message'      => $logMessage,
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
     *
     * Menggunakan WhatsApp Template Message via Botcake API.
     * Template: meeting_room_booking_confirmation (6 parameter)
     *
     * Tugas method ini:
     *  1. Mengambil data booking dari database
     *  2. Mengambil data client dari relasi user
     *  3. Menghitung sisa kuota meeting room
     *  4. Menyusun parameter berurutan
     *  5. Memanggil sendTemplate()
     */
    public function notifyMeetingRoomCreated(MeetingRoomBooking $booking): ?WhatsappLog
    {
        $booking->loadMissing(['user', 'benefit']);

        $phone = $booking->user->phone ?? null;
        if (empty($phone)) {
            Log::warning('WhatsAppService::notifyMeetingRoomCreated - Nomor telepon kosong.', [
                'booking_id' => $booking->id,
                'user_id'    => $booking->user_id,
            ]);
            return null;
        }

        // ── Prepare 6 template parameters ─────────────────────────────────────
        $clientName = $booking->user->pic_name
            ?? $booking->user->name
            ?? $booking->name
            ?? 'Client';

        $roomName = $booking->room_name ?? 'Meeting Room';

        $tanggal = $booking->date
            ? \Carbon\Carbon::parse($booking->date)->format('d M Y')
            : '-';

        $jamMulai = $booking->start_time
            ? \Carbon\Carbon::parse($booking->start_time)->format('H:i')
            : '-';

        // end_time: gunakan DB value, atau hitung dari start_time + 1 jam
        $jamSelesai = '-';
        if ($booking->end_time) {
            $jamSelesai = \Carbon\Carbon::parse($booking->end_time)->format('H:i');
        } elseif ($booking->start_time) {
            $jamSelesai = \Carbon\Carbon::parse($booking->start_time)->addHour()->format('H:i');
        }

        $sisaKuota = $this->calculateMeetingRoomRemainingQuota($booking);

        // ── Validate: log warning jika ada parameter kosong ───────────────────
        $paramLabels = [
            '{{1}} clientName'  => $clientName,
            '{{2}} roomName'    => $roomName,
            '{{3}} tanggal'     => $tanggal,
            '{{4}} jamMulai'    => $jamMulai,
            '{{5}} jamSelesai'  => $jamSelesai,
            '{{6}} sisaKuota'   => $sisaKuota,
        ];

        foreach ($paramLabels as $label => $value) {
            if (empty($value) || $value === '-') {
                Log::warning('WhatsAppService::notifyMeetingRoomCreated - Parameter template kosong/default.', [
                    'parameter'  => $label,
                    'value'      => $value,
                    'booking_id' => $booking->id,
                ]);
            }
        }

        // ── Kirim via Botcake Official WABA API ────────────────────────────────
        $templateId = config('services.botcake.templates.meeting_room_confirmation');

        return $this->sendTemplateById(
            $phone,
            $templateId,
            'UTILITY',
            [
                $clientName,   // {{1}} Nama Client
                $roomName,     // {{2}} Nama Ruangan
                $tanggal,      // {{3}} Tanggal Meeting
                $jamMulai,     // {{4}} Jam Mulai
                $jamSelesai,   // {{5}} Jam Selesai
                $sisaKuota,    // {{6}} Sisa Kuota
            ],
            $booking->user_id
        );
    }

    /**
     * Notify client when admin creates a Podcast Room reservation.
     *
     * Menggunakan WhatsApp Template Message via Botcake API.
     * Template: podcast_room_booking_confirmation (4 parameter)
     */
    public function notifyPodcastRoomCreated(PodcastRoomBooking $booking): ?WhatsappLog
    {
        $booking->loadMissing('user');

        $phone = $booking->user->phone ?? null;
        if (empty($phone)) {
            Log::warning('WhatsAppService::notifyPodcastRoomCreated - Nomor telepon kosong.', [
                'booking_id' => $booking->id,
                'user_id'    => $booking->user_id,
            ]);
            return null;
        }

        // ── Prepare 4 template parameters ─────────────────────────────────────
        $clientName = $booking->user->pic_name
            ?? $booking->user->name
            ?? $booking->name
            ?? 'Client';

        $tanggal = $booking->date
            ? \Carbon\Carbon::parse($booking->date)->format('d M Y')
            : '-';

        $jamMulai = $booking->start_time
            ? \Carbon\Carbon::parse($booking->start_time)->format('H:i')
            : '-';

        $jamSelesai = '-';
        if ($booking->end_time) {
            $jamSelesai = \Carbon\Carbon::parse($booking->end_time)->format('H:i');
        } elseif ($booking->start_time) {
            $jamSelesai = \Carbon\Carbon::parse($booking->start_time)->addHour()->format('H:i');
        }

        // ── Validate: log warning jika ada parameter kosong ───────────────────
        $paramLabels = [
            '{{1}} clientName' => $clientName,
            '{{2}} tanggal'    => $tanggal,
            '{{3}} jamMulai'   => $jamMulai,
            '{{4}} jamSelesai'  => $jamSelesai,
        ];

        foreach ($paramLabels as $label => $value) {
            if (empty($value) || $value === '-') {
                Log::warning('WhatsAppService::notifyPodcastRoomCreated - Parameter template kosong/default.', [
                    'parameter'  => $label,
                    'value'      => $value,
                    'booking_id' => $booking->id,
                ]);
            }
        }

        // ── Kirim via Botcake Official WABA API ────────────────────────────────
        $templateId = config('services.botcake.templates.podcast_room_confirmation');

        return $this->sendTemplateById(
            $phone,
            $templateId,
            'UTILITY',
            [
                $clientName,   // {{1}} Nama Client
                $tanggal,      // {{2}} Tanggal Penggunaan
                $jamMulai,     // {{3}} Jam Mulai
                $jamSelesai,   // {{4}} Jam Selesai
            ],
            $booking->user_id
        );
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

    /**
     * Notify client when admin/system checks in a Meeting Room reservation.
     * Menggunakan WhatsApp Template Message via Botcake Official WABA API.
     * Template: meeting_room_booking_confirmation (ID: 1732248697805244)
     */
    public function notifyMeetingRoomCheckIn(MeetingRoomBooking $booking): ?WhatsappLog
    {
        return $this->notifyMeetingRoomCreated($booking);
    }

    /**
     * Notify client when admin/system checks in a Podcast Room reservation.
     * Menggunakan WhatsApp Template Message via Botcake Official WABA API.
     * Template: podcast_room_booking_confirmation (ID: 1827038834946958)
     */
    public function notifyPodcastRoomCheckIn(PodcastRoomBooking $booking): ?WhatsappLog
    {
        return $this->notifyPodcastRoomCreated($booking);
    }

    /**
     * Notify client when admin/system checks out a Meeting Room reservation.
     *
     * Menggunakan WhatsApp Template Message via Botcake API.
     * Template: meeting_room_checkout (6 parameter)
     */
    public function notifyMeetingRoomCheckOut(MeetingRoomBooking $booking, string $actualDuration, int $billingHours, $checkinAt, $checkoutAt): ?WhatsappLog
    {
        $booking->loadMissing(['user', 'benefit']);

        $phone = $booking->user->phone ?? $booking->phone ?? env('DEV_OVERRIDE_PHONE') ?? null;
        if (empty($phone)) {
            Log::warning('WhatsAppService::notifyMeetingRoomCheckOut - Nomor telepon kosong.', [
                'booking_id' => $booking->id,
                'user_id'    => $booking->user_id,
            ]);
            return null;
        }

        // ── Prepare 6 template parameters ─────────────────────────────────────
        $clientName = $booking->user->pic_name
            ?? $booking->user->name
            ?? $booking->name
            ?? 'Client';

        $roomName = $booking->room_name ?? 'Meeting Room';

        // Tanggal: ambil dari tanggal yang sama dengan checkin ($booking->date)
        $tanggal = $booking->date
            ? \Carbon\Carbon::parse($booking->date)->format('d M Y')
            : ($checkinAt ? \Carbon\Carbon::parse($checkinAt)->format('d M Y') : ($checkoutAt ? \Carbon\Carbon::parse($checkoutAt)->format('d M Y') : '-'));

        // Jam Mulai: ambil dari jam mulai yang diisi saat checkin ($booking->start_time)
        $jamMulai = $booking->start_time
            ? \Carbon\Carbon::parse($booking->start_time)->format('H:i')
            : ($checkinAt ? \Carbon\Carbon::parse($checkinAt)->format('H:i') : '-');

        // Jam Selesai: ambil dari waktu admin klik checkout ($checkoutAt)
        $jamSelesai = $checkoutAt
            ? \Carbon\Carbon::parse($checkoutAt)->format('H:i')
            : ($booking->end_time ? \Carbon\Carbon::parse($booking->end_time)->format('H:i') : '-');

        // Sisa Kuota: menyesuaikan paket dikurangi jam dari mulai sampai selesai yang dibulatkan jamnya
        $sisaKuota = $this->calculateMeetingRoomRemainingQuota($booking->fresh());

        // ── Validate: log warning jika ada parameter kosong ───────────────────
        $paramLabels = [
            '{{1}} clientName' => $clientName,
            '{{2}} roomName'   => $roomName,
            '{{3}} tanggal'    => $tanggal,
            '{{4}} jamMulai'   => $jamMulai,
            '{{5}} jamSelesai' => $jamSelesai,
            '{{6}} sisaKuota'  => $sisaKuota,
        ];

        foreach ($paramLabels as $label => $value) {
            if (empty($value) || $value === '-') {
                Log::warning('WhatsAppService::notifyMeetingRoomCheckOut - Parameter template kosong/default.', [
                    'parameter'  => $label,
                    'value'      => $value,
                    'booking_id' => $booking->id,
                ]);
            }
        }

        // ── Kirim via Botcake Official WABA API ────────────────────────────────
        $templateId = config('services.botcake.templates.meeting_room_checkout');

        return $this->sendTemplateById(
            $phone,
            $templateId,
            'UTILITY',
            [
                $clientName,   // {{1}} Nama Client
                $roomName,     // {{2}} Nama Ruangan
                $tanggal,      // {{3}} Tanggal Meeting (Check-In Date)
                $jamMulai,     // {{4}} Jam Mulai (Check-In Time)
                $jamSelesai,   // {{5}} Jam Selesai (Check-Out Time)
                $sisaKuota,    // {{6}} Sisa Kuota
            ],
            $booking->user_id
        );
    }


    /**
     * Notify client when admin/system checks out a Podcast Room reservation.
     *
     * Menggunakan WhatsApp Template Message via Botcake Official WABA API.
     * Template: podcast_room_checkout (4 parameter)
     *
     * Placeholder mapping:
     *  {{1}} = Nama Client
     *  {{2}} = Tanggal Booking
     *  {{3}} = Jam Mulai
     *  {{4}} = Jam Selesai
     */
    public function notifyPodcastRoomCheckOut(PodcastRoomBooking $booking, string $actualDuration, int $billingHours, $checkinAt, $checkoutAt): ?WhatsappLog
    {
        $booking->loadMissing('user');

        $phone = $booking->user->phone ?? $booking->phone ?? env('DEV_OVERRIDE_PHONE') ?? null;
        if (empty($phone)) {
            Log::warning('WhatsAppService::notifyPodcastRoomCheckOut - Nomor telepon kosong.', [
                'booking_id' => $booking->id,
                'user_id'    => $booking->user_id,
            ]);
            return null;
        }

        // ── Prepare 4 template parameters ─────────────────────────────────────
        $clientName = $booking->user->pic_name
            ?? $booking->user->name
            ?? $booking->name
            ?? 'Client';

        // Tanggal: ambil dari tanggal yang sama dengan checkin ($booking->date)
        $tanggal = $booking->date
            ? \Carbon\Carbon::parse($booking->date)->format('d M Y')
            : ($checkinAt ? \Carbon\Carbon::parse($checkinAt)->format('d M Y') : ($checkoutAt ? \Carbon\Carbon::parse($checkoutAt)->format('d M Y') : '-'));

        // Jam Mulai: ambil dari jam mulai yang diisi saat checkin ($booking->start_time)
        $jamMulai = $booking->start_time
            ? \Carbon\Carbon::parse($booking->start_time)->format('H:i')
            : ($checkinAt ? \Carbon\Carbon::parse($checkinAt)->format('H:i') : '-');

        // Jam Selesai: ambil dari waktu admin klik checkout ($checkoutAt)
        $jamSelesai = $checkoutAt
            ? \Carbon\Carbon::parse($checkoutAt)->format('H:i')
            : ($booking->end_time ? \Carbon\Carbon::parse($booking->end_time)->format('H:i') : '-');

        // ── Validate: log warning jika ada parameter kosong ───────────────────
        $paramLabels = [
            '{{1}} clientName' => $clientName,
            '{{2}} tanggal'    => $tanggal,
            '{{3}} jamMulai'   => $jamMulai,
            '{{4}} jamSelesai' => $jamSelesai,
        ];

        foreach ($paramLabels as $label => $value) {
            if (empty($value) || $value === '-') {
                Log::warning('WhatsAppService::notifyPodcastRoomCheckOut - Parameter template kosong/default.', [
                    'parameter'  => $label,
                    'value'      => $value,
                    'booking_id' => $booking->id,
                ]);
            }
        }

        // ── Kirim via Botcake Official WABA API ────────────────────────────────
        $templateId = config('services.botcake.templates.podcastroom_checkout', config('services.botcake.templates.podcast_room_checkout', '1039778505436096'));

        return $this->sendTemplateById(
            $phone,
            $templateId,
            'UTILITY',
            [
                $clientName,   // {{1}} Nama Client
                $tanggal,      // {{2}} Tanggal Booking
                $jamMulai,     // {{3}} Jam Mulai
                $jamSelesai,   // {{4}} Jam Selesai
            ],
            $booking->user_id
        );
    }

    /**
     * Notify client when admin receives a Virtual Office Mail / Document.
     *
     * Menggunakan WhatsApp Template Message via Botcake Official WABA API.
     * Template: virtual_office_mail_notification (4 parameter)
     *
     * Placeholder mapping:
     *  {{1}} = Nama PT (Company Name)
     *  {{2}} = Tanggal Terima
     *  {{3}} = Jam Terima
     *  {{4}} = Pengirim (Sender Name)
     */
    public function notifyVirtualOfficeMailNotification(VirtualOfficeMailNotification $notification): ?WhatsappLog
    {
        $notification->loadMissing(['virtualOffice.user', 'client']);

        $phone = $notification->client->phone
            ?? $notification->virtualOffice->user->phone
            ?? ($notification->virtualOffice->form_data['pic_phone'] ?? null);

        if (empty($phone)) {
            Log::warning('WhatsAppService::notifyVirtualOfficeMailNotification - Nomor telepon kosong.', [
                'notification_id'   => $notification->id,
                'virtual_office_id' => $notification->virtual_office_id,
                'client_id'         => $notification->client_id,
            ]);
            $notification->update(['whatsapp_status' => 'FAILED']);
            return null;
        }

        // ── Prepare 4 template parameters ─────────────────────────────────────
        $companyName = $notification->virtualOffice->user->company_name
            ?? $notification->client->company_name
            ?? ($notification->virtualOffice->form_data['company_name'] ?? null)
            ?? $notification->client->name
            ?? 'Client';

        $tanggal = $notification->received_date
            ? \Carbon\Carbon::parse($notification->received_date)->format('d M Y')
            : '-';

        $jam = $notification->received_time
            ? \Carbon\Carbon::parse($notification->received_time)->format('H:i')
            : '-';

        $pengirim = $notification->sender_name ?? '-';

        // ── Validate: log warning jika ada parameter kosong ───────────────────
        $paramLabels = [
            '{{1}} companyName' => $companyName,
            '{{2}} tanggal'     => $tanggal,
            '{{3}} jam'         => $jam,
            '{{4}} pengirim'    => $pengirim,
        ];

        foreach ($paramLabels as $label => $value) {
            if (empty($value) || $value === '-') {
                Log::warning('WhatsAppService::notifyVirtualOfficeMailNotification - Parameter template kosong/default.', [
                    'parameter'       => $label,
                    'value'           => $value,
                    'notification_id' => $notification->id,
                ]);
            }
        }

        // ── Kirim via Botcake Official WABA API ────────────────────────────────
        $templateId = config('services.botcake.templates.virtual_office_mail_notification', '2856503864713589');

        $log = $this->sendTemplateById(
            $phone,
            $templateId,
            'UTILITY',
            [
                $companyName, // {{1}} Nama PT
                $tanggal,     // {{2}} Tanggal Terima
                $jam,         // {{3}} Jam Terima
                $pengirim,    // {{4}} Pengirim
            ],
            $notification->client_id,
            $notification->virtual_office_id
        );

        $status = ($log && $log->status === WhatsappLog::STATUS_SUCCESS) ? 'SUCCESS' : 'FAILED';
        $notification->update(['whatsapp_status' => $status]);

        return $log;
    }

    /**
     * Notify client when a guest arrives for Virtual Office.
     *
     * Menggunakan WhatsApp Template Message via Botcake Official WABA API.
     * Template: virtual_office_guest_notification (6 parameter)
     *
     * Placeholder mapping:
     *  {{1}} = Nama PT (Company Name)
     *  {{2}} = Nama Tamu
     *  {{3}} = Nomor HP Tamu / Kontak Tamu
     *  {{4}} = Instansi
     *  {{5}} = Jam Datang
     *  {{6}} = Keperluan
     */
    public function notifyVirtualOfficeGuest(VirtualOfficeGuestNotification $notification): ?WhatsappLog
    {
        $notification->loadMissing(['virtualOffice.user', 'client']);

        $phone = $notification->client->phone
            ?? $notification->virtualOffice->user->phone
            ?? ($notification->virtualOffice->form_data['pic_phone'] ?? null);

        if (empty($phone)) {
            Log::warning('WhatsAppService::notifyVirtualOfficeGuest - Nomor telepon client kosong.', [
                'notification_id'   => $notification->id,
                'virtual_office_id' => $notification->virtual_office_id,
                'client_id'         => $notification->client_id,
            ]);
            $notification->update(['whatsapp_status' => 'FAILED']);
            return null;
        }

        // ── Prepare 6 template parameters ─────────────────────────────────────
        $companyName = $notification->virtualOffice->user->company_name
            ?? $notification->client->company_name
            ?? ($notification->virtualOffice->form_data['company_name'] ?? null)
            ?? $notification->client->name
            ?? 'Client';

        $guestName    = $notification->guest_name ?? '-';
        $guestPhone   = $notification->guest_phone ?? '-';
        $guestCompany = $notification->guest_company ?? '-';
        
        $arrivalTime  = $notification->arrival_time
            ? \Carbon\Carbon::parse($notification->arrival_time)->format('H:i')
            : '-';

        $purpose      = $notification->purpose ?? '-';

        // ── Validate: log warning jika ada parameter kosong ───────────────────
        $paramLabels = [
            '{{1}} companyName'  => $companyName,
            '{{2}} guestName'    => $guestName,
            '{{3}} guestPhone'   => $guestPhone,
            '{{4}} guestCompany' => $guestCompany,
            '{{5}} arrivalTime'  => $arrivalTime,
            '{{6}} purpose'      => $purpose,
        ];

        foreach ($paramLabels as $label => $value) {
            if (empty($value) || $value === '-') {
                Log::warning('WhatsAppService::notifyVirtualOfficeGuest - Parameter template kosong/default.', [
                    'parameter'       => $label,
                    'value'           => $value,
                    'notification_id' => $notification->id,
                ]);
            }
        }

        // ── Kirim via Botcake Official WABA API ────────────────────────────────
        $templateId = config('services.botcake.templates.virtual_office_guest_notification', '1712545996642391');

        $log = $this->sendTemplateById(
            $phone,
            $templateId,
            'UTILITY',
            [
                $companyName,  // {{1}} Nama PT
                $guestName,    // {{2}} Nama Tamu
                $guestPhone,   // {{3}} Kontak Tamu
                $guestCompany, // {{4}} Instansi
                $arrivalTime,  // {{5}} Jam Datang
                $purpose,      // {{6}} Keperluan
            ],
            $notification->client_id,
            $notification->virtual_office_id
        );

        $status = ($log && $log->status === WhatsappLog::STATUS_SUCCESS) ? 'SUCCESS' : 'FAILED';
        $notification->update(['whatsapp_status' => $status]);

        return $log;
    }

    /**
     * Notify client Virtual Office tentang renewal H-30.
     *
     * Menggunakan WhatsApp Template Message via Botcake Official WABA API.
     * Template: virtual_office_renewal_h30 (6 parameter)
     *
     * Placeholder mapping:
     *  {{1}} = Nama PT (Company Name)
     *  {{2}} = Tanggal Berakhir (format: 01 September 2026)
     *  {{3}} = Nama Paket (e.g. Virtual Office Enterprise)
     *  {{4}} = Tanggal Mulai (format: 01 September 2025)
     *  {{5}} = Tanggal Berakhir (format: 01 September 2026)
     *  {{6}} = Harga (format: Rp5.800.000)
     */
    public function notifyVirtualOfficeRenewalReminder(Order $vo): ?WhatsappLog
    {
        $vo->loadMissing(['user', 'roomBenefits']);

        $phone = $vo->user->phone
            ?? ($vo->form_data['pic_phone'] ?? null);

        if (empty($phone)) {
            Log::warning('WhatsAppService::notifyVirtualOfficeRenewalReminder - Nomor telepon kosong.', [
                'order_id' => $vo->id,
                'user_id'  => $vo->user_id,
            ]);
            return null;
        }

        // ── Prepare 5 template parameters (Template v2: virtual_office_renewal_h30_v2) ──
        $companyName = $vo->user->company_name
            ?? ($vo->form_data['company_name'] ?? null)
            ?? $vo->user->name
            ?? 'Client';

        $benefit    = $vo->roomBenefits->first();
        $expiredAt  = $benefit && $benefit->expired_at
            ? \Carbon\Carbon::parse($benefit->expired_at)
            : null;

        $tanggalAktif   = $benefit && $benefit->created_at
            ? \Carbon\Carbon::parse($benefit->created_at)
            : \Carbon\Carbon::parse($vo->updated_at);

        $tanggalBerakhir = $expiredAt
            ? $expiredAt->translatedFormat('d F Y')
            : '-';

        $tanggalMulai = $tanggalAktif->translatedFormat('d F Y');

        // ── Nama Paket ────────────────────────────────────────────────────────
        $packageName = $vo->service_name ?? 'Virtual Office';
        $packageSuffix = $vo->form_data['package'] ?? '';
        if (!empty($packageSuffix) && !str_contains(strtolower($packageName), strtolower($packageSuffix))) {
            $packageName .= ' ' . ucfirst($packageSuffix);
        }

        // ── Validate: log warning jika ada parameter kosong ───────────────────
        $paramLabels = [
            '{{1}} companyName'      => $companyName,
            '{{2}} tanggalBerakhir'   => $tanggalBerakhir,
            '{{3}} packageName'      => $packageName,
            '{{4}} tanggalMulai'     => $tanggalMulai,
            '{{5}} tanggalBerakhir2' => $tanggalBerakhir,
        ];

        foreach ($paramLabels as $label => $value) {
            if (empty($value) || $value === '-') {
                Log::warning('WhatsAppService::notifyVirtualOfficeRenewalReminder - Parameter template kosong/default.', [
                    'parameter' => $label,
                    'value'     => $value,
                    'order_id'  => $vo->id,
                ]);
            }
        }

        // ── Kirim via Botcake Official WABA API ────────────────────────────────
        $templateId = config('services.botcake.templates.virtual_office_renewal_h30', '1329567535585592');

        $log = $this->sendTemplateById(
            $phone,
            $templateId,
            'UTILITY',
            [
                $companyName,     // {{1}} Nama PT
                $tanggalBerakhir, // {{2}} Tanggal Berakhir
                $packageName,     // {{3}} Nama Paket
                $tanggalMulai,    // {{4}} Tanggal Mulai
                $tanggalBerakhir, // {{5}} Tanggal Berakhir
            ],
            $vo->user_id,
            $vo->id
        );

        return $log;
    }

    /**
     * Notify client Virtual Office tentang renewal H-7.
     *
     * Menggunakan WhatsApp Template Message via Botcake Official WABA API.
     * Template: virtual_office_renewal_h7 (5 parameter)
     *
     * Placeholder mapping:
     *  {{1}} = Nama PT (Company Name)
     *  {{2}} = Tanggal Berakhir (format: 01 September 2026)
     *  {{3}} = Nama Paket (e.g. Virtual Office Enterprise)
     *  {{4}} = Tanggal Mulai (format: 01 September 2025)
     *  {{5}} = Tanggal Berakhir (format: 01 September 2026)
     */
    public function notifyVirtualOfficeRenewalReminderH7(Order $vo): ?WhatsappLog
    {
        $vo->loadMissing(['user', 'roomBenefits']);

        $phone = $vo->user->phone
            ?? ($vo->form_data['pic_phone'] ?? null);

        if (empty($phone)) {
            Log::warning('WhatsAppService::notifyVirtualOfficeRenewalReminderH7 - Nomor telepon kosong.', [
                'order_id' => $vo->id,
                'user_id'  => $vo->user_id,
            ]);
            return null;
        }

        // ── Prepare 5 template parameters (Template: virtual_office_renewal_h7) ──
        $companyName = $vo->user->company_name
            ?? ($vo->form_data['company_name'] ?? null)
            ?? $vo->user->name
            ?? 'Client';

        $benefit    = $vo->roomBenefits->first();
        $expiredAt  = $benefit && $benefit->expired_at
            ? \Carbon\Carbon::parse($benefit->expired_at)
            : null;

        $tanggalAktif   = $benefit && $benefit->created_at
            ? \Carbon\Carbon::parse($benefit->created_at)
            : \Carbon\Carbon::parse($vo->updated_at);

        $tanggalBerakhir = $expiredAt
            ? $expiredAt->translatedFormat('d F Y')
            : '-';

        $tanggalMulai = $tanggalAktif->translatedFormat('d F Y');

        // ── Nama Paket ────────────────────────────────────────────────────────
        $packageName = $vo->service_name ?? 'Virtual Office';
        $packageSuffix = $vo->form_data['package'] ?? '';
        if (!empty($packageSuffix) && !str_contains(strtolower($packageName), strtolower($packageSuffix))) {
            $packageName .= ' ' . ucfirst($packageSuffix);
        }

        // ── Validate: log warning jika ada parameter kosong ───────────────────
        $paramLabels = [
            '{{1}} companyName'      => $companyName,
            '{{2}} tanggalBerakhir'   => $tanggalBerakhir,
            '{{3}} packageName'      => $packageName,
            '{{4}} tanggalMulai'     => $tanggalMulai,
            '{{5}} tanggalBerakhir2' => $tanggalBerakhir,
        ];

        foreach ($paramLabels as $label => $value) {
            if (empty($value) || $value === '-') {
                Log::warning('WhatsAppService::notifyVirtualOfficeRenewalReminderH7 - Parameter template kosong/default.', [
                    'parameter' => $label,
                    'value'     => $value,
                    'order_id'  => $vo->id,
                ]);
            }
        }

        // ── Kirim via Botcake Official WABA API ────────────────────────────────
        $templateId = config('services.botcake.templates.virtual_office_renewal_h7', '1025817360352995');

        $log = $this->sendTemplateById(
            $phone,
            $templateId,
            'UTILITY',
            [
                $companyName,     // {{1}} Nama PT
                $tanggalBerakhir, // {{2}} Tanggal Berakhir
                $packageName,     // {{3}} Nama Paket
                $tanggalMulai,    // {{4}} Tanggal Mulai
                $tanggalBerakhir, // {{5}} Tanggal Berakhir
            ],
            $vo->user_id,
            $vo->id
        );

        return $log;
    }

    /**
     * Notify client Virtual Office tentang Hari H Expired.
     *
     * Pilihan Template berdasarkan Paket Virtual Office:
     *  - Paket Enterprise: virtual_office_expired_enterprise (ID 1818055572497687, 2 parameter: {{1}} Nama PT, {{2}} Tanggal Nonaktif)
     *  - Paket Lainnya (Premium/Eksklusif): virtual_office_expired_notification (ID 1757632552089608, 3 parameter: {{1}} Nama PT, {{2}} Tanggal Berakhir, {{3}} Tanggal Nonaktif)
     */
    public function notifyVirtualOfficeExpired(Order $vo): ?WhatsappLog
    {
        $vo->loadMissing(['user', 'roomBenefits']);

        $phone = $vo->user->phone
            ?? ($vo->form_data['pic_phone'] ?? null);

        if (empty($phone)) {
            Log::warning('WhatsAppService::notifyVirtualOfficeExpired - Nomor telepon kosong.', [
                'order_id' => $vo->id,
                'user_id'  => $vo->user_id,
            ]);
            return null;
        }

        // ── Prepare parameters ────────────────────────────────────────────────
        $companyName = $vo->user->company_name
            ?? ($vo->form_data['company_name'] ?? null)
            ?? $vo->user->name
            ?? 'Client';

        $benefit    = $vo->roomBenefits->first();
        $expiredAt  = $benefit && $benefit->expired_at
            ? \Carbon\Carbon::parse($benefit->expired_at)
            : null;

        $tanggalBerakhir = $expiredAt
            ? $expiredAt->translatedFormat('d F Y')
            : \Carbon\Carbon::parse($vo->updated_at)->translatedFormat('d F Y');

        $tanggalNonaktif = $tanggalBerakhir;

        // ── Nama Paket ────────────────────────────────────────────────────────
        $packageName = $vo->service_name ?? 'Virtual Office';
        $packageSuffix = $vo->form_data['package'] ?? '';
        if (!empty($packageSuffix) && !str_contains(strtolower($packageName), strtolower($packageSuffix))) {
            $packageName .= ' ' . ucfirst($packageSuffix);
        }

        // ── Cek apakah Paket Enterprise ───────────────────────────────────────
        $serviceName = strtolower($vo->service_name ?? '');
        $packageForm = strtolower($vo->form_data['package'] ?? '');
        $isEnterprise = str_contains($serviceName, 'enterprise') || $packageForm === 'enterprise';

        if ($isEnterprise) {
            $templateId = config('services.botcake.templates.virtual_office_expired_enterprise', '1818055572497687');
            $parameters = [
                $companyName,     // {{1}} Nama PT
                $tanggalNonaktif, // {{2}} Tanggal Nonaktif
                $packageName,     // {{3}} Nama Layanan / Paket Virtual Office
            ];

            $paramLabels = [
                '{{1}} companyName'     => $companyName,
                '{{2}} tanggalNonaktif'  => $tanggalNonaktif,
                '{{3}} packageName'      => $packageName,
            ];
        } else {
            $templateId = config('services.botcake.templates.virtual_office_expired', '1757632552089608');
            $parameters = [
                $companyName,     // {{1}} Nama PT
                $tanggalBerakhir, // {{2}} Tanggal Berakhir
                $tanggalNonaktif, // {{3}} Tanggal Nonaktif
                $packageName,     // {{4}} Nama Layanan / Paket Virtual Office
            ];

            $paramLabels = [
                '{{1}} companyName'     => $companyName,
                '{{2}} tanggalBerakhir'  => $tanggalBerakhir,
                '{{3}} tanggalNonaktif'  => $tanggalNonaktif,
                '{{4}} packageName'      => $packageName,
            ];
        }

        // ── Validate: log warning jika ada parameter kosong ───────────────────
        foreach ($paramLabels as $label => $value) {
            if (empty($value) || $value === '-') {
                Log::warning('WhatsAppService::notifyVirtualOfficeExpired - Parameter template kosong/default.', [
                    'parameter' => $label,
                    'value'     => $value,
                    'order_id'  => $vo->id,
                    'template'  => $isEnterprise ? 'virtual_office_expired_enterprise' : 'virtual_office_expired_notification',
                ]);
            }
        }

        // ── Kirim via Botcake Official WABA API ────────────────────────────────
        $log = $this->sendTemplateById(
            $phone,
            $templateId,
            'UTILITY',
            $parameters,
            $vo->user_id,
            $vo->id
        );

        return $log;
    }

    /**
     * Notify client Meeting Room tentang renewal H-30.
     *
     * Menggunakan WhatsApp Template Message via Botcake Official WABA API.
     * Template: meeting_room_renewal_h30 (5 parameter)
     *
     * Placeholder mapping:
     *  {{1}} = Nama Client (Company Name / User Name)
     *  {{2}} = Tanggal Berakhir (format: 01 September 2026)
     *  {{3}} = Nama Paket (e.g. Paket Meeting Room 12 Jam / Bundling Virtual Office - Meeting Room)
     *  {{4}} = Tanggal Mulai (format: 01 September 2025)
     *  {{5}} = Tanggal Berakhir (format: 01 September 2026)
     */
    public function notifyMeetingRoomRenewalReminderH30(RoomBenefit $benefit): ?WhatsappLog
    {
        $benefit->loadMissing(['user', 'order']);

        $phone = $benefit->user->phone
            ?? ($benefit->order->form_data['pic_phone'] ?? null);

        if (empty($phone)) {
            Log::warning('WhatsAppService::notifyMeetingRoomRenewalReminderH30 - Nomor telepon kosong.', [
                'benefit_id' => $benefit->id,
                'user_id'    => $benefit->user_id,
                'order_id'   => $benefit->order_id,
            ]);
            return null;
        }

        // ── Prepare 5 template parameters ─────────────────────────────────────
        $clientName = $benefit->user->company_name
            ?? ($benefit->order->form_data['company_name'] ?? null)
            ?? $benefit->user->name
            ?? 'Client';

        $expiredAt = $benefit->expired_at
            ? \Carbon\Carbon::parse($benefit->expired_at)
            : null;

        $createdAt = $benefit->created_at
            ? \Carbon\Carbon::parse($benefit->created_at)
            : \Carbon\Carbon::parse($benefit->updated_at);

        $tanggalBerakhir = $expiredAt
            ? $expiredAt->translatedFormat('d F Y')
            : '-';

        $tanggalMulai = $createdAt->translatedFormat('d F Y');

        $packageName = $benefit->paket ?? ($benefit->order->service_name ?? 'Paket Meeting Room');

        // ── Validate: log warning jika ada parameter kosong ───────────────────
        $paramLabels = [
            '{{1}} clientName'       => $clientName,
            '{{2}} tanggalBerakhir'   => $tanggalBerakhir,
            '{{3}} packageName'      => $packageName,
            '{{4}} tanggalMulai'     => $tanggalMulai,
            '{{5}} tanggalBerakhir2' => $tanggalBerakhir,
        ];

        foreach ($paramLabels as $label => $value) {
            if (empty($value) || $value === '-') {
                Log::warning('WhatsAppService::notifyMeetingRoomRenewalReminderH30 - Parameter template kosong/default.', [
                    'parameter'  => $label,
                    'value'      => $value,
                    'benefit_id' => $benefit->id,
                ]);
            }
        }

        // ── Kirim via Botcake Official WABA API ────────────────────────────────
        $templateId = config('services.botcake.templates.meeting_room_renewal_h30', '1062841652789555');

        $log = $this->sendTemplateById(
            $phone,
            $templateId,
            'UTILITY',
            [
                $clientName,      // {{1}} Nama Client
                $tanggalBerakhir, // {{2}} Tanggal Berakhir
                $packageName,     // {{3}} Nama Paket
                $tanggalMulai,    // {{4}} Tanggal Mulai
                $tanggalBerakhir, // {{5}} Tanggal Berakhir
            ],
            $benefit->user_id,
            $benefit->order_id
        );

        return $log;
    }

    /**
     * Notify client Meeting Room tentang renewal H-7.
     *
     * Menggunakan WhatsApp Template Message via Botcake Official WABA API.
     * Template: meeting_room_renewal_h7 (6 parameter)
     *
     * Placeholder mapping:
     *  {{1}} = Nama Client (Company Name / User Name)
     *  {{2}} = Tanggal Berakhir (format: 01 September 2026)
     *  {{3}} = Nama Paket (e.g. Paket Meeting Room 12 Jam / Bundling Virtual Office - Meeting Room)
     *  {{4}} = Tanggal Mulai (format: 01 September 2025)
     *  {{5}} = Tanggal Berakhir (format: 01 September 2026)
     *  {{6}} = Benefit Paket Meeting Room (e.g. Meeting Room 48 Jam/Tahun)
     */
    public function notifyMeetingRoomRenewalReminderH7(RoomBenefit $benefit): ?WhatsappLog
    {
        $benefit->loadMissing(['user', 'order']);

        $phone = $benefit->user->phone
            ?? ($benefit->order->form_data['pic_phone'] ?? null);

        if (empty($phone)) {
            Log::warning('WhatsAppService::notifyMeetingRoomRenewalReminderH7 - Nomor telepon kosong.', [
                'benefit_id' => $benefit->id,
                'user_id'    => $benefit->user_id,
                'order_id'   => $benefit->order_id,
            ]);
            return null;
        }

        // ── Prepare 6 template parameters ─────────────────────────────────────
        $clientName = $benefit->user->company_name
            ?? ($benefit->order->form_data['company_name'] ?? null)
            ?? $benefit->user->name
            ?? 'Client';

        $expiredAt = $benefit->expired_at
            ? \Carbon\Carbon::parse($benefit->expired_at)
            : null;

        $createdAt = $benefit->created_at
            ? \Carbon\Carbon::parse($benefit->created_at)
            : \Carbon\Carbon::parse($benefit->updated_at);

        $tanggalBerakhir = $expiredAt
            ? $expiredAt->translatedFormat('d F Y')
            : '-';

        $tanggalMulai = $createdAt->translatedFormat('d F Y');

        $packageName = $benefit->paket ?? ($benefit->order->service_name ?? 'Paket Meeting Room');

        // Benefit label format: "Meeting Room XX Jam/Tahun"
        $hours = round(($benefit->total_minutes ?? 0) / 60);
        $benefitLabel = "Meeting Room {$hours} Jam/Tahun";

        // ── Validate: log warning jika ada parameter kosong ───────────────────
        $paramLabels = [
            '{{1}} clientName'       => $clientName,
            '{{2}} tanggalBerakhir'   => $tanggalBerakhir,
            '{{3}} packageName'      => $packageName,
            '{{4}} tanggalMulai'     => $tanggalMulai,
            '{{5}} tanggalBerakhir2' => $tanggalBerakhir,
            '{{6}} benefitLabel'     => $benefitLabel,
        ];

        foreach ($paramLabels as $label => $value) {
            if (empty($value) || $value === '-') {
                Log::warning('WhatsAppService::notifyMeetingRoomRenewalReminderH7 - Parameter template kosong/default.', [
                    'parameter'  => $label,
                    'value'      => $value,
                    'benefit_id' => $benefit->id,
                ]);
            }
        }

        // ── Kirim via Botcake Official WABA API ────────────────────────────────
        $templateId = config('services.botcake.templates.meeting_room_renewal_h7', '');

        $log = $this->sendTemplateById(
            $phone,
            $templateId,
            'UTILITY',
            [
                $clientName,      // {{1}} Nama Client
                $tanggalBerakhir, // {{2}} Tanggal Berakhir
                $packageName,     // {{3}} Nama Paket
                $tanggalMulai,    // {{4}} Tanggal Mulai
                $tanggalBerakhir, // {{5}} Tanggal Berakhir
                $benefitLabel,    // {{6}} Benefit Paket Meeting Room
            ],
            $benefit->user_id,
            $benefit->order_id
        );

        return $log;
    }

    /**
     * Notify client Meeting Room tentang Hari H Expired.
     *
     * Menggunakan WhatsApp Template Message via Botcake Official WABA API.
     * Template: meeting_room_expired_notification (ID: 1020652014088993)
     *
     * Placeholder mapping (3 parameter):
     *  {{1}} = Nama Client (Company Name / User Name)
     *  {{2}} = Tanggal Nonaktif (format: 01 September 2026)
     *  {{3}} = Nama Paket Meeting Room (e.g. Paket Meeting Room 60 Jam / Bundling Virtual Office - Meeting Room)
     */
    public function notifyMeetingRoomExpired(RoomBenefit $benefit): ?WhatsappLog
    {
        $benefit->loadMissing(['user', 'order']);

        $phone = $benefit->user->phone
            ?? ($benefit->order->form_data['pic_phone'] ?? null);

        if (empty($phone)) {
            Log::warning('WhatsAppService::notifyMeetingRoomExpired - Nomor telepon kosong.', [
                'benefit_id' => $benefit->id,
                'user_id'    => $benefit->user_id,
                'order_id'   => $benefit->order_id,
            ]);
            return null;
        }

        // ── Prepare 3 template parameters ─────────────────────────────────────
        $clientName = $benefit->user->company_name
            ?? ($benefit->order->form_data['company_name'] ?? null)
            ?? $benefit->user->name
            ?? 'Client';

        $expiredAt = $benefit->expired_at
            ? \Carbon\Carbon::parse($benefit->expired_at)
            : null;

        $tanggalNonaktif = $expiredAt
            ? $expiredAt->translatedFormat('d F Y')
            : '-';

        $packageName = $benefit->paket ?? ($benefit->order->service_name ?? 'Paket Meeting Room');

        // ── Validate: log warning jika ada parameter kosong ───────────────────
        $paramLabels = [
            '{{1}} clientName'      => $clientName,
            '{{2}} tanggalNonaktif' => $tanggalNonaktif,
            '{{3}} packageName'     => $packageName,
        ];

        foreach ($paramLabels as $label => $value) {
            if (empty($value) || $value === '-') {
                Log::warning('WhatsAppService::notifyMeetingRoomExpired - Parameter template kosong/default.', [
                    'parameter'  => $label,
                    'value'      => $value,
                    'benefit_id' => $benefit->id,
                ]);
            }
        }

        // ── Kirim via Botcake Official WABA API ────────────────────────────────
        $templateId = config('services.botcake.templates.meeting_room_expired', '1020652014088993');

        $log = $this->sendTemplateById(
            $phone,
            $templateId,
            'UTILITY',
            [
                $clientName,      // {{1}} Nama Client
                $tanggalNonaktif, // {{2}} Tanggal Nonaktif
                $packageName,     // {{3}} Nama Paket Meeting Room
            ],
            $benefit->user_id,
            $benefit->order_id
        );

        return $log;
    }

    /**
     * Notify client Studio Podcast tentang renewal H-30.
     *
     * Menggunakan WhatsApp Template Message via Botcake Official WABA API.
     * Template: podcast_room_renewal_h30 (ID: 1578804397234740)
     *
     * Placeholder mapping (5 parameter):
     *  {{1}} = Nama Client (Company Name / User Name)
     *  {{2}} = Tanggal Berakhir (format: 01 September 2026)
     *  {{3}} = Nama Paket (e.g. Paket Studio Podcast 20 Jam)
     *  {{4}} = Tanggal Mulai (format: 01 September 2025)
     *  {{5}} = Tanggal Berakhir (format: 01 September 2026)
     */
    public function notifyPodcastRoomRenewalReminderH30(RoomBenefit $benefit): ?WhatsappLog
    {
        $benefit->loadMissing(['user', 'order']);

        $phone = $benefit->user->phone
            ?? ($benefit->order->form_data['pic_phone'] ?? null);

        if (empty($phone)) {
            Log::warning('WhatsAppService::notifyPodcastRoomRenewalReminderH30 - Nomor telepon kosong.', [
                'benefit_id' => $benefit->id,
                'user_id'    => $benefit->user_id,
                'order_id'   => $benefit->order_id,
            ]);
            return null;
        }

        // ── Prepare 5 template parameters ─────────────────────────────────────
        $clientName = $benefit->user->company_name
            ?? ($benefit->order->form_data['company_name'] ?? null)
            ?? $benefit->user->name
            ?? 'Client';

        $expiredAt = $benefit->expired_at
            ? \Carbon\Carbon::parse($benefit->expired_at)
            : null;

        $createdAt = $benefit->created_at
            ? \Carbon\Carbon::parse($benefit->created_at)
            : \Carbon\Carbon::parse($benefit->updated_at);

        $tanggalBerakhir = $expiredAt
            ? $expiredAt->translatedFormat('d F Y')
            : '-';

        $tanggalMulai = $createdAt->translatedFormat('d F Y');

        $packageName = $benefit->paket ?? ($benefit->order->service_name ?? 'Paket Studio Podcast');

        // ── Validate: log warning jika ada parameter kosong ───────────────────
        $paramLabels = [
            '{{1}} clientName'       => $clientName,
            '{{2}} tanggalBerakhir'   => $tanggalBerakhir,
            '{{3}} packageName'      => $packageName,
            '{{4}} tanggalMulai'     => $tanggalMulai,
            '{{5}} tanggalBerakhir2' => $tanggalBerakhir,
        ];

        foreach ($paramLabels as $label => $value) {
            if (empty($value) || $value === '-') {
                Log::warning('WhatsAppService::notifyPodcastRoomRenewalReminderH30 - Parameter template kosong/default.', [
                    'parameter'  => $label,
                    'value'      => $value,
                    'benefit_id' => $benefit->id,
                ]);
            }
        }

        // ── Kirim via Botcake Official WABA API ────────────────────────────────
        $templateId = config('services.botcake.templates.podcast_room_renewal_h30', '1578804397234740');

        $log = $this->sendTemplateById(
            $phone,
            $templateId,
            'UTILITY',
            [
                $clientName,      // {{1}} Nama Client
                $tanggalBerakhir, // {{2}} Tanggal Berakhir
                $packageName,     // {{3}} Nama Paket Studio Podcast
                $tanggalMulai,    // {{4}} Tanggal Mulai
                $tanggalBerakhir, // {{5}} Tanggal Berakhir
            ],
            $benefit->user_id,
            $benefit->order_id
        );

        return $log;
    }

    /**
     * Notify client Studio Podcast tentang renewal H-7.
     *
     * Menggunakan WhatsApp Template Message via Botcake Official WABA API.
     * Template: podcast_room_renewal_h7 (ID: 1370170901755859)
     *
     * Placeholder mapping (5 parameter):
     *  {{1}} = Nama Client (Company Name / User Name)
     *  {{2}} = Tanggal Berakhir (format: 01 September 2026)
     *  {{3}} = Nama Paket Studio Podcast (e.g. Paket Studio Podcast 20 Jam)
     *  {{4}} = Tanggal Mulai (format: 01 September 2025)
     *  {{5}} = Tanggal Berakhir (format: 01 September 2026)
     */
    public function notifyPodcastRoomRenewalReminderH7(RoomBenefit $benefit): ?WhatsappLog
    {
        $benefit->loadMissing(['user', 'order']);

        $phone = $benefit->user->phone
            ?? ($benefit->order->form_data['pic_phone'] ?? null);

        if (empty($phone)) {
            Log::warning('WhatsAppService::notifyPodcastRoomRenewalReminderH7 - Nomor telepon kosong.', [
                'benefit_id' => $benefit->id,
                'user_id'    => $benefit->user_id,
                'order_id'   => $benefit->order_id,
            ]);
            return null;
        }

        // ── Prepare 5 template parameters ─────────────────────────────────────
        $clientName = $benefit->user->company_name
            ?? ($benefit->order->form_data['company_name'] ?? null)
            ?? $benefit->user->name
            ?? 'Client';

        $expiredAt = $benefit->expired_at
            ? \Carbon\Carbon::parse($benefit->expired_at)
            : null;

        $createdAt = $benefit->created_at
            ? \Carbon\Carbon::parse($benefit->created_at)
            : \Carbon\Carbon::parse($benefit->updated_at);

        $tanggalBerakhir = $expiredAt
            ? $expiredAt->translatedFormat('d F Y')
            : '-';

        $tanggalMulai = $createdAt->translatedFormat('d F Y');

        $packageName = $benefit->paket ?? ($benefit->order->service_name ?? 'Paket Studio Podcast');

        // ── Validate: log warning jika ada parameter kosong ───────────────────
        $paramLabels = [
            '{{1}} clientName'       => $clientName,
            '{{2}} tanggalBerakhir'   => $tanggalBerakhir,
            '{{3}} packageName'      => $packageName,
            '{{4}} tanggalMulai'     => $tanggalMulai,
            '{{5}} tanggalBerakhir2' => $tanggalBerakhir,
        ];

        foreach ($paramLabels as $label => $value) {
            if (empty($value) || $value === '-') {
                Log::warning('WhatsAppService::notifyPodcastRoomRenewalReminderH7 - Parameter template kosong/default.', [
                    'parameter'  => $label,
                    'value'      => $value,
                    'benefit_id' => $benefit->id,
                ]);
            }
        }

        // ── Kirim via Botcake Official WABA API ────────────────────────────────
        $templateId = config('services.botcake.templates.podcast_room_renewal_h7', '1370170901755859');

        $log = $this->sendTemplateById(
            $phone,
            $templateId,
            'UTILITY',
            [
                $clientName,      // {{1}} Nama Client
                $tanggalBerakhir, // {{2}} Tanggal Berakhir
                $packageName,     // {{3}} Nama Paket Studio Podcast
                $tanggalMulai,    // {{4}} Tanggal Mulai
                $tanggalBerakhir, // {{5}} Tanggal Berakhir
            ],
            $benefit->user_id,
            $benefit->order_id
        );

        return $log;
    }

    /**
     * Notify client Studio Podcast tentang Hari H Expired.
     *
     * Menggunakan WhatsApp Template Message via Botcake Official WABA API.
     * Template: podcast_room_expired_notification
     *
     * Placeholder mapping (3 parameter):
     *  {{1}} = Nama Client (Company Name / User Name)
     *  {{2}} = Tanggal Berakhir / Nonaktif (format: 01 September 2026)
     *  {{3}} = Nama Paket Studio Podcast (e.g. Paket Studio Podcast 20 Jam)
     */
    public function notifyPodcastRoomExpired(RoomBenefit $benefit): ?WhatsappLog
    {
        $benefit->loadMissing(['user', 'order']);

        $phone = $benefit->user->phone
            ?? ($benefit->order->form_data['pic_phone'] ?? null);

        if (empty($phone)) {
            Log::warning('WhatsAppService::notifyPodcastRoomExpired - Nomor telepon kosong.', [
                'benefit_id' => $benefit->id,
                'user_id'    => $benefit->user_id,
                'order_id'   => $benefit->order_id,
            ]);
            return null;
        }

        // ── Prepare 3 template parameters ─────────────────────────────────────
        $clientName = $benefit->user->company_name
            ?? ($benefit->order->form_data['company_name'] ?? null)
            ?? $benefit->user->name
            ?? 'Client';

        $expiredAt = $benefit->expired_at
            ? \Carbon\Carbon::parse($benefit->expired_at)
            : null;

        $tanggalBerakhir = $expiredAt
            ? $expiredAt->translatedFormat('d F Y')
            : '-';

        $packageName = $benefit->paket ?? ($benefit->order->service_name ?? 'Paket Studio Podcast');

        // ── Validate: log warning jika ada parameter kosong ───────────────────
        $paramLabels = [
            '{{1}} clientName'      => $clientName,
            '{{2}} tanggalBerakhir' => $tanggalBerakhir,
            '{{3}} packageName'     => $packageName,
        ];

        foreach ($paramLabels as $label => $value) {
            if (empty($value) || $value === '-') {
                Log::warning('WhatsAppService::notifyPodcastRoomExpired - Parameter template kosong/default.', [
                    'parameter'  => $label,
                    'value'      => $value,
                    'benefit_id' => $benefit->id,
                ]);
            }
        }

        // ── Kirim via Botcake Official WABA API ────────────────────────────────
        $templateId = config('services.botcake.templates.podcast_room_expired', '');

        $log = $this->sendTemplateById(
            $phone,
            $templateId,
            'UTILITY',
            [
                $clientName,      // {{1}} Nama Client
                $tanggalBerakhir, // {{2}} Tanggal Berakhir / Nonaktif
                $packageName,     // {{3}} Nama Paket Studio Podcast
            ],
            $benefit->user_id,
            $benefit->order_id
        );

        return $log;
    }

    // ── Private: Message Builders ─────────────────────────────────────────────

    /**
     * Standard footer for all messages.
     */
    private function getFooter(): array
    {
        return [
            "Salam Hormat,",
            "",
            "Tim Lawgika",
            "Professional Business & Legal Services",
            "www.lawgika.co.id",
        ];
    }

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
            "📄 Nomor Order",
            $order->order_number,
            "",
            "🕒 Waktu Order",
            now()->format('d M Y H:i'),
            "",
            "💼 Layanan",
            $serviceLabel,
            "",
            "📦 Paket",
            $packageLabel,
            "",
            "💳 Total Pembayaran",
            "Rp " . number_format($order->total_price, 0, ',', '.'),
            "",
            "ℹ️ Status",
            $statusLabel,
        ];

        // ── Benefit section (from database) ───────────────────────────────────
        $benefits = $order->roomBenefits;
        if ($benefits && $benefits->isNotEmpty()) {
            $lines[] = "";
            $lines[] = "━━━━━━━━━━━━━━━━━━";
            $lines[] = "";
            $lines[] = "🎁 Benefit Anda:";
            $lines[] = "";

            foreach ($benefits as $benefit) {
                $typeLabel = $benefit->type === 'meeting' ? 'Meeting Room' : 'Podcast Room';
                $hours     = (int) ($benefit->total_minutes / 60);
                $lines[]   = "- {$typeLabel}: {$hours} Jam / Tahun";
            }
        }

        $lines[] = "";
        $lines[] = "━━━━━━━━━━━━━━━━━━";
        $lines[] = "";
        $lines[] = "Silakan login ke Dashboard Client untuk melihat perkembangan pesanan.";
        $lines[] = "";
        $lines[] = "Apabila membutuhkan bantuan, tim kami siap membantu.";
        $lines[] = "";
        $lines[] = "━━━━━━━━━━━━━━━━━━";
        $lines[] = "";
        
        $lines = array_merge($lines, $this->getFooter());

        return implode("\n", $lines);
    }

    private function buildMeetingRoomMessage(MeetingRoomBooking $booking): string
    {
        $clientName = $booking->user->pic_name ?? $booking->user->name ?? $booking->name;
        $tanggal    = $booking->date ? \Carbon\Carbon::parse($booking->date)->format('d M Y') : '-';
        $jam        = $booking->start_time ? \Carbon\Carbon::parse($booking->start_time)->format('H:i') : '-';
        $noRes      = "MR-" . \Carbon\Carbon::parse($booking->created_at)->format('Ymd') . "-" . str_pad($booking->id, 5, '0', STR_PAD_LEFT);

        $lines = [
            "Halo Bapak/Ibu {$clientName},",
            "",
            "Terima kasih telah mempercayakan Lawgika.",
            "",
            "Reservasi Meeting Room Anda berhasil dibuat.",
            "",
            "━━━━━━━━━━━━━━━━━━",
            "",
            "📅 Tanggal",
            $tanggal,
            "",
            "🕒 Waktu",
            $jam,
            "",
            "🏢 Ruangan",
            $booking->room_name ?? 'Meeting Room',
            "",
            "👥 Jumlah Peserta",
            ($booking->participants ?? 1) . " Orang",
            "",
            "📦 Paket",
            "Meeting Room Package ({$booking->duration} Jam)",
            "",
            "📄 Nomor Reservasi",
            $noRes,
            "",
            "ℹ️ Status",
            ucfirst($booking->status),
            "",
            "━━━━━━━━━━━━━━━━━━",
            "",
            "Silakan login ke Dashboard Client untuk detail selengkapnya.",
            "",
            "Apabila membutuhkan bantuan, tim kami siap membantu.",
            "",
            "━━━━━━━━━━━━━━━━━━",
            "",
        ];

        $lines = array_merge($lines, $this->getFooter());
        return implode("\n", $lines);
    }

    private function buildPodcastRoomMessage(PodcastRoomBooking $booking): string
    {
        $clientName = $booking->user->pic_name ?? $booking->user->name ?? $booking->name;
        $tanggal    = $booking->date ? \Carbon\Carbon::parse($booking->date)->format('d M Y') : '-';
        $noRes      = "PR-" . \Carbon\Carbon::parse($booking->created_at)->format('Ymd') . "-" . str_pad($booking->id, 5, '0', STR_PAD_LEFT);

        $lines = [
            "Halo Bapak/Ibu {$clientName},",
            "",
            "Terima kasih telah mempercayakan Lawgika.",
            "",
            "Reservasi Podcast Room Anda berhasil dibuat.",
            "",
            "━━━━━━━━━━━━━━━━━━",
            "",
            "📅 Tanggal",
            $tanggal,
            "",
            "🎙 Studio",
            $booking->room_name ?? 'Ruang Podcastroom Utama',
            "",
            "📦 Paket",
            "Podcast Room Package ({$booking->duration} Jam)",
            "",
            "📄 Nomor Reservasi",
            $noRes,
            "",
            "ℹ️ Status",
            ucfirst($booking->status),
            "",
            "━━━━━━━━━━━━━━━━━━",
            "",
            "Silakan login ke Dashboard Client untuk detail selengkapnya.",
            "",
            "Apabila membutuhkan bantuan, tim kami siap membantu.",
            "",
            "━━━━━━━━━━━━━━━━━━",
            "",
        ];

        $lines = array_merge($lines, $this->getFooter());
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
            "Terdapat pembaruan pada layanan Surat Menyurat Anda.",
            "",
            "Dokumen atau surat baru telah dikirimkan kepada Anda.",
            "",
            "━━━━━━━━━━━━━━━━━━",
            "",
            "📄 Jenis Surat",
            $correspondence->title,
            "",
            "ℹ️ Status",
            $statusLabel,
            "",
            "━━━━━━━━━━━━━━━━━━",
            "",
            "Silakan login ke Dashboard Client untuk melihat dokumen surat.",
            "",
            "Apabila membutuhkan bantuan, tim kami siap membantu.",
            "",
            "Terima kasih atas kepercayaan Anda kepada Lawgika.",
            "",
            "━━━━━━━━━━━━━━━━━━",
            "",
        ];

        $lines = array_merge($lines, $this->getFooter());
        return implode("\n", $lines);
    }

    private function buildMeetingRoomCheckInMessage(MeetingRoomBooking $booking): string
    {
        $clientName = $booking->user->pic_name ?? $booking->user->name ?? $booking->name;
        $tanggal    = \Carbon\Carbon::parse($booking->checkin_at)->format('d M Y');
        $waktu      = \Carbon\Carbon::parse($booking->checkin_at)->format('H:i');
        $ruangan    = $booking->room_name ?? 'Meeting Room';
        $noRes      = "MR-" . \Carbon\Carbon::parse($booking->created_at)->format('Ymd') . "-" . str_pad($booking->id, 5, '0', STR_PAD_LEFT);

        $lines = [
            "Halo Bapak/Ibu {$clientName},",
            "",
            "Selamat datang di Lawgika.",
            "",
            "Reservasi Meeting Room Anda telah berhasil melakukan CHECK IN.",
            "",
            "━━━━━━━━━━━━━━━━━━",
            "",
            "📅 Tanggal",
            $tanggal,
            "",
            "🕒 Waktu Check In",
            $waktu,
            "",
            "🏢 Ruangan",
            $ruangan,
            "",
            "👥 Jumlah Peserta",
            ($booking->participants ?? 1) . " Orang",
            "",
            "📄 Nomor Reservasi",
            $noRes,
            "",
            "━━━━━━━━━━━━━━━━━━",
            "",
            "Silakan menggunakan fasilitas Meeting Room sesuai jadwal reservasi yang telah ditentukan.",
            "",
            "Apabila membutuhkan bantuan selama penggunaan ruangan, tim kami siap membantu.",
            "",
            "Terima kasih atas kepercayaan Anda kepada Lawgika.",
            "",
            "━━━━━━━━━━━━━━━━━━",
            "",
        ];

        $lines = array_merge($lines, $this->getFooter());
        return implode("\n", $lines);
    }

    private function buildMeetingRoomCheckOutMessage(MeetingRoomBooking $booking, string $actualDuration, int $billingHours, $checkinAt, $checkoutAt): string
    {
        $clientName = $booking->user->pic_name ?? $booking->user->name ?? $booking->name;
        $tanggal    = \Carbon\Carbon::parse($checkoutAt)->format('d M Y');
        $jamMasuk   = \Carbon\Carbon::parse($checkinAt)->format('H:i');
        $jamKeluar  = \Carbon\Carbon::parse($checkoutAt)->format('H:i');
        $noRes      = "MR-" . \Carbon\Carbon::parse($booking->created_at)->format('Ymd') . "-" . str_pad($booking->id, 5, '0', STR_PAD_LEFT);
        
        // Calculate remaining quota if applicable
        $sisaJam = "-";
        if ($booking->source_type === 'benefit' && $booking->benefit_id) {
            $benefit = \App\Models\RoomBenefit::find($booking->benefit_id);
            if ($benefit) {
                $sisaJam = floor(($benefit->total_minutes - $benefit->used_minutes) / 60) . " Jam";
            }
        } else {
            $quota = \App\Models\UserRoomQuota::where('user_id', $booking->user_id)->first();
            if ($quota) {
                $sisaJam = floor($quota->remaining_seconds / 3600) . " Jam";
            }
        }

        $lines = [
            "Halo Bapak/Ibu {$clientName},",
            "",
            "Terima kasih telah menggunakan fasilitas Meeting Room Lawgika.",
            "",
            "Reservasi Anda telah berhasil melakukan CHECK OUT.",
            "",
            "━━━━━━━━━━━━━━━━━━",
            "",
            "📅 Tanggal",
            $tanggal,
            "",
            "🕒 Check In",
            $jamMasuk,
            "",
            "🕓 Check Out",
            $jamKeluar,
            "",
            "⏱ Durasi Penggunaan",
            $actualDuration,
            "",
            "📊 Kuota Terpakai",
            "{$billingHours} Jam",
            "",
            "📈 Sisa Kuota",
            $sisaJam,
            "",
            "📄 Nomor Reservasi",
            $noRes,
            "",
            "━━━━━━━━━━━━━━━━━━",
            "",
            "Terima kasih telah mempercayakan kebutuhan bisnis Anda kepada Lawgika.",
            "",
            "Kami berharap dapat kembali melayani Anda pada reservasi berikutnya.",
            "",
            "━━━━━━━━━━━━━━━━━━",
            "",
        ];

        $lines = array_merge($lines, $this->getFooter());
        return implode("\n", $lines);
    }

    private function buildPodcastRoomCheckInMessage(PodcastRoomBooking $booking): string
    {
        $clientName = $booking->user->pic_name ?? $booking->user->name ?? $booking->name;
        $tanggal    = \Carbon\Carbon::parse($booking->checkin_at)->format('d M Y');
        $waktu      = \Carbon\Carbon::parse($booking->checkin_at)->format('H:i');
        $ruangan    = $booking->room_name ?? 'Ruang Podcastroom Utama';
        $noRes      = "PR-" . \Carbon\Carbon::parse($booking->created_at)->format('Ymd') . "-" . str_pad($booking->id, 5, '0', STR_PAD_LEFT);

        $lines = [
            "Halo Bapak/Ibu {$clientName},",
            "",
            "Selamat datang di Lawgika.",
            "",
            "Reservasi Podcast Room Anda telah berhasil melakukan CHECK IN.",
            "",
            "━━━━━━━━━━━━━━━━━━",
            "",
            "📅 Tanggal",
            $tanggal,
            "",
            "🕒 Waktu Check In",
            $waktu,
            "",
            "🎙 Studio",
            $ruangan,
            "",
            "📦 Paket",
            "Podcast Room Package ({$booking->duration} Jam)",
            "",
            "📄 Nomor Reservasi",
            $noRes,
            "",
            "━━━━━━━━━━━━━━━━━━",
            "",
            "Selamat menikmati sesi podcast Anda.",
            "",
            "Semoga proses recording berjalan dengan lancar.",
            "",
            "Apabila membutuhkan bantuan selama sesi berlangsung, tim kami siap membantu.",
            "",
            "Terima kasih atas kepercayaan Anda kepada Lawgika.",
            "",
            "━━━━━━━━━━━━━━━━━━",
            "",
        ];

        $lines = array_merge($lines, $this->getFooter());
        return implode("\n", $lines);
    }

    private function buildPodcastRoomCheckOutMessage(PodcastRoomBooking $booking, string $actualDuration, int $billingHours, $checkinAt, $checkoutAt): string
    {
        $clientName = $booking->user->pic_name ?? $booking->user->name ?? $booking->name;
        $tanggal    = \Carbon\Carbon::parse($checkoutAt)->format('d M Y');
        $jamMasuk   = \Carbon\Carbon::parse($checkinAt)->format('H:i');
        $jamKeluar  = \Carbon\Carbon::parse($checkoutAt)->format('H:i');
        $ruangan    = $booking->room_name ?? 'Ruang Podcastroom Utama';
        $noRes      = "PR-" . \Carbon\Carbon::parse($booking->created_at)->format('Ymd') . "-" . str_pad($booking->id, 5, '0', STR_PAD_LEFT);

        $lines = [
            "Halo Bapak/Ibu {$clientName},",
            "",
            "Terima kasih telah menggunakan Podcast Room Lawgika.",
            "",
            "Reservasi Anda telah berhasil melakukan CHECK OUT.",
            "",
            "━━━━━━━━━━━━━━━━━━",
            "",
            "📅 Tanggal",
            $tanggal,
            "",
            "🕒 Check In",
            $jamMasuk,
            "",
            "🕓 Check Out",
            $jamKeluar,
            "",
            "⏱ Durasi Penggunaan",
            $actualDuration,
            "",
            "🎙 Studio",
            $ruangan,
            "",
            "📄 Nomor Reservasi",
            $noRes,
            "",
            "━━━━━━━━━━━━━━━━━━",
            "",
            "Semoga pengalaman recording bersama Lawgika menyenangkan.",
            "",
            "Kami menantikan kehadiran Anda kembali pada sesi berikutnya.",
            "",
            "━━━━━━━━━━━━━━━━━━",
            "",
        ];

        $lines = array_merge($lines, $this->getFooter());
        return implode("\n", $lines);
    }

    // ── Private: Helpers ──────────────────────────────────────────────────────

    /**
     * Calculate remaining Meeting Room quota for the booking's user.
     *
     * Checks benefit pool first, then falls back to UserRoomQuota.
     * Returns a human-readable string like "45 Jam" or "-" if unavailable.
     */
    private function calculateMeetingRoomRemainingQuota(MeetingRoomBooking $booking): string
    {
        // ── 1. Calculate directly from $booking if duration is set ───────────
        if (!empty($booking->duration) && $booking->duration > 0) {
            $totalSec = $booking->duration * 3600;
            $usedSec  = $booking->used_seconds;
            $remSec   = max(0, $totalSec - $usedSec);
            $remHours = (int) floor($remSec / 3600);
            return $remHours . ' Jam';
        }

        // ── 2. Check benefit pool ────────────────────────────────────────────
        if ($booking->source_type === 'benefit' && $booking->benefit_id) {
            $benefit = RoomBenefit::find($booking->benefit_id);
            if ($benefit) {
                $remMinutes = max(0, $benefit->total_minutes - $benefit->used_minutes);
                $remainingHours = (int) floor($remMinutes / 60);
                return $remainingHours . ' Jam';
            }
        }

        // ── 3. Check UserRoomQuota ───────────────────────────────────────────
        $quota = UserRoomQuota::where('user_id', $booking->user_id)->first();
        if ($quota && $quota->remaining_seconds > 0) {
            $remainingHours = (int) floor($quota->remaining_seconds / 3600);
            return $remainingHours . ' Jam';
        }

        // ── 4. Check all active meeting benefits for user ───────────────────
        $allBenefits = RoomBenefit::where('user_id', $booking->user_id)
            ->where('is_active', true)
            ->whereIn('type', ['meeting', 'shared'])
            ->where(function ($q) {
                $q->whereNull('expired_at')
                  ->orWhere('expired_at', '>', now());
            })
            ->get();

        if ($allBenefits->isNotEmpty()) {
            $totalRemaining = $allBenefits->sum(fn($b) => max(0, $b->total_minutes - $b->used_minutes));
            $remainingHours = (int) floor($totalRemaining / 60);
            return $remainingHours . ' Jam';
        }

        return '-';
    }

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

    /**
     * Format phone number to Botcake PSID string (wa_628xxx).
     */
    protected function formatPsid(string $phone): string
    {
        if (str_starts_with($phone, 'wa_')) {
            return $phone;
        }

        $clean = $this->normalizePhone($phone);

        return 'wa_' . $clean;
    }

    /**
     * Known template body text structures for Meta 1024 character validation.
     */
    protected function getTemplateBodyText(string $templateId): ?string
    {
        $templates = [
            '2856503864713589' => "Halo Ibu/Bapak {{1}},\n\nPemberitahuan: Kami telah menerima dokumen / surat masuk untuk perusahaan Anda dengan rincian berikut:\n\n📌 DETAIL SURAT / DOKUMEN MASUK:\n🏢 Perusahaan  : {{1}}\n📅 Tanggal Terima: {{2}}\n🕒 Jam Terima    : {{3}} WIB\n👤 Pengirim      : {{4}}\n\n━━━━━━━━━━━━━━━━━━\nApabila lebih dari 14 hari sejak notifikasi ini dokumen tidak diambil, maka kehilangan bukan menjadi tanggung jawab kami.\n\n📖 Panduan lengkap pengambilan dokumen:\nhttps://lawgika.co.id/virtual-office/panduan-pengambilan-dokumen\n\nTerima kasih atas perhatian Anda! 😊\n\nSalam,\nLawgika.co.id",

            '1712545996642391' => "Halo Ibu/Bapak {{1}},\n\nKami informasikan bahwa saat ini terdapat tamu yang datang dan menanyakan perusahaan Anda.\n\n━━━━━━━━━━━━━━━━━━\n\nBerikut data tamu:\n\n👤 Nama\n{{2}}\n\n📱 Kontak\n{{3}}\n\n🏢 Instansi\n{{4}}\n\n🕒 Jam Datang\n{{5}}\n\n📝 Keperluan\n{{6}}\n\n━━━━━━━━━━━━━━━━━━\n\nSilakan segera menghubungi tamu yang bersangkutan.\n\nTerima kasih. 🙏\n\nSalam,\nLawgika.co.id",

            '1732248697805244' => "Halo Ibu/Bapak {{1}},\n\nReservasi Meeting Room Anda telah berhasil dikonfirmasi.\n\n📌 DETAIL BOOKING:\n🏢 Ruangan  : {{2}}\n📅 Tanggal  : {{3}}\n🕒 Jam Mulai: {{4}}\n🕒 Selesai  : {{5}}\n⌛ Sisa Kuota: {{6}}\n\nTerima kasih telah menggunakan layanan Lawgika.co.id.",

            '805177639284905'  => "Halo Ibu/Bapak {{1}},\n\nPenggunaan Meeting Room Anda telah selesai (Check Out).\n\n📌 DETAIL PENGGUNAAN:\n🏢 Ruangan  : {{2}}\n📅 Tanggal  : {{3}}\n🕒 Jam Mulai: {{4}}\n🕒 Selesai  : {{5}}\n⌛ Sisa Kuota: {{6}}\n\nTerima kasih atas kunjungan Anda di Lawgika Office!",

            '1827038834946958' => "Halo Ibu/Bapak {{1}},\n\nReservasi Studio Podcast Anda telah berhasil dikonfirmasi.\n\n📌 DETAIL BOOKING:\n📅 Tanggal  : {{2}}\n🕒 Jam Mulai: {{3}}\n🕒 Selesai  : {{4}}\n\nTerima kasih telah menggunakan fasilitas Studio Podcast Lawgika.",

            '1039778505436096' => "Halo Ibu/Bapak {{1}},\n\n\nTerima kasih telah menggunakan fasilitas Studio Podcast Lawgika.\n\n\n━━━━━━━━━━━━━\n\n\n\n\nDETAIL BOOKING:\n\n\n🎙️ Ruangan\nPodcast Studio Lawgika Office, World Capital Tower Lt. 38 Unit 6-7, Mega Kuningan, Setia Budi, Jakarta Selatan, Indonesia\n\n\n\n\n📅 Tanggal\n\n\n{{2}}\n\n\n\n\n🕒 Mulai\n\n\n{{3}}\n\n\n\n\n🕒 Selesai\n\n\n{{4}}\n\n\n━━━━━━━━━━━━━\n\n\nTerima kasih banyak atas kepercayaan Anda telah bertransaksi dengan Lawgika.co.id 😊✨\n\n\nKami berharap untuk mendapat review atas pelayanan terbaik kami melalui:\nhttp://bit.ly/4xZqiGK\n\n\n\nJika ada yang ingin ditanyakan atau dibutuhkan lagi, jangan ragu hubungi kami ya!\n\n\nSee you di next order 😉\n\n\n\nSalam,\nLawgika.co.id",

            '2581822038921591' => "Halo Ibu/Bapak {{1}},\n\nTerima kasih telah menggunakan fasilitas Studio Podcast Lawgika.\n\n━━━━━━━━━━━━━━━━━━\n\nDETAIL BOOKING:\n\n🎙️ Ruangan\nPodcast Studio Lawgika Office, World Capital Tower Lt. 38 Unit 6-7, Mega Kuningan, Setia Budi, Jakarta Selatan, Indonesia\n\n📅 Tanggal\n{{2}}\n\n🕒 Mulai\n{{3}}\n\n🕒 Selesai\n{{4}}\n\n━━━━━━━━━━━━━━━━━━\n\nTerima kasih banyak atas kepercayaan Anda telah bertransaksi dengan Lawgika.co.id 😊✨\n\nKami berharap untuk mendapat review atas pelayanan terbaik kami melalui:\n\nhttp://bit.ly/4xZqiGK\n\nJika ada yang ingin ditanyakan atau dibutuhkan lagi, jangan ragu hubungi kami ya!\n\nSee you di next order 😉\n\nSalam,\n\nLawgika.co.id",
        ];

        return $templates[(string)$templateId] ?? null;
    }
}

