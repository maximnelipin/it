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
			$pageTitle='Добавление внешнего IP';
			$action='addform';
			$extip='';
			$extmask='';
			$extgw='';
			$extdns1='';
			$extdns2='';
			$id='';
			$button="Добавить";
			include $_SERVER['DOCUMENT_ROOT'].'/form/addextnethtml.php';
			exit;
		}
		//Добавляем Контрагента
		if (isset($_REQUEST['extip']) && isset($_REQUEST['addform']))	
		{
					try {
						//Добавляем его в таблицу
						$fieldsextnet=array('extip', 'extmask', 'extgw', 'extdns1', 'extdns2');
						$sql='insert into extnet set '.pdoSet($fieldsextnet,$valuesextnet);
						$sqlprep=$condb->prepare($sql);
						$sqlprep->execute($valuesextnet);
						
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
				$sql='SELECT * FROM extnet where id=:id';
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
			$extip=$res['extip'];
			$extmask=$res['extmask'];
			$extgw=$res['extgw'];
			$extdns1=$res['extdns1'];
			$extdns2=$res['extdns2'];
			$button="Обновить";
			include $_SERVER['DOCUMENT_ROOT'].'/form/addextnethtml.php';
			exit;
		
		}
		//Обновление контрагента
		if (isset($_REQUEST['editform']))
		{
			try
			{
				$fieldsextnet=array('extip', 'extmask', 'extgw', 'extdns1', 'extdns2');
				$sql='update extnet set '.pdoSet($fieldsextnet,$valuesextnet).' where id=:id';	
				$sqlprep=$condb->prepare($sql);
				$valuesextnet["id"]=$_REQUEST['id'];
				$sqlprep->execute($valuesextnet);
				
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
				$sql='DELETE FROM extnet WHERE id=:id';
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
			$result=$condb->query('SELECT id, extip FROM extnet order by extip');
		}
		catch (PDOExeption $e)
		{
			include '../form/errorhtml.php';
			exit;
		}
		
		foreach($result as $res)
		{
			$params[]=array('id'=>$res['id'], 'name'=>$res['extip']);
		}
		//Титул управляющей страницы в творительном падеже
		$ctrltitle="внешними IP";
		//Название ссылки в родительном падеже
		$ctrladd="внешний IP";
		
		include $_SERVER['DOCUMENT_ROOT'].'/form/ctrlonefieldshtml.php';
		
		//include $_SERVER['DOCUMENT_ROOT'].'/form/addagentshtml.php';
		if($condb!=null) {$condb=NULL;}
	}
	else header('Location: '.$_SERVER['DOCUMENT_ROOT'].'/index.php?link='.$_SERVER['PHP_SELF']);
	exit;
?>