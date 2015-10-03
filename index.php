<!doctype html>
<html>
<?php
	include $_SERVER['DOCUMENT_ROOT'].'/php_scripts/auth.php';
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="stylesheet/reset.css">
<link rel="stylesheet" type="text/css" href="stylesheet/style.css">

<title> Справочник </title>
</head>
     <body>
     <?php
	include $_SERVER['DOCUMENT_ROOT'].'/html/header.html';
	?>
    <?php
    	print '
    	<form action="index.php" method="post">
    		<div class="auth"> 
				<p> Справочная система </p>
    			<p>Имя пользователя </p> <p><input class="text" type="text" name="login" /> </p>
    			<p> Пароль</p> <p><input class="text" type="password" name="pwd"/> </p>
    			<p> <input class="button" type="submit" value="Вход"/> </p>
    		</div>
    	</form>
    	';
    
    			
    ?>
    
    
    </body>
 
</html>