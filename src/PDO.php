<?php

namespace tebe;

class PDO extends \PDO
{
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
                \PDO::ATTR_STATEMENT_CLASS => [PDOStatement::class, [$this]]
            ]
        );
        parent::__construct($dsn, $username, $password, $options);
    }

    public function run(string $sql, ?array $args = null): PDOStatement
    {
        if ($args === null) {
            return $this->query($sql);
        }
        $stmt = $this->prepare($sql);
        $stmt->execute($args);
        return $stmt;
    }
}
