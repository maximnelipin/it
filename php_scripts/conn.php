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
		if(isset($_GET['gwlan']))
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
					//Делаем выборку кабинетов, в которых есть подключения
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
							//получаем подключения и инфо о проваёдере
							$resconns=connInCab($res['id'],$condb);
							foreach ($resconns as $resconn)
							{	//Преодбразуем массив значений в строку
								$resc.=$resconn['str'];
							}
							//Дополняем строку массивами с таблицами по подклюяениям	
							$params[]=array('res'=>$resc, 'build'=>$res['name'].' '.$res['address'].' '.$res['floor'].' этаж '.$res['cabinet']);
								
								
								
						}
						//Заголовок страницы
						$ctrltitle="Отчёт по подключениям";
				}
			}
			
			//Выводим на страницу, если есть ping
			if(isset($_GET['ping']))
			{	
				try {
					//Делаем выборку
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
					//Увеличиваем время, чтобы получить результат при недоступности точек
					//На каждую ЛВС по 40 секунд
					set_time_limit($sqlprep->rowCount()*40);
					$result=$sqlprep->fetchall();
					foreach ($result as $res)
					{	//Для каждой точки
						$resp='<div class="ping">';
						//пингуем
						$respings=ping($res['gateway']);
						foreach ($respings as $resping)
						{	//Преодбразуем массив значений в строку с переносами
							$resp.='<p>'.iconv("cp866","utf-8",$resping).'</p>';
						}
						$resp.='</div>';
						$params[]=array('res'=>$resp, 'build'=>$res['name']);
				
							
							
					}
						
					$ctrltitle="Доступность ЛВС (ПИНГ)";
				}
			
			}
			//Формируем письмо и отправляем
			if(isset($_GET['mail']))
			{
								
			}
			
			
			
			
		}	
		else 
		{ //Если перешли на страницу без парметров, то открываем главную
			header('Location: main.php');
			exit;
		}
			
		include $_SERVER['DOCUMENT_ROOT'].'/form/reppinghtml.php';
		exit;
		
		
		
		
	}
	else header('Location: ../index.php?link='.$_SERVER['PHP_SELF']);
	exit;
?>