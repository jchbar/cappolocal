<?php
/*
error_reporting(E_ALL);
ini_set('display_errors',true);
*/
session_start();
// include("fpdf/a_cookies.php");
extract($_GET);
extract($_POST);
extract($_SESSION);

include("conex.php");
/*
if (!$link OR !$_SESSION['empresa']) {
	include("head.php");
	header("location: noempresa.php");
	exit;
}
*/


// header('Content-type: application/pdf');
define('FPDF_FONTPATH','fpdf/font/');
require('fpdf/fpdf.php');
ini_set('memory_limit','100M');

/*
require('fpdf/mysql_table.php');
include("fpdf/comunes.php");
// include ("conex.php"); 

//$pdf=new PDF('P','mm','Letter');

require('fpdf/pdf_js.php');


class PDF_AutoPrint extends PDF_JavaScript
{
function AutoPrint($dialog=false)
{
	//Open the print dialog or start printing immediately on the standard printer
	$param=($dialog ? 'true' : 'false');
	$script="print($param);";
	$this->IncludeJS($script);
}

function AutoPrintToPrinter($server, $printer, $dialog=false)
{
	//Print on a shared printer (requires at least Acrobat 6)
	$script = "var pp = getPrintParams();";
	if($dialog)
		$script .= "pp.interactive = pp.constants.interactionLevel.full;";
	else
		$script .= "pp.interactive = pp.constants.interactionLevel.automatic;";
	$script .= "pp.printerName = '\\\\\\\\".$server."\\\\".$printer."';";
	$script .= "print(pp);";
	$this->IncludeJS($script);
}
}
*/



//define('FPDF_FONTPATH','fpdf/font/');
/*
require('fpdf/rotation.php');
	

	class PDF extends PDF_Rotate
	{
		function RotatedText($x,$y,$txt,$angle)
		{
			//Text rotated around its origin
			$this->Rotate($angle,$x,$y);
			$this->Text($x,$y,$txt);
			$this->Rotate(0);
		}

		function RotatedImage($file,$x,$y,$w,$h,$angle)
		{
			//Image rotated around its upper-left corner
			$this->Rotate($angle,$x,$y);
			$this->Image($file,$x,$y,$w,$h);
			$this->Rotate(0);
		}
	}
*/


$header[0]='Linea';
$header[1]='Codigo';
$header[2]='Cedula';
$header[3]='Apellidos';
$header[4]='Nombres';
$alto=6;
$salto=$alto;
$w=array(20, 30,30,50, 50);
$p[0]=20;
for ($posicion=1;$posicion<count($w);$posicion++) 
	$p[$posicion]=$p[$posicion-1]+$w[$posicion-1];
