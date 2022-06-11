<?php include_once($_SERVER['DOCUMENT_ROOT']."/header.php")?>
<div id="modal_content"></div>
<div id="main_banner"></div>
<div id="link_bar">
    <p id="topic">Select <span>Topic</span></p>
    <ul class="clearfix">
        <li>
            <a href="<?php $_SERVER['DOCUMENT_ROOT']?>/list/list.php?mode=3D">
                <img src="<?php $_SERVER['DOCUMENT_ROOT']?>/img/menu/3D.jpg" alt="">
                <span>3D</span>
            </a>
        </li>  
        <li>
            <a href="<?php $_SERVER['DOCUMENT_ROOT']?>/list/list.php?mode=Animation">
                <img src="<?php $_SERVER['DOCUMENT_ROOT']?>/img/menu/Animation.jpg" alt="">
                <span>Animation</span>
            </a>
        </li>
        <li>
            <a href="<?php $_SERVER['DOCUMENT_ROOT']?>/list/list.php?mode=Cartoon">
                <img src="<?php $_SERVER['DOCUMENT_ROOT']?>/img/menu/Cartoon.jpg" alt="">
                <span>Cartoon</span>
            </a>
        </li>
        <li>
            <a href="<?php $_SERVER['DOCUMENT_ROOT']?>/list/list.php?mode=Digital">
                <img src="<?php $_SERVER['DOCUMENT_ROOT']?>/img/menu/Digital.jpg" alt="">
                <span>Digital Art</span>
            </a>
        </li>
        <li>
            <a href="<?php $_SERVER['DOCUMENT_ROOT']?>/list/list.php?mode=Drawing">
                <img src="<?php $_SERVER['DOCUMENT_ROOT']?>/img/menu/Drawing.jpg" alt="">
                <span>Drawing Art</span>
            </a>
        </li>
        <li>
            <a href="<?php $_SERVER['DOCUMENT_ROOT']?>/list/list.php?mode=Painting">
                <img src="<?php $_SERVER['DOCUMENT_ROOT']?>/img/menu/Painting.jpg" alt="">
                <span>Painting Art</span>
            </a>
        </li> 
        <li>
            <a href="<?php $_SERVER['DOCUMENT_ROOT']?>/list/list.php?mode=Game">
                <img src="<?php $_SERVER['DOCUMENT_ROOT']?>/img/menu/Game.jpg" alt="">
                <span>Game Art</span>
            </a>
        </li>
        <li>
            <a href="<?php $_SERVER['DOCUMENT_ROOT']?>/list/list.php?mode=Traditional">
                <img src="<?php $_SERVER['DOCUMENT_ROOT']?>/img/menu/Traditional.jpg" alt="">
                <span>Traditional Art</span>
            </a>
        </li>
        <li>
            <a href="<?php $_SERVER['DOCUMENT_ROOT']?>/list/list.php?mode=Illustration">
                <img src="<?php $_SERVER['DOCUMENT_ROOT']?>/img/menu/Illustration.jpg" alt="">
                <span>Illustration</span>
            </a>
        </li>
    </ul>
</div>
<div id="main">
<?php 
require_once($_SERVER['DOCUMENT_ROOT']."/lib/db_connect.php");
$pdo = db_connect();

if(isset($_REQUEST["mode"])){
    $mode=$_REQUEST["mode"];
}else{
    $mode="";
}

$scale = 16;//게시글 수
 
