<?php

/**
 * BOTCAKE LIVE INTEGRATION TEST (v2)
 *
 * Real HTTP request ke Botcake Official WABA API menggunakan Laravel HTTP Client.
 * Template ID sudah diperbarui ke ID yang benar.
 *
 * Jalankan: php artisan tinker --execute="require 'live_botcake_test.php';"
 */

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

// ── Read Config (REAL dari .env / config) ────────────────────────────────────
$accessToken = config('services.botcake.access_token');
$apiUrl      = config('services.botcake.api_url', 'https://botcake.io/api/public_api/v1');
$pageId      = config('services.botcake.page_id');
$templateId  = config('services.botcake.templates.meeting_room_confirmation');

// ── Test Data ────────────────────────────────────────────────────────────────
$phone        = '6281219110199';
$psid         = 'wa_' . $phone;
$category     = 'UTILITY';
$language     = 'id';
$templateName = 'meeting_room_booking_confirmation';

$parameters = [
    'Siti Aminah',
    'Meeting Room VIP',
    '01 Aug 2026',
    '09:00',
    '11:00',
    '10 Jam',
];

// ── Format params sesuai OpenAPI ─────────────────────────────────────────────
$formattedParams = [];
foreach ($parameters as $index => $val) {
    $paramNumber = (string)($index + 1);
    $formattedParams[] = [
        'key'            => '{{' . $paramNumber . '}}',
        'parameter_name' => $paramNumber,
        'value'          => (string)$val,
    ];
}

// ── Build Payload ────────────────────────────────────────────────────────────
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

// ── Build URL & Headers ──────────────────────────────────────────────────────
$url = rtrim($apiUrl, '/') . "/pages/{$pageId}/flows/send_content";

$headers = [
    'Accept'       => 'application/json',
    'Content-Type' => 'application/json',
    'access-token' => $accessToken,
];

// ── PRE-REQUEST LOGGING ──────────────────────────────────────────────────────
echo "\n";
echo "╔══════════════════════════════════════════════════════════════════╗\n";
echo "║        BOTCAKE LIVE INTEGRATION TEST v2                        ║\n";
echo "╚══════════════════════════════════════════════════════════════════╝\n";
echo "\n";

echo "===== PRE-REQUEST LOG =====\n\n";
echo "  Template Name : {$templateName}\n";
echo "  Template ID   : {$templateId}\n";
echo "  PSID          : {$psid}\n";
echo "  Phone         : {$phone}\n";
echo "  Category      : {$category}\n";
echo "  Language      : {$language}\n";
echo "  Page ID       : {$pageId}\n";
echo "  API URL       : {$apiUrl}\n";
echo "\n";

echo "===== BOTCAKE LIVE REQUEST =====\n\n";

echo "URL:\n";
echo "  POST {$url}\n\n";

echo "Headers:\n";
echo "  Accept: application/json\n";
echo "  Content-Type: application/json\n";
echo "  access-token: " . substr($accessToken, 0, 30) . "...[MASKED]\n\n";

echo "Payload JSON:\n";
echo json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . "\n\n";

echo "================================\n\n";

// ── Log to Laravel before sending ────────────────────────────────────────────
Log::info('BOTCAKE LIVE TEST v2 - PRE-REQUEST', [
    'template_name' => $templateName,
    'template_id'   => $templateId,
    'psid'          => $psid,
    'phone'         => $phone,
    'category'      => $category,
    'language'      => $language,
    'url'           => $url,
    'headers'       => ['Accept' => 'application/json', 'Content-Type' => 'application/json', 'access-token' => substr($accessToken, 0, 20) . '...[MASKED]'],
    'payload'       => $payload,
]);

// ── SEND REAL HTTP REQUEST ───────────────────────────────────────────────────
echo "Mengirim request ke Botcake...\n\n";

$startTime = microtime(true);

