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
	include $_SERVER['DOCUMENT_ROOT'].'/html/header.html';
	include $_SERVER['DOCUMENT_ROOT'].'/php_scripts/func.php';
	?>
	    <h2 class="title"> Главная страница</h2>
	    <div class="repmain">
	   		<div class="mainleft">
			   <form action=""  method="get" target="_blank" id="sched">
			    	<div >
			    		<label for="monyear" > Дежурства</label>
			    		<select required class="text" size="1" name="monyear">
			    			<option disabled selected>Выберите месяц</option>
			    			<?php 
								
								$selsql='SELECT Distinct month(dateduty) as month, year(dateduty) as year FROM schedule
										ORDER BY year, month';
								$ressql=$condb->query($selsql);
			    				while ($res=$ressql->fetch(PDO::FETCH_ASSOC))
			    				{
			    					$month=numToMonth($res['month']);
			    					
			    					echo '<option value='.$res['month'].','.$res['year'].'>'.$month. " ".$res['year'].'</option>';
			    					    					
			    				}
			    				?>
			    		</select> 	
			    	
				    	<div>
					    	<input type="submit" class="button" size="70" name="schedule" onClick="document.getElementById('sched').action = 'schedule.php'" value="В HTML">
					    	<input type="submit" class="button" size="70" name="schedpdf" onClick="document.getElementById('sched').action = 'schedpdf.php'" value="В PDF">	    	    	   
					   	</div>
			    	</div>
			    </form> 
			    <form action=""  method="get" target="_blank" id="conn">
			    	<div >
			    		<label for="gwlan" > ЛВС</label>
			    		<select required class="text" size="1" name="gwlan">
			    			<option  selected value='all'> Все ЛВС</option>
			    			<?php 
								
								$selsql='SELECT id, gateway FROM conn
										ORDER BY gateway';
								$ressql=$condb->query($selsql);
			    				while ($res=$ressql->fetch(PDO::FETCH_ASSOC))
			    				{
			    					
			    					
			    					echo '<option value='.$res['id'].'>'.$res['gateway'].'</option>';
			    					    					
			    				}
			    				?>
			    		</select> 	
			    	
				    	<div>
					    	<input type="submit" class="button" size="70" name="conn" onClick="document.getElementById('conn').action = 'conn.php'" value="Отчёт">
					    	<input type="submit" class="button" size="70" name="ping" onClick="document.getElementById('conn').action = 'ping.php'" value="Пинг">	    	    	   
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
								
								$selsql='SELECT login,fio FROM listuser
										ORDER BY fio';
								$ressql=$condb->query($selsql);
			    				while ($res=$ressql->fetch(PDO::FETCH_ASSOC))
			    				{
			    					
			    					
			    					echo '<option value='.$res['login'].'>'.$res['fio'].'</option>';
			    					    					
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
								
								$selsql='SELECT id,name FROM build
										ORDER BY name';
								$ressql=$condb->query($selsql);
			    				while ($res=$ressql->fetch(PDO::FETCH_ASSOC))
			    				{
			    					
			    					
			    					echo '<option value='.$res['id'].'>'.$res['name'].'</option>';
			    					    					
			    				}
			    				?>
			    		</select> 	
			    	
				    	<div>
				    		<input type="submit" class="button" size="70" name="usrpc"  value="Отчёт">    	    	   
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
		    <?php if(isset($ctrls)):{?>
		    
			    <?php foreach ($ctrls as $ctrl): ?>
				 <div>  <a   href=<?php echo htmlout($ctrl['url']);?> target=_blank> <?php echo htmlout($ctrl['name']); ?></a></div>
				<?php endforeach;?> 
				    
			<?php }endif;?> 
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
	     
	    
	    
	    
	    <?php
	
		?>
    </body>
    
</html>