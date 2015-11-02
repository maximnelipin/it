<?php
	session_start();
	
	if(isset($_SESSION['user_id']))
	{	
		//Файл с функциями
		include_once $_SERVER['DOCUMENT_ROOT'].'/php_scripts/func.php';
		//Файл подключения к БД
		include_once $_SERVER['DOCUMENT_ROOT'].'/php_scripts/mysql_conf.php';
		
		//Выводим форму на добавление
		if(isset($_REQUEST['add']))
		{
			$pageTitle='Добавление сервера';
			$action='addform';
			$name='';
			
			$type='';
			$descrip='';
			$login='';
			$note='';
			$id='';
			$dis='';
			$button="Добавить";
			include $_SERVER['DOCUMENT_ROOT'].'/form/addserverhtml.php';
			exit;
		}
		//Добавляем Контрагента
		if (isset($_REQUEST['name']) && isset($_REQUEST['addform']))
		{
		
			//преобразуем путь к папке для записи в Mysql
			//$_REQUEST["netpath"]=addslashes($_REQUEST["netpath"]);
			//$_REQUEST["container"]=addslashes($_REQUEST["container"]);
			try {
		
				$fields=array("name","type","descrip","login","note");
				$sql='insert into servers set '.pdoSet($fields,$values);
				$sqlprep=$condb->prepare($sql);
				$sqlprep->execute($values);
				$id_server=$condb->lastInsertId();
		
		
			}
		
			catch (PDOException $e)
			{
				include '../form/errorhtml.php';
				exit;
			}
			
			if(!empty($_POST['id_equip']))
			{
				foreach ($_POST['id_equip'] as $id_equip)
				{
					try {
					
						$sql='insert into eqsrv set id_equip=:id_equip, id_srv=:id_srv';
						$sqlprep=$condb->prepare($sql);
						$sqlprep->bindValue(':id_srv',$id_server);
						$sqlprep->bindValue(':id_equip',$id_equip);
						$sqlprep->execute();	
					
					}
					
					catch (PDOException $e)
					{
						include '../form/errorhtml.php';
						exit;
					}
				}
			}
		
			header('Location: '.$_SERVER['PHP_SELF'].'?add');
			exit;
		}
		//Выводим форму на редактирование
		if (isset($_REQUEST['action']) && $_REQUEST['action']=='Редактировать')
		{
			try
			{
				$sql='SELECT * FROM servers WHERE id=:id';
				$sqlprep=$condb->prepare($sql);
				$sqlprep->bindValue(':id',$_REQUEST['id_1']);
				$sqlprep->execute();
			}
			catch (PDOException $e)
			{
				include '../form/errorhtml.php';
				exit;
			}
			
			
			//Выбираем обычные данные
			$res=$sqlprep->fetch();
			$pageTitle='Редактирование сервера';
			$action='editform';
			$name=$res['name'];			
			$type=$res['type'];
			$descrip=$res['descrip'];
			$login=$res['login'];
			$note=$res['note'];
			$id=$res['id'];
			$dis='';
			$button="Обновить";
			include $_SERVER['DOCUMENT_ROOT'].'/form/addserverhtml.php';
			exit;
		
		}
		//Обновление
		if (isset($_REQUEST['editform']))
		{
			
			//Обновляем запись на сервере
			try
			{
				$fields=array("name","type","descrip","login","note");
				$sql='update servers set '.pdoSet($fields,$values).' where id=:id';
				$sqlprep=$condb->prepare($sql);
				$values["id"]=$_POST['id'];
				$sqlprep->execute($values);
			}
			catch (PDOException $e)
			{
				include '../form/errorhtml.php';
				exit;
			}
			
			
			if(!empty($_POST['id_equip']))
			{
				//удаляем всё связи с оборудованием
				try
				{
					$sql='Delete from eqsrv where id_srv=:id';
					$sqlprep=$condb->prepare($sql);
					$sqlprep->bindValue(':id',$_REQUEST['id']);
					$sqlprep->execute();
				}
				catch (PDOException $e)
				{
					include '../form/errorhtml.php';
					exit;
				}
				//добавляем новые записи
				foreach ($_POST['id_equip'] as $id_equip)
				{
					try {
							
						$sql='insert into eqsrv set id_equip=:id_equip, id_srv=:id_srv';
						$sqlprep=$condb->prepare($sql);
						$sqlprep->bindValue(':id_srv',$_REQUEST['id']);
						$sqlprep->bindValue(':id_equip',$id_equip);
						$sqlprep->execute();
							
					}
						
					catch (PDOException $e)
					{
						include '../form/errorhtml.php';
						exit;
					}
				}
			}
		
			header('Location: '.$_SERVER['PHP_SELF']);
			exit;
		
		}
		//Удаление сервера
		if (isset($_REQUEST['action']) && $_REQUEST['action']=='Удалить')
		{
			
			try
			{
				$sql='Delete from eqsrv where id_srv=:id';
				$sqlprep=$condb->prepare($sql);
				$sqlprep->bindValue(':id',$_REQUEST['id_1']);
				$sqlprep->execute();
			}
			catch (PDOException $e)
			{
				include '../form/errorhtml.php';
				exit;
			}
			
			try
			{
				$sql='DELETE FROM servers WHERE id=:id';
				$sqlprep=$condb->prepare($sql);
				$sqlprep->bindValue(':id',$_REQUEST['id_1']);
				$sqlprep->execute();
			}
			catch (PDOException $e)
			{
				include '../form/errorhtml.php';
				exit;
			}
			
			//удаляем всё связи с оборудованием
			
		
		}
		try
		{
			$cabs=$condb->query('SELECT DISTINCT id_cabinet FROM equip order by id_cabinet');
		}
		catch (PDOExeption $e)
		{
			include '../form/errorhtml.php';
			exit;
		}
		//Выборка оборудования, к которому привязан хотя бы 1 сервер
		$sql='SELECT DISTINCT equip.id, equip.phys, equip.ip, build.name, floor.floor, cabinet.cabinet, equip.rack, equip.unit
				FROM eqsrv
				LEFT JOIN equip ON equip.id = eqsrv.id_equip
				LEFT JOIN cabinet ON cabinet.id = equip.id_cabinet
				LEFT JOIN floor ON cabinet.id_floor = floor.id
				LEFT JOIN build ON floor.id_build = build.id
				ORDER BY build.name, floor.floor, cabinet.cabinet, equip.rack, equip.unit, equip.ip, equip.phys';
		$sqlprep=$condb->prepare($sql);
		$sqlf='SELECT DISTINCT servers.name, servers.id, eqsrv.id_equip
				FROM eqsrv
				RIGHT JOIN servers ON servers.id = eqsrv.id_srv
				WHERE eqsrv.id_equip =:id_equip ';
		$sqlprepf=$condb->prepare($sqlf);
		$sqlprep->execute();
		$result=$sqlprep->fetchall();
			foreach($result as $res)
			{
				$params[]=array('id'=>$res['id'], 'name'=>$res['name']."-".$res['floor'].'-'.$res['cabinet'].'-'.$res['rack'].'-'.$res['unit'].'-'.$res['ip'].'-'.$res['phys']);
				$sqlprepf->bindValue(':id_equip',$res['id']);
				$sqlprepf->execute();
				$resultf=$sqlprepf->fetchall();
				foreach($resultf as $resf)
				{	//массив для вложенной группы
					$paramsf[]=array('id_1'=>$resf['id'], 'name'=>$resf['name'], 'id'=>$resf['id_equip']);
						
				}
					
			}
		
		
		
		
		
		
		
		//Титул управляющей страницы в творительном падеже
		$ctrltitle="серверами";
		//Название ссылки в родительном падеже
		$ctrladd=' <a href="?add">Добавить сервер</a>';
		$btn_off='disabled';
		include $_SERVER['DOCUMENT_ROOT'].'/form/ctrlbfchtml.php';
		
		//include $_SERVER['DOCUMENT_ROOT'].'/form/addagentshtml.php';
		if($condb!=null) {$condb=NULL;}
		
		
	}
	else header('Location: ../index.php?link='.$_SERVER['PHP_SELF']);
	exit;
?>