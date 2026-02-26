<?php
declare(strict_types=1);

require_once __DIR__ . '/auth.php';

$user = require_role('administrator');
log_action('admin_panel_open');
$users = db()->query('SELECT id, login, password FROM user ORDER BY id')->fetchAll();
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
<main class="card card-wide">
    <h1>Administrator Panel</h1>
    <p>Welcome, <strong><?= htmlspecialchars($user['login'], ENT_QUOTES, 'UTF-8') ?></strong> (role: administrator).</p>
    <p>Role-based session control is enabled. Direct URL access without auth no longer works.</p>

    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>Login</th>
            <th>Password Hash</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($users as $row): ?>
            <tr>
                <td><?= (int) $row['id'] ?></td>
                <td><?= htmlspecialchars($row['login'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($row['password'], ENT_QUOTES, 'UTF-8') ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <p><a href="view_logs.php">View Action Logs</a></p>
    <p><a href="view_errors.php">View Error Logs</a></p>
    <p><a href="guestbook.php">Guest Book</a></p>
    <p><a href="logout.php">Logout</a></p>
</main>
</body>
</html>
