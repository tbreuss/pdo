# tebe\pdo

Provides an extension to the native PDO along with additional functionality.

Because `tebe\pdo` is an extension of the native PDO in the style of a wrapper, code already using the native PDO or typehinted to the native PDO can use `tebe\pdo` without any changes for the most part.

Added functionality in `tebe\pdo` over the native PDO includes:

- New `PDO::run()` method. This is for convenience to prepare and execute an SQL statement in one step.
- Array quoting. The `PDO::quote()` method will accept an array as input, and return a string of comma-separated quoted values.
- Bind array of values to placeholder. Placeholders that represent array values will be replaced with comma-separated quoted values. This means you can bind an array of values to a placeholder used with an IN (...) condition when using `PDO::run()`
- Exceptions by default. `tebe\pdo` starts in the ERRMODE_EXCEPTION mode for error reporting instead of the ERRMODE_SILENT mode.
- Several `fetch*()` methods. The new methods provide for commonly-used fetch actions.
- Several `fetchAll*()` methods. The new methods provide for commonly-used fetch all actions.

## Installation and Autoloading

This package is installable and PSR-4 autoloadable via Composer as `tebe/pdo`.

Alternatively, download a release, or clone this repository, then include the classes manually:

```php
include '{path/to/tebe/pdo}/src/PDO.php';
include '{path/to/tebe/pdo}/src/PDOStatement.php';
include '{path/to/tebe/pdo}/src/PDOResult.php';
include '{path/to/tebe/pdo}/src/PDOParser.php';
```

## Dependencies

This package requires PHP 8.1 or later; it has also been tested on PHP 8.1-8.3. We recommend using the latest available version of PHP as a matter of principle. `tebe\pdo` doesn't depend on other external packages.

## Quality

