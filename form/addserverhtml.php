<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="../stylesheet/reset.css">
<link rel="stylesheet" type="text/css" href="../stylesheet/general.css">
<link rel="stylesheet" type="text/css" href="../stylesheet/add.css">
<title><?php htmlout($pageTitle); ?></title>
</head>

    <body>
    <?php
	include $_SERVER['DOCUMENT_ROOT'].'/html/header.html';
	?>
	    <h2 class="title"><?php htmlout($pageTitle); ?></h2>
	     <form action=?<?php htmlout($action);?>  method="post">
	     	<div class="field">
	    		<label for="name"> Сетевое имя или ip-адрес серера</label>
	    		<input type="text" class="text" size="70"  name="name" value=<?php htmloutinput($name);?> required <?php htmlout($dis);?>>
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
	    						if($rescab['id']==$id_cabinet)
	    						{
	    							//$select='selected';
	    							$select='selected';
	    						}
	    						
	    						else 
	    						{
	    							$select='';
	    						}
	    						
	    						echo '<option '.$select.' value='.$rescab['id'].'>'.$res['build']. " ".$res['floor'].' этаж Кабинет "'.$rescab['cabinet'].'"</option>';
	    					}
	    					
	    					
	    				}
	    				?>
	    		</select>  	
	    	</div>
	    	<div class="field">
	    		<label for="type"> Тип: физический, виртуальный и т.д.</label>
	    		<input type="text" class="text" size="70" width="3" name="type" value=<?php htmloutinput($type);?>>
	    	</div>
	    	<div class="field">
	    		<label for="descrip"> Выполняемая функция</label>
	    		<input type="text" class="text" size="70" width="3" name="descrip" required value=<?php htmloutinput($descrip);?>>
	    	</div>
	    	<div class="field">
	    		<label for="phys"> Модель аппаратуры</label>
	    		<input type="text" class="text" size="70" width="3" name="phys" required value=<?php htmloutinput($phys);?>>
	    	</div>
	    	<div class="field">
	    		<label for="rack"> Номер стойки в серверной</label>
	    		<input type="text" class="text" size="70" width="3" name="rack" required value=<?php htmloutinput($rack);?>>
	    	</div>
	    	<div class="field">
	    		<label for="units"> Номер(а) юнитов в стойке</label>
	    		<input type="text" class="text" size="70" width="3" name="units" required value=<?php htmloutinput($units);?>>
	    	</div>
	    	<div class="field">
	    		<label for="login"> Ответственный</label>
	    		<select required class="text" size="5" name="login" >
	    			<option disabled>Выберите объект</option>
	    			<?php 
	    				$selsql='SELECT login, fio FROM itusers order by fio';
						$ressql=$condb->query($selsql);
	    				while ($res=$ressql->fetch(PDO::FETCH_ASSOC))
	    				{    					
	    					if(strcasecmp($res['login'],$login)==0)
	    					{
	    						
	    						$select='selected';
	    					}
	    					else
	    					{
	    						$select='';
	    					}	
	    					
	    					echo '<option '.$select.' value='.$res['login'].'>'.$res['fio'].'</option>';
	    						
	    				}
	    				?>
	    		</select>  	
	    	</div>
	    	<div class="field">
	    		<label for="note"> Примечание</label>
	    		<input type="text" class="text" size="70" width="3" name="note" value=<?php htmloutinput($note);?>>
	    	</div>
	    	<div>
	    		<input type="hidden" name="id" value=<?php htmlout($id);?>>
	    		<input type="submit" class="button" value=<?php htmlout($button);?>>
	    		<input type="button" class="button" value="Назад" onClick=<?php echo 'location.replace("http://'.$_SERVER["HTTP_HOST"].$_SERVER["PHP_SELF"].'");'?>>
	    	
	    	</div>
	    
	    </form>
	 </body>    
</html>