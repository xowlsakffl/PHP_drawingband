<?php 
session_start();
?>
<meta charset="UTF-8">
<?php
require_once($_SERVER['DOCUMENT_ROOT']."/lib/db_connect.php");
$pdo = db_connect();

$id = $_REQUEST["id"];

if(isset($id)){
    try{
        $sql = "select * from drawingband.member where id =?";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1,$id,PDO::PARAM_STR);
        $stmh->execute();
        $count = $stmh ->rowCount();
    }catch(PDOException $Exception){
        $pdo->rollback();
        print "오류 : ".$Exception->getMessage();
    }
    if($count > 0){
        echo "
        <script>
            $(function(){
                $('#id1').addClass('active');
            })
        </script>
        <span id='check_alert'>이미 존재하는 아이디입니다.</span>
        ";
    }else{
        echo "
        <script>
            $(function(){
                $('#id1').removeClass('active');
            })
        </script>
        <span>사용 가능한 아이디입니다.</span>
        ";
    }
}
?>