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
		    		<label for="id_build" > Название объекта</label>
		    		<p><select required class="text" size="5" name="id_build">
	    			<option disabled>Выберите объект</option>
	    			<?php 
	    				$selsql='SELECT name, id FROM build ORDER BY name';
						$ressql=$condb->query($selsql);
	    				while ($res=$ressql->fetch(PDO::FETCH_ASSOC))
	    				{
	    					if($res['id']==$id_build)
	    					{
	    						//$select='selected';
	    						$select='selected';
	    					}
	    						
	    					else
	    					{
	    						$select='';
	    					}
	    					
	    					echo '<option '.$select.' value='.$res['id'].'>'.$res['name'].'</option>';
	    				}
	    				?>
	    		</select>
		    	</div>
		    	<div class="field">
		    		<label for="floor"> Номера этажей</label>
		    		<input type="text" class="text" size="70"  name="floor" required value=<?php htmloutinput($floor);?>>
		    	</div>
		    	<div class="field">
		    		<label for="cabinet"> Кабинеты</label>
		    		<input type="text" class="text" size="70"  name="cabinet" <?php htmlout($dis);?> <?php htmlout($req);?>>
		    	</div>
		    	<div class="field">
		    		<label for="note"> Примечание</label>
		    		<input type="text" class="text" size="70"  name="note" value=<?php htmloutinput($note);?>>
		    	</div>		    		    	
		    	<div class="field" >
	    		<?php	include $_SERVER['DOCUMENT_ROOT'].'/html/addbutton.php';?>
	    	</div>	 	  
	    </form>
	    <?php
	
		?>
    </body>
    
</html>