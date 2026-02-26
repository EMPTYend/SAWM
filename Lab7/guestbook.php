<?php
declare(strict_types=1);

require_once __DIR__ . '/db.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = trim($_POST['user'] ?? '');
    $email = trim($_POST['e_mail'] ?? '');
    $message = trim($_POST['text_message'] ?? '');

    $userPattern = '/^[A-Za-z0-9_ ]{2,50}$/';
    $maxMessageLength = 500;

    if (!preg_match($userPattern, $user)) {
        $error = 'User name has invalid characters.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Email address is invalid.';
    } elseif ($message === '' || mb_strlen($message) > $maxMessageLength) {
        $error = 'Message must be 1..500 characters.';
    } else {
        // Remove tags before DB write to minimize stored attack payloads.
        $safeMessage = strip_tags($message);

        $stmt = db()->prepare('
            INSERT INTO guest (user, text_message, e_mail, data_time_message)
            VALUES (:user, :text_message, :e_mail, NOW())
        ');
        $stmt->execute([
            'user' => $user,
            'text_message' => $safeMessage,
            'e_mail' => $email,
        ]);

        $success = 'Message saved successfully.';
    }
}

$messages = db()->query('SELECT id, user, text_message, e_mail, data_time_message FROM guest ORDER BY id DESC')->fetchAll();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= APP_TITLE ?></title>
    <link rel="stylesheet" href="style.css">
    <script defer src="guest_validation.js"></script>
</head>
<body>
<main class="card card-wide">
    <h1>Guest Book</h1>
    <p>All output is escaped to prevent XSS execution.</p>

    <?php if ($error !== ''): ?>
        <div class="error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <?php if ($success !== ''): ?>
        <div class="success"><?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <form action="guestbook.php" method="post" id="guestbook-form" novalidate>
        <label for="user">User</label>
        <input id="user" name="user" type="text" maxlength="50" required>

        <label for="e_mail">E-mail</label>
        <input id="e_mail" name="e_mail" type="email" maxlength="120" required>

        <label for="text_message">Message</label>
        <textarea id="text_message" name="text_message" rows="4" maxlength="500" required></textarea>

        <button type="submit">Save Message</button>
    </form>

    <h2>Messages</h2>
    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>User</th>
            <th>Email</th>
            <th>Message</th>
            <th>Date/Time</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($messages as $message): ?>
            <tr>
                <td><?= (int) $message['id'] ?></td>
                <td><?= htmlspecialchars($message['user'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($message['e_mail'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= nl2br(htmlspecialchars($message['text_message'], ENT_QUOTES, 'UTF-8')) ?></td>
                <td><?= htmlspecialchars($message['data_time_message'], ENT_QUOTES, 'UTF-8') ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <p><a href="index.php">Back to login</a></p>
</main>
</body>
</html>

