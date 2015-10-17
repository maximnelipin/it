<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="../stylesheet/reset.css">
<link rel="stylesheet" type="text/css" href="../stylesheet/general.css">
<link rel="stylesheet" type="text/css" href="../stylesheet/ctrl.css">
<title>Управление контрагентами</title>
</head>

    <body>
    <?php
	include $_SERVER['DOCUMENT_ROOT'].'/html/header.html';
	?>
	    <h2 class="title"> Управление контрагентами</h2>
	    <div class="field">
	    
	    
		    <div>
		    	<a href="?add">Добавить нового контрагента</a>	    
		    </div>
		    <ul>
		    	<?php foreach ($agents as $agent): ?>
		    	<li>
		    		<form action="" method="post">
		    			<div class="leftli">
		    			<?php htmlout($agent['name']);?>
		    			</div>
		    				<input type="hidden"; name="id" value=<?php echo $agent['id'];?>>
		    			<div class="rightli">
			    			<input type="submit" class="button" name="action" value="Редактировать">
			    			<input type="submit" class="button" name="action" onClick="return confirm('Вы подтверждаете удаление?');" value="Удалить">
		    			</div>	    		
		    		</form>
		    	</li>
		    	<?php endforeach;?>    
		    </ul>	    
	    </div>
	    
    </body>
    
</html>