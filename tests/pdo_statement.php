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


// ------------------------------------------------------------------------------------------------------------------------
// Misc methods
// ------------------------------------------------------------------------------------------------------------------------

# Close cursor

$stmt = $db->prepare('SELECT id FROM fruits');
$otherStmt = $db->prepare('SELECT id FROM fruits');
$stmt->execute()->fetch();
$stmt->closeCursor();
$otherStmt->execute();

# Array binding

$ids = [1, 3, 5, 7];

$sql = "SELECT id FROM fruits WHERE id IN (?) OR id IN (?) ORDER BY 1";
$result = $db->run($sql, [$ids, $ids])->fetchAllColumn();
$expected = $ids;
assert_equal($result, $expected, 'Bind array of values to positional parameters');

$sql = "SELECT id FROM fruits WHERE id IN (:ids1) OR id IN (:ids2) ORDER BY 1";
$result = $db->run($sql, ['ids1' => $ids, 'ids2' => $ids])->fetchAllColumn();
$expected = $ids;
assert_equal($result, $expected, 'Bind array of values to named parameters');

# Bind column

$res = $db->prepare("SELECT * FROM fruits WHERE id = 6")->execute();
$res->bindColumn(1, $id);
$res->bindColumn(2, $name);
$res->bindColumn('color', $color);
$res->bindColumn('calories', $calories);
$result = '';
if ($res->fetch(PDO::FETCH_BOUND)) {    
    $result = join('-', [$id, $name, $color, $calories]);
}
assert_equal($result, '6-Lemon-yellow-25', 'Bind column with column number and name');

# Fetch

$sql = "SELECT * FROM fruits WHERE id = ?";

$result = $db->run($sql, [3])->fetch();
$expected = ["id"=>3,"name"=>"Pear","color"=>"green","calories"=>150];
assert_equal($result, $expected, 'Fetch');

$result = $db->run($sql, [9999])->fetch();
$expected = false;
assert_equal($result, $expected, 'Fetch with not existing record');

# Fetch Affected

$sql = "INSERT INTO fruits VALUES (998, 'Kiwi', 'green', 150), (999, 'Plum', 'purple', 250)";
$result = $db->run($sql)->fetchAffected();
$expected = 2;
assert_equal($result, $expected, 'Fetch affected');
$db->exec('DELETE FROM fruits WHERE id IN (998, 999)');

# Fetch Assoc

$sql = "SELECT * FROM fruits WHERE id = ?";

$result = $db->run($sql, [3])->fetchAssoc();
$expected = ["id"=>3,"name"=>"Pear","color"=>"green","calories"=>150];
assert_equal($result, $expected, 'Fetch assoc');

$result = $db->run($sql, [9999])->fetchAssoc();
$expected = false;
assert_equal($result, $expected, 'Fetch assoc with not existing record');

# Fetch Both

$sql = "SELECT * FROM fruits WHERE id = ?";

$result = $db->run($sql, [3])->fetchBoth();
$expected = ["id"=>3,"0"=>3,"name"=>"Pear","1"=>"Pear","color"=>"green","2"=>"green","calories"=>150,"3"=>150];
assert_equal($result, $expected, 'Fetch both');

$result = $db->run($sql, [9999])->fetchBoth();
$expected = false;
assert_equal($result, $expected, 'Fetch both with not existing record');

# Fetch Column

$sql = "SELECT * FROM fruits WHERE id = ?";

$result = $db->run($sql, [3])->fetchColumn();
$expected = 3;
assert_equal($result, $expected, 'Fetch column');

$result = $db->run($sql, [3])->fetchColumn(3);
$expected = 150;
assert_equal($result, $expected, 'Fetch column with explicit column');

$result = $db->run($sql, [9999])->fetchColumn();
$expected = false;
assert_equal($result, $expected, 'Fetch column with not existing record');

# Fetch Into

$sql = "SELECT * FROM fruits WHERE id = ?";

