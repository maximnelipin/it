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
	    		<div>
			    	<h2 class="title"> <?php echo $ctrltitle;?></h2>    
			    </div>
	    
	    
	    
			    
			<?php if(isset($params)):?>
					    
				<?php  foreach ($params as $param): ?>
				<div>
			    	<h2 class="title1"> <?php echo $param['title'];?></h2>    
			    </div>	
			    <div class="m_title1">
					<?php echo $param['res']; ?>
					<?php if (isset($paramsf)):?>
						<?php  foreach ($paramsf as $paramf): ?>
							<div>
				    			<h2 class="title2"> <?php echo $paramf['title'];?></h2>    
				    		</div>	
				    		 <div class="m_title2">
				    		<?php echo $paramf['res']; ?>
				    		</div>
						<?php endforeach;?> 
					<?php endif;?>
						
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