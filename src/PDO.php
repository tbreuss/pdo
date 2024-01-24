<?php

namespace tebe;

class PDO extends \PDO
{
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
                \PDO::ATTR_STATEMENT_CLASS => [PDOStatement::class, [$this]],
                \PDO::ATTR_PERSISTENT => false
            ]
        );
        parent::__construct($dsn, $username, $password, $options);
        $this->parser = new PDOParser($this->getAttribute(PDO::ATTR_DRIVER_NAME));
    }

    public function run(string $sql, ?array $args = null): PDOStatement
    {
        if ($args === null) {
            return $this->query($sql);
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

        $stmt = $this->prepare($sql);
        $stmt->execute($args);

        return $stmt;
    }

    public function quote(array|string|int|float|null $value, int $type = self::PARAM_STR): string|false
    {
        $value = $value ?? '';

        if (!is_array($value)) {
            return parent::quote($value, $type);
        }

        foreach ($value as $k => $v) {
            $value[$k] = parent::quote($v, $type);
        }

        return implode(', ', $value);
    }
}
