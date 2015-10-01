<?php
	//Параметры подключения к серверу ldap
	//Сетеовй адрес сервера ldap
	$host = "du-dc.du.i-net.su";
	//Порт подключения ldap
	$port = "389";
	//Путь к группе, которая имеет доступ к Справочнику
	$groupit= "CN=it_core,OU=Security Groups,OU=Groups,DC=du,DC=i-net,DC=su";
	//Область поиска учётных записей сотрудников отдела ИТ
	$itou= "OU=users,OU=IT Dep,DC=du,DC=i-net,DC=su";
	//Домен
	$domain="@du.i-net.su"
?>