<?php
	session_start();
	if(isset($_SESSION['user_id']))
	{	//Файл с функциями
		include_once $_SERVER['DOCUMENT_ROOT'].'/php_scripts/func.php';
		//Файл подключения к БД
		include_once $_SERVER['DOCUMENT_ROOT'].'/php_scripts/mysql_conf.php';
		
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
		//Добавляем 
		if (isset($_REQUEST['name']) && isset($_REQUEST['addform']))
		{
				
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
			header('Location: '.$_SERVER['PHP_SELF'].'?add');
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
			$id='';
			$dis='readonly';
			$button="Обновить";
			include $_SERVER['DOCUMENT_ROOT'].'/form/addgpohtml.php';
			exit;
		
		}
		//Обновление 
		if (isset($_REQUEST['editform']))
		{
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
		//Удаление 
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
		//Вывод списка 
		try
		{
			$sql='SELECT name FROM gpo order by name LIMIT 50';
			$sqlprep=$condb->prepare($sql);
			$sqlprep->execute();
		}
		catch (PDOExeption $e)
		{
			include '../form/errorhtml.php';
			exit;
		}
		if($sqlprep->rowCount()>0)
		{
			$result=$sqlprep->fetchall();
			foreach($result as $res)
			{
				//id-первичный ключ для поиска в таблице. Может принимать нужные значения
				$params[]=array('id'=>$res['name'], 'name'=>$res['name']);
			}
		}
		//Титул управляющей страницы в творительном падеже
		$ctrltitle="GPO";
		//Название ссылки в родительном падеже
		$ctrladd=createLink("Добавить GPO","?add" );
		
		include $_SERVER['DOCUMENT_ROOT'].'/form/ctrl1html.php';
		if($condb!=null) {$condb=NULL;}
	}
	else header('Location: ../index.php?link='.$_SERVER['PHP_SELF']);
	exit;
?>