<?php
	session_start();
	
	if(isset($_SESSION['user_id']))
	{	
		//Файл с функциями
		include_once $_SERVER['DOCUMENT_ROOT'].'/php_scripts/func.php';
		//Файл подключения к БД
		include_once $_SERVER['DOCUMENT_ROOT'].'/php_scripts/mysql_conf.php';
		
			//Делаем выборку инструкций
		try
		{
			$sql='SELECT * FROM itinst	ORDER BY name LIMIT 50';
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
			//Формируем шапку 
			$params[]=array('str'=>'<div class="field">');
			foreach ($result as $res)
			{
				//Формируем ссылку на инструкцию
				$params[]=array('str'=>'<div class=ainst>'.createLink(html($res['name']),html($res['url']),"_blank").'</a> </div>');
			}
			$params[]=array('str'=>'</div>');
			$ctrltitle="Инструкции для сотрудников отдела ИТ";
			$ctrls='Инструкции для сотрудников отдела ИТ';
		}
		
		else
		{
			//Не хватает параметров
			$params[]=array('str'=>'');
			$ctrltitle="Инструкции для сотрудников отдела ИТ";
			$ctrls='Нет инструкций для сотрудников отдела ИТ';
		}
		include $_SERVER['DOCUMENT_ROOT'].'/form/rep1html.php';
		exit;
	}
	else header('Location: ../index.php?link='.$_SERVER['PHP_SELF']);
	exit;
?>