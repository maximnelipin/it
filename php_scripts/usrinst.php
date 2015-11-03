<?php
		//Файл с функциями
		include_once $_SERVER['DOCUMENT_ROOT'].'/php_scripts/func.php';
		//Файл подключения к БД
		include_once $_SERVER['DOCUMENT_ROOT'].'/php_scripts/mysql_conf.php';
		try 
		{
		//Делаем выборку инструкций		}
			$sql='SELECT * FROM usrinst ORDER BY name LIMIT 50';
			$sqlprep=$condb->prepare($sql);
			$sqlprep->execute();
			
		}
		catch (PDOExeption $e)
		{
			include '../form/errorhtml.php';
			exit;
		}			
		//Формируем массив
		$params[]=array('str'=>'<div class="field">');
		//Если есть инструкции
		if($sqlprep->rowCount()>0)
		{	$result=$sqlprep->fetchall();
			foreach ($result as $res)
			{
				
				$params[]=array('str'=>'<div class=ainst> '.createLink(html($res['name']),html($res['url']),"_blank").' </div>');
			}
		}
		$params[]=array('str'=>'</div>');
		$ctrltitle="Инструкции для пользователей";
		$ctrls='Инструкции для пользователей';	
		include $_SERVER['DOCUMENT_ROOT'].'/form/rep1html.php';
		exit;
?>