<?php

namespace tebe;

/**
 * @method bool beginTransaction() Initiates a transaction
 * @method bool commit() Commits a transaction
 * @method ?string errorCode() Fetch the SQLSTATE associated with the last operation on the database handle
 * @method array errorInfo() Fetch extended error information associated with the last operation on the database handle
 * @method int|false exec(string $statement) Execute an SQL statement and return the number of affected rows
 * @method mixed getAttribute(int $attribute) Retrieve a database connection attribute
 * @method bool inTransaction() Checks if inside a transaction
 * @method string|false lastInsertId(?string $name = null) Returns the ID of the last inserted row or sequence value
 * @method bool rollBack() Rolls back a transaction
 * @method bool setAttribute(int $attribute, mixed $value) Set an attribute
 */
class PDO
{
    public const int PARAM_NULL = \PDO::PARAM_NULL;
    public const int PARAM_BOOL = \PDO::PARAM_BOOL;
    public const int PARAM_INT = \PDO::PARAM_INT;
    public const int PARAM_STR = \PDO::PARAM_STR;
    public const int PARAM_LOB = \PDO::PARAM_LOB;
    public const int PARAM_STMT = \PDO::PARAM_STMT;
    public const int PARAM_INPUT_OUTPUT = \PDO::PARAM_INPUT_OUTPUT;
    public const int PARAM_STR_NATL = \PDO::PARAM_STR_NATL;
    public const int PARAM_STR_CHAR = \PDO::PARAM_STR_CHAR;
    public const int PARAM_EVT_ALLOC = \PDO::PARAM_EVT_ALLOC;
    public const int PARAM_EVT_FREE = \PDO::PARAM_EVT_FREE;
    public const int PARAM_EVT_EXEC_PRE = \PDO::PARAM_EVT_EXEC_PRE;
    public const int PARAM_EVT_EXEC_POST = \PDO::PARAM_EVT_EXEC_POST;
    public const int PARAM_EVT_FETCH_PRE = \PDO::PARAM_EVT_FETCH_PRE;
    public const int PARAM_EVT_FETCH_POST = \PDO::PARAM_EVT_FETCH_POST;
    public const int PARAM_EVT_NORMALIZE = \PDO::PARAM_EVT_NORMALIZE;
    public const int FETCH_DEFAULT = \PDO::FETCH_DEFAULT;
    public const int FETCH_LAZY = \PDO::FETCH_LAZY;
    public const int FETCH_ASSOC = \PDO::FETCH_ASSOC;
    public const int FETCH_NUM = \PDO::FETCH_NUM;
    public const int FETCH_BOTH = \PDO::FETCH_BOTH;
    public const int FETCH_OBJ = \PDO::FETCH_OBJ;
    public const int FETCH_BOUND = \PDO::FETCH_BOUND;
    public const int FETCH_COLUMN = \PDO::FETCH_COLUMN;
    public const int FETCH_CLASS = \PDO::FETCH_CLASS;
    public const int FETCH_INTO = \PDO::FETCH_INTO;
    public const int FETCH_FUNC = \PDO::FETCH_FUNC;
    public const int FETCH_GROUP = \PDO::FETCH_GROUP;
    public const int FETCH_UNIQUE = \PDO::FETCH_UNIQUE;
    public const int FETCH_KEY_PAIR = \PDO::FETCH_KEY_PAIR;
    public const int FETCH_CLASSTYPE = \PDO::FETCH_CLASSTYPE;
    public const int FETCH_SERIALIZE = \PDO::FETCH_SERIALIZE;
    public const int FETCH_PROPS_LATE = \PDO::FETCH_PROPS_LATE;
    public const int FETCH_NAMED = \PDO::FETCH_NAMED;
    public const int ATTR_AUTOCOMMIT = \PDO::ATTR_AUTOCOMMIT;
    public const int ATTR_PREFETCH = \PDO::ATTR_PREFETCH;
    public const int ATTR_TIMEOUT = \PDO::ATTR_TIMEOUT;
    public const int ATTR_ERRMODE = \PDO::ATTR_ERRMODE;
    public const int ATTR_SERVER_VERSION = \PDO::ATTR_SERVER_VERSION;
    public const int ATTR_CLIENT_VERSION = \PDO::ATTR_CLIENT_VERSION;
    public const int ATTR_SERVER_INFO = \PDO::ATTR_SERVER_INFO;
    public const int ATTR_CONNECTION_STATUS = \PDO::ATTR_CONNECTION_STATUS;
    public const int ATTR_CASE = \PDO::ATTR_CASE;
    public const int ATTR_CURSOR_NAME = \PDO::ATTR_CURSOR_NAME;
    public const int ATTR_CURSOR = \PDO::ATTR_CURSOR;
    public const int ATTR_ORACLE_NULLS = \PDO::ATTR_ORACLE_NULLS;
    public const int ATTR_PERSISTENT = \PDO::ATTR_PERSISTENT;
    public const int ATTR_STATEMENT_CLASS = \PDO::ATTR_STATEMENT_CLASS;
    public const int ATTR_FETCH_TABLE_NAMES = \PDO::ATTR_FETCH_TABLE_NAMES;
    public const int ATTR_FETCH_CATALOG_NAMES = \PDO::ATTR_FETCH_CATALOG_NAMES;
    public const int ATTR_DRIVER_NAME = \PDO::ATTR_DRIVER_NAME;
    public const int ATTR_STRINGIFY_FETCHES = \PDO::ATTR_STRINGIFY_FETCHES;
    public const int ATTR_MAX_COLUMN_LEN = \PDO::ATTR_MAX_COLUMN_LEN;
    public const int ATTR_EMULATE_PREPARES = \PDO::ATTR_EMULATE_PREPARES;
    public const int ATTR_DEFAULT_FETCH_MODE = \PDO::ATTR_DEFAULT_FETCH_MODE;
    public const int ATTR_DEFAULT_STR_PARAM = \PDO::ATTR_DEFAULT_STR_PARAM;
    public const int ERRMODE_SILENT = \PDO::ERRMODE_SILENT;
    public const int ERRMODE_WARNING = \PDO::ERRMODE_WARNING;
    public const int ERRMODE_EXCEPTION = \PDO::ERRMODE_EXCEPTION;
    public const int CASE_NATURAL = \PDO::CASE_NATURAL;
    public const int CASE_LOWER = \PDO::CASE_LOWER;
    public const int CASE_UPPER = \PDO::CASE_UPPER;
    public const int NULL_NATURAL = \PDO::NULL_NATURAL;
    public const int NULL_EMPTY_STRING = \PDO::NULL_EMPTY_STRING;
    public const int NULL_TO_STRING = \PDO::NULL_TO_STRING;
    public const string ERR_NONE = \PDO::ERR_NONE;
    public const int FETCH_ORI_NEXT = \PDO::FETCH_ORI_NEXT;
    public const int FETCH_ORI_PRIOR = \PDO::FETCH_ORI_PRIOR;
    public const int FETCH_ORI_FIRST = \PDO::FETCH_ORI_FIRST;
    public const int FETCH_ORI_LAST = \PDO::FETCH_ORI_LAST;
    public const int FETCH_ORI_ABS = \PDO::FETCH_ORI_ABS;
    public const int FETCH_ORI_REL = \PDO::FETCH_ORI_REL;
    public const int CURSOR_FWDONLY = \PDO::CURSOR_FWDONLY;
    public const int CURSOR_SCROLL = \PDO::CURSOR_SCROLL;

    // our new attribute
    public const int ATTR_STATEMENT_FLUENT_INTERFACE = 1;

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
     * Calls the method of the original PDO object
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
     * Return an array of available PDO drivers
     */
    public static function getAvailableDrivers(): array
    {
        return \PDO::getAvailableDrivers();
    }

    /**
     * Prepares a statement for execution and returns a statement object
     */
    public function prepare(string $query, array $options = []): PDOStatement|false
    {
        $stmt = $this->pdo->prepare($query, $options);
        return $stmt ? new PDOStatement($stmt) : false;
    }

    /**
     * Prepares and executes an SQL statement without placeholders
     * 
     * This differs from `PDO::query` in that it will return a PDOResult object.
     */
    public function query(string $query, mixed ...$fetchModeArgs): PDOResult|false
    {
        $stmt = $this->pdo->query($query, ...$fetchModeArgs);
        return $stmt ? new PDOResult($stmt) : false;
    }

    /**
     * Quotes a string for use in a query
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
