<?php
	session_start();
	if(isset($_SESSION['user_id']))
	{	include_once 'ldap_conf.php';
		include 'mysql_conf.php';
		try {
			$condb=new PDO('mysql:host='.$hostsql.';dbname='.$dbname, $dbuser, $dbpwd);
			$condb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$condb->exec('SET NAMES "utf8"');
		}
		catch (PDOException $e)
		{
			include '../form/errorhtml.php';
			exit;
		}
		//Подключаемся к LDAP
		$conn=ldap_connect($host, $port) or die("LDAP сервер не доступен");
		//Включаем протокол третьей версии
		ldap_set_option($conn, LDAP_OPT_PROTOCOL_VERSION, 3);
		include $_SERVER['DOCUMENT_ROOT'].'/form/addithtml.php';
		if (isset($_POST['addit']))	
		{
			
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
   					<caption>Сотрудники ИТ-отдела</caption>
  					 <tr>
    				<th>ФИО</th>
    				<th>Логин</th>
    				<th>Должность</th>    			
   					</tr>';
			//создаем временную таблицу для удаления записей, которых нет в AD.
			$sql='Create table if not exists tempit (login varchar(50) not null primary key, fio tinytext, func tinytext) CHARSET "utf8"';
			$condb->exec($sql);
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
				//Заполняем временную таблицу записями из AD
				$sql='insert into tempit set  login="'.$login.'", fio="'.$name.'", func="'.$title.'"';
				$condb->exec($sql);
				//Делаем запрос на выборку записи из таблицы itusers с логином $login
				$sql='select login from itusers where login="'.$login.'"';
				$ressql=$condb->query($sql);
				//Если пользователя в таблице itusers нет
				if (!($ressql->fetch(PDO::FETCH_ASSOC)))
				{
					//Добавляем его в таблицу
					try {
						
						$sql='insert into itusers set login="'.$login.'", fio="'.$name.'", func="'.$title.'"';
						$condb->exec($sql);
					}					
					catch (PDOException $e)
					{
					
						include '../form/errorhtml.php';
						exit;
					}								
				}
				else
				{	//если есть такой логин в таблице, обновляем связанную запись
					try {
						$sql='update itusers set fio="'.$name.'", func="'.$title.'" where login="'.$login.'"';
						$condb->exec($sql);
					}
					catch (PDOException $e)
					{
								
						include '../form/errorhtml.php';
						exit;
					}					
				}
				//Инкремент переменной цикла-счётчика записей
			 	$i++;			
			}
			//делаем выборку из itusers
			$sql='select * from itusers order by fio';
			$ressql=$condb->query($sql);
			//перебираем записи из itusers
			while ($res=$ressql->fetch(PDO::FETCH_ASSOC))
			{	//Делаем выборку из tempit
				$sql='select login from tempit where login="'.$res['login'].'"';
				$restempit=$condb->query($sql);
				//Если логин есть и в itusers, и в tempit(AD)
				if ($restempit->fetch(PDO::FETCH_ASSOC))
				{	//Выводим его как результат синхронизации
					echo '<tr><td>'.$res['fio'].'</td><td>'.$res['func'].'</td><td>'.$res['login'].'</td> </tr>';					
				}
				else 
				{	//иначе удаяем запись с таким логином из таблицы itusers
					try {
						$sql='delete from itusers where login="'.$res['login'].'"';
						$condb->exec($sql);
					}
					catch (PDOException $e)
					{
					
						include '../form/errorhtml.php';
						exit;
					}
				}
				
			}
			//после окончания цикла заканчиваем вывод таблицы
			echo '</table>';
			header('Location .');
			//Чистим временную таблицу
			$sql='delete from tempit';
			$condb->exec($sql);
			
		}
		//Если скрипт открыт не через main, то отправляем на главную
		else header('Location ../php_scripts/main.php');
		if($conn!=null){ldap_unbind($conn);}
		if($condb!=null) {$condb=NULL;}
		
	}
	//Если без авторизации-на страницу авторизации
	else header('Location: ../index.php');
	exit;
?>