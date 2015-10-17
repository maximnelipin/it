<?php
	session_start();
	if(isset($_SESSION['user_id']))
	{	include 'func.php';
		include 'mysql_conf.php';
		try {
			$conbd=new PDO('mysql:host='.$hostsql.';dbname='.$dbname, $dbuser, $dbpwd);
			$conbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$conbd->exec('SET NAMES "utf8"');
		}
		catch (PDOException $e)		{
			
			include '../form/errorhtml.php';
			exit;
		}
		if (isset($_POST['name']))	
		{
			
			//преобразуем путь к папке для записи в Mysql
			$_POST["netpath"]=addslashes($_POST["netpath"]);
			
			try {
				
				$fields=array("name","manager","telman","emailman","address","type","netpath","note");
				$sql='insert into agents set '.pdoSet($fields,$values);
				$sqlprep=$condb->prepare($sql);
				$sqlprep->execute($values);			
				
			}
			
			catch (PDOException $e)
			{				
				include '../form/errorhtml.php';
				exit;
			}
			
			header('Location .');
			exit;
		}
		include $_SERVER['DOCUMENT_ROOT'].'/form/addagentshtml.php';
		if($condb!=null) {$condb=NULL;}
	}
	else header('Location: ../index.php?link='.$_SERVER['PHP_SELF']);
	exit;
?>