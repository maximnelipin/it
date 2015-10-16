<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="../stylesheet/reset.css">
<link rel="stylesheet" type="text/css" href="../stylesheet/general.css">
<link rel="stylesheet" type="text/css" href="../stylesheet/add.css">
<title>Добавление ИТ-инструкции</title>
</head>

    <body>
    <?php
	include $_SERVER['DOCUMENT_ROOT'].'/html/header.html';
	?>
	    <h2 class="title"> Добавление инструкции для ИТ-спецов</h2>
	    <form action="?"  method="post">
	    	<div class="field">
	    		<label for="name" > Название инструкции</label>
	    		<input type="text" class="text" size="70" name="name" required> 	
	    	</div>
	    	<div class="field">
	    		<label for="url"> URL-путь к странице с инструкцией</label>
	    		<input type="text" class="text" size="70"  name="url" required>
	    	</div>	    	
	    	<div>
	    		<input type="submit" class="button" value="Добавить">
	    	</div>
	    	    
	    </form>
	    <?php
	
		?>
    </body>
    
</html>