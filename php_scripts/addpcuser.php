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
		include $_SERVER['DOCUMENT_ROOT'].'/form/addpcuserhtml.php';
		
		if (isset($_POST['addpcuser']))	
		{
			
			//Задаем атрибуты, которые необходимо выбрать
			$attruser=array("title", "userPrincipalName", "displayName", "department" );
			$attrpc=array("name", "description");
			//Если соединение успешно
			if($conn)
			{
				//Входим в ldap
				$bind=ldap_bind($conn,$usrd,$pwdd);
				//Если успешно
				if($bind)
				{
					//выбираем пользователей-текущих сотрудников отдела, группа it_core
					$selit=ldap_search($conn, $itou, 'memberOf='.$groupit, $attruser);
					//получаем результаты  запроса
					$resit=ldap_get_entries($conn, $selit);					
					//выбираем пользователей
					$seluser=ldap_search($conn, $userou, '(&(objectCategory=user)(objectClass=user))', $attruser);
					//получаем пользователей из AD
					$resuser=ldap_get_entries($conn, $seluser);
					//выбираем компьютеры из первого OU
					$selpc1=ldap_search($conn, $pc1ou, '(&(objectCategory=computer)(objectClass=computer))', $attrpc);
					//Получаем результаты выборки
					$respc1=ldap_get_entries($conn, $selpc1);
					//выбираем компьютеры из первого OU
					$selpc2=ldap_search($conn, $pc2ou, '(&(objectCategory=computer)(objectClass=computer))', $attrpc);
					//Получаем результаты выборки
					$respc2=ldap_get_entries($conn, $selpc2);
				}
				else die("Введён неверный логин или пароль или недоступен сервер LDAP. <a href='main.php'> Попробовать ещё раз </a>");
			}
			else die ("Нет подключения к LDAP");
			//Обнуляем переменную цикла
			$i=0;
			//Готовим шапку таблицы
			echo '<table>
   					<caption>Пользователи</caption>
  					 <tr>
					<th>ФИО</th>
    				<th>Должность</th>
					<th>Отдел</th>
					<th>Логин</th>
   					</tr>';
			//создаем временную таблицу для удаления записей, которых нет в AD.
			$sql='Create table if not exists tempuser (login varchar(50) not null primary key, fio tinytext, func tinytext, dept tinytext) CHARSET "utf8"';
			$condb->exec($sql);
			$sql='delete from tempuser where 1';
			$condb->exec($sql);
			$sql='Create table if not exists temppc (name varchar(30) not null primary key, login varchar(50),descrip tinytext) CHARSET "utf8"';
			$condb->exec($sql);
			$sql='delete from temppc where 1';
			$condb->exec($sql);
			//---------------------------Занесение пользователей в базу------------------------
			//Цикл перебора записей  LDAP и внесения их в таблицу
			while ($i<$resuser['count'])
			{
				//Индексы полей выборки LDAP в НИЖНЕМ РЕГИСТРЕ
				//Обнуляем перменные в начале итерации				
				$title='';				
				$login='';
				$dept='';
				$name='';
				
				//присваиваем переменным значения из очередной строки результата запроса LDAP
				$name=$resuser[$i]['displayname'][0];
				$login=$resuser[$i]['userprincipalname'][0];
				//Если должность или отдел есть, то ставим так же занносим её в переменную
				//иначе без проверки будет ошибка
				if(isset($resuser[$i]['title'][0]))
					{$title=$resuser[$i]['title'][0];}		
				if(isset($resuser[$i]['department'][0]))
					{$dept=$resuser[$i]['department'][0];}
				//Заполняем временную таблицу записями из AD
				$sql='insert into tempuser set  login="'.$login.'", fio="'.$name.'", func="'.$title.'", dept="'.$dept.'"';
				$condb->exec($sql);
				//Делаем запрос на выборку записи из таблицы listuser с логином $login
				$sql='select login from listuser where login="'.$login.'"';
				$ressql=$condb->query($sql);
				//Если пользователя в таблице itusers нет
				if (!($ressql->fetch(PDO::FETCH_ASSOC)))
				{
					//Добавляем его в таблицу
					try {
						
						$sql='insert into listuser set  login="'.$login.'", fio="'.$name.'", func="'.$title.'", dept="'.$dept.'"';
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
						$sql='update listuser set   fio="'.$name.'", func="'.$title.'", dept="'.$dept.'" where login="'.$login.'"';
						$condb->exec($sql);
					}
					catch (PDOException $e)
					{
								
						include '../form/errorhtml.php';
						exit;
					}					
				}
				$i++;
			}
				//-------------Добавление ИТ-спецов к общему списку
				$i=0;
				while ($i<$resit['count'])
				{
					//Индексы полей выборки LDAP в НИЖНЕМ РЕГИСТРЕ
					//Обнуляем перменные в начале итерации
					$title='';
					$login='';
					$dept='';
					$name='';
				
					//присваиваем переменным значения из очередной строки результата запроса LDAP
					$name=$resit[$i]['displayname'][0];
					$login=$resit[$i]['userprincipalname'][0];
					//Если должность или отдел есть, то ставим так же занносим её в переменную
					//иначе без проверки будет ошибка
					if(isset($resit[$i]['title'][0]))
					{$title=$resit[$i]['title'][0];}
					if(isset($resit[$i]['department'][0]))
					{$dept=$resit[$i]['department'][0];}
					//Заполняем временную таблицу записями из AD
					$sql='insert into tempuser set  login="'.$login.'", fio="'.$name.'", func="'.$title.'", dept="'.$dept.'"';
					$condb->exec($sql);
					//Делаем запрос на выборку записи из таблицы listuser с логином $login
					$sql='select login from listuser where login="'.$login.'"';
					$ressql=$condb->query($sql);
					//Если пользователя в таблице itusers нет
				if (!($ressql->fetch(PDO::FETCH_ASSOC)))
				{
					//Добавляем его в таблицу
					try {
				
						$sql='insert into listuser set  login="'.$login.'", fio="'.$name.'", func="'.$title.'", dept="'.$dept.'"';
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
					$sql='update listuser set   fio="'.$name.'", func="'.$title.'", dept="'.$dept.'" where login="'.$login.'"';
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
			//делаем выборку из listuser
			$sql='select * from listuser order by fio';
			$ressql=$condb->query($sql);
			//перебираем записи из listuser
			while ($res=$ressql->fetch(PDO::FETCH_ASSOC))
			{	//Делаем выборку из tempuser
				$sql='select login from tempuser where login="'.$res['login'].'"';
				$restempuser=$condb->query($sql);
				//Если логин есть и в listuser, и в tempuser(AD)
				if ($restempuser->fetch(PDO::FETCH_ASSOC))
				{	//Выводим его как результат синхронизации
					echo '<tr><td>'.$res['fio'].'</td><td>'.$res['func'].'</td><td>'.$res['dept'].'</td><td>'.$res['login'].'</td> </tr>';					
				}
				else 
				{	//иначе удаяем запись с таким логином из таблицы listuser
					try {
						$sql='delete from listuser where login="'.$res['login'].'"';
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
			//----------------------------------------------------------------------------------
			//-----------------------------Добавляем ПК в базу----------------------------------
			//----------------------------------------------------------------------------------
			echo '<h2 class="title"> Добавленные ПК</h2>';
			echo '<table>
   					<caption>Компьютеры</caption>
  					 <tr>
					<th>Имя</th>
    				<th>Логин пользователя</th>
					<th>Описание из AD</th>
					<th>Примечание</th>
   					</tr>';
			
			//-------Добавляем во временную таблицу----------
			
			//Цикл добавления из первого OU
			$i=0;
			while ($i<$respc1['count'])
			{
				//Индексы полей выборки LDAP в НИЖНЕМ РЕГИСТРЕ
				//Обнуляем перменные в начале итерации
				$descrip='';
				$namepc='';
				
				//присваиваем переменным значения из очередной строки результата запроса LDAP
				$namepc=$respc1[$i]['name'][0]." ";
				//Если должность или отдел есть, то ставим так же занносим её в переменную
				//иначе без проверки будет ошибка
				if(isset($respc1[$i]['description'][0]))
				{$descrip=$respc1[$i]['description'][0]." ";}
				
				//Заполняем временную таблицу записями из AD
				try 
				{
					$sql='insert into temppc set  name="'.$namepc.'", descrip="'.$descrip.'"';
					$condb->exec($sql);
				}
				catch (PDOException $e)
				{
					include '../form/errorhtml.php';
					exit;
				}
				
				$i++;
			}
			$i=0;
			while ($i<$respc2['count'])
			{
				//Индексы полей выборки LDAP в НИЖНЕМ РЕГИСТРЕ
				//Обнуляем перменные в начале итерации
				$descrip='';
				$namepc='';
				
				//присваиваем переменным значения из очередной строки результата запроса LDAP
				$namepc=$respc2[$i]['name'][0]." ";
			
				//Если должность или отдел есть, то ставим так же занносим её в переменную
				//иначе без проверки будет ошибка
				if(isset($respc2[$i]['description'][0]))
				{$descrip=$respc2[$i]['description'][0]." ";}
			
				//Заполняем временную таблицу записями из AD
				try 
				{
					$sql='insert into temppc set  name="'.$namepc.'", descrip="'.$descrip.'"';
					$condb->exec($sql);
				}
				catch (PDOException $e)
				{
					include '../form/errorhtml.php';
					exit;
				}
				
				$i++;
				//echo '<tr><td>'.$namepc.'</td><td>'.$descrip.'</td></tr>';
			}
			
			//--------Добавляем во временную таблицу Логины пользователей
			//Выбираем всех пользователей
			$sql='select login, fio from listuser';
			$resusersql=$condb->query($sql);
			//пока всех не переберём
			while ($resuser=$resusersql->fetch(PDO::FETCH_ASSOC))
			{
				//отбираем ПК, у которых в описании стоит выбранный пользователь
				$sql='select name from temppc where descrip like "%'.$resuser['fio'].'%"';
				$respcsql=$condb->query($sql);
				//заносим в поле login в отобранных записях пользователя
				while ($respc=$respcsql->fetch(PDO::FETCH_ASSOC))
				{
					try {
						$sql='update temppc set login="'.$resuser['login'].'" where name="'.$respc['name'].'"';
						$condb->exec($sql);
					}
					catch (PDOException $e)
					{
							
						include '../form/errorhtml.php';
						exit;
					}
				}
			}
			//------Пененосим в базу----------
			
			//делаем выборку из listpc
			$sql='select * from temppc order by name';
			$ressql=$condb->query($sql);
			//Цикл перебора записей  LDAP и внесения их в таблицу
			while ($res=$ressql->fetch(PDO::FETCH_ASSOC))
			{				
				//Делаем запрос на выборку записи из таблицы listpc с именем $res['name']
				$sql='select name from listpc where name="'.$res['name'].'"';
				$restempsql=$condb->query($sql);
				//Если ПК в таблице listpc нет
				if (!($restempsql->fetch(PDO::FETCH_ASSOC)))
				{
					//Добавляем его в таблицу
					try {
			
						$sql='insert into listpc set  name="'.$res['name'].'", login="'.$res['login'].'", descrip="'.$res['descrip'].'"';
						$condb->exec($sql);
					}
					catch (PDOException $e)
					{
							
						include '../form/errorhtml.php';
						exit;
					}
				}
				else
				{	//если есть такой ПК в таблице, обновляем связанную запись
					try {
						$sql='update listpc set   login="'.$res['login'].'", descrip="'.$res['descrip'].'" where name="'.$res['name'].'"';
						$condb->exec($sql);
					}
					catch (PDOException $e)
					{
			
						include '../form/errorhtml.php';
						exit;
					}
				}
			}
			
			
			//Удаляем отсутсвующие ПК в AD из базы
			//делаем выборку из listpc
			$sql='select * from listpc order by name';
			$ressql=$condb->query($sql);
			//перебираем записи из listpc
			while ($res=$ressql->fetch(PDO::FETCH_ASSOC))
			{	//Делаем выборку из tempuser
				$sql='select name from temppc where name="'.$res['name'].'"';
				$restemppc=$condb->query($sql);
				//Если имя есть и в listuser, и в temppc(AD)
				if ($restemppc->fetch(PDO::FETCH_ASSOC))
				{	//Выводим его как результат синхронизации
					echo '<tr><td>'.$res['name'].'</td><td>'.$res['login'].'</td><td>'.$res['descrip'].'</td><td>'.$res['note'].'</td> </tr>';
				}
				else
				{	//иначе удаяем запись с таким именем из таблицы listuser
					try {
						$sql='delete from listpc where name="'.$res['name'].'"';
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
			//header('Location .');
			//Чистим временную таблицу
			$sql='delete from tempuser';
			$condb->exec($sql);
			$sql='delete from temppc';
			$condb->exec($sql);
			
		}
		//Если скрипт открыт не через main, то отправляем на главную
		else header('Location ../php_scripts/main.php');
		if($conn!=null){
			ldap_unbind($conn);}
		if($condb!=null) {
			$condb=NULL;}
		
	}
	//Если без авторизации-на страницу авторизации
	else header('Location: ../index.php');
	exit;
?>