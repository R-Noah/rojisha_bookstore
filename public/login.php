<?php

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../src/helpers.php';
require_once __DIR__ . '/../src/Security/Auth.php';

$errors = [];
$username = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = clean_string($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $errors[] = 'Username and password are required.';
    } else {
        if (login_user($pdo, $username, $password)) {
            header('Location: index.php');
            exit;
        } else {
            $errors[] = 'Invalid username or password.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Bookstore</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <h1>Login</h1>

    <p><a href="index.php">← Back to homepage</a></p>

    <?php if (!empty($errors)): ?>
        <div class="errors">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= e($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="login.php" method="post">
        <label>
            Username:
            <input type="text" name="username" value="<?= e($username) ?>" required>
        </label>
        <br><br>

        <label>
            Password:
            <input type="password" name="password" required>
        </label>
        <br><br>

        <button type="submit">Login</button>
    </form>
</body>
</html>
