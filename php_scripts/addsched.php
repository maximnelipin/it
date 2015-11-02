<?php
	session_start();
	
	if(isset($_SESSION['user_id']))
	{	
		//Файл с функциями
		include_once $_SERVER['DOCUMENT_ROOT'].'/php_scripts/func.php';
		//Файл подключения к БД
		include_once $_SERVER['DOCUMENT_ROOT'].'/php_scripts/mysql_conf.php';
		
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
		//Добавляем 
		if (isset($_REQUEST['login']) && isset($_REQUEST['addform']))
		{
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
			$id='';
			$cls='text';
			$dis='readonly';
			$button="Обновить";
			include $_SERVER['DOCUMENT_ROOT'].'/form/addschedhtml.php';
			exit;
		
		}
		//Обновление
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
		//Удаление 
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
			$sql='SELECT dateduty, login FROM schedule ORDER BY dateduty LIMIT 50';
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
				$params[]=array('id'=>$res['dateduty'], 'name'=>$res['dateduty']);
			}
		}
		//Титул управляющей страницы в творительном падеже
		$ctrltitle="дежурствами";
		//Название ссылки в родительном падеже
		$ctrladd=createLink("Добавить дежурство","?add" );
		
		include $_SERVER['DOCUMENT_ROOT'].'/form/ctrl1html.php';
		
		if($condb!=null) {$condb=NULL;}		
		
		
	}
	else header('Location: ../index.php?link='.$_SERVER['PHP_SELF']);
	exit;
?>