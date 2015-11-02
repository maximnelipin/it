 <?php
 //открываем сессию
 session_start();
 //Подключаем файл с параметрами подключения
 if(isset($_SESSION['user_id']))
 {    
 		//Файл для работы с pdf.
 		include $_SERVER['DOCUMENT_ROOT'].'/php_scripts/fpdf.php';
 		//Файл с функциями
		include_once $_SERVER['DOCUMENT_ROOT'].'/php_scripts/func.php';
		//Файл подключения к БД
		include_once $_SERVER['DOCUMENT_ROOT'].'/php_scripts/mysql_conf.php';
	    
	    $monyear=str_getcsv($_REQUEST["monyear"], ",");
	    
	    //Формируем заголовок окна 
	    $ctrltitle=numToMonth($monyear[0]).' '.$monyear[1];
	    
	    //Получаем количество дней в месяце
	    $dayInMon=cal_days_in_month(CAL_GREGORIAN, $monyear[0], $monyear[1]);
	    //Счётчик дней
	    $dayCount=1;
	    //Счётчик недель
	    $weekCount=0;	    
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
	    //получаем массив с результатами
	    $resday=$resdaysql->fetchall();
	    //Выводим результат в html-страницу
	    if(isset($_GET['schedrep']))
	    {
	    	$ctrls="Дежурства за ".$ctrltitle;
	    	//Формируем заголовок таблицы с месфцем
	    	$params[]=array('str'=> "<table class='duty'>
	    		<caption>".numToMonth($monyear[0]).' '.$monyear[1]."</caption>");
	    
		    //выводим месяц
		    for($i=0;$i<count($month);$i++)
		    {
		    	$params[]=array('str'=> "<tr>");
		    	for($j=0;$j<7;$j++)
		    	{
		    		if(!empty($month[$i][$j]))
		    		{	$fio=array();//обнуляем массив с ФИО сотрудника
		    			//Переходим в массиве на первый элемент
		    			reset($resday);
		    			//Перебираем все элементы
		    			foreach ($resday as $resd)
		    			{	//Сравниваем все дни дежурства в выбранный месяц с выводимыми днями
			    			if($resd['daym']==$month[$i][$j])
			    			{//Если совпал делаем выборку фамилии дежурившего		    	
			    			
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
		    			{//Выводим подсвеченным число
		    				$params[]=array('str'=>  "<td class='weekend' width=30px>".$month[$i][$j]."</td>");
		    				//Если есть фамилия дежурившего
		    				if(isset($fio[0]))
								{	//Выводим её
									$params[]=array('str'=>  "<td width=110px; class='weekend'>".$fio[0]."</td>");
								}
							else $params[]=array('str'=> "<td width=110px class='weekend'></td>");
		    			}
		    			else 
		    			{
		    				//Выводим подсвеченным число
		    				$params[]=array('str'=>  "<td width=30px>".$month[$i][$j]."</td>");
		    				//Если есть фамилия дежурившего
		    				if(isset($fio[0]))
		    				{	//Выводим её
		    				$params[]=array('str'=>  "<td width=110px>".$fio[0]."</td>");
		    				}
		    				else $params[]=array('str'=>  "<td width=110px></td>");    				
		    			}
		    			
		    		}
		    		else $params[]=array('str'=>  "<td width=140px colspan='2'></td>");
		    	}
		    	//Закрываем строку
		    	$params[]=array('str'=>  "</tr>");
		    }
		    //Закрываем таблицу
		    $params[]=array('str'=>  "</table>");
		    //Выводим кнопку с для формирования PDF
			 $params[]=array('str'=> '<div class="field"> 
					<form action=schedule.php?monyear='.$_REQUEST["monyear"].' target="_blank" method=get>
					<input type="submit" class="button" size="70" name="schedpdf" value="В PDF">
					<input type="hidden"  name="monyear"  value="'.$_REQUEST["monyear"].'">
					<form>
					</div>');	
			 include "../form/rep1html.php";
	    }
	    //Выводим результат в PDF
	    if(isset($_GET['schedpdf']))
	    {
	    	
	    	
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
	    	$pdf->SetTitle($ctrltitle,true);
	    	//Вывод месяца идёт с перекодировкой в cp1251
	    	$pdf->Cell(80,20,iconPDF($ctrltitle),1,1,'С',false);
	    	$pdf->Ln(20);
	    	//Меняем размер шрифта
	    	$pdf->SetFont('ArialMT','',12);
	    	//Высота строки
	    	$hig=20;
	    	//Ширина поля для фамилии
	    	$widfam=32;
	    	//Ширина для числа
	    	$widnum=8;
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
	    				$pdf->Cell($widfam,$hig,iconPDF($fio[0]),1,$ln,'C',$color);
	    			}
	    			else $pdf->Cell($widfam,$hig," ",1,$ln,'C',$color);
	    			}
	    			//Если нет даты на это поле, выводим просто пустое
	    			else $pdf->Cell($widfam+$widnum,$hig," ",1,$ln,'C',false);
	    		}
	    		
	    			
	    	}
	    	//Выводим жокумент в браузер и отображаем его в просмотрщике
	    	$pdf->Output(numToMonth($monyear[0]).' '.$monyear[1],'I');
	    }
	    
	    
	    //Закрываем подключение к базе
		if($condb!=null) {$condb=NULL; exit;}
 }		
	else header('Location: ../index.php?link='.$_SERVER['PHP_SELF'].'?monyear='.$_REQUEST["monyear"]);
	exit;

	?>