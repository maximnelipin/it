<?php
	session_start();
	
	if(isset($_SESSION['user_id']))
	{	
		//Файл с функциями
		include_once $_SERVER['DOCUMENT_ROOT'].'/php_scripts/func.php';
		//Файл подключения к БД
		include_once $_SERVER['DOCUMENT_ROOT'].'/php_scripts/mysql_conf.php';
		
		
		
			
				
				//Делаем выборку GPO
				$sql='SELECT * FROM itinst
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
				$ctrltitle="Инструкции для сотрудников отдела ИТ";
				$ctrls='Инструкции для сотрудников отдела ИТ';
		
		
		include $_SERVER['DOCUMENT_ROOT'].'/form/rep1html.php';
		exit;
		
		
		
		
	}
	else header('Location: ../index.php?link='.$_SERVER['PHP_SELF']);
	exit;
?>