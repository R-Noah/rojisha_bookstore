<?php
// public/book_edit.php

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../src/helpers.php';
require_once __DIR__ . '/../src/Models/Book.php';
require_once __DIR__ . '/../src/Security/Auth.php';
require_once __DIR__ . '/../twig_init.php';

require_login();

$bookModel = new Book($pdo);
$errors = [];

// Get ID from query string
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    die('Invalid book ID.');
}

// Fetch existing book
$book = $bookModel->getById($id);

if (!$book) {
    die('Book not found.');
}

// Pre-fill variables with existing values
$title          = $book['title'];
$author         = $book['author'];
$genre          = $book['genre'];
$year_published = (int)$book['year_published'];
$isbn           = $book['isbn'];
$price          = $book['price'];
$stock          = (int)$book['stock'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title          = clean_string($_POST['title'] ?? '');
    $author         = clean_string($_POST['author'] ?? '');
    $genre          = clean_string($_POST['genre'] ?? '');
    $year_published = clean_int($_POST['year_published'] ?? '');
    $isbn           = clean_string($_POST['isbn'] ?? '');
    $price          = ($_POST['price'] ?? '') !== '' ? clean_float($_POST['price']) : '';
    $stock          = clean_int($_POST['stock'] ?? '0');

    // Basic validation
    if ($title === '') {
        $errors[] = 'Title is required.';
    }
    if ($author === '') {
        $errors[] = 'Author is required.';
    }
    if ($genre === '') {
        $errors[] = 'Genre is required.';
    }
    if ($year_published <= 0) {
        $errors[] = 'Year of publication must be a valid year.';
    }
    if ($stock < 0) {
        $errors[] = 'Stock cannot be negative.';
    }

    if (empty($errors)) {
        $success = $bookModel->update($id, [
            'title'          => $title,
            'author'         => $author,
            'genre'          => $genre,
            'year_published' => $year_published,
            'isbn'           => $isbn,
            'price'          => $price,
            'stock'          => $stock,
        ]);

        if ($success) {
            header('Location: index.php');
            exit;
        } else {
            $errors[] = 'Failed to update book. Please try again.';
        }
    }
}

// Auth info for header
$isLoggedIn   = is_logged_in();
$username_nav = $isLoggedIn ? ($_SESSION['username'] ?? '') : '';

echo $twig->render('book_edit.html.twig', [
    'id'             => $id,
    'errors'         => $errors,
    'title'          => $title,
    'author'         => $author,
    'genre'          => $genre,
    'year_published' => $year_published,
    'isbn'           => $isbn,
    'price'          => $price,
    'stock'          => $stock,
    'is_logged_in'   => $isLoggedIn,
    'username_nav'   => $username_nav,
]);
