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
	    <h2 class="title"> <?php htmlout($pageTitle); ?></h2>
	     <form action=?<?php htmlout($action);?>  method="post">
	     	<div class="field">
	    		<label for="netpath"> Сетевой путь</label>
	    		<input type="text" class="text" size="70"  name="netpath" value=<?php htmloutinput($netpath);?> required >
	    	</div>
	    	<div class="field">
	    		<label for="id_printer" > Модель принтера</label>	    		
	    		<p><select required class="text" size="5" name="id_printer" >
	    			<option disabled>Выберите принтер</option>
	    			<?php 
	    				$ressql=getModelprints($condb);
						if((gettype($ressql)=='array'))
						{
							foreach($ressql as $res)
							{
			    				if($res['id']==$id_printer)
			    				{
			    					$select='selected';
			    				}
			    				else
			    				{
			    					$select='';
			    				}
	    						echo '<option '.$select.'  value='.$res['id'].'>'.$res['name'].' </option>';
							}
	    				}
	    			?>
	    		</select> 
	    		</p>  	
	    	</div>
	    	<div class="field">
	    		<label for="id_address" > Кабинет расположения</label>	    		
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
	    		</p>  	
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