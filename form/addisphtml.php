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
    <?php
	include $_SERVER['DOCUMENT_ROOT'].'/html/header.html';
	?>
	    <h2 class="title"> <?php htmlout($pageTitle); ?></h2>
	    <form action=?<?php htmlout($action);?>  method="post">
	    	<div class="field">
	    		<label for="name" > Название провайдера</label>
	    		<input type="text" class="text" size="70" name="name" value=<?php htmloutinput($name);?> required>  	
	    	</div>
	    	<div class="field">
	    		<label for="telsup"> Телефон поддержки, 11 цифр</label>
	    		<input type="text" class="text" size="70"  name="telsup" value=<?php htmloutinput($telsup);?> pattern="[0-9]{11}" required>
	    	</div>
	    	<div class="field">
	    		<label for="manager"> ФИО менеджера</label>
	    		<input type="text" class="text" size="70"  name="manager" value=<?php htmloutinput($manager);?>>
	    	</div>
	    	<div class="field">
	    		<label for="telman"> Телефон менеджера</label>
	    		<input type="text" class="text" size="70"  name="telman" value=<?php htmloutinput($telman);?>>
	    	</div>
	    	<div class="field">
	    		<label for="emailman"> Электронная почта менеджера</label>
	    		<input type="text" class="text" size="70"  name="emailman" value=<?php htmloutinput($emailman);?>>
	    	</div>
	    	<div class="field">
	    		<label for="address"> Адрес офиса обслуживания</label>
	    		<input type="text" class="text" size="70"  name="address" value=<?php htmloutinput($address);?>>
	    	</div>
	    	<div class="field">
	    		<label for="urllk"> Адрес личного кабинета</label>
	    		<input type="text" class="text" size="70"  name="urllk" value=<?php htmloutinput($urllk);?>>
	    	</div>
	    	<div class="field">
	    		<label for="netpath"> Папка с документами</label>
	    		<input type="text" class="text" size="70"  name="netpath" value=<?php htmloutinput($netpath);?>> 
	    	</div>
	    	<div class="field">
	    		<label for="note"> Примечание</label>
	    		<input type="text" class="text" size="70"  name="note" value=<?php htmloutinput($note);?>>
	    	</div>
	    	<div>
	    		<input type="hidden" name="id" value=<?php htmlout($id);?>>
	    		<input type="submit" class="button" value=<?php htmlout($button);?>>
	    		<input type="button" class="button" value="Назад" onClick=<?php echo 'location.replace("http://'.$_SERVER["HTTP_HOST"].$_SERVER["PHP_SELF"].'");'?>>
	    	</div>
	    
	    </form>
	    
    </body>
    
</html>