$fruit = new Fruit();
$result = $db->run($sql, [3])->fetchInto($fruit);
$expected = Fruit::class;
assert_instanceof($result, $expected, 'Fetch into instance');

$expected = ["id"=>3,"name"=>"Pear","color"=>"green","calories"=>150];
assert_equal((array)$result, $expected, 'Fetch into');

$result = $db->run($sql, [9999])->fetchInto($fruit);
$expected = false;
assert_equal($result, $expected, 'Fetch into with not existing record');

# Fetch Named

$sql = "SELECT f1.*, f2.color FROM fruits f1 INNER JOIN fruits f2 ON f2.id = f1.id + 1 WHERE f1.id = ?";

$result = $db->run($sql, [3])->fetchNamed();
$expected = ["id"=>3,"name"=>"Pear","color"=>["green","orange"],"calories"=>150];
assert_equal($result, $expected, 'Fetch named');

$result = $db->run($sql, [9999])->fetchNamed();
$expected = false;
assert_equal($result, $expected, 'Fetch named with not existing record');

# Fetch Numeric

$sql = "SELECT * FROM fruits WHERE id = ?";

$result = $db->run($sql, [3])->fetchNumeric();
$expected = [3,"Pear","green",150];
assert_equal($result, $expected, 'Fetch numeric');

$result = $db->run($sql, [9999])->fetchNumeric();
$expected = false;
assert_equal($result, $expected, 'Fetch numeric with not existing record');

# Fetch Object

$sql = "SELECT * FROM fruits WHERE id = ?";

$result = $db->run($sql, [3])->fetchObject(Fruit::class);
$expected = Fruit::class;
assert_instanceof($result, $expected, 'Fetch object instance');

$expected = ["id"=>3,"name"=>"Pear","color"=>"green","calories"=>150];
assert_equal((array)$result, $expected, 'Fetch object');

$result = $db->run($sql, [9999])->fetchObject(Fruit::class);
$expected = false;
assert_equal($result, $expected, 'Fetch object with not existing record');

# Fetch Pair

$sql = "SELECT name, color FROM fruits WHERE id = ?";

$result = $db->run($sql, [3])->fetchPair();
$expected = ["Pear" => "green"];
assert_equal($result, $expected, 'Fetch pair');

$result = $db->run($sql, [9999])->fetchPair();
$expected = false;
assert_equal($result, $expected, 'Fetch pair with not existing record');

# Fetch All

$sql = "SELECT * FROM fruits ORDER BY 1";
$result = $db->run($sql)->fetchAll();
$expected = [["id"=>1,"name"=>"Banana","color"=>"yellow","calories"=>250],["id"=>2,"name"=>"Apple","color"=>"red","calories"=>150],["id"=>3,"name"=>"Pear","color"=>"green","calories"=>150],["id"=>4,"name"=>"Orange","color"=>"orange","calories"=>300],["id"=>5,"name"=>"Lime","color"=>"green","calories"=>333],["id"=>6,"name"=>"Lemon","color"=>"yellow","calories"=>25],["id"=>7,"name"=>"Peach","color"=>"orange","calories"=>100],["id"=>8,"name"=>"Cherry","color"=>"red","calories"=>200]];
assert_equal($result, $expected, 'Fetch all');

# Fetch All Assoc

$sql = "SELECT * FROM fruits ORDER BY 1";
$result = $db->run($sql)->fetchAllAssoc();
$expected = [["id"=>1,"name"=>"Banana","color"=>"yellow","calories"=>250],["id"=>2,"name"=>"Apple","color"=>"red","calories"=>150],["id"=>3,"name"=>"Pear","color"=>"green","calories"=>150],["id"=>4,"name"=>"Orange","color"=>"orange","calories"=>300],["id"=>5,"name"=>"Lime","color"=>"green","calories"=>333],["id"=>6,"name"=>"Lemon","color"=>"yellow","calories"=>25],["id"=>7,"name"=>"Peach","color"=>"orange","calories"=>100],["id"=>8,"name"=>"Cherry","color"=>"red","calories"=>200]];
assert_equal($result, $expected, 'Fetch all assoc');

