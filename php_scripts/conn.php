<?php
	session_start();
	if(isset($_SESSION['user_id']))
	{	
		//Файл с функциями
		include_once $_SERVER['DOCUMENT_ROOT'].'/php_scripts/func.php';
		//Файл подключения к БД
		include_once $_SERVER['DOCUMENT_ROOT'].'/php_scripts/mysql_conf.php';
		
		
		//Если поступил id поля с адресом шлюза
		if(isset($_GET['gwlan']) AND (isset($_GET['ping']) OR isset($_GET['conn']) ))
		{	
			//Вывод всех точек
			if($_GET['gwlan']=="all")
			{
				$like='%';
			}
			//для одной точки
			else
			{
				$like=$_GET['gwlan'];
			}
			if(isset($_GET['conn']))
			{
				try {
					//Делаем выборку кабинетов, в которых есть выбранные подключения
					$sql='SELECT cabinet.cabinet, floor.floor, build.name, build.address, cabinet.id
							FROM cabinet
							LEFT JOIN floor ON cabinet.id_floor = floor.id
							LEFT JOIN build ON floor.id_build = build.id
							WHERE cabinet.id
							IN (
							
							SELECT id_cabinet
							FROM conn
							WHERE id LIKE :id
							)
							ORDER BY build.name, floor.floor, cabinet.cabinet
							LIMIT 20 
								';
					$sqlprep=$condb->prepare($sql);
					$sqlprep->bindValue(':id', $like);
					$sqlprep->execute();
				}
				catch (PDOException $e)
				{
					include '../form/errorhtml.php';
					exit;
				}
				if($sqlprep->rowCount()>0)
				{			
					$result=$sqlprep->fetchall();	
					foreach ($result as $res)
					{	//Для каждой точки
						$resc='';
						//получаем инфо о подключении и провайдере
						$resconns=connInCab($res['id'],$condb);
						foreach ($resconns as $resconn)
						{	//Преодбразуем массив значений в строку
							$resc.=$resconn['str'];
						}
						//Формируем строку с подключением и связанным провайдером в данном кабинете	
						$params[]=array('res'=>$resc, 'title'=>html($res['name'].' '.$res['address'].' '.$res['floor'].' этаж '.$res['cabinet']));
					}
						//Заголовок страницы
					$ctrltitle="Отчёт по подключениям";
				}
			}			
			//Выводим на страницу, если есть ping
			if(isset($_GET['ping']))
			{	
				try
				{
					//Делаем выборку адресов шлюзов
					$sql='SELECT conn.gateway, build.name FROM conn
							left JOIN cabinet ON conn.id_cabinet = cabinet.id
							left JOIN floor ON cabinet.id_floor = floor.id
							left JOIN build ON floor.id_build = build.id
							WHERE conn.id LIKE :id
							ORDER BY conn.gateway LIMIT 20
							';
					$sqlprep=$condb->prepare($sql);
					$sqlprep->bindValue(':id', $like);
					$sqlprep->execute();
				}
				catch (PDOException $e)
				{
					include '../form/errorhtml.php';
					exit;
				}
				if($sqlprep->rowCount()>0)
				{
					/*Увеличиваем время выполнения скрипта, 
					 * чтобы получить результат при недоступности точек
					На каждую ЛВС по 40 секунд*/
					set_time_limit($sqlprep->rowCount()*40);
					$result=$sqlprep->fetchall();
					foreach ($result as $res)
					{	
						//пингуем
						$respings=ping($res['gateway']);
						//Записываем результат пинга в элемент массива
						$params[]=array('res'=>$respings, 'title'=>html($res['name']));
					}
					$ctrltitle="Доступность ЛВС (ПИНГ)";
				}
			}
			else $ctrltitle='Не получены необходимые параметры';
		}	
		else
		{
			//$params[]=array('str'=>'');
			//Если нет дат дежурств в выбранном месяце
			$ctrltitle='Подключения и пинги Не получены необходимые параметры';
			
					 
		}
		include $_SERVER['DOCUMENT_ROOT'].'/form/rep3html.php';
		exit;
	}
	else header('Location: ../index.php?link='.str_replace('&','==',$_SERVER['REQUEST_URI']));
	exit;
?>