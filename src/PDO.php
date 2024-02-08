<?php

namespace tebe;

/**
 * @method bool beginTransaction() Initiates a transaction
 * @method bool commit() Commits a transaction
 * @method ?string errorCode() Fetch the SQLSTATE associated with the last operation on the database handle
 * @method array errorInfo() Fetch extended error information associated with the last operation on the database handle
 * @method int|false exec(string $statement) Execute an SQL statement and return the number of affected rows
 * @method mixed getAttribute(int $attribute) Retrieve a database connection attribute
 * @method array getAvailableDrivers() Return an array of available PDO drivers
 * @method bool inTransaction() Checks if inside a transaction
 * @method string|false lastInsertId(?string $name = null) Returns the ID of the last inserted row or sequence value
 * @method bool rollBack() Rolls back a transaction
 * @method bool setAttribute(int $attribute, mixed $value) Set an attribute
 */
class PDO
{
    public const PARAM_NULL = \PDO::PARAM_NULL;
    public const PARAM_BOOL = \PDO::PARAM_BOOL;
    public const PARAM_INT = \PDO::PARAM_INT;
    public const PARAM_STR = \PDO::PARAM_STR;
    public const PARAM_LOB = \PDO::PARAM_LOB;
    public const PARAM_STMT = \PDO::PARAM_STMT;
    public const PARAM_INPUT_OUTPUT = \PDO::PARAM_INPUT_OUTPUT;
    public const PARAM_STR_NATL = \PDO::PARAM_STR_NATL;
    public const PARAM_STR_CHAR = \PDO::PARAM_STR_CHAR;
    public const PARAM_EVT_ALLOC = \PDO::PARAM_EVT_ALLOC;
    public const PARAM_EVT_FREE = \PDO::PARAM_EVT_FREE;
    public const PARAM_EVT_EXEC_PRE = \PDO::PARAM_EVT_EXEC_PRE;
    public const PARAM_EVT_EXEC_POST = \PDO::PARAM_EVT_EXEC_POST;
    public const PARAM_EVT_FETCH_PRE = \PDO::PARAM_EVT_FETCH_PRE;
    public const PARAM_EVT_FETCH_POST = \PDO::PARAM_EVT_FETCH_POST;
    public const PARAM_EVT_NORMALIZE = \PDO::PARAM_EVT_NORMALIZE;
    public const FETCH_DEFAULT = \PDO::FETCH_DEFAULT;
    public const FETCH_LAZY = \PDO::FETCH_LAZY;
    public const FETCH_ASSOC = \PDO::FETCH_ASSOC;
    public const FETCH_NUM = \PDO::FETCH_NUM;
    public const FETCH_BOTH = \PDO::FETCH_BOTH;
    public const FETCH_OBJ = \PDO::FETCH_OBJ;
    public const FETCH_BOUND = \PDO::FETCH_BOUND;
    public const FETCH_COLUMN = \PDO::FETCH_COLUMN;
    public const FETCH_CLASS = \PDO::FETCH_CLASS;
    public const FETCH_INTO = \PDO::FETCH_INTO;
    public const FETCH_FUNC = \PDO::FETCH_FUNC;
    public const FETCH_GROUP = \PDO::FETCH_GROUP;
    public const FETCH_UNIQUE = \PDO::FETCH_UNIQUE;
    public const FETCH_KEY_PAIR = \PDO::FETCH_KEY_PAIR;
    public const FETCH_CLASSTYPE = \PDO::FETCH_CLASSTYPE;
    public const FETCH_SERIALIZE = \PDO::FETCH_SERIALIZE;
    public const FETCH_PROPS_LATE = \PDO::FETCH_PROPS_LATE;
    public const FETCH_NAMED = \PDO::FETCH_NAMED;
    public const ATTR_AUTOCOMMIT = \PDO::ATTR_AUTOCOMMIT;
    public const ATTR_PREFETCH = \PDO::ATTR_PREFETCH;
    public const ATTR_TIMEOUT = \PDO::ATTR_TIMEOUT;
    public const ATTR_ERRMODE = \PDO::ATTR_ERRMODE;
    public const ATTR_SERVER_VERSION = \PDO::ATTR_SERVER_VERSION;
    public const ATTR_CLIENT_VERSION = \PDO::ATTR_CLIENT_VERSION;
    public const ATTR_SERVER_INFO = \PDO::ATTR_SERVER_INFO;
    public const ATTR_CONNECTION_STATUS = \PDO::ATTR_CONNECTION_STATUS;
    public const ATTR_CASE = \PDO::ATTR_CASE;
    public const ATTR_CURSOR_NAME = \PDO::ATTR_CURSOR_NAME;
    public const ATTR_CURSOR = \PDO::ATTR_CURSOR;
    public const ATTR_ORACLE_NULLS = \PDO::ATTR_ORACLE_NULLS;
    public const ATTR_PERSISTENT = \PDO::ATTR_PERSISTENT;
    public const ATTR_STATEMENT_CLASS = \PDO::ATTR_STATEMENT_CLASS;
    public const ATTR_FETCH_TABLE_NAMES = \PDO::ATTR_FETCH_TABLE_NAMES;
    public const ATTR_FETCH_CATALOG_NAMES = \PDO::ATTR_FETCH_CATALOG_NAMES;
    public const ATTR_DRIVER_NAME = \PDO::ATTR_DRIVER_NAME;
    public const ATTR_STRINGIFY_FETCHES = \PDO::ATTR_STRINGIFY_FETCHES;
    public const ATTR_MAX_COLUMN_LEN = \PDO::ATTR_MAX_COLUMN_LEN;
    public const ATTR_EMULATE_PREPARES = \PDO::ATTR_EMULATE_PREPARES;
    public const ATTR_DEFAULT_FETCH_MODE = \PDO::ATTR_DEFAULT_FETCH_MODE;
    public const ATTR_DEFAULT_STR_PARAM = \PDO::ATTR_DEFAULT_STR_PARAM;
    public const ERRMODE_SILENT = \PDO::ERRMODE_SILENT;
    public const ERRMODE_WARNING = \PDO::ERRMODE_WARNING;
    public const ERRMODE_EXCEPTION = \PDO::ERRMODE_EXCEPTION;
    public const CASE_NATURAL = \PDO::CASE_NATURAL;
    public const CASE_LOWER = \PDO::CASE_LOWER;
    public const CASE_UPPER = \PDO::CASE_UPPER;
    public const NULL_NATURAL = \PDO::NULL_NATURAL;
    public const NULL_EMPTY_STRING = \PDO::NULL_EMPTY_STRING;
    public const NULL_TO_STRING = \PDO::NULL_TO_STRING;
    public const ERR_NONE = \PDO::ERR_NONE;
    public const FETCH_ORI_NEXT = \PDO::FETCH_ORI_NEXT;
    public const FETCH_ORI_PRIOR = \PDO::FETCH_ORI_PRIOR;
    public const FETCH_ORI_FIRST = \PDO::FETCH_ORI_FIRST;
    public const FETCH_ORI_LAST = \PDO::FETCH_ORI_LAST;
    public const FETCH_ORI_ABS = \PDO::FETCH_ORI_ABS;
    public const FETCH_ORI_REL = \PDO::FETCH_ORI_REL;
    public const CURSOR_FWDONLY = \PDO::CURSOR_FWDONLY;
    public const CURSOR_SCROLL = \PDO::CURSOR_SCROLL;

