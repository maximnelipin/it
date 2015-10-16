
<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="../stylesheet/reset.css">
<link rel="stylesheet" type="text/css" href="../stylesheet/general.css">

<title>Ошибка скрипта</title>
</head>

    <body>
    <?php
	include $_SERVER['DOCUMENT_ROOT'].'/html/header.html';
	?>
	    <h2 class="title"> Ошибка выполнения скрипта</h2>
	    <div class="error">
	    	<?php
	    	$error= $e->getMessage().'<a href='.$_SERVER['PHP_SELF'].'>'.$_SERVER['PHP_SELF'].'</a>';
	    	$error=iconv("cp1251","utf-8",$error);
	    	
			echo $error;
			?>
			
	    </div>
	</body>    
</html>