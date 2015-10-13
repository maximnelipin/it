<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="../stylesheet/reset.css">
<link rel="stylesheet" type="text/css" href="../stylesheet/general.css">
<link rel="stylesheet" type="text/css" href="../stylesheet/add.css">
<title>Добавление модели принтера</title>
</head>

    <body>
    <?php
	include $_SERVER['DOCUMENT_ROOT'].'/html/header.html';
	include $_SERVER['DOCUMENT_ROOT'].'/php_scripts/func.php';
	?>
	    <h2 class="title"> Главная страница</h2>
	    <form action="addit.php"  method="post" target="_blank">
	    	<div class="field">
	    		<input type="submit" class="button" size="70" name="addit" value="Синхронизировать ИТ-спецов"></textarea>    	
	    	</div>	    	   
	    </form>
	    <form action="addpcuser.php"  method="post" target="_blank">
	    	<div class="field">
	    		<input type="submit" class="button" size="70" name="addpcuser" value="Синхронизировать ПК и юзеров"></textarea>    	
	    	</div>	    	    	   
	    </form>
	    <form action=""  method="get/post" target="_blank" id="sched">
	    	<div class="field">
	    		<label for="monyear" > Месяц отчёта</label>
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
	    		   	
	    	</div>
	    	<input type="submit" class="button" size="70" name="schedule" value="В HTML" onClick="document.getElementById('sched').action = 'schedule.php'">
	    	<input type="submit" class="button" size="70" name="schedpdf" value="В PDF" onClick="document.getElementById('sched').action = 'schedpdf.php'">	    	    	   
	    </form>
	    
	    <?php
	
		?>
    </body>
    
</html>