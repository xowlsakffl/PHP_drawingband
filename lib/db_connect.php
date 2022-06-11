<?php
function db_connect(){
    $db_user = 'drawingband';
    $db_pass = 'dnwnd112!!';
    $db_host = 'localhost';
    $db_name = 'drawingband';
    $dsn = "mysql:host=$db_host;db_name=$db_name;charset=utf8";
    try{
        $pdo = new PDO($dsn,$db_user,$db_pass);
        $pdo -> setAttribute(PDO::ATTR_EMULATE_PREPARES,false);
        $pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }catch (PDOException $Exception) {  
        die('오류:'.$Exception->getMessage());
    }
    return $pdo;
}
?>