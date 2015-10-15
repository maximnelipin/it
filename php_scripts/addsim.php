<?php
	session_start();
	if(isset($_SESSION['user_id']))
	{	//if($_SESSION['error']!="noerror") $_SESSION['error']="noerror";
		include 'mysql_conf.php';
		try {
			$condb=new PDO('mysql:host='.$hostsql.';dbname='.$dbname, $dbuser, $dbpwd);
			$condb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$condb->exec('SET NAMES "utf8"');
			//$_SESSION['error']='';
		}
		catch (PDOException $e)
		{
			$error= $e->getMessage().'<a href='.$_SERVER['PHP_SELF'].'>'.
					$_SERVER['PHP_SELF'].'</a>';	
			$error=iconv("cp1251","utf-8",$error);
			$_SESSION['error']=$error;
			$_SESSION['urlerr']=$urlerr;
			include '../form/errorhtml.php';
			exit;
			
		}
		
		
		include 'func.php';
		if (isset($_POST['number']) && $condb!=null)	
		{
			//$login=$_POST["login"];
			//преобразуем путь к папке для записи в Mysql
			//$login=addslashes($login);
			try {
				
				$fields=array("number","account","id_address","id_operator","login","balance","pay","pwdlk","note");
				$sql='insert into sim set '.pdoSet($fields,$values);
				$sqlprep=$condb->prepare($sql);
				$sqlprep->execute($values);	
				
			}
			
			catch (PDOException $e)
			{
				
				$error= $e->getMessage().'<a href='.$_SERVER['PHP_SELF'].'>'.
				$_SERVER['PHP_SELF'].'</a>';	
				$error=iconv("cp1251","utf-8",$error);
				$_SESSION['error']=$error;
				include '../form/errorhtml.php';
				exit;
			
			}
			//$_SESSION['error']="noerror";
			header('Location .');
			//exit;
		}
		
		include $_SERVER['DOCUMENT_ROOT'].'/form/addsimhtml.php';
		
		
	}
	
	else header('Location ../index.php');
?>