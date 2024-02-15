<?php

declare(strict_types=1);

namespace tebe\pdo\tests;

use tebe\PDOParser;

function test_pdo_parser_rebuild_with_positional_placeholders(): void
{
    $sql = "SELECT * FROM fruits WHERE id = ? AND name = ? AND color = ? AND calories = ?";
    $values = [6, 'Apple', 'red', 200];
    [$newSql, $newValues] = (new PDOParser('sqlite'))->rebuild($sql, $values);
    $expectedNewSql = "SELECT * FROM fruits WHERE id = :__1 AND name = :__2 AND color = :__3 AND calories = :__4";
    $exptectedNewValues = ['__1' => 6, '__2' => 'Apple', '__3' => 'red', '__4' => 200];
    assert_equal($newSql, $expectedNewSql, 'Test rebuild with positional placeholders 1/2');
    assert_equal($newValues, $exptectedNewValues, 'Test rebuild with positional placeholders 2/2');
}

function test_pdo_parser_rebuild_with_named_placeholders(): void
{
    $sql = "SELECT * FROM fruits WHERE id = :id AND name = :name AND color = :color AND calories = :calories";
    $values = ['id' => 6, 'name' => 'Apple', 'color' => 'red', 'calories' => 200];
    [$newSql, $newValues] = (new PDOParser('sqlite'))->rebuild($sql, $values);
    $expectedNewSql = "SELECT * FROM fruits WHERE id = :id AND name = :name AND color = :color AND calories = :calories";
    $exptectedNewValues = ['id' => 6, 'name' => 'Apple', 'color' => 'red', 'calories' => 200];
    assert_equal($newSql, $expectedNewSql, 'Test rebuild with named placeholders 1/2');
    assert_equal($newValues, $exptectedNewValues, 'Test rebuild with named placeholders 2/2');
}
