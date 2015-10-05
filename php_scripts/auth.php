<?php
	//открываем сессию
	session_start();
	//Подключаем файл с параметрами подключения
	include_once 'ldap_conf.php';
	if (isset($_GET['logout']))
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
		$usr=$_POST['login'];
		$login=$_POST['login'].$domain;
		$pwd=$_POST['pwd'];
		//Коннектимся к КД
		$conn=ldap_connect($host, $port) or die("LDAP сервер не доступен");
		//Включаем протокол третьей версии
		ldap_set_option($conn, LDAP_OPT_PROTOCOL_VERSION, 3);
		if($conn)
		{
			//Входим в ldap с полученными учётными данными
			$bind=ldap_bind($conn,$login,$pwd);
			if($bind)
			{
				//проверка на принадлежность пользователя группе
				$check=ldap_search($conn, $itou, "(&(memberOf=".$groupit.")(sAMAccountName=".$usr."))");
				//Проверяем, есть ли результаты предыдущего запроса
				$check_num=ldap_get_entries($conn, $check);
			}
			else die("Введён неверный логин или пароль или недоступен сервер LDAP. <a href='index.php'> Попробовать ещё раз </a>");
		}
		//Если пользователь принадлежит группе, пускаем на главную
		if ($check_num['count']!=0)
		{
			$_SESSION['user_id']=$login;
			header("Location: ../php_scripts/main.php");
			exit;
		}
		else die ("Доступ закрыт. <a href='index.php'> Попробовать ещё раз </a>");
		
	}
?>