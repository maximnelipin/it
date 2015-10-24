<?php
	//открываем сессию
	session_start();
	//Подключаем файл с параметрами подключения
	if(isset($_SESSION['user_id']))
	{	if(isset($_REQUEST["monyear"]))
		{	
	
		//подключаем файл работы с pdf.
		include 'fpdf.php';
		include $_SERVER['DOCUMENT_ROOT'].'/php_scripts/func.php';
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
			include '../form/errorhtml.php';
			exit;
		}
		
		//Выделяем месяц и год
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
		//Вывод месяца идёт с перекодировкой в cp1251
		$pdf->Cell(80,20,iconv("utf-8","cp1251",numToMonth($monyear[0]).' '.$monyear[1]),1,1,'С',false);
		$pdf->Ln(20);
		//Получаем количество дней в месяце
		$dayInMon=date('t');
		//Счётчик дней
		$dayCount=1;
		//Счётчик недель
		$weekCount=0;
		//Меняем размер шрифта
		$pdf->SetFont('ArialMT','',12);
		//Высота строки
		$hig=20;
		//Ширина поля для фамилии
		$widfam=32;
		//Ширина для числа
		$widnum=8;
		//Обрабатываем первую неделю месяца
		for($i=0;$i<7;$i++)
		{
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
		while($dayCount<=$dayInMon)
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
			//if() break;
		}
		//Выбираем все дни, в которые дежурили в этот месяц
		$sql="select day(dateduty) as daym from schedule where month(dateduty)=:mon AND year(dateduty)=:year order by day(dateduty)";
		$resdaysql=$condb->prepare($sql);
		$resdaysql->bindValue(':mon',$monyear[0]);
		$resdaysql->bindValue(':year',$monyear[1]);
		$resdaysql->execute();
		$sql="select schedule.dateduty as dateduty, itusers.fio as fio from schedule
									right join itusers on itusers.login=schedule.login where
									day(schedule.dateduty)=:day
									 AND	month(schedule.dateduty)=:mon
									 AND year(schedule.dateduty)=:year";
		$resdutysql=$condb->prepare($sql);
		//Выбираем все дни, в которые дежурили в этот месяц
		//$sql="select day(dateduty) as daym from schedule where month(dateduty)=".$monyear[0].
				//" AND year(dateduty)=".$monyear[1]." order by day(dateduty)";
		//$resdaysql=$condb->query($sql);
		//получаем массив с результатами
		$resday=$resdaysql->fetchall();
		//выводим месяц
		for($i=0;$i<count($month);$i++)
		{	
			
			//обрабатываем неделю
			for($j=0;$j<7;$j++)
			{	//Если восвресенье, то после него переходим на новую строку
				if($j==6) $ln=1;
				else $ln=0;
				
				//Если в массиве не пустой элемент
				if(!empty($month[$i][$j]))
				{	$fio=array();//обнуляем массив с ФИО сотрудника
					//Переходим в массиве на первый элемент
					reset($resday);
					//Перебираем все элементы
					foreach ($resday as $resd)
					{	//Сравниваем все дни дежурства в выбранный месяц с выводимыми днями
						if($resd['daym']==$month[$i][$j])
						{//Если совпал делаем выборку фамилии дежурившего
							//$sql="select schedule.dateduty as dateduty, itusers.fio as fio from schedule
							//right join itusers on itusers.login=schedule.login where
							//day(schedule.dateduty)=".$month[$i][$j]."
							// AND	month(schedule.dateduty)=".$monyear[0]."
							// AND year(schedule.dateduty)=".$monyear[1];
							//$ressql=$condb->query($sql);
							$resdutysql->bindValue(':day',$month[$i][$j]);
							$resdutysql->bindValue(':mon',$monyear[0]);
							$resdutysql->bindValue(':year',$monyear[1]);
							$resdutysql->execute();
							if($res=$resdutysql->fetch(PDO::FETCH_ASSOC))
							{
								$fio=str_getcsv($res["fio"], " ");
							}
						//удаляем использованное значение из массива
						unset($resday['daym']);
						//Выходим из цикла
						break;
						}
						
					}					 
						
					//Если суббота или воскресенье
					if($j==5 || $j==6)
					{	
						$color=true;
					}	
					else 
					{	
						$color=false;
					}
					
					//Выводим подцвеченными
					$pdf->Cell($widnum,$hig,$month[$i][$j],1,0,'C',$color);
					//Если есть фамилия дежурившего
					if(isset($fio[0]))
					{	//Выводим её
					$pdf->Cell($widfam,$hig,$fio[0],1,$ln,'C',$color);
					}
					else $pdf->Cell($widfam,$hig," ",1,$ln,'C',$color);
				}
				//Если нет даты на это поле, выводим просто пустое 
				else $pdf->Cell($widfam+$widnum,$hig," ",1,$ln,'C',false);
			}
			
		}
		//Закрываем подключения
		if($condb!=null) {$condb=NULL;}
		//Выводим жокумент в браузер и отображаем его в просмотрщике
		$pdf->Output(numToMonth($monyear[0]).' '.$monyear[1],'I');		
		}
	else header('Location: main.php');
	}
	else header('Location: ../index.php?link='.$_SERVER['PHP_SELF'].'?monyear='.$_REQUEST["monyear"]);
	exit;
?>