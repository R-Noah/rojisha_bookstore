<?php

class User
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findByUsername(string $username): ?array
    {
        $sql = "SELECT id, username, email, password_hash, role
                FROM users
                WHERE username = :username";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch();

        return $user ?: null;
    }
}
