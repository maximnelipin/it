<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="../stylesheet/reset.css">
<link rel="stylesheet" type="text/css" href="../stylesheet/general.css">
<link rel="stylesheet" type="text/css" href="../stylesheet/add.css">

<title>Добавление кабинетов</title>
</head>

    <body>
    <?php
	include $_SERVER['DOCUMENT_ROOT'].'/html/header.html';
	?>
	    <h2 class="title"> Добавление кабинетов на этаж</h2>
	    <form action="?"  method="post">	    	
		    	<div class="field">
		    		<label for="id_floor" > Номер этажа</label>
		    		<select required class="text" size="5" name="id_floor">
	    			<option disabled>Выберите этаж</option>
	    			<?php 
						$selsql='SELECT build.name as build, floor.id as id_floor, floor.floor as floor FROM build
								RIGHT JOIN floor ON build.id = floor.id_build ORDER BY name, floor';
						$ressql=$condb->query($selsql);
	    				while ($res=$ressql->fetch(PDO::FETCH_ASSOC))
	    				{
	    					echo '<option value='.$res['id_floor'].'>'.$res['build']. " ".$res['floor'].' этаж </option>';
	    					    					
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