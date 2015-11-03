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
		$ctrltitle='Сим-карты';
		$ctrls='Сим-карты';
		//Если есть провайдер сим-карт
		if(isset($_GET['ispsim']))
		{	
			//Вывод всех 
			if($_GET['ispsim']=="all")
			{
				$like='%';
			}
			//для одной 
			else
			{
				$like=$_GET['ispsim'];
			}
			try 
			{
				//Формируем запрос на выборку операторов мобильной связи
				$sqlisp='SELECT DISTINCT isp.name from isp RIGHT JOIN sim ON isp.id=sim.id_operator WHERE  id like :id ';
				$sqlprepisp=$condb->prepare($sqlisp);
				$sqlprepisp->bindValue(':id', $like);
				$sqlprepisp->execute();
			}
			catch (PDOExeption $e)
			{
				$sql=$sqlisp;
				include '../form/errorhtml.php';
				exit;
			}
			if($sqlprepisp->rowCount()>0)
			{
				//формируем заголоквки
				$resultisp=$sqlprepisp->fetchall();
				foreach($resultisp as $resisp)
				{
					$ctrltitle.='-'.html($resisp['name']);
					$ctrls.='-'.html($resisp['name']);
				}
			}
			
			//-------------Выбираем сим-карты
			try 
			{
				$sqlsim='SELECT  sim.number, sim.account, sim.balance, sim.pay,sim.pwdlk, sim.note,
							isp.name as isp, isp.urllk, isp.telsup, listuser.fio, build.name as build
							FROM  sim LEFT JOIN build ON sim.id_address=build.id
							LEFT JOIN isp ON sim.id_operator=isp.id
							LEFT JOIN listuser ON sim.login=listuser.login
							WHERE sim.id_operator like :id_operator order by isp, listuser.fio';
				$sqlprepsim=$condb->prepare($sqlsim);
				$sqlprepsim->bindValue(':id_operator', $like);
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
				$result=$sqlprepsim->fetchall();
				//Выводим HTML-отчёт
				if(isset($_GET['simrep']))
				{	//Формируем шапку таблицы
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
				}
				//Формируем PDF-отчёт
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
					//Ширина ячеек
					$widthfio=45;
					$widthnum=40;
					$widthisp=30;
					$widthbuild=60;
					$widthpay=25;
					$widthnote=70;
					$pdf->Cell($widthnum,$hig,iconPDF('Номер'),1,0,'C',false);
					$pdf->Cell($widthisp,$hig,iconPDF('Оператор'),1,0,'C',false);
					$pdf->Cell($widthbuild,$hig,iconPDF('Объект'),1,0,'C',false);
					$pdf->Cell($widthfio,$hig,iconPDF('Числиться за'),1,0,'C',false);
					$pdf->Cell($widthpay,$hig,iconPDF('Оплата'),1,0,'C',false);
					$pdf->Cell($widthnote,$hig,iconPDF('Примечание'),1,1,'C',false);
					$i=0;
					$color=true;
					foreach ($result as $res)						
					{	
						$color=!$color;
						$fio=str_getcsv($res["fio"], " ");
						$note=wordwrap($res['note'],20,"\n",false);
						$pdf->Cell($widthnum,$hig,iconPDF($res['number']),1,0,'C',$color);
						$pdf->Cell($widthisp,$hig,iconPDF($res['isp']),1,0,'C',$color);
						$pdf->Cell($widthbuild,$hig,iconPDF($res['build']),1,0,'C',$color);
						$pdf->Cell($widthfio,$hig,iconPDF($fio[0]),1,0,'C',$color);	
						$pdf->Cell($widthpay,$hig,iconPDF($res['pay']),1,0,'C',$color);
						$pdf->MultiCell($widthnote,$hig,iconPDF($note),1,'C',$color);						
					}
					$pdf->Output($ctrls,'I');
					exit;
				}
			}
			//Если нет сим-карт, то выводим страницу
			else
			{
				include $_SERVER['DOCUMENT_ROOT'].'/form/rep1html.php';
				exit;
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