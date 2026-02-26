<?php
declare(strict_types=1);

if (!function_exists('app_error_log_file')) {
    function app_error_log_file(): string
    {
        $dir = __DIR__ . '/logs';
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        return $dir . '/errors.log';
    }
}

if (!function_exists('app_log_error')) {
    function app_log_error(string $kind, string $message, string $file = '', int $line = 0, array $extra = []): void
    {
        $context = [
            'time' => date('c'),
            'kind' => $kind,
            'message' => $message,
            'file' => $file,
            'line' => $line,
            'method' => $_SERVER['REQUEST_METHOD'] ?? 'CLI',
            'uri' => $_SERVER['REQUEST_URI'] ?? 'CLI',
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            'extra' => $extra,
        ];

        if (session_status() === PHP_SESSION_ACTIVE && isset($_SESSION['user'])) {
            $context['user'] = $_SESSION['user'];
        }

        file_put_contents(
            app_error_log_file(),
            json_encode($context, JSON_UNESCAPED_SLASHES) . PHP_EOL,
            FILE_APPEND | LOCK_EX
        );
    }
}

ini_set('display_errors', '0');
error_reporting(E_ALL);

set_error_handler(
    static function (int $severity, string $message, string $file, int $line): bool {
        app_log_error('php_error', $message, $file, $line, ['severity' => $severity]);
        return true;
    }
);

set_exception_handler(
    static function (Throwable $throwable): void {
        app_log_error(
            'uncaught_exception',
            $throwable->getMessage(),
            $throwable->getFile(),
            $throwable->getLine(),
            ['trace' => $throwable->getTraceAsString()]
        );

        if (!headers_sent()) {
            header('Location: safe.html');
        } else {
            echo '<a href="safe.html">Open safe page</a>';
        }
        exit;
    }
);

register_shutdown_function(
    static function (): void {
        $fatal = error_get_last();
        if ($fatal === null) {
            return;
        }

        $fatalTypes = [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR];
        if (!in_array($fatal['type'], $fatalTypes, true)) {
            return;
        }

        app_log_error('fatal_shutdown', $fatal['message'], $fatal['file'], $fatal['line'], ['severity' => $fatal['type']]);

        if (!headers_sent()) {
            header('Location: safe.html');
        } else {
            echo '<a href="safe.html">Open safe page</a>';
        }
    }
);

