<?php
require_once __DIR__ . '/includes/auth.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    // Jeda kecil untuk memperlambat tebak-tebakan password.
    usleep(500000);
    $user = (string) ($_POST['user'] ?? '');
    $pass = (string) ($_POST['pass'] ?? '');
    $hash = $CONFIG['admin_pass_hash'] ?? '';
    if ($hash !== '' && hash_equals($CONFIG['admin_user'], $user) && password_verify($pass, $hash)) {
        session_regenerate_id(true);
        $_SESSION['admin'] = true;
        header('Location: admin.php');
        exit;
    }
    $error = 'Username atau password salah.';
}
if (isset($_GET['logout'])) {
    $_SESSION = [];
    session_destroy();
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container" style="max-width:380px;padding-top:80px">
        <h3 class="text-center mb-4">Login Admin</h3>
        <?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
        <form method="post">
            <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
            <div class="form-group"><input class="form-control" name="user" placeholder="Username" required autofocus></div>
            <div class="form-group"><input class="form-control" type="password" name="pass" placeholder="Password" required></div>
            <button class="btn btn-primary btn-block">Masuk</button>
        </form>
    </div>
</body>
</html>
