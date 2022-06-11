<?php 
session_start();

require_once($_SERVER['DOCUMENT_ROOT']."/lib/db_connect.php");
$pdo = db_connect();

$id = $_SESSION['userid'];
$pw = $_REQUEST["pw"];
$birth = $_REQUEST["birth"];
$sex = $_REQUEST["sex"];
$hp = $_REQUEST["hp"];

$files = $_FILES["upfile"]; //첨부파일
$count = count($files["name"]);
$upload_dir = '../data/'; //물리적 저장위치
var_dump($files);
for ($i=0; $i<$count; $i++){
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

if($_REQUEST['del_file'] && count($_REQUEST['del_file']) > 0) {
    $num_checked = count($_REQUEST['del_file']);
}
$position = $_REQUEST['del_file'];
for($i=0; $i<$num_checked; $i++){ // delete checked item
    $index = $position[$i];
    $del_ok[$index] = "y";
}
for ($i=0; $i<$count; $i++){
    $field_org_name = "file_name_".$i;
    $field_real_name = "file_copied_".$i;
    $org_name_value = $upfile_name[$i];
    $org_real_value = $copied_file_name[$i];
    if ($del_ok[$i] == "y"){
        $delete_field = "file_copied_".$i;
        $delete_name = $row[$delete_field];
        $delete_path = $upload_dir.$delete_name;
        unlink($delete_path);
        try{
            $pdo->beginTransaction();
            $sql = "update drawingband.member set $field_org_name = ?, $field_real_name = ? where
            id=?";
            $stmh = $pdo->prepare($sql);
            $stmh->bindValue(1, $org_name_value, PDO::PARAM_STR);
            $stmh->bindValue(2, $org_real_value, PDO::PARAM_STR);
            $stmh->bindValue(3, $id, PDO::PARAM_STR);
            $stmh->execute();
            $pdo->commit();
        } catch (PDOException $Exception) {
            $pdo->rollBack();
            print "오류: ".$Exception->getMessage();
        }
    } else {
        if (!$upfile_error[$i]){
            try{
                $pdo->beginTransaction();
                $sql = "update drawingband.member set $field_org_name = ?, $field_real_name = ? where
                id=?";
                $stmh = $pdo->prepare($sql);
                $stmh->bindValue(1, $org_name_value, PDO::PARAM_STR);
                $stmh->bindValue(2, $org_real_value, PDO::PARAM_STR);
                $stmh->bindValue(3, $id, PDO::PARAM_STR);
                $stmh->execute();
                $pdo->commit();
            } catch (PDOException $Exception) {
                $pdo->rollBack();
                print "오류: ".$Exception->getMessage();
            }
        }
    }
}
try{
    $pdo->beginTransaction();
    $sql = "update drawingband.member set pw=?,birth=?,sex=?,hp=? where id=?";
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1,$pw,PDO::PARAM_STR);
    $stmh->bindValue(2,$birth,PDO::PARAM_STR);
    $stmh->bindValue(3,$sex,PDO::PARAM_STR);
    $stmh->bindValue(4,$hp,PDO::PARAM_STR);
    $stmh->bindValue(5,$id,PDO::PARAM_STR);
    $stmh->execute();
    $pdo->commit();
    header("Location:../index.php");
}catch(PDOException $Exception){
    $pdo->rollback();
    print "오류 : ".$Exception->getMessage();
}
?>