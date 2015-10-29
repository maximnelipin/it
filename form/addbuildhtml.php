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
    <?php
	include $_SERVER['DOCUMENT_ROOT'].'/html/header.php';
	?>
	    <h2 class="title"> <?php htmlout($pageTitle); ?></h2>
	    <form action=?<?php htmlout($action);?>  method="post">	    	
		    	<div class="field">
		    		<label for="name" > Название объекта</label>
		    		<input type="text" class="text" size="70" name="name" value=<?php htmloutinput($name);?> required>  	
		    	</div>
		    	<div class="field">
		    		<label for="address"> Адрес объекта</label>
		    		<input type="text" class="text" size="70"  name="address" value=<?php htmloutinput($address);?> required>
		    	</div>
		    	<div class="field">
		    		<label for="floor"> Номера этажей</label>
		    		<input type="text" class="text" size="70"  name="floor" <?php htmlout($dis);?> <?php htmlout($req);?>> 
		    	</div>
		    	<div class="field">
		    		<label for="cabinet"> Кабинеты</label>
		    		<input type="text" class="text" size="70"  name="cabinet" <?php htmlout($dis);?> <?php htmlout($req);?>>
		    	</div>		    	
		    	<div class="field" >
		    		<input type="hidden" name="id" value=<?php htmlout($id);?>>
	    		<input type="submit" class="button" value=<?php htmlout($button);?>>
	    		<input type="button" class="button" value="Назад" onClick=<?php echo 'location.replace("http://'.$_SERVER["HTTP_HOST"].$_SERVER["PHP_SELF"].'");'?>>
		    	</div>	 	  
	    </form>
	    <?php
	
		?>
    </body>
    
</html>