try{
    $sql="select * from drawingband.gallery order by num desc limit $scale";
    $stmh = $pdo->query($sql);
    $total_row = $stmh->rowCount(); //전체 글수
?>
    <section class="clearfix" id="section1">
        <p id="topic">Explore <span>ALL</span></p>
        <span id="list_content" class="clearfix">
            <?php
            $i=$total_row; // 글 삭제해도 연속 번호 보이는 목록번호 출력용             
            $file_dir = './data/';
            while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
                $item_num=$row["num"];
                $item_id=$row["id"];
                $item_name=$row["name"];
                $item_hit=$row["hit"];
                $item_date=$row["regist_day"];
                $image_name[0] = $row["file_name_0"];
                $image_copied[0] = $row["file_copied_0"];
                $item_date=substr($item_date, 0, 10);
                $item_subject=str_replace(" ", "&nbsp;", $row["subject"]);

                $sql="select * from drawingband.gallery_ripple where parent=$item_num";
                $stmh1 = $pdo->query($sql);
                $num_ripple=$stmh1->rowCount();
                                
            ?>
            <a href="view/view.php?num=<?=$item_num?>" id="gallery_list_box" class="imglist" data-target="#layerpop" data-toggle="modal">
                <div id="smog"></div>
                <div id="list_item0">
                    <?php  
                        if ($image_copied[0]){
                            $imageinfo = getimagesize($file_dir.$image_copied[0]);//인자 이미지파일명과 파일의 위치(경로) 크기와 형식을 배열형태로 반환
                            $image_width = $imageinfo[0];
                            $image_height = $imageinfo[1];
                            $image_type = $imageinfo[2];
                            $img_name = $image_copied[0];
                            $img_name = "./data/".$img_name;
                            if ($image_width > 440){
                                $image_width = 440;
                                // image 타입 1은 gif 2는 jpg 3은 png
                                if($image_type==1 || $image_type==2 || $image_type==3){
                                    if($image_type==3){
                                        print "<p id='image_back' style='margin:0 auto'><img src='$img_name' width='$image_width' id='list_itemimg'></p>";
                                    }else{
                                        print "<img src='$img_name'  width='$image_width' id='list_itemimg'>";
                                    } 
                                }
                            }else{
                                if($image_type==1 || $image_type==2 || $image_type==3){
                                    if($image_type==3){
                                        print "<p id='image_back' style='margin:0 auto'><img src='$img_name' id='list_smallimg'></p>";
                                    }else{
                                        print "<p style='margin:0 auto'><img src='$img_name' id='list_smallimg'></p>";
                                    } 
                                }
                            }
                            
                        }
                    ?>
                    <span id="textbox1">
                    <div id="list_item1">
                        <p><?=substr($item_subject,0,35)?></p>
                    </div>
                    <div id="list_item3">
                        <?php
                            $file_dir = "./data/";
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
                                $image_name[0] = $row["file_name_0"];
                                $image_copied[0] = $row["file_copied_0"];
                                if($image_copied[0]){                      
                                    $image_info = getimagesize($file_dir.$image_copied[0]);
                                    $image_width = $image_info[0];
                                    $image_height = $image_info[1];
                                    $image_type = $image_info[2];
                                    $image_width = $image_info[3];
                                    $image_name = $file_dir.$image_copied[0];
                                    if($image_width < 40){
                                        $image_width = 40;
                                        // image 타입 1은 gif 2는 jpg 3은 png
                                        if($image_type==2 || $image_type==3){
                                            print "<span id='pro_img'><img src='$image_name' width='$image_width'></span>";
                                        }
                                    }
                                }else{
                                    print "<span id='pro_img'><img src='./img/user_icon1.png' style='width:100%'></span>";
                                }
                            }
                        ?>
                        <span id="writer"><?= $item_name ?></span>
                    </div>
                    <div id="list_item4">
                        <span id="like_num">
                            <img src="<?php $_SERVER['DOCUMENT_ROOT']?>/img/like_icon.png" alt="" id='like_icon'>
                            <b>0</b>
                        </span>                   
                        <span id="view_num">
                            <img src="<?php $_SERVER['DOCUMENT_ROOT']?>/img/view_icon.png" alt="" id='view_icon'>
                            <b><?= $item_hit ?></b>
                        </span>
                        <span id='ripple_count'>
                            <img src='<?php $_SERVER['DOCUMENT_ROOT']?>/img/ripple_icon.png'>
                            <b><?=$num_ripple?></b>
                        </span>
                    </div>
                    </span>
                </div>
            </a>
        
    <?php
        $i--;
    }           
    } catch (PDOException $Exception) {
        print "오류: ".$Exception->getMessage();
    }
    ?>
    </span>
    <input type="hidden" id="pageno" value="1">
    <div id="loader">
        <img src="<?php $_SERVER['DOCUMENT_ROOT']?>/img/loader.gif">
    </div>
    </section>
</div><!--main end-->
<script>
$(function(){
    $(window).scroll(function(){
        if($(window).scrollTop() + $(window).height() >= $(document).height()) {
            var nextPage = parseInt($('#pageno').val())+1;
            $.ajax({
                type: 'POST',
                url: './loadmore/loadmore.php',
                data: {
                    pageno: nextPage
                },
                success: function(data){
                    if(data != ''){							 
                        $('#list_content').append(data);
                        $('#pageno').val(nextPage);
                        $('#loader').show();
                    } else {								 
                        $("#loader").hide();
                    }
                }
            });
        }
    })
})
</script>
<?php include_once($_SERVER['DOCUMENT_ROOT']."/footer.php")?>
