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
			<?php if(isset($params1)):?>
				<div>
			    	<h2 class="title"><?php echo $ctrl1;?> </h2>   
			    </div>	
				<?php  foreach ($params1 as $param1): ?>
					<?php echo $param1['str']; ?>	
				<?php endforeach;?>  
			<?php endif;?>
			<?php if(isset($params2)):?>
				<div>
			    	<h2 class="title"> <?php echo $ctrl2;?></h2>     
			    </div>	
				<?php   foreach ($params2 as $param2): ?>
					<?php echo $param2['str']; ?>	
				<?php endforeach;?> 			    		
			<?php endif;?>						    
			<div class="field">					    		    
		  	<div class="btn_close">
		    <input type="button" class="button" value="Закрыть окно" onClick=window.close();>  
		    </div>
	    </div>
	    
    </body>
    
</html>