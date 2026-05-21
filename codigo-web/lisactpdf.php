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
$pdf=new PDF('P','mm','Letter');
$pdf->Open();
$pdf->AddPage();
$linea=30;
$pdf->SetY($linea);
$pdf->SetX(0);
$pdf->SetFont('Arial','B',14);
$pdf->MultiCell(0,0,"LISTADO DE ACTIVOS FIJOS",0,C,0);
$pdf->SetY($linea);
$pdf->SetFont('Arial','',7);
$linea+=5;
$pdf->SetX(165);
$pdf->Cell(20,0,'Realizada el '.date('d/m/Y h:i A'),0,0,'C'); 
$linea=37;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->SetFillColor(200,200,200);
$pdf->SetTextColor(0);
$pdf->SetDrawColor(0,0,0);
$pdf->SetLineWidth(.2);
$pdf->SetFont('Arial','B',7);
$header=array('Ident','Descripción','Adquisición','Costo','Valor según Libros');
//Colores, ancho de línea y fuente en negrita
    $pdf->SetFillColor(200,200,200);
    $pdf->SetTextColor(0);
    $pdf->SetDrawColor(0,0,0);
    $pdf->SetLineWidth(.2);
    $pdf->SetFont('Arial','B',8);
//Cabecera
    $w=array(12,110,23,25,28);
    for($i=i+n;$i<count($header);$i++)
	    $pdf->Cell($w[$i],7,$header[$i],1,0,'C',1);
		$pdf->Ln();
		
		$ttcosto = 0; 
$ttvalor = 0;
     $sql="SELECT * , date_format(fechaad, '%d/%m/%Y') AS fechax FROM sgcaf610, sgcaf640 WHERE departa = coddepar and motivodes='' and fechades = '0000-00-00' order by departa";
		$resultado=mysql_query($sql);
while ($row1 = mysql_fetch_array($resultado))
{	//echo $jj; 
  if ($jj <> $row1['descpdep']){ 
 $linea+=8;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->SetFont('Arial','B',8);
$pdf->Cell(0,6,'DEPARTAMENTO: '.$row1['descpdep'].'',0,0,'L',0);
		$sql="SELECT *, date_format(fechaad, '%d/%m/%Y') AS fechax FROM sgcaf610 where motivodes='' and fechades = '0000-00-00' order by nidentif";
		$result=mysql_query($sql);
//Restauración de colores y fuentes
  $pdf->SetFillColor(200,200,200);
    $pdf->SetTextColor(0);
    $pdf->SetFont('Arial','',7);
	
$linea+=2;
$tcosto = 0; 
$tvalor = 0;
	while ($row = mysql_fetch_array($result))
	//	echo $sql; 
      { 
	  $hhh = $row ['departa']; 
	  if ($hhh == $row1['coddepar']) {
		 $linea+=4;
		 $pdf->SetY($linea);
		 $pdf->Cell($w[0],7,$row["nidentif"],0,0,'C',0); 
		 $pdf->Cell($w[1],7,$row["descrip"],0,0,'L',0);
		 $pdf->Cell($w[2],7,$row["fechax"],0,0,'C',0);
$tcosto = $tcosto + $row['costo']; 
		 $pdf->Cell($w[3],7,number_format($row["costo"],2,".",","),0,0,'R',0);
		 $valor= $row['costo'] - $row['depacfecha']; 
		 if ($valor  < 0) { $valor = '0';
		 $pdf->Cell($w[4],7,number_format ($valor,2,'.',','),0,0,'R',0);
$tvalor= $tvalor + $valor;}
else {
$pdf->Cell($w[4],7,number_format ($valor,2,'.',','),0,0,'R',0);
$tvalor= $tvalor + $valor;}
		  if ($linea>=245){
		 	$pdf->AddPage();
						$linea=25;
						
$linea=30;
$pdf->SetY($linea);
$pdf->SetX(0);
$pdf->SetFont('Arial','B',14);
$pdf->MultiCell(0,0,"LISTADO DE ACTIVOS FIJOS",0,C,0);
$pdf->SetY($linea);
$pdf->SetFont('Arial','',7);
$linea+=5;
$pdf->SetX(165);
$pdf->Cell(20,0,'Realizada el '.date('d/m/Y h:i A'),0,0,'C'); 
$linea=37;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->SetFillColor(200,200,200);
$pdf->SetTextColor(0);
$pdf->SetDrawColor(0,0,0);
$pdf->SetLineWidth(.2);
$pdf->SetFont('Arial','B',7);
$header=array('Ident','Descripción','Adquisición','Costo','Valor según Libros');
//Colores, ancho de línea y fuente en negrita
    $pdf->SetFillColor(200,200,200);
    $pdf->SetTextColor(0);
    $pdf->SetDrawColor(0,0,0);
    $pdf->SetLineWidth(.2);
    $pdf->SetFont('Arial','B',8);
//Cabecera
    $w=array(12,110,23,25,28);
    for($i=i+n;$i<count($header);$i++)
	    $pdf->Cell($w[$i],7,$header[$i],1,0,'C',1);
		$pdf->Ln();
$linea=45;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->SetFont('Arial','B',8);
$pdf->Cell(0,6,'DEPARTAMENTO: '.$row1['descpdep'].'',0,0,'L',0);
//Restauración de colores y fuentes
  $pdf->SetFillColor(200,200,200);
    $pdf->SetTextColor(0);
    $pdf->SetFont('Arial','',7);
	}				
						
						
						
 }	 
	} 
$linea+=7;
$pdf->SetFont('Arial','B',8);
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(122,6,'Subtotal para '.$row1['descpdep'].'('.$row1['coddepar'].')',0,0,'R',0);
$pdf->SetX(132);
$pdf->Cell(48,6,number_format($tcosto,2,".",","),0,0,'R',0);
$pdf->SetX(180);
$pdf->Cell(28,6,number_format($tvalor,2,".",","),0,0,'R',0);
$ttcosto = $ttcosto + $tcosto; 
$ttvalor = $ttvalor + $tvalor;
$jj= $row1 ['descpdep']; 
}
}
$linea+=7;
$pdf->SetFont('Arial','B',8);
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(122,6,'TOTAL GENERAL:',0,0,'R',0);
$pdf->SetX(132);
$pdf->Cell(48,6,number_format($ttcosto,2,".",","),0,0,'R',0);
$pdf->SetX(180);
$pdf->Cell(28,6,number_format($ttvalor,2,".",","),0,0,'R',0);
$pdf->Output();
?>
