<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="../stylesheet/reset.css">
<link rel="stylesheet" type="text/css" href="../stylesheet/general.css">
<link rel="stylesheet" type="text/css" href="../stylesheet/add.css">
<script src="http://code.jquery.com/jquery-2.0.3.min.js"></script>
<script src="app.js"></script>
<title>Добавление кабинетов</title>
</script>
</head>

    <body>
    <?php
	include $_SERVER['DOCUMENT_ROOT'].'/html/header.html';
	?>
	    <h2 class="title"> Добавление кабинетов на этаж</h2>
	    <form action="?"  method="post">	    	
		    	<div class="field">
		    		<label for="id_floor" > Номер этажа</label>
		    		<p><select required class="text" size="5" name="id_floor">
	    			<option disabled>Выберите этаж</option>
	    			<?php 
	    				$selsql='SELECT build.name, location.id, location.floor FROM build
								RIGHT JOIN location ON build.id = location.id_build ORDER BY name, floor';
						$ressql=$conbd->query($selsql);
	    				while ($res=$ressql->fetch(PDO::FETCH_ASSOC))
	    				{
	    					echo '<option value='.$res['id'].'>'.$res['name']. " ".$res['floor'].' этаж </option>';
	    				}
	    				?>
	    		</select>
		    	</div>
		    	<div class="field">
		    		<label for="cabinet"> Кабинеты</label>
		    		<input type="text" class="text" size="70"  name="cabinet">
		    	</div>		    		    	
		    	<div>
		    		<input type="submit" class="button" value="Добавить">
		    	</div>	 	  
	    </form>
	    <?php
	
		?>
    </body>
    
</html>