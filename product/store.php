<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
if(!isset($_SESSION['user'])) {
    header('Location:.');
    exit;
}

try {
    $connection = new \PDO(
      'mysql:host=localhost;dbname=pokemondatabase',
      'pokemonuser',
      'pokemonpassword',
      array(
        PDO::ATTR_PERSISTENT => true,
        PDO::MYSQL_ATTR_INIT_COMMAND => 'set names utf8')
    );
} catch(PDOException $e) {
    header('Location: create.php?op=errorconnection&result=0');
    exit;
}
 
$resultado = 0;
$url = 'create.php?op=insertpokemon&result=' . $resultado;

if(isset($_POST['name']) && isset($_POST['price']) ) {
    $name = $_POST['name'];
    $type = $_POST['type'];
    $weight = $_POST['weight'];
    $height = $_POST['height'];
    $lvl = $_POST['lvl'];
    $ok = true;
    $name = trim($name);

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

    if($ok) {
        $sql = 'insert into pokemon (name, price) values (:name, :price)';
        $sentence = $connection->prepare($sql);
        $parameters = ['name' => $name, 'type' => $type, 'weight' => $weight, 'height' => $height, 'lvl' => $lvl];
        foreach($parameters as $nombreParametro => $valorParametro) {
            $sentence->bindValue($nombreParametro, $valorParametro);
        }

        try {
            $sentence->execute();
            $resultado = $connection->lastInsertId();
            $url = 'index.php?op=insertpokemon&result=' . $resultado;
        } catch(PDOException $e) {
        }
    }
}
if($resultado == 0) {
    $_SESSION['old']['name'] = $name;
    $_SESSION['old']['type'] = $type;
    $_SESSION['old']['weight'] = $weight;
    $_SESSION['old']['height'] = $height;
    $_SESSION['old']['lvl'] = $lvl;
}

header('Location: ' . $url);