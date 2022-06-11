<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Drawing Band</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:ital,wght@0,300;0,400;0,700;1,300;1,400;1,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php $_SERVER["DOCUMENT_ROOT"]?>/css/reset.css">
    <link rel="stylesheet" href="<?php $_SERVER["DOCUMENT_ROOT"]?>/css/header.css">
    <link rel="stylesheet" href="<?php $_SERVER["DOCUMENT_ROOT"]?>/css/main.css">
    <link rel="stylesheet" href="<?php $_SERVER["DOCUMENT_ROOT"]?>/css/footer.css">
    <link rel="stylesheet" href="<?php $_SERVER["DOCUMENT_ROOT"]?>/css/style.css">
    <link rel="stylesheet" href="<?php $_SERVER["DOCUMENT_ROOT"]?>/login/style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap-theme.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
    <script src="<?php $_SERVER["DOCUMENT_ROOT"]?>/js/modal.js"></script>
</head>
<body>
<header>
    <div id="dummy"></div>
    <div id="header_bottom" class="clearfix">
        <button class="mbtn">
            <span class="bar1"></span>
            <span class="bar2"></span>
            <span class="bar3"></span>
        </button>
        <p id="logo">
            <a href="/">
                <img src="<?php $_SERVER["DOCUMENT_ROOT"]?>/img/logo.jpg" alt="logo">
            </a>
        </p>
        <form name="board_form" method="post" action="search_result.php?mode=search" id="search_form" class="clearfix" autocomplete="off">
            <div id="list_search">                   
                <div id="list_search4">
                    <i class="fas fa-search"></i>
                    <input type="text" name="str" maxlength="20" placeholder="SEARCH">     
                </div>
                <div id="list_search5">
                    <input type="submit" value="SEARCH">
                </div>                      
            </div> <!-- list_search -->
        </form>
        <div id="login">
            <a href="<?php $_SERVER['DOCUMENT_ROOT']?>/note/note.php" class="note_btn"><i class="far fa-comment-dots"></i></a>
            <?php 
            if(!isset($_SESSION['userid'])){
            ?>
                <a href="<?php $_SERVER['DOCUMENT_ROOT']?>/login/login_form.php" id="login_btn">
                <i class="far fa-user-circle"></i>
                </a>
            <?php
            }else{
            ?>
                <a href="<?php $_SERVER['DOCUMENT_ROOT']?>/login/login_info.php?id=<?=$_SESSION['userid']?>" id="login_btn">
                <i class="far fa-user-circle"></i>
                </a>
            <?php
            }
            ?>   
            <button id="menu_btn1">
                <span></span>
                <span></span>
                <span></span>
            </button>        
        </div>
        <div id="link_box">
            <p id="title">
                <img src="<?php $_SERVER["DOCUMENT_ROOT"]?>/img/logo.jpg" alt="logo">
            </p>
            <button id="menu_btn2">
                <i class="fas fa-times"></i>
            </button> 
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
        <script>
            $('#menu_btn1').click(function(){
                $('#link_box').addClass('active');
                $('#dummy').addClass('active');
            })
            $('#menu_btn2').click(function(){
                $('#link_box').removeClass('active');
                $('#dummy').removeClass('active');
            })
        </script>
    </div>
    <nav id="gnb_m">
        <script>
            $(function(){
                $('.mbtn').click(function(){
                    $('#gnb_m').slideToggle();
                    $('.mbtn').toggleClass('active');
                })
            })
        </script>
        <form name="board_form" method="post" action="search_result.php?mode=search" id="search_form" class="clearfix" autocomplete="off">
            <div id="list_search">                   
                <div id="list_search4">
                    <i class="fas fa-search"></i>
                    <input type="text" name="str" maxlength="15" placeholder="SEARCH">     
                </div>
                <div id="list_search5">
                    <input type="submit" value="SEARCH">
                </div>                      
            </div> <!-- list_search -->
        </form>
        <ul>
            <li>
                <a href="<?php $_SERVER['DOCUMENT_ROOT']?>/list/list.php?mode=3D">3D</a>
            </li>  
            <li>
                <a href="<?php $_SERVER['DOCUMENT_ROOT']?>/list/list.php?mode=pencil">Animation</a>
            </li>
            <li>
                <a href="<?php $_SERVER['DOCUMENT_ROOT']?>/list/list.php?mode=pen">Cartoon</a>
            </li>
            <li>
                <a href="<?php $_SERVER['DOCUMENT_ROOT']?>/list/list.php?mode=digital">Digital Art</a>
            </li>
            <li>
                <a href="<?php $_SERVER['DOCUMENT_ROOT']?>/list/list.php?mode=pastel">Painting Art</a>
            </li>
            <li>
                <a href="<?php $_SERVER['DOCUMENT_ROOT']?>/list/list.php?mode=oil">Drawing Art</a>
            </li>
            <li>
                <a href="<?php $_SERVER['DOCUMENT_ROOT']?>/list/list.php?mode=acrylic">Game Art</a>
            </li>
            <li>
                <a href="<?php $_SERVER['DOCUMENT_ROOT']?>/list/list.php?mode=stereoscopic">Traditional Art</a>
            </li>
            <li>
                <a href="<?php $_SERVER['DOCUMENT_ROOT']?>/list/list.php?mode=graffiti">Web</a>
            </li>
            <li>
                <a href="<?php $_SERVER['DOCUMENT_ROOT']?>/list/list.php?mode=graffiti">Illustration</a>
            </li>
        </ul>
        
    </nav>
</header>
<div id="bottom_btn">
    <button id="top_btn"><i class="fas fa-arrow-up"></i></button>
    <a href="<?php $_SERVER['DOCUMENT_ROOT']?>/write.php" id="write_btn"><i class="fas fa-plus"></i></a>
</div>
<script>
    win_info()
	get_offset()
	motion()
	function win_info(){
		scr_t=$(window).scrollTop()
		win_h=$(window).height()
		win_w=$(window).width()	
	}
	function get_offset(){
		header_height		= 		$('header').height()
	}
	function motion(){
		//header
		if(scr_t>=40){
			$('#top_btn').fadeIn();
		}else{
			$('#top_btn').fadeOut();
		}
		
	}
	$(window).scroll(function(){
		win_info()
		get_offset()
		motion()
	})
	$(window).resize(function(){
        win_info()
		get_offset()
		motion()
    })
    $('#top_btn').click(function(){
        $('body,html').stop().animate({'scrollTop':0})
    })
</script>


