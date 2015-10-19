<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="../stylesheet/reset.css">
<link rel="stylesheet" type="text/css" href="../stylesheet/general.css">
<link rel="stylesheet" type="text/css" href="../stylesheet/ctrl.css">
<title>Управление <?php echo $ctrltitle;?></title>
</head>

    <body>
    <?php
	include $_SERVER['DOCUMENT_ROOT'].'/html/header.html';
	?>
	    <h2 class="title"> Управление <?php echo $ctrltitle;?></h2>
	    <div class="field">
	    
	    
		    <div>
		    	<a href="?add">Добавить <?php echo $ctrladd;?></a>	    
		    </div>
		    <?php if(isset($params)):{?>
		    <ul>
		    	<?php foreach ($params as $param): ?>
		    	<li>
		    		<form action="" method="post">
		    			<div class="leftli">
		    			<?php htmlout($param['name']);?>
		    			</div>
		    				<input type="hidden"; name="id" value=<?php echo $param['id'];?>>
		    				<?php if(isset($param['id2'])):?>
		    				<input type="hidden"; name="id2" value=<?php echo $param['id2'];?>>
		    				 <?php endif;?> 
		    				 <?php if(isset($param['id3'])):?>
		    				<input type="hidden"; name="id3" value=<?php echo $param['id3'];?>>
		    				 <?php endif;?> 
		    			<div class="rightli">
			    			<input type="submit" class="button" name="action" value="Редактировать">
			    			<input type="submit" class="button" name="action" onClick="return confirm('Вы подтверждаете удаление?');" value="Удалить">
		    			</div>	    		
		    		</form>
		    	</li>
		    	<?php endforeach;?>    
		    </ul>
		    <?php }endif;?> 	    
	    </div>
	    
    </body>
    
</html>