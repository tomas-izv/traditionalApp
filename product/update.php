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
    $url = '.?op=updateproduct&result=noid';
    header('Location: ' . $url);
    exit;
}

if(($user === 'even' && $id % 2 != 0) ||
    ($user === 'odd' && $id % 2 == 0)) {
    header('Location: .?op=updateproduct&result=evenodd');
    exit;
}

if(isset($_POST['name'])) {
    $name = trim($_POST['name']);
} else {
    header('Location: .');
    exit;
}

if(isset($_POST['price'])) {
    $price = $_POST['price'];
} else {
    header('Location: .');
    exit;
}

$ok = true;
if(strlen($name) < 2 || strlen($name) > 100) {
    $ok = false;
}
if(!(is_numeric($price) && $price >= 0 && $price <= 1000000)) {
    $ok = false;
}

$resultado = 0;

if($ok) {
    $sql = 'update product set name = :name, price = :price where id = :id';
    $sentence = $connection->prepare($sql);
    $parameters = ['name' => $name, 'price' => $price, 'id' => $id];
    foreach($parameters as $nombreParametro => $valorParametro) {
        $sentence->bindValue($nombreParametro, $valorParametro);
    }
    try {
        $sentence->execute();
        $resultado = $sentence->rowCount();
        $url = '.?op=editproduct&result=' . $resultado;
    } catch(PDOException $e) {
    }
}

if($resultado == 0) {
    $_SESSION['old']['name'] = $name;
    $_SESSION['old']['price'] = $price;
    $url = 'edit.php?op=editproduct&result=' . $resultado . '&id=' . $id;
}
header('Location: ' . $url);