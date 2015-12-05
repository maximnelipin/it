<?php
	//открываем сессию
	session_start();
	//Файл настроек для подключения к серверу LDAP
	include_once $_SERVER['DOCUMENT_ROOT'].'/php_scripts/ldap_conf.php';
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
		//Если пользователь принадлежит группе
		if ($check_num['count']!=0)
		{
			$_SESSION['user_id']=$login;
			if(isset($_GET['link']))
			{
				
				//Формируем несколько параметров
				$link=str_replace('==','&',$_GET['link']);
				if($conn!=null){ldap_unbind($conn);}
				//Переходим на нужную ссылку				
				header("Location: ..".$link);
				exit;
			}
			else 
			{
				if($conn!=null){ldap_unbind($conn);}
				header("Location: /php_scripts/main.php");
				exit;
			}
			
			
		}
		else { 
			if($conn!=null){ldap_unbind($conn);	}	
			die ("Доступ закрыт. <a href='index.php'> Попробовать ещё раз </a>");
		}
		
	}
?>