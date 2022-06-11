<?php 
include $_SERVER["DOCUMENT_ROOT"]."/header.php";

if(isset($_REQUEST['mode'])){
    $mode = $_REQUEST['mode'];
}else{
    $mode = "";
}

if(isset($_REQUEST['num'])){
    $num = $_REQUEST['num'];
}else{
    $num = "";
}


if($mode=="modify"){
    try{
        require_once($_SERVER['DOCUMENT_ROOT']."/lib/db_connect.php");
        $pdo = db_connect();
        $sql = "select * from drawingband.gallery where num = ? ";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1,$num,PDO::PARAM_STR);
        $stmh->execute();
        $count = $stmh->rowCount();
        if($count<1){
            print "검색결과가 없습니다.<br>";
        }else{//수정할게 있으면 변수에 배열값 할당
            $row = $stmh->fetch(PDO::FETCH_ASSOC);
            $item_subject = $row["subject"];
            $item_content = $row["content"];
            $image_name[0] = $row["file_name_0"];
            $image_copied[0] = $row["file_copied_0"];
        }
    }catch(PDOException $Exception) {
        $pdo->rollBack();
        print "오류: ".$Exception->getMessage();
    }
}
if(isset($_SESSION['userid'])){
?>
<div id="gallery_page">
    <?php
    if($mode=="modify"){
    ?>
    <form action="insert.php?mode=modify&num=<?=$num?>" method="post" enctype="multipart/form-data" class="clearfix">
    <?php 
    }else{
    ?>
    <form action="insert.php" method="post" enctype="multipart/form-data" class="clearfix">
    <?php 
    }
    ?>
    <div id="left_box">
        <img src="" id="preview"> 
        <div id="imgbox">             
            <div id="upload_c">
                <label for="upfile" style="border: none;padding:0"><img src="./img/upload_img.png" alt=""></label>
                <label for="upfile">Upload file</label>
                <input type="file" name="upfile[]" id="upfile" required>   
            </div>
        </div>
        <?php if ($mode=="modify" && $image_copied[0]){ ?>
                <div id="delete_ok">
                <button type="button" id="upfile_delete1"><i class="fas fa-times"></i></button>
                    <script>
                        $(function(){
                            $('#imgbox').hide();
                            $('#upfile_delete1').click(function(){
                                $('#delete_ok img').hide();
                                $('#imgbox').show();
                                $('#upfile_delete1').hide();
                            })
                        })
                    </script>
                <?php
                    if ($image_copied[0]){
                        $imageinfo = getimagesize("./data/".$image_copied[0]);//인자 이미지파일명과 파일의 위치(경로) 크기와 형식을 배열형태로 반환
                        $image_width[$i] = $imageinfo[0];
                        $image_height[$i] = $imageinfo[1];
                        $image_type[$i] = $imageinfo[2];
                        $img_name = $image_copied[0];
                        $img_name = "./data/".$img_name;
                        if ($image_width[$i] >  800){
                            $image_width[$i] =  800;
                            // image 타입 1은 gif 2는 jpg 3은 png
                            if($image_type[$i]==1 || $image_type[$i]==2 || $image_type[$i]==3){
                                print "<img src='$img_name' style='width:100%'>";
                            }
                        }else{
                            if($image_type[$i]==1 || $image_type[$i]==2 || $image_type[$i]==3){
                                print "<img src='$img_name' width='$image_width[$i]'>";
                            }
                        }
                    }
                ?>
                </div>
            <?php } ?>
        <button type="button" id="upfile_delete"><i class="fas fa-times"></i></button>
        
        <script>             
               
            function readURL(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        var img = new Image;
                        img.onload = function(){
                            if(input.files[0].size > 10000000){
                                alert('10MB 이하의 파일만 업로드할 수 있습니다.');
                            }else{
                                if(img.width > 800){
                                    $('#preview').attr('src', e.target.result);
                                    $('#preview').addClass('active');
                                    $('#imgbox').hide();
                                    $('#upfile_delete').addClass('active');
                                }else{
                                    $('#preview').attr('src', e.target.result);
                                    $('#imgbox').hide();
                                    $('#upfile_delete').addClass('active');
                                }     
                            }
                        }
                        img.src = reader.result;
                    }                   
                    reader.readAsDataURL(input.files[0]);
                }
            }
            var domEleArray = [$('#upfile').clone()]; // 원본 복사
            $('#upfile_delete').click(function() {
                $("#upfile").val("");
                $('#preview').attr('src', "");
                $('#imgbox').show();
                $('#upfile_delete').removeClass('active');
            });
            $("#upfile").change(function() {                
                readURL(this);
            });
        </script> 
    </div>
    <p id="file_size">Only JPEG, PNG, GIF, 800 x 600 size, Below 10MB</p>
    <div id="right_box">
        <div id="category">
            <p>CATEGORY</p>
            <div>
                <select name="cate" id="">
                    <option value="3D">3D</option>
                    <option value="Animation">Animation</option>
                    <option value="Cartoon">Cartoon</option>
                    <option value="Digital">Digital Art</option>
                    <option value="Drawing">Drawing Art</option>
                    <option value="Painting">Painting Art</option>
                    <option value="Game">Game Art</option>
                    <option value="Traditional">Traditional Art</option>
                    <option value="Illustration">Illustration</option>
                </select>
            </div>
        </div>
        <div id="subject">
            <p>SUBJECT</p>
            <div>
                <?php if ($mode=="modify" && $item_subject){ ?>
                    <input type="text" name="subject" required value="<?=$item_subject?>" placeholder="Your Title">
                <?php }else{ ?>  
                    <input type="text" name="subject" required placeholder="Your Title">
                <?php }?>          
            </div>
        </div>
        <div id="content">
            <p>CONTENT</p>
            <div>                 
                <?php if ($mode=="modify" && $item_content){ ?>
                    <textarea name="content" wrap="physical"><?=$item_content?></textarea>
                <?php }else{ ?>  
                    <textarea name="content" wrap="physical" placeholder="Tell everyone your drawing"></textarea>
                <?php }?> 
            </div>
        </div>
        <div id="html">
            <p>OPTION</p>
            <div>                  
                <input type="checkbox" name="html_ok" value="y" id="check">
                <label for="check">HTML</label>
            </div>
        </div>
        <div class="clearfix" id="submit_btn">
            <a href="./index.php" id="list_btn">Cancel</a>
            <input type="submit" value="WRITE" id="write_btn">    
        </div>
    </div>
    </form>
    
</div>
<?php 
}else{
?>
<script>
    window.location.href = 'http://drawingband.dothome.co.kr/login/login_form.php';
</script>
<?php
}?>
<?php include $_SERVER["DOCUMENT_ROOT"]."/footer.php"?>