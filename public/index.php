<?php
// public/index.php

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../src/helpers.php';
require_once __DIR__ . '/../src/Models/Book.php';

$bookModel = new Book($pdo);

try {
    $books = $bookModel->getAll();
} catch (Throwable $e) {
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
