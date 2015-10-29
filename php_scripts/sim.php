<?php
	session_start();
	
	if(isset($_SESSION['user_id']))
	{	
		include 'func.php';
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
		
		//Выводим форму на добавление
		if(isset($_GET['ispsim']))
		{	$ctrltitle='Сим-карты';
			$ctrls='Сим-карты';
			
		
			
			//Вывод всех точек
			if($_GET['ispsim']=="all")
			{
				$like='%';
				
			}
			//для одной точки
			else
			{
				$like=$_GET['ispsim'];
				
			}
			//Формируем запрос на выборку имеён провайдеров для формирования заголовка
			$sqlisp='SELECT DISTINCT isp.name from isp RIGHT JOIN sim ON isp.id=sim.id_operator WHERE  id like :id ';
			$sqlprepisp=$condb->prepare($sqlisp);
			$sqlprepisp->bindValue(':id', $like);
			$sqlprepisp->execute();
			//формируем заголоквки
			$resultisp=$sqlprepisp->fetchall();
			foreach($resultisp as $resisp)
			{
				$ctrltitle.='-'.html($resisp['name']);
				$ctrls.='-'.html($resisp['name']);
			}
			
			
			if(isset($_GET['simrep']))
			{
				//-------------Формируем сим-карты
				$sql='SELECT  sim.number, sim.account, sim.balance, sim.pay,sim.pwdlk, sim.note,
						isp.name as isp, isp.urllk, isp.telsup, listuser.fio, build.name as build
						FROM  sim LEFT JOIN build ON sim.id_address=build.id
						LEFT JOIN isp ON sim.id_operator=isp.id
						LEFT JOIN listuser ON sim.login=listuser.login
						WHERE sim.id_operator like :id_operator';
				$sqlprep=$condb->prepare($sql);
				$sqlprep->bindValue(':id_operator', $like);
				$sqlprep->execute();
				
				//Если нет сим карт, то и не формируем таблицу
				if($sqlprep->rowCount()>0)
				{
					$result=$sqlprep->fetchall();
					$params[]=array('str'=>'<table>
		   					<caption>Сим-карты</caption>
		  					 <tr>
							<th>Номер</th>
							<th>Л/С</th>
							<th>Объект</th>
							<th>Оператор</th>
							<th>Техподдержка</th>
							<th>Числится за</th>
							<th>Баланс</th>
							<th>Оплата</th>
							<th>Личный кабинет</th>
							<th>Пароль личного кабинета</th>
							<th>Примечание</th>
		   					</tr>');
					foreach ($result as $res)
					{
						//Формирем строки таблицы
						$params[]=array('str'=>'<tr><td>'.
								html($res['number']).'</td><td>'.
								html($res['account']).'</td><td>'.
								html($res['build']).'</td><td>'.
								html($res['isp']).'</td><td>'.
								html($res['telsup']).'</td><td>'.
								html($res['fio']).'</td><td>'.
								html($res['balance']).'</td><td>'.
								html($res['pay']).'</td><td>
								<a href='.html($res['urllk']).' target="_blank"> '.html($res['urllk']).'</a></td><td>'.
								html($res['pwdlk']).'</td><td>'.
								html($res['note']).'</td> </tr>');
						
					}
						
					$params[]=array('str'=>'</table>');
					//Формируем заголовки
					
							
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