<?php
declare(strict_types=1);

require_once __DIR__ . '/auth.php';

$user = require_role('manager');
log_action('manager_panel_open');
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
    <h1>Manager Panel</h1>
    <p>Welcome, <strong><?= htmlspecialchars($user['login'], ENT_QUOTES, 'UTF-8') ?></strong> (role: manager).</p>
    <p>Available functions:</p>
    <p><a href="accounts.php">1) View user accounts</a></p>
    <p><a href="accounts.php">2) Edit or delete user accounts</a></p>
    <p><a href="guestbook.php">Guest Book</a></p>
    <p><a href="logout.php">Logout</a></p>
</main>
</body>
</html>
