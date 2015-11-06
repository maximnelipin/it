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
	    <h2 class="title"> <?php htmlout($pageTitle); ?></h2>
	    <form action=?<?php htmlout($action);?> method="post">	    	
		    	<div class="field">
		    		<label for="id_floor" > Номер этажа</label>
		    		<select required class="text" size="10" name="id_floor">
	    			<option disabled>Выберите этаж</option>
	    			<?php 
		    			//Получаем списки зданий, этажей
		    			$builds=getBuilds($condb);
		    			$floors=getfloors($condb);
		    			foreach ($builds as $build)
		    			{	echo '<optgroup label="'.html($build['name']).'">';
			    			foreach($floors as $floor)
			    			{	if($build['id']==$floor['id_build'])
			    				{
			    					if($floor['id']==$id_floor)
			    					{
			    						$select='selected';
			    					}
			    						
			    					else
			    					{
			    						$select='';
			    					}
			    					
			    					echo '<option '.$select.' value='.html($floor['id']).'>'.html($build['build']). " ".html($floor['floor']).' этаж </option>';
			    				}
			    			}
		    			}
	    			
						
	    				?>
	    		</select>
		    	</div>
		    	<div class="field">
		    		<label for="cabinet"> Кабинеты</label>
		    		<input type="text" class="text" size="70"  name="cabinet" required value=<?php htmloutinput($cabinet);?> >
		    	</div>		
		    	<div class="field">
		    		<label for="note"> Примечание</label>
		    		<input type="text" class="text" size="70"  name="note" value=<?php htmloutinput($note);?>>
		    	</div>	    		    	
		    	<div class="field" >
	    		<?php	include $_SERVER['DOCUMENT_ROOT'].'/html/addbutton.php';?>
	    	</div>  
	    </form>	    
    </body>    
</html>