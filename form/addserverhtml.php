<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="../stylesheet/reset.css">
<link rel="stylesheet" type="text/css" href="../stylesheet/general.css">
<link rel="stylesheet" type="text/css" href="../stylesheet/add.css">
<title>Добавление сервера</title>
</head>

    <body>
    <?php
	include $_SERVER['DOCUMENT_ROOT'].'/html/header.html';
	?>
	    <h2 class="title"> Добавление сервера</h2>
	     <form action="?"  method="post">
	     	<div class="field">
	    		<label for="name"> Сетевое имя или ip-адрес серера</label>
	    		<input type="text" class="text" size="70"  name="name">
	    	</div>
	    	
	    	</div>
	    	<div class="field">
	    		<label for="id_cabinet" > Кабинет расположения</label>	    		
	    		<select required class="text" size="5" name="id_cabinet">
	    			<option disabled>Выберите объект</option>
	    			<?php 
	    				$selsql='SELECT build.name as build, floor.id as id_floor, floor.floor as floor FROM build
								RIGHT JOIN floor ON build.id = floor.id_build ORDER BY name, floor';
						$ressql=$condb->query($selsql);
	    				while ($res=$ressql->fetch(PDO::FETCH_ASSOC))
	    				{
	    					$selsql='SELECT id, cabinet FROM cabinet WHERE id_floor='.$res['id_floor'].' ORDER BY cabinet';
	    					$rescabsql=$condb->query($selsql);
	    					while ($rescab=$rescabsql->fetch(PDO::FETCH_ASSOC))
	    					{
	    						echo '<option value='.$rescab['id'].'>'.$res['build']. " ".$res['floor'].' этаж Кабинет "'.$rescab['cabinet'].'"</option>';
	    						
	    					} 					
	    					
	    				}
	    				?>
	    		</select>  	
	    	</div>
	    	<div class="field">
	    		<label for="type"> Тип: физический, виртуальный и т.д.</label>
	    		<input type="text" class="text" size="70" width="3" name="type">
	    	</div>
	    	<div class="field">
	    		<label for="descrip"> Выполняемая функция</label>
	    		<input type="text" class="text" size="70" width="3" name="descrip">
	    	</div>
	    	<div class="field">
	    		<label for="phys"> Модель аппаратуры</label>
	    		<input type="text" class="text" size="70" width="3" name="phys">
	    	</div>
	    	<div class="field">
	    		<label for="rack"> Номер стойки в серверной</label>
	    		<input type="text" class="text" size="70" width="3" name="rack">
	    	</div>
	    	<div class="field">
	    		<label for="units"> Номер(а) юнитов в стойке</label>
	    		<input type="text" class="text" size="70" width="3" name="units">
	    	</div>
	    	<div class="field">
	    		<label for="login"> Ответственный</label>
	    		<select required class="text" size="5" name="login">
	    			<option disabled>Выберите объект</option>
	    			<?php 
	    				$selsql='SELECT login, fio FROM itusers';
						$ressql=$condb->query($selsql);
	    				while ($res=$ressql->fetch(PDO::FETCH_ASSOC))
	    				{    					
	    						echo '<option value='.$res['login'].'>'.$res['fio'].'</option>';
	    						
	    				}
	    				?>
	    		</select>  	
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