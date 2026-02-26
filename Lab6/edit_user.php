<?php
declare(strict_types=1);

require_once __DIR__ . '/auth.php';

$sessionUser = require_permission('accounts.edit');
$id = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);

if ($id <= 0) {
    header('Location: accounts.php?error=' . urlencode('Invalid user id.'));
    exit;
}

log_action('account_edit_open', ['target_user_id' => $id]);

$stmt = db()->prepare('SELECT id, login, role FROM user WHERE id = :id LIMIT 1');
$stmt->execute(['id' => $id]);
$target = $stmt->fetch();

if (!$target || $target['role'] === 'administrator') {
    header('Location: accounts.php?error=' . urlencode('Target account is protected.'));
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newLogin = trim($_POST['login'] ?? '');
    $newRole = trim($_POST['role'] ?? 'manager');

    if (!preg_match('/^[A-Za-z0-9_]{3,50}$/', $newLogin)) {
        $error = 'Invalid login format.';
    } elseif (!in_array($newRole, ['manager'], true)) {
        $error = 'Only manager role can be assigned here.';
    } else {
        $update = db()->prepare('UPDATE user SET login = :login, role = :role WHERE id = :id AND role <> :admin_role');
        $update->execute([
            'login' => $newLogin,
            'role' => $newRole,
            'id' => $id,
            'admin_role' => 'administrator',
        ]);

        log_action('account_updated', [
            'target_user_id' => $id,
            'new_login' => $newLogin,
            'new_role' => $newRole,
        ]);

        header('Location: accounts.php?message=' . urlencode('User updated.'));
        exit;
    }
}
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
    <h1>Edit User</h1>
    <p>Manager: <strong><?= htmlspecialchars($sessionUser['login'], ENT_QUOTES, 'UTF-8') ?></strong></p>

    <?php if ($error !== ''): ?>
        <div class="error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <form action="edit_user.php" method="post">
        <input type="hidden" name="id" value="<?= (int) $target['id'] ?>">

        <label for="login">Login</label>
        <input id="login" name="login" type="text" required maxlength="50" value="<?= htmlspecialchars($target['login'], ENT_QUOTES, 'UTF-8') ?>">

        <label for="role">Role</label>
        <select id="role" name="role">
            <option value="manager" <?= $target['role'] === 'manager' ? 'selected' : '' ?>>manager</option>
        </select>

        <button type="submit">Save Changes</button>
    </form>

    <p><a href="accounts.php">Back to accounts</a></p>
</main>
</body>
</html>
