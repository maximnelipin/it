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
		include $_SERVER['DOCUMENT_ROOT'].'/form/addserverhtml.php';
		if (isset($_POST['name']))	
		{
			
			//-----------Добавляем здание------
			try {
				$fields=array("name","id_cabinet","type","descrip","phys","rack","units","login","note");
				echo $_POST['id_cabinet'];
				$sql='insert into servers set '.pdoSet($fields,$values);
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