<?php
	session_start();
	include 'func.php';
	include 'mysql_conf.php';
	if(isset($_SESSION['user_id']))
	{	
		
		try {
			$condb=new PDO('mysql:host='.$hostsql.';dbname='.$dbname, $dbuser, $dbpwd);
			$condb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$condb->exec('SET NAMES "utf8"');
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
		include $_SERVER['DOCUMENT_ROOT'].'/form/addschedhtml.php';
		if (isset($_POST['login']))	
		{
			
			//-----------Добавляем здание------
			try {
				$fields=array("login","dateduty");
				$sql='insert into schedule set '.pdoSet($fields,$values);
				$sqlprep=$condb->prepare($sql);
				$sqlprep->execute($values);			
			
			}
			
			catch (PDOException $e)
			{
				echo 'Не удалось выполнить запрос';
				echo $e->getMessage();
				exit;
			}		
			
			
			
			
			header('Location .');
			exit;
		}	
	}
	else header('Location ../index.php');
?>