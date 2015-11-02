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
//---------Функция получения списка Сотрудников отдела ИТ
function getItusers($condb)
{	
	try {
	$sql='SELECT login, fio FROM itusers ORDER BY fio LIMIT 20';
	$sqlprep=$condb->prepare($sql);
	$sqlprep->execute();
	}
	catch (PDOException $e)
	{
		include '../form/errorhtml.php';
		exit;
	}
	if($sqlprep->rowCount()>0)
	{
		$result=$sqlprep->fetchall();
		foreach ($result as $res)
		{
			$itusers[]=array('login'=>$res['login'], 'fio'=>$res['fio']);
		}
	}
	else $itusers='';
	return $itusers;
}
//---------Функция получения списка Пользователей
function getUsers($condb)
{
	try {
		$sql='SELECT login, fio FROM listuser ORDER BY fio LIMIT 400';
		$sqlprep=$condb->prepare($sql);
		$sqlprep->execute();
	}
	catch (PDOException $e)
	{
		include '../form/errorhtml.php';
		exit;
	}
	if($sqlprep->rowCount()>0)
	{
		$result=$sqlprep->fetchall();
		foreach ($result as $res)
		{
			$users[]=array('login'=>$res['login'], 'fio'=>$res['fio']);
		}
	}
	else $users='';
	return $users;
}
//---------Функция получения списка Объектов
function getBuilds($condb)
{
	try {
		$sql='SELECT name,id FROM build ORDER BY name LIMIT 50';
		$sqlprep=$condb->prepare($sql);
		$sqlprep->execute();
	}
	catch (PDOException $e)
	{
		include '../form/errorhtml.php';
		exit;
	}
	if($sqlprep->rowCount()>0)
	{
		$result=$sqlprep->fetchall();
		foreach ($result as $res)
		{
			$builds[]=array('id'=>$res['id'], 'name'=>$res['name']);
		}
	}
	else $builds='';
	return $builds;
}
//---------Функция получения списка этажей
function getFloors($condb)
{

	try {
		$sql='SELECT floor,id,id_build FROM floor ORDER BY floor LIMIT 70';
		$sqlprep=$condb->prepare($sql);
		$sqlprep->execute();
	}
	catch (PDOException $e)
	{
		include '../form/errorhtml.php';
		exit;
	}
	if($sqlprep->rowCount()>0)
	{
		$result=$sqlprep->fetchall();
		foreach ($result as $res)
		{
			$floors[]=array('id'=>$res['id'], 'floor'=>$res['floor'], 'id_build'=>$res['id_build']);
		}
	}
	else $floors='';
	return $floors;
}
//---------Функция получения списка Кабинетов
function getCabs($condb)
{

	try {
		$sql='SELECT cabinet,id,id_floor FROM cabinet ORDER BY cabinet LIMIT 200';
		$sqlprep=$condb->prepare($sql);
		$sqlprep->execute();
	}
	catch (PDOException $e)
	{
		include '../form/errorhtml.php';
		exit;
	}
	if($sqlprep->rowCount()>0)
	{
		$result=$sqlprep->fetchall();
		foreach ($result as $res)
		{
			$cabinets[]=array('id'=>$res['id'], 'cabinet'=>$res['cabinet'], 'id_floor'=>$res['id_floor']);
		}
	}
	else $cabinets='';
	return $cabinets;
}
//---------Функция получения списка Провайдеров
function getIsps($condb)
{
	try {
		$sql='SELECT name,id FROM isp ORDER BY name LIMIT 50';
		$sqlprep=$condb->prepare($sql);
		$sqlprep->execute();
	}
	catch (PDOException $e)
	{
		include '../form/errorhtml.php';
		exit;
	}
	if($sqlprep->rowCount()>0)
	{
		$result=$sqlprep->fetchall();
		foreach ($result as $res)
		{
			$isps[]=array('id'=>$res['id'], 'name'=>$res['name']);
		}
	}
	else $isps='';
	return $isps;
}
//---------Функция получения списка Моделей принтеров
function getModelprints($condb)
{
	try {
		$sql='SELECT name,id FROM sprinters ORDER BY name LIMIT 50';
		$sqlprep=$condb->prepare($sql);
		$sqlprep->execute();
	}
	catch (PDOException $e)
	{
		include '../form/errorhtml.php';
		exit;
	}
	if($sqlprep->rowCount()>0)
	{
		$result=$sqlprep->fetchall();
		foreach ($result as $res)
		{
			$modelprints[]=array('id'=>$res['id'], 'name'=>$res['name']);
		}
	}
	else $modelprints='';
	return $modelprints;
}

