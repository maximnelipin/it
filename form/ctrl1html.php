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
     <?php	include $_SERVER['DOCUMENT_ROOT'].'/html/header.php';	?>
	    <div class="field">					    		    
			<div class="btn_close">
			   <input type="button" class="button" value="Закрыть окно" onClick=window.close();>  
			</div>
		</div>
	    <h2 class="title"> Управление <?php echo $ctrltitle;?></h2>
	    
	    <div class="field"> 	     
		    <div>
		    	<?php echo $ctrladd ;?>    
		    </div>
		    <?php if(isset($params)):{?>
		    <ul>
		    <?php foreach ($params as $param): ?>
			    <li class="leftli"> <?php htmlout($param['name']);?> </li>
			    <form action="" method="post">
			    	<input type="hidden"; name="id" value=<?php echo html($param['id']);?>>
					<div class="rightli">
						<input type="submit" class="but_ctrl" name="action" value="Редактировать<?php if(isset($btn)) echo $btn;?>" <?php if(isset($btn_off)) echo $btn_off;?>>
						<input type="submit" class="but_ctrl" name="action" onClick="return confirm('Вы подтверждаете удаление?');" value="Удалить<?php if(isset($btn)) echo $btn;?>" <?php if(isset($btn_off)) echo $btn_off;?>>
					</div>
				</form>
				    <?php if(isset($params1)):?>
				    	<?php foreach ($params1 as $param1): ?>
				    		<?php if($param1['id']==$param['id']):?>
						    	<li class="leftli_1"> <?php htmlout($param1['name']);?>  </li>
						    		<form action="" method="post">
						    			<input type="hidden"; name="id_1" value=<?php echo html($param1['id_1']);?>>
										<div class="rightli">
								    		<input type="submit" class="but_ctrl" name="action" value="Редактировать<?php if(isset($btn_1)) echo $btn_1;?>">
								    		<input type="submit" class="but_ctrl" name="action" onClick="return confirm('Вы подтверждаете удаление?');" value="Удалить<?php if(isset($btn_1)) echo $btn_1;?>">
							    		</div>
							    	</form>								    	
							    	<?php if(isset($params2)):?>
						    			<?php foreach ($params2 as $param2): ?>
							    			<?php if($param2['id_1']==$param1['id_1']):?>
									    		<li>					    		
									    			<form action="" method="post">
									    				<div class="leftli_2">
									    					<?php htmlout($param2['name']);?>
									    				</div> 
									    				<input type="hidden"; name="id_2" value=<?php echo html($param2['id_2']);?>>
														<div class="rightli">
										    				<input type="submit" class="but_ctrl" name="action" value="Редактировать<?php echo $btn_2;?>">
										    				<input type="submit" class="but_ctrl" name="action" onClick="return confirm('Вы подтверждаете удаление?');" value="Удалить<?php echo $btn_2;?>">
									    				</div>	    		
									    			</form>
									    		</li>	
							    	 		<?php endif;?>						    
							    	<?php endforeach;?> 
							   <?php endif;?>	
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