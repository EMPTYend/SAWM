<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';

$user = $_GET['user'] ?? 'guest';
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
    <h1>Admin Area (Not Protected Yet)</h1>
    <p>Welcome, <strong><?= htmlspecialchars($user, ENT_QUOTES, 'UTF-8') ?></strong>.</p>
    <p>You can open this page directly without authentication. This is intentional for Lab 1.</p>
    <a href="index.php">Back to login</a>
</main>
</body>
</html>