//----------Функция выборки принтеров по кабинетам
function printerInCab($id_cabinet,$condb)
{	
	
	$sql='SELECT printers.netpath, sprinters.name, sprinters.cart, sprinters.drivers 
			FROM printers LEFT JOIN sprinters ON printers.id_printer=sprinters.id 
			WHERE printers.id_cabinet=:id_cabinet';
	$sqlprep=$condb->prepare($sql);
	$sqlprep->bindValue('id_cabinet',$id_cabinet);
	$sqlprep->execute();
	//Если есть строки с таким условием
	if ($sqlprep->rowCount()>0)
	{	//Получаем массив значений
		$result=$sqlprep->fetchall();
		//Формируем массив с таблицей 
		$res[]=array('str'=> '<table>
		   					<caption>Принтеры</caption>
		  					 <tr>
							<th>Сетевой адрес</th>
							<th>Модель</th>
		    				<th>Картриджи</th>
							<th>Драйвера</th>
		   					</tr>');
		//Выводим принтеры
		foreach ($result as $printer)
		{
			$res[]=array('str'=> '<tr><td>'.html($printer['netpath']).'</td><td>'.
					html($printer['name']).'</td><td>'.
					html($printer['cart']).'</td><td>'.
					html($printer['drivers']).'</td></tr>');
		}
		$res[]=array('str'=>'</table>');
		
		
		
	}
	else 
	{
		//иначе возвращаем пустую строку		
		$res='';
	}
	return $res;

}
//----------Функция выборки серверов по кабинетам
function serverInCab($id_cabinet,$condb)
{

	$sql='SELECT servers.name, servers.type, servers.descrip, itusers.fio, equip.phys, equip.ip, equip.rack, equip.unit, equip.note
			FROM equip
			LEFT JOIN eqsrv ON equip.id = eqsrv.id_equip
			LEFT JOIN servers ON eqsrv.id_srv = servers.id
			LEFT JOIN itusers ON servers.login = itusers.login
			WHERE equip.id_cabinet =:id_cabinet order by servers.name, equip.rack, equip.unit ';
	$sqlprep=$condb->prepare($sql);
	$sqlprep->bindValue('id_cabinet',$id_cabinet);
	$sqlprep->execute();
	//Если есть строки с таким условием
	if ($sqlprep->rowCount()>0)
	{	//Получаем массив значений
		$result=$sqlprep->fetchall();
		//Формируем массив с таблицей 
		$res[]=array('str'=> '<table>
		   					<caption>Сервера</caption>
		  					 <tr>
							<th>Сетевое имя</th>
							<th>Тип</th>
		    				<th>Описание</th>
							<th>Оборудование</th>
							<th>IP-адрес оборудования</th>
							<th>Стойка</th>
							<th>Юнит</th>
							<th>Примечание</th>
		   					</tr>');
		//Выводим принтеры
		foreach ($result as $server)
		{
			$res[]=array('str'=> '<tr><td>'.html($server['name']).'</td><td>'.
					html($server['type']).'</td><td>'.
					html($server['descrip']).'</td><td>'.
					html($server['phys']).'</td><td>'.
					html($server['ip']).'</td><td>'.
					html($server['rack']).'</td><td>'.
					html($server['unit']).'</td><td>'.
					html($server['note']).'</td></tr>');
		}
		$res[]=array('str'=>'</table>');



	}
	else
	{
		//иначе возвращаем пустую строку
		$res='';
	}
	return $res;

}

//----------Функция выборки подключений и провайдеров по кабинетам
function connInCab($id_cabinet,$condb)
{

	$sql='SELECT conn.gateway, conn.typecon, conn.mask, conn.dhcp, conn.dns1, conn.dns2, conn.loginlk, conn.pwdlk, 
			conn.contract, ppp.typeppp, ppp.srv AS srvppp, ppp.login AS loginppp, ppp.pwd AS pwdppp, 
			extnet.extip,extnet.extmask, extnet.extgw, extnet.extdns1, extnet.extdns2, 
			company.name AS namecomp, company.innkpp, isp.name AS nameisp, isp.id as idisp, conn.note
			FROM conn
			LEFT JOIN isp ON conn.id_operator = isp.id
			LEFT JOIN ppp ON conn.id_ppp = ppp.id
			LEFT JOIN extnet ON conn.id_extnet = extnet.id
			LEFT JOIN company ON conn.id_company = company.id
			WHERE conn.id_cabinet =:id_cabinet
			ORDER BY conn.gateway';
	$sqlprep=$condb->prepare($sql);
	$sqlprep->bindValue('id_cabinet',$id_cabinet);
	$sqlprep->execute();
	$sqlisp='SELECT * FROM isp WHERE id=:id';
	$sqlprepisp=$condb->prepare($sqlisp);
	//Если есть строки с таким условием
	if ($sqlprep->rowCount()>0)
	{	//Получаем массив значений
		$result=$sqlprep->fetchall();
		//Формируем массив с таблицей
		$res[]=array('str'=> '<table>
		   					<caption>Подключения</caption>
		  					 <tr>
							<th>Шлюз</th>
							<th>Маска</th>
		    				<th>DHCP</th>
							<th>DNS1</th>
							<th>DNS2</th>
							<th>Провайдер</th>
							<th>Компания</th>
							<th>ИНН/КПП</th>
							<th>Договор</th>
							<th>Тип подключения</th>
							<th>Внешний IP</th>
							<th>Внешняя маска</th>
							<th>Внешний шлюз</th>
							<th>Внешний DNS1</th>
							<th>Внешний DNS2</th>
							<th>Тип PPP</th>
							<th>Сервер PPP</th>
							<th>Логин PPP</th>
							<th>Пароль PPP</th>
							<th>Логин ЛК</th>
							<th>Пароль ЛК</th>
							<th>Примечание</th>
		   					</tr>');
		
		$resp='<table>
		   					<caption>Провайдер</caption>
		  					 <tr>
							<th>Наименование</th>
							<th>Поддержка</th>
		    				<th>Менеджер</th>
							<th>Телефон менеджера</th>
							<th>Почта менеджера</th>
							<th>Офис</th>
							<th>Личный кабинет</th>
							<th>Папка с документами</th>
							<th>Примечание</th>';
		//Выводим 
		foreach ($result as $conn)
		{
			$res[]=array('str'=> '<tr><td>'.
					html($conn['gateway']).'</td><td>'.
					html($conn['mask']).'</td><td>'.
					html($conn['dhcp']).'</td><td>'.
					html($conn['dns1']).'</td><td>'.
					html($conn['dns2']).'</td><td>'.
					html($conn['nameisp']).'</td><td>'.
					html($conn['namecomp']).'</td><td>'.
					html($conn['innkpp']).'</td><td>'.
					html($conn['contract']).'</td><td>'.
					html($conn['typecon']).'</td><td>'.
					html($conn['extip']).'</td><td>'.
					html($conn['extmask']).'</td><td>'.
					html($conn['extgw']).'</td><td>'.
					html($conn['extdns1']).'</td><td>'.
					html($conn['extdns2']).'</td><td>'.
					html($conn['typeppp']).'</td><td>'.
					html($conn['srvppp']).'</td><td>'.
					html($conn['loginppp']).'</td><td>'.
					html($conn['pwdppp']).'</td><td>'.
					html($conn['loginlk']).'</td><td>'.
					html($conn['pwdlk']).'</td><td>'.
					html($conn['note']).'</td></tr>');
			$sqlprepisp->bindValue('id',$conn['idisp']);
			$sqlprepisp->execute();
			$isp=$sqlprepisp->fetch();
			$resp.='<tr><td>'.
					html($isp['name']).'</td><td>'.
					html($isp['telsup']).'</td><td>'.
					html($isp['manager']).'</td><td>'.
					html($isp['telman']).'</td><td>'.
					html($isp['emailman']).'</td><td>'.
					html($isp['address']).'</td><td>'.
					html($isp['urllk']).'</td><td>'.
					html($isp['netpath']).'</td><td>'.			
					html($conn['note']).'</td></tr>';
		}
		$res[]=array('str'=>'</table> ');
		$resp.='</table>';
		$res[]=array('str'=>$resp);



	}
	else
	{
		//иначе возвращаем пустую строку
		$res='';
	}
	return $res;

}
//---------добавление внешних параметров подключения
function addExtip($condb)
{
	try {
		//Добавляем его в таблицу
		$fieldsextnet=array('extip', 'extmask', 'extgw', 'extdns1', 'extdns2');
		$sql='insert into extnet set '.pdoSet($fieldsextnet,$valuesextnet);
		$sqlprep=$condb->prepare($sql);
		$sqlprep->execute($valuesextnet);
		//И получаем его id
		$_REQUEST['id_extnet']=$condb->lastInsertId();
			
			
	}
	catch (PDOException $e)
	{
		include '../form/errorhtml.php';
		exit;
	}
	
}
//---------добавление параметров PPP
function addPPP($condb)
{
	try {
			$fieldsppp=array('srv', 'login', 'pwd', 'typeppp');
			$sql='insert into ppp set '.pdoSet($fieldsppp,$valuesppp);
			echo $sql;
			$sqlprep=$condb->prepare($sql);
			$sqlprep->execute($valuesppp);
			$_REQUEST['id_ppp']=$condb->lastInsertId();
		}
		catch (PDOException $e)
		{
			include '../form/errorhtml.php';
			exit;
		}

}
//---------добавление Компании
function addCompany($condb)
{
	try 
	{
		$fieldscomp=array('name', 'innkpp');						
		$sql='insert into company set '.pdoSet($fieldscomp,$valuescomp);						
		$sqlprep=$condb->prepare($sql);						
		$sqlprep->execute($valuescomp);						
		$_REQUEST['id_company']=$condb->lastInsertId();		
	}
	catch (PDOException $e)					
	{						
		include '../form/errorhtml.php';						
		exit;					
	}

}
//--------Функция пинга-----------------
function ping ($pinghost){
	$result=array();
	//Оперделяем тип ос
	if(substr(PHP_OS, 0, 3) == "WIN")
	{
		//Пингуем c Windows
		//$result = explode("\n", `ping -n 4 -l 32 `.$pinghost);
		exec('ping -n 4 -l 32 '.escapeshellcmd($pinghost), $result);
			
	}
	else
	{
		//Пингуем c Unix
		exec('ping -c 4 -s 32 '.escapeshellcmd($pinghost), $result);
	}
	
	
	//Формирвем область с результатом пинга
	$resp='<div class="ping">';
	
	foreach ($result as $res)
	{	//Преодбразуем массив значений в строку с переносами
		if(substr(PHP_OS, 0, 3) == "WIN")
		{ //Для windows перекодируем результаты в utf-8
		$resp.='<p>'.iconv("cp866","utf-8",$res).'</p>';
		}
		else
		{
			$resp.='<p>'.$res.'</p>';
		}
	}
	$resp.='</div>';
	
	return $resp;
}

//---------------перевод номера месяца в название
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
//---------Перекодировка для PDF---------------
function iconPDF($str)
{
	return iconv("utf-8","cp1251",$str);
}
//Экранирование символов HTML
function html($text)
{
	return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}
//Вывод  экранированых символов HTML
function htmlout($text)
{
	echo html($text);
}
//Вывод  в поля input экранированых символов HTML
function htmloutinput($text)
{
	echo '"'.html($text).'"';
}
//Формирование ссылок
function createLink($linktext,$link, $target=NULL)
{					//Текст    Путь   Цель
	return '<a href="'.$link.'" target="'.$target.'">'.$linktext.'</a>';
}
?>