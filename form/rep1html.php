<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="../stylesheet/reset.css">
<link rel="stylesheet" type="text/css" href="../stylesheet/general.css">
<link rel="stylesheet" type="text/css" href="../stylesheet/report.css">
<title><?php echo $ctrltitle;?></title>
</head>

    <body>
    <?php
	include $_SERVER['DOCUMENT_ROOT'].'/html/header.php';
	?>
	    
	    
	    
	    
			    
			<?php if(isset($params)):?>
				<div>
			    	<h2 class="title"> <?php echo $ctrls;?></h2>    
			    </div>		    
				<?php  foreach ($params as $param): ?>
					<?php echo $param['str']; ?>				   
				<?php endforeach;?> 		    
			<?php endif;?> 			 
			<?php if(isset($paramsf)):?>
				<div>
			    	<h2 class="title"><?php echo $ctrlf;?> </h2>   
			    </div>	
				<?php  foreach ($paramsf as $paramf): ?>
					<?php echo $paramf['str']; ?>	
				<?php endforeach;?>  
			<?php endif;?>
			<?php if(isset($paramsc)):?>
				<div>
			    	<h2 class="title"> <?php echo $ctrlc;?></h2>     
			    </div>	
				<?php   foreach ($paramsc as $paramc): ?>
					<?php echo $paramc['str']; ?>	
				<?php endforeach;?> 			    		
			<?php endif;?>						    
			<div class="field">					    		    
		  	<div class="btn_close">
		    <input type="button" class="button" value="Закрыть окно" onClick=window.close();>  
		    </div>
	    </div>
	    
    </body>
    
</html>