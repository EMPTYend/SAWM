<?php
declare(strict_types=1);

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/logger.php';

$login = trim($_POST['login'] ?? '');
$password = trim($_POST['password'] ?? '');

$loginPattern = '/^[A-Za-z0-9_]{3,50}$/';
$passwordPattern = '/^[A-Za-z0-9_!@#$%^&*.\-]{6,72}$/';

if (!preg_match($loginPattern, $login) || !preg_match($passwordPattern, $password)) {
    log_action('login_rejected_by_validation', ['login' => $login]);
    header('Location: index.php?error=' . urlencode('Input contains blocked symbols.'));
    exit;
}

$stmt = db()->prepare('SELECT id, login, password, role FROM user WHERE login = :login LIMIT 1');
$stmt->execute([
    'login' => $login,
]);
$row = $stmt->fetch();

if ($row && password_verify($password, $row['password'])) {
    login_user($row);
    log_action('login_success', ['login' => $row['login'], 'role' => $row['role']]);

    if ($row['role'] === 'administrator') {
        header('Location: admin.php');
        exit;
    }

    if ($row['role'] === 'manager') {
        header('Location: manager.php');
        exit;
    }
}

log_action('login_failure', ['login' => $login]);
header('Location: index.php?error=' . urlencode('Invalid login or password.'));
exit;
