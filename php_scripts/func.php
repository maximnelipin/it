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
function addCab($id_floor, $Dcab){
	$Dc=str_getcsv($Dcab, ",");
	$val=array();
	try{
		//Для каждого кабинета
		foreach ($Dc as $Dcn) {
			$sql='insert into floor set id_floor=:id_floor, cabinet=:cabinet';
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
function addFloor($id_build, $Dfloor, $Dcab){
	$valf=array();
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
			$id_floor=$conn->lastInsertId();
			addCab($id_floor, $Dcab);		
		}		
	}
	catch (PDOException $e) {
		echo 'Не удалось выполнить запрос';
		echo $e->getMessage();
		exit;			
	}	
}
?>