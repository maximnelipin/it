<?php
	session_start();
	if(isset($_SESSION['user_id']))
	{	
		//Файл с функциями
		include_once $_SERVER['DOCUMENT_ROOT'].'/php_scripts/func.php';
		//Файл подключения к БД
		include_once $_SERVER['DOCUMENT_ROOT'].'/php_scripts/mysql_conf.php';
			
			
			$ctrltitle="Сервера";
			//Если получили серер
			if(isset($_GET['servers']))
			{	
				//Вывод всех серверов
				if($_GET['servers']=="all")
				{
					$like='%';
				}
				//Одного сервера
				else
				{
					$like=$_GET['servers'];
				}
				//Делаем выборку серверов
				try 
				{
					$sqlserver='SELECT servers.name, servers.type, servers.descrip,  servers.id, servers.note, itusers.fio 
								FROM servers
								LEFT JOIN itusers ON servers.login = itusers.login														
								WHERE id LIKE :id
								order by servers.name
								LIMIT 50';
					$sqlprepserver=$condb->prepare($sqlserver);
					$sqlprepserver->bindValue(':id', $like);
					$sqlprepserver->execute();
				}
				catch (PDOExeption $e)
				{
					
					$sql=$sqlserver;
					include '../form/errorhtml.php';
					exit;
				}
				//Подготавливаем запрос на оборудования связанного с сервером
				$sqlequip='SELECT equip.id, equip.phys, equip.ip, equip.rack, equip.unit, equip.note, 
								build.name as build,  build.address, floor.floor, cabinet.cabinet
								FROM equip
								LEFT JOIN eqsrv ON equip.id = eqsrv.id_equip
								LEFT JOIN cabinet ON equip.id_cabinet=cabinet.id
								LEFT JOIN floor ON cabinet.id_floor=floor.id
								LEFT JOIN build ON floor.id_build=build.id
								WHERE eqsrv.id_srv =:id_srv order by equip.phys, equip.rack, equip.unit';
				$sqlprepequip=$condb->prepare($sqlequip);
				
				//Если есть сервера, то выводим информации по ним
				if($sqlprepserver->rowCount()>0)
				{
					$resultserver=$sqlprepserver->fetchall();
						
					//Для каждого сервера делаем выборку оборудования
					foreach ($resultserver as $server)
					{	
						//Шапка таблицы для провайдера
						$resserver='<table>
			   					<caption>Сервер</caption>
			  					 <tr>
								<th>Сетевое имя</th>
								<th>Тип</th>
			    				<th>Описание</th>
								<th>Отвественный</th>
								<th>Примечание</th>
								</tr>';
						$resserver.='<tr><td>'.
									html($server['name']).'</td><td>'.
									html($server['type']).'</td><td>'.
									html($server['descrip']).'</td><td>'.
									html($server['fio']).'</td><td>'.
									html($server['note']).'</td></tr>';									
							
						$resserver.='</table>';
							
						$params[]=array('res'=>$resserver, 'title'=>$server['name'],  'id'=>$server['id']);
						//Выбираем оборудование
						try 
						{
							$sqlprepequip->bindValue(':id_srv', $server['id']);
							$sqlprepequip->execute();
						}
						catch (PDOExeption $e)
						{
							$sql=$sqlequip;
							include '../form/errorhtml.php';
							exit;
						}
						//Если есть результаты выборки
						if($sqlprepequip->rowCount()>0)
						{
							$resultequip=$sqlprepequip->fetchall();
							//шапка таблицы 
							$resequip='<table>
						   					<caption>Оборудование</caption>
						  					 <tr>
											<th>Модель</th>
											<th>IP-адрес</th>
											<th>Объект</th>
											<th>Адрес</th>
											<th>Этаж</th>											
											<th>Кабинет</th>
											<th>Стойка</th>
											<th>Юнит</th>
											<th>Примечание</th>											
						   					</tr>';
							foreach ($resultequip as $equip)
							{	//Формируем строки с оборудованием
								$resequip.='<tr><td>'.
											html($equip['phys']).'</td><td>'.
											html($equip['ip']).'</td><td>'.
											html($equip['build']).'</td><td>'.
											html($equip['address']).'</td><td>'.											
											html($equip['floor']).'</td><td>'.
											html($equip['cabinet']).'</td> <td>'.
											html($equip['rack']).'</td> <td>'.
											html($equip['unit']).'</td> <td>'.
											html($equip['note']).'</td> </tr>';									
							}
							//Закрываем таблицу 
							$resequip.='</table>';
							//Заносим в массив 
							$params1[]=array('res'=>$resequip, 'title'=>'Оборудование', 'id_1'=>$server['id']);
						}
					}	
				}	
			}
		
		else
		{
			//Если не хватает парметров
			$ctrltitle='Провайдеры Не получены необходимые параметры';					 
		}
		include $_SERVER['DOCUMENT_ROOT'].'/form/rep3html.php';
		exit;
	}
	else header('Location: ../index.php?link='.str_replace('&','==',$_SERVER['REQUEST_URI']));
	exit;
?>