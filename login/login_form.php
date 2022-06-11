<?php include $_SERVER["DOCUMENT_ROOT"]."/header.php";?>
<script>

</script>
<?php if(!isset($_SESSION['userid'])){ ?> 
    <div id="login_page">
        <a id="login_form_title" href="/"><img src="../img/login_title.png" alt=""></a>
        <div id="login_form">   
            <form action="login_result.php" method="post" name="login_form">
                <input type="text" name="id" required maxlength="30" id="id" placeholder="User id">
                <input type="password" name="pw" required maxlength="12" id="pw" placeholder="Password">
                <input type="submit" id="login_btn" value="Sign in">
            </form>
            <div><a href="<?php $_SERVER['DOCUMENT_ROOT']?>/member/insert_form.php">Sign up</a></div>
        </div>
    </div>
<?php }else{
    header('Location:./login_info.php');
}?>