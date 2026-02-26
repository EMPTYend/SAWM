<?php
declare(strict_types=1);

require_once __DIR__ . '/db.php';

$user = $_GET['user'] ?? 'guest';
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
    <h1>Admin Area: Stored Credentials</h1>
    <p>Welcome, <strong><?= htmlspecialchars($user, ENT_QUOTES, 'UTF-8') ?></strong>.</p>
    <p>This lab demonstrates that passwords are now stored as secure salted hashes.</p>

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

    <a href="index.php">Back to login</a>
</main>
</body>
</html>
