<?php

namespace App\Services;

use App\Models\Correspondence;
use App\Models\MeetingRoomBooking;
use App\Models\Order;
use App\Models\PodcastRoomBooking;
use App\Models\RoomBenefit;
use App\Models\UserRoomQuota;
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

        // ── Build log message ─────────────────────────────────────────────────
        $logMessage = "[TEMPLATE ID: {$templateId}] Params: " . json_encode($parameters, JSON_UNESCAPED_UNICODE);

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

    public function notifyMeetingRoomCheckIn(MeetingRoomBooking $booking): ?WhatsappLog
    {
        $booking->loadMissing('user');

        $phone = $booking->user->phone ?? null;
        if (empty($phone)) return null;

        $message = $this->buildMeetingRoomCheckInMessage($booking);

        return $this->send($phone, $message, $booking->user_id);
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

        $phone = $booking->user->phone ?? null;
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

        $tanggal = $checkoutAt
            ? \Carbon\Carbon::parse($checkoutAt)->format('d M Y')
            : ($booking->date ? \Carbon\Carbon::parse($booking->date)->format('d M Y') : '-');

        $jamMulai = $checkinAt
            ? \Carbon\Carbon::parse($checkinAt)->format('H:i')
            : ($booking->start_time ? \Carbon\Carbon::parse($booking->start_time)->format('H:i') : '-');

        $jamSelesai = $checkoutAt
            ? \Carbon\Carbon::parse($checkoutAt)->format('H:i')
            : ($booking->end_time ? \Carbon\Carbon::parse($booking->end_time)->format('H:i') : '-');

        $sisaKuota = $this->calculateMeetingRoomRemainingQuota($booking);

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
                $tanggal,      // {{3}} Tanggal Meeting
                $jamMulai,     // {{4}} Jam Mulai
                $jamSelesai,   // {{5}} Jam Selesai
                $sisaKuota,    // {{6}} Sisa Kuota
            ],
            $booking->user_id
        );
    }

    public function notifyPodcastRoomCheckIn(PodcastRoomBooking $booking): ?WhatsappLog
    {
        $booking->loadMissing('user');

        $phone = $booking->user->phone ?? null;
        if (empty($phone)) return null;

        $message = $this->buildPodcastRoomCheckInMessage($booking);

        return $this->send($phone, $message, $booking->user_id);
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

        $phone = $booking->user->phone ?? null;
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

        $tanggal = $checkoutAt
            ? \Carbon\Carbon::parse($checkoutAt)->format('d M Y')
            : ($booking->date ? \Carbon\Carbon::parse($booking->date)->format('d M Y') : '-');

        $jamMulai = $checkinAt
            ? \Carbon\Carbon::parse($checkinAt)->format('H:i')
            : ($booking->start_time ? \Carbon\Carbon::parse($booking->start_time)->format('H:i') : '-');

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
        $templateId = config('services.botcake.templates.podcast_room_checkout');

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
        // ── Source 1: Benefit pool ────────────────────────────────────────────
        if ($booking->source_type === 'benefit' && $booking->benefit_id) {
            $benefit = RoomBenefit::find($booking->benefit_id);
            if ($benefit) {
                $remainingHours = (int) floor(($benefit->total_minutes - $benefit->used_minutes) / 60);
                return $remainingHours . ' Jam';
            }
        }

        // ── Source 2: UserRoomQuota (manual/paket purchase) ───────────────────
        $quota = UserRoomQuota::where('user_id', $booking->user_id)->first();
        if ($quota) {
            $remainingHours = (int) floor($quota->remaining_seconds / 3600);
            return $remainingHours . ' Jam';
        }

        // ── Source 3: Check all active meeting benefits for user ──────────────
        $allBenefits = RoomBenefit::where('user_id', $booking->user_id)
            ->where('is_active', true)
            ->whereIn('type', ['meeting', 'shared'])
            ->where(function ($q) {
                $q->whereNull('expired_at')
                  ->orWhere('expired_at', '>', now());
            })
            ->get();

        if ($allBenefits->isNotEmpty()) {
            $totalRemaining = $allBenefits->sum(fn($b) => $b->total_minutes - $b->used_minutes);
            $remainingHours = (int) floor($totalRemaining / 60);
            return $remainingHours . ' Jam';
        }

        Log::warning('WhatsAppService::calculateMeetingRoomRemainingQuota - Tidak dapat menghitung sisa kuota.', [
            'booking_id' => $booking->id,
            'user_id'    => $booking->user_id,
        ]);

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
}

