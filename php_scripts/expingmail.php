<?php
//Результаты пинга по заданию на через сервер Exchnge
include_once 'ExSendMailSmtpClass.php';
//Файл с функциями
include_once $_SERVER['DOCUMENT_ROOT'].'/php_scripts/func.php';
//Файл подключения к БД
include_once $_SERVER['DOCUMENT_ROOT'].'/php_scripts/mysql_conf.php';
$mailSMTP=new SendMailSmtpClass('max','zuneipod23', 'vs-00-ex-final.du.i-net.su','max@develug.ru',25);
//								логин  пароль		сервер							от кого			порт
// заголовок письма
$headers= "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=utf-8\r\n"; // кодировка письма
$headers .= "From: Max <max@develug.ru>\r\n"; // от кого письмо
$result =  $mailSMTP->send('nelmaxim@gmail.com', 'test', 'test', $headers); // отправляем письмо
// $result =  $mailSMTP->send('Кому письмо', 'Тема письма', 'Текст письма', 'Заголовки письма');
if($result === true){
    echo "Письмо успешно отправлено";
}else{
    echo "Письмо не отправлено. Ошибка: " . $result;
}	
		

?>