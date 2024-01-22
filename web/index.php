<?php

require dirname(__DIR__) . '/vendor/autoload.php';

use tebe\xpdo\PDO;

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
echo json_encode($db->run($sql)->fetchAll());
