<?php
session_start();

$file_dir = '../data/';
$num=$_REQUEST["num"];

require_once($_SERVER['DOCUMENT_ROOT']."/lib/db_connect.php");
$pdo = db_connect();

try{
    $sql = "select * from drawingband.gallery where num=?";
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $num, PDO::PARAM_STR);
    $stmh->execute();
    $row = $stmh->fetch(PDO::FETCH_ASSOC);
    $item_num = $row["num"];
    $item_id = $row["id"];
    $item_name = $row["name"];
    $item_hit = $row["hit"];
    $item_cate = $row['cate'];

    $image_name[0] = $row["file_name_0"];

    $image_copied[0] = $row["file_copied_0"];
    
    $item_date = $row["regist_day"];
    $item_date = substr($item_date,0,10);
    $item_subject = str_replace(" ", "&nbsp;", $row["subject"]);
    $item_content = $row["content"];
    $is_html = $row["is_html"];
    if ($is_html!="y"){
        $item_content = str_replace(" ", "&nbsp;", $item_content);
        $item_content = str_replace("\n", "<br>", $item_content);
    }
    $new_hit = $item_hit + 1;
    try{
        $pdo->beginTransaction();
        $sql = "update drawingband.gallery set hit=? where num=?"; // 조회수 증가
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $new_hit, PDO::PARAM_STR);
        $stmh->bindValue(2, $num, PDO::PARAM_STR);
        $stmh->execute();
        $pdo->commit();
    } catch (PDOException $Exception) {
        $pdo->rollBack();
        print "오류: ".$Exception->getMessage();
    }
