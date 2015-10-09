<?php
	session_start();
	if(isset($_SESSION['user_id']))
	{	include $_SERVER['DOCUMENT_ROOT'].'/form/addgpohtml.php';
		include 'mysql_conf.php';
		try {
			$conbd=new PDO('mysql:host='.$hostsql.';dbname='.$dbname, $dbuser, $dbpwd);
			$conbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$conbd->exec('SET NAMES "utf8"');
		}
		catch (PDOException $e)
		{
			$error= 'Не удалось выполнить запрос'.$e->getMessage();	
			$urlerr=$_SERVER['PHP_SELF'];
			//$_SESSION['erroor']=$error;
			//$_SESSION['urlerr']=$urlerr;
			include '../form/errorhtml.php';
			exit;
		}
		if (isset($_POST['name']))	
		{
			$container=$_POST["container"];
			//преобразуем путь к папке для записи в Mysql
			$container=addslashes($container);
			$netpath=addslashes($_POST["netpath"]);
			
			try {
				$sql='insert into gpo set name="'.$_POST["name"].'", container="'.$container.'", netpath="'.$netpath.'", descrip="'.$_POST["descrip"].'"';
				$conbd->exec($sql);
			}
			
			catch (PDOException $e)
			{
				
				$error= 'Не удалось выполнить запрос'.$e->getMessage();	
				$urlerr=$_SERVER['PHP_SELF'];
				//$_SESSION['error']=$error;
				//$_SESSION['urlerr']=$urlerr;
				include '../form/errorhtml.php';
				exit;
			}
			
			header('Location .');
			exit;
		}	
	}
	else header('Location ../index.php');
?>