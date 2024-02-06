# tebe\pdo

Provides an extension to the native PDO along with additional functionality. 
Because `tebe\pdo` is an extension of the native PDO, code already using the native PDO or typehinted to the native PDO can use `tebe\pdo` without any changes.

Added functionality in `tebe\pdo` over the native PDO includes:

- New run() method. The run() method is for convenience to prepare and execute an SQL statement in one step.
- Array quoting. The quote() method will accept an array as input, and return a string of comma-separated quoted values.
- Bind array of values to placeholder. Placeholders that represent array values will be replaced with comma-separated quoted values. This means you can bind an array of values to a placeholder used with an IN (...) condition when using run()
- Several fetch*() methods. The new fetch*() methods provide for commonly-used fetch actions.
- Several fetchAll*() methods. The new fetchAll*() methods provide for commonly-used fetch all actions.
- Exceptions by default. `tebe\pdo` starts in the ERRMODE_EXCEPTION mode for error reporting instead of the ERRMODE_SILENT mode.

## Installation and Autoloading

This package is installable and PSR-4 autoloadable via Composer as `tebe/pdo`.

Alternatively, download a release, or clone this repository, then include the classes manually:

```php
include '{path/to/tebe/pdo}/src/PDO.php';
include '{path/to/tebe/pdo}/src/PDOStatement.php';
```

## Dependencies

This package requires PHP 8.1 or later; it has also been tested on PHP 8.1-8.3. We recommend using the latest available version of PHP as a matter of principle. `tebe\pdo` doesn't depend on other external packages.

## Quality

