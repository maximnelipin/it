<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="../stylesheet/reset.css">
<link rel="stylesheet" type="text/css" href="../stylesheet/general.css">
<link rel="stylesheet" type="text/css" href="../stylesheet/add.css">


<title><?php htmlout($pageTitle); ?></title>
</head>
    <body >
	<?php	include $_SERVER['DOCUMENT_ROOT'].'/html/header.php';	?>
	    <h2 class="title"> <?php htmlout($pageTitle); ?></h2>
	     <form action=?<?php htmlout($action);?>  method="post">
	     	<div class="field">
	    		<label for="number"> Номер, 10 цифр, без 8</label>
	    		<input type="text" class="text" size="70"  name="number" value=<?php htmloutinput($number);?> 
	    		required <?php htmlout($dis);?> pattern="[0-9]{10}" >
	    	</div>
	    	<div class="field">
	    		<label for="account" > Лицевой счёт</label>	    		
	    		<input type="text" class="text" size="70"  name="account" value=<?php htmloutinput($account);?>>	
	    	</div>
	    	<div class="field">
	    		<label for="id_address" > Адрес расположения</label>	    		
	    		<p><select required class="text" size="5" name="id_address">
	    			<option disabled>Выберите объект</option>
	    			<?php 
		    			$ressql=getBuilds($condb);
		    			if((gettype($ressql)=='array'))
		    			{
		    				foreach($ressql as $res)
		    				{
		    					 
		    					if($res['id']==$id_address)
		    					{
		    			
		    						$select='selected';
		    					}
		    					else
		    					{
		    						$select='';
		    					}
		    					echo '<option '.$select.' value='.html($res['id']).'>'.html($res['name']).'</option>';
		    				}
		    			}
	    			?>
	    		</select> 
	    		</p>  	
	    	</div>
	    	<div class="field">
	    		<label for="id_operator" > Оператор связи</label>	    		
	    		<p><select required class="text" size="5" name="id_operator">
	    			<option disabled>Выберите оператора</option>
	    			<?php 
	    				$ressql=getIsps($condb);
						if((gettype($ressql)=='array'))
						{
							foreach($ressql as $res)
							{
			    				if($res['id']==$id_operator)
			    				{
			    					$select='selected';
			    				}
			    				else
			    				{
			    					$select='';
			    				}	
	    						echo '<option '.$select.' value='.html($res['id']).'>'.html($res['name']).'</option>';
							}
						}
	    			?>
	    		</select> 
	    		</p>  	
	    	</div>
	    	
	    	<div class="field">
	    		<label for="login" > Пользователь</label>	    		
	    		<p><select required class="text" size="5" name="login">
	    			<option disabled>Выберите пользователя</option>
	    			<?php 
						$ressql=getUsers($condb);
						if((gettype($ressql)=='array'))
						{
		    				foreach($ressql as $res)
		    				{    					
		    					if(strcasecmp($res['login'],$login)==0)
		    					{
		    						
		    						$select='selected';
		    					}
		    					else
		    					{
		    						$select='';
		    					}			    					
		    					echo '<option '.$select.' value='.html($res['login']).'>'.html($res['fio']).'</option>';		    						
		    				}
						}
	    				?>
	    		</select> 
	    		</p>  	
	    	</div>
	    	<div class="field">
	    		<label for="balance"> Баланс</label>
	    		<input type="text" class="text" size="70" width="3" name="balance" value=<?php htmloutinput($balance);?>>
	    	</div>
	    	<div class="field">
	    		<label for="pay"> Ежемесячная плата</label>
	    		<input type="text" class="text" size="70" width="3" name="pay" value=<?php htmloutinput($pay);?>>
	    	</div>
	    	
	    	<div class="field">
	    		<label for="pwdlk"> Пароль личного кабинета</label>
	    		<input type="text" class="text" size="70" width="3" name="pwdlk" value=<?php htmloutinput($pwdlk);?>>
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