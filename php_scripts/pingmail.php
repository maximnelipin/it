<?php
	//Файл с классом отправки почты через соккеты
	include_once $_SERVER['DOCUMENT_ROOT'].'/php_scripts/SendMailSmtpClass.php';
	//Файл с функциями
	include_once $_SERVER['DOCUMENT_ROOT'].'/php_scripts/func.php';
	//Файл подключения к БД
	include_once $_SERVER['DOCUMENT_ROOT'].'/php_scripts/mysql_conf.php';
		
	try 
	{
		//Делаем выборку фдресов шлюзов
		$sql='SELECT conn.gateway, build.name as build FROM conn
							left JOIN cabinet ON conn.id_cabinet = cabinet.id
							left JOIN floor ON cabinet.id_floor = floor.id
							left JOIN build ON floor.id_build = build.id
							ORDER BY conn.gateway LIMIT 20
							';
		$sqlprep=$condb->prepare($sql);		
		$sqlprep->execute();
	}
	catch (PDOException $e)
	{
		include '../form/errorhtml.php';
		exit;
	}
	$ctrltitle="Доступность ЛВС (ПИНГ)";
	//Формируем начало письма со стилями
	$body='<html> <head> <title>'.$ctrltitle.'</title>  <style>
						.ping {
							margin-bottom:10px;
							font-size:105%;
							margin-left:4%;	
							}

						.ping p {
							margin-bottom:3px;	
						}
						.title1 {	
							font-size: 150%;
							color:#FF0000;
							margin-bottom: 1%;
							margin-left:5%;
							width:60%;			
						}
						.m_title1{		
							margin-left:1%;	
						}
						</style></head> <body>';
	//Если есть результаты выборки
	if($sqlprep->rowCount()>0)
	{
		//Увеличиваем время, чтобы получить результат при недоступности точек
		//На каждую ЛВС по 40 секунд
		set_time_limit($sqlprep->rowCount()*40);
		$result=$sqlprep->fetchall();
		foreach ($result as $res)
		{	
			//пингуем
			$respings=ping($res['gateway']);
			//Добавляем к станице пинги
			$body.='<div>
						<h2 class="title1">'. html($res['build']).'</h2>
					</div>	
				<div class="m_title1">'.$respings.'</div>';
		}
	//Заканчиваем формирования текста письма
					
	}
	//Если в бызе пусто
	else 
	{
		$body.='<div>
					<h2 class="title1"> БАЗА ПУСТА</h2>
				</div>';
	}
				
	$body.='</body></html>';
	//Отправка почты через обычные сервера
	$mailSMTP=new SendMailSmtpClass('nelipin.maxim@yandex.ru','pravoverniy', 'ssl://smtp.yandex.ru','MAX',465);
	// заголовок письма
	$headers= "MIME-Version: 1.0\r\n";
	$headers .= "Content-type: text/html; charset=utf-8\r\n"; // кодировка письма
	$headers .= "From: Max <nelipin.maxim@yandex.ru>\r\n"; // от кого письмо
	$result =  $mailSMTP->send('nelmaxim@gmail.com', $ctrltitle, $body, $headers); // отправляем письмо
	// $result =  $mailSMTP->send('Кому письмо', 'Тема письма', 'Текст письма', 'Заголовки письма');
	if($result === true)
	{
		echo "Письмо успешно отправлено";
	}
	else
	{
		echo "Письмо не отправлено. Ошибка: " . $result;
	}
?>