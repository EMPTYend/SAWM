<?php
declare(strict_types=1);

require_once __DIR__ . '/auth.php';

require_permission('accounts.delete');

$id = (int) ($_GET['id'] ?? 0);
if ($id <= 0) {
    header('Location: accounts.php?error=' . urlencode('Invalid user id.'));
    exit;
}

$delete = db()->prepare('DELETE FROM user WHERE id = :id AND role <> :admin_role');
$delete->execute([
    'id' => $id,
    'admin_role' => 'administrator',
]);

if ($delete->rowCount() < 1) {
    header('Location: accounts.php?error=' . urlencode('Delete failed or target is protected.'));
    exit;
}

header('Location: accounts.php?message=' . urlencode('User deleted.'));
exit;

