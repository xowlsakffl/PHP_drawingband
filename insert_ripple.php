<?php 
session_start(); 
?>
<meta charset="utf-8">
<?php
if(!isset($_SESSION["userid"])) {
?>
<script>
alert('로그인 후 이용해 주세요.');
history.back();
</script>
<?php
}
$num=$_REQUEST["num"];
$ripple_content=$_REQUEST["ripple_content"];
$category=$_REQUEST['cate'];

require_once($_SERVER['DOCUMENT_ROOT']."/lib/db_connect.php");
$pdo = db_connect();

try{
$pdo->beginTransaction();
$sql = "insert into drawingband.gallery_ripple(parent, id, name, content, cate, regist_day)";
$sql.= "values(?, ?, ?, ?, ?,now())";
$stmh = $pdo->prepare($sql);
$stmh->bindValue(1, $num, PDO::PARAM_STR);
$stmh->bindValue(2, $_SESSION["userid"], PDO::PARAM_STR);
$stmh->bindValue(3, $_SESSION["name"], PDO::PARAM_STR);
$stmh->bindValue(4, $ripple_content, PDO::PARAM_STR);
$stmh->bindValue(5, $category, PDO::PARAM_STR);
$stmh->execute();
$pdo->commit();
$prevPage = $_SERVER['HTTP_REFERER'];
header("location:$prevPage");
exit;
} catch (PDOException $Exception) {
$pdo->rollBack();
print "오류: ".$Exception->getMessage();
}
?>