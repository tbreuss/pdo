<?php

namespace tests;

require_once __DIR__ . '/_setup.php';

use tebe\PDO;

# Bind param

$id = 6;
$name = 'Lemon';
$color = 'yellow';
$calories = 25;
$stmt = $db->prepare('SELECT * FROM fruits WHERE id = ? AND name = ? AND color = ? AND calories = ?');
$stmt->bindParam(1, $id, PDO::PARAM_INT);
$stmt->bindParam(2, $name, PDO::PARAM_STR);
$stmt->bindParam(3, $color, PDO::PARAM_STR);
$stmt->bindParam(4, $calories, PDO::PARAM_INT);
$result = $stmt->execute()->fetch();
$expected = ["id"=>6,"name"=>"Lemon","color"=>"yellow","calories"=>25];
assert_equal($result, $expected, 'Bind param with question mark placeholders');

$id = 6;
$name = 'Lemon';
$color = 'yellow';
$calories = 25;
$stmt = $db->prepare('SELECT * FROM fruits WHERE id = :id AND name = :name AND color = :color AND calories = :calories');
$stmt->bindParam('id', $id, PDO::PARAM_INT);
$stmt->bindParam('name', $name, PDO::PARAM_STR);
$stmt->bindParam(':color', $color, PDO::PARAM_STR);
$stmt->bindParam(':calories', $calories, PDO::PARAM_INT);
$result = $stmt->execute()->fetch();
$expected = ["id"=>6,"name"=>"Lemon","color"=>"yellow","calories"=>25];
assert_equal($result, $expected, 'Bind param with named placeholders');

# Bind value

$id = 6;
$name = 'Lemon';
$color = 'yellow';
$calories = 25;
$stmt = $db->prepare('SELECT * FROM fruits WHERE id = ? AND name = ? AND color = ? AND calories = ?');
$stmt->bindValue(1, $id, PDO::PARAM_INT);
$stmt->bindValue(2, $name, PDO::PARAM_STR);
$stmt->bindValue(3, $color, PDO::PARAM_STR);
$stmt->bindValue(4, $calories, PDO::PARAM_INT);
$result = $stmt->execute()->fetch();
$expected = ["id"=>6,"name"=>"Lemon","color"=>"yellow","calories"=>25];
assert_equal($result, $expected, 'Bind value with question mark placeholders');

$id = 6;
$name = 'Lemon';
$color = 'yellow';
$calories = 25;
$stmt = $db->prepare('SELECT * FROM fruits WHERE id = :id AND name = :name AND color = :color AND calories = :calories');
$stmt->bindValue('id', $id, PDO::PARAM_INT);
$stmt->bindValue('name', $name, PDO::PARAM_STR);
$stmt->bindValue(':color', $color, PDO::PARAM_STR);
$stmt->bindValue(':calories', $calories, PDO::PARAM_INT);
$result = $stmt->execute()->fetch();
$expected = ["id"=>6,"name"=>"Lemon","color"=>"yellow","calories"=>25];
assert_equal($result, $expected, 'Bind value with named placeholders');

# Execute

$count = (int)$db->prepare("SELECT COUNT(id) FROM fruits")->execute()->fetchColumn();
assert_equal($count, 8, 'Execute without params');

$count = (int)$db->prepare("SELECT COUNT(id) FROM fruits WHERE id > ?")->execute([5])->fetchColumn();
assert_equal($count, 3, 'Execute with params');

# Get attribute
$attribute = $db->prepare("SELECT COUNT(id) FROM fruits")->getAttribute(PDO::ATTR_EMULATE_PREPARES);
assert_equal($attribute, false, 'Get attribute');

# Set attribute
$status = $db->prepare("SELECT COUNT(id) FROM fruits")->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
assert_equal($status, false, 'Set attribute');
