<?php
// src/Security/Auth.php

// Make sure session is started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../Models/User.php';

function login_user(PDO $pdo, string $username, string $password): bool
{
    $userModel = new User($pdo);
    $user = $userModel->findByUsername($username);

    if (!$user) {
        return false;
    }

    if (!password_verify($password, $user['password_hash'])) {
        return false;
    }

    // Login successful – store minimal info in session
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['role'] = $user['role'];

    return true;
}

function is_logged_in(): bool
{
    return isset($_SESSION['user_id']);
}

function require_login(): void
{
    if (!is_logged_in()) {
        header('Location: login.php');
        exit;
    }
}

function logout_user(): void
{
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params['path'], $params['domain'],
            $params['secure'], $params['httponly']
        );
    }
    session_destroy();
}
