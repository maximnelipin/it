<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="../stylesheet/reset.css">
<link rel="stylesheet" type="text/css" href="../stylesheet/general.css">
<link rel="stylesheet" type="text/css" href="../stylesheet/add.css">
<link rel="stylesheet" type="text/css" href="../stylesheet/tcal.css">
<script src="../js_scripts/tcal.js"></script>
<title><?php htmlout($pageTitle); ?></title>
</head>

    <body>
    <?php
	include $_SERVER['DOCUMENT_ROOT'].'/html/header.php';
	?>
	    <h2 class="title"> <?php htmlout($pageTitle); ?></h2>
	     <form action=?<?php htmlout($action);?>  method="post">
	     	<div class="fieldcall">
	    		<label for="dateduty" > Дата дежурства</label>
	    		<input type="text" class=<?php htmloutinput($cls);?> name="dateduty"  value=<?php htmloutinput($dateduty);?> 
	    		required <?php htmlout($dis);?>>
	    	</div>
	    	
	    	
	    	<div class="field">
	    		<label for="login"> Дежурный</label>
	    		<select required class="text" size="5" name="login">
	    			<option disabled>Выберите дежурного</option>
	    			<?php 
						$ressql=getItusers($condb);
						if((gettype($ressql)=='array'))
						{
		    				foreach($ressql as $res)
		    				{    					
		    					if(strcasecmp($res['login'],$login)==0)
		    					{
		    						
		    						$select='selected';
		    					}
		    					else
		    					{
		    						$select='';
		    					}			    					
		    					echo '<option '.$select.' value='.$res['login'].'>'.$res['fio'].'</option>';		    						
		    				}
						}
	    				?>
	    		</select>  	
	    	</div>
	    	<div class="field" >
	    		<?php	include $_SERVER['DOCUMENT_ROOT'].'/html/addbutton.php';?>
	    	</div>
	    
	    </form>
	 </body>    
</html>