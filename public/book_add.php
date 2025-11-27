<?php
// public/book_add.php

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../src/helpers.php';
require_once __DIR__ . '/../src/Models/Book.php';

$bookModel = new Book($pdo);

$errors = [];
$title = $author = $genre = $year_published = $isbn = $price = '';
$stock = 0;

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
        $success = $bookModel->create([
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
            $errors[] = 'Failed to add book. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Book</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <h1>Add New Book</h1>

    <p><a href="index.php">← Back to list</a></p>

    <?php if (!empty($errors)): ?>
        <div class="errors">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= e($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="book_add.php" method="post">
        <label>
            Title:
            <input type="text" name="title" value="<?= e($title) ?>" required>
        </label><br><br>

        <label>
            Author:
            <input type="text" name="author" value="<?= e($author) ?>" required>
        </label><br><br>

        <label>
            Genre:
            <input type="text" name="genre" value="<?= e($genre) ?>" required>
        </label><br><br>

        <label>
            Year Published:
            <input type="number" name="year_published" value="<?= e((string)$year_published) ?>" required>
        </label><br><br>

        <label>
            ISBN:
            <input type="text" name="isbn" value="<?= e($isbn) ?>">
        </label><br><br>

        <label>
            Price:
            <input type="number" step="0.01" name="price" value="<?= e((string)$price) ?>">
        </label><br><br>

        <label>
            Stock:
            <input type="number" name="stock" value="<?= e((string)$stock) ?>" min="0">
        </label><br><br>

        <button type="submit">Add Book</button>
    </form>
</body>
</html>
