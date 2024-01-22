<?php

namespace tebe;

class PDOStatement extends \PDOStatement
{
    private $pdo;

    private function __construct(PDO $pdo) {
        $this->pdo = $pdo;
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

    public function fetchAllNamed(): array
    {
        return $this->fetchAll(PDO::FETCH_NAMED);
    }

    public function fetchAllNumeric(): array
    {
        return $this->fetchAll(PDO::FETCH_NUM);
    }

    public function fetchAllObject(string $class = 'stdClass', ?array $constructorArgs = null, int $style = 0): array
    {
        if ($constructorArgs) {
            return $this->fetchAll(PDO::FETCH_CLASS | $style, $class, $constructorArgs);
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

    public function fetchInto(object $object): object|false
    {
        $this->setFetchMode(PDO::FETCH_INTO, $object);
        return $this->fetch();
    }

    public function fetchNamed(): array|false
    {
        return $this->fetch(PDO::FETCH_NAMED);
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
