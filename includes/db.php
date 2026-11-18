<?php
// Koneksi database. Kredensial dibaca dari includes/config.local.php (tidak ikut di-commit).
$cfgFile = __DIR__ . '/config.local.php';
if (!is_file($cfgFile)) {
    http_response_code(500);
    exit('Konfigurasi belum dibuat. Salin includes/config.example.php menjadi includes/config.local.php.');
}
$CONFIG = require $cfgFile;

function db(): PDO
{
    global $CONFIG;
    static $pdo = null;
    if ($pdo === null) {
        $dsn = "mysql:host={$CONFIG['db_host']};dbname={$CONFIG['db_name']};charset=utf8mb4";
        $pdo = new PDO($dsn, $CONFIG['db_user'], $CONFIG['db_pass'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    }
    return $pdo;
}

function e($s): string
{
    return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8');
}
