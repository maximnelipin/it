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
			$pageTitle='Добавление модели принтера';
			$action='addform';
			$name='';
			$cart='';
			$drivers='';
			$id='';
			$button="Добавить";
			include $_SERVER['DOCUMENT_ROOT'].'/form/addsprintershtml.php';
			exit;
		}
		//Добавляем Контрагента
		if (isset($_REQUEST['name']) && isset($_REQUEST['addform']))
		{
				
			//преобразуем путь к папке для записи в Mysql
			$_REQUEST["drivers"]=addslashes($_REQUEST["drivers"]);
				
			try {
		
				$fields=array("name","cart","drivers");
				$sql='insert into sprinters set '.pdoSet($fields,$values);
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
				$sql='SELECT * FROM sprinters where id=:id';
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
			$pageTitle='Редактирование модели принтера';
			$action='editform';
			$id=$res['id'];
			$name=$res['name'];
			$cart=$res['cart'];
			$drivers=$res['drivers'];
			$button="Обновить";
			include $_SERVER['DOCUMENT_ROOT'].'/form/addsprintershtml.php';
			exit;
		
		}
		//Обновление контрагента
		if (isset($_REQUEST['editform']))
		{
			try
			{
				$fields=array("name","cart","drivers");
				$sql='update sprinters set '.pdoSet($fields,$values).' where id=:id';
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
				$sql='DELETE FROM sprinters WHERE id=:id';
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
			$result=$condb->query('SELECT id, name FROM sprinters ORDER BY name');
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
		$ctrltitle="моделями принтеров";
		//Название ссылки в родительном падеже
		$ctrladd="модели принтера";
		
		include $_SERVER['DOCUMENT_ROOT'].'/form/ctrlonefieldshtml.php';
		
		//include $_SERVER['DOCUMENT_ROOT'].'/form/addagentshtml.php';
		if($condb!=null) {$condb=NULL;}
		
		
	}
	else header('Location: ../index.php?link='.$_SERVER['PHP_SELF']);
	exit;
?>