<?php
	//��������� ������
	session_start();
	//���������� ���� � ����������� �����������
	if(isset($_SESSION['user_id']))
	{	if(isset($_REQUEST["monyear"]))
		{	
	
		//���������� ���� ������ � pdf.
		include 'fpdf.php';
		include $_SERVER['DOCUMENT_ROOT'].'/php_scripts/func.php';
		//--------������� � ����
		include 'mysql_conf.php';
		try {
			$condb=new PDO('mysql:host='.$hostsql.';dbname='.$dbname, $dbuser, $dbpwd);
			$condb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			//�������� ��������� ���� � utf8, cp1251 ���������� ��� ������������ pdf
			$condb->exec('SET NAMES "CP1251"');
		}
		catch (PDOException $e)
		{
			include '../form/errorhtml.php';
			exit;
		}
		
		//�������� ����� � ���
		$monyear=str_getcsv($_REQUEST["monyear"], ",");
		
		
		//������ ����� ������ � �����������: ���������� A4 � �������� �� ����������
		$pdf=new FPDF('L','mm','A4');
		$pdf->AddPage();
		$pdf->AddFont('TimesNewRomanPSMT','','times.php');
		$pdf->AddFont('TimesNewRomanPS-BoldMT','B','timesb.php');
		$pdf->AddFont('ArialMT','','a2c023acb498ea969bcb0e43b4925663_arial.php');
		//������������� �����
		$pdf->SetFont('ArialMT','',16);
		//������� �������� ����
		$pdf->SetFillColor(171,255,0);
		//���������
		$pdf->SetTitle(numToMonth($monyear[0]).' '.$monyear[1],true);
		//����� ������ ��� � �������������� � cp1251
		$pdf->Cell(80,20,iconv("utf-8","cp1251",numToMonth($monyear[0]).' '.$monyear[1]),1,1,'�',false);
		$pdf->Ln(20);
		//�������� ���������� ���� � ������
		$dayInMon=date('t');
		//������� ����
		$dayCount=1;
		//������� ������
		$weekCount=0;
		//������ ������ ������
		$pdf->SetFont('ArialMT','',12);
		//������ ������
		$hig=20;
		//������ ���� ��� �������
		$widfam=32;
		//������ ��� �����
		$widnum=8;
		//������������ ������ ������ ������
		for($i=0;$i<7;$i++)
		{
			//�������� ����� ��� ������
			$dayOfWeek=date('w', mktime(0, 0, 0, $monyear[0]  , $dayCount, $monyear[1]));
			//��������� ������������ ������� ������ � ������������ ����������
			$dayOfWeek=$dayOfWeek-1;
			if($dayOfWeek==-1) $dayOfWeek=6;
			//���� ���� ������ �������� � ����������� ���
			if($dayOfWeek==$i){
				//��������� ��������� ������ � �������
				$month[$weekCount][$i]=$dayCount;
				$dayCount++;
		
		
			}
			else{
				$month[$weekCount][$i]='';
			}
		}
		
		//������������ ����������� ������ ������
		while($dayCount<=$dayInMon)
		{
			$weekCount++;
			for($i=0;$i<7;$i++)
			{	
				$month[$weekCount][$i]=$dayCount;
				$dayCount++;
				//���� ����� ������ - ����� �� �����
				if($dayCount>$dayInMon) break;
			}
			//���� ����� ������ - ����� �� �����
			//if() break;
		}
		//�������� ��� ���, � ������� �������� � ���� �����
		$sql="select day(dateduty) as daym from schedule where month(dateduty)=:mon AND year(dateduty)=:year order by day(dateduty)";
		$resdaysql=$condb->prepare($sql);
		$resdaysql->bindValue(':mon',$monyear[0]);
		$resdaysql->bindValue(':year',$monyear[1]);
		$resdaysql->execute();
		$sql="select schedule.dateduty as dateduty, itusers.fio as fio from schedule
									right join itusers on itusers.login=schedule.login where
									day(schedule.dateduty)=:day
									 AND	month(schedule.dateduty)=:mon
									 AND year(schedule.dateduty)=:year";
		$resdutysql=$condb->prepare($sql);
		//�������� ��� ���, � ������� �������� � ���� �����
		//$sql="select day(dateduty) as daym from schedule where month(dateduty)=".$monyear[0].
				//" AND year(dateduty)=".$monyear[1]." order by day(dateduty)";
		//$resdaysql=$condb->query($sql);
		//�������� ������ � ������������
		$resday=$resdaysql->fetchall();
		//������� �����
		for($i=0;$i<count($month);$i++)
		{	
			
			//������������ ������
			for($j=0;$j<7;$j++)
			{	//���� �����������, �� ����� ���� ��������� �� ����� ������
				if($j==6) $ln=1;
				else $ln=0;
				
				//���� � ������� �� ������ �������
				if(!empty($month[$i][$j]))
				{	$fio=array();//�������� ������ � ��� ����������
					//��������� � ������� �� ������ �������
					reset($resday);
					//���������� ��� ��������
					foreach ($resday as $resd)
					{	//���������� ��� ��� ��������� � ��������� ����� � ���������� �����
						if($resd['daym']==$month[$i][$j])
						{//���� ������ ������ ������� ������� �����������
							//$sql="select schedule.dateduty as dateduty, itusers.fio as fio from schedule
							//right join itusers on itusers.login=schedule.login where
							//day(schedule.dateduty)=".$month[$i][$j]."
							// AND	month(schedule.dateduty)=".$monyear[0]."
							// AND year(schedule.dateduty)=".$monyear[1];
							//$ressql=$condb->query($sql);
							$resdutysql->bindValue(':day',$month[$i][$j]);
							$resdutysql->bindValue(':mon',$monyear[0]);
							$resdutysql->bindValue(':year',$monyear[1]);
							$resdutysql->execute();
							if($res=$resdutysql->fetch(PDO::FETCH_ASSOC))
							{
								$fio=str_getcsv($res["fio"], " ");
							}
						//������� �������������� �������� �� �������
						unset($resday['daym']);
						//������� �� �����
						break;
						}
						
					}					 
						
					//���� ������� ��� �����������
					if($j==5 || $j==6)
					{	
						$color=true;
					}	
					else 
					{	
						$color=false;
					}
					
					//������� �������������
					$pdf->Cell($widnum,$hig,$month[$i][$j],1,0,'C',$color);
					//���� ���� ������� �����������
					if(isset($fio[0]))
					{	//������� �
					$pdf->Cell($widfam,$hig,$fio[0],1,$ln,'C',$color);
					}
					else $pdf->Cell($widfam,$hig," ",1,$ln,'C',$color);
				}
				//���� ��� ���� �� ��� ����, ������� ������ ������ 
				else $pdf->Cell($widfam+$widnum,$hig," ",1,$ln,'C',false);
			}
			
		}
		//��������� �����������
		if($condb!=null) {$condb=NULL;}
		//������� �������� � ������� � ���������� ��� � ������������
		$pdf->Output(numToMonth($monyear[0]).' '.$monyear[1],'I');		
		}
	else header('Location: main.php');
	}
	else header('Location: ../index.php?link='.$_SERVER['PHP_SELF'].'?monyear='.$_REQUEST["monyear"]);
	exit;
?>