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
			header('Location: '.$_SERVER['PHP_SELF'].'?add');
			exit;
		}
		//Выводим форму на редактирование
		if (isset($_REQUEST['action']) && $_REQUEST['action']=='Редактировать')
		{
			try
			{
				$sql='SELECT * FROM sim where number=:number';
				$sqlprep=$condb->prepare($sql);
				$sqlprep->bindValue(':number',$_REQUEST['id_1']);
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
			//Обновляем поля сим-карты
			try
			{
				$fields=array("account","id_address","id_operator","login","balance","pay","pwdlk","note");
				$sql='update sim set '.pdoSet($fields,$values).' where number=:number';
				$sqlprep=$condb->prepare($sql);
				$values["number"]=$_REQUEST['number'];
				$sqlprep->execute($values);
			}
			catch (PDOException $e)
			{
				include '../form/errorhtml.php';
				exit;
			}
			//Обновляем баланс на привязанном лицевом счёте сим-карты
			try
			{
				$fields=array("balance");
				$sql='update sim set '.pdoSet($fields,$values).' where account=:account';
				$sqlprep=$condb->prepare($sql);
				$values["account"]=$_REQUEST['account'];
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
				$sql='DELETE FROM sim WHERE number=:number';
				$sqlprep=$condb->prepare($sql);
				$sqlprep->bindValue(':number',$_REQUEST['id_1']);
				$sqlprep->execute();
			}
			catch (PDOException $e)
			{
				include '../form/errorhtml.php';
				exit;
			}
		
		}
		//Вывод списка лицевых счетов
		try
		{
			$sql='SELECT DISTINCT account FROM sim ORDER BY account LIMIT 50';
			$sqlprep=$condb->prepare($sql);
			$sqlprep->execute();
		}
		catch (PDOExeption $e)
		{
			include '../form/errorhtml.php';
			exit;
		}
		//Подготовка выборки сим-карт по лицевому счёту
		$sqlsim='SELECT number FROM sim WHERE account=:account ORDER BY number LIMIT 50';
		$sqlprepsim=$condb->prepare($sqlsim);
		if($sqlprep->rowCount()>0)
		{
			$result=$sqlprep->fetchall();
			foreach($result as $res)
			{
				//id-первичный ключ для поиска в таблице. Может принимать нужные значения
				$params[]=array('id'=>$res['account'], 'name'=>$res['account']);
				try 
				{
					$sqlprepsim->bindValue(':account',$res['account']);
					$sqlprepsim->execute();
				}
				catch (PDOExeption $e)
				{
					$sql=$sqlsim;
					include '../form/errorhtml.php';
					exit;
				}
				if($sqlprepsim->rowCount()>0)
				{
					$resultsim=$sqlprepsim->fetchall();
					foreach($resultsim as $ressim)
					{
						$params1[]=array('id'=>$res['account'], 'id_1'=>$ressim['number'], 'name'=>$ressim['number']);
					}
					
				}
			}
		}
		//Титул управляющей страницы в творительном падеже
		$ctrltitle="сим-картами";
		//Название ссылки в родительном падеже
		$ctrladd=createLink("Добавить сим-карту","?add" );
		$btn_off='disabled';
		include $_SERVER['DOCUMENT_ROOT'].'/form/ctrl1html.php';
		if($condb!=null) {$condb=NULL;}
		
		
	}
	else header('Location: ../index.php?link='.$_SERVER['PHP_SELF']);
	exit;
?>