// $pdf=new PDF_AutoPrint();
$pdf=new FPDF('P','mm','Letter');
$pdf->Open();
//$pdf->SetFont('Arial','',10);
//$pdf->AddPage();
$tamanocuadro = 40;
$lineaempieza=(($tamanocuadro-5)*(-1));
$linea=$lineaempieza;
$pdf->SetY($linea);
$pdf->SetX(0);
$participan = true;
if ($participan)
{
	$sql="select codsoc_sdp, cedsoc_sdp,nombr_prof, ape_prof from sgcaf310, sgcaf200 where ((tipo_socio='E' or tipo_socio='P') and (codsoc_sdp = cod_prof) and (UPPER(statu_prof)='ACTIVO' OR UPPER(statu_prof)='JUBILA')) and ((codpre_sdp='RIF' and f_1cuo_sdp='2018-12-31') and (stapre_sdp='A' or stapre_sdp='C')) order by cedsoc_sdp"; //limit 100 ";
	$titulo = "Participantes Sexto Sorteo (2018)";
}
else 
{
	$sql="select codsoc_sdp, cedsoc_sdp,nombr_prof, ape_prof from sgcaf310, sgcaf200 where ((tipo_socio='E' or tipo_socio='P') and (codsoc_sdp = cod_prof) and (UPPER(statu_prof)='ACTIVO' OR UPPER(statu_prof)='JUBILA')) and ((codpre_sdp='RIF' and f_1cuo_sdp='2018-12-26') and (stapre_sdp='A' or stapre_sdp='C')) order by cedsoc_sdp"; //limit 100 ";
	$titulo = "NO PARTICIPAN Sexto Sorteo (2018)";
}
$result= mysql_query($sql) or die('Error 810-5');
$izquierda=true;
$linea=encabeza($header,$w,$p,$pdf,$salto,$alto, $titulo);
$nro = 0;
while ($fila = mysql_fetch_assoc($result)) {
/*
	if ($linea >= ($tamanocuadro*5))
		if ($izquierda == true)
		{
			$linea = $lineaempieza;
			$pdf->AddPage();
		}
	if ($izquierda == true)
	{
		$linea+=$tamanocuadro;
		$lado=30;
		$izquierda = false;
	}
	else
	{
		$lado=130;
		$izquierda = true;
	}

	//Colores, ancho de línea y fuente en negrita
	$pdf->SetFillColor(200,200,200);
	$pdf->SetTextColor(0);
	$pdf->SetDrawColor(0,0,0);
	$pdf->SetLineWidth(.2);
	$pdf->SetFont('Arial','B',9);
	$pdf->SetY($linea+10);
	$pdf->SetX($lado);
	$pdf->Cell(30,7,'C A P P O U C L A',0,2,'C',1);
	$pdf->SetY($linea+8);
	$pdf->SetX($lado+25);
	$pdf->SetFont('Arial','I',8);
	$pdf->Cell(5,7,'2018',0,2,'C',0);


	$pdf->SetFont('Arial','B',30);
	$pdf->SetY($linea+8);
	$pdf->SetX($lado-10);
	$pdf->Rect($lado-25, $linea, 100, $tamanocuadro); // cuadro 
	// $pdf->Rect($lado+55, $linea, 100, 35);
	// $pdf->Cell(50,30,substr($fila['codsoc_sdp'],1,4),0,0,'C',0);
	$pdf->Cell(50,30,$fila['cedsoc_sdp'],0,0,'C',0);
	//Restauración de colores y fuentes
	$pdf->SetFillColor(255,255,255);
	$pdf->SetTextColor(0);
	$pdf->SetY($linea+14);
	$pdf->SetX($lado);
	$pdf->SetFont('Arial','',8);
	$pdf->Cell(50,30,substr($fila['codsoc_sdp'],1,4),0,0,'C',0);
	// $pdf->Cell(50,30,substr($fila['codsoc_sdp'],1,4).$fila['ape_prof'].$fila['nom_prof'],0,0,'C',0);
	// $pdf->Cell(50,30,substr($fila['codsoc_sdp'],1,4).'-'.$linea.'-'.($linea+35),0,0,'C',0);

	$pdf->Line(($lado+55), $linea, ($lado+55), ($linea+$tamanocuadro) ); // division vertical
//	$pdf->RotatedText($lado+65,$linea+10,''.$fila['cedsoc_sdp'],270); 

	$nuevalinea = ($linea+10);

	$pdf->SetY($nuevalinea-5);
	$pdf->SetX($lado+18);
	$pdf->SetFont('Arial','I',8);
	$pdf->Cell(95,7,'2018',0,2,'C',0);
	$pdf->SetFont('Arial','',8);

	for ($c = 0; $c < strlen($fila['cedsoc_sdp']); $c++)
	{
		$pdf->SetFont('Arial','I',7);
		$nuevalinea += 2;
		$pdf->SetY($nuevalinea);
		$pdf->SetX($lado+60);
//		$pdf->Cell(10,2,$nuevalinea,0,2,'C',1);
		$pdf->Cell(10,2,substr($fila['cedsoc_sdp'],$c,1),0,2,'C',1);
		
	}
*/
	if ($linea > 250)
	{
		$linea=encabeza($header,$w,$p,$pdf,$salto,$alto, $titulo);
	}
	$nro++;
	$pdf->SetY($linea); 
	$pdf->SetX($p[0]); $pdf->Cell($w[0],$salto,$nro,0,0,'C',0);
	$pdf->SetY($linea); 
	$pdf->SetX($p[1]); $pdf->Cell($w[1],$salto,$fila['codsoc_sdp'],0,0,'C',0);
	$pdf->SetY($linea); 
	$pdf->SetX($p[2]); $pdf->Cell($w[2],$salto,$fila['cedsoc_sdp'],0,2,'C',0);
	$pdf->SetY($linea); 
	$pdf->SetX($p[3]); $pdf->Cell($w[3],$salto,$fila['ape_prof'],0,2,'L',0);
	$pdf->SetY($linea); 
	$pdf->SetX($p[4]); $pdf->Cell($w[4],$salto,$fila['nombr_prof'],0,2,'L',0);
	$linea += $salto;
}
// $pdf->Line(75, 10, 75, 35);
// $pdf->Output('tickets.pdf');
$pdf->Output();

function encabeza($header,$w,$p,&$pdf,$salto,$alto, $titulo)
{
$pdf->AddPage();
$linea=15;
$pdf->SetY($linea);
$pdf->SetX(30);
$pdf->SetFont('Arial','B',20);
$pdf->Cell(0,0,$titulo);
$pdf->SetY($linea);
$pdf->SetFont('Arial','',7);
$linea+=5;
$pdf->SetX(170);
$pdf->Cell(20,0,'Realizado el '.date('d/m/Y h:i A'),0,0,'L'); 
//Títulos de las columnas
$linea+=5;
$pdf->SetY($linea);
//$header=array($$arrtitulo);
//Colores, ancho de línea y fuente en negrita
$pdf->SetFillColor(200,200,200);
$pdf->SetTextColor(0);
$pdf->SetDrawColor(0,0,0);
$pdf->SetLineWidth(.2);
$pdf->SetFont('Arial','B',12);
//Cabecera
for($i=0;$i<count($header);$i++){
	$pdf->SetY($linea);
	$pdf->SetX($p[$i]);
	$pdf->Cell($w[$i],$alto,$header[$i],1,0,'C',1);
}
//Restauración de colores y fuentes
$pdf->SetFillColor(224,235,255);
$pdf->SetTextColor(0);
$pdf->SetFont('Arial','',12);
$linea+=$salto;
$pdf->SetY($linea);
$pdf->SetX($p[0]);
// $pdf->Cell(0,0,'  ',1,0,'L',0);
return $linea;
}

?> 
