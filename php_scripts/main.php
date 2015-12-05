<?php
	session_start();
	if(isset($_SESSION['user_id']))
	{	//Файл с функциями
		include_once $_SERVER['DOCUMENT_ROOT'].'/php_scripts/func.php';
		//Файл подключения к БД
		include_once $_SERVER['DOCUMENT_ROOT'].'/php_scripts/mysql_conf.php';
		//Делаем выборку ссылок на управление данными в таблицах
		try
		{
			$sql='SELECT url, name FROM ctrllink order by name LIMIT 70';
			$sqlprep=$condb->prepare($sql);
			$sqlprep->execute();
		}
		catch (PDOExeption $e)
		{
			
			include '../form/errorhtml.php';
			exit;
		}
		//Заносим их в массив
		if($sqlprep->rowCount()>0)
		{
			$result=$sqlprep->fetchall();
			foreach($result as $res)
			{
				$ctrls[]=array('url'=>html($res['url']), 'name'=>html($res['name']));			
			}
		}
		
		
		include $_SERVER['DOCUMENT_ROOT'].'/form/mainhtml.php';
		if($condb!=null) {$condb=NULL;}
	}
	else header('Location: ../index.php?link='.str_replace('&','==',$_SERVER['REQUEST_URI']));
	exit;

?>