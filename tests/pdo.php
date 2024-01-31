<?php

namespace tests;

require_once __DIR__ . '/_setup.php';

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
