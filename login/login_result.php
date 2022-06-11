
<?php
session_start();
?>
<meta charset="UTF-8">
<?php
$id = $_REQUEST['id'];
$pw = $_REQUEST['pw'];

require_once($_SERVER['DOCUMENT_ROOT']."/lib/db_connect.php");
$pdo = db_connect();

try{
    $sql = "select * from drawingband.member where id=?";
    $stmh = $pdo -> prepare($sql);
    $stmh->bindValue(1,$id,PDO::PARAM_STR);
    $stmh->execute();
    $count = $stmh->rowCount();
}catch(PDOException $Exception){
    $pdo->rollBack();
    print "오류 : ".$Exception->getMessage();
}
$row = $stmh->fetch(PDO::FETCH_ASSOC);
if($count<1){
?>
<script>
    alert("아이디 또는 비밀번호가 틀립니다.");
    history.back();
</script>
<?php
}else if($pw != $row["pw"]){
?>
<script>
    alert("아이디 또는 비밀번호가 틀립니다.");
    history.back();
</script>
<?php
}else{
    $_SESSION["userid"] = $row["id"];
    $_SESSION["name"] = $row["name"];
    $_SESSION["level"] = $row["level"];
    header("Location:./login_info.php");
    exit;
}
?>