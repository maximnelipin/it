<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="../stylesheet/reset.css">
<link rel="stylesheet" type="text/css" href="../stylesheet/general.css">
<link rel="stylesheet" type="text/css" href="../stylesheet/add.css">
<link rel="stylesheet" type="text/css" href="../stylesheet/tcal.css">
<script src="../js_scripts/tcal.js"></script>
<title>Добавление дежурства</title>
</head>

    <body>
    <?php
	include $_SERVER['DOCUMENT_ROOT'].'/html/header.html';
	?>
	    <h2 class="title"> Добавление дежурства</h2>
	     <form action="?"  method="post">
	     	<div class="fieldcall">
	    		<label for="dateduty" > Дата дежурства</label>
	    		<input type="text" class="tcal" name="dateduty"   value="">
	    	</div>
	    	
	    	
	    	<div class="field">
	    		<label for="login"> Дежурный</label>
	    		<select required class="text" size="5" name="login">
	    			<option disabled>Выберите дежурного</option>
	    			<?php 
	    				$selsql='SELECT login, fio FROM itusers';
						$ressql=$condb->query($selsql);
	    				while ($res=$ressql->fetch(PDO::FETCH_ASSOC))
	    				{    					
	    						echo '<option value='.$res['login'].'>'.$res['fio'].'</option>';
	    						
	    				}
	    				?>
	    		</select>  	
	    	</div>
	    	<div>
	    		<input type="submit" class="button" value="Добавить">
	    	</div>
	    
	    </form>
	 </body>    
</html>