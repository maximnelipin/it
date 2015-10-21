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
			$pageTitle='Добавление провайдера';
			$action='addform';
			$name='';
			$telsup='';
			$manager='';
			$telman='';
			$emailman='';
			$address='';
			$urllk='';
			$netpath='';
			$note='';
			$id='';
			$button="Добавить";
			include $_SERVER['DOCUMENT_ROOT'].'/form/addisphtml.php';
			exit;
		}
		//Добавляем Контрагента
		if (isset($_REQUEST['name']) && isset($_REQUEST['addform']))	
		{
			
			//преобразуем путь к папке для записи в Mysql
			$_REQUEST["netpath"]=addslashes($_REQUEST["netpath"]);
			$_REQUEST["urllk"]=addslashes($_REQUEST["urllk"]);
			
			try {
				
				$fields=array("name", "telsup","manager","telman","emailman","address","urllk","netpath","note");
				$sql='insert into isp set '.pdoSet($fields,$values);
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
				$sql='SELECT * FROM isp where id=:id';
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
			$pageTitle='Редактирование провайдера';
			$action='editform';
			$id=$res['id'];
			$name=$res['name'];
			$telsup=$res['telsup'];
			$manager=$res['manager'];
			$telman=$res['telman'];
			$emailman=$res['emailman'];
			$address=$res['address'];
			$urllk=$res['urllk'];
			$netpath=$res['netpath'];
			$note=$res['note'];
			$button="Обновить";
			include $_SERVER['DOCUMENT_ROOT'].'/form/addisphtml.php';
			exit;
		
		}
		//Обновление контрагента
		if (isset($_REQUEST['editform']))
		{
			try
			{
				$fields=array("name", "telsup","manager","telman","emailman","address","urllk","netpath","note");
				$sql='update isp set '.pdoSet($fields,$values).' where id=:id';				
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
				$sql='DELETE FROM isp WHERE id=:id';
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
			$result=$condb->query('SELECT id, name FROM isp order by name');
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
		$ctrltitle="провайдерами";
		//Название ссылки в родительном падеже
		$ctrladd="провайдера";
		
		include $_SERVER['DOCUMENT_ROOT'].'/form/ctrlonefieldshtml.php';
		
		//include $_SERVER['DOCUMENT_ROOT'].'/form/addisphtml.php';
		if($condb!=null) {$condb=NULL;}		
		
	}
	else header('Location: ../index.php?link='.$_SERVER['PHP_SELF']);
	exit;
?>