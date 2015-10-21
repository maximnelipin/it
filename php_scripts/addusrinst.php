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
			$pageTitle='Добавление инструкции пользователей';
			$action='addform';
			$name='';
			$url='';
			$id='';
			$dis='';
			$button="Добавить";
			include $_SERVER['DOCUMENT_ROOT'].'/form/addusrinsthtml.php';
			exit;
		}
		//Добавляем Контрагента
		if (isset($_REQUEST['name']) && isset($_REQUEST['addform']))
		{
		
			//преобразуем путь к папке для записи в Mysql
			$_REQUEST["url"]=addslashes($_REQUEST["url"]);
			//$_REQUEST["container"]=addslashes($_REQUEST["container"]);
			try {
		
				$fields=array("name","url");
				$sql='insert into usrinst set '.pdoSet($fields,$values);
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
				$sql='SELECT * FROM usrinst where id=:id';
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
			$pageTitle='Редактирование инструкции пользователя';
			$action='editform';
			$name=$res['name'];
			$url=$res['url'];
			$id=$res['id'];
			$dis='disabled';
			$button="Обновить";
			include $_SERVER['DOCUMENT_ROOT'].'/form/addusrinsthtml.php';
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
				$fields=array("name","url");
				$sql='update usrinst set '.pdoSet($fields,$values).' where id=:id';
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
				$sql='DELETE FROM usrinst WHERE id=:id';
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
		//Вывод списка 
		try
		{
			$result=$condb->query('SELECT id,name FROM usrinst order by name');
		}
		catch (PDOExeption $e)
		{
			include '../form/errorhtml.php';
			exit;
		}
		
		foreach($result as $res)
		{
			//id-первичный ключ для поиска в таблице. Может принимать нужные значения
			$params[]=array('id'=>$res['id'], 'name'=>$res['name']);
		}
		//Титул управляющей страницы в творительном падеже
		$ctrltitle="интрукциями пользователей";
		//Название ссылки в родительном падеже
		$ctrladd="инструкцию пользователей";
		
		include $_SERVER['DOCUMENT_ROOT'].'/form/ctrlonefieldshtml.php';
		
		//include $_SERVER['DOCUMENT_ROOT'].'/form/addagentshtml.php';
		if($condb!=null) {$condb=NULL;}	
		
	}
	else header('Location: ../index.php?link='.$_SERVER['PHP_SELF']);
	exit;
?>