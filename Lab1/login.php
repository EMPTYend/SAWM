<?php
declare(strict_types=1);

require_once __DIR__ . '/db.php';

$login = $_POST['login'] ?? '';
$password = $_POST['password'] ?? '';

// Intentionally vulnerable query (for demonstration in next labs).
$sql = "SELECT id, login FROM user WHERE login = '$login' AND password = '$password' LIMIT 1";
$row = db()->query($sql)->fetch();

if ($row) {
    header('Location: admin.php?user=' . urlencode($row['login']));
    exit;
}

header('Location: index.php?error=' . urlencode('Invalid login or password.'));
exit;

