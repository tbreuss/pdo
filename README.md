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

## fetchAll

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

## fetchAllAssoc

The output is identical to `fetchAll`.

## fetchAllBoth

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

## fetchAllColumn

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

## fetchAllFunction

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
