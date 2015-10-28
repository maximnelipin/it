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
	    <?php //Вывод инфы о зданиии
	    	foreach ($paramsb as $paramb): ?>
				<?php echo $paramb['str']; ?>				   
		<?php endforeach;?> 	    
			    
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
				<?php //выводим номер этажа 
					echo '<div> <h2 class="title">'.$paramf['str'].'</h2></div>'; ?>
			<?php //перебираем кабинеты  
				foreach ($paramsc as $paramс)
				{ 
					if ($paramс['id_floor']==$paramf['id'])
					{	
						$printers=array();
						
						//Выводи название кабинета
						echo '<div> <h2 class="title">'.$paramс['str'].'</h2></div>';
						$printers=printerInCab($paramс['id'],$condb);
						//если есть принтеры						
						if (gettype($printers)=='array')
						{ 	//Выводим шапку
							
							echo '<table>
		   					<caption>Принтеры</caption>
		  					 <tr>
							<th>Сетевой адрес</th>
							<th>Модель</th>
		    				<th>Картриджи</th>
							<th>Драйвера</th>						
		   					</tr>';
							//Выводим принтеры
							foreach ($printers as $printer)
							{
								echo '<tr><td>'.html($printer['netpath']).'</td><td>'.
										html($printer['name']).'</td><td>'.
										html($printer['cart']).'</td><td>'.
										html($printer['drivers']).'</td></tr>';
							}
							echo '</table>';
						}
						
						
					}
				}
				?>
					
				
			<?php endforeach;?>  
			
				    
			<div class="field">					    		    
		  	<div class="btn_close">
		    <input type="button" class="button" value="Закрыть окно" onClick=window.close();>  
		    </div>
	    </div>
	    
    </body>
    
</html>