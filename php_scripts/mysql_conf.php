<?php
	//Параметры подключения к sql БД
	//Адрес SQL-сервера
	$hostsql = "vs-00-web";
	//Имя БД
	$dbname = "IT_INFO";
	//Пользователь БД
	$dbuser= "itinfo";
	//Пароль пользователя БД
	$dbpwd= "Passw0rd";
	try {
		$condb=new PDO('mysql:host='.$hostsql.';dbname='.$dbname, $dbuser, $dbpwd);
		$condb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$condb->exec('SET NAMES "utf8"');
			
	}
	catch (PDOException $e)
	{
		include '../form/errorhtml.php';
		exit;
			
	}
	
?>