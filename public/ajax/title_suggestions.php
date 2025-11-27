<?php
// public/ajax/title_suggestions.php

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../src/helpers.php';
require_once __DIR__ . '/../../src/Models/Book.php';

header('Content-Type: application/json; charset=utf-8');

$bookModel = new Book($pdo);

// Read and clean query
$q = clean_string($_GET['q'] ?? '');

if ($q === '' || strlen($q) < 2) {
    echo json_encode([]);
    exit;
}

try {
    $results = $bookModel->searchTitles($q);
    $titles = [];

    foreach ($results as $row) {
        $titles[] = $row['title'];
    }

    echo json_encode($titles);
} catch (Throwable $e) {
    echo json_encode([]);
}
