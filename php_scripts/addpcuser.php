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
			$error= 'Не удалось выполнить запрос'.$e->getMessage();	
			$urlerr=$_SERVER['PHP_SELF'];
			//$_SESSION['erroor']=$error;
			//$_SESSION['urlerr']=$urlerr;
			include '../form/errorhtml.php';
			exit;
		}
		//Подключаемся к LDAP
		$conn=ldap_connect($host, $port) or die("LDAP сервер не доступен");
		//Включаем протокол третьей версии
		ldap_set_option($conn, LDAP_OPT_PROTOCOL_VERSION, 3);
		include $_SERVER['DOCUMENT_ROOT'].'/form/addithtml.php';
		
		if (isset($_POST['addpcuser']))	
		{
			
			//Задаем атрибуты, которые необходимо выбрать
			$attruser=array("title", "userPrincipalName", "name", "department" );
			$attrpc=array("name", "description");
			//Если соединение успешно
			if($conn)
			{
				//Входим в ldap
				$bind=ldap_bind($conn,$usrd,$pwdd);
				//Если успешно
				if($bind)
				{
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
			//Обнуляем переменную цикла
			$i=1;
			//Готовим шапку таблицы
			echo '<table>
   					<caption>Сотрудники ИТ-отдела</caption>
  					 <tr>
					<th>ФИО</th>
    				<th>Должность</th>
					<th>Отдел</th>
					<th>Логин</th>
   					</tr>';
			//создаем временную таблицу для удаления записей, которых нет в AD.
			$sql='Create table if not exists tempuser (login varchar(50) not null primary key, fio tinytext, func tinytext, dept tinytext) CHARSET "utf8"';
			$condb->exec($sql);
			$sql='delete from tempuser';
			$condb->exec($sql);
			$sql='Create table if not exists temppc (name varchar(30) not null primary key, login varchar(50),descrip tinytext) CHARSET "utf8"';
			$condb->exec($sql);
			$sql='delete from temppc';
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
				$name=$resuser[$i]['name'][0]." ";
				$login=$resuser[$i]['userprincipalname'][0]." ";
				//Если должность или отдел есть, то ставим так же занносим её в переменную
				//иначе без проверки будет ошибка
				if(isset($resuser[$i]['title'][0]))
					{$title=$resuser[$i]['title'][0]." ";}		
				if(isset($resuser[$i]['department'][0]))
					{$dept=$resuser[$i]['department'][0]." ";}
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
					
						$error= 'Не удалось выполнить запрос'.$e->getMessage().$login."insert";
						$urlerr=$_SERVER['PHP_SELF'];
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
								
						$error= 'Не удалось выполнить запрос'.$e->getMessage().$login."update";
						$urlerr=$_SERVER['PHP_SELF'];							
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
				$restempit=$condb->query($sql);
				//Если логин есть и в listuser, и в tempuser(AD)
				if ($restempit->fetch(PDO::FETCH_ASSOC))
				{	//Выводим его как результат синхронизации
					echo '<tr><td>'.$res['fio'].'</td><td>'.$res['func'].'</td><td>'.$res['dept'].'</td><td>'.$res['login'].'</td> </tr>';					
				}
				else 
				{	//иначе удаяем запись с таким логином из таблицы itusers
					try {
						$sql='delete from listuser where login="'.$res['login'].'"';
						$condb->exec($sql);
					}
					catch (PDOException $e)
					{
					
						$error= 'Не удалось выполнить запрос'.$e->getMessage().$login;
						$urlerr=$_SERVER['PHP_SELF'];
						include '../form/errorhtml.php';
						exit;
					}
				}
				
			}
			//после окончания цикла заканчиваем вывод таблицы
			//echo '</table>';
			//----------------------------------------------------------------------------------
			//-----------------------------Добавляем ПК в базу----------------------------------
			//----------------------------------------------------------------------------------
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
				$sql='insert into temppc set  name="'.$namepc.'", descrip="'.$descrip.'"';
				$condb->exec($sql);
				echo '<tr><td>'.$namepc.'</td><td>'.$descrip.'</td></tr>';
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
				$sql='insert into temppc set  name="'.$namepc.'", descrip="'.$descrip.'"';
				$condb->exec($sql);
				$i++;
				//echo '<tr><td>'.'123'.'</td><td>'.'123'.'</td><td>'.'123'.'</td></tr>';
				echo '<tr><td>'.$namepc.'</td><td>'.$descrip.'</td></tr>';
			}
			
			//после окончания цикла заканчиваем вывод таблицы
			echo '</table>';
			//header('Location .');
			//Чистим временную таблицу
			$sql='delete from tempuser';
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
	else header('Location ../index.php');
?>