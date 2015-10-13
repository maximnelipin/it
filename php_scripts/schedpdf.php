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
		//создаем HEADER
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
		$pdf->SetFillColor(255,18,18);
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
					//Если суббота или воскресенье
					if($j==5 || $j==6)
						$pdf->Cell(40,20,$month[$i][$j]."Бочаров",1,$ln,'L',true);
					else $pdf->Cell(40,20,$month[$i][$j],1,$ln,'L',false);
				}
				else $pdf->Cell(40,20," ",1,$ln,'L',false);
			}
			
		}	
		$pdf->Ln(20);
		$pdf->Output(numToMonth($monyear[0]).' '.$monyear[1],'I');		
		
	}
?>