    protected \PDO $pdo;
    protected PDOParser $parser;

    /**
     * Creates a PDO instance representing a connection to a database
     */
    public function __construct(string $dsn, ?string $username = NULL, ?string $password = NULL, array $options = [])
    {
        $options = array_replace(
            [
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ], 
            $options
        );
        $this->pdo = new \PDO($dsn, $username, $password, $options);
        $this->parser = new PDOParser($this->pdo->getAttribute(PDO::ATTR_DRIVER_NAME));
    }

    /**
     * Calls the method of the underlying PDO instance
     */
    public function __call(string $name, array $arguments): mixed
    {
        $methods = [
            'beginTransaction',
            'commit',
            'errorCode',
            'errorInfo',
            'exec',
            'getAttribute',
            'inTransaction',
            'lastInsertId',
            'rollBack',
            'setAttribute',
        ];

        if (in_array($name, $methods)) {
            return call_user_func_array([$this->pdo, $name], $arguments);
        }

        throw new \BadMethodCallException("Method $name doesn't exist");
    }

    /**
     * 
     * Calls the static method of the underlying PDO instance
     */
    public static function __callStatic(string $name, array $arguments): mixed
    {
        $methods = [
            'getAvailableDrivers',
        ];

        if (in_array($name, $methods)) {
            return call_user_func_array([\PDO::class, $name], $arguments);
        }

        throw new \BadMethodCallException("Static method $name doesn't exist");        
    }

    /**
     * Return the underlying PDO instance
     */
    public function getWrappedPdo(): \PDO
    {
        return $this->pdo;
    }

    /**
     * Prepares a statement for execution and returns a statement object
     * 
     * This differs from `PDO::prepare` in that it will return a `tebe\PDOStatement` object.
     */
    public function prepare(string $query, array $options = []): PDOStatement|false
    {
        $stmt = $this->pdo->prepare($query, $options);
        return $stmt ? new PDOStatement($stmt) : false;
    }

    /**
     * Prepares and executes an SQL statement without placeholders and returns a result object
     * 
     * This differs from `PDO::query` in that it will return a `tebe\PDOResult` object.
     */
    public function query(string $query, mixed ...$fetchModeArgs): PDOResult|false
    {
        $stmt = $this->pdo->query($query, ...$fetchModeArgs);
        return $stmt ? new PDOResult($stmt) : false;
    }

    /**
     * Quotes a value for use in a query
     * 
     * This differs from `PDO::quote()` in that it will convert an array into 
     * a string of comma-separated quoted values.
     */
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

    /**
     * Runs a query with bound values and returns the resulting PDOResult; 
     * array values will be processed by the parser instance and placeholders are replaced.
     */
    public function run(string $sql, ?array $args = null): PDOResult|false
    {
        if ($args === null) {
            $stmt = $this->pdo->query($sql);
            return $stmt ? new PDOResult($stmt) : false;
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
        $status = $stmt->execute($args);

        return $status ? new PDOResult($stmt) : false;
    }
}
