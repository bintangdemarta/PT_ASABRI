<?php
require_once __DIR__ . '/includes/auth.php';
require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit;
}
check_csrf();

$id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
if ($id) {
    db()->prepare('DELETE FROM data_contact WHERE id = ?')->execute([$id]);
}
header('Location: admin.php');
exit;
