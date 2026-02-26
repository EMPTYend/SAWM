<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';

$error = $_GET['error'] ?? '';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= APP_TITLE ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<main class="card">
    <h1>Lab 1: Basic Auth (Vulnerable)</h1>
    <p>This page is intentionally vulnerable and used as a baseline for next labs.</p>

    <?php if ($error !== ''): ?>
        <div class="error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <form action="login.php" method="post">
        <label for="login">Login</label>
        <input id="login" name="login" type="text" placeholder="admin">

        <label for="password">Password</label>
        <input id="password" name="password" type="password" placeholder="123456">

        <button type="submit">Sign In</button>
    </form>
</main>
</body>
</html>

