<?php
// Pencarian FAQ (dulu mencari tabel data_pencarian berisi nama/alamat/telepon; diganti karena membuka data pribadi ke publik).
require_once __DIR__ . '/includes/faq.php';

$term = trim((string) ($_GET['term'] ?? ''));
if ($term === '' || mb_strlen($term) > 100) {
    exit;
}
$hits = faq_search($term, 5);
if (!$hits) {
    echo 'Tidak ada hasil ditemukan';
    exit;
}
foreach ($hits as $h) {
    echo "<p class='result-item'><b>" . e($h['pertanyaan']) . '</b><br>' . e($h['jawaban']) . '</p>';
}
