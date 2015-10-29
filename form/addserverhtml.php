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
	include $_SERVER['DOCUMENT_ROOT'].'/html/header.php';
	?>
	    <h2 class="title"><?php htmlout($pageTitle); ?></h2>
	     <form action=?<?php htmlout($action);?>  method="post">
	     	<div class="field">
	    		<label for="name"> Сетевое имя или ip-адрес серера</label>
	    		<input type="text" class="text" size="70"  name="name" value=<?php htmloutinput($name);?> required <?php htmlout($dis);?>>
	    	</div>
	    	
	    	
	    	<div class="field">
	    		<label for="id_equip" > Оборудование</label>	    		
	    		<select required multiple class="text" size="5" name="id_equip[]">
	    			<option disabled>Выберите объект</option>
	    			<?php 
	    				
		    			$selsql='SELECT equip.id, eqsrv.id AS id_eqsrv, eqsrv.id_srv AS id_srv, equip.phys, equip.ip, build.name, floor.floor, cabinet.cabinet, equip.rack, equip.unit
		    			FROM eqsrv
		    			RIGHT JOIN equip ON equip.id = eqsrv.id_equip
		    			LEFT JOIN cabinet ON cabinet.id = equip.id_cabinet
		    			LEFT JOIN floor ON cabinet.id_floor = floor.id
		    			LEFT JOIN build ON floor.id_build = build.id order by build.name, floor.floor, cabinet.cabinet, equip.rack, equip.unit, equip.ip, equip.phys ';
		    			$ressql=$condb->query($selsql);    				
	    				
	    				while ($res=$ressql->fetch(PDO::FETCH_ASSOC))
	    				{
	    						$select='';
	    						if($id!=''){
	    						if($res['id_srv']==$id)
	    						{
	    							//$select='selected';
	    							$select='selected';
	    							
	    						}
	    						
	    						else 
	    						{
	    							$select='';
	    						}
	    						//неолбходимо id оборудование, чтобы сделать привязку в таблице eqsrv
	    						}
	    						echo '<option '.$select.' value='.$res['id'].'>'.$res['name']. " ".$res['floor'].'-'.$res['cabinet'].'-'.$res['rack'].'-'.$res['unit'].'-'.$res['ip'].'-'.$res['phys'].'</option>';
	    					
	    						
	    					
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
	    	<div class="field" >
	    		<input type="hidden" name="id" value=<?php htmlout($id);?>>
	    		<input type="submit" class="button" value=<?php htmlout($button);?>>
	    		<input type="button" class="button" value="Назад" onClick=<?php echo 'location.replace("http://'.$_SERVER["HTTP_HOST"].$_SERVER["PHP_SELF"].'");'?>>
	    	
	    	</div>
	    
	    </form>
	 </body>    
</html>