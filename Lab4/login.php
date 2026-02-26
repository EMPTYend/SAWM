<?php
declare(strict_types=1);

require_once __DIR__ . '/db.php';

$login = trim($_POST['login'] ?? '');
$password = trim($_POST['password'] ?? '');

$loginPattern = '/^[A-Za-z0-9_]{3,50}$/';
$passwordPattern = '/^[A-Za-z0-9_!@#$%^&*.\-]{6,72}$/';

if (!preg_match($loginPattern, $login) || !preg_match($passwordPattern, $password)) {
    header('Location: index.php?error=' . urlencode('Input contains blocked symbols.'));
    exit;
}

$stmt = db()->prepare('SELECT id, login, password FROM user WHERE login = :login LIMIT 1');
$stmt->execute([
    'login' => $login,
]);
$row = $stmt->fetch();

if ($row && password_verify($password, $row['password'])) {
    header('Location: admin.php?user=' . urlencode($row['login']));
    exit;
}

header('Location: index.php?error=' . urlencode('Invalid login or password.'));
exit;
