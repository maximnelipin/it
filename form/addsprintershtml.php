<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="../stylesheet/reset.css">
<link rel="stylesheet" type="text/css" href="../stylesheet/general.css">
<link rel="stylesheet" type="text/css" href="../stylesheet/add.css">
<title><?php htmlout($pageTitle); ?></title>
</head>

    <body>
    <?php	include $_SERVER['DOCUMENT_ROOT'].'/html/header.php';	?>
	    <h2 class="title"> <?php htmlout($pageTitle); ?></h2>
	    <form action=?<?php htmlout($action);?>  method="post">
	    	<div class="field">
	    		<label for="name" > Модель принтера</label>
	    		<input type="text" class="text" size="70" name="name" value=<?php htmloutinput($name);?> required>   	
	    	</div>
	    	<div class="field">
	    		<label for="cart"> Тип картриджа</label>
	    		<input type="text" class="text" size="70"  name="cart" value=<?php htmloutinput($cart);?> required>
	    	</div>
	    	<div class="field">
	    		<label for="drivers"> Папка с драйверами</label>
	    		<input type="text" class="text" size="70"  name="drivers" value=<?php htmloutinput($drivers);?> required>
	    	</div>
	    	<div class="field" >
	    		<?php	include $_SERVER['DOCUMENT_ROOT'].'/html/addbutton.php';?>
	    	</div>	    
	    </form>	    
    </body>    
</html>