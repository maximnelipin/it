<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="../stylesheet/reset.css">
<link rel="stylesheet" type="text/css" href="../stylesheet/general.css">
<link rel="stylesheet" type="text/css" href="../stylesheet/add.css">

<title>Добавление этажей</title>

</head>

    <body>
    <?php
	include $_SERVER['DOCUMENT_ROOT'].'/html/header.html';
	?>
	    <h2 class="title"> Добавление этажей</h2>
	    <form action="?"  method="post">	    	
		    	<div class="field">
		    		<label for="id_build" > Название объекта</label>
		    		<p><select required class="text" size="5" name="id_build">
	    			<option disabled>Выберите объект</option>
	    			<?php 
	    				$selsql='SELECT name, id FROM build ORDER BY name';
						$ressql=$condb->query($selsql);
	    				while ($res=$ressql->fetch(PDO::FETCH_ASSOC))
	    				{
	    					echo '<option value='.$res['id'].'>'.$res['name'].'</option>';
	    				}
	    				?>
	    		</select>
		    	</div>
		    	<div class="field">
		    		<label for="floor"> Номера этажей</label>
		    		<input type="text" class="text" size="70"  name="floor">
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