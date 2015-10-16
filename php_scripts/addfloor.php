<?php	
	session_start();
	if(isset($_SESSION['user_id']))
	{	include 'mysql_conf.php';
		include $_SERVER['DOCUMENT_ROOT'].'/php_scripts/func.php';
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
		
		if (isset($_POST['floor']))	
		{
			
			//Получаем список этажей
			$Dfloor=str_getcsv($_POST["floor"], ",");
			//Получаем список всех кабинетов на этажах
			$Dcab=str_getcsv($_POST["cabinet"], ";");			
			//----------вставка этажей и кабинетов на них--------------
			addFloor($_POST["id_build"], $Dfloor, $Dcab, $condb);				
					
			header('Location .');
			exit;
		}	
		include $_SERVER['DOCUMENT_ROOT'].'/form/addfloorhtml.php';
		if($condb!=null) {$condb=NULL;}
			
	}
	else header('Location: ../index.php?link='.$_SERVER['PHP_SELF']);
	exit;
?>