[![Testing tebe\pdo](https://github.com/tbreuss/pdo/actions/workflows/tests.yml/badge.svg)](https://github.com/tbreuss/pdo/actions/workflows/tests.yml)

This project adheres to [Semantic Versioning](https://semver.org/).

To run the functional tests at the command line, issue `composer install` and then `composer test` at the package root. (This requires [Composer](https://getcomposer.org/) to be available as `composer`.)

This package attempts to comply with [PSR-1](https://www.php-fig.org/psr/psr-1/), [PSR-4](https://www.php-fig.org/psr/psr-4/), and [PSR-12](https://www.php-fig.org/psr/psr-12/). If you notice compliance oversights, please send a patch via pull request.

## Documentation

The documentation will be completed shortly, in the meantime please refer to the [tests/](https://github.com/tbreuss/pdo/tree/main/tests) directory.

**tebe\PDO**  
- [beginTransaction](#begintransaction)
- [commit](#commit)
- [__construct](#__construct)
- [errorCode](#errorcode)
- [errorInfo](#errorinfo)
- [exec](#exec)
- [getAttribute](#getattribute)
- [getAvailableDrivers](#getavailabledrivers)
- [getPdo](#getpdo)
- [inTransaction](#intransaction)
- [lastInsertId](#lastinsertid)
- [prepare](#prepare)
- [query](#query)
- [quote](#quote)
- [rollBack](#rollback)
- [setAttribute](#setattribute)

**tebe\PDOStatement**  
**tebe\PDOResult**  
**tebe\PDOParser**  

## tebe\PDO

### beginTransaction

Initiates a transaction.  

```php
public PDO::beginTransaction(): bool
```

See [php.net/pdo.beginTransaction](https://php.net/pdo.beginTransaction).

### commit

Commits a transaction.

```php
public PDO::commit(): bool
```

See [php.net/pdo.commit](https://php.net/pdo.commit).

### __construct

Creates a PDO instance to represent a connection to the requested database.

```php
public PDO::__construct(
    string $dsn,
    ?string $username = null,
    ?string $password = null,
    ?array $options = null
)
```

See [php.net/pdo.__construct](https://php.net/pdo.__construct).

### errorCode

Fetch the SQLSTATE associated with the last operation on the database handle.  

```php
public PDO::errorCode(): ?string
```

See [php.net/pdo.errorCode](https://php.net/pdo.errorCode).

### errorInfo

Fetch extended error information associated with the last operation on the database handle.  

```php
public PDO::errorInfo(): array
```

See [php.net/pdo.errorInfo](https://php.net/pdo.errorInfo).

### exec

Execute an SQL statement and return the number of affected rows.  

```php
public PDO::exec(string $statement): int|false
```

See [php.net/pdo.exec](https://php.net/pdo.exec).

### getAttribute

Retrieve a database connection attribute.  

```php
public PDO::getAttribute(int $attribute): mixed
```

See [php.net/pdo.getAttribute](https://php.net/pdo.getAttribute).

### getAvailableDrivers

Return an array of available PDO drivers.

```php
public static PDO::getAvailableDrivers(): array
```

See [php.net/pdo.getAvailableDrivers](https://php.net/pdo.getAvailableDrivers).

### getPdo

Return the underlying PDO instance.

```php
public PDO::getPdo(): \PDO
```

See [php.net/pdo](https://php.net/pdo).

### inTransaction

Checks if inside a transaction.  

```php
public PDO::inTransaction(): bool
```

See [php.net/pdo.inTransaction](https://php.net/pdo.inTransaction).

### lastInsertId

Returns the ID of the last inserted row or sequence value.  

```php
public PDO::lastInsertId(?string $name = null):string|false 
```

See [php.net/pdo.lastInsertId](https://php.net/pdo.lastInsertId).

### prepare

Prepares a statement for execution and returns a statement object.
This differs from `PDO::prepare` in that it will return a tebe\PDOStatement object.

```php
public PDO::prepare(string $query, array $options = []): PDOStatement|false
````

See [php.net/pdo.prepare](https://php.net/pdo.prepare).

### query

Prepares and executes an SQL statement without placeholders.
This differs from `PDO::query` in that it will return a tebe\DOResult object.

```php
public PDO::query(string $query, mixed ...$fetchModeArgs): PDOResult|false
```

See [php.net/pdo.query](https://php.net/pdo.query).

### quote

Quotes a string for use in a query.

This differs from `PDO::quote()` in that it will convert an array into a string of comma-separated quoted values.

```php
public PDO::quote(array|string|int|float|null $value, int $type = self::PARAM_STR): string|false
```

See [php.net/pdo.quote](https://php.net/pdo.quote).

### rollBack

Rolls back a transaction.  

```php
public PDO::rollBack():bool
```

See [php.net/pdo.rollBack](https://php.net/pdo.rollBack).

### setAttribute

Set an attribute.  

```php
public PDO::setAttribute(int $attribute, mixed $value):bool
```

See [php.net/pdo.setAttribute](https://php.net/pdo.setAttribute).

## PDOStatement

To be done.

## PDOResult

### fetchAll

```php
$sql = "SELECT * FROM fruits ORDER BY 1 LIMIT 2";
$result = $db->run($sql)->fetchAll();
echo json_encode($result);
```

```json
[
    {
        "id": 1,
        "name": "Banana",
        "color": "yellow",
        "calories": 250
    },
    {
        "id": 2,
        "name": "Apple",
        "color": "red",
        "calories": 150
    }
]
```

### fetchAllAssoc

The output is identical to `fetchAll`.

### fetchAllBoth

```php
$sql = "SELECT * FROM fruits ORDER BY 1 LIMIT 2";
$result = $db->run($sql)->fetchBoth();
echo json_encode($result);
```

```json
[
    {
        "id": 1,
        "0": 1,
        "name": "Banana",
        "1": "Banana",
        "color": "yellow",
        "2": "yellow",
        "calories": 250,
        "3": 250
    },
    {
        "id": 2,
        "0": 2,
        "name": "Apple",
        "1": "Apple",
        "color": "red",
        "2": "red",
        "calories": 150,
        "3": 150
    }
]
```

### fetchAllColumn

```php
$sql = "SELECT * FROM fruits ORDER BY 1 LIMIT 3";
$result = $db->run($sql)->fetchAllColumn();
echo json_encode($result);
```

```json
[
    1,
    2,
    3
]
```

With explicit column:

```php
$sql = "SELECT * FROM fruits ORDER BY 1 LIMIT 3";
$result = $db->run($sql)->fetchAllColumn(2);
echo json_encode($result);
```

```json
[
    "yellow",
    "red",
    "green"
]
```

### fetchAllFunction

```php 
$sql = "SELECT * FROM fruits ORDER BY 1";
$function = function (mixed ...$item): string {
    return join('-', $item);
};
$result = json_encode($db->run($sql)->fetchAllFunction($function));
```

```json
[
    "1-Banana-yellow-250",
    "2-Apple-red-150",
    "3-Pear-green-150",
    "4-Orange-orange-300",
    "5-Lime-green-333",
    "6-Lemon-yellow-25",
    "7-Peach-orange-100",
    "8-Cherry-red-200"
]
```
