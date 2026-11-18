<?php
// Daftar semua pertanyaan untuk tombol saran di chatbot.
require_once __DIR__ . '/includes/db.php';
header('Content-Type: application/json; charset=utf-8');
try {
    $rows = db()->query('SELECT kategori, pertanyaan FROM faq ORDER BY id')->fetchAll();
    echo json_encode($rows, JSON_UNESCAPED_UNICODE);
} catch (Throwable $ex) {
    error_log('faq_list.php: ' . $ex->getMessage());
    http_response_code(500);
    echo json_encode([]);
}
