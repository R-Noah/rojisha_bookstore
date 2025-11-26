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
}
