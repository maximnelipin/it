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
			//Выводим на страницу, если есть ping
			if(isset($_GET['conn']))
			{
				//Делаем выборку кабинетов
				$sqlcab='SELECT cabinet.cabinet, floor.floor, build.name, build.address, cabinet.id
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
				$sqlprepcab=$condb->prepare($sqlcab);
				$sqlprepcab->bindValue(':id', $like);
				$sqlprepcab->execute();
				$resultcab=$sqlprepcab->fetchall();	
						foreach ($resultcab as $rescab)
						{	//Для каждой точки
							$resc='';
							//пингуем
							$resconns=connInCab($rescab['id'],$condb);
							foreach ($resconns as $resconn)
							{	//Преодбразуем массив значений в строку
								$resc.=$resconn['str'];
							}
							//Дополняем строку массивами с таблицами по подклюяениям	
							$params[]=array('res'=>$resc, 'build'=>$rescab['name'].' '.$rescab['address'].' '.$rescab['floor'].' этаж '.$rescab['cabinet']);
								
								
								
						}
						$ctrltitle="Отчёт по подключениям";
				
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