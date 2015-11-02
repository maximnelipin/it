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
			$pageTitle='Добавление компании';
			$action='addform';
			$name='';
			$innkpp='';
			$id='';
			$button="Добавить";
			include $_SERVER['DOCUMENT_ROOT'].'/form/addcompanyhtml.php';
			exit;
		}
		//Добавляем 
		if (isset($_REQUEST['name']) && isset($_REQUEST['addform']))	
		{
			addCompany($condb);
			
			header('Location: '.$_SERVER['PHP_SELF'].'?add');
			exit;
		}
		//Выводим форму на редактирование
		if (isset($_REQUEST['action']) && $_REQUEST['action']=='Редактировать')
		{
			try 
			{
				$sql='SELECT * FROM company where id=:id';
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
			$pageTitle='Редактирование компании';
			$action='editform';
			$id=$res['id'];
			$name=$res['name'];
			$innkpp=$res['innkpp'];
			$button="Обновить";
			include $_SERVER['DOCUMENT_ROOT'].'/form/addcompanyhtml.php';
			exit;
		
		}
		//Обновление 
		if (isset($_REQUEST['editform']))
		{
			try
			{
				$fields=array("name","innkpp");
				$sql='update company set '.pdoSet($fields,$values).' where id=:id';				
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
				$sql='DELETE FROM company WHERE id=:id';
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
			
			$sql='SELECT id, name FROM company ORDER BY name LIMIT 50';
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
				$params[]=array('id'=>$res['id'], 'name'=>$res['name']);
			}
		}
		//Титул управляющей страницы в творительном падеже
		$ctrltitle="компаниями";
		//Название ссылки в родительном падеже
		$ctrladd=createLink("Добавить компанию","?add" );
		
		include $_SERVER['DOCUMENT_ROOT'].'/form/ctrl1html.php';
		if($condb!=null) {$condb=NULL;}
	}
	else header('Location: '.$_SERVER['DOCUMENT_ROOT'].'/index.php?link='.$_SERVER['PHP_SELF']);
	exit;
?>