 <?php
 //открываем сессию
 session_start();
 //Подключаем файл с параметрами подключения
 if(isset($_SESSION['user_id']))
 {    
	    include 'mysql_conf.php';
	    try {
	    	$condb=new PDO('mysql:host='.$hostsql.';dbname='.$dbname, $dbuser, $dbpwd);
	    	$condb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	    	$condb->exec('SET NAMES "utf8"');
	    }
	    catch (PDOException $e)
	    {
	    	include '../form/errorhtml.php';
			exit;
	    }
	    
	    $monyear=str_getcsv($_REQUEST["monyear"], ",");
	    include "../form/repschedhtml.php";
	    //Получаем количество дней в месяце
	    $dayInMon=date('t');
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
	    //Подготавливаем таблицу
	    echo "<table class='duty'> 
	    		<caption>".numToMonth($monyear[0]).' '.$monyear[1]."</caption>";
	    //Выбираем все дни, в которые дежурили в этот месяц
	    $sql="select day(dateduty) as daym from schedule where month(dateduty)=".$monyear[0].
	    " AND year(dateduty)=".$monyear[1]." order by day(dateduty)";
	    $resdaysql=$condb->query($sql);
	    //получаем массив с результатами
	    $resday=$resdaysql->fetchall();
	    //выводим месяц
	    for($i=0;$i<count($month);$i++)
	    {
	    	echo "<tr>";
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
		    			//удаляем использованное значение из массива
		    			unset($resday['daym']);
		    			//Выходим из цикла
		    			break;
		    			}
	    			
	    			}
	    			//Если суббота или воскресенье
	    			if($j==5 || $j==6)
	    			{//Выводим подсвеченным число
	    				 echo "<td class='weekend' width=30px>".$month[$i][$j]."</td>";
	    				//Если есть фамилия дежурившего
	    				if(isset($fio[0]))
							{	//Выводим её
								echo "<td width=110px; class='weekend'>".$fio[0]."</td>";
							}
						else echo "<td width=110px class='weekend'></td>";
	    			}
	    			else 
	    			{
	    				//Выводим подсвеченным число
	    				echo "<td width=30px>".$month[$i][$j]."</td>";
	    				//Если есть фамилия дежурившего
	    				if(isset($fio[0]))
	    				{	//Выводим её
	    				echo "<td width=110px>".$fio[0]."</td>";
	    				}
	    				else echo "<td width=110px></td>";    				
	    			}
	    			
	    		}
	    		else echo "<td width=140px colspan='2'></td>";
	    	}
	    	echo "</tr>";
	    }
	    echo "</table>";
	    
		echo '<div class="field"> 
				<form action=schedpdf.php?monyear='.$_REQUEST["monyear"].' target="_blank" method=get>
				<input type="submit" class="button" size="70" name="schedpdf" value="В PDF">
				<input type="hidden"  name="monyear"  value="'.$_REQUEST["monyear"].'">
				<form>
				</div>';	
			
		if($condb!=null) {$condb=NULL;}
 }		
	else header('Location: ../index.php?link='.$_SERVER['PHP_SELF'].'?monyear='.$_REQUEST["monyear"]);
	exit;

	?>