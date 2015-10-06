<?php
	session_start();
	if(isset($_SESSION['user_id']))
	{	include_once 'ldap_conf.php';
		include 'mysql_conf.php';
		try {
			$conbd=new PDO('mysql:host='.$hostsql.';dbname='.$dbname, $dbuser, $dbpwd);
			$conbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$conbd->exec('SET NAMES "utf8"');
		}
		catch (PDOException $e)
		{
			$error= 'Не удалось выполнить запрос'.$e->getMessage();	
			$urlerr=$_SERVER['PHP_SELF'];
			//$_SESSION['erroor']=$error;
			//$_SESSION['urlerr']=$urlerr;
			include '../form/errorhtml.php';
			exit;
		}
		include $_SERVER['DOCUMENT_ROOT'].'/form/addithtml.php';
		if (isset($_POST['addit']))	
		{
			//Подключаемся к LDAP
			$conn=ldap_connect($host, $port) or die("LDAP сервер не доступен");
			//Включаем протокол третьей версии
			ldap_set_option($conn, LDAP_OPT_PROTOCOL_VERSION, 3);
			//Задаем атрибуты, которые необходимо выбрать
			$attr=array("title", "userPrincipalName", "name" );
			//Если соединение успешно
			if($conn)
			{
				//Входим в ldap
				$bind=ldap_bind($conn,$usrd,$pwdd);
				//Если успешно
				if($bind)
				{
					//выбираем пользователей-текущих сотрудников отдела, группа it_core
					$sel=ldap_search($conn, $itou, 'memberOf='.$groupit, $attr);
					//получаем результаты  запроса
					$res=ldap_get_entries($conn, $sel);
				}
				else die("Введён неверный логин или пароль или недоступен сервер LDAP. <a href='main.php'> Попробовать ещё раз </a>");
			}
			//Обнуляем переменную цикла
			$i=0;
			//Готовим шапку таблицы
			echo '<table border=2>
   					<caption>Обновленные и добавленые сотрудники</caption>
  					 <tr>
    				<th>ФИО</th>
    				<th>Логин</th>
    				<th>Должность</th>    			
   					</tr>';
			//Цикл перебора записей LDAP и внесения их в таблицу
			while ($i<$res['count'])
			{
				//Индексы полей выборки LDAP в НИЖНЕМ РЕГИСТРЕ
				//Обнуляем перменные в начале итерации
				$title='';
				$name='';
				$login='';
				
				//присваиваем переменным значения из очередной строки результата запроса LDAP
				$name=$res[$i]['name'][0]." ";
				$login=$res[$i]['userprincipalname'][0]." ";
				//Если должность есть, то ставим так же занносим её в переменную
				//иначе без проверки будет ошибка
				if(isset($res[$i]['title'][0]))
					{$title=$res[$i]['title'][0]." ";}
				
				//Делаем запрос на выборку записи из таблицы itusers с логином $login
				$sql='select login from itusers where login="'.$login.'"';
				$ressql=$conbd->query($sql);
				//Если пользователя в таблице нет
				if (!($ressql->fetch(PDO::FETCH_ASSOC)))
				{
					//Добавляем его в таблицу
					try {
						
						$sql='insert into itusers set login="'.$login.'", fio="'.$name.'", func="'.$title.'"';
						$conbd->exec($sql);
					}					
					catch (PDOException $e)
					{
					
						$error= 'Не удалось выполнить запрос'.$e->getMessage().$login;
						$urlerr=$_SERVER['PHP_SELF'];
						include '../form/errorhtml.php';
						exit;
					}
					//Выводим добавленное поле
					echo '<tr><td>'.$name.'</td><td>'.$login.'</td><td>'.$title.'</td> </tr>';
					
				}
				else
				{	//если есть такой логин в таблице, обновляем связанную запись
					try {
						$sql='update itusers set fio="'.$name.'", func="'.$title.'" where login="'.$login.'"';
						$conbd->exec($sql);
					}
					catch (PDOException $e)
					{
								
						$error= 'Не удалось выполнить запрос'.$e->getMessage().$login;
						$urlerr=$_SERVER['PHP_SELF'];							
						include '../form/errorhtml.php';
						exit;
					}
					//Выводим обновлённое поле
					echo '<tr><td>'.$name.'</td><td>'.$login.'</td><td>'.$title.'</td> </tr>';
							
				}
				//Инкремент переменной цикла-счётчика записей
			 	$i++;			
			}
			//после окончания цикла заканчиваем вывод таблицы
			echo '</table>';
			header('Location .');
		}
		//Если скрипт открыт не через main, то отправляем на главную
		else header('Location ../php_scripts/main.php');
	
	}
	//Если без авторизации-на страницу авторизации
	else header('Location ../index.php');
?>