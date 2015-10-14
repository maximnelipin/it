<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="../stylesheet/reset.css">
<link rel="stylesheet" type="text/css" href="../stylesheet/general.css">
<link rel="stylesheet" type="text/css" href="../stylesheet/add.css">
<title>Добавление GPO</title>
</head>

    <body>
    <?php
	include $_SERVER['DOCUMENT_ROOT'].'/html/header.html';
	?>
	    <h2 class="title"> Добавление GPO</h2>
	    <form action="?"  method="post">
	    	<div class="field">
	    		<label for="name" > Имя политики в AD</label>
	    		<input type="text" class="text" size="70" name="name">   	
	    	</div>
	    	<div class="field">
	    		<label for="container"> Связанные контайнеры, через запятую</label>
	    		<input type="text" class="text" size="70"  name="container">
	    	</div>
	    	<div class="field">
	    		<label for="netpath"> Путь к связанным файлам, через запятую </label>
	    		<textarea class="text" cols="63" rows="5"  name="netpath"></textarea> 
	    	</div>
	    	<div class="field">
	    		<label for="descrip"> Описание </label>
	    		<textarea class="text" cols="63" rows="5"  name="descrip"></textarea> 
	    	</div>
	    	<div>
	    		<input type="submit" class="button" value="Добавить">
	    	</div>
	    	    
	    </form>
	    <?php
	
		?>
    </body>
    
</html>