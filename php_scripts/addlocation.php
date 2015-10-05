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
			$error= 'Не удалось выполнить запрос'.$e->getMessage();	
			$urlerr=$_SERVER['PHP_SELF'];
			//$_SESSION['erroor']=$error;
			//$_SESSION['urlerr']=$urlerr;
			include '../form/errorhtml.php';
			exit;
		}
		include $_SERVER['DOCUMENT_ROOT'].'/form/addlocationhtml.php';
		
		if (isset($_POST['idbuild']))	
		{
						
			try {
				$sql='insert into location set id_build="'.$_POST["idbuild"].'", floor="'.$_POST["floor"].'", note="'.$_POST["note"].'"';
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
?>