# Fetch All Both

$sql = "SELECT * FROM fruits ORDER BY 1";
$result = $db->run($sql)->fetchAllBoth();
$expected = [["id"=>1,"0"=>1,"name"=>"Banana","1"=>"Banana","color"=>"yellow","2"=>"yellow","calories"=>250,"3"=>250],["id"=>2,"0"=>2,"name"=>"Apple","1"=>"Apple","color"=>"red","2"=>"red","calories"=>150,"3"=>150],["id"=>3,"0"=>3,"name"=>"Pear","1"=>"Pear","color"=>"green","2"=>"green","calories"=>150,"3"=>150],["id"=>4,"0"=>4,"name"=>"Orange","1"=>"Orange","color"=>"orange","2"=>"orange","calories"=>300,"3"=>300],["id"=>5,"0"=>5,"name"=>"Lime","1"=>"Lime","color"=>"green","2"=>"green","calories"=>333,"3"=>333],["id"=>6,"0"=>6,"name"=>"Lemon","1"=>"Lemon","color"=>"yellow","2"=>"yellow","calories"=>25,"3"=>25],["id"=>7,"0"=>7,"name"=>"Peach","1"=>"Peach","color"=>"orange","2"=>"orange","calories"=>100,"3"=>100],["id"=>8,"0"=>8,"name"=>"Cherry","1"=>"Cherry","color"=>"red","2"=>"red","calories"=>200,"3"=>200]];
assert_equal($result, $expected, 'Fetch all both');

# Fetch All Column

$sql = "SELECT * FROM fruits ORDER BY 1";
$result = $db->run($sql)->fetchAllColumn();
$expected = [1,2,3,4,5,6,7,8];
assert_equal($result, $expected, 'Fetch all column');

$result = $db->run($sql)->fetchAllColumn(2);
$expected = ["yellow","red","green","orange","green","yellow","orange","red"];
assert_equal($result, $expected, 'Fetch all column with explicit column');

# Fetch All Function

$sql = "SELECT * FROM fruits ORDER BY 1";
$function = function (mixed ...$item) {
    return join('-', $item);
};
$result = $db->run($sql)->fetchAllFunction($function);
$expected = ["1-Banana-yellow-250","2-Apple-red-150","3-Pear-green-150","4-Orange-orange-300","5-Lime-green-333","6-Lemon-yellow-25","7-Peach-orange-100","8-Cherry-red-200"];
assert_equal($result, $expected, 'Fetch all function');

# Fetch All Group

$sql = "SELECT color, id, name FROM fruits ORDER BY 1";
$result = $db->run($sql)->fetchAllGroup();
$expected = ["green"=>[["id"=>3,"name"=>"Pear"],["id"=>5,"name"=>"Lime"]],"orange"=>[["id"=>4,"name"=>"Orange"],["id"=>7,"name"=>"Peach"]],"red"=>[["id"=>2,"name"=>"Apple"],["id"=>8,"name"=>"Cherry"]],"yellow"=>[["id"=>1,"name"=>"Banana"],["id"=>6,"name"=>"Lemon"]]];
assert_equal($result, $expected, 'Fetch all group');

$sql = "SELECT * FROM fruits ORDER BY 1";
$result = $db->run($sql)->fetchAllGroup(\PDO::FETCH_COLUMN);
$expected = ["1"=>["Banana"],"2"=>["Apple"],"3"=>["Pear"],"4"=>["Orange"],"5"=>["Lime"],"6"=>["Lemon"],"7"=>["Peach"],"8"=>["Cherry"]];
assert_equal($result, $expected, 'Fetch all group with additional column style');

# Fetch All Named

