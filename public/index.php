<?php
// public/index.php

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../src/helpers.php';
require_once __DIR__ . '/../src/Models/Book.php';

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
    // For now just show a simple message
    die('Error fetching books.');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bookstore</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <h1>Bookstore</h1>
    <p><a href="book_add.php">Add new book</a></p>
<h2>Search Books</h2>

<form method="get" action="index.php">
    <label>
        Title:
        <input type="text" name="title" value="<?= e($title) ?>">
    </label>
    <br><br>

    <label>
        Author:
        <input type="text" name="author" value="<?= e($author) ?>">
    </label>
    <br><br>

    <label>
        Genre:
        <input type="text" name="genre" value <?= e($genre) ?>>
    </label>
    <br><br>

    <label>
        Year:
        <input type="number" name="year" value="<?= e($year) ?>">
    </label>
    <br><br>

    <button type="submit">Search</button>
    <a href="index.php">Reset</a>
</form>


<hr>


    <?php if (empty($books)): ?>
        <p>No books found in the database.</p>
    <?php else: ?>
        <table border="1" cellpadding="8" cellspacing="0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Genre</th>
                    <th>Year</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($books as $book): ?>
                <tr>
                    <td><?= e($book['id']) ?></td>
                    <td><?= e($book['title']) ?></td>
                    <td><?= e($book['author']) ?></td>
                    <td><?= e($book['genre']) ?></td>
                    <td><?= e($book['year_published']) ?></td>
                    <td>
    <a href="book_edit.php?id=<?= e((string)$book['id']) ?>">Edit</a>

    <form action="book_delete.php" method="post" style="display:inline;"
          onsubmit="return confirm('Are you sure you want to delete this book?');">
        <input type="hidden" name="id" value="<?= e((string)$book['id']) ?>">
        <button type="submit">Delete</button>
    </form>
</td>

                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>
