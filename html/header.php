<header> 
	<div class=leftcolumn>
	<?php if(isset($_SESSION['user_id'])): ?>
		<a href='/php_scripts/main.php' > Главная </a>
	<?php endif; ?>
	
	</div>
	<div class=centrcolumn>
		
		<a href="http://www.develug.ru"> <img alt="Логотип" src="/content/logo.png"></a>
	</div>
	<div class=rightcolumn>
		<?php if(isset($_SESSION['user_id'])): ?>
		<a href="../index.php?link=logout"> Выход </a>
		<?php endif; ?>
		<?php if(!isset($_SESSION['user_id'])):  ?>
		<a href="../index.php"> Вход </a>
		<?php endif; ?>
	</div>
</header>
