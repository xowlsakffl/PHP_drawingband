<?php include_once($_SERVER['DOCUMENT_ROOT']."/header.php");?>
<link rel="stylesheet" href="<?php $_SERVER["DOCUMENT_ROOT"]?>/note/style.css">
<section id="note_page">
    <p id="note_title">Message</p>
<?php
if(isset($_SESSION['userid'])){
?>
<ul id="note_menu1" class="clearfix">
        <li>
            <a href="<?php $_SERVER["DOCUMENT_ROOT"]?>/note/note.php">received Message</a>
        </li>
        <li>
            <a href="<?php $_SERVER["DOCUMENT_ROOT"]?>/note/note_send.php">Sent Message</a>
        </li>
</ul>
<div id="write_note_in">
	<form action="insert.php" method="post" enctype="multipart/form-data">
        <div id="write_form">
            <div class="wr_ip">
                <p>RECIPIENT</p>
                <input type="text" name="recv_name" required />
            </div>
            <div class="wr_ip wr_ip_top">
                <p>SUBJECT</p>
                <input type="text" name="subject" required/>
            </div>
            <div class="wr_ip wr_ip_top">
                <p>CONTENT</p>
                <textarea name="content" required wrap="physical"></textarea>
            </div>
            <button type="submit" class="wri_bt wr_ip_top">Send message</button>
        </div>
    </form>
</div>
<script>
        window.location.href = 'http://drawingband.dothome.co.kr/login/login_form.php';
    </script>
</section>
<?php include_once($_SERVER['DOCUMENT_ROOT']."/footer.php");?>