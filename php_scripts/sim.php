<?php
	session_start();
	
	if(isset($_SESSION['user_id']))
	{	
		//Файл для работы с pdf.
 		include $_SERVER['DOCUMENT_ROOT'].'/php_scripts/fpdf.php';
 		//Файл с функциями
		include_once $_SERVER['DOCUMENT_ROOT'].'/php_scripts/func.php';
		//Файл подключения к БД
		include_once $_SERVER['DOCUMENT_ROOT'].'/php_scripts/mysql_conf.php';
		
		//Выводим форму на добавление
		if(isset($_GET['ispsim']))
		{	$ctrltitle='Сим-карты';
			$ctrls='Сим-карты';
			
		
			
			//Вывод всех точек
			if($_GET['ispsim']=="all")
			{
				$like='%';
				
			}
			//для одной точки
			else
			{
				$like=$_GET['ispsim'];
				
			}
			//Формируем запрос на выборку имеён провайдеров для формирования заголовка
			$sqlisp='SELECT DISTINCT isp.name from isp RIGHT JOIN sim ON isp.id=sim.id_operator WHERE  id like :id ';
			$sqlprepisp=$condb->prepare($sqlisp);
			$sqlprepisp->bindValue(':id', $like);
			$sqlprepisp->execute();
			//формируем заголоквки
			$resultisp=$sqlprepisp->fetchall();
			foreach($resultisp as $resisp)
			{
				$ctrltitle.='-'.html($resisp['name']);
				$ctrls.='-'.html($resisp['name']);
			}
			
			//-------------Формируем сим-карты
			$sql='SELECT  sim.number, sim.account, sim.balance, sim.pay,sim.pwdlk, sim.note,
						isp.name as isp, isp.urllk, isp.telsup, listuser.fio, build.name as build
						FROM  sim LEFT JOIN build ON sim.id_address=build.id
						LEFT JOIN isp ON sim.id_operator=isp.id
						LEFT JOIN listuser ON sim.login=listuser.login
						WHERE sim.id_operator like :id_operator order by isp, listuser.fio';
			$sqlprep=$condb->prepare($sql);
			$sqlprep->bindValue(':id_operator', $like);
			$sqlprep->execute();
			
			if(isset($_GET['simrep']))
			{
				
				
				//Если нет сим карт, то и не формируем таблицу
				if($sqlprep->rowCount()>0)
				{
					$result=$sqlprep->fetchall();
					$params[]=array('str'=>'<table>
		   					<caption>Сим-карты</caption>
		  					 <tr>
							<th>Номер</th>
							<th>Л/С</th>
							<th>Объект</th>
							<th>Оператор</th>
							<th>Техподдержка</th>
							<th>Числится за</th>
							<th>Баланс</th>
							<th>Оплата</th>
							<th>Личный кабинет</th>
							<th>Пароль личного кабинета</th>
							<th>Примечание</th>
		   					</tr>');
					foreach ($result as $res)
					{
						//Формирем строки таблицы
						$params[]=array('str'=>'<tr><td>'.
								html($res['number']).'</td><td>'.
								html($res['account']).'</td><td>'.
								html($res['build']).'</td><td>'.
								html($res['isp']).'</td><td>'.
								html($res['telsup']).'</td><td>'.
								html($res['fio']).'</td><td>'.
								html($res['balance']).'</td><td>'.
								html($res['pay']).'</td><td>
								<a href='.html($res['urllk']).' target="_blank"> '.html($res['urllk']).'</a></td><td>'.
								html($res['pwdlk']).'</td><td>'.
								html($res['note']).'</td> </tr>');
						
					}
						
					$params[]=array('str'=>'</table>');
					//Формируем заголовки
					
							
				}
			
			}
			if (isset($_GET['simpdf']))
			{
				$pdf=new FPDF('L','mm','A4');
				$pdf->AddPage();
				$pdf->AddFont('TimesNewRomanPSMT','','times.php');
				$pdf->AddFont('TimesNewRomanPS-BoldMT','B','timesb.php');
				$pdf->AddFont('ArialMT','','a2c023acb498ea969bcb0e43b4925663_arial.php');
				//Устанавливаем шрифт
				$pdf->SetFont('ArialMT','',14);
				//Заливка выходных дней
				$pdf->SetFillColor(171,255,0);
				//Заголовок
				$pdf->SetTitle($ctrltitle,true);
				//Вывод месяца идёт с перекодировкой в cp1251
				$pdf->Cell(100,20,iconv("utf-8","cp1251",$ctrltitle),1,1,'С',false);
				$pdf->Ln(20);
				//Меняем размер шрифта
				$pdf->SetFont('ArialMT','',12);
				//Высота строки
				$hig=7;
				//Ширина ячейки
				$width=45;
				$pdf->Cell(40,$hig,iconPDF('Номер'),1,0,'C',false);
				$pdf->Cell(30,$hig,iconPDF('Оператор'),1,0,'C',false);
				$pdf->Cell(60,$hig,iconPDF('Объект'),1,0,'C',false);
				$pdf->Cell($width,$hig,iconPDF('Числиться за'),1,0,'C',false);
				$pdf->Cell(25,$hig,iconPDF('Оплата'),1,0,'C',false);
				$pdf->Cell(70,$hig,iconPDF('Примечание'),1,1,'C',false);
				if($sqlprep->rowCount()>0)
				{
					$result=$sqlprep->fetchall();
					$i=0;
					$color=true;
					foreach ($result as $res)
					
					{	$color=!$color;
						$fio=str_getcsv($res["fio"], " ");
						$note=wordwrap($res['note'],20,"\n",false);
						$pdf->Cell(40,$hig,iconPDF($res['number']),1,0,'C',$color);
						
						$pdf->Cell(30,$hig,iconPDF($res['isp']),1,0,'C',$color);
						
						$pdf->Cell(60,$hig,iconPDF($res['build']),1,0,'C',$color);
						
						$pdf->Cell(45,$hig,iconPDF($fio[0]),1,0,'C',$color);						
						
						$pdf->Cell(25,$hig,iconPDF($res['pay']),1,0,'C',$color);
						
						$pdf->MultiCell(70,$hig,iconPDF($note),1,'C',$color);
					
					}
					$pdf->Output($ctrls,'I');
					exit;
				}
			}
		}	
		else 
		{ //Если перешли на страницу без парметров, то открываем главную
			header('Location: main.php');
			exit;
		}
			
		include $_SERVER['DOCUMENT_ROOT'].'/form/rep1html.php';
		exit;
		
		
		
		
	}
	else header('Location: ../index.php?link='.$_SERVER['PHP_SELF']);
	exit;
?>