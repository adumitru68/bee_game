<?php

namespace App\Domain\Repository;

use App\Domain\Connection;
use PDO;
use PDOStatement;

class AbstractRepository
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function execute($query, ?array $queryParams = null): ?PDOStatement
    {
        if (!$query instanceof PDOStatement) {
            $query = $this->prepare($query);
        }

        $query->execute($queryParams);

        return $query;
    }

    public function getPdo(): PDO
    {
        return $this->connection->getPdo();
    }

    public function lastInsertId(): ?int
    {
        return $this->connection->getPdo()->lastInsertId();
    }

    public function prepare(string $sql): PDOStatement
    {
        return $this->connection->getPdo()->prepare($sql);
    }
}