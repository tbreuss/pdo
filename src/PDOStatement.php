<?php

declare(strict_types=1);

namespace tebe;

/**
 * Represents a prepared statement and, after the statement is executed, an associated result set.
 * 
 * @method bool bindValue(string|int $param, mixed $value, int $type = PDO::PARAM_STR) Binds a value to a parameter
 * @method bool closeCursor() Closes the cursor, enabling the statement to be executed again
 * @method int columnCount() Returns the number of columns in the result set
 * @method bool|null debugDumpParams() Dump an SQL prepared command
 * @method string|null errorCode() Fetch the SQLSTATE associated with the last operation on the statement handle
 * @method array errorInfo() Fetch extended error information associated with the last operation on the statement handle
 * @method mixed fetch(int $mode = PDO::FETCH_DEFAULT, int $cursorOrientation = PDO::FETCH_ORI_NEXT, int $cursorOffset = 0) Fetches the next row from a result set
 * @method array fetchAll(int $mode = PDO::FETCH_DEFAULT) Fetches the remaining rows from a result set
 * @method array fetchAll(int $mode = PDO::FETCH_COLUMN, int $column) Fetches the remaining rows from a result set
 * @method array fetchAll(int $mode = PDO::FETCH_CLASS, string $class, array|null $constructorArgs) Fetches the remaining rows from a result set
 * @method array fetchAll(int $mode = PDO::FETCH_FUNC, callable $callback) Fetches the remaining rows from a result set
 * @method array fetchColumn(int $column = 0) Returns a single column from the next row of a result set
 * @method object|false fetchObject(string|null $class = "stdClass", array $constructorArgs = []) Fetches the next row and returns it as an object
 * @method mixed getAttribute(int $name) Retrieve a statement attribute
 * @method false nextRowset() Advances to the next rowset in a multi-rowset statement handle
 * @method int rowCount() Returns the number of rows affected by the last SQL statement
 * @method bool setAttribute(int $attribute, mixed $value) Set a statement attribute
 * @method bool setFetchMode(int $mode) Set the default fetch mode for this statement
 * @method bool setFetchMode(int $mode = PDO::FETCH_COLUMN, int $colno) Set the default fetch mode for this statement
 * @method bool setFetchMode(int $mode = PDO::FETCH_CLASS, string $class, array|null $constructorArgs = null) Set the default fetch mode for this statement
 * @method bool setFetchMode(int $mode = PDO::FETCH_INTO, object $object) Set the default fetch mode for this statement
 */
class PDOStatement implements \IteratorAggregate
{
    protected \PDOStatement $stmt;

    /**
     * Used query string
     */
    public string $queryString;

    /**
     * Creates a PDOStatement instance representing a query statement and wraps the original PDOStatement
     */
    public function __construct(\PDOStatement $stmt)
    {
        $this->stmt = $stmt;
        $this->queryString = $stmt->queryString;
    }

