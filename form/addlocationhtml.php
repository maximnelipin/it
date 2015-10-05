<?php
	    	
			//mysql_connect($hostsql, $dbuser, $dbpwd);
			//mysql_select_db($dbname);
			//$selsql='select name from build';
	    	//$ressql=mysql_query($selsql);
	    	//mysql_set_charset( 'utf8' );
	    	//$ressql=$conbd->exec($selsql);
	    	
	    	?>

<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="../stylesheet/reset.css">
<link rel="stylesheet" type="text/css" href="../stylesheet/general.css">
<link rel="stylesheet" type="text/css" href="../stylesheet/add.css">
<title>Добавление здания</title>
</head>

    <body>
    <?php
	include $_SERVER['DOCUMENT_ROOT'].'/html/header.html';
	?>
	
	    <h2 class="title"> Добавление модели принтера</h2>
	    <form action="?"  method="post">
	    	<div class="field">
	    		<label for="idbuild" > Модель принтера</label>	    		
	    		<select required class="text" size="5" name="idbuild">
	    			<option disabled>Выберите объект</option>
	    			<?php 
	    				$selsql='select id, name from build';
						$ressql=$conbd->query($selsql);
	    				while ($res=$ressql->fetch(PDO::FETCH_ASSOC))
	    				{
	    					echo '<option value='.$res['id'].'>'.$res['name'].' </option>';
	    				}
	    				?>
	    		</select>   	
	    	</div>
	    	<div class="field">
	    		<label for="floor"> Этаж</label>
	    		<input type="text" class="text" size="70"  name="floor"></textarea> 
	    	</div>
	    	<div class="field">
	    		<label for="note"> Примечание</label>
	    		<input type="text" class="text" size="70" width="3" name="note"></textarea> 
	    	</div>
	    	<div>
	    		<input type="submit" class="button" value="Добавить">
	    	</div>
	    
	    </form>
	    <?php
	
		?>
    </body>
    
</html>