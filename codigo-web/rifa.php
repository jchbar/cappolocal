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


define('FPDF_FONTPATH','fpdf/font/');
ini_set('memory_limit','100M');
require('fpdf/mysql_table.php');
include("fpdf/comunes.php");
// include ("conex.php"); 

//$pdf=new PDF('P','mm','Letter');
$pdf=new PDF_AutoPrint();
$pdf->Open();
$pdf->AddPage();

$linea=-10;
$pdf->SetY($linea);
$pdf->SetX(0);
$sql="select cod_prof from sgcaf200 where ((tipo_socio='E' or tipo_socio='P') and (UPPER(statu_prof)='ACTIVO' OR UPPER(statu_prof)='JUBILA')) order by cod_prof"; //  limit 100";
$sql="select codsoc_sdp from sgcaf310 where (codpre_sdp='RIF' and f_1cuo_sdp='2014-12-31') and (stapre_sdp='A') order by codsoc_sdp"; //  limit 100";
$result= mysql_query($sql) or die('Error 810-5');
$izquierda=true;
while ($fila = mysql_fetch_assoc($result)) {
	if ($linea > 220)
		if ($izquierda == true)
		{
			$linea = -10;
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
	$pdf->SetFont('Arial','B',72);
	$pdf->SetY($linea+4);
	$pdf->SetX($lado);
	$pdf->Rect($lado-25, $linea, 100, 35);
	$pdf->Cell(50,30,substr($fila['codsoc_sdp'],1,4),0,0,'C',0);
	//Restauración de colores y fuentes
	$pdf->SetFillColor(255,255,255);
	$pdf->SetTextColor(0);
}
$pdf->Output();


?> 
