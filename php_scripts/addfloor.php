<?php	
	session_start();
	if(isset($_SESSION['user_id']))
	{	include 'mysql_conf.php';
		include $_SERVER['DOCUMENT_ROOT'].'/php_scripts/func.php';
		try {
			$condb =new PDO('mysql:host='.$hostsql.';dbname='.$dbname, $dbuser, $dbpwd);
			$condb ->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$condb ->exec('SET NAMES "utf8"');
		}
		catch (PDOException $e)
		{
			include '../form/errorhtml.php';
			exit;
		}
		
		//Выводим форму на добавление
		if(isset($_REQUEST['add']))
		{
			$pageTitle='Добавление этажа';
			$action='addform';
			$id_build='';
			$floor='';
			$note='';
			$dis='';
			$id='';
			$cabinet='';
			$button="Добавить";
			include $_SERVER['DOCUMENT_ROOT'].'/form/addfloorhtml.php';
			exit;
		}
		//Добавляем Контрагента
		if (isset($_REQUEST['name']) && isset($_REQUEST['addform']))
		{				
		
			//Получаем список этажей
			$Dfloor=str_getcsv($_POST["floor"], ",");
			//Получаем список всех кабинетов на этажах
			$Dcab=str_getcsv($_POST["cabinet"], ";");			
			//----------вставка этажей и кабинетов на них--------------
			addFloor($_POST["id_build"], $Dfloor, $Dcab, $condb);
		
			header('Location: '.$_SERVER['PHP_SELF']);
			exit;
		}
		//Выводим форму на редактирование
		if (isset($_REQUEST['action']) && $_REQUEST['action']=='Редактировать')
		{
			try
			{
				$sql='SELECT * FROM floor where id=:id';
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
			$pageTitle='Редактирование этажа';
			$action='editform';
			$id_build=$res['id_build'];
			$floor=$res['floor'];
			$note=$res['note'];
			$cabinet='';
			$id=$res['id'];
			$dis='readonly';
			$button="Обновить";
			include $_SERVER['DOCUMENT_ROOT'].'/form/addfloorhtml.php';
			exit;
		
		}
		//Обновление контрагента
		if (isset($_REQUEST['editform']))
		{
			try
			{
				$fields=array("id_build","floor");
				$sql='update floor set '.pdoSet($fields,$values).' where id=:id';
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
				$sql='DELETE FROM floor WHERE id=:id';
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
			$result=$condb->query('SELECT id, name FROM build order by name');
		}
		catch (PDOExeption $e)
		{
			include '../form/errorhtml.php';
			exit;
		}
		
			$sqlf='SELECT id, floor, id_build FROM floor WHERE id_build=:id_build';
			$sqlprepf=$condb->prepare($sqlf);
		
		foreach($result as $res)
		{
			$params[]=array('id'=>$res['id'], 'name'=>$res['name']);
			
			$sqlprepf->bindValue(':id_build',$res['id']);
			$sqlprepf->execute();
			$resultf=$sqlprepf->fetchall();
			foreach($resultf as $resf) 
			{	//массив для вложенной группы
				$paramsf[]=array('id'=>$resf['id'], 'name'=>$resf['floor'], 'id_build'=>$resf['id_build']);
			}
			
		}
		//Титул управляющей страницы в творительном падеже
		$ctrltitle="этажами";
		//Название ссылки в родительном падеже
		$ctrladd=" этаж";
		
		include $_SERVER['DOCUMENT_ROOT'].'/form/ctrlfloorhtml.php';
		
		//include $_SERVER['DOCUMENT_ROOT'].'/form/addbuildhtml.php';
		if($condb!=null) {$condb=NULL;}
		
		
		
			
	}
	else header('Location: ../index.php?link='.$_SERVER['PHP_SELF']);
	exit;
?>