<?php
	//Файл с классом отправки почты через соккеты
	include_once $_SERVER['DOCUMENT_ROOT'].'/php_scripts/ExSendMailSmtpClass.php';
	//Файл с функциями
	include_once $_SERVER['DOCUMENT_ROOT'].'/php_scripts/func.php';
	//Файл подключения к БД
	include_once $_SERVER['DOCUMENT_ROOT'].'/php_scripts/mysql_conf.php';
		
		//Вычитаем половину стоимости оплаты за месяц
		//Вычитание происходит по лицевым счётам
		try {
			$sql='UPDATE sim, (SELECT account, balance, SUM( pay ) AS pay, COUNT( * ) AS count_number	FROM sim GROUP BY account) as sim1 
			SET sim.balance=(sim1.balance-sim1.pay/2) WHERE sim.account=sim1.account';
			$sqlprep=$condb->prepare($sql);
			$sqlprep->execute();
		}
		catch (PDOException $e)
		{
			include '../form/errorhtml.php';
			exit;
		}
		//месяцев до оплаты
		$montopay=2;
		try 
		{
			//Делаем выборку сим -карт, где денег осталось меньше, чем на 2 месяца
			$sql='SELECT sim.number, sim.account, sim.balance, sim.pay, sim.pwdlk, sim.note, isp.name AS isp, isp.urllk, isp.telsup, 
					listuser.fio, build.name AS build, sim1.balance / sim1.pay as mon
			FROM sim
			INNER JOIN (
							
			SELECT account, balance, SUM( pay ) AS pay, COUNT( * ) AS count_number
			FROM sim
			GROUP BY account
			) AS sim1 ON ( sim.account = sim1.account AND sim1.balance / sim1.pay < :montopay )
			LEFT JOIN build ON sim.id_address = build.id
			LEFT JOIN isp ON sim.id_operator = isp.id
			LEFT JOIN listuser ON sim.login = listuser.login
			ORDER BY isp.name
			LIMIT 0 , 30';
			$sqlprep=$condb->prepare($sql);
			$sqlprep->bindValue(':montopay',$montopay );
			$sqlprep->execute();
		}
		catch (PDOException $e)
		{
			include '../form/errorhtml.php';
			exit;
		}
		$ctrltitle="Оплата сим-карт";
				//Формируем начало письма со стилями
		$body='<html> <head> <title>'.$ctrltitle.'</title>  <style>
				table, th, td, caption {
									border-style:solid;
									border-width:1px;
									border-collapse: collapse;
									padding:3px;
									font-size: 100%;
									background-color: azure;	
								}
								table a {
									font-size: 100%;
								}
								
								table {
									margin-left:4%;
									margin-right:2%;
									margin-top:1%;
									margin-bottom:1%;
								}
								caption {
									font-size: 120%
								}
								th{
									font-size:110%;
									
								}
					.title1 {	
							font-size: 150%;
							color:#FF0000;
							margin-bottom: 1%;
							margin-left:5%;
							width:60%;			
						}
						
						</style> </head> <body>';
		//Если есть сим-карты
		if($sqlprep->rowCount()>0)
		{
			//Формируем шапку
			$body.='<table> <caption>Сим-карты</caption>
		  					 <tr>
							<th>Номер</th>
							<th>Л/С</th>
							<th>Объект</th>
							<th>Оператор</th>
							<th>Техподдержка</th>
							<th>Числится за</th>
							<th>Баланс</th>
							<th>Оплата</th>
							<th>До выключения, мес</th>
							<th>Личный кабинет</th>
							<th>Примечание</th>
		   					</tr>';
			$result=$sqlprep->fetchall();
			foreach ($result as $res)
			{			
			//Добавляем к странице сим-карты, которые нужно оплатить
					$body.='<tr><td>'.
								html($res['number']).'</td><td>'.
								html($res['account']).'</td><td>'.
								html($res['build']).'</td><td>'.
								html($res['isp']).'</td><td>'.
								html($res['telsup']).'</td><td>'.
								html($res['fio']).'</td><td>'.
								html($res['balance']).'</td><td>'.
								html($res['pay']).'</td><td>'.
								html($res['mon']).'</td><td>
								<a href='.html($res['urllk']).' target="_blank"> '.html($res['urllk']).'</a></td><td>'.
								html($res['note']).'</td> </tr>';
			}
					
		}
				//Если нет результата выборки
		else {
			$body.='<div>
						<h2 class="title1"> Все сим-карты оплачены более, чем на 2 месяца<h2>
					</div>';
		}
						
		//Заканчиваем формирования текста письма
		$body.='</table></body></html>';
		
	//Отправка почты через exchange	
	$mailSMTP=new SendMailSmtpClass('max','zuneipod23', 'vs-00-ex-final.du.i-net.su','max@develug.ru',25);
	//								логин  пароль		сервер							от кого			порт
	// заголовок письма
	$headers= "MIME-Version: 1.0\r\n";
	$headers .= "Content-type: text/html; charset=utf-8\r\n"; // кодировка письма
	$headers .= "From: Max <max@develug.ru>\r\n"; // от кого письмо
	$result =  $mailSMTP->send('max@develug.ru',  $ctrltitle, $body, $headers); // отправляем письмо
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