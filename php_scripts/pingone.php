<?php
	
	//Оперделяем тип ос
	if(substr(PHP_OS, 0, 3) == "WIN")
	{
		//Пингуем
		$result = explode("\n", `ping -n 4 -l 32 192.168.231.1`);
		//Выводим результаты пинга
		foreach ($result as $res)
		{
			echo iconv("cp866","utf-8",$res)."<br>";
		}
		
	}
	
	
?>