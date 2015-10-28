<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="../stylesheet/reset.css">
<link rel="stylesheet" type="text/css" href="../stylesheet/general.css">
<link rel="stylesheet" type="text/css" href="../stylesheet/ctrl.css">
<title><?php echo $ctrltitle;?></title>
</head>

    <body>
    <?php
	include $_SERVER['DOCUMENT_ROOT'].'/html/header.html';
	?>
	    <h2 class="title"> <?php echo $ctrltitle;?></h2> 
	        
			  <div class="etaj">  
			<?php //Вывод инфы о сим-картах, если есть
				if(isset($params)):?>
				<div>
			    	<h2 class="title"> <?php echo $ctrls;?></h2>    
			    </div>
			   		    
				<?php  foreach ($params as $param): ?>
					<?php echo $param['str']; ?>				   
				<?php endforeach;?> 		    
			<?php endif;?>
			 				
			<?php  foreach ($paramsf as $paramf): ?>
									
				 <div> <h2 class="title"><?php //выводим номер этажа 
											echo $paramf['str']; ?>
				</h2></div>
			<div class="cab">
			<?php //перебираем кабинеты  
				
				foreach ($paramsc as $paramс):				 
					if ($paramс['id_floor']==$paramf['id']):?>
						
						
						
						
						 <div> <h2 class="title"><?php echo $paramс['str']?></h2></div>
						<?php 
						//вывод принтеров
						$printers=printerInCab($paramс['id'],$condb);
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
						$servers=serverInCab($paramс['id'],$condb);
						if (gettype($servers)=='array')
						{ 	
							foreach ($servers as $server)
							{
								echo $server['str'];
							}
							
						}
						//Вывод подключений
						$conns=connInCab($paramс['id'],$condb);
						if (gettype($conns)=='array')
						{
							foreach ($conns as $conn)
							{
								echo $conn['str'];
							}
								
						}
						?>
						
					<?php endif;?>
				<?php endforeach;?> 
				</div>
					
				
			<?php endforeach;?>  
			</div>
				    
			<div class="field">					    		    
		  	<div class="btn_close">
		    <input type="button" class="button" value="Закрыть окно" onClick=window.close();>  
		    </div>
	    </div>
	    
    </body>
    
</html>