[![Testing tebe\pdo](https://github.com/tbreuss/pdo/actions/workflows/tests.yml/badge.svg)](https://github.com/tbreuss/pdo/actions/workflows/tests.yml)

This project adheres to [Semantic Versioning](https://semver.org/).

To run the functional tests at the command line, issue `composer install` and then `composer test` at the package root. (This requires [Composer](https://getcomposer.org/) to be available as `composer`.)

This package attempts to comply with [PSR-1](https://www.php-fig.org/psr/psr-1/), [PSR-4](https://www.php-fig.org/psr/psr-4/), and [PSR-12](https://www.php-fig.org/psr/psr-12/). If you notice compliance oversights, please send a patch via pull request.

## Documentation

## tebe\PDO

### getWrappedPdo

Returns the underlying PDO instance.

```php
public PDO::getWrappedPdo(): \PDO
```

### prepare

Prepares a statement for execution and returns a statement object.

This differs from `PDO::prepare` in that it will return a `tebe\PDOStatement` object.

```php
public PDO::prepare(string $query, array $options = []): PDOStatement|false
```

See [php.net](https://php.net/pdo.prepare)

### query

Prepares and executes an SQL statement without placeholders.

This differs from `PDO::query` in that it will return a `tebe\PDOResult` object.

```php
public PDO::query(string $query, mixed ...$fetchModeArgs): PDOResult|false
```

See [php.net](https://php.net/pdo.query)

### quote

Quotes a string for use in a query.

This differs from `PDO::quote` in that it will convert an array into a string of comma-separated quoted values.

```php
public PDO::quote(array|string|int|float|null $value, int $type = PDO::PARAM_STR): string|false
```

See [php.net](https://php.net/pdo.quote)

### run

Runs a query with bound values and returns the resulting `tebe\PDOResult`. 

Array values will be processed by the parser instance and placeholders are replaced.

```php
public PDO::run(string $sql, ?array $args = null): PDOResult|false
```

---

For the remaining `tebe\PDO` methods, which are just wrapper methods of the `PDO` class, see the documentation at [php.net](https://php.net/pdo).

- [beginTransaction](https://php.net/pdo.beginTransaction)
- [commit](https://php.net/pdo.commit)
- [__construct](https://php.net/pdo.__construct)
- [errorCode](https://php.net/pdo.errorCode)
- [errorInfo](https://php.net/pdo.errorInfo)
- [exec](https://php.net/pdo.exec)
- [getAttribute](https://php.net/pdo.getAttribute)
- [getAvailableDrivers](https://php.net/pdo.getAvailableDrivers)
- [inTransaction](https://php.net/pdo.inTransaction)
- [lastInsertId](https://php.net/pdo.lastInsertId)
- [rollBack](https://php.net/pdo.rollBack)
- [setAttribute](https://php.net/pdo.setAttribute)

## tebe\PDOStatement

The `tebe\PDOStatement` class differs from `PDOStatement` in that it contains only those methods that are related to the prepared statement.

#### __construct

Creates a `tebe\PDOStatement` instance representing a query statement and wraps the original `PDOStatement`.

#### execute

Executes a prepared statement

This differs from `PDOStatement::execute` in that it will return a `tebe\PDOResult` object.

```php
public PDOStatement::execute(?array $params = null): PDOResult|false
```

See [php.net](https://php.net/pdostatement.execute)

---

For the remaining `tebe\PDOStatement` methods, which are just wrapper methods of the `PDO` class, see the documentation at [php.net](https://php.net/pdostatement).

- [queryString](https://php.net/pdostatement)
- [bindColumn](https://php.net/pdostatement.bindcolumn)
- [bindParam](https://php.net/pdostatement.bindParam)
- [bindValue](https://php.net/pdostatement.bindValue)
- [errorCode](https://php.net/pdostatement.errorCode)
- [errorInfo](https://php.net/pdostatement.errorInfo)
- [getAttribute](https://php.net/pdostatement.getAttribute)
- [setAttribute](https://php.net/pdostatement.setAttribute)

## tebe\PDOResult

The `tebe\PDOResult` is a new class that contains those methods from `PDOStatement` that are related to the associated result set of an executed statement.

Besides that it contains several new fetch*() and fetchAll*() methodes for commonly-used fetch actions.

### __construct

```php
public PDOResult::__construct(\PDOStatement $stmt)
```

### fetchAffected

```php
public PDOResult::fetchAffected(): int
```

### fetchAssoc

```php
public PDOResult::fetchAssoc(): array|false
```

### fetchBoth

```php
public PDOResult::fetchBoth(): array|false
```

### fetchInto

```php
public PDOResult::fetchInto(): object|false
```

### fetchNamed

```php
public PDOResult::fetchNamed(): array|false
```

### fetchNumeric

```php
public PDOResult::fetchNumeric(): array|false
```

### fetchPair

```php
public PDOResult::fetchPair(): array|false
```

### fetchNumeric

```php
public PDOResult::fetchNumeric(): array|false
```

### fetchAllAssoc

```php
public PDOResult::fetchAllAssoc(): array
```

### fetchAllBoth

```php
public PDOResult::fetchAllBoth(): array
```

### fetchAllColumn

```php
public PDOResult::fetchAllColumn(int $column = 0): array
```

### fetchAllFunction

```php
public PDOResult::fetchAllFunction(callable $callable): array
```

### fetchAllGroup

```php
public PDOResult::fetchAllGroup(int $style = 0): array
```

### fetchAllNamed

```php
public PDOResult::fetchAllNamed(): array
```

### fetchAllNumeric

```php
public PDOResult::fetchAllNumeric(): array
```

### fetchAllObject

```php
public PDOResult::fetchAllObject(string $class = 'stdClass', ?array $constructorArgs = null, int $style = 0): array
```

### fetchAllPairs

```php
public PDOResult::fetchAllPairs(): array
```

### fetchAllUnique

```php
public PDOResult::fetchAllUnique(int $style = 0): array
```

---

For the remaining `tebe\PDOResult` methods, which are just wrapper methods of the `PDOStatement` class, see the documentation at [php.net](https://php.net/pdostatement).

- [closeCursor](https://php.net/pdostatement.closeCursor)
- [columnCount](https://php.net/pdostatement.columnCount)
- [debugDumpParams](https://php.net/pdostatement.debugDumpParams)
- [errorCode](https://php.net/pdostatement.errorCode)
- [errorInfo](https://php.net/pdostatement.errorInfo)
- [fetch](https://php.net/pdostatement.fetch)
- [fetchAll](https://php.net/pdostatement.fetchAll)
- [fetchColumn](https://php.net/pdostatement.fetchColumn)
- [fetchObject](https://php.net/pdostatement.fetchObject)
- [getColumnMeta](https://php.net/pdostatement.getColumnMeta)
- [nextRowset](https://php.net/pdostatement.nextRowset)
- [rowCount](https://php.net/pdostatement.rowCount)
- [setFetchMode](https://php.net/pdostatement.setFetchMode)

## tebe\PDOParser

### __construct

Creates a `tebe\PDOParser` instance.

```php
public PDOParser::__construct(string $driver)
```

### rebuild

Rebuilds a statement with placeholders and bound values.

```php
public PDOParser::rebuild(string $statement, array $values = []): array
```
