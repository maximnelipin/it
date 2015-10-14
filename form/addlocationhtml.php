<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="../stylesheet/reset.css">
<link rel="stylesheet" type="text/css" href="../stylesheet/general.css">
<link rel="stylesheet" type="text/css" href="../stylesheet/add.css">
<title>Добавление локации</title>
</head>

    <body>
    <?php
	include $_SERVER['DOCUMENT_ROOT'].'/html/header.html';
	?>
	
	    <h2 class="title"> Добавление этажей здания</h2>
	    <form action="?"  method="post">
	    	<div class="field">
	    		<label for="idbuild" > Здание</label>	    		
	    		<p><select required class="text" size="5" name="idbuild">
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
	    		</p>  	
	    	</div>
	    	<div class="field">
	    		<label for="floor"> Этаж</label>
	    		<input type="text" class="text" size="70"  name="floor">
	    	</div>
	    	<div class="field">
	    		<label for="note"> Примечание</label>
	    		<input type="text" class="text" size="70" width="3" name="note">
	    	</div>
	    	<div>
	    		<input type="submit" class="button" value="Добавить">
	    	</div>
	    
	    </form>
	    <?php
	
		?>
    </body>
    
</html>