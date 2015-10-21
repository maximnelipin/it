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
	    				$selsql='select id, name from sprinters order by name';
						$ressql=$condb->query($selsql);
	    				while ($res=$ressql->fetch(PDO::FETCH_ASSOC))
	    				{
	    					if($res['id']==$id_printer)
	    					{
	    						//$select='selected="true"  onBlur="if(n==0) {this.selected=false; n=1}"';
	    						$select='selected';
	    					}
	    					else
	    					{
	    						$select='';
	    					}
	    					echo '<option '.$select.'  value='.$res['id'].'>'.$res['name'].' </option>';
	    				}
	    				?>
	    		</select> 
	    		</p>  	
	    	</div>
	    	<div class="field">
	    		<label for="id_address" > Кабинет расположения</label>	    		
	    		<p><select required class="text" size="5" name="id_cabinet">
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
	    		</p>  	
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