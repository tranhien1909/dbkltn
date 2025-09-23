<?php
$base  = getenv('OPENAI_BASE_URL') ?: 'https://api.groq.com/openai/v1';
$key   = getenv('OPENAI_API_KEY') ?: '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';
$model = getenv('OPENAI_MODEL') ?: 'llama-3.1-8b-instant';

$payload = [
    'model' => $model,
    // Test đơn giản nhất cho /responses: chỉ cần "input" là chuỗi
    'input' => 'ping'
];

$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => rtrim($base, '/') . '/responses',
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => [
        'Authorization: Bearer ' . $key,
        'Content-Type: application/json'
    ],
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POSTFIELDS => json_encode($payload),
    CURLOPT_TIMEOUT => 30
]);
$res  = curl_exec($ch);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

http_response_code($code);
header('Content-Type: application/json; charset=utf-8');
echo $res;
