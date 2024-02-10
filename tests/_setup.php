<?php

namespace tests;

error_reporting(E_ERROR | E_PARSE);

require_once dirname(__DIR__) . '/src/PDO.php';
require_once dirname(__DIR__) . '/src/PDOParser.php';
require_once dirname(__DIR__) . '/src/PDOStatement.php';

// ------------------------------------------------------------------------------------------------------------------------
// Helpers
// ------------------------------------------------------------------------------------------------------------------------

$numberOfTests = 0;

function _assert(mixed $assertion, string $message): void
{
    global $numberOfTests;
    assert($assertion, $message);
    echo '✓ ' . $message . PHP_EOL;
    $numberOfTests++;
}

function assert_equal(mixed $result, mixed $expected, string $message) {
    _assert($result === $expected, $message);
}

function assert_instanceof(mixed $result, string $class, string $message) {
    _assert($result instanceof $class, $message);
}

// ------------------------------------------------------------------------------------------------------------------------
// Setup
// ------------------------------------------------------------------------------------------------------------------------

function init_db(): \tebe\PDO
{
    $db = new \tebe\PDO('sqlite::memory:', options: [
        \tebe\PDO::ATTR_ERRMODE => \tebe\PDO::ERRMODE_SILENT,
        \tebe\PDO::ATTR_PERSISTENT => true
    ]);

    $sql = "CREATE TABLE fruits (id int, name varchar(20), color varchar(20), calories int)";
    $db->run($sql);
    
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
    $db->run($sql);

    return $db;
}

$db = init_db();
