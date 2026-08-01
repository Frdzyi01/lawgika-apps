<?php

/**
 * Simulasi Payload — Botcake Official WABA API
 *
 * Script ini mensimulasikan payload yang dibentuk oleh
 * WhatsAppService::sendTemplateById() untuk memastikan
 * payload IDENTIK dengan spesifikasi OpenAPI Botcake.
 *
 * Jalankan: php test_botcake_payload.php
 */

// ── Data Test ────────────────────────────────────────────────────────────────
$clientName  = 'Siti Aminah';
$phone       = '6281219110199';
$templateId  = '1852676328789174'; // Meeting Room Confirmation
$category    = 'UTILITY';
$language    = 'id';
$pageId      = 'waba_1053844194483527';
$baseUrl     = 'https://botcake.io/api/public_api/v1';

// ── Simulate formatPsid() ────────────────────────────────────────────────────
$psid = 'wa_' . $phone;

// ── Simulate parameters (Meeting Room Confirmation: 6 params) ────────────────
$parameters = [
    'Siti Aminah',           // {{1}} Nama Client
    'Meeting Room VIP',      // {{2}} Nama Ruangan
    '01 Aug 2026',           // {{3}} Tanggal Meeting
    '09:00',                 // {{4}} Jam Mulai
    '11:00',                 // {{5}} Jam Selesai
    '10 Jam',                // {{6}} Sisa Kuota
];

// ── Format params sesuai OpenAPI Botcake ──────────────────────────────────────
$formattedParams = [];
foreach ($parameters as $index => $val) {
    $paramNumber = (string)($index + 1);
    $formattedParams[] = [
        'key'            => '{{' . $paramNumber . '}}',
        'parameter_name' => $paramNumber,
        'value'          => (string)$val,
    ];
}

// ── Build Payload 100% sesuai OpenAPI ─────────────────────────────────────────
$payload = [
    'psid' => $psid,
    'data' => [
        'version' => 'v2',
        'content' => [
            'messages' => [
                [
                    'type'        => 'whatsapp_message_template',
                    'template_id' => $templateId,
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

// ── Build URL ─────────────────────────────────────────────────────────────────
$url = $baseUrl . '/pages/' . $pageId . '/flows/send_content';

// ── Output ────────────────────────────────────────────────────────────────────
echo "╔══════════════════════════════════════════════════════════════════╗\n";
echo "║ SIMULASI PAYLOAD — BOTCAKE OFFICIAL WABA API                   ║\n";
echo "╚══════════════════════════════════════════════════════════════════╝\n\n";

echo "── ENDPOINT ──────────────────────────────────────────────────────\n";
echo "POST {$url}\n\n";

echo "── HEADERS ─────────────────────────────────────────────────────────\n";
echo "Accept: application/json\n";
echo "Content-Type: application/json\n";
echo "access-token: eyJhbGciOiJIUzI1NiIsInR5cCI6...[MASKED]\n\n";

echo "── PSID ────────────────────────────────────────────────────────────\n";
echo "Input Phone : {$phone}\n";
echo "Output PSID : {$psid}\n\n";

echo "── TEMPLATE ────────────────────────────────────────────────────────\n";
echo "Template ID : {$templateId}\n";
echo "Category    : {$category}\n";
echo "Language    : {$language}\n\n";

echo "── PAYLOAD JSON ─────────────────────────────────────────────────\n";
echo json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . "\n\n";

echo "── VALIDASI ─────────────────────────────────────────────────────\n";

$checks = [
    'Root key psid exists'       => isset($payload['psid']),
    'PSID format wa_628xxx'      => str_starts_with($payload['psid'], 'wa_62'),
    'data.version = v2'          => ($payload['data']['version'] ?? '') === 'v2',
    'messages is array'          => is_array($payload['data']['content']['messages'] ?? null),
    'type = whatsapp_message_template' => ($payload['data']['content']['messages'][0]['type'] ?? '') === 'whatsapp_message_template',
    'template_id is string'      => is_string($payload['data']['content']['messages'][0]['template_id'] ?? null),
    'template_id is numeric'     => is_numeric($payload['data']['content']['messages'][0]['template_id'] ?? ''),
    'language = id'              => ($payload['data']['content']['messages'][0]['language'] ?? '') === 'id',
    'category = UTILITY'         => ($payload['data']['content']['messages'][0]['category'] ?? '') === 'UTILITY',
    'components[0].type = BODY'  => ($payload['data']['content']['messages'][0]['components'][0]['type'] ?? '') === 'BODY',
    'params count = 6'           => count($payload['data']['content']['messages'][0]['components'][0]['params'] ?? []) === 6,
    'param[0].key = {{1}}'       => ($payload['data']['content']['messages'][0]['components'][0]['params'][0]['key'] ?? '') === '{{1}}',
    'param[0].parameter_name = 1' => ($payload['data']['content']['messages'][0]['components'][0]['params'][0]['parameter_name'] ?? '') === '1',
    'param[0].value is string'   => is_string($payload['data']['content']['messages'][0]['components'][0]['params'][0]['value'] ?? null),
    'NO template_name key'       => !isset($payload['data']['content']['messages'][0]['template_name']),
    'NO action key'              => !isset($payload['action']),
    'NO page_access_token'       => !str_contains($url, 'page_access_token'),
];

$allPassed = true;
foreach ($checks as $check => $result) {
    $icon = $result ? '✅' : '❌';
    echo "  {$icon} {$check}\n";
    if (!$result) $allPassed = false;
}

echo "\n";
if ($allPassed) {
    echo "🎉 SEMUA VALIDASI PASSED — Payload IDENTIK dengan OpenAPI Botcake\n";
} else {
    echo "⚠️  ADA VALIDASI YANG GAGAL — Periksa kembali payload\n";
}
echo "\n";
