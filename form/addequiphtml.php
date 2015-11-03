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
    <?php	include $_SERVER['DOCUMENT_ROOT'].'/html/header.php';	?>
	    <h2 class="title"><?php htmlout($pageTitle); ?></h2>
	     <form action=?<?php htmlout($action);?>  method="post">	     	    	
	    	<div class="field">
	    		<label for="id_cabinet" > Кабинет расположения</label>	    		
	    		<p><select required class="text" size="10" name="id_cabinet">
	    			<option disabled>Выберите Кабинет</option>
	    			<?php 
	    				//Получаем списки зданий, этажей, кабинетов
	    				$builds=getBuilds($condb);
	    				$floors=getfloors($condb);
	    				$cabs=getCabs($condb);
	    				
	    				foreach ($builds as $build)
	    				{	echo '<optgroup label="'.html($build['name']).'">';
	    					foreach($floors as $floor)
	    					{	if($build['id']==$floor['id_build'])
	    						{
		    						echo '<optgroup label= "Этаж '.html($floor['floor']).'">';
		    						foreach ($cabs as $cab)
		    						{	if($floor['id']==$cab['id_floor'])
			    						{
			    							if($cab['id']==$id_cabinet)
			    							{
			    								$select='selected';
			    							}
			    							else
			    							{
			    								$select='';
			    							}
			    							
			    							echo '<option '.$select.' value='.html($cab['id']).'>'.html($cab['cabinet']).'</option>';
			    						}					
		    							
		    						}
	    							
	    						}
	    						echo '</optgroup>';
	    					}
	    					echo '</optgroup>';
	    				}
	    			?>
	    		</select>  	
	    	</div>
	    	<div class="field">
	    		<label for="ip"> IP-адрес физического сервера</label>
	    		<input type="text" class="text" size="70"  name="ip" value=<?php htmloutinput($ip);?> required <?php htmlout($dis);?>>
	    	</div>	 
	    	<div class="field">
	    		<label for="phys"> Модель сервера</label>
	    		<input type="text" class="text" size="70"  name="phys" value=<?php htmloutinput($phys);?> required <?php htmlout($dis);?>>
	    	</div>	    		    	
	    	<div class="field">
	    		<label for="rack"> Номер стойки в серверной</label>
	    		<input type="text" class="text" size="70" width="3" name="rack" required value=<?php htmloutinput($rack);?>>
	    	</div>
	    	<div class="field">
	    		<label for="unit"> Номер(а) юнитов в стойке</label>
	    		<input type="text" class="text" size="70" width="3" name="unit" required value=<?php htmloutinput($unit);?>>
	    	</div>	    	
	    	<div class="field">
	    		<label for="note"> Примечание</label>
	    		<input type="text" class="text" size="70" width="3" name="note" value=<?php htmloutinput($note);?>>
	    	</div>
	    	<div class="field" >
	    		<?php	include $_SERVER['DOCUMENT_ROOT'].'/html/addbutton.php';?>
	    	</div>	    
	    </form>
	 </body>    
</html>