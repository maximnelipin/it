<?php	

	include $_SERVER['DOCUMENT_ROOT'].'/php_scripts/func.php';
	session_start();
	if(isset($_SESSION['user_id']))
	{	
		
		try {
			$condb=new PDO('mysql:host=192.168.0.75;dbname=IT_INFO', 'itinfo', 'Passw0rd');
			$condb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$condb->exec('SET NAMES "utf8"');
		}
		catch (PDOException $e)
		{
			echo 'Нет подключения к базе';
			echo $e->getMessage();
			exit;
		}
		include $_SERVER['DOCUMENT_ROOT'].'/form/addcabhtml.php';
		if (isset($_POST['cabinet']))	
		{
			
			//Получаем список всех кабинетов на этаже
			//$Dcab=str_getcsv($_POST["cabinet"], ",");			
			//----------вставка этажей и кабинетов на них--------------
			addCab($_POST['id_floor'], $_POST['cabinet'], $condb);				
			}			
			header('Location .');
			exit;
			
	}
	else header('Location ../index.php');
?>