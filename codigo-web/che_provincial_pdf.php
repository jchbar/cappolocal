<?php
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
	//header("location: noempresa.php");
	exit;
}
define('FPDF_FONTPATH','fpdf/font/');
require('fpdf/mysql_table.php');
include("fpdf/comunes.php");

$pdf=new PDF_AutoPrint();
$pdf->AddPage();
$pdf->Open();
$linea=13+5;
$pdf->SetY($linea);
//$numero='00000001'; 
//$codigo='0002'; 
	$sql="SELECT *, date_format(mche_fecha, '%d/%m') AS fecha, date_format(mche_fecha, '%Y') AS ano, date_format(mche_fecha, '%d/%m/%Y') AS fechac  FROM sgcaf840, sgcaf843 where mche_orden='$numero' and mche_banco='$codigo' and mche_banco=cod_banco";
    $result=mysql_query($sql);
    $row1= mysql_fetch_array($result);
$pdf->SetFont('Arial','B',11);
$linea+=5;
$h='252,62'; 
$pdf->SetX(160);
$pdf->Cell(20,0,'**'.number_format($row1["mche_monto"],2,".",",").'**',0,0,'C');

$linea+=12-7;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->SetFillColor(200,200,200);
$pdf->SetTextColor(0);
$pdf->SetDrawColor(0,0,0);
$pdf->SetLineWidth(.2);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(43,6,'',0,0,'L',0);
$pdf->SetX(53);
$pdf->Cell(147,6,$row1["mche_nombr"],0,0,'L',0);
$linea+=13;
$linea+=6;
$pdf->SetFont('Arial','B',11);
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(20,7,'',0,0,'C',0);
$pdf->SetX(45-10);
$hoy= date("d/m");
$pdf->Cell(64,7,'Barquisimeto, '.$row1['fecha'],0,0,'L',0);
$h= date("Y");
$pdf->SetX(95);
$pdf->Cell(20,7,$row1['ano'],0,0,'L',0);

$linea+=8;
$linea+=6;
$linea+=6;
$linea+=6;
$linea+=6;
$linea+=8;
// $linea+=6;
$linea+=6;
$linea+=8;
$linea+=8;
$pdf->SetFont('Arial','',11);
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(76,6,$row1['nombre_ban'],0,0,'L',0);
$pdf->SetX(86);
$pdf->Cell(50,6,$row1['nro_cta_ba'],0,0,'R',0);
$pdf->SetX(130);
$pdf->Cell(38,6,$row1['fechac'],0,0,'R',0);
$pdf->SetX(174);
$pdf->Cell(26,6,$row1['mche_orden'],0,0,'R',0);
$linea+=10;
$linea+=8;
$pdf->SetFont('Arial','',11);
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(190,6,$row1['mche_descr'],0,0,'L',0);
$linea+=6;
$linea+=6;
$linea+=6;
$linea+=6;
$linea+=6;
$linea+=6;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->SetFillColor(200,200,200);
$pdf->SetTextColor(0);
$pdf->SetDrawColor(0,0,0);
$pdf->SetLineWidth(.2);
$pdf->SetFont('Arial','',11);
$header=array('','','','');
//Colores, ancho de línea y fuente en negrita
    $pdf->SetFillColor(200,200,200);
    $pdf->SetTextColor(0);
    $pdf->SetDrawColor(0,0,0);
    $pdf->SetLineWidth(.2);
    $pdf->SetFont('Arial','',9);
//Cabecera
    $w=array(38,74,48,30);
    for($i=i+n;$i<count($header);$i++)
	    $pdf->Cell($w[$i],6,$header[$i],0,0,'C',0);
		$pdf->Ln();
//Restauración de colores y fuentes
  $pdf->SetFillColor(200,200,200);
    $pdf->SetTextColor(0);
    $pdf->SetFont('Arial','',9);
		$sql="select * FROM sgcaf841 WHERE mche_orden='$numero' and mche_banco='$codigo' ORDER BY mche_debcr, registro DESC ";
$resultado=mysql_query($sql);
        //Restauración de colores y fuentes
  $pdf->SetFillColor(200,200,200);
    $pdf->SetTextColor(0);
    $pdf->SetFont('Arial','',9);
		
$td='0';
$th='0'; 
while($row=mysql_fetch_assoc($resultado)) {
$linea+=6;
		 $pdf->SetY($linea);
		 $pdf->Cell($w[0],6,$row["mche_cuent"],0,0,'L',0); 
		 $pdf->Cell($w[1],6,$row ["mche_descr"],0,0,'L',0);
		 if ($row["mche_debcr"]=='+') 
		 {
		 $pdf->Cell($w[2],6,number_format($row["mche_monto1"],2,".",","),0,0,'R',0);
		 $td=$td+$row['mche_monto1']; 
		 $th=$th+$row['mche_monto2']; 
		 $pdf->Cell($w[3],6,'',0,0,'R',0);
		 }
		 else if ($row["mche_debcr"]=='-') 
		 {
		 $pdf->Cell($w[2],6,'',0,0,'R',0);
		 $td=$td+$row['mche_monto1']; 
		 $th=$th+$row['mche_monto2']; 
		 $pdf->Cell($w[3],6,number_format($row["mche_monto2"],2,".",","),0,0,'R',0);
         }
}		
$linea+=6;
$pdf->SetFont('Arial','B',9);
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(112,6,'TOTALES',0,0,'R',0); 
$pdf->SetX(122);
$pdf->Cell(48,6,number_format($td,2,".",","),0,0,'R',0);  
$pdf->SetX(170);
$pdf->Cell(30,6,number_format($th,2,".",","),0,0,'R',0); 
$pdf->AutoPrint(true);
$pdf->Output();

?>