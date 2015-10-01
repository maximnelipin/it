<!doctype html>
<html>
<?php
	include_once ('auth.php');
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="stylesheets/style.css">
<title> Справочник </title>
</head>

    <body>
    <?php
    	print '
    	<form action="index.php" method="post">
    		<div class="auth"> 
    			<p>Имя  <input type="text" name="login" /> </p>
    			<p> Пароль<input type="password" name="pwd"/> </p>
    			<p> <input type="submit" value="Вход"/> </p>
    		</div>
    	</form>
    	';
    	echo " Авторизация"
    			
    ?>
    
    
    </body>
 
</html>