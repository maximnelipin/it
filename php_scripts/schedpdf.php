<?php
	//��������� ������
	session_start();
	//���������� ���� � ����������� �����������
	if(isset($_SESSION['user_id']))
	{//���������� ���� ������ � pdf.
		include 'fpdf.php';
		include $_SERVER['DOCUMENT_ROOT'].'/php_scripts/func.php';
		//include "makefont/makefont.php";
		//MakeFont('makefont/times.ttf','times.afm','cp1251');
		//������� HEADER
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
		$pdf->SetFillColor(255,18,18);
		//���������
		$pdf->SetTitle(numToMonth($monyear[0]).' '.$monyear[1],true);
		$pdf->Cell(60,20,"������� 2015 ",1,1,'L',false);
		
		$pdf->Ln(20);
		//�������� ���������� ���� � ������
		$dayInMon=date('t');
		//������� ����
		$dayCount=1;
		//������� ������
		$weekCount=0;
		$pdf->SetFont('ArialMT','',12);
		
		//������������ ������ ������ ������
		for($i=0;$i<7;$i++){
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
		while(1)
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
			if($dayCount>$dayInMon) break;
		}
		
		for($i=0;$i<count($month);$i++)
		{	$ln=0;
			for($j=0;$j<7;$j++)
			{
				if($j==6) $ln=1;
				if(!empty($month[$i][$j]))
				{
					//���� ������� ��� �����������
					if($j==5 || $j==6)
						$pdf->Cell(40,20,$month[$i][$j]."�������",1,$ln,'L',true);
					else $pdf->Cell(40,20,$month[$i][$j],1,$ln,'L',false);
				}
				else $pdf->Cell(40,20," ",1,$ln,'L',false);
			}
			
		}	
		$pdf->Ln(20);
		$pdf->Output(numToMonth($monyear[0]).' '.$monyear[1],'I');		
		
	}
?>