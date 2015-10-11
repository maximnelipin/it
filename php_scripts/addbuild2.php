<?php	

	include $_SERVER['DOCUMENT_ROOT'].'/php_scripts/func.php';
	session_start();
	if(isset($_SESSION['user_id']))

	{	include $_SERVER['DOCUMENT_ROOT'].'/form/addbuildhtml2.php';
		
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
		if (isset($_POST['name']))	
		{
			//-----------Добавляем здание------
			try {
				$fields=array("name","address");
				$sql='insert into build set '.pdoSet($fields,$values);
				$sqlprep=$condb->prepare($sql);
				$sqlprep->execute($values);
				//Получаем id введённого здания
				$id_build=$condb->lastInsertId();
				//echo $sql."W";
				
			}
				
			catch (PDOException $e)
			{
				echo 'Не удалось выполнить запрос';					
				echo $e->getMessage();
				exit;
			}
			//Получаем список этажей
			$Dfloor=str_getcsv($_POST["floor"], ",");
			//Получаем список всех кабинетов на этажах
			$Dcab=str_getcsv($_POST["cabinet"], ";");			
			//----------вставка этажей и кабинетов на них--------------
			addFloor($id_build, $Dfloor, $Dcab,$condb);				
			}			
			header('Location .');
			exit;
			
	}
	else header('Location ../index.php');
?>