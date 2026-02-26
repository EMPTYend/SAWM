<?php
declare(strict_types=1);

require_once __DIR__ . '/auth.php';
require_permission('logs.view');

$logFile = action_log_file();
$lines = [];

if (is_file($logFile)) {
    $allLines = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $lines = array_slice($allLines, -300);
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
<main class="card card-wide">
    <h1>Action Log Viewer</h1>
    <p>Administrator-only page. Latest log records are shown below.</p>

    <table>
        <thead>
        <tr>
            <th>#</th>
            <th>JSON Record</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($lines as $index => $line): ?>
            <tr>
                <td><?= $index + 1 ?></td>
                <td><code><?= htmlspecialchars($line, ENT_QUOTES, 'UTF-8') ?></code></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <p><a href="admin.php">Back to admin panel</a></p>
    <p><a href="logout.php">Logout</a></p>
</main>
</body>
</html>

