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
	include $_SERVER['DOCUMENT_ROOT'].'/html/header.html';
	?>
	    <h2 class="title"><?php htmlout($pageTitle); ?></h2>
	    <form action=?<?php htmlout($action);?>  method="post">
	    	<div class="field">
	    		<label for="name" > Название инструкции</label>
	    		<input type="text" class="text" size="70" name="name" value=<?php htmloutinput($name);?> required> 	
	    	</div>
	    	<div class="field">
	    		<label for="url"> URL-путь к странице с инструкцией</label>
	    		<input type="text" class="text" size="70"  name="url" value=<?php htmloutinput($url);?> required>
	    	</div>	    	
	    	<div>
	    		<input type="hidden" name="id" value=<?php htmlout($id);?>>
	    		<input type="submit" class="button" value=<?php htmlout($button);?>>
	    		<input type="button" class="button" value="Назад" onClick=<?php echo 'location.replace("http://'.$_SERVER["HTTP_HOST"].$_SERVER["PHP_SELF"].'");'?>>
	    	</div>
	    	    
	    </form>
	
    </body>
    
</html>