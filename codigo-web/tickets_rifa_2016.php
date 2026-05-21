<?php
/*  
  
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
	
include("head.php");

if (!$link OR !$_SESSION['empresa']) {
	include("noempresa.php");
	exit;
}
	*/

// $link = @mysql_connect("localhost","root", "",'',65536) or die ("<p /><br /><p /><div style='text-align:center'>En estos momentos no hay conexión con el servidor, inténtalo más tarde.</div>");
// mysql_select_db($_POST['sica'], $link);
session_start();

// include("fpdf/a_cookies.php");

extract($_GET);
extract($_POST);
extract($_SESSION);

include("conex.php");
if (!$link OR !$_SESSION['empresa']) {
	include("head.php");
	header("location: noempresa.php");
	exit;
}


//header('Content-type: application/pdf');
define('FPDF_FONTPATH','fpdf/font/');
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



// $pdf=new PDF_AutoPrint();
$pdf=new PDF('P','mm','Letter');
$pdf->Open();
$pdf->AddPage();
$lineaempieza=-30;
$linea=$lineaempieza;
$pdf->SetY($linea);
$pdf->SetX(0);
$sql="select codsoc_sdp, cedsoc_sdp,nom_prof, ape_prof from sgcaf310, sgcaf200 where ((tipo_socio='E' or tipo_socio='P') and (codsoc_sdp = cod_prof) and (UPPER(statu_prof)='ACTIVO' OR UPPER(statu_prof)='JUBILA')) and ((codpre_sdp='RIF' and f_1cuo_sdp='2016-12-31') and (stapre_sdp='A') and (renovado=0)) order by codsoc_sdp ";
$result= mysql_query($sql) or die('Error 810-5');
$izquierda=true;
while ($fila = mysql_fetch_assoc($result)) {
	if ($linea > 210)
		if ($izquierda == true)
		{
			$linea = $lineaempieza;
			$pdf->AddPage();
		}
	if ($izquierda == true)
	{
		$linea+=35;
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
	$pdf->SetFont('Arial','B',8);
	$pdf->SetY($linea+2);
	$pdf->SetX($lado);
	$pdf->Cell(50,7,'C A P P O U C L A',0,2,'C',1);
	$pdf->SetFont('Arial','B',36);
	$pdf->SetY($linea+4);
	$pdf->SetX($lado-10);
	$pdf->Rect($lado-25, $linea, 100, 35);
	// $pdf->Rect($lado+55, $linea, 100, 35);
	// $pdf->Cell(50,30,substr($fila['codsoc_sdp'],1,4),0,0,'C',0);
	$pdf->Cell(50,30,$fila['cedsoc_sdp'],0,0,'C',0);
	//Restauración de colores y fuentes
	$pdf->SetFillColor(255,255,255);
	$pdf->SetTextColor(0);
	$pdf->SetY($linea+10);
	$pdf->SetX($lado);
	$pdf->SetFont('Arial','',8);
	$pdf->Cell(50,30,substr($fila['codsoc_sdp'],1,4),0,0,'C',0);
	// $pdf->Cell(50,30,substr($fila['codsoc_sdp'],1,4).$fila['ape_prof'].$fila['nom_prof'],0,0,'C',0);
	// $pdf->Cell(50,30,substr($fila['codsoc_sdp'],1,4).'-'.$linea.'-'.($linea+35),0,0,'C',0);

	$pdf->Line(($lado+55), $linea, ($lado+55), ($linea+35) );
	$pdf->RotatedText($lado+65,$linea+10,''.$fila['cedsoc_sdp'],270); 
}
// $pdf->Line(75, 10, 75, 35);
 $pdf->Output('tickets.pdf');


?> 
