<?php
declare(strict_types=1);

require_once __DIR__ . '/db.php';

function start_secure_session(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }

    session_name(SESSION_NAME);

    $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => $isHttps,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);

    session_start();
}

function login_user(array $user): void
{
    start_secure_session();
    session_regenerate_id(true);

    $_SESSION['user'] = [
        'id' => (int) $user['id'],
        'login' => (string) $user['login'],
        'role' => (string) $user['role'],
    ];
}

function current_user(): ?array
{
    start_secure_session();
    return $_SESSION['user'] ?? null;
}

function require_auth(): array
{
    $user = current_user();
    if ($user === null) {
        header('Location: index.php?error=' . urlencode('Authentication is required.'));
        exit;
    }

    return $user;
}

function require_role(string $role): array
{
    $user = require_auth();
    if ($user['role'] !== $role) {
        header('Location: index.php?error=' . urlencode('Access denied for this role.'));
        exit;
    }

    return $user;
}

function has_permission(string $permissionCode): bool
{
    $user = current_user();
    if ($user === null) {
        return false;
    }

    $stmt = db()->prepare('
        SELECT 1
        FROM role r
        JOIN role_permission rp ON rp.role_id = r.id
        JOIN permission p ON p.id = rp.permission_id
        WHERE r.code = :role_code AND p.code = :permission_code
        LIMIT 1
    ');
    $stmt->execute([
        'role_code' => $user['role'],
        'permission_code' => $permissionCode,
    ]);

    return (bool) $stmt->fetchColumn();
}

function require_permission(string $permissionCode): array
{
    $user = require_auth();
    if (!has_permission($permissionCode)) {
        header('Location: index.php?error=' . urlencode('Permission denied.'));
        exit;
    }

    return $user;
}

function logout_user(): void
{
    start_secure_session();
    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'] ?? '', $params['secure'], $params['httponly']);
    }

    session_destroy();
}

