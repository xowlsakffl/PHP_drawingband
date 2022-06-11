<?php
$num=$_REQUEST["num"];
$ripple_num=$_REQUEST["ripple_num"];

require_once($_SERVER['DOCUMENT_ROOT']."/lib/db_connect.php");
$pdo = db_connect();

try{
$pdo->beginTransaction();
$sql = "delete from drawingband.gallery_ripple where num = ?"; //db만 수정
$stmh = $pdo->prepare($sql);
$stmh->bindValue(1,$ripple_num,PDO::PARAM_STR);
$stmh->execute();
$pdo->commit();
$prevPage = $_SERVER['HTTP_REFERER'];
header("location:$prevPage");
} catch (Exception $ex) {
$pdo->rollBack();
print "오류: ".$Exception->getMessage();
}
?>