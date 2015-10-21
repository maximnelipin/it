<?php
	session_start();
	if(isset($_SESSION['user_id']))
	{	include 'func.php';
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
			$pageTitle='Добавление принтера';
			$action='addform';
			$id_printer='';
			$id_address='';
			$netpath='';
			$note='';
			$id='';
			$dis='';
			$button="Добавить";
			include $_SERVER['DOCUMENT_ROOT'].'/form/addprintershtml.php';
			exit;
		}
		//Добавляем Контрагента
		if (isset($_REQUEST['netpath']) && isset($_REQUEST['addform']))
		{
		
			//преобразуем путь к папке для записи в Mysql
			$_REQUEST["netpath"]=addslashes($_REQUEST["netpath"]);
			//$_REQUEST["container"]=addslashes($_REQUEST["container"]);
			try {
		
				$fields=array("netpath","id_address","id_printer","note");
				$sql='insert into printers set '.pdoSet($fields,$values);
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
				$sql='SELECT * FROM printers where id=:id';
				$sqlprep=$condb->prepare($sql);
				$sqlprep->bindValue(':id',$_REQUEST['id']);
				$sqlprep->execute();
			}
			catch (PDOException $e)
			{
				include '../form/errorhtml.php';
				exit;
			}
		
			$res=$sqlprep->fetch();
			$pageTitle='Редактирование принтеров';
			$action='editform';
			$id_printer=$res['id_printer'];
			$id_address=$res['id_address'];
			$netpath=$res['netpath'];
			$note=$res['note'];
			$id=$res['id'];;
			$dis='readonly';
			$button="Обновить";
			include $_SERVER['DOCUMENT_ROOT'].'/form/addprintershtml.php';
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
				$fields=array("netpath","id_address","id_printer","note");
				$sql='update printers set '.pdoSet($fields,$values).' where id=:id';
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
				$sql='DELETE FROM printers WHERE id=:id';
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
		//Вывод списка контрагентов
		try
		{
			$result=$condb->query('SELECT sprinters.name AS model, sprinters.id AS model_id , build.name as buildname, floor.floor, cabinet.id as id_cabinet, 
									cabinet.cabinet, printers.netpath, printers.id as printerid
								FROM printers
								LEFT JOIN sprinters ON printers.id_printer = sprinters.id
								LEFT JOIN cabinet ON cabinet.id = printers.id_address
								LEFT JOIN floor ON cabinet.id_floor = floor.id
								LEFT JOIN build ON floor.id_build = build.id');
		}
		catch (PDOExeption $e)
		{
			include '../form/errorhtml.php';
			exit;
		}
		
		foreach($result as $res)
		{
			//id-первичный ключ для поиска в таблице. Может принимать нужные значения
			$params[]=array('id'=>$res['printerid'], 'name'=>$res['model'].' '.$res['buildname'].' '.$res['floor'].' эт. '.$res['cabinet']);
		}
		//Титул управляющей страницы в творительном падеже
		$ctrltitle="принтерами";
		//Название ссылки в родительном падеже
		$ctrladd="принтер";
		
		include $_SERVER['DOCUMENT_ROOT'].'/form/ctrlonefieldshtml.php';
		
		//include $_SERVER['DOCUMENT_ROOT'].'/form/addagentshtml.php';
		if($condb!=null) {$condb=NULL;}
		
		
	}
	else header('Location: ../index.php?link='.$_SERVER['PHP_SELF']);
	exit;
?>