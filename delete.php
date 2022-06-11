<?php
session_start();

$num=$_REQUEST["num"];

require_once($_SERVER['DOCUMENT_ROOT']."/lib/db_connect.php");
$pdo = db_connect();

$upload_dir = '../data/'; //물리적 저장위치

try{
    $sql = "select * from drawingband.gallery where num = ? ";
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1,$num,PDO::PARAM_STR);
    $stmh->execute();
    $count = $stmh->rowCount();
    $row = $stmh->fetch(PDO::FETCH_ASSOC);
    $copied_name[0] = $row['file_copied_0'];
    if ($copied_name[0]){
        $image_name = $upload_dir.$copied_name[$i];
        unlink($image_name);
    }
}catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}
try{
    $pdo->beginTransaction();
    $sql = "delete from drawingband.gallery where num = ?";
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1,$num,PDO::PARAM_STR);
    $stmh->execute();
    $pdo->commit();
    $prevPage = $_SERVER['HTTP_REFERER'];
    header('location:'.$prevPage);
} catch (Exception $ex) {
    $pdo->rollBack();
    print "오류: ".$Exception->getMessage();
}