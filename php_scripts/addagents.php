<?php
	session_start();
	if(isset($_SESSION['user_id']))
	{	include $_SERVER['DOCUMENT_ROOT'].'/form/addagentshtml.php';
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
			$netpath=$_POST["netpath"];
			//преобразуем путь к папке для записи в Mysql
			$netpath=addslashes($netpath);
			
			try {
				$sql='insert into agents set name="'.$_POST["name"].'", manager="'.$_POST["manager"].'", telman="'.$_POST["telman"].'", emailman="'.$_POST["emailman"].'",
					address="'.$_POST["address"].'", type="'.$_POST["type"].'", netpath="'.$netpath.'", note="'.$_POST["note"].'"';
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