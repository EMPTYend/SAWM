<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/auth.php';

$sessionUser = current_user();
if ($sessionUser !== null) {
    if ($sessionUser['role'] === 'administrator') {
        header('Location: admin.php');
        exit;
    }

    if ($sessionUser['role'] === 'manager') {
        header('Location: manager.php');
        exit;
    }
}

$error = $_GET['error'] ?? '';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= APP_TITLE ?></title>
    <link rel="stylesheet" href="style.css">
    <script defer src="validation.js"></script>
</head>
<body>
<main class="card">
    <h1>Lab 5: Access Control and Sessions</h1>
    <p>Authentication is mandatory. Access is role-based for administrator and manager users.</p>

    <?php if ($error !== ''): ?>
        <div class="error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <form action="login.php" method="post" id="login-form" novalidate>
        <label for="login">Login</label>
        <input id="login" name="login" type="text" placeholder="admin" minlength="3" maxlength="50" required>

        <label for="password">Password</label>
        <input id="password" name="password" type="password" placeholder="admin123" minlength="6" maxlength="72" required>

        <button type="submit">Sign In</button>
    </form>

    <p><a href="guestbook.php">Open Guest Book</a></p>
    <p>Test users: <code>admin/admin123</code>, <code>manager/manager123</code>.</p>
</main>
</body>
</html>
