<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="../stylesheet/reset.css">
<link rel="stylesheet" type="text/css" href="../stylesheet/general.css">
<link rel="stylesheet" type="text/css" href="../stylesheet/add.css">
<title>Добавление сим-карты</title>
</head>

    <body>
    <?php
	include $_SERVER['DOCUMENT_ROOT'].'/html/header.html';
	?>
	    <h2 class="title"> Добавление сим-карты</h2>
	     <form action="?"  method="post">
	     	<div class="field">
	    		<label for="number"> Номер</label>
	    		<input type="text" class="text" size="70"  name="number">
	    	</div>
	    	<div class="field">
	    		<label for="account" > Лицевой счёт</label>	    		
	    		<input type="text" class="text" size="70"  name="account">	
	    	</div>
	    	<div class="field">
	    		<label for="id_address" > Адрес расположения</label>	    		
	    		<p><select required class="text" size="5" name="id_address">
	    			<option disabled>Выберите объект</option>
	    			<?php 
	    				$selsql='SELECT name, id FROM build ORDER BY name';
						$ressql=$conbd->query($selsql);
	    				while ($res=$ressql->fetch(PDO::FETCH_ASSOC))
	    				{
	    					echo '<option value='.$res['id'].'>'.$res['name'].'</option>';
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
	    				$selsql='SELECT id, name FROM isp ORDER BY name';
						$ressql=$conbd->query($selsql);
	    				while ($res=$ressql->fetch(PDO::FETCH_ASSOC))
	    				{
	    					echo '<option value='.$res['id'].'>'.$res['name'].'</option>';
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
	    				$selsql='SELECT login, name FROM listuser ORDER BY name';
						$ressql=$conbd->query($selsql);
	    				while ($res=$ressql->fetch(PDO::FETCH_ASSOC))
	    				{
	    					echo '<option value='.$res['login'].'>'.$res['login'].'</option>';
	    				}
	    				?>
	    		</select> 
	    		</p>  	
	    	</div>
	    	
	    	<div class="field">
	    		<label for="pwdlk"> Пароль личного кабинета</label>
	    		<input type="text" class="text" size="70" width="3" name="pwdlk">
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