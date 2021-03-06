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
			$pageTitle='Добавление PPP-подключения';
			$action='addform';
			$typeppp='';
			$srv='';
			$login='';
			$pwd='';			
			$id='';
			$button="Добавить";
			include $_SERVER['DOCUMENT_ROOT'].'/form/addppphtml.php';
			exit;
		}
		//Добавляем 
		if (isset($_REQUEST['srv']) && isset($_REQUEST['addform']))	
		{
				addPPP($condb);
			
			header('Location: '.$_SERVER['PHP_SELF'].'?add');
			exit;
		}
		//Выводим форму на редактирование
		if (isset($_REQUEST['action']) && $_REQUEST['action']=='Редактировать')
		{
			try 
			{
				$sql='SELECT * FROM ppp where id=:id';
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
			$pageTitle='Редактирование PPP-подключения';
			$action='editform';
			$id=$res['id'];
			$typeppp=$res['typeppp'];
			$srv=$res['srv'];
			$login=$res['login'];
			$pwd=$res['pwd'];
			$button="Обновить";
			include $_SERVER['DOCUMENT_ROOT'].'/form/addppphtml.php';
			exit;
		
		}
		//Обновление 
		if (isset($_REQUEST['editform']))
		{
			try
			{
				$fields=array('typeppp', 'srv', 'login', 'pwd');
				$sql='update ppp set '.pdoSet($fields,$values).' where id=:id';	
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
		//Удаление 
		if (isset($_REQUEST['action']) && $_REQUEST['action']=='Удалить')
		{
			try
			{
				$sql='DELETE FROM ppp WHERE id=:id';
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
			$sql='SELECT id, typeppp, srv FROM ppp ORDER BY srv, typeppp LIMIT 50';
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
				$params[]=array('id'=>$res['id'], 'name'=>$res['srv'].' '.$res['typeppp']);
			}
		}
		
		//Титул управляющей страницы в творительном падеже
		$ctrltitle="PPP-подключениями";
		//Название ссылки в родительном падеже
		$ctrladd=createLink("Добавить PPP-подключения","?add" );
		
		include $_SERVER['DOCUMENT_ROOT'].'/form/ctrl1html.php';
		
		if($condb!=null) {$condb=NULL;}
	}
	else header('Location: '.$_SERVER['DOCUMENT_ROOT'].'/index.php?link='.$_SERVER['PHP_SELF']);
	exit;
?>