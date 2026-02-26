<?php
declare(strict_types=1);

function action_log_file(): string
{
    $dir = __DIR__ . '/logs';
    if (!is_dir($dir)) {
        mkdir($dir, 0775, true);
    }

    return $dir . '/actions.log';
}

function log_action(string $action, array $details = []): void
{
    $userData = (session_status() === PHP_SESSION_ACTIVE) ? ($_SESSION['user'] ?? null) : null;

    $payload = [
        'time' => date('c'),
        'action' => $action,
        'user_id' => $userData['id'] ?? null,
        'login' => $userData['login'] ?? 'anonymous',
        'role' => $userData['role'] ?? 'none',
        'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
        'details' => $details,
    ];

    file_put_contents(
        action_log_file(),
        json_encode($payload, JSON_UNESCAPED_SLASHES) . PHP_EOL,
        FILE_APPEND | LOCK_EX
    );
}
