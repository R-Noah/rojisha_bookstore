<?php
// src/Models/Book.php

class Book
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Get all books ordered by newest first.
     */
    public function getAll(): array
    {
        $sql = "SELECT id, title, author, genre, year_published 
                FROM books
                ORDER BY created_at DESC";

        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }
    public function create(array $data): bool
{
    $sql = "INSERT INTO books (title, author, genre, year_published, isbn, price, stock)
            VALUES (:title, :author, :genre, :year_published, :isbn, :price, :stock)";

    $stmt = $this->pdo->prepare($sql);

    // Allow null price if empty
    $price = ($data['price'] === '' ? null : $data['price']);

    return $stmt->execute([
        ':title'          => $data['title'],
        ':author'         => $data['author'],
        ':genre'          => $data['genre'],
        ':year_published' => $data['year_published'],
        ':isbn'           => $data['isbn'],
        ':price'          => $price,
        ':stock'          => $data['stock'],
    ]);
}

public function getById(int $id): ?array
{
    $sql = "SELECT id, title, author, genre, year_published, isbn, price, stock
            FROM books
            WHERE id = :id";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':id' => $id]);
    $book = $stmt->fetch();

    return $book ?: null;
}

public function update(int $id, array $data): bool
{
    $sql = "UPDATE books
            SET title = :title,
                author = :author,
                genre = :genre,
                year_published = :year_published,
                isbn = :isbn,
                price = :price,
                stock = :stock
            WHERE id = :id";

    $stmt = $this->pdo->prepare($sql);

    $price = ($data['price'] === '' ? null : $data['price']);

    return $stmt->execute([
        ':title'          => $data['title'],
        ':author'         => $data['author'],
        ':genre'          => $data['genre'],
        ':year_published' => $data['year_published'],
        ':isbn'           => $data['isbn'],
        ':price'          => $price,
        ':stock'          => $data['stock'],
        ':id'             => $id,
    ]);
}


}
