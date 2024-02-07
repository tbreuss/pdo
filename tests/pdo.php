<?php

namespace tests;

require_once __DIR__ . '/_setup.php';

use tebe\PDO;
use tebe\PDOStatement;

# Begin/rollBack transaction

$db->beginTransaction();
$db->exec("DELETE FROM fruits");
$db->rollBack();

$count = (int)$db->run("SELECT COUNT(id) FROM fruits")->fetchColumn();
assert_equal($count, 8, 'Begin and roll back transaction');

# Begin/commit transaction

$db->beginTransaction();
$db->exec("INSERT INTO fruits VALUES (999, 'XYZ', 'xyz', 999)");
$db->commit();

$count = (int)$db->run("SELECT COUNT(id) FROM fruits")->fetchColumn();
$db->exec("DELETE FROM fruits WHERE id = 999");
assert_equal($count, 9, 'Begin and commit back transaction');

# Error code

$errorCode = $db->errorCode();
assert_equal($errorCode, '00000', 'Empty error code');

$db->exec("DELETE FROM not_existing_db");
$errorCode = $db->errorCode();
assert_equal($errorCode, 'HY000', 'Error code HY000');

# Error info

$count = (int)$db->run("SELECT COUNT(id) FROM fruits")->fetchColumn();
$errorInfo = $db->errorInfo();
$expected = ['00000', null, null];
assert_equal($errorInfo, $expected, 'Empty error info');

$db->exec("DELETE FROM not_existing_db");
$errorInfo = $db->errorInfo();
$expected = ['HY000', 1, 'no such table: not_existing_db'];
assert_equal($errorInfo, $expected, 'Error info HY000');

# Exec
$count = $db->exec("INSERT INTO fruits VALUES (888, 'name', 'color', 888), (999, 'name', 'color', 999)");
assert_equal($count, 2, 'Exec with insert into');

$count = $db->exec("DELETE FROM fruits WHERE id IN (888, 999)");
assert_equal($count, 2, 'Exec with delete from');

$count = $db->exec("DELETE FROM fruits WHERE id IN (888, 999)");
assert_equal($count, 0, 'Exec with delete from and no affected rows');

$count = $db->exec("DELETE FROM not_existing_table");
assert_equal($count, false, 'Exec with delete from not existing table 1/2');
assert_equal($db->errorCode(), 'HY000', 'Exec with delete from not existing table 2/2');

# Get attribute

$errorMode = $db->getAttribute(PDO::ATTR_PERSISTENT);
assert_equal($errorMode, true, 'Get attribute is ATTR_PERSISTENT');

# Get available drivers
$drivers = $db::getAvailableDrivers();
$expected = ['dblib', 'mysql', 'odbc', 'pgsql', 'sqlite'];
assert_equal($drivers, $expected, 'Get available drivers');

# Get wrapped PDO
$pdo = $db->getWrappedPdo();
assert_instanceof($pdo, \PDO::class, 'Get wrapped PDO');

# In transaction
$status = $db->inTransaction();
$expected = false;
assert_equal($status, $expected, 'In transaction is false');

# Last insert id
$db->exec("INSERT INTO fruits VALUES (999, 'XYZ', 'xyz', 999)");
$lastInsertId = $db->lastInsertId();
$expected = '9';
assert_equal($lastInsertId, $expected, 'Last insert id is ' . $expected);

# Prepare
$sql = "SELECT * FROM fruits WHERE id > ?";
$stmt = $db->prepare($sql);
assert_instanceof($stmt, PDOStatement::class, 'Prepare returns PDOStatement');
assert_equal($stmt->queryString, $sql, 'Query string of PDOStatement');

# Quote
$value = $db->quote('abc');
assert_equal($value, "'abc'", 'Quote string');

$value = $db->quote(23);
assert_equal($value, "'23'", 'Quote integer');

$value = $db->quote(25.37);
assert_equal($value, "'25.37'", 'Quote float');

$value = $db->quote(null);
assert_equal($value, "''", 'Quote null');

$value = $db->quote(['a', 'b', 'c']);
assert_equal($value, "'a', 'b', 'c'", 'Quote string array');

$value = $db->quote([1, 2, 3]);
assert_equal($value, "'1', '2', '3'", 'Quote integer array');

$value = $db->quote([1.1, 2.2, 3.3]);
assert_equal($value, "'1.1', '2.2', '3.3'", 'Quote float array');

$value = $db->quote([null, null, null]);
assert_equal($value, "'', '', ''", 'Quote null array');

# Set attribute
$status = $db->setAttribute(PDO::ATTR_CASE, PDO::CASE_UPPER);
assert_equal($status, true, 'Set attribute return value');

$attr = $db->getAttribute(PDO::ATTR_CASE);
assert_equal($attr, PDO::CASE_UPPER, 'Set attribute successful');
