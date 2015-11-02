<?php
	session_start();
	if(isset($_SESSION['user_id']))
	{	//Файл с функциями
		include_once $_SERVER['DOCUMENT_ROOT'].'/php_scripts/func.php';
		//Файл подключения к БД
		include_once $_SERVER['DOCUMENT_ROOT'].'/php_scripts/mysql_conf.php';
		try
		{
			$result=$condb->query('SELECT url, name FROM ctrllink order by name');
		}
		catch (PDOExeption $e)
		{
			include '../form/errorhtml.php';
			exit;
		}
		
		
		foreach($result as $res)
		{
			$ctrls[]=array('url'=>$res['url'], 'name'=>$res['name']);			
		}
		
		
		
		include $_SERVER['DOCUMENT_ROOT'].'/form/mainhtml.php';
		if($condb!=null) {$condb=NULL;}
	}
	else header('Location: ../index.php?link='.$_SERVER['PHP_SELF']);
	exit;

?>