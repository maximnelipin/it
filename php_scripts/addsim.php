<?php
	session_start();
	if(isset($_SESSION['user_id']))
	{	
		include 'mysql_conf.php';
		include 'func.php';
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
			$pageTitle='Добавление сим-карт';
			$action='addform';
			$number='';
			$account='';
			$id_operator='';
			$id_address='';
			$login='';
			$balance='';
			$pay='';
			$pwdlk='';
			$note='';
			$id='';
			$dis='';
			$button="Добавить";
			include $_SERVER['DOCUMENT_ROOT'].'/form/addsimhtml.php';
			exit;
		}
		//Добавляем 
		if (isset($_REQUEST['number']) && isset($_REQUEST['addform']))
		{
		
			//преобразуем путь к папке для записи в Mysql
			//$_REQUEST["netpath"]=addslashes($_REQUEST["netpath"]);
			//$_REQUEST["container"]=addslashes($_REQUEST["container"]);
			try {
		
				$fields=array("number","account","id_address","id_operator","login","balance","pay","pwdlk","note");
				$sql='insert into sim set '.pdoSet($fields,$values);
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
				$sql='SELECT * FROM sim where number=:number';
				$sqlprep=$condb->prepare($sql);
				$sqlprep->bindValue(':number',$_REQUEST['id']);
				$sqlprep->execute();
			}
			catch (PDOException $e)
			{
				include '../form/errorhtml.php';
				exit;
			}
		
			$res=$sqlprep->fetch();
			$pageTitle='Редактирование сим-карт';
			$action='editform';
			$number=$res['number'];
			$account=$res['account'];
			$id_operator=$res['id_operator'];
			$id_address=$res['id_address'];
			$login=$res['login'];
			$balance=$res['balance'];
			$pay=$res['pay'];
			$pwdlk=$res['pwdlk'];
			$note=$res['note'];
			$id='';
			$dis='readonly';
			$button="Обновить";
			include $_SERVER['DOCUMENT_ROOT'].'/form/addsimhtml.php';
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
				$fields=array("account","id_address","id_operator","login","balance","pay","pwdlk","note");
				$sql='update sim set '.pdoSet($fields,$values).' where number=:number';
				$sqlprep=$condb->prepare($sql);
				$values["number"]=$_POST['number'];
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
				$sql='DELETE FROM sim WHERE number=:number';
				$sqlprep=$condb->prepare($sql);
				$sqlprep->bindValue(':number',$_REQUEST['id']);
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
			$result=$condb->query('SELECT number FROM sim order by number');
		}
		catch (PDOExeption $e)
		{
			include '../form/errorhtml.php';
			exit;
		}
		
		foreach($result as $res)
		{
			//id-первичный ключ для поиска в таблице. Может принимать нужные значения
			$params[]=array('id'=>$res['number'], 'name'=>$res['number']);
		}
		//Титул управляющей страницы в творительном падеже
		$ctrltitle="сим-картами";
		//Название ссылки в родительном падеже
		$ctrladd="сим-карту";
		
		include $_SERVER['DOCUMENT_ROOT'].'/form/ctrlonefieldshtml.php';
		
		//include $_SERVER['DOCUMENT_ROOT'].'/form/addagentshtml.php';
		if($condb!=null) {$condb=NULL;}
		
		
	}
	else header('Location: ../index.php?link='.$_SERVER['PHP_SELF']);
	exit;
?>