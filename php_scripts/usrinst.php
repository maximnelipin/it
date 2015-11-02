<?php
	
	
		
		//Файл с функциями
		include_once $_SERVER['DOCUMENT_ROOT'].'/php_scripts/func.php';
		//Файл подключения к БД
		include_once $_SERVER['DOCUMENT_ROOT'].'/php_scripts/mysql_conf.php';
		
		
		
			
				
				//Делаем выборку GPO
				$sql='SELECT * FROM usrinst
						ORDER BY name';
				$sqlprep=$condb->prepare($sql);
				$sqlprep->execute();
				$result=$sqlprep->fetchall();
				$params=array();
				//Формируем шапку таблицы
				$params[]=array('str'=>'<div class="field">');
			
				
				foreach ($result as $res)
				{
					
					//Формируем строки таблицы. Одновременно идёт выделение контейнеров и сетевых папок
					//и отобиражение с новой строки в ячейке таблицы
					$params[]=array('str'=>'<div class=ainst> <a href='.$_SERVER['HTTP_HOST'].html($res['url']).'>'.html($res['name']).'</a> </div>');
			
					
					
				}
				$params[]=array('str'=>'</div>');
				$ctrltitle="Инструкции для пользователей";
				$ctrls='Инструкции для пользователей';
		
		
		include $_SERVER['DOCUMENT_ROOT'].'/form/rep1html.php';
		exit;
		
		
		
		
	
	
?>