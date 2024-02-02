<?php

namespace tebe;

/**
 * @method bool bindColumn(string|int $column, mixed &$var, int $type = PDO::PARAM_STR, int $maxLength = 0, mixed $driverOptions = null) Bind a column to a PHP variable
 * @method bool bindParam(string|int $param, mixed &$var, int $type = PDO::PARAM_STR, int $maxLength = 0, mixed $driverOptions = null) Binds a parameter to the specified variable name
 * @method bool bindValue(string|int $param, mixed $value, int $type = PDO::PARAM_STR) Binds a value to a parameter
 * @method ?string errorCode() Fetch the SQLSTATE associated with the last operation on the statement handle
 * @method array errorInfo() Fetch extended error information associated with the last operation on the statement handle
 * @method mixed getAttribute(int $name) Retrieve a statement attribute
 * @method bool setAttribute(int $attribute, mixed $value) Set a statement attribute
 */
class PDOStatement
{
    protected \PDOStatement $stmt;

    /**
     * Creates a PDOStatement instance representing a query statement and wraps the original PDOStatement
     */
    public function __construct(\PDOStatement $stmt)
    {
        $this->stmt = $stmt;
    }

    /**
     * Calls the method of the original PDOStatement object
     */
    public function __call(string $name, array $arguments): mixed
    {
        $methods = [
            'bindColumn', 
            'bindParam', 
            'bindValue', 
            'errorCode', 
            'errorInfo', 
            'getAttribute', 
            'setAttribute'
        ];

        if (in_array($name, $methods)) {
            return call_user_func_array([$this->stmt, $name], $arguments);
        }

        throw new \BadMethodCallException("Method $name doesn't exist");
    }

    public function execute(?array $params = null): PDOResult|false
    {
        $status = $this->stmt->execute($params);
        return $status ? new PDOResult($this->stmt) : false;
    }
}
