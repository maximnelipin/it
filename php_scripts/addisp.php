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
		//Добавляем 
		if (isset($_REQUEST['name']) && isset($_REQUEST['addform']))	
		{			
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
		//Обновление 
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
		//Удаление 
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
		//Вывод списка 
		try
		{	
			$sql='SELECT id, name FROM isp order by name LIMIT 50';
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
		$ctrltitle="провайдерами";
		//Название ссылки в родительном падеже
		$ctrladd=createLink("Добавить провайдера","?add" );
		
		include $_SERVER['DOCUMENT_ROOT'].'/form/ctrl1html.php';
		if($condb!=null) {$condb=NULL;}		
		
	}
	else header('Location: ../index.php?link='.$_SERVER['PHP_SELF']);
	exit;
?>