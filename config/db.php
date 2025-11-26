<?php
// config/db.php

$host = 'localhost';
$port = 3307;          // your local MySQL port
$dbname = 'bookstore_db';

$dbUser = 'root';      // change if your MySQL user is different
$dbPass = '';          // change if your MySQL password is not empty

try {
    $pdo = new PDO(
        "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4",
        $dbUser,
        $dbPass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (PDOException $e) {
    // In production you would log this, not display it
    die('Database connection failed: ' . htmlspecialchars($e->getMessage()));
}
