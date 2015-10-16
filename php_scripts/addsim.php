<?php
	session_start();
	if(isset($_SESSION['user_id']))
	{	
		include 'mysql_conf.php';
		try {
			$condb=new PDO('mysql:host='.$hostsql.';dbname='.$dbname, $dbuser, $dbpwd);
			$condb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$condb->exec('SET NAMES "utf8"');
			
		}
		catch (PDOException $e)
		{
			include '../form/errorhtml.php';
			exit;
			
		}
		
		
		include 'func.php';
		if (isset($_POST['number']) && $condb!=null)	
		{
			
			try {
				
				$fields=array("number","account","id_address","id_operator","login","balance","pay","pwdlk","note");
				$sql='insert into sim set '.pdoSet($fields,$values);
				$sqlprep=$condb->prepare($sql);
				$sqlprep->execute($values);	
				
			}
			
			catch (PDOException $e)
			{				
				
				include '../form/errorhtml.php';
				exit;
			
			}
			
			header('Location .');
			exit;
		}
		
		include $_SERVER['DOCUMENT_ROOT'].'/form/addsimhtml.php';
		
		if($condb!=null) {$condb=NULL;}
	}
	else header('Location: ../index.php?link='.$_SERVER['PHP_SELF']);
	exit;
?>