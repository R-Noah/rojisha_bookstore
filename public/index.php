<?php
// public/index.php

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../src/helpers.php';
require_once __DIR__ . '/../src/Models/Book.php';
require_once __DIR__ . '/../src/Security/Auth.php';
require_once __DIR__ . '/../twig_init.php';

$bookModel = new Book($pdo);

// Read and clean search inputs
$title  = clean_string($_GET['title'] ?? '');
$author = clean_string($_GET['author'] ?? '');
$genre  = clean_string($_GET['genre'] ?? '');
$year   = clean_string($_GET['year'] ?? '');

// Decide whether to search or list all
$hasSearch = ($title !== '' || $author !== '' || $genre !== '' || $year !== '');

try {
    if ($hasSearch) {
        $books = $bookModel->search([
            'title'  => $title,
            'author' => $author,
            'genre'  => $genre,
            'year'   => $year,
        ]);
    } else {
        $books = $bookModel->getAll();
    }
} catch (Throwable $e) {
    // In production you'd log this
    die('Error fetching books.');
}

// Prepare auth info for Twig
$isLoggedIn = is_logged_in();
$username_nav = $isLoggedIn ? ($_SESSION['username'] ?? '') : '';

echo $twig->render('home.html.twig', [
    'books'         => $books,
    'title'         => $title,
    'author'        => $author,
    'genre'         => $genre,
    'year'          => $year,
    'is_logged_in'  => $isLoggedIn,
    'username_nav'  => $username_nav,
]);
