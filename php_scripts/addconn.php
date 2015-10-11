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
		include $_SERVER['DOCUMENT_ROOT'].'/form/addconnhtml.php';
		if (isset($_POST['gateway']))	
		{
			$id_extnet='';
			$id_ppp='';
			$id_comp='';
			if($_POST['radextnet']=="sel")
				{$id_extnet=$_POST['id_extnet'];}
			if($_POST['radextnet']=="add")
				{
					try {
					$fields=array('extip', 'extmask', 'extgw', 'extdns1', 'extdns2');					
					$sql='insert into extnet set '.pdoSet($fields,$values);
					$sqlprep=$condb->prepare($sql);
					$sqlprep->execute($values);
					$id_extnet=$condb->lastInsertId();
					}
					catch (PDOException $e)
					{
						echo 'Не удалось выполнить запрос';
						echo $e->getMessage();
						exit;
					}
				}
			
			echo $id_extnet;
			
			
			
			
			
			
			header('Location .');
			exit;
		}	
	}
	else header('Location ../index.php');
?>