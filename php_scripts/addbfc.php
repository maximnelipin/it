<?php	

	
	session_start();
	if(isset($_SESSION['user_id']))
	{	//Файл с функциями
		include_once $_SERVER['DOCUMENT_ROOT'].'/php_scripts/func.php';
		//Файл подключения к БД
		include_once $_SERVER['DOCUMENT_ROOT'].'/php_scripts/mysql_conf.php';
		
		//----------------------ЗДАНИЕ----------------------------
		//Выводим форму на добавление
		if(isset($_REQUEST['add_b']))
		{
			$pageTitle='Добавление здания';
			$action='add_build';
			$name='';
			$address='';
			$id='';
			$req='required';
			$dis='';
			$button="Добавить";
			include $_SERVER['DOCUMENT_ROOT'].'/form/addbuildhtml.php';
			exit;
		}
		//Добавляем 
		if (isset($_REQUEST['add_build']))
		{
			try {
		
				$fields=array("name","address");
				$sql='insert into build set '.pdoSet($fields,$values);
				$sqlprep=$condb->prepare($sql);
				$sqlprep->execute($values);
				//Получаем id введённого здания
				$id_build=$condb->lastInsertId();
			}
		
			catch (PDOException $e)
			{
				include '../form/errorhtml.php';
				exit;
			}
			//Получаем список этажей			
			$Dfloor=str_getcsv($_REQUEST["floor"], ",");
			//Получаем список всех кабинетов на этажах
			$Dcab=str_getcsv($_REQUEST["cabinet"], ";");
			//----------вставка этажей и кабинетов на них--------------			
			addFloor($id_build, $Dfloor, $Dcab,$condb);		
			header('Location: '.$_SERVER['PHP_SELF'].'?add_b');
			exit;
		}
		//Выводим форму на редактирование
		if (isset($_REQUEST['action']) && $_REQUEST['action']=='Редактировать здание')
		{
			try
			{
				$sql='SELECT * FROM build where id=:id';
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
			$pageTitle='Редактирование здания';
			$action='edit_b';
			$name=$res['name'];
			$address=$res['address'];
			$req='';
			$id=$res['id'];
			$dis='readonly';
			$button="Обновить";
			include $_SERVER['DOCUMENT_ROOT'].'/form/addbuildhtml.php';
			exit;
		
		}
		//Обновление 
		if (isset($_REQUEST['edit_b']))
		{
			try
			{
				$fields=array("name","address");
				$sql='update build set '.pdoSet($fields,$values).' where id=:id';
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
		if (isset($_REQUEST['action']) && $_REQUEST['action']=='Удалить здание')
		{
			try
			{
				$sql='DELETE FROM build WHERE id=:id';
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
	//-----------------------------ЭТАЖИ----------------------------
	
		//Выводим форму на добавление
		if(isset($_REQUEST['add_f']))
		{
			$pageTitle='Добавление этажа';
			$action='add_floor';
			$id_build='';
			$floor='';
			$note='';
			$req='required';
			$dis='';
			$id='';
			$cabinet='';
			$button="Добавить";
			include $_SERVER['DOCUMENT_ROOT'].'/form/addfloorhtml.php';
			exit;
		}
		//Добавляем 
		if (isset($_REQUEST['floor']) && isset($_REQUEST['add_floor']))
		{
		
			//Получаем список этажей
			$Dfloor=str_getcsv($_REQUEST["floor"], ",");
			//Получаем список всех кабинетов на этажах
			$Dcab=str_getcsv($_REQUEST["cabinet"], ";");
			//----------вставка этажей и кабинетов на них--------------
			addFloor($_REQUEST["id_build"], $Dfloor, $Dcab, $condb);
		
			header('Location: '.$_SERVER['PHP_SELF'].'?add_f');
			exit;
		}
		//Выводим форму на редактирование
		if (isset($_REQUEST['action']) && $_REQUEST['action']=='Редактировать этаж')
		{
			try
			{
				$sql='SELECT * FROM floor where id=:id';
				$sqlprep=$condb->prepare($sql);
				$sqlprep->bindValue(':id',$_REQUEST['id_1']);
				$sqlprep->execute();
			}
			catch (PDOException $e)
			{
				include '../form/errorhtml.php';
				exit;
			}
		
			$res=$sqlprep->fetch();
			$pageTitle='Редактирование этажа';
			$action='edit_floor';
			$id_build=$res['id_build'];
			$floor=$res['floor'];
			$note=$res['note'];
			$cabinet='';
			$req='';
			$id=$res['id'];
			$dis='readonly';
			$button="Обновить";
			include $_SERVER['DOCUMENT_ROOT'].'/form/addfloorhtml.php';
			exit;
		
		}
		//Обновление 
		if (isset($_REQUEST['edit_floor']))
		{
			try
			{
				$fields=array("id_build","floor","note");
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
		if (isset($_REQUEST['action']) && $_REQUEST['action']=='Удалить этаж')
		{
			try
			{
				$sql='DELETE FROM floor WHERE id=:id';
				$sqlprep=$condb->prepare($sql);
				$sqlprep->bindValue(':id',$_REQUEST['id_1']);
				$sqlprep->execute();
			}
			catch (PDOException $e)
			{
				include '../form/errorhtml.php';
				exit;
			}
		
		}
		//-------------------КАБИНЕТЫ-------------------------------
		//Выводим форму на добавление
		if(isset($_REQUEST['add_c']))
		{
			$pageTitle='Добавление кабинета';
			$action='add_cab';
			$id_floor='';
			$cabinet='';
			$note='';
			$dis='';
			$id='';
			$cabinet='';
			$button="Добавить";
			include $_SERVER['DOCUMENT_ROOT'].'/form/addcabhtml.php';
			exit;
		}
		//Добавляем
		if (isset($_REQUEST['cabinet']) && isset($_REQUEST['add_cab']))
		{
			
			addCab($_REQUEST['id_floor'], $_REQUEST['cabinet'], $condb);
			
			header('Location: '.$_SERVER['PHP_SELF'].'?add_c');
			exit;
		}
		//Выводим форму на редактирование
		if (isset($_REQUEST['action']) && $_REQUEST['action']=='Редактировать кабинет')
		{
			try
			{
				$sql='SELECT * FROM cabinet where id=:id';
				$sqlprep=$condb->prepare($sql);
				$sqlprep->bindValue(':id',$_REQUEST['id_2']);
				$sqlprep->execute();
			}
			catch (PDOException $e)
			{
				include '../form/errorhtml.php';
				exit;
			}
		
			$res=$sqlprep->fetch();
			$pageTitle='Редактирование кабинета';
			$action='edit_f';
			$id_floor=$res['id_floor'];
			$cabinet=$res['cabinet'];
			$note=$res['note'];
			$id=$res['id'];
			$dis='readonly';
			$button="Обновить";
			include $_SERVER['DOCUMENT_ROOT'].'/form/addcabhtml.php';
			exit;
		
		}
		//Обновление
		if (isset($_REQUEST['edit_f']))
		{
			try
			{
				$fields=array("id_floor","cabinet","note");
				$sql='update cabinet set '.pdoSet($fields,$values).' where id=:id';
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
		if (isset($_REQUEST['action']) && $_REQUEST['action']=='Удалить кабинет')
		{
			try
			{
				$sql='DELETE FROM cabinet WHERE id=:id';
				$sqlprep=$condb->prepare($sql);
				$sqlprep->bindValue(':id',$_REQUEST['id_2']);
				$sqlprep->execute();
			}
			catch (PDOException $e)
			{
				include '../form/errorhtml.php';
				exit;
			}
		
		}
		//Выборка списка зданий
		try
		{
			$sql='SELECT id, name FROM build order by name LIMIT 50';
			$sqlprep=$condb->prepare($sql);
			$sqlprep->execute();
			
		}
		catch (PDOExeption $e)
		{
			include '../form/errorhtml.php';
			exit;
		}
		//Подготовка запросов
		$sqlf='SELECT id, floor, id_build FROM floor WHERE id_build=:id_build order by floor';
		$sqlprepf=$condb->prepare($sqlf);
		$sqlc='SELECT id, cabinet, id_floor FROM cabinet WHERE id_floor=:id_floor order by cabinet';
		$sqlprepc=$condb->prepare($sqlc);
		if($sqlprep->rowCount()>0)
		{
			//Формирование списка зданийб этажей и кабинетов
			$result=$sqlprep->fetchall();
			foreach($result as $res)
			{
				$params[]=array('id'=>$res['id'], 'name'=>$res['name']);
				
				try 
				{
					$sqlprepf->bindValue(':id_build',$res['id']);
					$sqlprepf->execute();
				}
				catch (PDOExeption $e)
				{	
					$sql=$sqlf;
					include '../form/errorhtml.php';
					exit;
				}
				if($sqlprepf->rowCount()>0)
				{
					$resultf=$sqlprepf->fetchall();
					foreach($resultf as $resf)
					{	//Создаём массив с этажами
						$params1[]=array('id_1'=>$resf['id'], 'name'=>$resf['floor'].' этаж', 'id'=>$resf['id_build']);
						try
						{
							$sqlprepc->bindValue(':id_floor',$resf['id']);
							$sqlprepc->execute();
						}
						catch (PDOExeption $e)
						{
							$sql=$sqlc;
							include '../form/errorhtml.php';
							exit;
						}
						if($sqlprepc->rowCount()>0)
						{
							$resultc=$sqlprepc->fetchall();
							foreach ($resultc as $resc)
							{
								$params2[]=array('id_2'=>$resc['id'], 'name'=>$resc['cabinet'], 'id_1'=>$resc['id_floor']);
							}
						}					
					}
				}
			}
		}
		
		//Титул управляющей страницы в творительном падеже
		$ctrltitle="зданиями";
		//Cсылки на добавление информации
		$ctrladd=createLink("Добавить кабинет","?add_c" ).' '.createLink("Добавить этаж","?add_f" ).' '.createLink("Добавить здание","?add_b" );
		//' <a href="?add_c">Добавить кабинет</a>   	<a href="?add_f">Добавить этаж</a>	    	<a href="?add_b">Добавить здание</a>';
		//Добавочные значение к кнопкам
		$btn=' здание';
		$btn_1=' этаж';
		$btn_2=' кабинет';
		
		include $_SERVER['DOCUMENT_ROOT'].'/form/ctrl1html.php';
		if($condb!=null) {$condb=NULL;}
	}
	else header('Location: ../index.php?link='.$_SERVER['PHP_SELF']);
	exit;
?>