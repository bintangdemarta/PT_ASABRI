<?php
require_once __DIR__ . '/includes/auth.php';
require_admin();

$id = filter_var($_REQUEST['id'] ?? null, FILTER_VALIDATE_INT);
if (!$id) {
    header('Location: admin.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    $nama = trim((string) ($_POST['nama'] ?? ''));
    $nomor = preg_replace('/\D/', '', (string) ($_POST['nomor'] ?? ''));
    $subject = trim((string) ($_POST['subject'] ?? ''));
    $message = trim((string) ($_POST['message'] ?? ''));
    if ($nama === '' || $subject === '' || $message === '' || strlen($nomor) < 9 || strlen($nomor) > 15) {
        $error = 'Semua kolom wajib diisi, nomor HP 9–15 digit.';
    } else {
        db()->prepare('UPDATE data_contact SET nama = ?, nomor = ?, subject = ?, message = ? WHERE id = ?')
            ->execute([$nama, $nomor, $subject, $message, $id]);
        header('Location: admin.php');
        exit;
    }
}

$stmt = db()->prepare('SELECT * FROM data_contact WHERE id = ?');
$stmt->execute([$id]);
$row = $stmt->fetch();
if (!$row) {
    header('Location: admin.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container" style="max-width:640px;padding-top:30px">
        <h3 class="mb-3">Edit Data Kontak</h3>
        <?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
        <form method="post">
            <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
            <input type="hidden" name="id" value="<?= (int) $row['id'] ?>">
            <div class="form-group"><label>Nama</label><input class="form-control" name="nama" maxlength="100" value="<?= e($row['nama']) ?>" required></div>
            <div class="form-group"><label>Nomor HP</label><input class="form-control" name="nomor" maxlength="15" value="<?= e($row['nomor']) ?>" required></div>
            <div class="form-group"><label>Subject</label><input class="form-control" name="subject" maxlength="150" value="<?= e($row['subject']) ?>" required></div>
            <div class="form-group"><label>Pesan</label><textarea class="form-control" name="message" rows="6" required><?= e($row['message']) ?></textarea></div>
            <button class="btn btn-primary">Simpan</button>
            <a href="admin.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</body>
</html>
