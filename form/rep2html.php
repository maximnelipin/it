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
	    <h2 class="title"> <?php echo $ctrltitle;?></h2>
	    <h2 class="title"> <?php echo $address;?></h2> 
	        
			  
			<?php //Вывод инфы о сим-картах, если есть 
				if(isset($params)):?>
				<div>
			    	<h2 class="title"> <?php echo $ctrls;?></h2>
			    	      
			    </div>
			   		    
				<?php  foreach ($params as $param): ?>
					<?php echo $param['str']; ?>				   
				<?php endforeach;?> 		    
			<?php endif;?>
			 				
			<?php  foreach ($params1 as $param1): ?>
									
				 <div> <h2 class="title1"><?php //выводим номер этажа 
											echo $param1['str']; ?>
				</h2></div>
			
			<?php //перебираем кабинеты  
				if (isset($params2)):							
					foreach ($params2 as $param2):				 
						if ($param2['id_floor']==$param1['id']):?>
							 <div> <h2 class="title2"><?php echo $param2['str']?></h2></div>
							<div class="m_title2">
								<?php 
								
									//вывод принтеров
									$printers=printerInCab($param2['id'],$condb);
									//если есть принтеры						
									if (gettype($printers)=='array')
									{ 	
										//Выводим принтеры
										foreach ($printers as $printer)
										{
											echo $printer['str'];
										}
										
									}
									//Вывод серверов
									$servers=serverInCab($param2['id'],$condb);
									if (gettype($servers)=='array')
									{ 	
										foreach ($servers as $server)
										{
											echo $server['str'];
										}
										
									}
									//Вывод подключений и провайдеров
									$conns=connInCab($param2['id'],$condb);
									if (gettype($conns)=='array')
									{
										foreach ($conns as $conn)
										{
											echo $conn['str'];
										}
											
									}
								?>
							</div>
						<?php endif;?>
					<?php endforeach;?> 
				<?php endif;?>
			<?php endforeach;?>  
			
				    
			<div class="field">					    		    
		  	<div class="btn_close">
		    <input type="button" class="button" value="Закрыть окно" onClick=window.close();>  
		    </div>
	    </div>
	    
    </body>
    
</html>