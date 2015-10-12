<?php
	session_start();
	include 'func.php';
	include 'mysql_conf.php';
	if(isset($_SESSION['user_id']))
	{	
		
		try {
			$condb=new PDO('mysql:host='.$hostsql.';dbname='.$dbname, $dbuser, $dbpwd);
			$condb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$condb->exec('SET NAMES "utf8"');
		}
		catch (PDOException $e)
		{
			$error= 'Не удалось выполнить запрос'.$e->getMessage();	
			$urlerr=$_SERVER['PHP_SELF'];
			//$_SESSION['erroor']=$error;
			//$_SESSION['urlerr']=$urlerr;
			include '../form/errorhtml.php';
			exit;
		}
		include $_SERVER['DOCUMENT_ROOT'].'/form/addconnhtml.php';
		if (isset($_POST['gateway']))	
		{
			//Обнуляем перменные для связи с внешними таблицыми extnet,ppp,company
			$id_extnet='';
			$id_ppp='';
			$id_comp='';
			//Если идёт выбор, то запоминаем id внешних параметров
			//if($_POST['radextnet']=="sel")
				//{$id_extnet=$_POST['id_extnet'];}
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
							$_POST['id_extnet']=$condb->lastInsertId();
							
							
						}
						catch (PDOException $e)
						{
							echo 'Не удалось выполнить запрос';
							echo $e->getMessage();
							exit;
						}			
						
					}
										
				}			
			echo $id_extnet;
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
						$_POST['id_ppp']=$condb->lastInsertId();
					}
					catch (PDOException $e)
					{
						echo 'Не удалось выполнить запрос';
						echo $e->getMessage();
						exit;
					}
			
				}
				
			}		
			
			echo $id_ppp;
			//------------------------Добавление компании
			//Аналогично добавлению внешнего ip
			//if($_POST['radcomp']=="sel")
			//{$id_ppp=$_POST['id_company'];}
			if($_POST['radcomp']=="add")
			{
					
				if(isset($_POST['name']))
				{
					try {
						$fieldscomp=array('name', 'innkpp');
						$sql='insert into company set '.pdoSet($fieldscomp,$valuescomp);
						$sqlprep=$condb->prepare($sql);
						$sqlprep->execute($valuescomp);
						$_POST['id_company']=$condb->lastInsertId();
						echo $sql;
					}
					catch (PDOException $e)
					{
						echo 'Не удалось выполнить запрос';
						echo $e->getMessage();
						exit;
					}
						
				}

			}
			//----------Добавляем подключение-------------
			
			try {
				$fields=array('gateway', 'id_operator', 'typecon','mask','dhcp','dns1','dns2','loginlk',
					'pwdlk','contract','note','id_company','id_ppp','id_extnet','id_address');
				$sql='insert into conn set '.pdoSet($fields,$values);
				$sqlprep=$condb->prepare($sql);
				$sqlprep->execute($values);
			}
			catch (PDOException $e)
			{
				echo 'Не удалось выполнить запрос';
				echo $e->getMessage();
				exit;
			}
			
			header('Location .');
			exit;
		}	
	}
	else header('Location ../index.php');
?>