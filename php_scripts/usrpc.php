<?php
	session_start();
	if(isset($_SESSION['user_id']))
	{	
		//Файл с функциями
		include_once $_SERVER['DOCUMENT_ROOT'].'/php_scripts/func.php';
		//Файл подключения к БД
		include_once $_SERVER['DOCUMENT_ROOT'].'/php_scripts/mysql_conf.php';
		//если есть пользователь
		if(isset($_GET['usr']))
		{
			//Вывод всех пользователей и связанных ПК
			if($_GET['usr']=="all")
			{
				//Делаем выборку
				$sql='SELECT listuser.login as login, listuser.fio as fio, listuser.func as func, listuser.dept as dept, 
						listpc.name as name, listpc.descrip as descrip
						FROM listuser
						RIGHT JOIN listpc ON listuser.login = listpc.login
						ORDER BY listuser.fio';
				$sqlprep=$condb->prepare($sql);
				$sqlprep->execute();
				if($sqlprep->rowCount()>0)
				{				
					$result=$sqlprep->fetchall();
					//Формируем шапку таблицы
					$params[]=array('str'=>'<table class="tblusrpc">
	   					<caption>Пользователи и ПК</caption>
	  					 <tr>
						<th>ФИО</th>
						<th>Логин</th>
	    				<th>Должность</th>
						<th>Отдел</th>
						<th>ПК</th>
						<th>Описание ПК</th>
	   					</tr>');
					foreach ($result as $res)
					{
						//Формируем массив, причём по фамилии пользователя можно перейти на его отчёт<a href=?usr='.html($res['login']).' target="_blank"> '.html($res['fio']).'</a>
						$params[]=array('str'=>'<tr><td>'.createLink(html($res['fio']),'?usr='.html($res['login']),"_blank").'</td><td>'.html($res['login']).'</td>
						<td>'.html($res['func']).'</td><td>'.html($res['dept']).'</td><td>'.html($res['name']).'</td><td>'.html($res['descrip']).'</td> </tr>');		
					}
					$params[]=array('str'=>'<table>');
				}
				$ctrltitle="Список пользователей и ПК";
				$ctrls='Список пользователей и ПК';
			}
			
			//Если получили логин в виде e-mail
			if(filter_var($_GET['usr'], FILTER_VALIDATE_EMAIL))
			{
				//---------------------Информация о пользователе
				try 
				{
					$sql='SELECT  login,fio,dept,func	FROM listuser WHERE login = :login';
					$sqlprep=$condb->prepare($sql);
					$sqlprep->bindValue(':login', $_GET['usr']);
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
					$params[]=array('str'=>'<table >
		   					<caption>Пользователь</caption>
		  					 <tr>
							<th>ФИО</th>
							<th>Логин</th>
		    				<th>Должность</th>
							<th>Отдел</th>
		   					</tr>');
					foreach ($result as $res)
					{	
						$params[]=array('str'=>'<tr><td>'.html($res['fio']).'</td><td>'.html($res['login']).'</td>
								<td>'.html($res['func']).'</td><td>'.html($res['dept']).'</td> </tr>');
						$ctrltitle=html($res['fio']);
					
					}					
					
					$params[]=array('str'=>'</table>');
				}
				$ctrls='Пользователь';
				//-------------Выборка сим-карт
				try 
				{
					$sql='SELECT  sim.number, sim.account, sim.balance, sim.pay,sim.pwdlk, sim.note, build.name as build, isp.name as isp, isp.urllk 
							FROM  sim LEFT JOIN build ON sim.id_address=build.id LEFT JOIN isp ON sim.id_operator=isp.id
							WHERE sim.login = :login';
					$sqlprep=$condb->prepare($sql);
					$sqlprep->bindValue(':login', $_GET['usr']);
					$sqlprep->execute();
				}
				catch (PDOExeption $e)
				{
					include '../form/errorhtml.php';
					exit;
				}
				//Если нет сим карт, то не формируем таблицу с заголовком
				if($sqlprep->rowCount()>0)
				{
					$result=$sqlprep->fetchall();
					$params1[]=array('str'=>'<table>
		   					<caption>Сим-карты</caption>
		  					 <tr>
							<th>Номер</th>
							<th>Л/С</th>
		    				<th>Объект</th>
							<th>Оператор</th>
							<th>Баланс</th>
							<th>Оплата</th>
							<th>Личный кабинет</th>
							<th>Пароль личного кабинета</th>
							<th>Примечание</th>
		   					</tr>');
					foreach ($result as $res)
					{
					
						$params1[]=array('str'=>'<tr><td>'.html($res['number']).'</td><td>'.html($res['account']).'</td>
							<td>'.html($res['build']).'</td><td>'.html($res['isp']).'</td><td>'.html($res['balance']).
								'</td><td>'.html($res['pay']).'</td><td><a href='.html($res['urllk']).' target="_blank"> '.html($res['urllk']).
								'</a></td><td>'.html($res['pwdlk']).'</td><td>'.html($res['note']).
						'</td> </tr>');
					}			
						
					$params1[]=array('str'=>'</table>');
					$ctrl1='Сим-карты';
				}
				
				//-------------Выборка компьютеров
				try
				{
					$sql='SELECT  name, descrip FROM  listpc WHERE login = :login';
					$sqlprep=$condb->prepare($sql);
					$sqlprep->bindValue(':login', $_GET['usr']);
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
					$params2[]=array('str'=>'<table>
		   					<caption>Персональные компьютеры</caption>
		  					 <tr>
							<th>Имя</th>
							<th>Описание</th>    				
		   					</tr>');
					
					foreach ($result as $res)
					{
						$params2[]=array('str'=>'<tr><td>'.html($res['name']).'</td><td>'.html($res['descrip']).'</td> </tr>');						
					}			
								
					$params2[]=array('str'=>'</table>');
					$ctrl2='Персональные компьютеры';
					
				}
				
			}
		}	
		else 
		{ //Если перешли на страницу без парметров, то открываем главную
			header('Location: main.php');
			exit;
		}			
		include $_SERVER['DOCUMENT_ROOT'].'/form/rep1html.php';
		exit;		
	}
	else header('Location: ../index.php?link='.$_SERVER['PHP_SELF']);
	exit;
?>