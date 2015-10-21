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
		if(isset($_REQUEST['add']))
		{
			$pageTitle='Добавление сервера';
			$action='addform';
			$name='';
			$id_cabinet='';
			$type='';
			$descrip='';
			$phys='';
			$rack='';
			$units='';
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
		
				$fields=array("name","id_cabinet","type","descrip","phys","rack","units","login","note");
				$sql='insert into servers set '.pdoSet($fields,$values);
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
				$sql='SELECT * FROM servers WHERE id=:id';
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
			$pageTitle='Редактирование сервера';
			$action='editform';
			$name=$res['name'];
			$id_cabinet=$res['id_cabinet'];
			$type=$res['type'];
			$descrip=$res['descrip'];
			$phys=$res['phys'];
			$rack=$res['rack'];
			$units=$res['units'];
			$login=$res['login'];
			$note=$res['note'];
			$id=$res['id'];
			$dis='readonly';
			$button="Обновить";
			include $_SERVER['DOCUMENT_ROOT'].'/form/addserverhtml.php';
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
				$fields=array("id_cabinet","type","descrip","phys","rack","units","login","note");
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
		
			header('Location: '.$_SERVER['PHP_SELF']);
			exit;
		
		}
		//Удаление контрагента
		if (isset($_REQUEST['action']) && $_REQUEST['action']=='Удалить')
		{
			try
			{
				$sql='DELETE FROM servers WHERE id=:id';
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
			$result=$condb->query('SELECT servers.name AS servername, build.name as buildname, floor.floor, cabinet.id as id_cabinet,
									cabinet.cabinet, servers.id as id_servers
								FROM servers
								LEFT JOIN cabinet ON cabinet.id = servers.id_cabinet
								LEFT JOIN floor ON cabinet.id_floor = floor.id
								LEFT JOIN build ON floor.id_build = build.id order by servername');
		}
		catch (PDOExeption $e)
		{
			include '../form/errorhtml.php';
			exit;
		}
		
		foreach($result as $res)
		{
			//id-первичный ключ для поиска в таблице. Может принимать нужные значения
			$params[]=array('id'=>$res['id_servers'], 'name'=>$res['servername'].' '.$res['buildname'].' '.$res['floor'].' эт. '.$res['cabinet']);
		}
		//Титул управляющей страницы в творительном падеже
		$ctrltitle="серверами";
		//Название ссылки в родительном падеже
		$ctrladd="сервер";
		
		include $_SERVER['DOCUMENT_ROOT'].'/form/ctrlonefieldshtml.php';
		
		//include $_SERVER['DOCUMENT_ROOT'].'/form/addagentshtml.php';
		if($condb!=null) {$condb=NULL;}
		
		
	}
	else header('Location: ../index.php?link='.$_SERVER['PHP_SELF']);
	exit;
?>