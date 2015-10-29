<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="../stylesheet/reset.css">
<link rel="stylesheet" type="text/css" href="../stylesheet/general.css">
<link rel="stylesheet" type="text/css" href="../stylesheet/report.css">
<link rel="stylesheet" type="text/css" href="../stylesheet/tcal.css">
<script src="../js_scripts/tcal.js"></script>
<title><?php 
	include $_SERVER['DOCUMENT_ROOT'].'/php_scripts/func.php';
	
	echo numToMonth($monyear[0]).' '.$monyear[1];
	?>
</title>
</head>

    <body>
    <?php
	include $_SERVER['DOCUMENT_ROOT'].'/html/header.php';
	?>
	    <h2 class="title"> Дежурства за <?php echo numToMonth($monyear[0]).' '.$monyear[1];?></h2>
	    
	 </body>    
</html>