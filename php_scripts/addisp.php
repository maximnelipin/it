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
		catch (PDOException $e)
		{
			include '../form/errorhtml.php';
			exit;
		}
		include $_SERVER['DOCUMENT_ROOT'].'/form/addisphtml.php';
		if (isset($_POST['name']))	
		{
			
			//преобразуем путь к папке для записи в Mysql
			$_POST["netpath"]=addslashes($_POST["netpath"]);
			//Делаем URL хранимым в базе как ссылка для уменьшения обработки на конечных страницах
			//$urllk='<a href='.$_POST["urllk"].'>личный кабинет'.$_POST["name"].'</a>';
			$_POST["urllk"]=addslashes($_POST["urllk"]);
			
			try {
				
				$fields=array("name", "telsup","manager","telman","emailman","address","urllk","netpath","note");
				$sql='insert into agents set '.pdoSet($fields,$values);
				$sqlprep=$condb->prepare($sql);
				$sqlprep->execute($values);
				
				$sql='insert into isp set name="'.$_POST["name"].'", telsup="'.$_POST["telsup"].'", 
					manager="'.$_POST["manager"].'", telman="'.$_POST["telman"].'", emailman="'.$_POST["emailman"].'",
					address="'.$_POST["address"].'", urllk="'.$urllk.'", netpath="'.$netpath.'", note="'.$_POST["note"].'"';
				$conbd->exec($sql);
			}
			
			catch (PDOException $e)
			{
				
				include '../form/errorhtml.php';
				exit;
			}
			
			header('Location .');
			exit;
		}
		include $_SERVER['DOCUMENT_ROOT'].'/form/addisphtml.php';
		if($condb!=null) {$condb=NULL;}
	}
	else header('Location: ../index.php?link='.$_SERVER['PHP_SELF']);
	exit;
?>