?>
<section id="layerpop" class="modal fade clearfix">
    <button type="button" class="close" data-dismiss="modal"><i class="fas fa-times"></i></button>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                
                <div id="view_profile_info" class="clearfix">
                <?php
                    try{
                        $sql = "select * from drawingband.member where id=?";
                        $stmh2 = $pdo->prepare($sql);
                        $stmh2->bindValue(1,$item_id,PDO::PARAM_STR);
                        $stmh2->execute();
                    }catch(PDOException $Exception){
                        $pdo->rollback();
                        print "오류 : ".$Exception->getMessage();
                    } 
                    while($row = $stmh2->fetch(PDO::FETCH_ASSOC)){
                        $profile_name[0] = $row["file_name_0"];
                        $profile_copied[0] = $row["file_copied_0"];
                        if($profile_copied[0]){                      
                            $profile_info = getimagesize($file_dir.$profile_copied[0]);
                            $profile_width = $profile_info[0];
                            $profile_height = $profile_info[1];
                            $profile_type = $profile_info[2];
                            $profile_width = $profile_info[3];
                            $profile_name = $file_dir.$profile_copied[0];
                            if($profile_width < 50){
                                $profile_width = 50;
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
                <span id="view_subject"><?=$item_subject?></span>
                <span id="writer">by <?=$item_name?></span>
                <span id="write_date"><i class="far fa-clock"></i><?= $item_date ?></span>
                </div>
                <div id="view_button">
                <?php
                    if(isset($_SESSION["userid"])) {
                        if($_SESSION["userid"]==$item_id || $_SESSION["userid"]=="admin" || $_SESSION["level"]==1){
                    ?>
                            <a href="../write.php?mode=modify&num=<?=$num?>" id="update_btn1"><img src="<?php $_SERVER['DOCUMENT_ROOT']?>/img/modify_icon.png" alt=""></a>
                            <script>
                            function del(href){
                                if(confirm("한번 삭제한 자료는 복구할 방법이 없습니다.\n\n정말 삭제하시겠습니까?")){
                                    document.location.href = href;
                                }
                            }
                            </script>
                            <a href="javascript:del('<?php $_SERVER['DOCUMENT_ROOT']?>/delete.php?num=<?=$num?>')" id="delete_btn1"><img src="<?php $_SERVER['DOCUMENT_ROOT']?>/img/delete_icon.png" alt=""></a>
                        <?php
                        }
                        ?>
                    <?php
                    }
                    ?>
                </div>
            </div><!--modal header-->
            <div class="modal-body">
                <?php
                    if ($image_copied[0]){
                        $imageinfo = getimagesize($file_dir.$image_copied[0]);//인자 이미지파일명과 파일의 위치(경로) 크기와 형식을 배열형태로 반환
                        $image_width[$i] = $imageinfo[0];
                        $image_height[$i] = $imageinfo[1];
                        $image_type[$i] = $imageinfo[2];
                        $img_name = $image_copied[0];
                        $img_name = "../data/".$img_name;
                        if ($image_width[$i] >  740){
                            $image_width[$i] =  740;
                            // image 타입 1은 gif 2는 jpg 3은 png
                            if($image_type[$i]==1 || $image_type[$i]==2 || $image_type[$i]==3){
                                print "<div id='view_imgbox'><img src='$img_name' style='width:100%'></div>";
                            }
                        }else{
                            if($image_type[$i]==1 || $image_type[$i]==2 || $image_type[$i]==3){
                                print "<div id='view_imgbox'><img src='$img_name' width='$image_width[$i]'></div>";
                            }
                        }
                    }
                ?>
                <div id="view_content"><?= $item_content ?></div>
            </div><!--modal body-->
            <div class="modal-footer">
                <div id="ripple">
                    <p id="ripple_title">COMMENT</p>
                    <form name="ripple_form" method="post" action="insert_ripple.php?num=<?=$item_num?>&cate=<?=$item_cate?>" id="ripple_form">
                        <div id="ripple_text" class="clearfix">
                            <div id="ripple_text1">
                                <textarea name="ripple_content" required wrap="physical" placeholder="Share your throughts"></textarea>
                            </div>
                            <div id="ripple_text2">
                                <input type="submit" value="WRITE">
                            </div>
                        </div>
                    </form>
                    <?php
                    try{
                        $sql = "select * from drawingband.gallery_ripple where parent='$item_num'";
                        $stmh1 = $pdo->query($sql); // ripple PDOStatement 변수명을 다르게
                    } catch (PDOException $Exception) {
                        print "오류: ".$Exception->getMessage();
                    }
                    while ($row_ripple = $stmh1->fetch(PDO::FETCH_ASSOC)) {
                        $ripple_num = $row_ripple["num"];
                        $ripple_id = $row_ripple["id"];
                        $ripple_content = str_replace("\n", "<br>", $row_ripple["content"]);
                        $ripple_content = str_replace(" ", "&nbsp;", $ripple_content);
                        $ripple_date = $row_ripple["regist_day"];
                        $ripple_date = substr($ripple_date, 0, 16);
                    ?>
                    <div id="ripple_box">
                        <div id="profile_img_box">
                            <?php
                                try{
                                    $sql = "select * from drawingband.member where id=?";
                                    $stmh3 = $pdo->prepare($sql);
                                    $stmh3->bindValue(1,$ripple_id,PDO::PARAM_STR);
                                    $stmh3->execute();
                                }catch(PDOException $Exception){
                                    $pdo->rollback();
                                    print "오류 : ".$Exception->getMessage();
                                } 
                                while($row = $stmh3->fetch(PDO::FETCH_ASSOC)){
                                    $profile_name[0] = $row["file_name_0"];
                                    $profile_copied[0] = $row["file_copied_0"];
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
                                        $profile_info = getimagesize("../img/user_icon.png");//인자 이미지파일명과 파일의 위치(경로) 크기와 형식을 배열형태로 반환
                                        $profile_width = $profile_info[0];
                                        $profile_height = $profile_info[1];
                                        $profile_type = $profile_info[2];
                                        $profile_img_name = "user_icon.png";
                                        $profile_img_name = "../data/".$profile_img_name;
                                        if($profile_width > 40){
                                            $profile_width = 40;
                                            if($profile_type==2 || $profile_type==3){
                                                print "<span id='pro_img'><img src='../img/user_icon.png' width='$profile_width'></span>";
                                            }
                                        }
                                    }
                                }
                                ?>
                                
                        </div>              
                        <div id="ripple_name_box">                
                            <?php
                            if(isset($_SESSION["userid"])){
                                if($_SESSION["userid"]=="admin" || $_SESSION["userid"]==$ripple_id)
                                print "<a href='../delete_ripple.php?num=$item_num&ripple_num=$ripple_num' id='ripple_delete'><i class='far fa-times-circle'></i></a>";
                            }
                            ?>
                            <p id="writer"><?= $item_name ?></p>
                            <p id="ripple_content"><?=$ripple_content?></p>
                            <p id="ripple_date"><i class="far fa-clock"></i><?=$ripple_date?></p>
                        </div>
                    </div>
                    <?php
                    } // while문의 끝
                    ?>
                    
                </div><!-- end of ripple-->
<?php
}catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}
?>

            </div>
        </div>
    </div>
</section>