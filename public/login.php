<?php

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../src/helpers.php';
require_once __DIR__ . '/../src/Security/Auth.php';

$errors = [];
$username = '';

// Generate CAPTCHA numbers on initial load if not already set
if (!isset($_SESSION['captcha_a'], $_SESSION['captcha_b'])) {
    $_SESSION['captcha_a'] = random_int(1, 9);
    $_SESSION['captcha_b'] = random_int(1, 9);
}

$captchaA = $_SESSION['captcha_a'];
$captchaB = $_SESSION['captcha_b'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = clean_string($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $captcha_answer = clean_int($_POST['captcha'] ?? '');

    // First check CAPTCHA
    $expected = $captchaA + $captchaB;

    if ($captcha_answer !== $expected) {
        $errors[] = 'Incorrect CAPTCHA answer. Please try again.';

        // Regenerate CAPTCHA for the next attempt
        $_SESSION['captcha_a'] = random_int(1, 9);
        $_SESSION['captcha_b'] = random_int(1, 9);
        $captchaA = $_SESSION['captcha_a'];
        $captchaB = $_SESSION['captcha_b'];
    } else {
        // CAPTCHA is correct, now check username/password
        if ($username === '' || $password === '') {
            $errors[] = 'Username and password are required.';
        } else {
            if (login_user($pdo, $username, $password)) {
                // Clear CAPTCHA values on successful login
                unset($_SESSION['captcha_a'], $_SESSION['captcha_b']);
                header('Location: index.php');
                exit;
            } else {
                $errors[] = 'Invalid username or password.';

                // Regenerate CAPTCHA for the next attempt
                $_SESSION['captcha_a'] = random_int(1, 9);
                $_SESSION['captcha_b'] = random_int(1, 9);
                $captchaA = $_SESSION['captcha_a'];
                $captchaB = $_SESSION['captcha_b'];
            }
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

        <label>
            CAPTCHA: What is <?= e((string)$captchaA) ?> + <?= e((string)$captchaB) ?> ?
            <input type="number" name="captcha" required>
        </label>
        <br><br>

        <button type="submit">Login</button>
    </form>
</body>
</html>
