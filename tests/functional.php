<?php

namespace tests;

require_once dirname(__DIR__) . '/src/PDO.php';
require_once dirname(__DIR__) . '/src/PDOStatement.php';

use tebe\pdox\PDO;

// ------------------------------------------------------------------------------------------------------------------------
// Setup
// ------------------------------------------------------------------------------------------------------------------------

$db = new PDO('sqlite::memory:');

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

// ------------------------------------------------------------------------------------------------------------------------
// Fetch All methods
// ------------------------------------------------------------------------------------------------------------------------

# Fetch All

$sql = "SELECT * FROM fruits ORDER BY 1";
$result = json_encode($db->run($sql)->fetchAll());
$expected = '[{"id":1,"name":"Banana","color":"yellow","calories":250},{"id":2,"name":"Apple","color":"red","calories":150},{"id":3,"name":"Pear","color":"green","calories":150},{"id":4,"name":"Orange","color":"orange","calories":300},{"id":5,"name":"Lime","color":"green","calories":333},{"id":6,"name":"Lemon","color":"yellow","calories":25},{"id":7,"name":"Peach","color":"orange","calories":100},{"id":8,"name":"Cherry","color":"red","calories":200}]';
test_identical($result, $expected, 'Fetch all');

# Fetch All Assoc

$sql = "SELECT * FROM fruits ORDER BY 1";
$result = json_encode($db->run($sql)->fetchAllAssoc());
$expected = '[{"id":1,"name":"Banana","color":"yellow","calories":250},{"id":2,"name":"Apple","color":"red","calories":150},{"id":3,"name":"Pear","color":"green","calories":150},{"id":4,"name":"Orange","color":"orange","calories":300},{"id":5,"name":"Lime","color":"green","calories":333},{"id":6,"name":"Lemon","color":"yellow","calories":25},{"id":7,"name":"Peach","color":"orange","calories":100},{"id":8,"name":"Cherry","color":"red","calories":200}]';
test_identical($result, $expected, 'Fetch all assoc');

# Fetch All Both

$sql = "SELECT * FROM fruits ORDER BY 1";
$result = json_encode($db->run($sql)->fetchAllBoth());
$expected = '[{"id":1,"0":1,"name":"Banana","1":"Banana","color":"yellow","2":"yellow","calories":250,"3":250},{"id":2,"0":2,"name":"Apple","1":"Apple","color":"red","2":"red","calories":150,"3":150},{"id":3,"0":3,"name":"Pear","1":"Pear","color":"green","2":"green","calories":150,"3":150},{"id":4,"0":4,"name":"Orange","1":"Orange","color":"orange","2":"orange","calories":300,"3":300},{"id":5,"0":5,"name":"Lime","1":"Lime","color":"green","2":"green","calories":333,"3":333},{"id":6,"0":6,"name":"Lemon","1":"Lemon","color":"yellow","2":"yellow","calories":25,"3":25},{"id":7,"0":7,"name":"Peach","1":"Peach","color":"orange","2":"orange","calories":100,"3":100},{"id":8,"0":8,"name":"Cherry","1":"Cherry","color":"red","2":"red","calories":200,"3":200}]';
test_identical($result, $expected, 'Fetch all both');

# Fetch All Column

$sql = "SELECT * FROM fruits ORDER BY 1";
$result = json_encode($db->run($sql)->fetchAllColumn());
$expected = '[1,2,3,4,5,6,7,8]';
test_identical($result, $expected, 'Fetch all column');

$result = json_encode($db->run($sql)->fetchAllColumn(2));
$expected = '["yellow","red","green","orange","green","yellow","orange","red"]';
test_identical($result, $expected, 'Fetch all column with explicit column');

# Fetch All Function

$sql = "SELECT * FROM fruits ORDER BY 1";
$function = function (mixed ...$item) {
    return join('-', $item);
};
$result = json_encode($db->run($sql)->fetchAllFunction($function));
$expected = '["1-Banana-yellow-250","2-Apple-red-150","3-Pear-green-150","4-Orange-orange-300","5-Lime-green-333","6-Lemon-yellow-25","7-Peach-orange-100","8-Cherry-red-200"]';
test_identical($result, $expected, 'Fetch all function');

