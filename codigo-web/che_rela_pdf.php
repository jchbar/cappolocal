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
$pdf->Open();
$pdf->AddPage();

$linea=40;
$pdf->SetY($linea);
$pdf->SetFont('Arial','',12);
$linea+=5;
$pdf->SetX(20);
$pdf->Cell(90,0,'Relación de Cheque emitidos del Banco:',0,0,'L');
$pdf->SetX(110);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(50,0,$nombre,0,0,'L');
$linea+=1;
$pdf->SetY($linea);
$pdf->SetX(20);
$pdf->SetFont('Arial','',12);
$pdf->Cell(30,0,'Cuenta Nro.',0,0,'L');
$pdf->SetX(50);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(60,0,$codigo,0,0,'L');
$pdf->SetX(110);
$pdf->SetFont('Arial','',12);
$pdf->Cell(85,0,'entregados a Socios y Beneficiarios de esta',0,0,'L');

$linea+=5;
$pdf->SetY($linea);
$pdf->SetX(20);
$pdf->SetFont('Arial','',12);
$pdf->Cell(50,0,'Institución de fecha',0,0,'L');
$pdf->SetX(75);
$pdf->SetFont('Arial','B',12);
$hoy= date("d/m/Y");
$pdf->Cell(60,0,$hoy,0,0,'L');


//Títulos de las columnas
$linea+=5;
$pdf->SetY($linea);
$header=array('Nombre del Beneficiario','Nro. de Cheque','Fecha','Monto Bs.');
//Colores, ancho de línea y fuente en negrita
    $pdf->SetFillColor(200,200,200);
    $pdf->SetTextColor(0);
    $pdf->SetDrawColor(0,0,0);
    $pdf->SetLineWidth(.2);
    $pdf->SetFont('Arial','B',10);
//Cabecera
    $w=array(90,30,30,40);
    for($i=i+n;$i<count($header);$i++)
	    $pdf->Cell($w[$i],4,$header[$i],0,0,'C',1);
		$pdf->Ln();
//Restauración de colores y fuentes
    $pdf->SetFillColor(224,235,255);
    $pdf->SetTextColor(0);
    $pdf->SetFont('Arial','',8);
	$total='0'; 
		$sql="SELECT *, date_format(mche_fecha, '%d/%m/%Y') AS fecha FROM sgcaf843, sgcaf840 where nro_cta_ba='$codigo' and cod_banco=mche_banco and mche_statu<>'L' and mche_fecha>='$fechadesde' and mche_fecha<='$fechahasta' and mche_orden>='$chequedesde' and mche_orden<='$chequehasta' order by mche_orden ASC";
$resultado=mysql_query($sql);
        while($row=mysql_fetch_assoc($resultado)) 
		{
         $linea+=4;
		 $pdf->SetY($linea);
		 if ($row["mche_statu"]=='A')
		 { 
		 $mche_nombre='***CHEQUE ANULADO***'; 
		 $mche_monto='0.00'; 
		 }
		 else 
		 { 
		 $mche_nombre=trim($row ["mche_nombr"]); 
		 $mche_monto=$row["mche_monto"]; 
		 }
		 $pdf->Cell($w[0],4,$mche_nombre,0,0,'L',0); 
		 $pdf->Cell($w[1],4,$row ["mche_orden"],0,0,'C',0);
		 $pdf->Cell($w[2],4,$row["fecha"],0,0,'R',0);
		 $pdf->Cell($w[3],4,number_format($mche_monto,2,".",","),0,0,'R',0); 
		 $total=$total+$mche_monto; 
		 if ($linea>=260){
		 	$pdf->AddPage();
						$linea=40;
$pdf->SetY($linea);
$pdf->SetFont('Arial','',12);
$linea+=5;
$pdf->SetX(20);
$pdf->Cell(90,0,'Relación de Cheque emitidos del Banco:',0,0,'L');
$pdf->SetX(110);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(50,0,$nombre,0,0,'L');
$linea+=1;
$pdf->SetY($linea);
$pdf->SetX(20);
$pdf->SetFont('Arial','',12);
$pdf->Cell(30,0,'Cuenta Nro.',0,0,'L');
$pdf->SetX(50);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(60,0,$codigo,0,0,'L');
$pdf->SetX(110);
$pdf->SetFont('Arial','',12);
$pdf->Cell(85,0,'entregados a Socios y Beneficiarios de esta',0,0,'L');

$linea+=5;
$pdf->SetY($linea);
$pdf->SetX(20);
$pdf->SetFont('Arial','',12);
$pdf->Cell(50,0,'Institución de fecha',0,0,'L');
$pdf->SetX(75);
$pdf->SetFont('Arial','B',12);
$hoy= date("d/m/Y");
$pdf->Cell(60,0,$hoy,0,0,'L');


//Títulos de las columnas
$linea+=5;
$pdf->SetY($linea);
$header=array('Nombre del Beneficiario','Nro. de Cheque','Fecha','Monto Bs.');
//Colores, ancho de línea y fuente en negrita
    $pdf->SetFillColor(200,200,200);
    $pdf->SetTextColor(0);
    $pdf->SetDrawColor(0,0,0);
    $pdf->SetLineWidth(.2);
    $pdf->SetFont('Arial','B',10);
//Cabecera
    $w=array(90,30,30,40);
    for($i=i+n;$i<count($header);$i++)
	    $pdf->Cell($w[$i],4,$header[$i],0,0,'C',1);
		$pdf->Ln();
//Restauración de colores y fuentes
    $pdf->SetFillColor(224,235,255);
    $pdf->SetTextColor(0);
    $pdf->SetFont('Arial','',8);
		 };
}		
$linea+=6;
$pdf->SetFont('Arial','B',9);
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(150,6,'TOTALES',0,0,'R',0); 
$pdf->SetX(160);
$pdf->SetFont('Arial','BU',9);
$pdf->Cell(40,6,number_format($total,2,".",","),0,0,'R',0);  
$pdf->AutoPrint(true);
$pdf->Output();
?>