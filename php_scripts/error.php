
 
    
    	<?php 
    		//Если есть ошибка, выводим её после header
 
    		    //	echo '<script type="text/javascript">
    				//function addErr (){
    				
    			//	var err="'.$_SESSION['error'].'"; 
    			//	alert(err);
    			//	if (err!="noerror") {document.getElementById("error").innerHTML=err; 
    									//err="noerror";}
    			//	else document.getElementById("error").innerHTML="";					
	
					//}';
	    	//echo '</script>';
	    //	echo $_SESSION['error'];
	    	
	    	if(isset($_SESSION['error']) && $_SESSION['error']!='') 
		    {
			    echo '<h2 class="title"> Ошибка выполнения скрипта</h2>
			    <div class="error">';
			    
					
					echo iconv("cp1251","utf-8",$_SESSION['error']);
			  	//echo $_SESSION['error'];
			    echo '</div>';
			   // $_SESSION['error']='';
							
	    	}
	    	
	    ?>
	