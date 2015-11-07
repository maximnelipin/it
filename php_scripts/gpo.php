<?php
	session_start();	
	if(isset($_SESSION['user_id']))
	{	
		//Файл с функциями
		include_once $_SERVER['DOCUMENT_ROOT'].'/php_scripts/func.php';
		//Файл подключения к БД
		include_once $_SERVER['DOCUMENT_ROOT'].'/php_scripts/mysql_conf.php';
		
			//Делаем выборку GPO
			try 
			{
				$sql='SELECT name, container, netpath, descrip
							FROM gpo
							ORDER BY name LIMIT 70';
				$sqlprep=$condb->prepare($sql);
				$sqlprep->execute();
				
			}
			catch (PDOExeption $e)
			{
				include '../form/errorhtml.php';
				exit;
			}
			if($sqlprep->rowCount()>0)
			{	
				$result=$sqlprep->fetchall();
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
			}
			
			
			else
			{
				//Не хватает параметров
				$params[]=array('str'=>'');
				$ctrltitle="Групповые политики";
				$ctrls='Нет групповых политик';
			}
		include $_SERVER['DOCUMENT_ROOT'].'/form/rep1html.php';
		exit;
	}
	else header('Location: ../index.php?link='.str_replace('&','==',$_SERVER['REQUEST_URI']));
	exit;
?>