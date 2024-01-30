<?php

namespace tebe;

class PDOFetchStatement
{
    protected \PDOStatement $stmt;

    public function __construct(\PDOStatement $stmt) {
        $this->stmt = $stmt;
    }

    // Misc methods

    public function affected(): int
    {
        return $this->stmt->rowCount();
    }

    public function execute(?array $params = null): self
    {
        $this->stmt->execute($params);
        return $this;
    }

    // All methods

    public function all(int $mode = PDO::FETCH_DEFAULT, mixed ...$args): array
    {
        return $this->stmt->fetchAll($mode, ...$args);
    }

    public function allAssoc(): array
    {
        return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function allBoth(): array
    {
        return $this->stmt->fetchAll(PDO::FETCH_BOTH);
    }

    public function allColumn(int $column = 0): array
    {
        return $this->stmt->fetchAll(PDO::FETCH_COLUMN, $column);
    }

    public function allFunction(callable $callable): array
    {
        return $this->stmt->fetchAll(PDO::FETCH_FUNC, $callable);
    }

    public function allGroup(int $style = 0): array
    {
        return $this->stmt->fetchAll(PDO::FETCH_GROUP | $style);
    }

    public function allNamed(): array
    {
        return $this->stmt->fetchAll(PDO::FETCH_NAMED);
    }

    public function allNumeric(): array
    {
        return $this->stmt->fetchAll(PDO::FETCH_NUM);
    }

    public function allObject(string $class = 'stdClass', ?array $constructorArgs = null, int $style = 0): array
    {
        if ($constructorArgs) {
            return $this->stmt->fetchAll(PDO::FETCH_CLASS | $style, $class, $constructorArgs);
        }
        return $this->stmt->fetchAll(PDO::FETCH_CLASS | $style, $class);
    }

    public function allPairs(): array
    {
        return $this->stmt->fetchAll(PDO::FETCH_KEY_PAIR);
    }

    public function allUnique(int $style = 0): array
    {
        return $this->stmt->fetchAll(PDO::FETCH_UNIQUE | $style);
    }

    // Fetch methods

    public function one(int $mode = PDO::FETCH_DEFAULT, int $cursorOrientation = PDO::FETCH_ORI_NEXT, int $cursorOffset = 0): array|false
    {
        return $this->stmt->fetch($mode, $cursorOrientation, $cursorOffset);
    }

    public function oneAssoc(): array|false
    {
        return $this->stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function oneBoth(): array|false
    {
        return $this->stmt->fetch(PDO::FETCH_BOTH);
    }

    public function oneInto(object $object): object|false
    {
        $this->stmt->setFetchMode(PDO::FETCH_INTO, $object);
        return $this->stmt->fetch();
    }

    public function oneNamed(): array|false
    {
        return $this->stmt->fetch(PDO::FETCH_NAMED);
    }

    public function oneNumeric(): array|false
    {
        return $this->stmt->fetch(PDO::FETCH_NUM);
    }

    public function onePair(): array|false
    {
        return $this->stmt->fetch(PDO::FETCH_KEY_PAIR);
    }
}
