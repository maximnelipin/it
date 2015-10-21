<?php
	session_start();
	if(isset($_SESSION['user_id']))
	{	include $_SERVER['DOCUMENT_ROOT'].'/php_scripts/func.php';
		include $_SERVER['DOCUMENT_ROOT'].'/php_scripts/mysql_conf.php';
		//Подключаемся к БД
		try {
			$condb=new PDO('mysql:host='.$hostsql.';dbname='.$dbname, $dbuser, $dbpwd);
			$condb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$condb->exec('SET NAMES "utf8"');
		}
		catch (PDOException $e)		{
			
			include '../form/errorhtml.php';
			exit;
		}
		//Выводим форму на добавление 
		if(isset($_REQUEST['add']))
		{
			$pageTitle='Добавление контрагента';
			$action='addform';
			$name='';
			$manager='';
			$telman='';
			$emailman='';
			$address='';
			$type='';
			$netpath='';
			$note='';
			$id='';
			$button="Добавить";
			include $_SERVER['DOCUMENT_ROOT'].'/form/addagentshtml.php';
			exit;
		}
		//Добавляем Контрагента
		if (isset($_REQUEST['name']) && isset($_REQUEST['addform']))	
		{
			
			//преобразуем путь к папке для записи в Mysql
			$_REQUEST["netpath"]=addslashes($_REQUEST["netpath"]);
			
			try {
				
				$fields=array("name","manager","telman","emailman","address","type","netpath","note");
				$sql='insert into agents set '.pdoSet($fields,$values);
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
				$sql='SELECT * FROM agents where id=:id';
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
			$pageTitle='Редактирование контрагента';
			$action='editform';
			$id=$res['id'];
			$name=$res['name'];
			$manager=$res['manager'];
			$telman=$res['telman'];
			$emailman=$res['emailman'];
			$address=$res['address'];
			$type=$res['type'];
			$netpath=$res['netpath'];
			$note=$res['note'];
			$button="Обновить";
			include $_SERVER['DOCUMENT_ROOT'].'/form/addagentshtml.php';
			exit;
		
		}
		//Обновление контрагента
		if (isset($_REQUEST['editform']))
		{
			try
			{
				$fields=array("name","manager","telman","emailman","address","type","netpath","note");
				$sql='update agents set '.pdoSet($fields,$values).' where id=:id';				
				$sqlprep=$condb->prepare($sql);
				$values["id"]=$_REQUEST['id'];
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
				$sql='DELETE FROM agents WHERE id=:id';
				$sqlprep=$condb->prepare($sql);
				$sqlprep->bindValue(':id',$_POST['id']);
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
			$result=$condb->query('SELECT id, name FROM agents order by name');
		}
		catch (PDOExeption $e)
		{
			include '../form/errorhtml.php';
			exit;
		}
		
		foreach($result as $res)
		{
			$params[]=array('id'=>$res['id'], 'name'=>$res['name']);
		}
		//Титул управляющей страницы в творительном падеже
		$ctrltitle="контрагентами";
		//Название ссылки в родительном падеже
		$ctrladd="контрагента";
		
		include $_SERVER['DOCUMENT_ROOT'].'/form/ctrlonefieldshtml.php';
		
		//include $_SERVER['DOCUMENT_ROOT'].'/form/addagentshtml.php';
		if($condb!=null) {$condb=NULL;}
	}
	else header('Location: '.$_SERVER['DOCUMENT_ROOT'].'/index.php?link='.$_SERVER['PHP_SELF']);
	exit;
?>