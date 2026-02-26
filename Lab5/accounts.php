<?php
declare(strict_types=1);

require_once __DIR__ . '/auth.php';

$user = require_permission('accounts.view');
$users = db()->query('SELECT id, login, role FROM user ORDER BY id')->fetchAll();
$message = $_GET['message'] ?? '';
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
<main class="card card-wide">
    <h1>Account Management</h1>
    <p>Signed in as <strong><?= htmlspecialchars($user['login'], ENT_QUOTES, 'UTF-8') ?></strong>.</p>

    <?php if ($error !== ''): ?>
        <div class="error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <?php if ($message !== ''): ?>
        <div class="success"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>Login</th>
            <th>Role</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($users as $row): ?>
            <tr>
                <td><?= (int) $row['id'] ?></td>
                <td><?= htmlspecialchars($row['login'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($row['role'], ENT_QUOTES, 'UTF-8') ?></td>
                <td>
                    <?php if ($row['role'] !== 'administrator'): ?>
                        <a href="edit_user.php?id=<?= (int) $row['id'] ?>">Edit</a>
                        |
                        <a href="delete_user.php?id=<?= (int) $row['id'] ?>" onclick="return confirm('Delete this user?');">Delete</a>
                    <?php else: ?>
                        Protected
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <p><a href="manager.php">Back to manager panel</a></p>
    <p><a href="logout.php">Logout</a></p>
</main>
</body>
</html>

