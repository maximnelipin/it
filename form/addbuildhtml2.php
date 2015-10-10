<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="../stylesheet/reset.css">
<link rel="stylesheet" type="text/css" href="../stylesheet/general.css">
<link rel="stylesheet" type="text/css" href="../stylesheet/add.css">
<script src="http://code.jquery.com/jquery-2.0.3.min.js"></script>
<script src="app.js"></script>
<title>Добавление здания</title>
</script>
</head>

    <body>
    <?php
	include $_SERVER['DOCUMENT_ROOT'].'/html/header.html';
	?>
	    <h2 class="title"> Добавление здания</h2>
	    <form action="?"  method="post">	    	
		    	<div class="field">
		    		<label for="name" > Название объекта</label>
		    		<input type="text" class="text" size="70" name="name"></textarea>    	
		    	</div>
		    	<div class="field">
		    		<label for="address"> Адрес объекта</label>
		    		<input type="text" class="text" size="70"  name="address"></textarea> 
		    	</div>
		    	<div class="field">
		    		<label for="floor"> Номера этажей</label>
		    		<input type="text" class="text" size="70"  name="floor"></textarea> 
		    	</div>
		    	<div class="field">
		    		<label for="cabinet"> Кабинеты</label>
		    		<input type="text" class="text" size="70"  name="cabinet"></textarea> 
		    	</div>		    	
		    	<div>
		    		<input type="submit" class="button" value="Добавить">
		    	</div>	 	  
	    </form>
	    <?php
	
		?>
    </body>
    
</html>