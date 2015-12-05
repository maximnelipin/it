<?php
	session_start();	
	if(isset($_SESSION['user_id']))
	{	
		//Файл с функциями
		include_once $_SERVER['DOCUMENT_ROOT'].'/php_scripts/func.php';
		//Файл подключения к БД
		include_once $_SERVER['DOCUMENT_ROOT'].'/php_scripts/mysql_conf.php';			
			
			$ctrltitle="МФУ и Принтеры";
			//Если получили id принтера
			if(isset($_GET['printers']))
			{	
				//Вывод всех принтеров
				if($_GET['printers']=="all")
				{
					$like='%';
				}
				//вывод одного принтера
				else
				{
					$like=$_GET['printers'];
				}
				//Делаем выборку моделей
				try 
				{
					$sqlmodel='SELECT * 
							FROM sprinters						
							WHERE id LIKE :id					
							LIMIT 30';
					$sqlprepmodel=$condb->prepare($sqlmodel);
					$sqlprepmodel->bindValue(':id', $like);
					$sqlprepmodel->execute();
				}
				catch (PDOExeption $e)
				{
					$sql=$sqlmodel;
					include '../form/errorhtml.php';
					exit;
				}
				//Подготавливаем запрос на выборку 
				$sqlprinter='SELECT printers.netpath, printers.note,  build.name as build,  build.address, floor.floor, cabinet.cabinet
					FROM  printers 
					LEFT JOIN cabinet ON printers.id_cabinet=cabinet.id
					LEFT JOIN floor ON cabinet.id_floor=floor.id
					LEFT JOIN build ON floor.id_build=build.id
					WHERE printers.id_printer= :id_printer';
				$sqlprepprinter=$condb->prepare($sqlprinter);
				
				//Если есть модели принтеров
				if($sqlprepmodel->rowCount()>0)
				{
					$resultmodel=$sqlprepmodel->fetchall();						
					//Для каждой модели делаем выборку экземпляров оборудования
					foreach ($resultmodel as $model)
					{	//Формируем таблицу для модели принтера
							$resmodel='<table>
			   					<caption>Модель принтера</caption>
			  					 <tr>
								<th>Наименование</th>
								<th>Картриджи</th>
			    				<th>Драйвера</th>
								</tr>';
							$resmodel.='<tr><td>'.
									html($model['name']).'</td><td>'.
									html($model['cart']).'</td><td>'.
									html($model['drivers']).'</td></tr>';
							$resmodel.='</table>';							
							$params[]=array('res'=>$resmodel, 'title'=>$model['name'],  'id'=>$model['id']);
							//Выбираем экземпляры модели
							try 
							{
								$sqlprepprinter->bindValue(':id_printer', $model['id']);
								$sqlprepprinter->execute();
							}
							catch (PDOExeption $e)
							{
								$sql=$sqlprinter;
								include '../form/errorhtml.php';
								exit;
							}
							//Если есть результаты выборки
							if($sqlprepprinter->rowCount()>0)
							{
								$resultprinter=$sqlprepprinter->fetchall();
								//шапка таблицы для принтеров
								$resprinter='<table>
						   					<caption>Принтеры</caption>
						  					 <tr>
											<th>Сетевой путь</th>
											<th>Объект</th>
											<th>Адрес</th>
											<th>Этаж</th>											
											<th>Кабинет</th>
											<th>Примечание</th>
											
						   					</tr>';
								foreach ($resultprinter as $printer)
								{
									//Добавляем экземпляр принтера
									$resprinter.='<tr><td>'.
											html($printer['netpath']).'</td><td>'.
											html($printer['build']).'</td><td>'.
											html($printer['address']).'</td><td>'.											
											html($printer['floor']).'</td><td>'.
											html($printer['cabinet']).'</td> <td>'.
											html($printer['note']).'</td> </tr>';
								}
								//Закрываем таблицу экземплярами одной модели
								$resprinter.='</table>';							
								$params1[]=array('res'=>$resprinter, 'title'=>'Местонахождение', 'id_1'=>$model['id']);
							}
						}
					}	
				}
		
		else
		{
			
			//Если не хватает парметров
			$ctrltitle='Принтеры <p> Не получены необходимые параметры</P>';
			
					 
		}
		include $_SERVER['DOCUMENT_ROOT'].'/form/rep3html.php';
		exit;
	}
	else header('Location: ../index.php?link='.str_replace('&','==',$_SERVER['REQUEST_URI']));
	exit;
?>