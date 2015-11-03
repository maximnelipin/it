<?php
	session_start();
	if(isset($_SESSION['user_id']))
	{	//Файл настроек для подключения к серверу LDAP
		include_once $_SERVER['DOCUMENT_ROOT'].'/php_scripts/ldap_conf.php';
		//Файл с функциями
		include_once $_SERVER['DOCUMENT_ROOT'].'/php_scripts/func.php';
		//Файл подключения к БД
		include_once $_SERVER['DOCUMENT_ROOT'].'/php_scripts/mysql_conf.php';
		//Подключаемся к LDAP
		$conn=ldap_connect($host, $port) or die("LDAP сервер не доступен");
		//Включаем протокол третьей версии
		ldap_set_option($conn, LDAP_OPT_PROTOCOL_VERSION, 3);
		$pageTitle='Добавление сотрудников отдела ИТ';
		$ctrl='Добавленные сотрудники отдела ИТ';
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
			try
			{
				$sql='Create table if not exists tempit (login varchar(50) not null primary key, fio tinytext, func tinytext) CHARSET "utf8"';
				$sqlprep=$condb->prepare($sql);
				$sqlprep->execute();
				
			}
			catch (PDOExeption $e)
			{
				include '../form/errorhtml.php';
				exit;
			}
			//------------------Подготовка запросов
			//Вставка во временную таблицу
			$sqlit='insert into tempit set  login=:login, fio=:fio, func=:func';
			$sqlprepit=$condb->prepare($sqlit);
			//Поиск в базе
			$sqlsi='select login from itusers where login=:login';
			$sqlprepsi=$condb->prepare($sqlsi);
			//Вставка в базу
			$sqlii='insert into itusers set  login=:login, fio=:fio, func=:func';
			$sqlprepii=$condb->prepare($sqlii);
			//Обновление в базе
			$sqlui='update itusers set fio=:fio, func=:func WHERE login=:login';
			$sqlprepui=$condb->prepare($sqlui);
			//Выюорка из базы
			$sqlsi2='SELECT * FROM itusers ORDER BY fio LIMIT 30';
			$sqlprepsi2=$condb->prepare($sqlsi2);
			//Поиск во временной таблице
			$sqlst='select login from tempit where login=:login';
			$sqlprepst=$condb->prepare($sqlst);
			//Удаление из базы
			$sqldi='delete from itusers where login=:login';
			$sqlprepdi=$condb->prepare($sqldi);
			//Цикл перебора записей LDAP и внесения их в таблицу
			while ($i<$res['count'])
			{
				//Индексы полей выборки LDAP в НИЖНЕМ РЕГИСТРЕ
				//Обнуляем перменные в начале итерации
				$func='';
				$fio='';
				$login='';
				
				//присваиваем переменным значения из очередной строки результата запроса LDAP
				$fio=$res[$i]['name'][0];
				$login=$res[$i]['userprincipalname'][0];
				//Если должность есть, то ставим так же занносим её в переменную
				if(isset($res[$i]['title'][0]))
					{$func=$res[$i]['title'][0];}					
				//Заполняем временную таблицу записями из AD
				try 
				{
					
					$sqlprepit->bindValue(':login',$login);
					$sqlprepit->bindValue(':fio',$fio);
					$sqlprepit->bindValue(':func',$func);
					$sqlprepit->execute();
				}
				catch (PDOExeption $e)
				{
					$sql=$sqlit;
					include '../form/errorhtml.php';
					exit;
				}
				//Делаем запрос на выборку записи из таблицы itusers с логином $login
				try
				{
					
					$sqlprepsi->bindValue(':login',$login);
					$sqlprepsi->execute();
					
				}
				catch (PDOExeption $e)
				{
					$sql=$sqlsi;
					include '../form/errorhtml.php';
					exit;
				}
				
				
				//Если пользователя в таблице itusers нет
				if ($sqlprepsi->rowCount()==0)
				{
					//Добавляем его в таблицу
					try 
					{						
						$sqlprepii->bindValue(':login',$login);
						$sqlprepii->bindValue(':fio',$fio);
						$sqlprepii->bindValue(':func',$func);
						$sqlprepiit->execute();
					}					
					catch (PDOException $e)
					{
						$sql=$sqlii;
						include '../form/errorhtml.php';
						exit;
					}								
				}
				else
				{	//если есть такой логин в таблице, обновляем связанную запись
					try {
						$sqlprepui->bindValue(':login',$login);
						$sqlprepui->bindValue(':fio',$fio);
						$sqlprepui->bindValue(':func',$func);
						$sqlprepui->execute();
					}
					catch (PDOException $e)
					{
						$sql=$sqlui;		
						include '../form/errorhtml.php';
						exit;
					}					
				}
				//Инкремент переменной цикла-счётчика записей
			 	$i++;			
			}
			//делаем выборку из itusers
			try 
			{
				$sqlprepsi2->execute();
			}
			catch (PDOException $e)
			{
				$sql=$sqlsi2;
				include '../form/errorhtml.php';
				exit;
			}
			if($sqlprepsi2->rowCount()>0)
			{
				//перебираем записи из itusers
				$ressi2=$sqlprepsi2->fetchall();
				foreach ($ressi2 as $res)
				{	//Делаем выборку из tempit
					try 
					{
						$sqlprepst->bindValue(':login',$res['login']);
						$sqlprepst->execute();
					}
					catch (PDOException $e)
					{
						$sql=$sqlst;
						include '../form/errorhtml.php';
						exit;
					}
					
					//Если логин есть и в itusers, и в tempit(AD)
					if ($sqlprepst->rowCount()>0)
					{	//Выводим его как результат синхронизации
						echo '<tr><td>'.html($res['fio']).'</td><td>'.html($res['func']).'</td><td>'.html($res['login']).'</td> </tr>';					
					}
					else 
					{	//иначе удаяем запись с таким логином из таблицы itusers
						try 
						{
							$sqlprepdi->bindValue(':login',$res['login']);
							$sqlprepst->execute();
						}
						catch (PDOException $e)
						{
							$sql=$sqldi;
							include '../form/errorhtml.php';
							exit;
						}
					}
				}
			}
			//после окончания цикла заканчиваем вывод таблицы
			echo '</table>';
			header('Location .');
			//Чистим временную таблицу
			try 
			{
				$sql='delete from tempit';
				$sqlprep->prepare($sql);
				$sqlprep->execute();
			}
			catch (PDOException $e)
			{
				include '../form/errorhtml.php';
				exit;
			}
			
			
		}
		//Если скрипт открыт не через main, то отправляем на главную
		else header('Location ../php_scripts/main.php');
		//Закрываем соединение с базой
		if($conn!=null){ldap_unbind($conn);}
		if($condb!=null) {$condb=NULL;}
		
	}
	//Если без авторизации-на страницу авторизации
	else header('Location: ../index.php');
	exit;
?>