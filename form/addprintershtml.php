<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="../stylesheet/reset.css">
<link rel="stylesheet" type="text/css" href="../stylesheet/general.css">
<link rel="stylesheet" type="text/css" href="../stylesheet/add.css">
<title>Добавление принтера</title>
</head>

    <body>
    <?php
	include $_SERVER['DOCUMENT_ROOT'].'/html/header.html';
	?>
	    <h2 class="title"> Добавление принтера</h2>
	     <form action="?"  method="post">
	     	<div class="field">
	    		<label for="netpath"> Сетевой путь</label>
	    		<input type="text" class="text" size="70"  name="netpath">
	    	</div>
	    	<div class="field">
	    		<label for="id_ptinter" > Модель принтера</label>	    		
	    		<p><select required class="text" size="5" name="id_ptinter">
	    			<option disabled>Выберите принтер</option>
	    			<?php 
	    				$selsql='select id, name from sprinters';
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
	    		<label for="id_address" > Этаж расположения</label>	    		
	    		<p><select required class="text" size="5" name="id_address">
	    			<option disabled>Выберите объект</option>
	    			<?php 
	    				$selsql='SELECT build.name as build, floor.id as id_floor, floor.floor as floor FROM build
								RIGHT JOIN floor ON build.id = floor.id_build ORDER BY name, floor';
						$ressql=$conbd->query($selsql);
	    				while ($res=$ressql->fetch(PDO::FETCH_ASSOC))
	    				{
	    					$selsql='SELECT id, cabinet FROM cabinet WHERE id_floor='.$res['id_floor'].' ORDER BY cabinet';
	    					$rescabsql=$conbd->query($selsql);
	    					while ($rescab=$rescabsql->fetch(PDO::FETCH_ASSOC))
	    					{
	    						echo '<option value='.$rescab['id'].'>'.$res['build']. " ".$res['floor'].' этаж Кабинет "'.$rescab['cabinet'].'"</option>';
	    						
	    					}
	    					
	    					
	    				}
	    				?>
	    		</select> 
	    		</p>  	
	    	</div>
	    	<div class="field">
	    		<label for="cabinet"> Кабинет</label>
	    		<input type="text" class="text" size="70" width="3" name="cabinet">
	    	</div>
	    	<div class="field">
	    		<label for="note"> Примечание</label>
	    		<input type="text" class="text" size="70" width="3" name="note">
	    	</div>
	    	<div>
	    		<input type="submit" class="button" value="Добавить">
	    	</div>
	    
	    </form>
	 </body>    
</html>