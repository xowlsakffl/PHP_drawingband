<?php 
session_start();
?>
<meta charset="UTF-8">
<?php
require_once($_SERVER['DOCUMENT_ROOT']."/lib/db_connect.php");
$pdo = db_connect();

$id = $_REQUEST["id"];
$pw = $_REQUEST["pw"];
$name = $_REQUEST["name"];
$birth = $_REQUEST["birth"];
$sex = $_REQUEST["sex"];
$hp = $_REQUEST["hp"];

$files = $_FILES["upfile"]; //첨부파일
$upload_dir = '../data/';

if($files && count($files["name"]) > 0){
    for ($i=0; $i<count($files["name"]); $i++){
        $upfile_name[$i] = $files["name"][$i];
        $upfile_tmp_name[$i] = $files["tmp_name"][$i];
        $upfile_type[$i] = $files["type"][$i];
        $upfile_size[$i] = $files["size"][$i];
        $upfile_error[$i] = $files["error"][$i];
        $file = explode(".", $upfile_name[$i]);//explode는 결과를 배열로 반환
        $file_name = $file[0];
        $file_ext = $file[1];
        if (!$upfile_error[$i]){
            $new_file_name = date("Y_m_d_H_i_s");
            $new_file_name = $new_file_name."_".$i;
            $copied_file_name[$i] = $new_file_name.".".$file_ext;
            $uploaded_file[$i] = $upload_dir.$copied_file_name[$i];
            if( $upfile_size[$i] >  10000000) {
            print("
            <script>
            alert('업로드 파일 크기가 지정된 용량(10MB)을 초과합니다!<br>파일 크기를 체크해주세요! ');
            history.back();
            </script>
            ");
            exit;
            }
            if (($upfile_type[$i] != "image/jpeg") && ($upfile_type[$i] != "image/png")){
                print(" <script>
                alert('JPG, PNG 이미지 파일만 업로드 가능합니다!');
                history.back();
                </script>");
                exit;
            }
            if (!move_uploaded_file($upfile_tmp_name[$i], $uploaded_file[$i]) ){
                print("<script>
                alert('파일을 지정한 디렉 토리에 복사하는데 실패했습니다.');
                history.back();
                </script>");
                exit;
            }
        }
    }
}
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
        echo "<script>
            alert('이미 존재하는 아이디입니다.')
            history.back();
        </script>";
    }else{
        try{
            $pdo->beginTransaction();
            $sql = "insert into drawingband.member VALUES(?,?,?,?,?,?,now(),9,?,?)";
            $stmh = $pdo->prepare($sql);
            $stmh->bindValue(1,$id,PDO::PARAM_STR);
            $stmh->bindValue(2,$pw,PDO::PARAM_STR);
            $stmh->bindValue(3,$name,PDO::PARAM_STR);
            $stmh->bindValue(4,$birth,PDO::PARAM_STR);
            $stmh->bindValue(5,$sex,PDO::PARAM_STR);
            $stmh->bindValue(6,$hp,PDO::PARAM_STR);
            $stmh->bindValue(7,$upfile_name[0],PDO::PARAM_STR);
            $stmh->bindValue(8,$copied_file_name[0],PDO::PARAM_STR);
            $stmh->execute();
            $pdo->commit();
            header("Location:../index.php");
        }catch(PDOException $Exception){
            $pdo->rollback();
            print "오류 : ".$Exception->getMessage();
        }
    }
}

?>