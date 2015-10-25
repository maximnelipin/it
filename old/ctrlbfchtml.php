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
	    <h2 class="title"> Управление зданиями</h2>
	    <div class="field">
	    
	    
		    <div>
		    	<a href="?add_с">Добавить кабинет</a>	
		    	<a href="?add_f">Добавить этаж</a>	
		    	<a href="?add_b">Добавить здание</a>	     
		    </div>
		    <?php if(isset($params)):{?>
		    <ul>
		    <?php foreach ($params as $param): ?>
			    <li class="gbuild"> <?php htmlout($param['name']);?> </li>
			    <form action="" method="post">
			    <input type="hidden"; name="id_b" value=<?php echo $param['id'];?>>
				<div class="rightli">
					<input type="submit" class="but_ctrl" name="action" value="Редактировать здание">
					<input type="submit" class="but_ctrl" name="action" onClick="return confirm('Вы подтверждаете удаление?');" value="Удалить здание">
				</div>
				</form>
				    <?php if(isset($paramsf)):?>
				    	<?php foreach ($paramsf as $paramf): ?>
				    		<?php if($paramf['id_build']==$param['id']):?>
						    	<li class="fleftli"> <?php htmlout($paramf['name']);?>  этаж</li>
						    		<form action="" method="post">
						    		<input type="hidden"; name="id_f" value=<?php echo $paramf['id'];?>>
										<div class="rightli">
								    		<input type="submit" class="but_ctrl" name="action" value="Редактировать этаж">
								    		<input type="submit" class="but_ctrl" name="action" onClick="return confirm('Вы подтверждаете удаление?');" value="Удалить этаж">
							    		</div>
							    	<form>
						    		<?php foreach ($paramsc as $paramc): ?>
						    		<?php if($paramc['id_floor']==$paramf['id']):?>
							    	<li>					    		
							    		<form action="" method="post">
							    			<div class="cleftli">
							    			<?php htmlout($paramc['name']);?>
							    			</div> 
							    			<input type="hidden"; name="id_c" value=<?php echo $paramc['id'];?>>
											<div class="rightli">
								    			<input type="submit" class="but_ctrl" name="action" value="Редактировать кабинет">
								    			<input type="submit" class="but_ctrl" name="action" onClick="return confirm('Вы подтверждаете удаление?');" value="Удалить кабинет">
							    			</div>	    		
							    		</form>
							    	</li>	
							    	 <?php endif;?>						    
							    	<?php endforeach;?> 
							   
					   		<?php endif;?>	
				    	<?php endforeach;?>  
				    <?php endif;?>
			<?php endforeach;?>   
		    </ul>
		    <?php }endif;?> 	    
	  	<div class="btn_close">
	    <input type="button" class="button" value="Закрыть окно" onClick=window.close();>  
	    </div>
	    </div>
	    
    </body>
    
</html>