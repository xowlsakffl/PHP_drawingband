<?php include_once($_SERVER['DOCUMENT_ROOT']."/header.php");?>
<link rel="stylesheet" href="<?php $_SERVER["DOCUMENT_ROOT"]?>/note/style.css">
<section id="note_page">
    <p id="note_title">Message</p>
<?php
if(isset($_SESSION['userid'])){

    $num = $_REQUEST['num']; /* bno함수에 idx값을 받아와 넣음*/

    require_once($_SERVER['DOCUMENT_ROOT']."/lib/db_connect.php");
    $pdo = db_connect();

    try{
        $sql = "select * from drawingband.recv_note where num = ?";
        $stmh = $pdo -> prepare($sql);
        $stmh -> bindValue(1,$num,PDO::PARAM_STR);
        $stmh -> execute();
    } catch (PDOException $Exception) {
        print "오류: ".$Exception->getMessage();
    }
    while($row = $stmh -> fetch(PDO::FETCH_ASSOC)){
        $re_da = $row['send_date']; //$re_da변수에 send_date값 넣음
        $rec_chk = 1; // rec_chk에 1을 넣음
        try{
            $pdo->beginTransaction();
            $sql = "update drawingband.send_note set recv_chk = ? where send_date = ?";
            $stmh1 = $pdo -> prepare($sql);
            $stmh1 -> bindValue(1,$rec_chk,PDO::PARAM_STR);
            $stmh1 -> bindValue(2,$re_da,PDO::PARAM_STR);
            $stmh1 -> execute();
            $pdo->commit();
        } catch (PDOException $Exception) {
            print "오류: ".$Exception->getMessage();
        }
        // send_note테이블의 recv_chk를 1로, send_date가 $re_da와 같은 데이터에 업데이트한다.
?>
<ul id="note_menu1" class="clearfix">
        <li>
            <a href="<?php $_SERVER["DOCUMENT_ROOT"]?>/note/note.php">received Message</a>
        </li>
        <li>
            <a href="<?php $_SERVER["DOCUMENT_ROOT"]?>/note/note_send.php">Sent Message</a>
        </li>
</ul>
<div id="note_read">
	<div id="note_info">
		<div id="user_info">
			<ul>
				<li>
                    <span id="send_data">FROM</span>
                    <span id="profile_span">
                    <?php
                        $file_dir = "../data/";
                        try{
                            $sql = "select * from drawingband.member where id=?";
                            $stmh2 = $pdo->prepare($sql);
                            $stmh2->bindValue(1,$row['send_id'],PDO::PARAM_STR);
                            $stmh2->execute();
                        }catch(PDOException $Exception){
                            $pdo->rollback();
                            print "오류 : ".$Exception->getMessage();
                        } 
                        while($row1 = $stmh2->fetch(PDO::FETCH_ASSOC)){
                            $profile_name[0] = $row1["file_name_0"];
                            $profile_copied[0] = $row1["file_copied_0"];
                            if($profile_copied[0]){                      
                                $profile_info = getimagesize($file_dir.$profile_copied[0]);
                                $profile_width = $profile_info[0];
                                $profile_height = $profile_info[1];
                                $profile_type = $profile_info[2];
                                $profile_width = $profile_info[3];
                                $profile_name = $file_dir.$profile_copied[0];
                                if($profile_width < 40){
                                    $profile_width = 40;
                                    // image 타입 1은 gif 2는 jpg 3은 png
                                    if($profile_type==2 || $profile_type==3){
                                        print "<span id='pro_img'><img src='$profile_name' width='$profile_width'></span>";
                                    }
                                }
                            }else{
                                print "<span id='pro_img'><img src='../img/user_icon.png' style='width:100%'></span>"; 
                            }
                        }
                    ?>
                        <span style="margin-left: 5px;"><?=$row['send_id']; ?></span>
                    </span>
                </li>
				<li>
                    <span id="send_data">DATE</span>
                    <span><?=$row['send_date']; ?></span>  
                </li>
                <li>
                    <span id="send_data">TITLE</span>
                    <span><?= nl2br($row['subject']); ?></span>  
                </li>
			</ul>
        </div>
		<div id="bo_content">
			<?= nl2br($row['content']); ?>
		</div>
    </div>
</div>
<div id="note_read_bt">
    <a href="recv_delete.php?num=<?=$row['num']?>" class="del_bt">DELETE</a>
    <a href="reply_write.php?num=<?=$row['num']?>" class="dap_bt">REPLY</a>
</div>
<?php }
}else{?>
    <script>
        window.location.href = 'http://drawingband.dothome.co.kr/login/login_form.php';
    </script>
<?php
}?>
</section>
<?php include_once($_SERVER['DOCUMENT_ROOT']."/footer.php");?>