    /**
     * Calls the method of the underlying PDOStatement object
     */
    public function __call(string $name, array $arguments): mixed
    {
        $methods = [
            'bindValue',
            'closeCursor',
            'columnCount',
            'debugDumpParams',
            'errorCode',
            'errorInfo',
            'fetch',
            'fetchAll',
            'fetchColumn',
            'fetchObject',
            'getAttribute',
            'getColumnMeta',
            'nextRowset',
            'rowCount',
            'setAttribute',
            'setFetchMode',          
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

    /**
     * Binds a parameter to the specified variable name
     */
    public function bindParam(string|int $param, mixed &$var, int $type = PDO::PARAM_STR, int $maxLength = 0, mixed $driverOptions = null): bool
    {
        // method is implemented because of the passed by reference $var param
        return $this->stmt->bindParam($param, $var, $type, $maxLength, $driverOptions);
    }

    /**
     * Executes a prepared statement
     * 
     * This differs from `PDOStatement::execute` in that it will return a PDOStatement object.
     */
    public function execute(?array $params = null): PDOStatement|false
    {
        $status = $this->stmt->execute($params);
        return $status ? new PDOStatement($this->stmt) : false;
    }

    /**
     * Fetches the number of affected rows from the result set
     */
    public function fetchAffected(): int
    {
        return $this->stmt->rowCount();
    }
    
    /**
     * Fetches the next row from the result set as an associative array
     */
    public function fetchAssoc(): array|false
    {
        return $this->stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Fetches the next row from the result set as an associative and indexed array
     */
    public function fetchBoth(): array|false
    {
        return $this->stmt->fetch(PDO::FETCH_BOTH);
    }

    /**
     * Fetches the next row from the result as the updated passed object, by mapping the columns to named properties of the object.
     */
    public function fetchInto(object $object): object|false
    {
        $this->stmt->setFetchMode(PDO::FETCH_INTO, $object);
        return $this->stmt->fetch();
    }

    /**
     * Fetches the next row from the result set as an associative array;
     * If the result set contains multiple columns with the same name, an array of values per column name is returned.
     */
    public function fetchNamed(): array|false
    {
        return $this->stmt->fetch(PDO::FETCH_NAMED);
    }

    /**
     * Fetches the next row from the result set as an indexed array
     */    
    public function fetchNumeric(): array|false
    {
        return $this->stmt->fetch(PDO::FETCH_NUM);
    }

    /**
     * Fetches the next row from the result set as a key-value pair
     */     
    public function fetchPair(): array|false
    {
        return $this->stmt->fetch(PDO::FETCH_KEY_PAIR);
    }

    /**
     * Fetches all rows from the result set as an array of associative arrays
     */
    public function fetchAllAssoc(): array
    {
        return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Fetches all rows from the result set as an array of associative and indexed arrays
     */
    public function fetchAllBoth(): array
    {
        return $this->stmt->fetchAll(PDO::FETCH_BOTH);
    }

    /**
     * Fetches all rows from the result set as an array of a single column
     */
    public function fetchAllColumn(int $column = 0): array
    {
        return $this->stmt->fetchAll(PDO::FETCH_COLUMN, $column);
    }

    /**
     * Fetches all rows from the result set as an array by calling a function for each row
     */    
    public function fetchAllFunction(callable $callable): array
    {
        return $this->stmt->fetchAll(PDO::FETCH_FUNC, $callable);
    }

    /**
     * Fetches all rows from the result set as an array by grouping all rows by a single column
     */
    public function fetchAllGroup(int $style = 0): array
    {
        return $this->stmt->fetchAll(PDO::FETCH_GROUP | $style);
    }

    /**
     * Fetches all rows from the result set as an array of associative arrays;
     * If the result set contains multiple columns with the same name, an array of values per column name is returned.
     */
    public function fetchAllNamed(): array
    {
        return $this->stmt->fetchAll(PDO::FETCH_NAMED);
    }

    /**
     * Fetches all rows from the result set as an array of indexed arrays
     */
    public function fetchAllNumeric(): array
    {
        return $this->stmt->fetchAll(PDO::FETCH_NUM);
    }

    /**
     * Fetches all rows from the result set as an array of objects of the requested class, 
     * mapping the columns to named properties in the class
     */
    public function fetchAllObject(string $class = 'stdClass', ?array $constructorArgs = null, int $style = 0): array
    {
        if ($constructorArgs) {
            return $this->stmt->fetchAll(PDO::FETCH_CLASS | $style, $class, $constructorArgs);
        }
        return $this->stmt->fetchAll(PDO::FETCH_CLASS | $style, $class);
    }

    /**
     * Fetches all rows from the result set as an array of key-value pairs
     */
    public function fetchAllPair(): array
    {
        return $this->stmt->fetchAll(PDO::FETCH_KEY_PAIR);
    }

    /**
     * TODO add description
     */
    public function fetchAllUnique(int $style = 0): array
    {
        return $this->stmt->fetchAll(PDO::FETCH_UNIQUE | $style);
    }

    /**
     * Gets result set iterator
     */
    public function getIterator(): \Iterator
    {
        return $this->stmt->getIterator();
    }    
}
