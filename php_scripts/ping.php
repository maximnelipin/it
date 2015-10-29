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
			$result=$sqlprep->fetchall();
			
			//Выводим на страницу, если есть ping
			if(isset($_GET['ping']))
			{
				
				
				
				
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