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
		if (isset($_POST['name']))	
		{
			
			$_POST["url"]=addslashes($_POST["url"]);
			
			try {
				
				$fields=array("name","url");
				$sql='insert into itinst set '.pdoSet($fields,$values);
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
		include $_SERVER['DOCUMENT_ROOT'].'/form/additinsthtml.php';
		if($condb!=null) {$condb=NULL;}
	}
	else header('Location: ../index.php?link='.$_SERVER['PHP_SELF']);
	exit;
?>