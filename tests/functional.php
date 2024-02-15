<?php

declare(strict_types=1);

namespace tebe\pdo\tests;

ini_set('display_errors', '1');

error_reporting(E_ERROR | E_PARSE);

require_once dirname(__DIR__) . '/src/PDO.php';
require_once dirname(__DIR__) . '/src/PDOParser.php';
require_once dirname(__DIR__) . '/src/PDOStatement.php';
require_once __DIR__ . '/pdo.php';
require_once __DIR__ . '/pdo_parser.php';
require_once __DIR__ . '/pdo_statement.php';

$numberOfAssertions = 0;

function _assert(mixed $assertion, string $message): void
{
    global $numberOfAssertions;
    assert($assertion, $message);
    echo '✓ ' . $message . PHP_EOL;
    $numberOfAssertions++;
}

function assert_equal(mixed $result, mixed $expected, string $message)
{
    _assert($result === $expected, $message);
}

function assert_instanceof(mixed $result, string $class, string $message)
{
    _assert($result instanceof $class, $message);
}

function init_db(): \tebe\PDO
{
    // set options that are later used for tests
    $db = new \tebe\PDO('sqlite::memory:', options: [
        \tebe\PDO::ATTR_ERRMODE => \tebe\PDO::ERRMODE_SILENT,
        \tebe\PDO::ATTR_PERSISTENT => true,
        \tebe\PDO::ATTR_CASE => \tebe\PDO::CASE_LOWER,
    ]);

    $sql = "DROP TABLE IF EXISTS fruits";
    $db->exec($sql);

    $sql = "CREATE TABLE fruits (id int, name varchar(20), color varchar(20), calories int)";
    $db->exec($sql);

    $sql = "
        INSERT INTO fruits VALUES
            (1, 'Banana', 'yellow', 250),
            (2, 'Apple', 'red', 150),
            (3, 'Pear', 'green', 150),
            (4, 'Orange', 'orange', 300),
            (5, 'Lime', 'green', 333),
            (6, 'Lemon', 'yellow', 25),
            (7, 'Peach', 'orange', 100),
            (8, 'Cherry', 'red', 200)
    ";
    $db->exec($sql);

    return $db;
}

(function () {
    global $numberOfAssertions;
    $definedFunctions = get_defined_functions()['user'] ?? [];
    $filterCallback = function (string $function): bool {
        $namespaceWithFunction = explode('\\', $function);
        $functionName = end($namespaceWithFunction);
        return str_starts_with($functionName, 'test_');
    };
    $testFunctions = array_filter($definedFunctions, $filterCallback);
    $countTestFunctions = count($testFunctions);
    print "Start testing\n";
    foreach ($testFunctions as $testFunction) {
        $testFunction();
    }
    print "Performed $countTestFunctions tests with $numberOfAssertions assertions\n";
})();
