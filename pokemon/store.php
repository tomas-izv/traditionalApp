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
      'pokemon_user',
      'pokemon_user',
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

if(isset($_POST['name']) && isset($_POST['lvl']) && isset($_POST['type'])) {
    $name = $_POST['name'];
    $lvl = $_POST['lvl'];
    $type = $_POST['type'];
    $ok = true;
    $name = trim($name);
    $type = trim($type);

    if(strlen($name) < 2 || strlen($name) > 100) {
        $ok = false;
    }
    if(!(is_numeric($lvl) && $lvl >= 0 && $lvl <= 1000000)) {
        $ok = false;
    }
    if(strlen($type) < 2 || strlen($type) > 100) {
        $ok = false;
    }

    if($ok) {
        $sql = 'insert into pokemon (name, lvl, type) values (:name, :lvl, :type)';
        $sentence = $connection->prepare($sql);
        $parameters = ['name' => $name, 'lvl' => $lvl, 'type'=> $type];
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
    $_SESSION['old']['lvl'] = $lvl;
    $_SESSION['old']['type'] = $type;
}

header('Location: ' . $url);