<?php

namespace tebe;

class PDO
{
    const ATTR_STATEMENT_FLUENT_INTERFACE = 1;

    protected \PDO $pdo;
    protected PDOParser $parser;

    public function __construct(string $dsn, ?string $username = NULL, ?string $password = NULL, array $options = [])
    {
        $options = array_replace(
            [
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                \PDO::ATTR_EMULATE_PREPARES => false,
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            ], 
            $options, 
            [
                \PDO::ATTR_PERSISTENT => true
            ]
        );
        $this->pdo = new \PDO($dsn, $username, $password, $options);
        $this->parser = new PDOParser($this->pdo->getAttribute(\PDO::ATTR_DRIVER_NAME));
    }

    public function run(string $sql, ?array $args = null): PDOResult
    {
        if ($args === null) {
            return new PDOResult($this->pdo->query($sql));
        }
        
        $isMultiArray = false;
        foreach ($args as $arg) {
            if (is_array($arg)) {
                $isMultiArray = true;
                break;
            }
        }

        if ($isMultiArray) {
            $parser = clone $this->parser;
            [$sql, $args] = $parser->rebuild($sql, $args);
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($args);

        return new PDOResult($stmt);
    }

    public function query()
    {
        
    }

    public function quote(array|string|int|float|null $value, int $type = self::PARAM_STR): string|false
    {
        $value = $value ?? '';

        if (!is_array($value)) {
            return $this->pdo->quote($value, $type);
        }

        foreach ($value as $k => $v) {
            $value[$k] = $this->pdo->quote($v, $type);
        }

        return implode(', ', $value);
    }
}
