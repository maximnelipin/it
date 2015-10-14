<?php
	session_start();
	if(isset($_SESSION['user_id']))
	{	
		include 'mysql_conf.php';
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
		include $_SERVER['DOCUMENT_ROOT'].'/form/addsimhtml.php';
		include 'func.php';
		if (isset($_POST['number']))	
		{
			//$login=$_POST["login"];
			//преобразуем путь к папке для записи в Mysql
			//$login=addslashes($login);
			try {
				
				$fields=array("number","account","id_address","id_operator","login","balance","pay","pwdlk","note");
				$sql='insert into sim set '.pdoSet($fields,$values);
				$sqlprep=$condb->prepare($sql);
				$sqlprep->execute($values);			
				
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