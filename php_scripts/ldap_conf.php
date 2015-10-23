<?php
	//Параметры подключенияк ldap
	//Сервер ldap
	$host = "vs-00-dc.du.i-net.su";
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
	$userous[0]="OU=users,OU=developmentug,OU=DU,OU=Users&Pcs,DC=du,DC=i-net,DC=su";
	//OU с компами 1
	$pcous[0]="OU=PCs,OU=developmentug,OU=DU,OU=Users&Pcs,DC=du,DC=i-net,DC=su";
	//OU с компами 2
	$pcous[1]="OU=PCs,DC=du,DC=i-net,DC=su";
?>