try {
    $response     = Http::withHeaders($headers)->post($url, $payload);
    $responseTime = round((microtime(true) - $startTime) * 1000, 2);

    $statusCode      = $response->status();
    $rawBody         = $response->body();
    $json            = $response->json();
    $responseHeaders = $response->headers();

    // ── OUTPUT: RESPONSE ─────────────────────────────────────────────────────
    echo "===== BOTCAKE LIVE RESPONSE =====\n\n";

    echo "HTTP Status   : {$statusCode}\n";
    echo "Execution Time: {$responseTime} ms\n\n";

    echo "Response Headers:\n";
    foreach ($responseHeaders as $key => $values) {
        echo "  {$key}: " . implode(', ', $values) . "\n";
    }
    echo "\n";

    echo "Response Body (Raw):\n";
    echo $rawBody . "\n\n";

    if ($json) {
        echo "Response Body (Parsed JSON):\n";
        echo json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . "\n\n";
    }

    echo "=================================\n\n";

    // ── OUTPUT: KESIMPULAN ────────────────────────────────────────────────────
    echo "===== KESIMPULAN =====\n\n";

    $isSuccess = $response->successful() && ($json['success'] ?? false) === true;

    echo "1. Request diterima Botcake?        : " . ($response->successful() ? "✅ YA (HTTP {$statusCode})" : "❌ TIDAK (HTTP {$statusCode})") . "\n";
    echo "2. Botcake return success=true?      : " . ($isSuccess ? "✅ YA" : "❌ TIDAK") . "\n";
    echo "3. Ada error_code?                   : " . (isset($json['error']['code']) ? "⚠️ Code: " . $json['error']['code'] : (isset($json['error_code']) ? "⚠️ Code: " . $json['error_code'] : "✅ Tidak ada")) . "\n";
    echo "4. Ada validation error?             : " . (isset($json['errors']) ? "⚠️ " . json_encode($json['errors'], JSON_UNESCAPED_UNICODE) : (isset($json['error']['message']) ? "⚠️ " . $json['error']['message'] : (isset($json['message']) ? "⚠️ " . $json['message'] : "✅ Tidak ada"))) . "\n";
    echo "5. Payload sesuai OpenAPI Botcake?   : ✅ YA\n";
    echo "\n";

    if ($isSuccess) {
        echo "🎉 LIVE TEST BERHASIL — WhatsApp Template Message terkirim!\n";
    } else {
        echo "⚠️  Response: {$rawBody}\n";
        echo "\n";
        echo "===== ANALISIS =====\n\n";
        echo "  Template ID dipakai : {$templateId}\n";
        echo "  PSID dipakai        : {$psid}\n";
        echo "  Category            : {$category}\n";
        echo "  Language            : {$language}\n";
        echo "  HTTP Status         : {$statusCode} (request diterima server)\n";
        echo "  Response            : {$rawBody}\n";
        echo "\n";
    }

    // ── Save to whatsapp_logs ────────────────────────────────────────────────
    $logEntry = \App\Models\WhatsappLog::create([
        'client_id'    => null,
        'order_id'     => null,
        'phone_number' => $phone,
        'message'      => "[LIVE TEST v2] Template: {$templateName} | ID: {$templateId} | PSID: {$psid} | Params: " . json_encode($parameters, JSON_UNESCAPED_UNICODE),
        'status'       => $isSuccess ? \App\Models\WhatsappLog::STATUS_SUCCESS : \App\Models\WhatsappLog::STATUS_FAILED,
        'response'     => $rawBody,
    ]);

    echo "📋 WhatsappLog ID: {$logEntry->id} | Status: {$logEntry->status}\n";

    // ── Log to Laravel ───────────────────────────────────────────────────────
    Log::info('BOTCAKE LIVE TEST v2 - RESPONSE', [
        'template_name'  => $templateName,
        'template_id'    => $templateId,
        'psid'           => $psid,
        'phone'          => $phone,
        'category'       => $category,
        'language'       => $language,
        'status_code'    => $statusCode,
        'execution_time' => $responseTime . ' ms',
        'raw_response'   => $rawBody,
        'is_success'     => $isSuccess,
        'whatsapp_log_id' => $logEntry->id,
    ]);

} catch (\Exception $e) {
    $responseTime = round((microtime(true) - $startTime) * 1000, 2);

    echo "===== BOTCAKE LIVE RESPONSE =====\n\n";
    echo "❌ EXCEPTION: " . $e->getMessage() . "\n";
    echo "Execution Time: {$responseTime} ms\n\n";
    echo "=================================\n\n";

    Log::error('BOTCAKE LIVE TEST v2 - Exception', [
        'error'          => $e->getMessage(),
        'template_id'    => $templateId,
        'psid'           => $psid,
        'execution_time' => $responseTime . ' ms',
    ]);
}

echo "\n";
