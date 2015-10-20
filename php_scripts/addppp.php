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
		//Добавляем Контрагента
		if (isset($_REQUEST['srv']) && isset($_REQUEST['addform']))	
		{
					try {
						//Добавляем его в таблицу
						$fields=array('typeppp', 'srv', 'login', 'pwd');
						$sql='insert into ppp set '.pdoSet($fields,$values);
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
		//Обновление контрагента
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
		//Удаление контрагента
		if (isset($_REQUEST['action']) && $_REQUEST['action']=='Удалить')
		{
			try
			{
				$sql='DELETE FROM ppp WHERE id=:id';
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
			$result=$condb->query('SELECT id, typeppp, srv FROM ppp order by srv, typeppp');
		}
		catch (PDOExeption $e)
		{
			include '../form/errorhtml.php';
			exit;
		}
		
		foreach($result as $res)
		{
			$params[]=array('id'=>$res['id'], 'name'=>$res['srv'].' '.$res['typeppp']);
		}
		//Титул управляющей страницы в творительном падеже
		$ctrltitle="PPP-подключениями";
		//Название ссылки в родительном падеже
		$ctrladd="PPP-подключения";
		
		include $_SERVER['DOCUMENT_ROOT'].'/form/ctrlonefieldshtml.php';
		
		//include $_SERVER['DOCUMENT_ROOT'].'/form/addagentshtml.php';
		if($condb!=null) {$condb=NULL;}
	}
	else header('Location: '.$_SERVER['DOCUMENT_ROOT'].'/index.php?link='.$_SERVER['PHP_SELF']);
	exit;
?>