<?php 
include $_SERVER["DOCUMENT_ROOT"]."/header.php";


require_once($_SERVER['DOCUMENT_ROOT']."/lib/db_connect.php");
$pdo = db_connect();

$id = $_SESSION['userid'];
$file_dir = "../data/";
try{
    $sql = "select * from drawingband.member where id=?";
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1,$id,PDO::PARAM_STR);
    $stmh->execute();
}catch(PDOException $Exception){
    $pdo->rollback();
    print "오류 : ".$Exception->getMessage();
}
while($row=$stmh->fetch(PDO::FETCH_ASSOC)){
    
?>

<script>
    function Pwcheck(){
    if($('#pw').val() != $('#pw2').val()){
        if($('#pw2').val()!=''){
            $('#pw_check1').addClass('active');
            $('#pw_check2').addClass('active');
            $('#pw2').val('');
            $('#pw2').focus();
        }
    }
    if($('#pw').val() == $('#pw2').val()){
        if($('#pw2').val()!=''){
            $('#pw_check1').removeClass('active');
            $('#pw_check2').removeClass('active');
        }
	}
};
</script>
<link rel="stylesheet" href="<?php $_SERVER["DOCUMENT_ROOT"]?>/member/style.css">
<div id="reg_form">
    <form name="member_form" action="updatepro.php?id=<?=$id?>" method="post" onSubmit="return chkForm();" enctype="multipart/form-data" class="clearfix">
    <div id="profile">
            <p id="title">Create Account</p>
            <div id="user_img">
                <?php
                    $image_name[0] = $row["file_name_0"];
                    $image_copied[0] = $row["file_copied_0"];
                    if($image_copied[0]){
                        $image_info = getimagesize($file_dir.$image_copied[0]);
                        $image_width = $image_info[0];
                        $image_height = $image_info[1];
                        $image_type = $image_info[2];
                        $image_width = $image_info[3];
                        $image_name = $file_dir.$image_copied[0];
                        if($image_width < 174){
                            $image_width = 174;
                            // image 타입 1은 gif 2는 jpg 3은 png
                            if($image_type==2 || $image_type==3){
                                print "<p id='pro_img'><img src='$image_name' width='$image_width' id='preview'></p>";
                            }
                        }
                    }else{
                        $imageinfo = getimagesize("../img/user_icon.png");//인자 이미지파일명과 파일의 위치(경로) 크기와 형식을 배열형태로 반환
                        $image_width = $imageinfo[0];
                        $image_height = $imageinfo[1];
                        $image_type = $imageinfo[2];
                        $img_name = "user_icon.png";
                        $img_name = "../data/".$img_name;
                        if($image_type==2 || $image_type==3){
                            print "<img src='../img/user_icon.png' id='preview'>";
                        }
                    }
                ?>
            </div>
            <div id="upload_c">
                <label for="upfile">Edit image</label>
                <input type="file" name="upfile[]" id="upfile">
                <?php if ($row["file_name_0"]){ ?>
                    <div class="delete_ok" style="margin-top:5px">
                        <input type="checkbox" name="del_file[]" value="0">
                        <span style="font-weight: 700;">Delete</span>
                    </div>
                <?php } ?>
            </div>   
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
                                if(img.width > 173){
                                    $('#preview').attr('src', e.target.result);
                                    $('#preview').addClass('active');
                                }else{
                                    $('#preview').attr('src', e.target.result);
                                }   
                            }
                        }
                        img.src = reader.result;
                    }                   
                    reader.readAsDataURL(input.files[0]);
                }
            }
            $("#upfile").change(function() {                
                readURL(this);
            });
            </script>
        </div>
        <div id="input_box">
            <p id="input_title">ID</p>
            <p id="update_id"><?=$row['id']?></p>

            <p id="input_title">PASSWORD</p>
            <p id="input_content">
                <input type="password" name="pw" required placeholder="Number+ 6~12" maxlength="12" id="pw">
                <span id="pw_check1">비밀번호가 일치하지 않습니다.</span>
            </p>
            <p id="input_title">PASSWORD CONFIRM</p>
            <p id="input_content">
                <input type="password" name="pw_confirm" required placeholder="Number+ 6~12" maxlength="12" id="pw2" onblur="Pwcheck()">
                <span id="pw_check2">비밀번호가 일치하지 않습니다.</span>
            </p>

            <p id="input_title">NAME</p>
            <p id="update_name"><?=$row['name']?></p>

            <p id="input_title">BIRTH</p>
            <p id="input_content"><input type="text" name="birth" required placeholder="Number+6 ex(000101)" maxlength="6"></p>

            <p id="input_title">SEX</p>
            <p id="input_sex">
                <input type="radio" name="sex" value="남" required>
                <span>MAN</span>
                <input type="radio" name="sex" value="여">
                <span>FEMALE</span>
            </p>

            <p id="input_title">HP</p>
            <p id="input_content"><input type="text" name="hp" required placeholder="Skip -" maxlength="20"></p>
        </div>
<?php 
}
?>
        <div id="button">
            <input type="submit" value="EDIT">
        </div>
    </form>
</div>
<?php 
include $_SERVER["DOCUMENT_ROOT"]."/footer.php";
?>