# Fetch All Group

$sql = "SELECT color, id, name FROM fruits ORDER BY 1";
$result = json_encode($db->run($sql)->fetchAllGroup());
$expected = '{"green":[{"id":3,"name":"Pear"},{"id":5,"name":"Lime"}],"orange":[{"id":4,"name":"Orange"},{"id":7,"name":"Peach"}],"red":[{"id":2,"name":"Apple"},{"id":8,"name":"Cherry"}],"yellow":[{"id":1,"name":"Banana"},{"id":6,"name":"Lemon"}]}';
test_identical($result, $expected, 'Fetch all group');

$sql = "SELECT * FROM fruits ORDER BY 1";
$result = json_encode($db->run($sql)->fetchAllGroup(PDO::FETCH_COLUMN));
$expected = '{"1":["Banana"],"2":["Apple"],"3":["Pear"],"4":["Orange"],"5":["Lime"],"6":["Lemon"],"7":["Peach"],"8":["Cherry"]}';
test_identical($result, $expected, 'Fetch all group with additional column style');

# Fetch All Numeric

$sql = "SELECT * FROM fruits ORDER BY 1";
$result = json_encode($db->run($sql)->fetchAllNumeric());
$expected = '[[1,"Banana","yellow",250],[2,"Apple","red",150],[3,"Pear","green",150],[4,"Orange","orange",300],[5,"Lime","green",333],[6,"Lemon","yellow",25],[7,"Peach","orange",100],[8,"Cherry","red",200]]';
test_identical($result, $expected, 'Fetch all numeric');

# Fetch All Object

$sql = "SELECT color, fruits.* FROM fruits ORDER BY 1";
$result = json_encode($db->run($sql)->fetchAllObject(Fruit::class));
$expected = '[{"id":3,"name":"Pear","color":"green","calories":150},{"id":5,"name":"Lime","color":"green","calories":333},{"id":4,"name":"Orange","color":"orange","calories":300},{"id":7,"name":"Peach","color":"orange","calories":100},{"id":2,"name":"Apple","color":"red","calories":150},{"id":8,"name":"Cherry","color":"red","calories":200},{"id":1,"name":"Banana","color":"yellow","calories":250},{"id":6,"name":"Lemon","color":"yellow","calories":25}]';
test_identical($result, $expected, 'Fetch all object');

# Fetch All Pairs

$sql = "SELECT name, color FROM fruits ORDER BY 1";
$result = json_encode($db->run($sql)->fetchAllPairs());
$expected = '{"Apple":"red","Banana":"yellow","Cherry":"red","Lemon":"yellow","Lime":"green","Orange":"orange","Peach":"orange","Pear":"green"}';
test_identical($result, $expected, 'Fetch all pairs');

# Fetch All Unique

$sql = "SELECT id, name, color, calories FROM fruits ORDER BY 1";
$result = json_encode($db->run($sql)->fetchAllUnique());
$expected = '{"1":{"name":"Banana","color":"yellow","calories":250},"2":{"name":"Apple","color":"red","calories":150},"3":{"name":"Pear","color":"green","calories":150},"4":{"name":"Orange","color":"orange","calories":300},"5":{"name":"Lime","color":"green","calories":333},"6":{"name":"Lemon","color":"yellow","calories":25},"7":{"name":"Peach","color":"orange","calories":100},"8":{"name":"Cherry","color":"red","calories":200}}';
test_identical($result, $expected, 'Fetch all unique');

// ------------------------------------------------------------------------------------------------------------------------
// Fetch methods
// ------------------------------------------------------------------------------------------------------------------------

# Fetch

$sql = "SELECT * FROM fruits WHERE id = 3";
$result = json_encode($db->run($sql)->fetch());
$expected = '{"id":3,"name":"Pear","color":"green","calories":150}';
test_identical($result, $expected, 'Fetch');

$sql = "SELECT * FROM fruits WHERE id = 9999";
$result = $db->run($sql)->fetch();
$expected = false;
test_identical($result, $expected, 'Fetch with not existing record');

# Fetch Assoc

