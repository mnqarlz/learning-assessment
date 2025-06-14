<?php

namespace App\Infrastructure\Database;

use PDO;

class StudentDatabase
{
    private PDO $pdo;

    public function __construct(array $config)
    {
        $dsn = sprintf(
            '%s:host=%s;port=%s;dbname=%s;',
            $config['driver'],
            $config['host'],
            $config['port'],
            $config['database']
        );

        $this->pdo = new PDO($dsn, $config['username'], $config['password'], $config['flags']);
    }

    public function getPdo(): PDO
    {
        return $this->pdo;
    }
}
