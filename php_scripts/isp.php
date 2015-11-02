<?php
	session_start();
	
	if(isset($_SESSION['user_id']))
	{	
		//Файл с функциями
		include_once $_SERVER['DOCUMENT_ROOT'].'/php_scripts/func.php';
		//Файл подключения к БД
		include_once $_SERVER['DOCUMENT_ROOT'].'/php_scripts/mysql_conf.php';
		if(isset($_GET['isprep']))
		{	
			
			$ctrltitle="Провайдеры";
			//Выводим форму на добавление
			if(isset($_GET['isp']))
			{	
				//Вывод всех точек
				if($_GET['isp']=="all")
				{
					$like='%';
				}
				//для одной точки
				else
				{
					$like=$_GET['isp'];
				}
				//Делаем выборку провайдера
					$sqlisp='SELECT * 
							FROM isp						
							WHERE id LIKE :id					
							LIMIT 20';
					$sqlprepisp=$condb->prepare($sqlisp);
					$sqlprepisp->bindValue(':id', $like);
					$sqlprepisp->execute();
					
					//Подготавливаем запрос на выборку сим-карт
					$sqlsim='SELECT  sim.number, sim.account, sim.balance, sim.pay,sim.pwdlk, sim.note,
					listuser.fio,  build.name as build
					FROM  sim LEFT JOIN build ON sim.id_address=build.id
					LEFT JOIN isp ON sim.id_operator=isp.id
					LEFT JOIN listuser ON sim.login=listuser.login
					WHERE sim.id_operator = :id_operator';
					$sqlprepsim=$condb->prepare($sqlsim);
					//Подготавливаем запрос на выборку подключений
					$sqlconn='SELECT conn.gateway, conn.typecon, conn.mask, conn.dhcp, conn.dns1, conn.dns2, conn.loginlk, conn.pwdlk, 
								conn.contract, ppp.typeppp, ppp.srv AS srvppp, ppp.login AS loginppp, ppp.pwd AS pwdppp, 
								extnet.extip,extnet.extmask, extnet.extgw, extnet.extdns1, extnet.extdns2, 
								company.name AS namecomp, company.innkpp,  conn.note, build.name AS build
								FROM conn
								LEFT JOIN ppp ON conn.id_ppp = ppp.id
								LEFT JOIN extnet ON conn.id_extnet = extnet.id
								LEFT JOIN company ON conn.id_company = company.id
								LEFT JOIN cabinet ON conn.id_cabinet = cabinet.id
								LEFT JOIN floor ON cabinet.id_floor = floor.id
								LEFT JOIN build ON floor.id_build = build.id
								WHERE conn.id_operator =:id_isp
								ORDER BY conn.gateway';
					$sqlprepconn=$condb->prepare($sqlconn);
					//Если есть провайдеры, то выводим информации по ним
					if($sqlprepisp->rowCount()>0)
					{
						$resultisp=$sqlprepisp->fetchall();
						
							//Для каждого провайдера делдаем выборкут симкарт и подключений
						foreach ($resultisp as $isp)
						{	//Формируем таблицу с инфо от провайдеров
							
							//Шапка таблицы для провайдера
							$resisp='<table>
			   					<caption>Провайдер</caption>
			  					 <tr>
								<th>Наименование</th>
								<th>Поддержка</th>
			    				<th>Менеджер</th>
								<th>Телефон менеджера</th>
								<th>Почта менеджера</th>
								<th>Офис</th>
								<th>Личный кабинет</th>
								<th>Папка с документами</th>
								<th>Примечание</th> </tr>';
							$resisp.='<tr><td>'.
									html($isp['name']).'</td><td>'.
									html($isp['telsup']).'</td><td>'.
									html($isp['manager']).'</td><td>'.
									html($isp['telman']).'</td><td>'.
									html($isp['emailman']).'</td><td>'.
									html($isp['address']).'</td><td>'.
									html($isp['urllk']).'</td><td>'.
									html($isp['netpath']).'</td><td>'.
									html($isp['note']).'</td></tr>';
							
							$resisp.='</table>';
							
							$params[]=array('res'=>$resisp, 'title'=>$isp['name'],  'id'=>$isp['id']);
							//Выбираем сим-краты
							$sqlprepsim->bindValue(':id_operator', $isp['id']);
							$sqlprepsim->execute();
							//Если есть результаты выборки
							if($sqlprepsim->rowCount()>0)
							{
								$resultsim=$sqlprepsim->fetchall();
								//шапка таблицы для сим-карт
								$ressim='<table>
						   					<caption>Сим-карты</caption>
						  					 <tr>
											<th>Номер</th>
											<th>Л/С</th>
											<th>Объект</th>											
											<th>Числится за</th>
											<th>Баланс</th>
											<th>Оплата</th>	
											<th>Пароль личного кабинета</th>
											<th>Примечание</th>
						   					</tr>';
								foreach ($resultsim as $sim)
								{
								
									$ressim.='<tr><td>'.
											html($sim['number']).'</td><td>'.
											html($sim['account']).'</td><td>'.
											html($sim['build']).'</td><td>'.											
											html($sim['fio']).'</td><td>'.
											html($sim['balance']).'</td><td>'.
											html($sim['pay']).'</td><td>'.								
											html($sim['pwdlk']).'</td><td>'.
											html($sim['note']).'</td> </tr>';
									
								//Дополняем строку массивами с таблицами по подклюяениям	
								
								}
								//Закрываем таблицу с симкамми одного оператора
								$ressim.='</table>';
							
								$paramsf[]=array('res'=>$ressim, 'title'=>'Сим-карты', 'id_1'=>$isp['id']);
							}
							
							//Выбираем сим-краты
							$sqlprepconn->bindValue(':id_isp', $isp['id']);
							$sqlprepconn->execute();
							if($sqlprepconn->rowCount()>0)
							{
								$resultconn=$sqlprepconn->fetchall();
								$resconn='<table>
			   					<caption>Подключения</caption>
			  					 <tr>
								<th>Шлюз</th>
								<th>Объект</th>
								<th>Маска</th>
			    				<th>DHCP</th>
								<th>DNS1</th>
								<th>DNS2</th>
								<th>Компания</th>
								<th>ИНН/КПП</th>
								<th>Договор</th>
								<th>Тип подключения</th>
								<th>Внешний IP</th>
								<th>Внешняя маска</th>
								<th>Внешний шлюз</th>
								<th>Внешний DNS1</th>
								<th>Внешний DNS2</th>
								<th>Тип PPP</th>
								<th>Сервер PPP</th>
								<th>Логин PPP</th>
								<th>Пароль PPP</th>
								<th>Логин ЛК</th>
								<th>Пароль ЛК</th>
								<th>Примечание</th>
			   					</tr>';
								foreach ($resultconn as $conn)
								{
										
									$resconn.='<tr><td>'.
										html($conn['gateway']).'</td><td>'.
										html($conn['build']).'</td><td>'.
										html($conn['mask']).'</td><td>'.
										html($conn['dhcp']).'</td><td>'.
										html($conn['dns1']).'</td><td>'.
										html($conn['dns2']).'</td><td>'.
										html($conn['namecomp']).'</td><td>'.
										html($conn['innkpp']).'</td><td>'.
										html($conn['contract']).'</td><td>'.
										html($conn['typecon']).'</td><td>'.
										html($conn['extip']).'</td><td>'.
										html($conn['extmask']).'</td><td>'.
										html($conn['extgw']).'</td><td>'.
										html($conn['extdns1']).'</td><td>'.
										html($conn['extdns2']).'</td><td>'.
										html($conn['typeppp']).'</td><td>'.
										html($conn['srvppp']).'</td><td>'.
										html($conn['loginppp']).'</td><td>'.
										html($conn['pwdppp']).'</td><td>'.
										html($conn['loginlk']).'</td><td>'.
										html($conn['pwdlk']).'</td><td>'.
										html($conn['note']).'</td></tr>';
										
									
								}
								//Закрываем таблицу с симкамми одного оператора
								$resconn.='</table>';
								//Дополняем строку массивами с таблицами по подклюяениям
								$paramsf[]=array('res'=>$resconn, 'title'=>'Подключения', 'id_1'=>$isp['id']);
							}
									
						}
							
					}	
				}
				
				
				
				
				
		}
		else 
		{ //Если перешли на страницу без парметров, то открываем главную
			header('Location: main.php');
			exit;
		}
			
		include $_SERVER['DOCUMENT_ROOT'].'/form/rep3html.php';
		exit;
		
		
		
		
	}
	else header('Location: ../index.php?link='.$_SERVER['PHP_SELF']);
	exit;
?>