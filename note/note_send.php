<?php include_once($_SERVER['DOCUMENT_ROOT']."/header.php")?>
<link rel="stylesheet" href="<?php $_SERVER["DOCUMENT_ROOT"]?>/note/style.css">
<section id="note_page">
    <p id="note_title">Message</p>
<?php
if(isset($_SESSION['userid'])){
    require_once($_SERVER['DOCUMENT_ROOT']."/lib/db_connect.php");
    $pdo = db_connect();
    try{
        $sql = "select * from drawingband.send_note where send_id = ? order by num desc";
        $stmh = $pdo -> prepare($sql);
        $stmh -> bindValue(1,$_SESSION['userid'],PDO::PARAM_STR);
        $stmh -> execute();
        $total_row = $stmh->rowCount();//전체 글수
    } catch (PDOException $Exception) {
        print "오류: ".$Exception->getMessage();
    }
?>

<ul id="note_menu" class="clearfix">
		<li><a href="<?php $_SERVER["DOCUMENT_ROOT"]?>/note/note.php">received Message</a></li>
        <li><a href="<?php $_SERVER["DOCUMENT_ROOT"]?>/note/note_send.php" style="font-weight: bold;">Sent Message(<?=$total_row?>)</a></li>
</ul>
<div id="main_in">
	<table class="list-table_send">
    <thead>
      <tr>
        <th id="from">To</th>
        <th id="title">Title</th>
        <th id="date">Date</th>
        <th id="reception">Reception</th>
      </tr>
    </thead>
    <?php
        
        while($row = $stmh -> fetch(PDO::FETCH_ASSOC)){
            $note_title=$row["subject"]; 
            if(strlen($note_title)>30)
            { 
                $note_title=str_replace($row["subject"],mb_substr($row["subject"],0,30,"utf-8")."...",$row["subject"]);
            }
    ?>
        <tr onclick="location.href='send_view.php?num=<?=$row['num']?>'" id="note_tr">
        <td id="profile_td">
                <?php
                    $file_dir = "../data/";
                    try{
                        $sql = "select * from drawingband.member where id=?";
                        $stmh2 = $pdo->prepare($sql);
                        $stmh2->bindValue(1,$row['recv_id'],PDO::PARAM_STR);
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
                            if($profile_width < 70){
                                $profile_width = 70;
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
                <span id="send_id"><?=$row['recv_id']?></span>
            </td> <!---보낸이 -->
            <td id="title_td">
                    <?=$note_title?>
            </td> <!---제목 -->
            <td id="date_td"><?=substr($row['send_date'],0,16) ?></td> 
            <td id="reception_td">
                <?php 
                if($row['recv_chk'] == "0")
                {
                    echo "<img src='../img/reception_1.png'>";
                }else{ 
                    echo "<img src='../img/reception_2.png'>";
                }
                ?>
            </td>
        </tr>
    <?php }?>
    </table>
    <a href="write.php" id="note_btn">New message</a>
</div>
<?php 
}else{?>
    <script>
        window.location.href = 'http://drawingband.dothome.co.kr/login/login_form.php';
    </script>
<?php }?>
</section>
<?php include_once($_SERVER['DOCUMENT_ROOT']."/footer.php")?>