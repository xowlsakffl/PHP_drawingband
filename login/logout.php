<?php
session_start();
unset($_SESSION['userid']);
unset($_SESSION['name']);
unset($_SESSION['nick']);
unset($_SESSION['level']);
header("Location:../index.php");
?>