<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="../stylesheet/reset.css">
<link rel="stylesheet" type="text/css" href="../stylesheet/general.css">
<link rel="stylesheet" type="text/css" href="../stylesheet/report.css">
<title>Дежурства за 
	<?php 
	include $_SERVER['DOCUMENT_ROOT'].'/php_scripts/func.php';
	$monyear=str_getcsv($_REQUEST["monyear"], ",");
	echo numToMonth($monyear[0]).' '.$monyear[1];
	?>



</title>
</head>


    <body>
    <h2 class="title"> Отчёт за <?php echo numToMonth($monyear[0]).' '.$monyear[1];?></h2>
    <?php
    //Получаем количество дней в месяце
    $dayInMon=date('t');
    //Счётчик дней
    $dayCount=1;
    //Счётчик недель
    $weekCount=0;
    $monyear=str_getcsv($_REQUEST["monyear"], ",");
    
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
    echo "<table class='duty'>";
    for($i=0;$i<count($month);$i++)
    {
    	echo "<tr>";
    	for($j=0;$j<7;$j++)
    	{
    		if(!empty($month[$i][$j]))
    		{
    			//Если суббота или воскресенье
    			if($j==5 || $j==6)
    				echo "<td class='weekend'>".$month[$i][$j]."</td>";
    			else echo "<td>".$month[$i][$j]."</td>";
    		}
    		else echo "<td>&nbsp</td>";
    	}
    	echo "</tr>";
    }
    echo"</table>";

	?>
    </body>
    
</html>