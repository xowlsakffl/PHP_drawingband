<?php 
include $_SERVER["DOCUMENT_ROOT"]."/header.php";

$category = ['3D','Animation','Cartoon','Digital','Painting','Drawing','Game','Traditional','Illustration'];

if(isset($_REQUEST['mode'])){
    $mode = $_REQUEST['mode'];
    switch ($mode) {
        case '3D':
            $mode = '3D';
            break;
        case 'Animation':
            $mode = 'Animation';
            break;
        case 'Cartoon':
            $mode = 'Cartoon';
            break;
        case 'Digital':
            $mode = 'Digital';
            break;
        case 'Painting':
            $mode = 'Painting';
            break;
        case 'Drawing':
            $mode = 'Drawing';
            break;
        case 'Game':
            $mode = 'Game';
            break;
        case 'Traditional':
            $mode = 'Traditional';
            break;
        case 'Illustration':
            $mode = 'Illustration';
            break;
        default:
            header('location:./index.php');
            break;
    }
}else{
    $mode = "";
}

$scale = 16;//게시글 수
try{
    require_once($_SERVER['DOCUMENT_ROOT']."/lib/db_connect.php");
    $pdo = db_connect();
    $sql = "select * from drawingband.gallery where cate = ? order by num desc limit $scale";
    $stmh = $pdo -> prepare($sql);
    $stmh -> bindValue(1,$mode,PDO::PARAM_STR);
    $stmh -> execute();
    $total_row = $stmh->rowCount(); //전체 글수
}catch(PDOException $Exception) {
    $pdo->rollBack();
    print "오류: ".$Exception->getMessage();
}
?>
<div id="modal_content"></div>
<div id="main">
<section class="list_sec clearfix" id="section1">
        <p id="topic" style="margin-top: 93px;">Explore <span><?=strtoupper($mode)?></span></p>
        <span id="list_content" class="clearfix">
            <?php
            $i=$total_row; // 글 삭제해도 연속 번호 보이는 목록번호 출력용             
            $file_dir = '../data/';
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
            <a href="../view/view.php?num=<?=$item_num?>" id="gallery_list_box" class="imglist" data-target="#layerpop" data-toggle="modal">
                <div id="smog"></div>
                <div id="list_item0">
                    <?php  
                        if ($image_copied[0]){
                            $imageinfo = getimagesize($file_dir.$image_copied[0]);//인자 이미지파일명과 파일의 위치(경로) 크기와 형식을 배열형태로 반환
                            $image_width = $imageinfo[0];
                            $image_height = $imageinfo[1];
                            $image_type = $imageinfo[2];
                            $img_name = $image_copied[0];
                            $img_name = "../data/".$img_name;
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
                            $file_dir = "../data/";
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
                                    print "<span id='pro_img'><img src='../img/user_icon1.png' style='width:100%'></span>";
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
    ?>
    </span>
    <input type="hidden" id="pageno" value="1">
    <input type="hidden" id="mode" value="<?=$mode?>">
</section>
</div>
<script>
$(function(){
    $(window).scroll(function(){
        if($(window).scrollTop() + $(window).height() >= $(document).height()) {
            var nextPage = parseInt($('#pageno').val())+1;
            $.ajax({
                type: 'POST',
                url: '../loadmore/loadmore_list.php',
                data: {
                    pageno: nextPage,
                    mode : $('#mode').val()
                },
                success: function(data){
                    if(data != ''){							 
                        $('#list_content').append(data);
                        $('#pageno').val(nextPage);
                    }
                }
            });
        }
    })
})
</script>
<?php include $_SERVER["DOCUMENT_ROOT"]."/footer.php";?>