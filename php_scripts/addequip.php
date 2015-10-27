<?php
	session_start();
	
	if(isset($_SESSION['user_id']))
	//if(1)
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
		if(isset($_REQUEST['add']))
		{
			$pageTitle='Добавление оборудования';
			$action='addform';
			
			$id_cabinet='';
			$phys='';
			$ip='';
			$rack='';
			$unit='';
			$note='';
			$id='';
			$dis='';
			$button="Добавить";
			include $_SERVER['DOCUMENT_ROOT'].'/form/addequiphtml.php';
			exit;
		}
		//Добавляем Контрагента
		if (isset($_REQUEST['phys']) && isset($_REQUEST['addform']))
		{
		
			//преобразуем путь к папке для записи в Mysql
			//$_REQUEST["netpath"]=addslashes($_REQUEST["netpath"]);
			//$_REQUEST["container"]=addslashes($_REQUEST["container"]);
			try {
		
				$fields=array("id_cabinet","ip","phys","rack","unit","note");
				$sql='insert into equip set '.pdoSet($fields,$values);
				$sqlprep=$condb->prepare($sql);
				$sqlprep->execute($values);
		
		
			}
		
			catch (PDOException $e)
			{
				include '../form/errorhtml.php';
				exit;
			}
		
			header('Location: '.$_SERVER['PHP_SELF'].'?add');
			exit;
		}
		//Выводим форму на редактирование
		if (isset($_REQUEST['action']) && $_REQUEST['action']=='Редактировать')
		{
			try
			{
				$sql='SELECT * FROM equip WHERE id=:id';
				$sqlprep=$condb->prepare($sql);
				$sqlprep->bindValue(':id',$_REQUEST['id_1']);
				$sqlprep->execute();
			}
			catch (PDOException $e)
			{
				include '../form/errorhtml.php';
				exit;
			}
		
			$res=$sqlprep->fetch();
			$pageTitle='Редактирование оборудования';
			$action='editform';
			$id_cabinet=$res['id_cabinet'];
			$ip=$res['ip'];
			$phys=$res['phys'];
			$rack=$res['rack'];
			$unit=$res['unit'];
			$note=$res['note'];
			$id=$res['id'];
			$dis='';
			$button="Обновить";
			include $_SERVER['DOCUMENT_ROOT'].'/form/addequiphtml.php';
			exit;
		
		}
		//Обновление
		if (isset($_REQUEST['editform']))
		{
			//преобразуем путь к папке для записи в Mysql
			//$_REQUEST["netpath"]=addslashes($_REQUEST["netpath"]);
			//$_REQUEST["container"]=addslashes($_REQUEST["container"]);
		
			try
			{
				$fields=array("id_cabinet","ip","phys","rack","unit","note");
				$sql='update equip set '.pdoSet($fields,$values).' where id=:id';
				$sqlprep=$condb->prepare($sql);
				$values["id"]=$_POST['id'];
				$sqlprep->execute($values);
			}
			catch (PDOException $e)
			{
				include '../form/errorhtml.php';
				exit;
			}
		
			header('Location: '.$_SERVER['PHP_SELF']);
			exit;
		
		}
		//Удаление контрагента
		if (isset($_REQUEST['action']) && $_REQUEST['action']=='Удалить')
		{
			try
			{
				$sql='DELETE FROM equip WHERE id=:id';
				$sqlprep=$condb->prepare($sql);
				$sqlprep->bindValue(':id',$_REQUEST['id']);
				$sqlprep->execute();
			}
			catch (PDOException $e)
			{
				include '../form/errorhtml.php';
				exit;
			}
		
		}
		//Выбираем все id_cabinet из equip
		try
		{
			$cabs=$condb->query('SELECT DISTINCT id_cabinet FROM equip order by id_cabinet');
		}
		catch (PDOExeption $e)
		{
			include '../form/errorhtml.php';
			exit;
		}
		//Запрос выборки кабинета
		$sql='SELECT build.name as build, floor.id as id_floor, floor.floor as floor, cabinet.id as id_cab, cabinet.cabinet as cabinet FROM build
				RIGHT JOIN floor ON build.id = floor.id_build RIGHT JOIN cabinet ON cabinet.id_floor=floor.id WHERE  cabinet.id=:id_cabinet';		
		$sqlprep=$condb->prepare($sql);
		$sqlf='SELECT DISTINCT id, id_cabinet, phys,ip FROM equip WHERE id_cabinet=:id_cabinet order by  ip,phys ';
		$sqlprepf=$condb->prepare($sqlf);
		foreach ($cabs as $cab)
		{
			$sqlprep->bindValue(':id_cabinet',$cab['id_cabinet']);
			$sqlprep->execute();
			$result=$sqlprep->fetchall();
			//Формирование списка зданийб этажей и кабинетов
			foreach($result as $res)
			{
				$params[]=array('id'=>$res['id_cab'], 'name'=>$res['build'].' '.$res['floor'].' этаж '.$res['cabinet']);
				$sqlprepf->bindValue(':id_cabinet',$res['id_cab']);
				$sqlprepf->execute();
				$resultf=$sqlprepf->fetchall();
				foreach($resultf as $resf)
				{	//массив для вложенной группы
					$paramsf[]=array('id_1'=>$resf['id'], 'name'=>$resf['ip'].' '.$resf['phys'], 'id'=>$resf['id_cabinet']);
					
				}
			
			}
		}
		
		
		
		
		
	
		//Титул управляющей страницы в творительном падеже
		$ctrltitle="оборудованием";
		//Название ссылки в родительном падеже
		$ctrladd=' <a href="?add">Добавить оборудование</a>';
		$btn_off='disabled';
		include $_SERVER['DOCUMENT_ROOT'].'/form/ctrlbfchtml.php';
		
		//include $_SERVER['DOCUMENT_ROOT'].'/form/addagentshtml.php';
		if($condb!=null) {$condb=NULL;}
		
		
	}
	else header('Location: ../index.php?link='.$_SERVER['PHP_SELF']);
	exit;
?>