$sql = "SELECT * FROM fruits WHERE id = 3";
$result = json_encode($db->run($sql)->fetchAssoc());
$expected = '{"id":3,"name":"Pear","color":"green","calories":150}';
test_identical($result, $expected, 'Fetch assoc');

$sql = "SELECT * FROM fruits WHERE id = 9999";
$result = $db->run($sql)->fetchAssoc();
$expected = false;
test_identical($result, $expected, 'Fetch assoc with not existing record');

# Fetch Both

$sql = "SELECT * FROM fruits WHERE id = 3";
$result = json_encode($db->run($sql)->fetchBoth());
$expected = '{"id":3,"0":3,"name":"Pear","1":"Pear","color":"green","2":"green","calories":150,"3":150}';
test_identical($result, $expected, 'Fetch both');

$sql = "SELECT * FROM fruits WHERE id = 9999";
$result = $db->run($sql)->fetchBoth();
$expected = false;
test_identical($result, $expected, 'Fetch both with not existing record');

# Fetch Column

$sql = "SELECT * FROM fruits WHERE id = 3";
$result = $db->run($sql)->fetchColumn();
$expected = 3;
test_identical($result, $expected, 'Fetch column');

$result = $db->run($sql)->fetchColumn(3);
$expected = 150;
test_identical($result, $expected, 'Fetch column with explicit column');

$sql = "SELECT * FROM fruits WHERE id = 9999";
$result = $db->run($sql)->fetchColumn();
$expected = false;
test_identical($result, $expected, 'Fetch column with not existing record');

# Fetch Numeric

$sql = "SELECT * FROM fruits WHERE id = 3";
$result = json_encode($db->run($sql)->fetchNumeric());
$expected = '[3,"Pear","green",150]';
test_identical($result, $expected, 'Fetch numeric');

$sql = "SELECT * FROM fruits WHERE id = 9999";
$result = $db->run($sql)->fetchNumeric();
$expected = false;
test_identical($result, $expected, 'Fetch numeric with not existing record');

# Fetch Object

$sql = "SELECT * FROM fruits WHERE id = 3";
$object = $db->run($sql)->fetchObject(Fruit::class);
$expected = Fruit::class;
test_instanceof($object, $expected, 'Fetch object instance');

$result = json_encode($object);
$expected = '{"id":3,"name":"Pear","color":"green","calories":150}';
test_identical($result, $expected, 'Fetch object');

$sql = "SELECT * FROM fruits WHERE id = 9999";
$result = $db->run($sql)->fetchObject(Fruit::class);
$expected = false;
test_identical($result, $expected, 'Fetch object with not existing record');

# Fetch Pair

$sql = "SELECT name, color FROM fruits WHERE id = 3";
$result = json_encode($db->run($sql)->fetchPair());
$expected = '{"Pear":"green"}';
test_identical($result, $expected, 'Fetch pair');

$sql = "SELECT name, color FROM fruits WHERE id = 9999";
$result = $db->run($sql)->fetchPair();
$expected = false;
test_identical($result, $expected, 'Fetch pair with not existing record');

// ------------------------------------------------------------------------------------------------------------------------
// Misc methods
// ------------------------------------------------------------------------------------------------------------------------

# Fetch Affected

$sql = "INSERT INTO fruits VALUES (0, 'Kiwi', 'green', 150), (0, 'Plum', 'purple', 250)";
$result = $db->run($sql)->fetchAffected();
$expected = 2;
test_identical($result, $expected, 'Fetch affected');

// ------------------------------------------------------------------------------------------------------------------------
// Classes
// ------------------------------------------------------------------------------------------------------------------------

class Fruit {
    public int $id; 
    public string $name;
    public string $color;
    public int $calories;
}

// ------------------------------------------------------------------------------------------------------------------------
// Helpers
// ------------------------------------------------------------------------------------------------------------------------

$numberOfTests = 0;

function test(mixed $assertion, string $message): void
{
    global $numberOfTests;
    assert($assertion, $message);
    echo $message . ' ✓' . PHP_EOL;
    $numberOfTests++;
}

function test_identical(mixed $result, mixed $expected, string $message) {
    test($result === $expected, $message);
}

function test_instanceof(mixed $result, string $class, string $message) {
    test($result instanceof $class, $message);
}
