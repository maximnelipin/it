<?php
	//Параметры подключенияк ldap
	//Сервер ldap
	$host = "du-dc.du.i-net.su";
	//порт ldap
	$port = "389";
	//Группа сотрудников ИТ-отдела
	$groupit= "CN=it_core,OU=Security Groups,OU=Groups,DC=du,DC=i-net,DC=su";
	//Организационная единица пользователей
	$itou= "OU=users,OU=IT Dep,DC=du,DC=i-net,DC=su";
	//домен
	$domain="@du.i-net.su";
	//пользователь для подключения к серверу LDAP
	$usrd="max".$domain;
	//Пароль пользоватлея LDAP
	$pwdd="zuneipod23";
	//OU с сотрудниками
	$userou="OU=users,OU=developmentug,OU=DU,OU=Users&Pcs,DC=du,DC=i-net,DC=su";
	//OU с компами 1
	$pc1ou="OU=PCs,OU=developmentug,OU=DU,OU=Users&Pcs,DC=du,DC=i-net,DC=su";
	//OU с компами 2
	$pc2ou="OU=PCs,DC=du,DC=i-net,DC=su";
?>