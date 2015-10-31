<?php
	//открываем сессию
	session_start();
	//Подключаем файл с параметрами подключения
	include_once 'ldap_conf.php';
	if (isset($_GET['link'])=="logout")
		{if(isset($_SESSION['user_id']))
			{
			//Закрываем сессию 
			unset($_SESSION['user_id']);
			//Удаляем куки
			setcookie('login','',0,"/");
			setcookie('pwd','',0,"/");
			//Переходим на страницу авторизации
			header('Location: ../index.php');
			//Прекращаем выполнения скрипта
			exit;
		}		
	}

	//Если пользователь уже аутентифицирован, то перенапраялем его на Главную
	if (isset($_SESSION['user_id']))
	{
			header("Location: ../php_scripts/main.php");
			exit;
	}
	
	//Если пользователь не атентифицирован, то проверяем его права доступа
	if(isset($_POST['login']) && isset($_POST['pwd']))
		{
		
		$login=$_POST['login'].$domain;
		
		$_SESSION['user_id']=$login;
		if(isset($_GET['link']))
		{
			if($conn!=null){ldap_unbind($conn);}
			header("Location: ..".$_GET['link']);
			exit;
		}
		else
		{
			if($conn!=null){ldap_unbind($conn);}
			header("Location: /php_scripts/main.php");
			exit;
		}
		
	}
?>