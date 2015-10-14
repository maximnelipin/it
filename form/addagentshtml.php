<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="../stylesheet/reset.css">
<link rel="stylesheet" type="text/css" href="../stylesheet/general.css">
<link rel="stylesheet" type="text/css" href="../stylesheet/add.css">
<title>Добавление контрагента</title>
</head>

    <body>
    <?php
	include $_SERVER['DOCUMENT_ROOT'].'/html/header.html';
	?>
	    <h2 class="title"> Добавление контрагента</h2>
	    <form action="?"  method="post">
	    	<div class="field">
	    		<label for="name" > Название контрагента</label>
	    		<input type="text" class="text" size="70" name="name">    	
	    	</div>
	    	<div class="field">
	    		<label for="manager"> ФИО менеджера</label>
	    		<input type="text" class="text" size="70"  name="manager">
	    	</div>
	    	<div class="field">
	    		<label for="telman"> Телефон менеджера</label>
	    		<input type="text" class="text" size="70"  name="telman">
	    	</div>
	    	<div class="field">
	    		<label for="emailman"> Электронная почта менеджера</label>
	    		<input type="text" class="text" size="70"  name="emailman">
	    	</div>
	    	<div class="field">
	    		<label for="address"> Адрес офиса обслуживания</label>
	    		<input type="text" class="text" size="70"  name="address"> 
	    	</div>
	    	<div class="field">
	    		<label for="type"> Тип предоставляемой/покупаемой услуг</label>
	    		<input type="text" class="text" size="70"  name="type"> 
	    	</div>
	    	<div class="field">
	    		<label for="netpath"> Папка с документами</label>
	    		<input type="text" class="text" size="70"  name="netpath">
	    	</div>
	    	<div class="field">
	    		<label for="note"> Примечание</label>
	    		<input type="text" class="text" size="70"  name="note">
	    	</div>
	    	<div>
	    		<input type="submit" class="button" value="Добавить">
	    	</div>
	    
	    </form>
	    <?php
	
		?>
    </body>
    
</html>