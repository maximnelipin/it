<?php
	session_start();
	if(isset($_SESSION['user_id']))
	{	
		//Файл с функциями
		include_once $_SERVER['DOCUMENT_ROOT'].'/php_scripts/func.php';
		//Файл подключения к БД
		include_once $_SERVER['DOCUMENT_ROOT'].'/php_scripts/mysql_conf.php';
			//Делаем выборку
		try 
		{
			$sql='SELECT * FROM agents	ORDER BY name LIMIT 50';
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
			$params=array();
			//Формируем шапку таблицы
			$params[]=array('str'=>'<table class="">
   					<caption>Контрагенты</caption>
  					 <tr>
					<th>Имя</th>
					<th>Менеджер</th>
    				<th>Телефон</th>
					<th>E-mail</th>
					<th>Офис</th>
					<th>Тип партнёрства</th>
					<th>Папка</th>
					<th>Примечание</th>
   					</tr>');			
			foreach ($result as $res)
			{
				//Формируем строки таблицы. Одновременно идёт выделение видов сотрудничества
				//в отдельную строку
				$params[]=array('str'=>'<tr><td>'.html($res['name']).'</td><td>'.html($res['manager']).'</td>
				<td>'.html($res['telman']).'</td><td>'.html($res['emailman']).'</td><td>'.html($res['address']).
				'</td><td>'.strWRet(str_getcsv($res['type'], ",")).'</td><td>'.html($res['netpath']).
				'</td><td>'.html($res['note']).
				'</td></td> </tr>');	
				}
			$params[]=array('str'=>'<table>');
			$ctrltitle="Контрагенты";
			$ctrls='Контрагенты';
		}
				
		else
		{
			//Не хватает параметров
			$params[]=array('str'=>'');
			$ctrltitle="Контрагенты";			
			$ctrls='Нет контрагентов';					 
		}
				
		include $_SERVER['DOCUMENT_ROOT'].'/form/rep1html.php';
		exit;		
	}
	else header('Location: ../index.php?link='.str_replace('&','==',$_SERVER['REQUEST_URI']));
	exit;
?>