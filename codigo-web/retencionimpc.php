<?php
// ALTER TABLE `sgcaretucla` ADD `gastosadm` DECIMAL( 18, 3 ) NOT NULL DEFAULT '0';
session_start();

extract($_GET);
extract($_POST);
extract($_SESSION);
// $fechaaporte='2023-02-16';

include("conex.php");
if (!$link OR !$_SESSION['empresa']) {
	include("head.php");
	header("location: noempresa.php");
	exit;
}
include_once('funciones.php');

define('FPDF_FONTPATH','fpdf/font/');
require('fpdf/mysql_table.php');
include("fpdf/comunes.php");
$pdf=new PDF('P','mm','Letter');
$pdf->Open();
$pdf->AddPage();

$linea=0;
$linea=25;
$pdf->SetY($linea);
$pdf->SetX(0);
$concepto='A';
$pdf->SetFont('Arial','B',8);
$pdf->MultiCell(0,0,"Listado Gastos Administrativos Retención UCLA al ".convertir_fechadmy($fechaaporte),0,C,0);
$pdf->SetY($linea);
$pdf->SetFont('Arial','',8);
$pdf->SetFillColor(255,255,255);
$pdf->SetTextColor(0);
$linea+=5;
$comisionbancaria = 5;

$header=array('Cédula','Apellidos y Nombres','Comisión','','Cédula','Apellidos y Nombres','Comisión',''); 
$w=array(15, 40, 15,10, 15, 40,15,10);
$p=array(10, 30, 80, 95, 105, 125, 175,190);

$pdf->SetY($linea);
$pdf->SetFont('Arial','B',8);
$pdf->SetX(30);
	
//Cabecera
// $pc=$w[0];
// $p=array();
// array_push($p,$pc);
for($i=0;$i<count($header);$i++)
{
	$pdf->SetX($p[$i]);
    $pdf->Cell($w[$i],7,$header[$i],0,0,'C',1);
	// $pc+=$w[$i+1];
	// array_push($p,$pc);
}


$sql="select * from t_his200, sgcaf200 where (fecha ='$fechaaporte') and (t_his200.cedula = sgcaf200.ced_prof) order by cedula";
$result=mysql_query($sql) or die (mysql_error());
$emonto = $emonto2 = 0;
$maxlineas=235;
$pdf->SetFont('Arial','',8);
$cont='0'; 
$columna=0;
while ($row = mysql_fetch_array($result))
{
 //posicion celda, alto,contenido,bordes que mostramos(left,right top botton),0, alineacion izquierda,relleno
 //imprimo nombre, apellidos y localidad
	$linea+=4;
	$pdf->SetY($linea);
	$pdf->SetX($p[0+$columna]);
    $asterisco = (((trim($row['pertenece']) == 'AO') or (trim($row['pertenece']) == 'JO'))?'*':'');
	// $pdf->Cell($w[0+$columna],5,$row['cod_prof'],0,0,'LRTB',0); 
	// $pdf->SetX($p[1+$columna]);
	$pdf->Cell($w[0+$columna],5,$row['ced_prof'].$asterisco,0,0,'LRTB',0); 
	$cont++; 
	$acotado = substr(trim($row["ape_prof"]).' '.trim($row["nombr_prof"]),0,30);
	$pdf->SetX($p[1+$columna]);
    $pdf->Cell($w[1+$columna],5,$acotado,0,0,'LRTB',0);  
    $asterisco = (((trim($row['pertenece']) == 'AO') or (trim($row['pertenece']) == 'JO'))?'*':'');
	$pdf->SetX($p[2+$columna]);
	$pdf->Cell($w[2+$columna],5,number_format($row['comision'],2,".",",").$asterisco,0,0,'R',0);
	$pdf->SetX($p[3+$columna]);
	$pdf->Cell($w[3+$columna],5,$row['pertenece'].$asterisco,0,0,'L',0);
	$emonto+=$row['comision'];
	if ($linea >= $maxlineas)
	{
		$columna+=4;
		if ($columna < 6)
		{
			$linea+=4;
			$pdf->SetY($linea);
			$pdf->SetX($p[1]);
		    $pdf->Cell($w[1],5,'Van....',0,0,'C',0);  
			$pdf->SetX($p[2]);
			$pdf->Cell($w[2],5,number_format($emonto,2,".",","),0,0,'R',0);
			$linea=30;
			$pdf->SetY($linea);
			$pdf->SetX(0);
			$pdf->SetFont('Arial','B',8);			
		}
		else 
		{
			$linea+=4;
			$pdf->SetY($linea);

			$pdf->SetX($p[5]);
		    $pdf->Cell($w[5],5,'Van....',0,0,'C',0);  
			$pdf->SetX($p[6]);
			$pdf->Cell($w[6],5,number_format($emonto,2,".",","),0,0,'R',0);
			$columna = 0;
			$pdf->AddPage();
			$linea=25;
			$pdf->SetY($linea);
			$pdf->SetX(0);
			$pdf->SetFont('Arial','B',8);
			$concepto='A';
			$pdf->MultiCell(0,0,"Listado Gastos Administrativos Retención UCLA al ".convertir_fechadmy($fechaaporte),0,C,0);
			$pdf->SetY($linea);
			$pdf->SetFont('Arial','',8);
			$pdf->SetFillColor(255,255,255);
			$pdf->SetTextColor(0);
			$linea+=5;
			$pdf->SetY($linea);
			$pdf->SetFont('Arial','B',8);
			$pdf->SetX(30);
			
			//Cabecera

			for($i=0;$i<count($header);$i++)
			{
				$pdf->SetX($p[$i]);
			    $pdf->Cell($w[$i],7,$header[$i],0,0,'C',1);
			}
		}
	}
	$pdf->SetFont('Arial','',8);
};
$linea+=4;
$pdf->SetY($linea);

$pdf->SetX($p[4]);
$pdf->Cell($w[4],5,'Total....',0,0,'C',0);  
$pdf->SetX($p[5]);
$pdf->Cell($w[5],5,number_format($emonto,2,".",","),0,0,'R',0);

$sql="select sum(comision) as monto, pertenece, count(pertenece) as cuantos from t_his200 where (fecha ='$fechaaporte') group by pertenece";
$result=mysql_query($sql);
$linea+=4;
while ($row = mysql_fetch_array($result))
{
	$linea+=4;
	$pdf->SetY($linea);
	$pdf->SetX(30);
	$acotado = trim($row["pertenece"]);
	$pdf->SetX($p[1+$columna]);
	$pdf->Cell($w[1+$columna],5,$row['cuantos']. '->' .$acotado,0,0,'R',0); 
	$cont++; 
	$pdf->SetX($p[2+$columna]);
	$pdf->Cell($w[2+$columna],5,number_format($row['monto'],2,".",","),0,0,'R',0);
}

$archivo='retenciones_ucla/'.$fechaaporte.'c.pdf';
$ip = $_SERVER['HTTP_CLIENT_IP'];
if (!$ip) {$ip = $_SERVER['REMOTE_ADDR'];}
$sql = "update sgcaretucla set gastosadm = ".$emonto." where fecha ='$fechaaporte'";
// echo $sql;
$result=mysql_query($sql);
$pdf->Output();
$pdf->Output($archivo);

function encabeza_aportes($pdf,$linea)
{
//	return $linea;
}
?> 
