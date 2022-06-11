<?php 
include $_SERVER["DOCUMENT_ROOT"]."/header.php";
?>
<script>
function Checkid(){
    $.ajax({
        url : './check_id.php',
        type : 'POST',
        data: $('#member_form').serialize(),
        success : function(data){
            $('#id_check').html(data);
        }
    })
} 
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
    <form name="member_form" action="insertpro.php" method="post" enctype="multipart/form-data" class="clearfix" id="member_form" autocomplete="off">
        <div id="profile">
            <p id="title">Create Account</p>
            <p id="user_img">
                <img src="../img/user_icon.png" alt="" id="preview">
            </p>
            <div id="upload_c">
                <label for="upfile" style="border: none;padding:0"><img src="./img/upload_img.png" alt=""></label>
                <label for="upfile">Upload profile image</label>
                <input type="file" name="upfile[]" id="upfile">
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
            <p id="input_content">
                <input type="text" name="id" id="id1" placeholder="Your ID" maxlength="30" onblur="Checkid()" required>
                <span id="id_check"></span>
            </p>
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
            <p id="input_content"><input type="text" name="name" required placeholder="Your Name" maxlength="10"></p>

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
        <div id="button">
            <input type="submit" value="SIGN UP">
        </div>
    </form>
</div>
<?php 
include $_SERVER["DOCUMENT_ROOT"]."/footer.php";
?>