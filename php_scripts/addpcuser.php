<?php
	session_start();
	if(isset($_SESSION['user_id']))
	{		
		if (isset($_POST['addpcuser']))	
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
			$pageTitle='Добавление пользователей и ПК';
			$ctrl='Добавленные пользователи';
			include $_SERVER['DOCUMENT_ROOT'].'/form/addpcuserhtml.php';
			
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
					$i=0;
					//Выбираем пользоватлей из всех ou
					foreach ($userous as $userou)
					{
						//выбираем пользователей
						$seluser=ldap_search($conn, $userou, '(&(objectCategory=user)(objectClass=user))', $attruser);
						//получаем пользователей из AD
						$resusers[$i]=ldap_get_entries($conn, $seluser);
						$i++;
					}
					$i=0;
					//Выбираем компьютеры из всех ou
					foreach ($pcous as $pcou)
					{
							//выбираем компьютеры OU
						$selpc=ldap_search($conn, $pcou, '(&(objectCategory=computer)(objectClass=computer))', $attrpc);
						//Получаем результаты выборки
						$respcs[$i]=ldap_get_entries($conn, $selpc);
						$i++;
					}
					
				}
				else die("Введён неверный логин или пароль или недоступен сервер LDAP.");
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
			//---------------------------создаем временную таблицу для удаления записей, которых нет в AD.
			$sql='Create table if not exists tempuser (login varchar(50) not null primary key, fio tinytext, func tinytext, dept tinytext) CHARSET "utf8"';
			$condb->exec($sql);
			//очищаем временные таблицы
			delFromTbl('tempuser',$condb);
			$sql='Create table if not exists temppc (name varchar(30) not null primary key, login varchar(50),descrip tinytext) CHARSET "utf8"';
			$condb->exec($sql);
			//очищаем временные таблицы
			delFromTbl('temppc',$condb);
			//--------------------Подготовка sql-запросов------------------------------
			//Вствка во временную таблицу пользователей
			$sqlitu='insert into tempuser set login=:login, fio=:fio, func=:func, dept=:dept';
			$sqlpritu=$condb->prepare($sqlitu);
			//Вставка в таблицу пользователей
			$sqlilu='insert into listuser set login=:login, fio=:fio, func=:func, dept=:dept';
			$sqlprilu=$condb->prepare($sqlilu);
			//обновление в таблице пользователей
			$sqlulu='update listuser set fio=:fio, func=:func, dept=:dept where login=:login';
			$sqlprulu=$condb->prepare($sqlulu);
			//выборка из таблицы пользователей
			$sqlslu='select login from listuser where login=:login';
			$sqlprslu=$condb->prepare($sqlslu);
			//выборка из временной таблицы пользователей
			$sqlstu='select login from tempuser where login=:login';
			$sqlprstu=$condb->prepare($sqlstu);
			//удалени из таблицы пользователей
			$sqldlu='delete from listuser where login=:login';
			$sqlprdlu=$condb->prepare($sqldlu);
			//Для ПК
			//Вствка во временную таблицу ПК
			$sqlitpc='insert into temppc set name=:name, descrip=:descrip, login=:login';
			$sqlpritpc=$condb->prepare($sqlitpc);
			//обновление во временной таблице ПК
			$sqlutpc='update temppc set login=:login where name=:name';
			$sqlprutpc=$condb->prepare($sqlutpc);
			//посик во временной таблице ПК
			$sqlstpc='select name from temppc where descrip LIKE :descrip';
			$sqlprstpc=$condb->prepare($sqlstpc);
			//Вставка в таблицу ПК
			$sqlilpc='insert into listpc set login=:login,name=:name, descrip=:descrip';
			$sqlprilpc=$condb->prepare($sqlilpc);
			//обновление в таблице ПК
			$sqlulpc='update listpc set login=:login, descrip=:descrip where name=:name';
			$sqlprulpc=$condb->prepare($sqlulpc);
			//выборка из таблицы ПК
			$sqlslpc='select name from listpc where name=:name';
			$sqlprslpc=$condb->prepare($sqlslpc);
			//выборка из таблицы ПК
			$sqlstpc1='select name from temppc where name=:name';
			$sqlprstpc1=$condb->prepare($sqlstpc1);
			//удалени из таблицы ПК
			$sqldlpc='delete from listpc where name=:name';
			$sqlprdlpc=$condb->prepare($sqldlpc);
			
			//---------------------------Занесение пользователей в базу------------------------
			//Цикл перебора записей  LDAP и внесения их в таблицу
			foreach ($resusers as $resuser)
			{	$i=0;
				while ($i<$resuser['count'])
				{
					//Индексы полей выборки LDAP в НИЖНЕМ РЕГИСТРЕ
					//присваиваем переменным значения из очередной строки результата запроса LDAP
					$valu['fio']=$resuser[$i]['displayname'][0];
					$valu['login']=$resuser[$i]['userprincipalname'][0];
					//Если должность или отдел есть, то ставим так же занносим её в переменную
					//иначе без проверки будет ошибка
					if(isset($resuser[$i]['title'][0]))
						{$valu['func']=$resuser[$i]['title'][0];}
					else $valu['func']='';
					if(isset($resuser[$i]['department'][0]))
						{$valu['dept']=$resuser[$i]['department'][0];}
					else $valu['dept']='';
					//Заполняем временную таблицу записями из AD	
					try {
						
						$sqlpritu->execute($valu);
					}
					catch (PDOException $e)
					{
						$sql=$sqlitu;
						include '../form/errorhtml.php';
						exit;
					}
					//Делаем запрос на выборку записи из таблицы listuser с логином $login
					try 
					{
						$valsu['login']=$valu['login'];
						$sqlprslu->execute($valsu);
					}
					catch (PDOException $e)
					{
						$sql=$sqlslu;
						include '../form/errorhtml.php';
						exit;
					}
					//Если пользователя в таблице listuser нет
					if($sqlprslu->rowCount()==0)
					
					{
						//Добавляем его в таблицу
						try {
							
							$sqlprilu->execute($valu);
						}					
						catch (PDOException $e)
						{
							$sql=$sqlilu;
							include '../form/errorhtml.php';
							exit;
						}								
					}
					else
					{	//если есть такой логин в таблице, обновляем связанную запись
						try {
							
							$sqlprulu->execute($valu);
						}
						catch (PDOException $e)
						{
							$sql=$sqlulu;
							include '../form/errorhtml.php';
							exit;
						}					
					}
					$i++;
				}
			}
				//-------------Добавление ИТ-спецов к общему списку
				$i=0;
				while ($i<$resit['count'])
				{
					//Индексы полей выборки LDAP в НИЖНЕМ РЕГИСТРЕ
					//присваиваем переменным значения из очередной строки результата запроса LDAP
					$valu['fio']=$resit[$i]['displayname'][0];
					$valu['login']=$resit[$i]['userprincipalname'][0];
					//Если должность или отдел есть, то ставим так же занносим её в переменную
					//иначе без проверки будет ошибка
					if(isset($resit[$i]['title'][0]))
						{$valu['func']=$resit[$i]['title'][0];}
					else $valu['func']='';
					if(isset($resit[$i]['department'][0]))
						{$valu['dept']=$resit[$i]['department'][0];}
					else $valu['dept']='';
					//Заполняем временную таблицу записями из AD	
					try 
					{
												
						$sqlpritu->execute($valu);
					}
					catch (PDOException $e)
					{
						$sql=$sqlitu;
						include '../form/errorhtml.php';
						exit;
					}
					
					//Делаем запрос на выборку записи из таблицы listuser с логином $login	
					try 
					{
					$valsu['login']=$valu['login'];
					$sqlprslu->execute($valsu);
					}
					catch (PDOExeption $e)
					{
						$sql=$sqlslu;
						include '../form/errorhtml.php';
						exit;
					}
					
					//Если пользователя в таблице listuser нет
				if ($sqlprslu->rowCount()==0)
				{
					//Добавляем его в таблицу
					try {
						$sqlprilu->execute($valu);
					}
					catch (PDOException $e)
					{
						$sql=$sqlilu;
						include '../form/errorhtml.php';
						exit;
					}
				}
				else
				{	//если есть такой логин в таблице, обновляем связанную запись
				try {					
					$sqlprulu->execute($valu);
				}
				catch (PDOException $e)
				{
					$sql=$sqlulu;
					include '../form/errorhtml.php';
					exit;
				}
				}
				//Инкремент переменной цикла-счётчика записей
			 	$i++;			
			}
			//делаем выборку из listuser
			try 
			{
				$sql='select * from listuser order by fio LIMIT 500';
				$sqlprep=$condb->prepare($sql);
				$sqlprep->execute();
			}
			catch (PDOExeption $e)
			{
				include '../form/errorhtml.php';
				exit;
			}
			if($sqlprep->rowCount()>0)
			{
				$result=$sqlprep->fetchall();
				//перебираем записи из listuser
				foreach($result as $res)
				{	//Делаем выборку из tempuser
					try 
					{
						$valsu['login']=$res['login'];
						$sqlprstu->execute($valsu);
					}
					catch (PDOExeption $e)
					{
						$sql=$sqlstu;
						include '../form/errorhtml.php';
						exit;
					}
					//Если логин есть и в listuser, и в tempuser(AD)
					if ($sqlprstu->rowCount()>0)
					{	//Выводим его как результат синхронизации
						echo '<tr><td>'.html($res['fio']).'</td><td>'.html($res['func']).'</td><td>'.html($res['dept']).'</td><td>'.html($res['login']).'</td> </tr>';					
					}
					else 
					{	//иначе удаяем запись с таким логином из таблицы listuser
						try 
						{							
							$sqlprdlu->execute($valsu);							
						}
						catch (PDOException $e)
						{
							$sql=$sqldlu;
							include '../form/errorhtml.php';
							exit;
						}
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
   					</tr>';
			
			//-------Добавляем во временную таблицу----------
			foreach ($respcs as $respc)
			{
				//Цикл добавления из первого OU
				$i=0;
				while ($i<$respc['count'])
				{
					//Индексы полей выборки LDAP в НИЖНЕМ РЕГИСТРЕ
					
					
					//присваиваем переменным значения из очередной строки результата запроса LDAP
					$valpc['name']=$respc[$i]['name'][0];
					$valpc['login']='';
					//Если должность или отдел есть, то ставим так же занносим её в переменную
					//иначе без проверки будет ошибка
					if(isset($respc[$i]['description'][0]))
					{$valpc['descrip']=$respc[$i]['description'][0];}
					else $valpc['descrip']='';
					
					//Заполняем временную таблицу записями из AD
					try 
					{
						$sqlpritpc->execute($valpc);						
					}
					catch (PDOException $e)
					{
						$sql=$sqlitpc;
						include '../form/errorhtml.php';
						exit;
					}
					
					$i++;
				}
			}
			$i=0;
			
			//--------Добавляем во временную таблицу Логины пользователей
			//Выбираем всех пользователей
			try {
				$sql='select login, fio from listuser where 1 LIMIT 500';
				$sqlprep=$condb->prepare($sql);
				$sqlprep->execute();
			}
			catch (PDOExeption $e)
			{
				include '../form/errorhtml.php';
				exit;
			}
			$respcsql=$condb->query($sql);
			if($sqlprep->rowCount()>0)
			{	
				$result=$sqlprep->fetchall();
				//пока всех не переберём
				foreach ($result as $resu)
				{
					try {
					//отбираем ПК, у которых в описании стоит выбранный пользователь
					$valstpc['descrip']="%".$resu["fio"]."%";				
					$sqlprstpc->execute($valstpc);
					
					}
					catch (PDOException $e)
					{
						$sql=$sqlstpc;
						include '../form/errorhtml.php';
						exit;
					}
					//Если пользователь логинился на ПК
					if($sqlprstpc->rowCount()>0)
					{
						$respcsqlu=$sqlprstpc->fetchall();
						//заносим в поле login в отобранных записях пользователя
						foreach ($respcsqlu as $respcu)
						{
							try {
								$valutpc['login']= $resu['login'];
								$valutpc['name']= $respcu['name'];							
								$sqlprutpc->execute($valutpc);							
							}
							catch (PDOException $e)
							{
								$sql=$sqlprutpc;
								include '../form/errorhtml.php';
								exit;
							}
						}
					}
				}
			}
			//------Пененосим в базу----------
			
			//делаем выборку из listpc
			try 
			{
				$sql='select * from temppc order by name';
				$sqlprep=$condb->prepare($sql);
				$sqlprep->execute();
			}
			catch (PDOExeption $e)
			{
				include '../form/errorhtml.php';
				exit;
			}
			if($sqlprep->rowCount()>0)
			{			
				$result=$sqlprep->fetchall();
				//Цикл перебора записей  LDAP и внесения их в таблицу
				foreach ($result as $res)
				{				
					
					try {
						//Делаем запрос на выборку записи из таблицы listpc с именем $res['name']
						$valslpc['name']=$res['name'];						
						$sqlprslpc->execute($valslpc);
					}
					catch (PDOException $e)
					{
						$sql=$sqlslpc;
						include '../form/errorhtml.php';
						exit;
					}
					//Подготовка значений для обновления и добавления в базу
					$valilpc['name']=$res['name'];
					$valilpc['login']=$res['login'];
					$valilpc['descrip']=$res['descrip'];
					
					if ($sqlprslpc->rowCount()==0)
					{	
						//Добавляем его в таблицу
						try 
						{							
							$sqlprilpc->execute($valilpc);							
						}
						catch (PDOException $e)
						{
							$sql=$sqlilpc;
							include '../form/errorhtml.php';
							exit;
						}
					}
					else
					{	//если есть такой ПК в таблице, обновляем связанную запись
						try 
						{							
							$sqlprulpc->execute($valilpc);							
						}
						catch (PDOException $e)
						{
							$sql=$sqlulpc;
							include '../form/errorhtml.php';
							exit;
						}
					}
				}
			}
			
			
			//Удаляем отсутсвующие ПК в AD из базы
			//делаем выборку из listpc
			try 
			{
				$sql='select * from listpc order by name';
				$sqlprep=$condb->prepare($sql);
				$sqlprep->execute();
			}
			catch (PDOExeption $e)
			{
				include '../form/errorhtml.php';
				exit;
			}
			if($sqlprep->rowCount()>0)
			{		
				$result=$sqlprep->fetchall();
			
				//перебираем записи из listpc
				foreach ($result as $res)
				{	//Делаем выборку из tempuser
					$valstpc1['name']=$res['name'];
					
					try 
					{						
						$sqlprstpc1->execute($valstpc1);						
					}
					catch (PDOException $e)
					{
						$sql=$sqlstpc1;
						include '../form/errorhtml.php';
						exit;
					}
					
					
					//Если имя есть и в listuser, и в temppc(AD)
					if ($sqlprstpc1->rowCount()>0)
					{	//Выводим его как результат синхронизации
						echo '<tr><td>'.html($res['name']).'</td><td>'.html($res['login']).'</td><td>'.html($res['descrip']).'</td> </tr>';
					}
					else
					{	//иначе удаяем запись с таким именем из таблицы listuser
						try {
							$sql=$sqldlpc;
							$sqlprdlpc->execute($valstpc1);
						}
						catch (PDOException $e)
						{
								
							include '../form/errorhtml.php';
							exit;
						}
					}
				
				}
			}
			//после окончания цикла заканчиваем вывод таблицы
			echo '</table>';
			//Чистим временную таблицу
			delFromTbl('tempuser',$condb);
			delFromTbl('temppc',$condb);
			
			
		}
		//Если скрипт открыт не через main, то отправляем на главную
		else header('Location: ../php_scripts/main.php');
		if($conn!=null){
			ldap_unbind($conn);}
		if($condb!=null) {
			$condb=NULL;}
		
	}
	//Если без авторизации-на страницу авторизации
	else header('Location: ../index.php');
	exit;
?>