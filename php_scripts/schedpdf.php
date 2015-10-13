<?php
	//открываем сессию
	session_start();
	//Подключаем файл с параметрами подключения
	if(isset($_SESSION['user_id']))
	{//подключаем файл работы с pdf.
		include 'fpdf.php';
		include $_SERVER['DOCUMENT_ROOT'].'/php_scripts/func.php';
		//include "makefont/makefont.php";
		//MakeFont('makefont/times.ttf','times.afm','cp1251');
		//--------Коннект к базе
		include 'mysql_conf.php';
		try {
			$condb=new PDO('mysql:host='.$hostsql.';dbname='.$dbname, $dbuser, $dbpwd);
			$condb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			//Исходная кодировка базы в utf8, cp1251 подключена для формирования pdf
			$condb->exec('SET NAMES "CP1251"');
		}
		catch (PDOException $e)
		{
			$error= 'Не удалось выполнить запрос'.$e->getMessage();
			$urlerr=$_SERVER['PHP_SELF'];
			//$_SESSION['erroor']=$error;
			//$_SESSION['urlerr']=$urlerr;
			include '../form/errorhtml.php';
			exit;
		}
		
		$monyear=str_getcsv($_REQUEST["monyear"], ",");
		
		
		//Создаём новый объект с параметрами: портретный A4 с делением по милиметрам
		$pdf=new FPDF('L','mm','A4');
		$pdf->AddPage();
		$pdf->AddFont('TimesNewRomanPSMT','','times.php');
		$pdf->AddFont('TimesNewRomanPS-BoldMT','B','timesb.php');
		$pdf->AddFont('ArialMT','','a2c023acb498ea969bcb0e43b4925663_arial.php');
		//Устанавливаем шрифт
		$pdf->SetFont('ArialMT','',16);
		//Заливка выходных дней
		$pdf->SetFillColor(171,255,0);
		//Заголовок
		$pdf->SetTitle(numToMonth($monyear[0]).' '.$monyear[1],true);
		$pdf->Cell(60,20,"Октябрь 2015 ",1,1,'L',false);
		
		$pdf->Ln(20);
		//Получаем количество дней в месяце
		$dayInMon=date('t');
		//Счётчик дней
		$dayCount=1;
		//Счётчик недель
		$weekCount=0;
		$pdf->SetFont('ArialMT','',12);
		
		//Обрабатываем первую неделю месяца
		for($i=0;$i<7;$i++){
			//Получаем номер дня недели
			$dayOfWeek=date('w', mktime(0, 0, 0, $monyear[0]  , $dayCount, $monyear[1]));
			//Переводим американский варинат недели в патриотичный российский
			$dayOfWeek=$dayOfWeek-1;
			if($dayOfWeek==-1) $dayOfWeek=6;
			//Если день недели совпадае с проверяемым днём
			if($dayOfWeek==$i){
				//Заполняем двумерный массив с числами
				$month[$weekCount][$i]=$dayCount;
				$dayCount++;
		
		
			}
			else{
				$month[$weekCount][$i]='';
			}
		}
		
		//Обрабатываем последующие недели месяца
		while(1)
		{
			$weekCount++;
			for($i=0;$i<7;$i++)
			{
				$month[$weekCount][$i]=$dayCount;
				$dayCount++;
				//Если конец месяца - выход из цикла
				if($dayCount>$dayInMon) break;
			}
			//Если конец месяца - выход из цикла
			if($dayCount>$dayInMon) break;
		}
		
		for($i=0;$i<count($month);$i++)
		{	$ln=0;
			for($j=0;$j<7;$j++)
			{
				if($j==6) $ln=1;
				if(!empty($month[$i][$j]))
				{
					$sql="select schedule.dateduty as dateduty, itusers.fio as fio from schedule 
							right join itusers on itusers.login=schedule.login where 
							day(schedule.dateduty)=".$month[$i][$j]." 
							AND	month(schedule.dateduty)=".$monyear[0]."
							AND year(schedule.dateduty)=".$monyear[1];
					$ressql=$condb->query($sql);
					if($res=$ressql->fetch(PDO::FETCH_ASSOC))
					{
						$fio=str_getcsv($res["fio"], " ");
					}
					else $fio=array();
						
					//Если суббота или воскресенье
					if($j==5 || $j==6)
					{
						$pdf->Cell(8,20,$month[$i][$j],1,0,'C',true);
						if(isset($fio[0]))
						{
							$pdf->Cell(32,20,$fio[0],1,$ln,'C',true);
						}
						else $pdf->Cell(32,20," ",1,$ln,'C',true);
						
					}	
					else 
					{	$pdf->Cell(8,20,$month[$i][$j],1,0,'C',false);
						if(isset($fio[0]))
						{
							$pdf->Cell(32,20,$fio[0],1,$ln,'C',false);
						}
						else $pdf->Cell(32,20," ",1,$ln,'C',false);
					}
				}
				else $pdf->Cell(40,20," ",1,$ln,'C',false);
			}
			
		}	
		$pdf->Ln(20);
		$pdf->Output(numToMonth($monyear[0]).' '.$monyear[1],'I');		
		
	}
?>