$sql = "SELECT f1.*, f2.color FROM fruits f1 INNER JOIN fruits f2 ON f2.id = f1.id + 1 ORDER BY f1.id";
$result = $db->run($sql)->fetchAllNamed();
$expected = [["id"=>1,"name"=>"Banana","color"=>["yellow","red"],"calories"=>250],["id"=>2,"name"=>"Apple","color"=>["red","green"],"calories"=>150],["id"=>3,"name"=>"Pear","color"=>["green","orange"],"calories"=>150],["id"=>4,"name"=>"Orange","color"=>["orange","green"],"calories"=>300],["id"=>5,"name"=>"Lime","color"=>["green","yellow"],"calories"=>333],["id"=>6,"name"=>"Lemon","color"=>["yellow","orange"],"calories"=>25],["id"=>7,"name"=>"Peach","color"=>["orange","red"],"calories"=>100]];
assert_equal($result, $expected, 'Fetch all named');

# Fetch All Numeric

$sql = "SELECT * FROM fruits ORDER BY 1";
$result = $db->run($sql)->fetchAllNumeric();
$expected = [[1,"Banana","yellow",250],[2,"Apple","red",150],[3,"Pear","green",150],[4,"Orange","orange",300],[5,"Lime","green",333],[6,"Lemon","yellow",25],[7,"Peach","orange",100],[8,"Cherry","red",200]];
assert_equal($result, $expected, 'Fetch all numeric');

# Fetch All Object

$sql = "SELECT color, fruits.* FROM fruits ORDER BY 1";
$result = $db->run($sql)->fetchAllObject(Fruit::class);
$result = array_map(fn ($item) => (array)$item, $result);
$expected = [["id"=>3,"name"=>"Pear","color"=>"green","calories"=>150],["id"=>5,"name"=>"Lime","color"=>"green","calories"=>333],["id"=>4,"name"=>"Orange","color"=>"orange","calories"=>300],["id"=>7,"name"=>"Peach","color"=>"orange","calories"=>100],["id"=>2,"name"=>"Apple","color"=>"red","calories"=>150],["id"=>8,"name"=>"Cherry","color"=>"red","calories"=>200],["id"=>1,"name"=>"Banana","color"=>"yellow","calories"=>250],["id"=>6,"name"=>"Lemon","color"=>"yellow","calories"=>25]];
assert_equal($result, $expected, 'Fetch all object');

# Fetch All Pair

$sql = "SELECT name, color FROM fruits ORDER BY 1";
$result = $db->run($sql)->fetchAllPair();
$expected = ["Apple"=>"red","Banana"=>"yellow","Cherry"=>"red","Lemon"=>"yellow","Lime"=>"green","Orange"=>"orange","Peach"=>"orange","Pear"=>"green"];
assert_equal($result, $expected, 'Fetch all pair');

# Fetch All Unique

$sql = "SELECT id, name, color, calories FROM fruits ORDER BY 1";
$result = $db->run($sql)->fetchAllUnique();
$expected = ["1"=>["name"=>"Banana","color"=>"yellow","calories"=>250],"2"=>["name"=>"Apple","color"=>"red","calories"=>150],"3"=>["name"=>"Pear","color"=>"green","calories"=>150],"4"=>["name"=>"Orange","color"=>"orange","calories"=>300],"5"=>["name"=>"Lime","color"=>"green","calories"=>333],"6"=>["name"=>"Lemon","color"=>"yellow","calories"=>25],"7"=>["name"=>"Peach","color"=>"orange","calories"=>100],"8"=>["name"=>"Cherry","color"=>"red","calories"=>200]];
assert_equal($result, $expected, 'Fetch all unique');

// ------------------------------------------------------------------------------------------------------------------------
// Classes
// ------------------------------------------------------------------------------------------------------------------------

class Fruit {
    public int $id; 
    public string $name;
    public string $color;
    public int $calories;
}
