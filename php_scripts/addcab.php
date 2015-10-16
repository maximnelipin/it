<?php	

	include $_SERVER['DOCUMENT_ROOT'].'/php_scripts/func.php';
	session_start();
	if(isset($_SESSION['user_id']))
	{	include $_SERVER['DOCUMENT_ROOT'].'/php_scripts/func.php';
		include 'mysql_conf.php';
		try {
			$conbd=new PDO('mysql:host='.$hostsql.';dbname='.$dbname, $dbuser, $dbpwd);
			$condb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$condb->exec('SET NAMES "utf8"');
		}
		catch (PDOException $e)
		{
			include '../form/errorhtml.php';
			exit;
		}
		
		if (isset($_POST['cabinet']))	
		{
			
			//Получаем список всех кабинетов на этаже
			//$Dcab=str_getcsv($_POST["cabinet"], ",");			
			//----------вставка этажей и кабинетов на них--------------
			addCab($_POST['id_floor'], $_POST['cabinet'], $condb);				
			header('Location .');
			exit;
		}			
			
		include $_SERVER['DOCUMENT_ROOT'].'/form/addcabhtml.php';
	}
	else header('Location: ../index.php?link='.$_SERVER['PHP_SELF']);
	exit;
?>