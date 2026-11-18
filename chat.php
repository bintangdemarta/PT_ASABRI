<?php
// Endpoint chatbot FAQ: POST/GET q=pertanyaan, balasan JSON.
require_once __DIR__ . '/includes/faq.php';
header('Content-Type: application/json; charset=utf-8');

$q = trim((string) ($_POST['q'] ?? $_GET['q'] ?? ''));
if ($q === '' || mb_strlen($q) > 300) {
    http_response_code(400);
    echo json_encode(['error' => 'Pertanyaan kosong atau terlalu panjang.']);
    exit;
}

try {
    $hits = faq_search($q, 3);
} catch (Throwable $ex) {
    error_log('chat.php: ' . $ex->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Layanan sedang bermasalah.']);
    exit;
}

if (!$hits) {
    echo json_encode([
        'found' => false,
        'answer' => 'Maaf, belum ada jawaban untuk pertanyaan itu. Silakan hubungi call center 1500043 atau kirim pesan lewat form kontak di halaman utama.',
    ]);
    exit;
}

$best = array_shift($hits);
echo json_encode([
    'found' => true,
    'question' => $best['pertanyaan'],
    'answer' => $best['jawaban'],
    'source' => $best['sumber'],
    'related' => array_map(fn($h) => $h['pertanyaan'], $hits),
], JSON_UNESCAPED_UNICODE);
