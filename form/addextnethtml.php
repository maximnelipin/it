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
	    <h2 class="title"> <?php htmlout($pageTitle);?></h2>
	    <form action=?<?php htmlout($action);?> method="post">
	    	<div class="field">
			    		<label for="extip"> Внешний IP-адрес</label>
			    		<input type="text" class="text" size="70" width="3" name="extip"  id="extip" required value=<?php htmloutinput($extip);?>>
	    			</div>
	    			<div class="field">
			    		<label for="extmask"> Внешняя маска</label>
			    		<input type="text" class="text" size="70" width="3" name="extmask" value=<?php htmloutinput($extmask);?>>
	    			</div>
	    			<div class="field">
			    		<label for="extgw"> Внешний шлюз</label>
			    		<input type="text" class="text" size="70" width="3" name="extgw" value=<?php htmloutinput($extgw);?>>
	    			</div>
	    			<div class="field">
			    		<label for="extdns1"> Внешний первый DNS</label>
			    		<input type="text" class="text" size="70" width="3" name="extdns1" value=<?php htmloutinput($extdns1);?>>
	    			</div>
	    			<div class="field">
			    		<label for="extdns2"> Внешний второй DNS</label>
			    		<input type="text" class="text" size="70" width="3" name="extdns2" value=<?php htmloutinput($extdns2);?>>
	    			</div>
	    	</div>
	    	<div>
	    		<input type="hidden" name="id" value=<?php htmlout($id);?>>
	    		<input type="submit" class="button" value=<?php htmlout($button);?>>
	    		<input type="button" class="button" value="Назад" onClick=<?php echo 'location.replace("http://'.$_SERVER["HTTP_HOST"].$_SERVER["PHP_SELF"].'");'?>>
	    	</div>
	    
	    </form>
	    
    </body>
    
</html>