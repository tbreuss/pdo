[![PHP Version Require](http://poser.pugx.org/tebe/pdo/require/php)](https://packagist.org/packages/tebe/pdo)
[![Version](http://poser.pugx.org/tebe/pdo/version)](https://packagist.org/packages/tebe/pdo)
[![Testing tebe\pdo](https://github.com/tbreuss/pdo/actions/workflows/tests.yml/badge.svg)](https://github.com/tbreuss/pdo/actions/workflows/tests.yml)

# tebe\pdo

Provides an extension to the native PDO along with additional functionality.

Because `tebe\pdo` is an extension of the native PDO in the style of a wrapper, code already using the native PDO or typehinted to the native PDO can use `tebe\pdo` with minimal changes for the most part.

Added functionality in `tebe\pdo` over the native PDO includes:

- Exceptions by default. `tebe\pdo` starts in the ERRMODE_EXCEPTION mode for error reporting instead of the ERRMODE_SILENT mode.
- New `PDO::run()` method. This is for convenience to prepare and execute an SQL statement in one step.
- Bind array of values to placeholder in `PDO::run()` method. Placeholders that represent array values will be replaced with comma-separated quoted values. This means you can bind an array of values to a placeholder used with an IN (...) condition.
- Array quoting. The `PDO::quote()` method will accept an array as input, and return a string of comma-separated quoted values.
- New `PDOStatement::fetch*()` methods. The new methods provide for commonly-used fetch actions.
- New `PDOStatement::fetchAll*()` methods. The new methods provide for commonly-used fetch all actions.

## Examples

Create a PDO instance representing a connection to a database.

```php
use tebe\PDO;
$db = new PDO('sqlite:database.sqlite');
```

Execute an SQL statement without placeholders and return the number of affected rows.

```php
$sql = "INSERT INTO fruits VALUES
(1, 'Banana', 'yellow', 250),
(2, 'Apple', 'red', 150),
(3, 'Pear', 'green', 150),
(4, 'Orange', 'orange', 300),
(5, 'Lime', 'green', 333),
(6, 'Lemon', 'yellow', 25),
(7, 'Peach', 'orange', 100),
(8, 'Cherry', 'red', 200)";
print $db->exec($sql);
// outputs 8
```

Prepare and execute an SQL statement without placeholders, and fetch all rows from the result set.

```php
$sql = "SELECT name, color FROM fruits WHERE color = 'green' ORDER BY name";
print json_encode($db->query($sql)->fetchAll());
// outputs [{"name":"Lime","color":"green"},{"name":"Pear","color":"green"}]
```

Prepare a statement with placeholders for execution, return a statement object, and fetch all columns from the result set.

```php
$sql = "SELECT name FROM fruits WHERE color = ? ORDER BY name";
print json_encode($db->prepare($sql)->execute(['red'])->fetchAllColumn());
// outputs ["Apple","Cherry"]
```

Run a query with placeholders, return the resulting PDOStatement, and fetch key value array (pairs) from the result set.

```php
$sql = "SELECT name, calories FROM fruits WHERE color = ? ORDER BY name";
print json_encode($db->run($sql, ['red'])->fetchAllPair());
// outputs {"Banana":250,"Lemon":25}
```

Run a query with an IN clause and placeholders, return the resulting PDOStatement, and fetch all rows grouped by color from the result set.

```php
$sql = "SELECT color, name FROM fruits WHERE color IN (?) ORDER BY name";
print json_encode($db->run($sql, [['green', 'red']])->fetchAllGroup());
// outputs {"red":[{"name":"Apple"},{"name":"Cherry"}],"green":[{"name":"Lime"},{"name":"Pear"}]}
```

Quote an array for use in a query.

```php
print $db->quote(['red', 'green', 'yellow']);
// outputs 'red', 'green', 'yellow'
```

## Installation and Autoloading

This package is installable and PSR-4 autoloadable via Composer as `tebe/pdo`.

Alternatively, download a release, or clone this repository, then include the classes manually:

```php
include '{path/to/tebe/pdo}/src/PDO.php';
include '{path/to/tebe/pdo}/src/PDOStatement.php';
include '{path/to/tebe/pdo}/src/PDOParser.php';
```

## Dependencies

This package requires PHP 8.1 or later; it has been tested on PHP 8.1-8.3. We recommend using the latest available version of PHP as a matter of principle. `tebe\pdo` doesn't depend on other external packages.

## Documentation

### tebe\PDO

The class `tebe\PDO` is a wrapper for the native `PDO` class and implements all methods of this class. 

The main differences are that some methods return `tebe\PDOStatement` instances and the `quote` method can also handle arrays.

In addition, the class contains a new method `run`, which executes a query with bound values and returns the resulting statement instance.

#### getWrappedPdo

Returns the underlying PDO instance.

```php
public PDO::getWrappedPdo(): \PDO
```

#### prepare

Prepares a statement for execution and returns a statement object.

This differs from `PDO::prepare` in that it will return a `tebe\PDOStatement` object.

```php
public PDO::prepare(string $query, array $options = []): PDOStatement|false
```

See [php.net](https://php.net/pdo.prepare)

#### query

Prepares and executes an SQL statement without placeholders.

This differs from `PDO::query` in that it will return a `tebe\PDOStatement` object.

```php
public PDO::query(string $query, mixed ...$fetchModeArgs): PDOStatement|false
```

See [php.net](https://php.net/pdo.query)

#### quote

Quotes a string for use in a query.

This differs from `PDO::quote` in that it will convert an array into a string of comma-separated quoted values.

```php
public PDO::quote(array|string|int|float|null $value, int $type = PDO::PARAM_STR): string|false
```

See [php.net](https://php.net/pdo.quote)

#### run

Runs a query with bound values and returns the resulting `tebe\PDOStatement`. 

Array values will be processed by the parser instance and placeholders are replaced.

```php
public PDO::run(string $sql, ?array $args = null): PDOStatement|false
```

---

The remaining `tebe\PDO` methods are simple wrapper methods of the underlying `PDO` class.
For more information, see [php.net](https://php.net/pdo).

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

### tebe\PDOStatement

The class `tebe\PDOStatement` is a wrapper for the native `PDOStatement` class and implements all methods of this class. 

The main difference is that the `execute` method returns a statement instance. This was done to allow method chaining aka fluent interface.

Besides that it contains several new `fetch*()` and `fetchAll*()` methodes for commonly-used fetch actions.

#### __construct

Creates a `tebe\PDOStatement` instance representing a query statement and wraps the original `PDOStatement`.

```php
public PDOStatement::__construct(\PDOStatement $stmt)
```

#### execute

Executes a prepared statement

This differs from `PDOStatement::execute` in that it will return a `tebe\PDOStatement` object.

```php
public PDOStatement::execute(?array $params = null): PDOStatement|false
```

See [php.net](https://php.net/pdostatement.execute)

#### fetchAffected

Fetches the number of affected rows from the result set.

```php
public PDOStatement::fetchAffected(): int
```

#### fetchAssoc

Fetches the next row from the result set as an associative array.

```php
public PDOStatement::fetchAssoc(): array|false
```

#### fetchBoth

Fetches the next row from the result set as an associative and indexed array.

```php
public PDOStatement::fetchBoth(): array|false
```

#### fetchInto

Fetches the next row from the result as the updated passed object, by mapping the columns to named properties of the object.

```php
public PDOStatement::fetchInto(object $object): object|false
```

#### fetchNamed

Fetches the next row from the result set as an associative array;
If the result set contains multiple columns with the same name, an array of values per column name is returned.

```php
public PDOStatement::fetchNamed(): array|false
```

#### fetchNumeric

Fetches the next row from the result set as an indexed array.

```php
public PDOStatement::fetchNumeric(): array|false
```

#### fetchPair

Fetches the next row from the result set as a key-value pair.

```php
public PDOStatement::fetchPair(): array|false
```

#### fetchAllAssoc

Fetches all rows from the result set as an array of associative arrays.

```php
public PDOStatement::fetchAllAssoc(): array
```

#### fetchAllBoth

Fetches all rows from the result set as an array of associative and indexed arrays.

```php
public PDOStatement::fetchAllBoth(): array
```

#### fetchAllColumn

Fetches all rows from the result set as an array of a single column.

```php
public PDOStatement::fetchAllColumn(int $column = 0): array
```

#### fetchAllFunction

Fetches all rows from the result set as an array by calling a function for each row.

```php
public PDOStatement::fetchAllFunction(callable $callable): array
```

#### fetchAllGroup

Fetches all rows from the result set as an array by grouping all rows by a single column.

```php
public PDOStatement::fetchAllGroup(int $style = 0): array
```

#### fetchAllNamed

Fetches all rows from the result set as an array of associative arrays;
If the result set contains multiple columns with the same name, an array of values per column name is returned.

```php
public PDOStatement::fetchAllNamed(): array
```

#### fetchAllNumeric

Fetches all rows from the result set as an array of indexed arrays.

```php
public PDOStatement::fetchAllNumeric(): array
```

#### fetchAllObject

Fetches all rows from the result set as an array of objects of the requested class, 
mapping the columns to named properties in the class.

```php
public PDOStatement::fetchAllObject(string $class = 'stdClass', ?array $constructorArgs = null, int $style = 0): array
```

#### fetchAllPair

Fetches all rows from the result set as an array of key-value pairs.

```php
public PDOStatement::fetchAllPair(): array
```

#### fetchAllUnique

Fetches all rows from the result set as an array of arrays, indexed by an unique field.

```php
public PDOStatement::fetchAllUnique(int $style = 0): array
```

---

The remaining `tebe\PDOStatement` methods are simple wrapper methods of the underlying `PDOStatement` class.
For more information, see [php.net](https://php.net/pdostatement).

- [bindColumn](https://php.net/pdostatement.bindcolumn)
- [bindParam](https://php.net/pdostatement.bindParam)
- [bindValue](https://php.net/pdostatement.bindValue)
- [closeCursor](https://php.net/pdostatement.closeCursor)
- [columnCount](https://php.net/pdostatement.columnCount)
- [debugDumpParams](https://php.net/pdostatement.debugDumpParams)
- [errorCode](https://php.net/pdostatement.errorCode)
- [errorInfo](https://php.net/pdostatement.errorInfo)
- [fetch](https://php.net/pdostatement.fetch)
- [fetchAll](https://php.net/pdostatement.fetchAll)
- [fetchColumn](https://php.net/pdostatement.fetchColumn)
- [fetchObject](https://php.net/pdostatement.fetchObject)
- [getAttribute](https://php.net/pdostatement.getAttribute)
- [getColumnMeta](https://php.net/pdostatement.getColumnMeta)
- [getIterator](https://php.net/pdostatement.getIterator)
- [nextRowset](https://php.net/pdostatement.nextRowset)
- [rowCount](https://php.net/pdostatement.rowCount)
- [setAttribute](https://php.net/pdostatement.setAttribute)
- [setFetchMode](https://php.net/pdostatement.setFetchMode)

### tebe\PDOParser

Class `tebe\PDOParser` offers parsing and rebuilding functions for all drivers.

#### __construct

Creates a `tebe\PDOParser` instance.

```php
public PDOParser::__construct(string $driver)
```

#### rebuild

Rebuilds a statement with placeholders and bound values.

```php
public PDOParser::rebuild(string $statement, array $values = []): array
```

## Quality

[![Testing tebe\pdo](https://github.com/tbreuss/pdo/actions/workflows/tests.yml/badge.svg)](https://github.com/tbreuss/pdo/actions/workflows/tests.yml)

This project adheres to [Semantic Versioning](https://semver.org/).

To run the functional tests at the command line, issue `composer install` and then `composer test` at the package root. (This requires [Composer](https://getcomposer.org/) to be available as `composer`.)

This package attempts to comply with [PSR-1](https://www.php-fig.org/psr/psr-1/), [PSR-4](https://www.php-fig.org/psr/psr-4/), and [PSR-12](https://www.php-fig.org/psr/psr-12/). If you notice compliance oversights, please send a patch via pull request.
