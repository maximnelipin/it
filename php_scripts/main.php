<?php
	session_start();
	if(isset($_SESSION['user_id']))
	{try {
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
	echo 'Есть подключение к базе';	
	}
	else header('Location: ../index.php');

?>