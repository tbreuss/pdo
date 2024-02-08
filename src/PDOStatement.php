<?php

namespace tebe;

/**
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
     * Calls the method of the original PDOStatement object
     */
    public function __call(string $name, array $arguments): mixed
    {
        $methods = [
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

    /**
     * Binds a parameter to the specified variable name
     */
    public function bindParam(string|int $param, mixed &$var, int $type = PDO::PARAM_STR, int $maxLength = 0, mixed $driverOptions = null): bool
    {
        // method was implemented because of the passed by reference $var param
        return $this->stmt->bindParam($param, $var, $type, $maxLength, $driverOptions);
    }

    /**
     * Executes a prepared statement
     * 
     * This differs from `PDOStatement::execute` in that it will return a PDOResult object.
     */
    public function execute(?array $params = null): PDOResult|false
    {
        $status = $this->stmt->execute($params);
        return $status ? new PDOResult($this->stmt) : false;
    }
}
