<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="../stylesheet/reset.css">
<link rel="stylesheet" type="text/css" href="../stylesheet/general.css">
<link rel="stylesheet" type="text/css" href="../stylesheet/main.css">
<title>Главная</title>
</head>

    <body>
    <?php
	include_once $_SERVER['DOCUMENT_ROOT'].'/html/header.php';
	include_once $_SERVER['DOCUMENT_ROOT'].'/php_scripts/func.php';
	?>
	    <h2 class="title"> Главная страница</h2>
	    <div class="repmain">
	   		<div class="mainleft">
			   <form action="schedule.php"  method="get" target="_blank" id="sched">
			    	<div >
			    		<label for="monyear" > Дежурства</label>
			    		<select required class="text" size="1" name="monyear">
			    			<option disabled selected>Выберите месяц</option>
			    			<?php 
				    			try
				    			{
				    				$sql='SELECT Distinct month(dateduty) as month, year(dateduty) as year FROM schedule
											ORDER BY year, month LIMIT 50';
				    				$sqlprep=$condb->prepare($sql);
				    				$sqlprep->execute();
				    			}
				    			catch (PDOExeption $e)
				    			{
				    				include '../form/errorhtml.php';
				    				exit;
				    			}
				    			if($sqlprep->rowCount()>0)
				    			{
			    					$result=$sqlprep->fetchall();
								
								
			    					foreach ($result as $res)
				    				{
				    				
				    					echo '<option value='.html($res['month']).','.html($res['year']).'>'.html(numToMonth($res['month'])). " ".html($res['year']).'</option>';
				    					    					
				    				}
				    			}
			    				?>
			    		</select> 				    	
				    	<div>
					    	<input type="submit" class="button" size="70" name="schedrep"  value="В HTML">
					    	<input type="submit" class="button" size="70" name="schedpdf"  value="В PDF">	    	    	   
					   	</div>
			    	</div>
			    </form> 
			    <form action="conn.php"  method="get" target="_blank" id="conn">
			    	<div >
			    		<label for="gwlan" > ЛВС</label>
			    		<select required class="text" size="1" name="gwlan">
			    			<option  selected value='all'> Все ЛВС</option>
			    			<?php 								
				    			try
				    			{
				    				$sql='SELECT id, gateway FROM conn ORDER BY gateway LIMIT 50';
				    				$sqlprep=$condb->prepare($sql);
				    				$sqlprep->execute();
				    			}
				    			catch (PDOExeption $e)
				    			{
				    				include '../form/errorhtml.php';
				    				exit;
				    			}
				    			if($sqlprep->rowCount()>0)
				    			{
				    				$result=$sqlprep->fetchall();
				    			
				    			
				    				foreach ($result as $res)
				    				{
				    					echo '<option value='.html($res['id']).'>'.html($res['gateway']).'</option>';				    			
				    				}
				    			}	
			    			?>
			    		</select> 	
			    	
				    	<div>
					    	<input type="submit" class="button" size="70" name="conn"  value="Отчёт">
					    	<input type="submit" class="button" size="70" name="ping" value="Пинг">	    	    	   
					   	</div>
			    	</div>
			    </form> 
			    <form action=""  method="get" target="_blank" id="ispsim">
			    	<div >
			    		<label for="ispsim" > Сим-карты</label>
			    		<select required class="text" size="1" name="ispsim">
			    			<option  selected value='all'> Все операторы</option>
			    			<?php 
				    			try
				    			{
				    				$sql='SELECT DISTINCT isp.id, isp.name
										FROM isp
										RIGHT JOIN sim ON isp.id = sim.id_operator
										ORDER BY isp.name
										LIMIT 50';
				    				$sqlprep=$condb->prepare($sql);
				    				$sqlprep->execute();
				    			}
				    			catch (PDOExeption $e)
				    			{
				    				include '../form/errorhtml.php';
				    				exit;
				    			}
				    			if($sqlprep->rowCount()>0)
				    			{
				    				$result=$sqlprep->fetchall();
				    				 
				    				 
				    				foreach ($result as $res)
				    				{
				    					echo '<option value='.html($res['id']).'>'.html($res['name']).'</option>';
				    				}
				    			}								
			    			?>
			    		</select> 	
			    	
				    	<div>
					    	<input type="submit" class="button" size="70" name="simrep" onClick="document.getElementById('ispsim').action = 'sim.php'" value="Отчёт">
					    	<input type="submit" class="button" size="70" name="simpdf" onClick="document.getElementById('ispsim').action = 'sim.php'" value="В PDF">	    	    	   
					   	</div>
			    	</div>
			    </form> 
			    <form action="isp.php"  method="get" target="_blank" id="isp">
			    	<div >
			    		<label for="isp" > Операторы связи</label>
			    		<select required class="text" size="1" name="isp">
			    			<option  selected value='all'> Все операторы</option>			    				   
			    			<?php 								
								$ressql=getIsps($condb);
								if((gettype($ressql)=='array'))
								{
									foreach($ressql as $res)
									{
										echo '<option value='.html($res['id']).'>'.html($res['name']).'</option>';
									}
								}
			    			?>
			    		</select> 	
			    	
				    	<div>
					    	<input type="submit" class="button" size="70"  value="Отчёт">	    	    	   
					   	</div>
			    	</div>
			    </form> 
		    </div> 
		    <div class="maincenter">
			    <form action="usrpc.php"  method="get" target="_blank" >
			    	<div >
			    		<label for="usr" > Пользователи</label>
			    		<select required class="text" size="1" name="usr">
			    			<option selected value="all">Все Пользователи - Все ПК</option>
			    			<?php 								
								$ressql=getUsers($condb);
								if((gettype($ressql)=='array'))
								{
				    				foreach($ressql as $res)
				    				{    					
				    					echo '<option  value='.html($res['login']).'>'.html($res['fio']).'</option>';		    						
				    				}
								}
			    			?>
			    		</select> 	
			    	
				    	<div>
				    		<input type="submit" class="button" size="70"   value="Отчёт">    	    	   
				   		</div>
			    	</div>
			    </form> 
			     <form action="build.php"  method="get" target="_blank" >
			    	<div >
			    		<label for="build" > Здания</label>
			    		<select required class="text" size="1" name="build">
			    			<option disabled selected value="all">Выберите здание</option>
			    			<?php 								
								$ressql=getBuilds($condb);
								if((gettype($ressql)=='array'))
								{
				    				foreach($ressql as $res)
				    				{  
			    						echo '<option value='.html($res['id']).'>'.html($res['name']).'</option>';
				    				}
			    					    					
			    				}
			    			?>
			    		</select> 	
			    	
				    	<div>
				    		<input type="submit" class="button" size="70"   value="Отчёт">    	    	   
				   		</div>
			    	</div>
			    </form>
			    <form action="printers.php"  method="get" target="_blank" id="printers">
			    	<div >
			    		<label for="printers" > МФУ/Принтеры</label>
			    		<select required class="text" size="1" name="printers">
			    			<option  selected value='all'> Все МФУ/Принтеры</option>
			    			<?php 
								$ressql=getModelprints($condb);
								if((gettype($ressql)=='array'))
								{
									foreach($ressql as $res)
									{
										echo '<option value='.html($res['id']).'>'.html($res['name']).'</option>';
									}
								}
			    			?>
			    		</select> 	
			    	
				    	<div>
					    	<input type="submit" class="button" size="70"   value="Отчёт">	    	    	   
					   	</div>
			    	</div>
			    </form>  
			      <form action="servers.php"  method="get" target="_blank" id="servers">
			    	<div >
			    		<label for="servers" > Сервера</label>
			    		<select required class="text" size="1" name="servers">
			    			<option  selected value='all'> Все сервера</option>
			    			<?php 
				    			try
				    			{
				    				$sql='SELECT id, name FROM servers ORDER BY name LIMIT 50';
				    				$sqlprep=$condb->prepare($sql);
				    				$sqlprep->execute();
				    			}
				    			catch (PDOExeption $e)
				    			{
				    				include '../form/errorhtml.php';
				    				exit;
				    			}
				    			if($sqlprep->rowCount()>0)
				    			{
				    				$result=$sqlprep->fetchall();
				    				 
				    				 
				    				foreach ($result as $res)
				    				{
				    					echo '<option value='.html($res['id']).'>'.html($res['name']).'</option>';
				    				}
				    			}								
			    			?>	
			    		</select> 			    	
				    	<div>
					    	<input type="submit" class="button" size="70"  value="Отчёт">	    	    	   
					   	</div>
			    	</div>
			    </form>  
		    </div>
			<div class="mainright">
				<div>
				    <a href=gpo.php target="_blank"> GPO </a>
				</div>
				<div>
				    <a href=agent.php target="_blank"> Контрагенты </a>
				</div>
				<div>
				    <a href=itinst.php target="_blank"> Инструкции для ИТ-отдела </a>
				</div>
				<div>
				    <a href=usrinst.php target="_blank"> Инструкции для пользователей </a>
			    </div>
			</div>
		</div>
	    <div class='ctrl' >
		    <h3 > Управление</h3>
		   		 <div class='ctrl_col' >
			   		 <?php if(isset($ctrls)):?>
			    	
				    	<?php foreach ($ctrls as $ctrl): ?>
					 		<div> <?php echo createLink($ctrl['name'],$ctrl['url'],"_blank")?> </div>
						<?php endforeach;?> 
					    
					<?php endif;?> 
				</div>	        
		   <div class=ctrl_btn>
				<form action="addit.php"  method="post" target="_blank">	    	
			    	<input type="submit" class="button" size="70" name="addit" value="Синхронизировать ИТ-спецов">    	    	   
			    </form>
		    	<form action="addpcuser.php"  method="post" target="_blank">
		    		<input type="submit" class="button" size="70" name="addpcuser" value="Синхронизировать ПК и юзеров"> 
		   		</form>
		 </div>
	   </div> 	     
    </body>    
</html>