<?php
require_once __DIR__ . '/includes/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: asabri.html');
    exit;
}

$nama = trim((string) ($_POST['nama'] ?? ''));
$nomor = preg_replace('/\D/', '', (string) ($_POST['nomor'] ?? ''));
$subject = trim((string) ($_POST['subject'] ?? ''));
$message = trim((string) ($_POST['message'] ?? ''));

$errors = [];
if ($nama === '' || mb_strlen($nama) > 100) $errors[] = 'Nama wajib diisi (maks. 100 karakter).';
if (strlen($nomor) < 9 || strlen($nomor) > 15) $errors[] = 'Nomor HP harus 9–15 digit.';
if ($subject === '' || mb_strlen($subject) > 150) $errors[] = 'Subject wajib diisi (maks. 150 karakter).';
if ($message === '' || mb_strlen($message) > 5000) $errors[] = 'Pesan wajib diisi (maks. 5000 karakter).';

if ($errors) {
    http_response_code(422);
    echo '<p>' . implode('<br>', array_map('e', $errors)) . '</p><p><a href="asabri.html#contact">Kembali</a></p>';
    exit;
}

try {
    $stmt = db()->prepare('INSERT INTO data_contact (nama, nomor, subject, message) VALUES (?, ?, ?, ?)');
    $stmt->execute([$nama, $nomor, $subject, $message]);
} catch (Throwable $ex) {
    error_log('process_form.php: ' . $ex->getMessage());
    http_response_code(500);
    echo '<p>Pesan gagal disimpan. Silakan coba lagi nanti.</p><p><a href="asabri.html">Kembali</a></p>';
    exit;
}

header('Location: asabri.html?terkirim=1');
exit;
