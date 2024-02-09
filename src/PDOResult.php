<?php

namespace tebe;

/**
 * @method bool closeCursor() Closes the cursor, enabling the statement to be executed again
 * @method int columnCount() Returns the number of columns in the result set
 * @method ?bool debugDumpParams() Dump an SQL prepared command
 * @method ?string errorCode() Fetch the SQLSTATE associated with the last operation on the statement handle
 * @method array errorInfo() Fetch extended error information associated with the last operation on the statement handle
 * @method mixed fetch(int $mode = PDO::FETCH_DEFAULT, int $cursorOrientation = PDO::FETCH_ORI_NEXT, int $cursorOffset = 0) Fetches the next row from a result set
 * @method array fetchAll(int $mode = PDO::FETCH_DEFAULT) Fetches the remaining rows from a result set
 * @method array fetchAll(int $mode = PDO::FETCH_COLUMN, int $column) Fetches the remaining rows from a result set
 * @method array fetchAll(int $mode = PDO::FETCH_CLASS, string $class, ?array $constructorArgs) Fetches the remaining rows from a result set
 * @method array fetchAll(int $mode = PDO::FETCH_FUNC, callable $callback) Fetches the remaining rows from a result set
 * @method array fetchColumn(int $column = 0) Returns a single column from the next row of a result set
 * @method object|false fetchObject(?string $class = "stdClass", array $constructorArgs = []) Fetches the next row and returns it as an object
 * @method false nextRowset() Advances to the next rowset in a multi-rowset statement handle
 * @method int rowCount() Returns the number of rows affected by the last SQL statement
 * @method bool setFetchMode(int $mode) Set the default fetch mode for this statement
 * @method bool setFetchMode(int $mode = PDO::FETCH_COLUMN, int $colno) Set the default fetch mode for this statement
 * @method bool setFetchMode(int $mode = PDO::FETCH_CLASS, string $class, ?array $constructorArgs = null) Set the default fetch mode for this statement
 * @method bool setFetchMode(int $mode = PDO::FETCH_INTO, object $object) Set the default fetch mode for this statement
 */
class PDOResult implements \IteratorAggregate
{
    protected \PDOStatement $stmt;

    public function __construct(\PDOStatement $stmt) {
        $this->stmt = $stmt;
    }
    
    public function __call(string $name, array $arguments): mixed
    {
        $methods = [
            'closeCursor',
            'columnCount',
            'debugDumpParams',
            'errorCode',
            'errorInfo',
            'fetch',
            'fetchAll',
            'fetchColumn',
            'fetchObject',
            'getColumnMeta',
            'nextRowset',
            'rowCount',
            'setFetchMode'
        ];

        if (in_array($name, $methods)) {
            return call_user_func_array([$this->stmt, $name], $arguments);
        }

        throw new \BadMethodCallException("Method $name doesn't exist");
    }

    /**
     * Bind a column to a PHP variable
     */
    public function bindColumn(string|int $column, mixed &$var, int $type = PDO::PARAM_STR, int $maxLength = 0, mixed $driverOptions = null): bool
    {
        // method is implemented because of the passed by reference $var param
        return $this->stmt->bindColumn($column, $var, $type, $maxLength, $driverOptions);
    }

    // Fetch methods

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

    public function fetchPair(): array|false
    {
        return $this->stmt->fetch(PDO::FETCH_KEY_PAIR);
    }

    // Fetch all methods

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

    public function fetchAllPair(): array
    {
        return $this->stmt->fetchAll(PDO::FETCH_KEY_PAIR);
    }

    public function fetchAllUnique(int $style = 0): array
    {
        return $this->stmt->fetchAll(PDO::FETCH_UNIQUE | $style);
    }

    public function getIterator(): \Iterator
    {
        return $this->stmt->getIterator();
    }
}
