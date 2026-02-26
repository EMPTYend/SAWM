<?php
declare(strict_types=1);

require_once __DIR__ . '/auth.php';

$user = require_permission('accounts.delete');

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
    log_action('account_delete_failed', [
        'target_user_id' => $id,
        'reason' => 'protected_or_not_found',
    ]);
    header('Location: accounts.php?error=' . urlencode('Delete failed or target is protected.'));
    exit;
}

log_action('account_deleted', [
    'target_user_id' => $id,
    'by_user_id' => $user['id'],
]);
header('Location: accounts.php?message=' . urlencode('User deleted.'));
exit;
