<?php

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../src/helpers.php';
require_once __DIR__ . '/../src/Security/Auth.php';
require_once __DIR__ . '/../twig_init.php';

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

// Auth info for base layout
$isLoggedIn = is_logged_in();
$currentUsername = $isLoggedIn ? ($_SESSION['username'] ?? '') : '';

echo $twig->render('login.html.twig', [
    'errors'      => $errors,
    'username'    => $username,
    'captchaA'    => $captchaA,
    'captchaB'    => $captchaB,
    'is_logged_in'=> $isLoggedIn,
    'username'    => $username,
    'username_nav'=> $currentUsername,
]);
