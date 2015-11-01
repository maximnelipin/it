<?php
	
		//Оперделяем тип ос
		if(substr(PHP_OS, 0, 3) == "WIN")
		{	$pinghost='192.168.1.14';
			//Пингуем c Windows
			$pingstr=array();
			
			
			'ping -n 4 -l 32 '.$pinghost;
			exec('ping -n 4 -l 32 '.escapeshellcmd($pinghost), $pingstr);
			
				var_dump ($pingstr);
				
				
			
		}
		
	
	
?>