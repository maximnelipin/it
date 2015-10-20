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
	    <h2 class="title"> <?php htmlout($pageTitle); ?></h2>
	    <form action=?<?php htmlout($action);?> method="post">	    	
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
	    					if($res['id_floor']==$id_floor)
	    					{
	    						//$select='selected';
	    						$select='selected';
	    					}
	    						
	    					else
	    					{
	    						$select='';
	    					}
	    					
	    					echo '<option '.$select.' value='.$res['id_floor'].'>'.$res['build']. " ".$res['floor'].' этаж </option>';
	    					    					
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
		    		<input type="hidden" name="id" value=<?php htmlout($id);?>>
	    			<input type="submit" class="button" value=<?php htmlout($button);?>>
	    			<input type="button" class="button" value="Назад" onClick=<?php echo 'location.replace("http://'.$_SERVER["HTTP_HOST"].$_SERVER["PHP_SELF"].'");'?>>
		    	</div>	 	  
	    </form>
	    
    </body>
    
</html>