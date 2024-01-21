<?php

namespace tebe\pdox;

class PDOStatement extends \PDOStatement
{
    private $db;

    private function __construct(PDO $db) {
        $this->db = $db;
    }

    // Misc fetch methods

    public function fetchAffected(): int
    {
        return $this->rowCount();
    }

    // Fetch all methods

    public function fetchAllAssoc(): array
    {
        return $this->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function fetchAllBoth(): array
    {
        return $this->fetchAll(PDO::FETCH_BOTH);
    }

    public function fetchAllColumn(int $column = 0): array
    {
        return $this->fetchAll(PDO::FETCH_COLUMN, $column);
    }

    public function fetchAllFunction(callable $callable): array
    {
        return $this->fetchAll(PDO::FETCH_FUNC, $callable);
    }

    public function fetchAllGroup(int $style = 0): array
    {
        return $this->fetchAll(PDO::FETCH_GROUP | $style);
    }

    public function fetchAllNumeric(): array
    {
        return $this->fetchAll(PDO::FETCH_NUM);
    }

    public function fetchAllObject(string $class = 'stdClass', array $args = [], int $style = 0): array
    {
        if (! empty($args)) {
            return $this->fetchAll(PDO::FETCH_CLASS | $style, $class, $args);
        }

        return $this->fetchAll(PDO::FETCH_CLASS | $style, $class);
    }

    public function fetchAllPairs(): array
    {
        return $this->fetchAll(PDO::FETCH_KEY_PAIR);
    }

    public function fetchAllUnique(int $style = 0): array
    {
        return $this->fetchAll(PDO::FETCH_UNIQUE | $style);
    }

    // Fetch methods

    public function fetchAssoc(): array|false
    {
        return $this->fetch(PDO::FETCH_ASSOC);
    }
    
    public function fetchBoth(): array|false
    {
        return $this->fetch(PDO::FETCH_BOTH);
    }

    public function fetchNumeric(): array|false
    {
        return $this->fetch(PDO::FETCH_NUM);
    }

    public function fetchPair(): array|false
    {
        return $this->fetch(PDO::FETCH_KEY_PAIR);
    }
}
