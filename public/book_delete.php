<?php
// public/book_delete.php

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../src/helpers.php';
require_once __DIR__ . '/../src/Models/Book.php';

$bookModel = new Book($pdo);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('Invalid request method.');
}

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

if ($id <= 0) {
    die('Invalid book ID.');
}

$bookModel->delete($id);

header('Location: index.php');
exit;
