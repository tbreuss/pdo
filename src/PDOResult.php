<?php

namespace tebe;

class PDOResult implements \IteratorAggregate
{
    protected \PDOStatement $stmt;

    public function __construct(\PDOStatement $stmt) {
        $this->stmt = $stmt;
    }

    public function closeCursor(): bool
    {
        return $this->stmt->closeCursor();
    }

    public function columnCount(): int
    {
        return $this->stmt->columnCount();
    }

    public function debugDumpParams(): ?bool
    {
        return $this->stmt->debugDumpParams();
    }

    public function errorCode(): ?string
    {
        return $this->stmt->errorCode();
    }

    public function errorInfo(): array
    {
        return $this->stmt->errorInfo();
    }

    // Fetch methods

    public function fetch(int $mode = PDO::FETCH_DEFAULT, int $cursorOrientation = PDO::FETCH_ORI_NEXT, int $cursorOffset = 0): mixed
    {
        return $this->stmt->fetch($mode, $cursorOrientation, $cursorOffset);
    }

    public function fetchAffected(): int
    {
        return $this->stmt->rowCount();
    }
    
    public function fetchAssoc(): array|false
    {
        return $this->stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function fetchBoth(): array|false
    {
        return $this->stmt->fetch(PDO::FETCH_BOTH);
    }

    public function fetchColumn(int $column = 0): mixed
    {
        return $this->stmt->fetchColumn($column);
    }

    public function fetchInto(object $object): object|false
    {
        $this->stmt->setFetchMode(PDO::FETCH_INTO, $object);
        return $this->stmt->fetch();
    }

    public function fetchNamed(): array|false
    {
        return $this->stmt->fetch(PDO::FETCH_NAMED);
    }

    public function fetchNumeric(): array|false
    {
        return $this->stmt->fetch(PDO::FETCH_NUM);
    }

    public function fetchObject(?string $class = "stdClass", array $constructorArgs = []): object|false
    {
        return $this->stmt->fetchObject($class, $constructorArgs);
    }

    public function fetchPair(): array|false
    {
        return $this->stmt->fetch(PDO::FETCH_KEY_PAIR);
    }

    // Fetch all methods

    public function fetchAll(int $mode = PDO::FETCH_DEFAULT, mixed ...$args): array
    {
        return $this->stmt->fetchAll($mode, ...$args);
    }

    public function fetchAllAssoc(): array
    {
        return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function fetchAllBoth(): array
    {
        return $this->stmt->fetchAll(PDO::FETCH_BOTH);
    }

    public function fetchAllColumn(int $column = 0): array
    {
        return $this->stmt->fetchAll(PDO::FETCH_COLUMN, $column);
    }

    public function fetchAllFunction(callable $callable): array
    {
        return $this->stmt->fetchAll(PDO::FETCH_FUNC, $callable);
    }

    public function fetchAllGroup(int $style = 0): array
    {
        return $this->stmt->fetchAll(PDO::FETCH_GROUP | $style);
    }

    public function fetchAllNamed(): array
    {
        return $this->stmt->fetchAll(PDO::FETCH_NAMED);
    }

    public function fetchAllNumeric(): array
    {
        return $this->stmt->fetchAll(PDO::FETCH_NUM);
    }

    public function fetchAllObject(string $class = 'stdClass', ?array $constructorArgs = null, int $style = 0): array
    {
        if ($constructorArgs) {
            return $this->stmt->fetchAll(PDO::FETCH_CLASS | $style, $class, $constructorArgs);
        }
        return $this->stmt->fetchAll(PDO::FETCH_CLASS | $style, $class);
    }

    public function fetchAllPairs(): array
    {
        return $this->stmt->fetchAll(PDO::FETCH_KEY_PAIR);
    }

    public function fetchAllUnique(int $style = 0): array
    {
        return $this->stmt->fetchAll(PDO::FETCH_UNIQUE | $style);
    }

    public function getColumnMeta(int $column): array|false
    {
        return $this->stmt->getColumnMeta($column);
    }

    public function getIterator(): \Iterator
    {
        return $this->stmt->getIterator();
    }

    public function nextRowset(): bool
    {
        return $this->stmt->nextRowset();
    }

    public function rowCount(): int
    {
        return $this->stmt->rowCount();
    }

    public function setFetchMode(int $mode, mixed ...$args): bool
    {
        return $this->stmt->setFetchMode($mode, ...$args);
    }
}
