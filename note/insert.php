<?php
session_start();

require_once($_SERVER['DOCUMENT_ROOT']."/lib/db_connect.php");
$pdo = db_connect();


if(isset($_SESSION['userid'])){
    try{
        $pdo->beginTransaction();
        $sql = "insert into drawingband.send_note(recv_id, send_id, subject, content, recv_chk) values(?, ?, ?, ?, 0)";
        $stmh = $pdo -> prepare($sql);
        $stmh -> bindValue(1,$_REQUEST['recv_name'],PDO::PARAM_STR);
        $stmh -> bindValue(2,$_SESSION['userid'],PDO::PARAM_STR);
        $stmh -> bindValue(3,$_REQUEST['subject'],PDO::PARAM_STR);
        $stmh -> bindValue(4,$_REQUEST['content'],PDO::PARAM_STR);
        $stmh -> execute();
        $pdo->commit();
    } catch (PDOException $Exception) {
        print "오류: ".$Exception->getMessage();
    }
    try{
        $pdo->beginTransaction();
        $sql = "insert into drawingband.recv_note(recv_id, send_id, subject, content) values(?, ?, ?, ?)";
        $stmh = $pdo -> prepare($sql);
        $stmh -> bindValue(1,$_REQUEST['recv_name'],PDO::PARAM_STR);
        $stmh -> bindValue(2,$_SESSION['userid'],PDO::PARAM_STR);
        $stmh -> bindValue(3,$_REQUEST['subject'],PDO::PARAM_STR);
        $stmh -> bindValue(4,$_REQUEST['content'],PDO::PARAM_STR);
        $stmh -> execute();
        $pdo->commit();
    } catch (PDOException $Exception) {
        print "오류: ".$Exception->getMessage();
    }
    header('Location:./note.php');
}else{?>
    <script>
        window.location.href = 'http://drawingband.dothome.co.kr/login/login_form.php';
    </script>
<?php }?>