<?php
	session_start();
	if(isset($_SESSION['user_id']))
	{	
		include 'mysql_conf.php';
		try {
			$conbd=new PDO('mysql:host='.$hostsql.';dbname='.$dbname, $dbuser, $dbpwd);
			$conbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$conbd->exec('SET NAMES "utf8"');
		}
		catch (PDOException $e)
		{
			$error= 'Нет подключения к базе'.$e->getMessage();	
			$urlerr=$_SERVER['PHP_SELF'];
			//$_SESSION['erroor']=$error;
			//$_SESSION['urlerr']=$urlerr;
			include '../form/errorhtml.php';
			exit;
		}
		include $_SERVER['DOCUMENT_ROOT'].'/form/addisphtml.php';
		if (isset($_POST['name']))	
		{
			$netpath=$_POST["netpath"];
			//преобразуем путь к папке для записи в Mysql
			$netpath=addslashes($netpath);
			//Делаем URL хранимым в базе как ссылка для уменьшения обработки на конечных страницах
			$urllk='<a href='.$_POST["urllk"].'>личный кабинет'.$_POST["name"].'</a>';
			$urllk=addslashes($urllk);
			
			try {
				$sql='insert into isp set name="'.$_POST["name"].'", telsup="'.$_POST["telsup"].'", 
					manager="'.$_POST["manager"].'", telman="'.$_POST["telman"].'", emailman="'.$_POST["emailman"].'",
					address="'.$_POST["address"].'", urllk="'.$urllk.'", netpath="'.$netpath.'", note="'.$_POST["note"].'"';
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