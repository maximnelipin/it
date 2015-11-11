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
			$pageTitle='Добавление оборудования';
			$action='addform';
			
			$id_cabinet='';
			$phys='';
			$ip='';
			$rack='';
			$unit='';
			$note='';
			$id='';
			$dis='';
			$button="Добавить";
			include $_SERVER['DOCUMENT_ROOT'].'/form/addequiphtml.php';
			exit;
		}
		//Добавляем Контрагента
		if (isset($_REQUEST['phys']) && isset($_REQUEST['addform']))
		{
			try {
				$fields=array("id_cabinet","ip","phys","rack","unit","note");
				$sql='insert into equip set '.pdoSet($fields,$values);
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
				$sql='SELECT * FROM equip WHERE id=:id';
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
			$pageTitle='Редактирование оборудования';
			$action='editform';
			$id_cabinet=$res['id_cabinet'];
			$ip=$res['ip'];
			$phys=$res['phys'];
			$rack=$res['rack'];
			$unit=$res['unit'];
			$note=$res['note'];
			$id=$res['id'];
			$dis='';
			$button="Обновить";
			include $_SERVER['DOCUMENT_ROOT'].'/form/addequiphtml.php';
			exit;
		
		}
		//Обновление
		if (isset($_REQUEST['editform']))
		{
			try
			{
				$fields=array("id_cabinet","ip","phys","rack","unit","note");
				$sql='update equip set '.pdoSet($fields,$values).' where id=:id';
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
			
			//Удалояем оборудование
			try
			{
				$sql='DELETE FROM equip WHERE id=:id';
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
		//Выбираем все id_cabinet из equip
		try
		{	
			$sql='SELECT DISTINCT id_cabinet FROM equip order by id_cabinet LIMIT 100';
			$sqlprep=$condb->prepare($sql);
			$sqlprep->execute();
		}
		catch (PDOExeption $e)
		{
			include '../form/errorhtml.php';
			exit;
		}
		//Подготовка запроса выборки полного пути к кабинету
		$sqlcab='SELECT build.name as build, floor.id as id_floor, floor.floor as floor, cabinet.id as id_cab, cabinet.cabinet as cabinet FROM build
				RIGHT JOIN floor ON build.id = floor.id_build RIGHT JOIN cabinet ON cabinet.id_floor=floor.id WHERE  cabinet.id=:id_cabinet';		
		$sqlprepcab=$condb->prepare($sqlcab);
		//Запрос выборки оборудования в кабинете
		$sqleq='SELECT DISTINCT id, id_cabinet, phys,ip FROM equip WHERE id_cabinet=:id_cabinet order by  ip,phys ';
		$sqlprepeq=$condb->prepare($sqleq);
		if($sqlprep->rowCount()>0)
		{	$cabs=$sqlprep->fetchall();
			foreach ($cabs as $cab)
			{
				$sqlprepcab->bindValue(':id_cabinet',$cab['id_cabinet']);
				$sqlprepcab->execute();
				if($sqlprepcab->rowCount()>0)
				{
					$result=$sqlprepcab->fetchall();
					//Формирование списка зданийб этажей и кабинетов
					foreach($result as $res)
					{
						$params[]=array('id'=>$res['id_cab'], 'name'=>$res['build'].' '.$res['floor'].' этаж '.$res['cabinet']);
						$sqlprepeq->bindValue(':id_cabinet',$res['id_cab']);
						$sqlprepeq->execute();
						if($sqlprepeq->rowCount()>0)
						{
							$resultf=$sqlprepeq->fetchall();
							foreach($resultf as $resf)
							{	//массив для вложенной группы
								$params1[]=array('id_1'=>$resf['id'], 'name'=>$resf['ip'].' '.$resf['phys'], 'id'=>$resf['id_cabinet']);
						
							}
						}
					}
				}
			}
		}
		//Титул управляющей страницы в творительном падеже
		$ctrltitle="оборудованием";
		//Название ссылки в родительном падеже
		$ctrladd=createLink("Добавить оборудование","?add" );
		$btn_off='disabled';
		include $_SERVER['DOCUMENT_ROOT'].'/form/ctrl1html.php';
		if($condb!=null) {$condb=NULL;}
		
		
	}
	else header('Location: ../index.php?link='.$_SERVER['PHP_SELF']);
	exit;
?>