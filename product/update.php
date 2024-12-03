<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
if(!isset($_SESSION['user'])) {
    header('Location:.');
    exit;
}
$user = $_SESSION['user'];

try {
    $connection = new \PDO(
      'mysql:host=localhost;dbname=productdatabase',
      'productuser',
      'productpassword',
      array(
        PDO::ATTR_PERSISTENT => true,
        PDO::MYSQL_ATTR_INIT_COMMAND => 'set names utf8')
    );
} catch(PDOException $e) {
    header('Location: ..');
    exit;
}
if(isset($_POST['id'])) {
    $id = $_POST['id'];
} else {
    $url = '.?op=updatepokemon&result=noid';
    header('Location: ' . $url);
    exit;
}

if(($user === 'even' && $id % 2 != 0) ||
    ($user === 'odd' && $id % 2 == 0)) {
    header('Location: .?op=updatepokemon&result=evenodd');
    exit;
}

if(isset($_POST['name'])) {
    $name = trim($_POST['name']);
} else {
    header('Location: .');
    exit;
}

if(isset($_POST['type'])) {
    $type = $_POST['type'];
} else {
    header('Location: .');
    exit;
}

if(isset($_POST['weight'])) {
    $weight = $_POST['weight'];
} else {
    header('Location: .');
    exit;
}

if(isset($_POST['height'])) {
    $height = $_POST['height'];
} else {
    header('Location: .');
    exit;
}
if(isset($_POST['lvl'])) {
    $lvl = $_POST['lvl'];
} else {
    header('Location: .');
    exit;
}

$ok = true;
if(strlen($name) < 2 || strlen($name) > 100) {
    $ok = false;
}
if(strlen($type)  < 2 || strlen($type) > 100) {
    $ok = false;
}
if(!(is_numeric($weight) && $weight >= 0 && $weight <= 1000000)) {
    $ok = false;
}
if(!(is_numeric($height) && $height >= 0 && $height <= 1000000)) {
    $ok = false;
}
if(!(is_numeric($lvl) && $lvl >= 0 && $lvl <= 1000000)) {
    $ok = false;
}

$resultado = 0;

if($ok) {
    $sql = 'update pokemon set name = :name, type = :type where id = :id, weight = :weight where id = :id, height = :height where id = :id, lvl = :lvl where id = :id';
    $sentence = $connection->prepare($sql);
    $parameters = ['name' => $name, 'type' => $type, 'weight' => $weight, 'height' => $height, 'lvl' => $lvl, 'id' => $id];
    foreach($parameters as $nombreParametro => $valorParametro) {
        $sentence->bindValue($nombreParametro, $valorParametro);
    }
    try {
        $sentence->execute();
        $resultado = $sentence->rowCount();
        $url = '.?op=editpokemon&result=' . $resultado;
    } catch(PDOException $e) {
    }
}

if($resultado == 0) {
    $_SESSION['old']['name'] = $name;
    $_SESSION['old']['type'] = $type;
    $_SESSION['old']['weight'] = $weight;
    $_SESSION['old']['height'] = $height;
    $_SESSION['old']['lvl'] = $lvl;
    $url = 'edit.php?op=editpokemon&result=' . $resultado . '&id=' . $id;
}
header('Location: ' . $url);