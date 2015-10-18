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
			$pageTitle='Добавление GPO';
			$action='addform';
			$name='';
			$container='';
			$netpath='';
			$descrip='';			
			$id='';
			$dis='';
			$button="Добавить";
			include $_SERVER['DOCUMENT_ROOT'].'/form/addgpohtml.php';
			exit;
		}
		//Добавляем Контрагента
		if (isset($_REQUEST['name']) && isset($_REQUEST['addform']))
		{
				
			//преобразуем путь к папке для записи в Mysql
			$_REQUEST["netpath"]=addslashes($_REQUEST["netpath"]);
			$_REQUEST["container"]=addslashes($_REQUEST["container"]);
			try {
		
				$fields=array("name","container","netpath","descrip");
				$sql='insert into gpo set '.pdoSet($fields,$values);
				$sqlprep=$condb->prepare($sql);
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
		//Выводим форму на редактирование
		if (isset($_REQUEST['action']) && $_REQUEST['action']=='Редактировать')
		{
			try
			{
				$sql='SELECT * FROM gpo where name=:name';
				$sqlprep=$condb->prepare($sql);
				$sqlprep->bindValue(':name',$_REQUEST['id']);
				$sqlprep->execute();
			}
			catch (PDOException $e)
			{
				include '../form/errorhtml.php';
				exit;
			}
				
			$res=$sqlprep->fetch();
			$pageTitle='Редактирование GPO';
			$action='editform';
			$name=$res['name'];
			$container=$res['container'];
			$netpath=$res['netpath'];
			$descrip=$res['descrip'];
			$dis='disabled';
			$button="Обновить";
			include $_SERVER['DOCUMENT_ROOT'].'/form/addgpohtml.php';
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
				$fields=array("container","netpath","descrip");
				$sql='update gpo set '.pdoSet($fields,$values).' where name=:name';
				$sqlprep=$condb->prepare($sql);
				$values["name"]=$_REQUEST['name'];
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
				$sql='DELETE FROM gpo WHERE name=:name';
				$sqlprep=$condb->prepare($sql);
				$sqlprep->bindValue(':name',$_REQUEST['id']);
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
			$result=$condb->query('SELECT name FROM gpo order by name');
		}
		catch (PDOExeption $e)
		{
			include '../form/errorhtml.php';
			exit;
		}
		
		foreach($result as $res)
		{
			//id-первичный ключ для поиска в таблице. Может принимать нужные значения
			$params[]=array('id'=>$res['name'], 'name'=>$res['name']);
		}
		//Титул управляющей страницы в творительном падеже
		$ctrltitle="GPO";
		//Название ссылки в родительном падеже
		$ctrladd="GPO";
		
		include $_SERVER['DOCUMENT_ROOT'].'/form/ctrlonefieldshtml.php';
		
		//include $_SERVER['DOCUMENT_ROOT'].'/form/addagentshtml.php';
		if($condb!=null) {$condb=NULL;}
	}
	else header('Location: ../index.php?link='.$_SERVER['PHP_SELF']);
	exit;
?>