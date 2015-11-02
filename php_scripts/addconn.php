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
			$pageTitle='Добавление подключения к ЛВС';
			$action='addform';
			$gateway='';
			$id_operator='';
			$id_extnet='';
			$id_cabinet='';
			$typecon='';
			$mask='';
			$dhcp='';
			$dns1='';
			$dns2='';
			$id_ppp='';
			$loginlk='';
			$pwdlk='';
			$id_company='';
			$contract='';
			$note='';
			$id='';
			$dis='';
			$button="Добавить";
			include $_SERVER['DOCUMENT_ROOT'].'/form/addconnhtml.php';
			exit;
		}
		//Добавляем Контрагента
		if (isset($_REQUEST['gateway']) && isset($_REQUEST['addform']))
		{
		
			//Обнуляем перменные для связи с внешними таблицыми extnet,ppp,company
			$id_extnet='';
			$id_ppp='';
			$id_comp='';
			
			//Если добавляем
			if($_POST['radextnet']=="add")
			{
				//Если забит внешний ip
				if(isset($_POST['extip']))
				{
					try {
						//Добавляем его в таблицу
						$fieldsextnet=array('extip', 'extmask', 'extgw', 'extdns1', 'extdns2');
						$sql='insert into extnet set '.pdoSet($fieldsextnet,$valuesextnet);
						$sqlprep=$condb->prepare($sql);
						$sqlprep->execute($valuesextnet);
						//И получаем его id
						$_REQUEST['id_extnet']=$condb->lastInsertId();
						
					}
					catch (PDOException $e)
					{
						include '../form/errorhtml.php';
						exit;
					}
			
				}
			
			}
			//----------------------Добавление параметров ppp
			//Аналогично добавлению внешнего ip
				
			if($_POST['radppp']=="sel")
			{
				if($_POST['id_ppp']=='none')
				{
					$_POST['id_ppp']='';
				}
			
			}
			if($_POST['radppp']=="add")
			{
					
				if(isset($_POST['srv']))
				{
					try {
						$fieldsppp=array('srv', 'login', 'pwd', 'typeppp');
						$sql='insert into ppp set '.pdoSet($fieldsppp,$valuesppp);
						echo $sql;
						$sqlprep=$condb->prepare($sql);
						$sqlprep->execute($valuesppp);
						$_REQUEST['id_ppp']=$condb->lastInsertId();
					}
					catch (PDOException $e)
					{
						include '../form/errorhtml.php';
						exit;
					}
						
				}
			
			}
				
			//------------------------Добавление компании
			//Аналогично добавлению внешнего ip
			if($_POST['radcomp']=="add")
			{
					
				if(isset($_POST['name']))
				{
					try {
						$fieldscomp=array('name', 'innkpp');
						$sql='insert into company set '.pdoSet($fieldscomp,$valuescomp);
						$sqlprep=$condb->prepare($sql);
						$sqlprep->execute($valuescomp);
						$_REQUEST['id_company']=$condb->lastInsertId();
					}
					catch (PDOException $e)
					{
						include '../form/errorhtml.php';
						exit;
					}
			
				}
			
			}
			//----------Добавляем подключение-------------
			
			
			
			
			
			try {
		
				$fields=array('gateway', 'id_operator', 'typecon','mask','dhcp','dns1','dns2','loginlk',
					'pwdlk','contract','note','id_company','id_ppp','id_extnet','id_cabinet');
				$sql='insert into conn set '.pdoSet($fields,$values);
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
				$sql='SELECT * FROM conn WHERE id=:id';
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
			$pageTitle='Редактирование подключения к ЛВС';
			$action='editform';
			$gateway=$res['gateway'];
			$id_operator=$res['id_operator'];
			$id_extnet=$res['id_extnet'];
			$id_cabinet=$res['id_cabinet'];
			$typecon=$res['typecon'];
			$mask=$res['mask'];
			$dhcp=$res['dhcp'];
			$dns1=$res['dns1'];
			$dns2=$res['dns2'];
			$id_ppp=$res['id_ppp'];
			$loginlk=$res['loginlk'];
			$pwdlk=$res['pwdlk'];
			$id_company=$res['id_company'];
			$contract=$res['contract'];
			$note=$res['note'];
			$id=$res['id'];
			$dis='readonly';
			$button="Обновить";
			include $_SERVER['DOCUMENT_ROOT'].'/form/addconnhtml.php';
			exit;
		
		}
		//Обновление
		if (isset($_REQUEST['editform']))
		
		
		{
		
			//Обнуляем перменные для связи с внешними таблицыми extnet,ppp,company
			$id_extnet='';
			$id_ppp='';
			$id_comp='';
			
			//Если добавляем
			if($_POST['radextnet']=="add")
			{
				//Если забит внешний ip
				if(isset($_POST['extip']))
				{
					try {
						//Добавляем его в таблицу
						$fieldsextnet=array('extip', 'extmask', 'extgw', 'extdns1', 'extdns2');
						$sql='insert into extnet set '.pdoSet($fieldsextnet,$valuesextnet);
						$sqlprep=$condb->prepare($sql);
						$sqlprep->execute($valuesextnet);
						//И получаем его id
						$_REQUEST['id_extnet']=$condb->lastInsertId();
							
							
					}
					catch (PDOException $e)
					{
						include '../form/errorhtml.php';
						exit;
					}
			
				}
			
			}
			//----------------------Добавление параметров ppp
			//Аналогично добавлению внешнего ip
				
			if($_REQUEST['radppp']=="sel")
			{
				if($_REQUEST['id_ppp']=='none')
				{
					$_REQUEST['id_ppp']='';
				}
			
			}
			if($_REQUEST['radppp']=="add")
			{
					
				if(isset($_REQUEST['srv']))
				{
					try {
						$fieldsppp=array('srv', 'login', 'pwd', 'typeppp');
						$sql='insert into ppp set '.pdoSet($fieldsppp,$valuesppp);
						echo $sql;
						$sqlprep=$condb->prepare($sql);
						$sqlprep->execute($valuesppp);
						$_REQUEST['id_ppp']=$condb->lastInsertId();
					}
					catch (PDOException $e)
					{
						include '../form/errorhtml.php';
						exit;
					}
						
				}
			
			}
				
			//------------------------Добавление компании
			//Аналогично добавлению внешнего ip
			if($_REQUEST['radcomp']=="add")
			{
					
				if(isset($_REQUEST['name']))
				{
					try {
						$fieldscomp=array('name', 'innkpp');
						$sql='insert into company set '.pdoSet($fieldscomp,$valuescomp);
						$sqlprep=$condb->prepare($sql);
						$sqlprep->execute($valuescomp);
						$_REQUEST['id_company']=$condb->lastInsertId();
						echo $sql;
					}
					catch (PDOException $e)
					{
						include '../form/errorhtml.php';
						exit;
					}
			
				}
			
			}
			//------------обновляем подключение-------------
			try
			{
				$fields=array('id_operator', 'typecon','mask','dhcp','dns1','dns2','loginlk',
					'pwdlk','contract','note','id_company','id_ppp','id_extnet','id_cabinet');
				$sql='update conn set '.pdoSet($fields,$values).' where id=:id';
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
				$sql='DELETE FROM conn WHERE id=:id';
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
		//Вывод списка полей
		try
		{
			$result=$condb->query('SELECT conn.gateway AS conngw, build.name as buildname, floor.floor, cabinet.id as id_cabinet,
									cabinet.cabinet, isp.name as ispname, conn.id_extnet as id_extnet, conn.id as id_conn
								FROM conn
								LEFT JOIN isp ON conn.id_operator=isp.id
								LEFT JOIN cabinet ON cabinet.id = conn.id_cabinet
								LEFT JOIN floor ON cabinet.id_floor = floor.id
								LEFT JOIN build ON floor.id_build = build.id order by conngw');
		}
		catch (PDOExeption $e)
		{
			include '../form/errorhtml.php';
			exit;
		}
		
		foreach($result as $res)
		{
			//id-первичный ключ для поиска в таблице. Может принимать нужные значения
			$params[]=array('id'=>$res['id_conn'], 'name'=>$res['conngw'].' '.$res['ispname'].' '.$res['buildname'].' '.$res['floor'].' эт. '.$res['cabinet']);
		}
		//Титул управляющей страницы в творительном падеже
		$ctrltitle="подключениями к ЛВС";
		//Название ссылки в родительном падеже
		$ctrladd="подключение к ЛВС";
		
		include $_SERVER['DOCUMENT_ROOT'].'/form/ctrlonefieldshtml.php';
		
		//include $_SERVER['DOCUMENT_ROOT'].'/form/addagentshtml.php';
		if($condb!=null) {$condb=NULL;}
		
		
		
		
	}
	else header('Location: ../index.php?link='.$_SERVER['PHP_SELF']);
	exit;
?>