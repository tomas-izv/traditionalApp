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
    header('Location: create.php?op=errorconnection');
    exit;
}
if(isset($_POST['name'])) {
    $name = $_POST['name'];
} else {
    header('Location: create.php?op=errorname');
    exit;
}
if(isset($_POST['price'])) {
    $price = $_POST['price'];
} else {
    header('Location: create.php?op=errorprice');
    exit;
}
$name = trim($name);
$ok = true;
if(strlen($name) < 2 || strlen($name) > 100) {
    $ok = false;
}
if(!(is_numeric($price) && $price >= 0 && $price <= 1000000)) {
    $ok = false;
}
if($ok === false) {
    $_SESSION['old']['name'] = $name;
    $_SESSION['old']['price'] = $price;
    header('Location: create.php?op=errordata');
    exit;
}
$sql = 'insert into product (name, price) values (:name, :price)';
$sentence = $connection->prepare($sql);
$parameters = ['name' => $name, 'price' => $price];
foreach($parameters as $nombreParametro => $valorParametro) {
    $sentence->bindValue($nombreParametro, $valorParametro);
}
if(!$sentence->execute()){
    echo 'no sql';
    exit;
}
$resultado = $connection->lastInsertId();
$url = '.?op=insertproduct&result=' . $resultado;
header('Location: ' . $url);