<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="../stylesheet/reset.css">
<link rel="stylesheet" type="text/css" href="../stylesheet/general.css">
<link rel="stylesheet" type="text/css" href="../stylesheet/add.css">
<title><?php htmlout($pageTitle); ?></title>
</head>
    <body>
    <?php	include $_SERVER['DOCUMENT_ROOT'].'/html/header.php';	?>
	    <h2 class="title"> <?php htmlout($pageTitle);?></h2>
	    <form action=?<?php htmlout($action);?> method="post">	    	
			<div class="field">
				<label for="typeppp"> Тип PPP</label>
				<input type="text" class="text" size="70" width="3" name="typeppp" value=<?php htmloutinput($typeppp);?>>
		    </div>	    			
		    <div class="field">
				<label for="srv"> Сервер PPP</label>
				<input type="text" class="text" size="70" width="3" name="srv" id="srv" value=<?php htmloutinput($srv);?>>
		    </div>
		    <div class="field">
				<label for="login"> Логин PPP</label>
				<input type="text" class="text" size="70" width="3" name="login" value=<?php htmloutinput($login);?>>
		    </div>
		    <div class="field">
			<label for="pwd"> Пароль PPP </label>
			<input type="text" class="text" size="70" width="3" name="pwd" value=<?php htmloutinput($pwd);?>>
	    	</div>
	    	<div class="field" >
	    		<?php	include $_SERVER['DOCUMENT_ROOT'].'/html/addbutton.php';?>
	    	</div>	    
	    </form>	    
    </body>    
</html>