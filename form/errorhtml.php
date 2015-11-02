
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
	include $_SERVER['DOCUMENT_ROOT'].'/html/header.php';
	?>
	    <h2 class="title"> Ошибка выполнения скрипта</h2>
	    <div class="error">
	    	<?php
	    	$error= iconv("cp1251","utf-8",$e->getMessage()).'<p> ПРОБЛЕМНАЯ СТРАНИЦА: <a href='.$_SERVER['PHP_SELF'].'>'.$_SERVER['PHP_SELF'].'</a></p>';
	    	echo $error;
	    	//если проблема возникла с sql-запросом, то выводим и его
	    	if(isset($sql))
	    	{
				echo '<p>ЗАПРОС: '.$sql.'<p>';
	    	}
			?>
			
	    </div>
	</body>    
</html>