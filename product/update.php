<?php
session_start();
if(!isset($_SESSION['user'])) {
    header('.');
    exit;
}
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
    echo 'no connection';
    exit;
}
if(isset($_POST['name'])) {
    $name = $_POST['name'];
} else {
    echo 'no name';
    exit;
}
if(isset($_POST['price'])) {
    $price = $_POST['price'];
} else {
    echo 'no price';
    exit;
}
if(isset($_POST['id'])) {
    $id = $_POST['id'];
} else {
    echo 'no id';
    exit;
}
$sql = 'update product set name = :name, price = :price where id = :id';
$sentence = $connection->prepare($sql);
$parameters = ['name' => $name, 'price' => $price, 'id' => $id];
foreach($parameters as $nombreParametro => $valorParametro) {
    $sentence->bindValue($nombreParametro, $valorParametro);
}
if(!$sentence->execute()){
    echo 'no sql';
    exit;
}
$resultado = $sentence->rowCount();
$url = '.?op=editproduct&result=' . $resultado;
header('Location: ' . $url);