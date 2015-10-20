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
	    <form action="?<?php htmlout($action);?>"  method="post">
	    	<div class="field">
	    		<label for="name" > Имя политики в AD</label>
	    		<input type="text" class="text" size="70" name="name" value=<?php htmloutinput($name);?> required <?php htmlout($dis);?>>   	
	    	</div>
	    	<div class="field">
	    		<label for="container"> Связанные контайнеры, через запятую</label>
	    		<input type="text" class="text" size="70"  name="container" value=<?php htmloutinput($container);?> >
	    	</div>
	    	<div class="field">
	    		<label for="netpath"> Путь к связанным файлам, через запятую </label>
	    		<textarea class="text" cols="63" rows="5"  name="netpath" ><?php htmlout($netpath);?></textarea>
	    	</div>
	    	<div class="field">
	    		<label for="descrip"> Описание </label>
	    		<textarea class="text" cols="63" rows="5"  name="descrip" ><?php htmlout($descrip);?></textarea>
	    	</div>
	    	<div class="field" >
	    		<input type="submit" class="button" value=<?php htmlout($button);?>>
	    		<input type="button" class="button" value="Назад" onClick=<?php echo 'location.replace("http://'.$_SERVER["HTTP_HOST"].$_SERVER["PHP_SELF"].'");'?>>
	    	</div>
	    	    
	    </form>
	    <?php
	
		?>
    </body>
    
</html>