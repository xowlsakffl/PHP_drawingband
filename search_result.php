<?php 
include $_SERVER["DOCUMENT_ROOT"]."/header.php";

require_once($_SERVER['DOCUMENT_ROOT']."/lib/db_connect.php");
$pdo = db_connect();

if(isset($_REQUEST["mode"])){
    $mode=$_REQUEST["mode"];
}else{
    $mode="";
}   
            
if(isset($_REQUEST["str"])){ // str 쿼리스트링 값 할당 체크
    $str=$_REQUEST["str"];
}else{
    $str="";
}   

    if($mode=="search"){
        if(!$str){
?>
            <script>
            alert('검색할 단어를 입력해 주세요.');
            document.location.href="/";
            </script>
<?php
        }
    $sql="select * from drawingband.gallery where subject like '%$str%' order by num desc";
    }else{
    ?>
    <script>
    history.back();
    </script>
    <?php
    }
    try{
        $stmh = $pdo->query($sql);
        $total_row = $stmh->rowCount();//전체 글수
    } catch (PDOException $Exception) {
        print "오류: ".$Exception->getMessage();
    }
?>
<div id="modal_content"></div>
<div id="main">
<section class="clearfix" id="section1">
        <p id="search_result"><span><?=$str?></span> result</p>
        <p id="total_row"><span><?=$total_row?></span> rows were found</p>
        <?php if($total_row > 0){?>
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
            <a href="./view/view.php?num=<?=$item_num?>" id="gallery_list_box" class="imglist" data-target="#layerpop" data-toggle="modal">
                <div id="list_item0">
                    <?php  
                        if ($image_copied[0]){
                            $imageinfo = getimagesize($file_dir.$image_copied[0]);//인자 이미지파일명과 파일의 위치(경로) 크기와 형식을 배열형태로 반환
                            $image_width = $imageinfo[0];
                            $image_height = $imageinfo[1];
                            $image_type = $imageinfo[2];
                            $img_name = $image_copied[0];
                            $img_name = "./data/".$img_name;
                            if ($image_width > 300){
                                // image 타입 1은 gif 2는 jpg 3은 png
                                if($image_type==1 || $image_type==2 || $image_type==3){
                                    if($image_type==3){
                                        print "<p id='image_back' style='margin:0 auto'><img src='$img_name' style='width:100%'></p>";
                                    }else{
                                        print "<img src='$img_name'  style='width:100%'>";
                                    } 
                                }
                            }else{
                                if($image_type==1 || $image_type==2 || $image_type==3){
                                    if($image_type==3){
                                        print "<p id='image_back' style='margin:0 auto'><img src='$img_name' width='$image_width'></p>";
                                    }else{
                                        print "<p style='margin:0 auto'><img src='$img_name' width='$image_width'></p>";
                                    } 
                                }
                            }
                            
                        }else{
                            $imageinfo = getimagesize($file_dir."noimage.png");//인자 이미지파일명과 파일의 위치(경로) 크기와 형식을 배열형태로 반환
                            $image_width = $imageinfo[0];
                            $image_height = $imageinfo[1];
                            $image_type = $imageinfo[2];
                            $img_name = "noimage.png";
                            $img_name = "./data/".$img_name;
                            if ($image_width >  $image_css_width)
                                $image_width =  $image_css_width;
                                // image 타입 1은 gif 2는 jpg 3은 png
                                if($image_type==1 || $image_type==2 || $image_type==3){
                                    print "<img src='$img_name' width='$image_width' id='noimage'>";
                                }
                        }
                    ?>
                </div>
                <div id="list_textbox" class="clearfix">
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
                                    $imageinfo = getimagesize("../img/user_icon.png");//인자 이미지파일명과 파일의 위치(경로) 크기와 형식을 배열형태로 반환
                                    $image_width = $imageinfo[0];
                                    $image_height = $imageinfo[1];
                                    $image_type = $imageinfo[2];
                                    $img_name = "user_icon.png";
                                    $img_name = "../data/".$img_name;
                                    if($image_width > 40){
                                        $image_width = 40;
                                        if($image_type==2 || $image_type==3){
                                            print "<span id='pro_img'><img src='../img/user_icon.png' width='$image_width'></span>";
                                        }
                                    }
                                }
                            }
                        ?>
                        <span id="writer"><?= $item_name ?></span>
                    </div>
                    <div id="list_item4">                   
                        <span id="view_num">
                            <img src="./img/view_icon.png" alt="" id='view_icon'>
                            <b><?= $item_hit ?></b>
                        </span>
                        <span id='ripple_count'>
                            <img src='./img/ripple_icon.png'>
                            <b><?=$num_ripple?></b>
                        </span>
                    </div>
                </div>
            </a>
        
    <?php
        $i--;
    }           
    ?>
        </span>
        <?php }else{?>
            <div id="not_found">
                <img src="./img/not_found.png" alt="" style="opacity: 0.5;">
                <p>No results found</p>
            </div>
        <?php }?>
</section>
</div>
<script>
$(function(){
    $('.imglist').click(function(event) {
        $.ajax({
        url: $(this).attr('href'),
        success: function(newHTML, textStatus, jqXHR) {
            $(newHTML).appendTo('#modal_content').modal('show');
        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert('ajax error');
        }
        // More AJAX customization goes here.
        });

    return false;
    });
})
</script>
<?php include $_SERVER["DOCUMENT_ROOT"]."/footer.php";?>