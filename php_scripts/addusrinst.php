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
		
			//преобразуем путь к инструкции для записи в Mysql
			$_REQUEST["url"]='/usrinst/'.$_REQUEST["url"];
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
		//Удаление 
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
			$sql='SELECT id,name FROM usrinst ORDER BY name LIMIT 50';
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
				$params[]=array('id'=>$res['id'], 'name'=>$res['name']);
			}
		}
		//Титул управляющей страницы в творительном падеже
		$ctrltitle="интрукциями пользователей";
		//Название ссылки в родительном падеже
		$ctrladd=createLink("Добавить инструкцию пользователей","?add" );
		
		include $_SERVER['DOCUMENT_ROOT'].'/form/ctrl1html.php';	
		if($condb!=null) {$condb=NULL;}	
		
	}
	else header('Location: ../index.php?link='.$_SERVER['PHP_SELF']);
	exit;
?>