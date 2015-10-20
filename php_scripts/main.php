<?php
	session_start();
	if(isset($_SESSION['user_id']))
	{	include 'mysql_conf.php';
		try 
		{
			$condb=new PDO('mysql:host='.$hostsql.';dbname='.$dbname, $dbuser, $dbpwd);
			$condb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$condb->exec('SET NAMES "utf8"');
		}
		catch (PDOException $e)
		{
			include '../form/errorhtml.php';
			exit;
		}
		try
		{
			$result=$condb->query('SELECT url, name FROM ctrllink order by name');
		}
		catch (PDOExeption $e)
		{
			include '../form/errorhtml.php';
			exit;
		}
		
		
		foreach($result as $res)
		{
			$ctrls[]=array('url'=>$res['url'], 'name'=>$res['name']);			
		}
		
		
		
		include $_SERVER['DOCUMENT_ROOT'].'/form/mainhtml.php';
		if($condb!=null) {$condb=NULL;}
	}
	else header('Location: ../index.php?link='.$_SERVER['PHP_SELF']);
	exit;

?>