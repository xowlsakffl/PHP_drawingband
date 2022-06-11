<?php
session_start();

require_once($_SERVER['DOCUMENT_ROOT']."/lib/db_connect.php");
$pdo = db_connect();

$num = $_REQUEST['num'];

try{
    $pdo->beginTransaction();
    $sql = "delete from drawingband.send_note where num= ?";
    $stmh = $pdo -> prepare($sql);
    $stmh -> bindValue(1,$num,PDO::PARAM_STR);
    $stmh -> execute();
    $pdo->commit();
    header('Location:./note_send.php');
} catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}
?>