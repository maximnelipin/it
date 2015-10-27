<?php
//Данная функция взята с http://phpfaq.ru/pdo. Все права принадлежат автору
//Если имена форм совпадают с полями таблице, то функция формирует строку для insert/update set
function pdoSet($fields, &$values, $source = array()) {
	$set = '';
	$values = array();
	if (!$source) $source = &$_REQUEST;
	foreach ($fields as $field) {
		if (isset($source[$field])) {
			$set.="`".str_replace("`","``",$field)."`". "=:$field, ";
			$values[$field] = $source[$field];
		}
	}
	return substr($set, 0, -2);
}

//-----------Функция добавления кабинета на этаж
function addCab($id_floor, $Dcab,$condb, $i=null){
	//Если это массив
	if(gettype($Dcab)=='array')
	{
		//Если есть кабинеты для этого этажа
		if(isset($Dcab[$i]))
		{
			//Парнсим элемент массива и вводим дынные в базу
			$Dc=str_getcsv($Dcab[$i], ",");
			
		}
		else exit();
	}
	
	//Если получили строку
	if(gettype($Dcab)=='string')
	{	//Парсим и вводим в базу
		$Dc=str_getcsv($Dcab, ",");		
		
	}
	
	$val=array();
	try{
		//Для каждого кабинета
		foreach ($Dc as $Dcn) {
			$sql='insert into cabinet set id_floor=:id_floor, cabinet=:cabinet';
			//создаём массив допустимых значений
			$val['id_floor']=$id_floor;
			$val['cabinet']=$Dcn;
			$sqlprep=$condb->prepare($sql);
			$sqlprep->execute($val);
		}
	}
	catch (PDOException $e) {
		include '../form/errorhtml.php';
		exit;
			
	}
	
}
//-----------Функция добавления этажа здания
function addFloor($id_build, $Dfloor, $Dcab,$condb){
	$valf=array();
	$i=0;;
	try {
		//Для каждого этажа
		foreach ($Dfloor as $Df) {
			$sql='insert into floor set id_build=:id_build, floor=:floor';
			//создаём массив допустимых значений
		
			$valf['id_build']=$id_build;
			$valf['floor']=$Df;
			//выполняем запрос
			$sqlprep=$condb->prepare($sql);
			$sqlprep->execute($valf);
			//Получаем id введённого этажа
			$id_floor=$condb->lastInsertId();
			
			addCab($id_floor, $Dcab,$condb,$i);
			//В след.итерации берём следующий набор этажей из парсенного массива
			$i++;
		}		
	}
	catch (PDOException $e) {
		include '../form/errorhtml.php';
		exit;			
	}	
}
//----------Функция формирования строки с переносами для таблицы
function strWRet($strs)
{	//Функция принимает только массивы

	//Инициализируем результирующую строку
	$resstr='';
	if(gettype($strs)=='array')
	{
		foreach ($strs as $str)
		{
			$resstr.="<p>".html($str)."</p>";
			
		}
	}
	return $resstr;
	
}
//---------------перевод чисел в названия месяцев
function numToMonth($num){
	
	switch($num)
	{
		case 1:
			$month='Январь';
			break;
		case 2:
			$month='Февраль';
			break;
		case 3:
			$month='Март';
			break;
		case 4:
			$month='Апрель';
			break;
		case 5:
			$month='Май';
			break;
		case 6:
			$month='Июнь';
			break;
		case 7:
			$month='Июль';
			break;
		case 8:
			$month='Август';
			break;
		case 9:
			$month='Сентябрь';
			break;
		case 10:
			$month='Октябрь';
			break;
		case 11:
			$month='Ноябрь';
			break;
		case 12:
			$month='Декабрь';
			break;
		default:
			$month='';
			break;
	}
	
	return $month;
}

function html($text)
{
	return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}

function htmlout($text)
{
	echo html($text);
}

function htmloutinput($text)
{
	echo '"'.html($text).'"';
}
?>