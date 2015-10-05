<?php
	session_start();
	if(isset($_SESSION['user_id']))
	{	include $_SERVER['DOCUMENT_ROOT'].'/form/addbuildhtml.php';
		try {
			$conbd=new PDO('mysql:host=192.168.0.75;dbname=IT_INFO', 'itinfo', 'Passw0rd');
			$conbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$conbd->exec('SET NAMES "utf8"');
		}
		catch (PDOException $e)
		{
			echo 'Нет подключения к базе';
			echo $e->getMessage();
			exit;
		}
		if (isset($_POST['nameb']))	
		{
			$nameb=$_POST["nameb"];
			$address=$_POST["address"];
			
			try {
				$sql='insert into build set name="'.$nameb.'", address="'.$address.'"';
				$conbd->exec($sql);
			}
			
			catch (PDOException $e)
			{
				echo 'Не удалось выполнить запрос';
				     
				     echo $e->getMessage();
				exit;
			}
			
			header('Location .');
			exit;
		}	
	}
	else header('Location ../index.php');
?>