<!doctype html>
<html>
<?php
	include $_SERVER['DOCUMENT_ROOT'].'/php_scripts/auth.php';
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="stylesheet/reset.css">
<link rel="stylesheet" type="text/css" href="stylesheet/general.css">
<link rel="stylesheet" type="text/css" href="stylesheet/index.css">

<title> Справочник </title>
</head>
     <body>
     <?php
	include $_SERVER['DOCUMENT_ROOT'].'/html/header.html';
	?>
    
    	<form action="index.php<?php if(isset ($_GET['link'])) echo '?link='.$_GET['link']; ?>" method="post">
    		<div class="auth"> 
				<p> Справочная система </p>
    			<p>Имя пользователя </p> <p><input class="text" type="text" size="27" name="login" /> </p>
    			<p> Пароль</p> <p><input class="text" type="password" size="27" name="pwd"/> </p>
    			 <input class="button" type="submit" value="Вход"/>
    		</div>
    	</form>    	    
    </body>
 
</html>