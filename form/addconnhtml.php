<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="../stylesheet/reset.css">
<link rel="stylesheet" type="text/css" href="../stylesheet/general.css">
<link rel="stylesheet" type="text/css" href="../stylesheet/add.css">
<script src="http://code.jquery.com/jquery-2.0.3.min.js"></script>
<script src="../js_scripts/app.js"></script>


</script>
<title><?php htmlout($pageTitle); ?></title>

</head>

    <body>
    <?php
	include $_SERVER['DOCUMENT_ROOT'].'/html/header.html';
	?>
	    
	    <h2 class="title"> <?php htmlout($pageTitle); ?></h2>
	     <form action=?<?php htmlout($action);?>  method="post">
	     	<div class="field">
	    		<label for="gateway"> Шлюз ЛВС</label>
	    		<input type="text" class="text" size="70"  name="gateway"  <?php htmlout($dis);?> required value=<?php htmloutinput($gateway);?>>
	    	</div>	    	
	    	<div class="field">
	    		<label for="id_address" > Кабинет, куда подходит кабель</label>	    		
	    		<select required class="text" size="5" name="id_address">
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
	    		<label for="id_operator"> Оператор связи</label>
	    		<select required class="text" size="5" name="id_operator">
	    			<option disabled>Выберите оператора</option>
	    			<?php 
	    				$selsql='SELECT id, name FROM isp';
						$ressql=$condb->query($selsql);
	    				while ($res=$ressql->fetch(PDO::FETCH_ASSOC))
	    				{    					
	    						
	    					if($res['id']==$id_operator)
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
	    		<label for="id_extnet" > Параметры внешнего подключения</label>
	    		<div class="radio">
		    		Выбрать <input type="radio" class="text"  name="radextnet" value="sel" checked onClick=selextnet();>
		    		Ввести <input type="radio" class="text"  name="radextnet" value="add" onClick=addextnet();>
	    		</div>
	    		<div id="selextnet"  class="divon">
		    		<select  class="text"  size="5" name="id_extnet" id="id_extnet">
		    		<option disabled>Выберите параметры</option>
		    		<?php 
		    				$selsql='SELECT id, extip FROM extnet order by extip';
							$ressql=$condb->query($selsql);
		    				while ($res=$ressql->fetch(PDO::FETCH_ASSOC))
		    				{    					
		    						
		    					if($res['id']==$id_extnet)
		    					{
		    						
		    						$select='selected';
		    					}
		    						
		    					else
		    					{
		    						$select='';
		    					}
		    					echo '<option '.$select.' value='.$res['id'].'>'.$res['extip'].'</option>';
		    						
		    				}
		    				?>
	    				</select> 
	    		</div>
	    		<div id="addextnet"  class="divoff">
	    			<div class="field">
			    		<label for="extip"> Внешний IP-адрес</label>
			    		<input type="text" class="text" size="70" width="3" name="extip"  id="extip" >
	    			</div>
	    			<div class="field">
			    		<label for="extmask"> Внешняя маска</label>
			    		<input type="text" class="text" size="70" width="3" name="extmask">
	    			</div>
	    			<div class="field">
			    		<label for="extgw"> Внешний шлюз</label>
			    		<input type="text" class="text" size="70" width="3" name="extgw">
	    			</div>
	    			<div class="field">
			    		<label for="extdns1"> Внешний первый DNS</label>
			    		<input type="text" class="text" size="70" width="3" name="extdns1">
	    			</div>
	    			<div class="field">
			    		<label for="extdns2"> Внешний второй DNS</label>
			    		<input type="text" class="text" size="70" width="3" name="extdns2">
	    			</div>
	    			
	    		</div>
	    	</div>
	    	<div class="field">
	    		<label for="typecon"> Тип подключения:DSL/IP/оптика и т.д.</label>
	    		<input type="text" class="text" size="70" width="3" name="typecon" value=<?php htmloutinput($typecon);?>>
	    	</div>
	    	<div class="field">
	    		<label for="mask"> Маска подсети ЛВС</label>
	    		<input type="text" class="text" size="70" width="3" name="mask" value=<?php htmloutinput($mask);?>>
	    	</div>
	    	<div class="field">
	    		<label for="dhcp"> DHCP-сервер ЛВС</label>
	    		<input type="text" class="text" size="70" width="3" name="dhcp" value=<?php htmloutinput($dhcp);?>>
	    	</div>
	    	<div class="field">
	    		<label for="dns1"> Первый DNS-сервер ЛВС</label>
	    		<input type="text" class="text" size="70" width="3" name="dns1" value=<?php htmloutinput($dns1);?>>
	    	</div>
	    	<div class="field">
	    		<label for="dns2"> Второй DNS-сервер ЛВС</label>
	    		<input type="text" class="text" size="70" width="3" name="dns2" value=<?php htmloutinput($dns2);?>>
	    	</div>
	    	<div class="field">
	    		<label for="id_ppp" > Параметры протокола PPP</label>
	    		<div class="radio">
		    		Выбрать <input type="radio" class="text"  name="radppp" value="sel" checked onClick=selppp();>
		    		Ввести <input type="radio" class="text"  name="radppp" value="add" onClick=addppp();>
	    		</div>
	    		<div id="selppp"  class="divon">
		    		<select class="text" size="5" name="id_ppp" id='id_ppp'>
		    		<option selected value='none'>PPP Отсутствует</option>
		    		<?php 
		    				$selsql='SELECT id, srv,typeppp FROM ppp order by srv';
							$ressql=$condb->query($selsql);
		    				while ($res=$ressql->fetch(PDO::FETCH_ASSOC))
		    				{    					
		    						
		    					if($res['id']==$id_ppp)
		    					{
		    						//$select='selected';
		    						$select='selected';
		    					}
		    						
		    					else
		    					{
		    						$select='';
		    					}
		    					'<option '.$select.' value='.$res['id'].'>'.$res['srv'].' '.$res['typeppp'].'</option>';
		    						
		    				}
		    				?>
	    				</select> 
	    		</div>
	    		<div id="addppp"  class="divoff">
	    			<div class="field">
			    		<label for="typeppp"> Тип PPP</label>
			    		<input type="text" class="text" size="70" width="3" name="typeppp">
	    			</div>
	    			
	    			<div class="field">
			    		<label for="srv"> Сервер PPP</label>
			    		<input type="text" class="text" size="70" width="3" name="srv" id="srv">
	    			</div>
	    			<div class="field">
			    		<label for="login"> Логин PPP</label>
			    		<input type="text" class="text" size="70" width="3" name="login">
	    			</div>
	    			<div class="field">
			    		<label for="pwd"> Пароль PPP </label>
			    		<input type="text" class="text" size="70" width="3" name="pwd">
	    			</div>
			    	
	    			
	    		</div>
	    	</div>
	    	<div class="field">
	    		<label for="loginlk"> Логин личного кабинета</label>
	    		<input type="text" class="text" size="70" width="3" name="loginlk" value=<?php htmloutinput($loginlk);?>>
	    	</div>
	    	<div class="field">
	    		<label for="pwdlk"> Пароль личного кабинета</label>
	    		<input type="text" class="text" size="70" width="3" name="pwdlk" value=<?php htmloutinput($pwdlk);?>>
	    	</div>
	    	<div class="field">
	    		<label for="id_company" > Компания</label>
	    		<div class="radio">
		    		Выбрать <input type="radio" class="text"  name="radcomp" value="sel" checked onClick=selcomp();>
		    		Ввести <input type="radio" class="text"  name="radcomp" value="add" onClick=addcomp();>
	    		</div>
	    		<div id="selcomp"  class="divon">
		    		<select  class="text" size="5" name="id_company" id='id_company'>
		    		<option disabled>Выберите параметры</option>
		    		<?php 
		    				$selsql='SELECT id, name FROM company order by name';
							$ressql=$condb->query($selsql);
		    				while ($res=$ressql->fetch(PDO::FETCH_ASSOC))
		    				{    					
		    					if($res['id']==$id_company)
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
	    		<div id="addcomp"  class="divoff">
	    			<div class="field">
			    		<label for="name"> Название компании</label>
			    		<input type="text" class="text" size="70" width="3" name="name" id="name">
	    			</div>
	    			
	    			<div class="field">
			    		<label for="innkpp"> ИНН/КПП Компании</label>
			    		<input type="text" class="text" size="70" width="3" name="innkpp">
	    			</div>
	    			
	    			
	    		</div>
	    	</div>
	    	<div class="field">
	    		<label for="contract"> Номер договора</label>
	    		<input type="text" class="text" size="70" width="3" name="contract" value=<?php htmloutinput($contract);?>>
	    	</div>	    	
	    	<div class="field">
	    		<label for="note"> Примечание</label>
	    		<input type="text" class="text" size="70" width="3" name="note" value=<?php htmloutinput($note);?>>
	    	</div>
	    	<div>
	    		<input type="submit" class="button" value="Добавить">
	    	</div>   
	    
	    
	    
	    </form>
	 </body>    
</html>