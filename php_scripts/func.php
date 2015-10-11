<?php
function pdoSet($fields, &$values, $source = array()) {
	$set = '';
	$values = array();
	if (!$source) $source = &$_POST;
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
		echo 'Не удалось выполнить запрос';
		echo $e->getMessage();
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
		echo 'Не удалось выполнить запрос';
		echo $e->getMessage();
		exit;			
	}	
}
?>