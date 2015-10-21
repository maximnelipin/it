<?php
	session_start();
	
	if(isset($_SESSION['user_id']))
	{	
		include 'func.php';
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
			$pageTitle='Добавление дежурства';
			$action='addform';
			$dateduty='';
			$login='';
			$id='';
			$dis='';
			$cls='tcal';
			$button="Добавить";
			include $_SERVER['DOCUMENT_ROOT'].'/form/addschedhtml.php';
			exit;
		}
		//Добавляем Контрагента
		if (isset($_REQUEST['login']) && isset($_REQUEST['addform']))
		{
				
			//преобразуем путь к папке для записи в Mysql
			//$_REQUEST["netpath"]=addslashes($_REQUEST["netpath"]);
				
			try {
		
				$fields=array("dateduty","login");
				$sql='insert into schedule set '.pdoSet($fields,$values);
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
				$sql='SELECT dateduty,login FROM schedule where dateduty=:dateduty';
				$sqlprep=$condb->prepare($sql);
				$sqlprep->bindValue(':dateduty',$_REQUEST['id']);
				$sqlprep->execute();
			}
			catch (PDOException $e)
			{
				include '../form/errorhtml.php';
				exit;
			}
				
			$res=$sqlprep->fetch();
			$pageTitle='Редактирование дежурства';
			$action='editform';
			$id='';
			$dateduty=$res['dateduty'];
			$login=$res['login'];
			$cls='text';
			$dis='readonly';
			$button="Обновить";
			include $_SERVER['DOCUMENT_ROOT'].'/form/addschedhtml.php';
			exit;
		
		}
		//Обновление контрагента
		if (isset($_REQUEST['editform']))
		{
			try
			{
				$fields=array("login");
				$sql='update schedule set '.pdoSet($fields,$values).' where dateduty=:dateduty';
				$sqlprep=$condb->prepare($sql);
				$values["dateduty"]=$_REQUEST['dateduty'];
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
				$sql='DELETE FROM schedule WHERE dateduty=:dateduty';
				$sqlprep=$condb->prepare($sql);
				$sqlprep->bindValue(':dateduty',$_POST['id']);
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
			$result=$condb->query('SELECT dateduty, login FROM schedule order by dateduty');
		}
		catch (PDOExeption $e)
		{
			include '../form/errorhtml.php';
			exit;
		}
		
		foreach($result as $res)
		{
			$params[]=array('id'=>$res['dateduty'], 'name'=>$res['dateduty']);
		}
		//Титул управляющей страницы в творительном падеже
		$ctrltitle="дежурствами";
		//Название ссылки в родительном падеже
		$ctrladd="дежурства";
		
		include $_SERVER['DOCUMENT_ROOT'].'/form/ctrlonefieldshtml.php';
		
		//include $_SERVER['DOCUMENT_ROOT'].'/form/addagentshtml.php';
		if($condb!=null) {$condb=NULL;}		
		
		
	}
	else header('Location: ../index.php?link='.$_SERVER['PHP_SELF']);
	exit;
?>