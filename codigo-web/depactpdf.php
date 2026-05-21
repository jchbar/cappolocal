<?php
session_start();

// include("fpdf/a_cookies.php");
extract($_GET);
extract($_POST);
extract($_SESSION);
    include("conex.php");

if (!$link OR !$_SESSION['empresa']) {
    //header("location: noempresa.php");
	exit;
} 
define('FPDF_FONTPATH','fpdf/font/');
require('fpdf/mysql_table.php');
include("fpdf/comunes.php");
// include ("conex.php"); 
$pdf=new PDF('L','mm','Letter');
$pdf->Open();
$ttcosto = 0; 
$ttdepacum = 0; 
$ttdep = 0;
$ttvalor = 0;
     $sql="SELECT * , substr( cta_contab, 1, 17 ) AS cuenta, date_format(fechaad, '%d/%m/%Y') AS fechax FROM sgcaf610, sgcaf620 WHERE (substr(cta_contab,1,17) = codigoact) and motivodes='' ORDER BY cta_contab";
		$resultado=mysql_query($sql);
while ($row1 = mysql_fetch_array($resultado))

{	//echo $jj; 
  if ($jj <> $row1['descripact']){ 
 
		$sql="SELECT *, date_format(fechaad, '%d/%m/%Y') AS fechax FROM sgcaf610 where motivodes='' order by cta_contab";
		$result=mysql_query($sql);
		
$pdf->AddPage();
$linea=30;
$pdf->SetY($linea);
$pdf->SetX(0);
$pdf->SetFont('Arial','B',14);
$pdf->MultiCell(0,0,"DEPRECIACIÓN DE ACTIVOS FIJOS A LA FECHA $fecha ",0,C,0);
$pdf->SetY($linea);
$pdf->SetFont('Arial','',7);
$linea+=5;
$pdf->SetX(235);
$pdf->Cell(20,0,'Realizada el '.date('d/m/Y h:i A'),0,0,'C'); 
$linea=37;
$pdf->SetY($linea);
$pdf->SetX(0);
$pdf->SetFont('Arial','B',12);
$pdf->MultiCell(0,0,$row1['descripact'],0,C,0);
$linea+=2;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->SetFillColor(300,300,300);
$pdf->SetTextColor(0);
$pdf->SetDrawColor(0,0,0);
$pdf->SetLineWidth(.2);
$pdf->SetFont('Arial','B',7);
$header=array('Ident','Descripción','Fecha de Adquisición','Costo','Meses','%','Depreciación Acumulada','Depreciación', 'Valor según Libros');
//Colores, ancho de línea y fuente en negrita
    $pdf->SetFillColor(200,200,200);
    $pdf->SetTextColor(0);
    $pdf->SetDrawColor(0,0,0);
    $pdf->SetLineWidth(.2);
    $pdf->SetFont('Arial','B',7);
//Cabecera
    $w=array(15,110,27,20,9,8,31,17,24);
    for($i=i+n;$i<count($header);$i++)
	    $pdf->Cell($w[$i],7,$header[$i],1,0,'C',1);
		$pdf->Ln();
//Restauración de colores y fuentes
  $pdf->SetFillColor(300,300,300);
    $pdf->SetTextColor(0);
    $pdf->SetFont('Arial','',7);
$linea+=3;
$tcosto = 0; 
$tdepacum = 0; 
$tdep = 0;
$tvalor = 0;
	while ($row = mysql_fetch_array($result))
	//	echo $sql; 
      { 
	  $hhh = substr($row ['cta_contab'],0,17); 
	  if ($hhh == $row1['codigoact']) {
		 $linea+=5;
		 $pdf->SetY($linea);
		 $pdf->Cell($w[0],7,$row["nidentif"],0,0,'C',0); 
		 $pdf->Cell($w[1],7,$row["descrip"],0,0,'L',0);
		 $pdf->Cell($w[2],7,$row["fechax"],0,0,'C',0);
$tcosto = $tcosto + $row['costo']; 
		 $pdf->Cell($w[3],7,number_format($row["costo"],2,".",","),0,0,'R',0);
		 $pdf->Cell($w[4],7,'1',0,0,'C',0);
		 $pdf->Cell($w[5],7,number_format ($row1['pordepre'],2,'.',','),0,0,'R',0);
if ($row['depacfecha'] >= $row['costo']){
$dep= $row['depacfecha'] + '0'; 
$tdepacum = $tdepacum + $dep; }
else {		 
$dep= $row['depacfecha']+$row['depmensual'];
$tdepacum = $tdepacum + $dep; 
}
if ($row['costo'] <= $dep){
$pdf->Cell($w[6],7,number_format ($dep,2,'.',','),0,0,'R',0);
$tdep= $tdep + '0,00';
$pdf->Cell($w[7],7,'0,00',0,0,'R',0);
$tvalor= $tvalor + '0,00';
$pdf->Cell($w[8],7,'0,00',0,0,'R',0); }
		  else if ($row['costo'] <> $dep) {
		  $pdf->Cell($w[6],7,number_format ($dep,2,'.',','),0,0,'R',0);
		  $tdep= $tdep + $row['depmensual'];
		  $pdf->Cell($w[7],7,number_format($row["depmensual"],2,".",","),0,0,'R',0);
		  $valor = $row['costo'] - $dep; 
		  $tvalor= $tvalor + $valor;
		 $pdf->Cell($w[8],7,number_format($valor,2,".",","),0,0,'R',0);}
		  if ($linea>=185){
		 	$pdf->AddPage();
						$linea=25;
						
					$linea=30;
$pdf->SetY($linea);
$pdf->SetX(0);
$pdf->SetFont('Arial','B',14);
$pdf->MultiCell(0,0,"DEPRECIACIÓN DE ACTIVOS FIJOS A LA FECHA $fecha ",0,C,0);
$pdf->SetY($linea);
$pdf->SetFont('Arial','',7);
$linea+=5;
$pdf->SetX(235);
$pdf->Cell(20,0,'Realizada el '.date('d/m/Y h:i A'),0,0,'C'); 
$linea=40;
$pdf->SetY($linea);
$pdf->SetX(0);
$pdf->SetFont('Arial','B',12);
$pdf->MultiCell(0,0,$row1['descripact'],0,C,0);
$linea+=2;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->SetFillColor(300,300,300);
$pdf->SetTextColor(0);
$pdf->SetDrawColor(0,0,0);
$pdf->SetLineWidth(.2);
$pdf->SetFont('Arial','B',7);
$header=array('Ident','Descripción','Fecha de Adquisición','Costo','Meses','%','Depreciación Acumulada','Depreciación', 'Valor según Libros');
//Colores, ancho de línea y fuente en negrita
    $pdf->SetFillColor(200,200,200);
    $pdf->SetTextColor(0);
    $pdf->SetDrawColor(0,0,0);
    $pdf->SetLineWidth(.2);
    $pdf->SetFont('Arial','B',7);
//Cabecera
    $w=array(15,110,27,20,9,8,31,17,24);
    for($i=i+n;$i<count($header);$i++)
	    $pdf->Cell($w[$i],7,$header[$i],1,0,'C',1);
		$pdf->Ln();
//Restauración de colores y fuentes
  $pdf->SetFillColor(300,300,300);
    $pdf->SetTextColor(0);
    $pdf->SetFont('Arial','',7);
	}				
						
						
						
 }	 
	} 
$linea+=7;
$pdf->SetFont('Arial','B',8);
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(125,6,'Subtotal para '.$row1['descripact'].'('.$row1['codigoact'].')',0,0,'R',0);
$pdf->SetX(135);
$pdf->Cell(47,6,number_format($tcosto,2,".",","),0,0,'R',0);
$pdf->SetX(182);
$pdf->Cell(48,6,number_format($tdepacum,2,".",","),0,0,'R',0);
$pdf->SetX(230);
$pdf->Cell(17,6,number_format($tdep,2,".",","),0,0,'R',0);
$pdf->SetX(247);
$pdf->Cell(24,6,number_format($tvalor,2,".",","),0,0,'R',0);
$ttcosto = $ttcosto + $tcosto; 
$ttdepacum = $ttdepacum + $tdepacum; 
$ttdep = $ttdep + $tdep;
$ttvalor = $ttvalor + $tvalor;
$jj= $row1 ['descripact']; 
}
}
$linea+=7;
$pdf->SetFont('Arial','B',8);
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(125,6,'TOTAL GENERAL:',0,0,'R',0);
$pdf->SetX(135);
$pdf->Cell(47,6,number_format($ttcosto,2,".",","),0,0,'R',0);
$pdf->SetX(182);
$pdf->Cell(48,6,number_format($ttdepacum,2,".",","),0,0,'R',0);
$pdf->SetX(230);
$pdf->Cell(17,6,number_format($ttdep,2,".",","),0,0,'R',0);
$pdf->SetX(247);
$pdf->Cell(24,6,number_format($ttvalor,2,".",","),0,0,'R',0);
$pdf->Output();
?>
