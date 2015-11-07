<?php
	session_start();
	
	if(isset($_SESSION['user_id']))
	{	
		//Файл с функциями
		include_once $_SERVER['DOCUMENT_ROOT'].'/php_scripts/func.php';
		//Файл подключения к БД
		include_once $_SERVER['DOCUMENT_ROOT'].'/php_scripts/mysql_conf.php';
		
		//Если получилили здание
		
		if(isset($_GET['build']))
		{	
			
			//---------------------Выбираем информацию о зданиии
			try 
			{
			$sql='SELECT  name, address	FROM build WHERE id = :id';
			$sqlprep=$condb->prepare($sql);
			$sqlprep->bindValue(':id', $_GET['build']);
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
				foreach ($result as $res)
				{	//Выводим имя объекта и его адрес
					$ctrltitle=html($res['name']);
					$address=html($res['address']);
				}					
						
				
				//-------------Формируем таблицу с сим-картами, привязанными к текущему объекту
				try
				{
					$sql='SELECT  sim.number, sim.account, sim.balance, sim.pay,sim.pwdlk, sim.note, 
							isp.name as isp, isp.urllk, isp.id, isp.telsup, listuser.fio 
							FROM  sim LEFT JOIN build ON sim.id_address=build.id 
							LEFT JOIN isp ON sim.id_operator=isp.id
							LEFT JOIN listuser ON sim.login=listuser.login
							WHERE sim.id_address = :id_address';
					$sqlprep=$condb->prepare($sql);
					$sqlprep->bindValue(':id_address', $_GET['build']);
					$sqlprep->execute();
				}
				catch (PDOExeption $e)
				{
					include '../form/errorhtml.php';
					exit;
				}
				//Если нет сим карт, то не формируем таблицу
				if($sqlprep->rowCount()>0)
				{
					$result=$sqlprep->fetchall();
					$params[]=array('str'=>'<table>
		   					<caption>Сим-карты</caption>
		  					 <tr>
							<th>Номер</th>
							<th>Л/С</th>
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
								html($res['isp']).'</td><td>'.							
								html($res['telsup']).'</td><td>'.
								html($res['fio']).'</td><td>'.
								html($res['balance']).'</td><td>'.
								html($res['pay']).'</td><td>'.	
								createLink(html($res['urllk']), html($res['urllk']),'_blank').'</td><td>'.
								html($res['pwdlk']).'</td><td>'.
								html($res['note']).'</td> </tr>');
					}			
					//Закрываем таблицу	
					$params[]=array('str'=>'</table>');
					$ctrls='Сим-карты';
				}
				//---------------------Формируем этажи и кабинеты
				//Этажи
				try 
				{
					$sqlf='SELECT  id,floor	FROM  floor WHERE id_build = :id_build order by floor';
					$sqlprepf=$condb->prepare($sqlf);
					$sqlprepf->bindValue(':id_build', $_GET['build']);
					$sqlprepf->execute();
				}
				catch (PDOExeption $e)
				{
					$sql=$sqlf;
					include '../form/errorhtml.php';
					exit;
				}
				//Подготовка запроса выборки кабинетов на эатаже
				$sqlc='SELECT id, cabinet FROM cabinet WHERE id_floor=:id_floor order by cabinet';
				$sqlprepc=$condb->prepare($sqlc);
				if($sqlprepf->rowCount()>0)
				{
					$resultf=$sqlprepf->fetchall();					
					foreach ($resultf as $resf)
					{
						//Подготоавливаем информацию по этажам
						$params1[]=array('str'=>html($resf['floor']).' этаж', 'id'=>html($resf['id']));
						//Выбираем кабинеты на этаже
						try 
						{
							$sqlprepc->bindValue(':id_floor', $resf['id']);
							$sqlprepc->execute();
						}
						catch (PDOExeption $e)
						{
							$sql=$sqlc;
							include '../form/errorhtml.php';
							exit;
						}
						if($sqlprepc->rowCount()>0)
						{
							$resultc=$sqlprepc->fetchall();
							foreach ($resultc as $resc)
							{	
								$params2[]=array('str'=>html($resc['cabinet']), 'id'=>html($resc['id']), 'id_floor'=>html($resf['id']));
							}
						}			
					}
				}
			}
		}
			
		else
		{
			$params[]=array('str'=>'');
			//Если нет дат дежурств в выбранном месяце
			$ctrltitle='Здания';
			$address='';
			$ctrls='Не получены необходимые параметры';
					 
		}
			
		include $_SERVER['DOCUMENT_ROOT'].'/form/rep2html.php';
		exit;
		
		
		
		
	}
	else header('Location: ../index.php?link='.str_replace('&','==',$_SERVER['REQUEST_URI']));
	exit;
?>