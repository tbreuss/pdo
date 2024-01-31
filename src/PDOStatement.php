<?php

namespace tebe;

class PDOStatement
{
    protected \PDOStatement $stmt;

    public function __construct(\PDOStatement $stmt) 
    {
        $this->stmt = $stmt;
    }

    public function bindColumn(string|int $column, mixed &$var, int $type = PDO::PARAM_STR, int $maxLength = 0, mixed $driverOptions = null): bool
    {
        return $this->stmt->bindColumn($column, $var, $type, $maxLength, $driverOptions);
    }

    public function bindParam(string|int $param, mixed &$var, int $type = PDO::PARAM_STR, int $maxLength = 0, mixed $driverOptions = null): bool
    {
        return $this->stmt->bindParam($param, $var, $type, $maxLength, $driverOptions);
    }

    public function bindValue(string|int $param, mixed $value, int $type = PDO::PARAM_STR): bool
    {
        return $this->stmt->bindValue($param, $value, $type);
    }

    public function errorCode(): ?string
    {
        return $this->stmt->errorCode();
    }

    public function errorInfo(): array
    {
        return $this->stmt->errorInfo();
    }

    public function execute(?array $params = null): PDOResult|false
    {
        $status = $this->stmt->execute($params);
        return $status ? new PDOResult($this->stmt) : false;
    }

    public function getAttribute(int $name): mixed
    {
        return $this->stmt->getAttribute($name);
    }

    public function setAttribute(int $attribute, mixed $value): bool
    {
        return $this->stmt->setAttribute($attribute, $value);
    }
}
