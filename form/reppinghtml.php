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
	include $_SERVER['DOCUMENT_ROOT'].'/html/header.html';
	?>
	    		<div>
			    	<h2 class="title"> <?php echo $ctrltitle;?></h2>    
			    </div>
	    
	    
	    
			    
			<?php if(isset($params)):?>
					    
				<?php  foreach ($params as $param): ?>
				<div>
			    	<h2 class="title1"> <?php echo $param['build'];?></h2>    
			    </div>	
			    <div class="m_title1">
					<?php echo $param['res']; ?>	
				</div>		   
				<?php endforeach;?> 
						    
			<?php endif;?> 			 
						    
			<div class="field">					    		    
		  	<div class="btn_close">
		    <input type="button" class="button" value="Закрыть окно" onClick=window.close();>  
		    </div>
	    </div>
	    
    </body>
    
</html>