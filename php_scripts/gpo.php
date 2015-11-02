<?php
	session_start();
	
	if(isset($_SESSION['user_id']))
	{	
		//Файл с функциями
		include_once $_SERVER['DOCUMENT_ROOT'].'/php_scripts/func.php';
		//Файл подключения к БД
		include_once $_SERVER['DOCUMENT_ROOT'].'/php_scripts/mysql_conf.php';
		
		
		
			
				
				//Делаем выборку GPO
				$sql='SELECT name, container, netpath, descrip
						FROM gpo
						ORDER BY name';
				$sqlprep=$condb->prepare($sql);
				$sqlprep->execute();
				$result=$sqlprep->fetchall();
				$params=array();
				//Формируем шапку таблицы
				$params[]=array('str'=>'<table class="">
   					<caption>Групповые политики</caption>
  					 <tr>
					<th>Имя</th>
					<th>Котейнеры</th>
    				<th>Файлы</th>
					<th>Описание</th>
   					</tr>');
				
				foreach ($result as $res)
				{
					
					//Формируем строки таблицы. Одновременно идёт выделение контейнеров и сетевых папок
					//и отобиражение с новой строки в ячейке таблицы
					$params[]=array('str'=>'<tr><td>'.html($res['name']).'</td><td>'.strWRet(str_getcsv($res['container'], ",")).'</td>
					<td>'.strWRet(str_getcsv($res['netpath'], ",")).'</td><td>'.html($res['descrip']).'</td></td> </tr>');
			
					
					
				}
				$params[]=array('str'=>'<table>');
				$ctrltitle="Групповые политики";
				$ctrls='Групповые политики';
		
		
		include $_SERVER['DOCUMENT_ROOT'].'/form/rep1html.php';
		exit;
		
		
		
		
	}
	else header('Location: ../index.php?link='.$_SERVER['PHP_SELF']);
	exit;
?>