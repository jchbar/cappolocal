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
$pdf->SetFont('Arial','B',12);
$linea+=5;
$pdf->SetX(20);
$pdf->Cell(160,0,'CONCILIACION BANCARIA',0,1,'C');
$pdf->SetFont('Arial','',7);
$linea+=5;
$pdf->SetX(165);
$pdf->Cell(20,0,'Realizada el '.date('d/m/Y h:i A'),0,0,'C'); 
	$sql="SELECT *, date_format(fecha_concil, '%d/%m/%Y') AS fecha FROM sgcaf843, sgcaf848 where nro_cta_ba='$codigo' and nro_cta_ba=cuent_banco and fecha_concil='$fecha'";
$resultado=mysql_query($sql);
$fila2 = mysql_fetch_assoc($resultado);
$linea+=1;
$pdf->SetFont('Arial','B',11);
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(71,6,'BANCO ' .$fila2['nombre_ban'],0,0,'L',0);
$pdf->SetX(81);
$pdf->Cell(25,6,'CUENTA Nº',0,0,'L',0);
$pdf->SetX(106);
$pdf->Cell(55,6,$fila2['nro_cta_ba'],0,0,'L',0);
$pdf->SetX(161);
$pdf->Cell(15,6,'Fecha',0,0,'L',0);
$pdf->SetX(176);
$pdf->Cell(24,6,$fila2['fecha'],0,0,'L',0);

$linea+=5;
$pdf->SetFillColor(200,200,200);
$pdf->SetTextColor(0);
$pdf->SetDrawColor(0,0,0);
$pdf->SetLineWidth(.2);
$pdf->SetFont('Arial','B',9);
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(130,6,'Saldo Según Bancos ',0,0,'L',0);
$pdf->SetX(140);
$pdf->Cell(30,6,number_format($fila2['saldo_bancos'],2,".",","),0,0,'R',0);
$linea+=5;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(130,6,'Saldo Según Libros ',0,0,'L',0);
$pdf->SetX(140);
$pdf->Cell(30,6,number_format($fila2['saldo_libros'],2,".",","),0,0,'R',0);
$linea+=5;
$pdf->SetFont('Arial','B',9);
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(160,6,'Diferencia',0,0,'R',1);
$pdf->SetX(170);
$diferencia=$fila2['saldo_bancos']-$fila2['saldo_libros']; 
$pdf->Cell(30,6,number_format($diferencia,2,".",","),0,0,'R',0);
$linea+=7;
$pdf->SetFont('Arial','B',9);
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(160,6,'Explicación de la Diferencia',0,0,'C',1);
$linea+=5;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(130,6,'Cheques sin Cobrar ',0,0,'L',0);
$pdf->SetX(140);
$pdf->Cell(30,6,number_format($fila2['monto_ch_t'],2,".",","),0,0,'R',0);
$linea+=5;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(130,6,'Depósitos en Tránsito ',0,0,'L',0);
$pdf->SetX(140);
$pdf->Cell(30,6,number_format($fila2['monto_de_t'],2,".",","),0,0,'R',0);
$linea+=6;
$pdf->SetFont('Arial','B',9);
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(160,6,'Diferencia Conciliada',0,0,'R',1);
$pdf->SetX(170);
$pdf->Cell(30,6,number_format($fila2['dif_con'],2,".",","),0,0,'R',0);

$sql="SELECT *, date_format(mche_fecha, '%d/%m/%Y') AS fecha FROM sgcaf840, sgcaf843 where  nro_cta_ba='$codigo' and mche_fecha<='$fecha' and mche_statu<>'L' and  cod_banco=mche_banco and cobrados='0' and fecha_cobrados='$fecha'";
$result=mysql_query($sql);
if (mysql_num_rows($result) > 0) {
$linea+=8;
$pdf->SetFont('Arial','B',9);
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(130,6,'Listado de Cheques Sin Cobrar',0,0,'C',1);
$linea+=5;
$pdf->SetFont('Arial','B',8);
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(15,6,'Fecha',0,0,'C',0);
$pdf->SetX(25);
$pdf->Cell(15,6,'Nº',0,0,'C',0);
$pdf->SetX(40);
$pdf->Cell(75,6,'Beneficiario',0,0,'C',0);
$pdf->SetX(115);
$totalche=0; 
$pdf->Cell(25,6,'Monto',0,0,'C',0);
while($row=mysql_fetch_assoc($result)) {
$linea+=4;
$pdf->SetFont('Arial','B',7);
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(15,5,$row['fecha'],0,0,'C',0);
$pdf->SetX(25);
$pdf->Cell(15,5,$row['mche_orden'],0,0,'C',0);
$pdf->SetX(40);
$pdf->Cell(75,5,trim($row['mche_nombr']),0,0,'L',0);
$pdf->SetX(115);
$totalche=$totalche+$row['mche_monto']; 
$pdf->Cell(25,5,number_format($row['mche_monto'],2,".",","),0,0,'R',0);
$pdf->SetX(120);
if ($linea>=245){
		 	$pdf->AddPage();
						$linea=40;
$pdf->SetY($linea);
$pdf->SetFont('Arial','B',12);
$linea+=5;
$pdf->SetX(20);
$pdf->Cell(160,0,'CONCILIACION BANCARIA',1,1,'C');
$pdf->SetFont('Arial','',7);
$linea+=5;
$pdf->SetX(165);
$pdf->Cell(20,0,'Realizada el '.date('d/m/Y h:i A'),0,0,'C'); 
		$sql="SELECT *, date_format(fecha_concil, '%d/%m/%Y') AS fecha FROM sgcaf843, sgcaf848 where nro_cta_ba='$codigo' and nro_cta_ba=cuent_banco and fecha_concil='$fecha'";
$resultado=mysql_query($sql);
$fila2 = mysql_fetch_assoc($resultado);
$linea+=1;
$pdf->SetFont('Arial','B',11);
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(71,6,'BANCO ' .$fila2['nombre_ban'],0,0,'L',0);
$pdf->SetX(81);
$pdf->Cell(25,6,'CUENTA Nº',0,0,'L',0);
$pdf->SetX(106);
$pdf->Cell(55,6,$fila2['nro_cta_ba'],0,0,'L',0);
$pdf->SetX(161);
$pdf->Cell(15,6,'Fecha',0,0,'L',0);
$pdf->SetX(176);
$pdf->Cell(24,6,$fila2['fecha'],0,0,'L',0);
$linea+=8;
$pdf->SetFont('Arial','B',9);
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(130,6,'Listado de Cheques Sin Cobrar',0,0,'C',1);
$linea+=5;
$pdf->SetFont('Arial','B',8);
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(15,6,'Fecha',0,0,'C',0);
$pdf->SetX(25);
$pdf->Cell(15,6,'Nº',0,0,'C',0);
$pdf->SetX(40);
$pdf->Cell(75,6,'Beneficiario',0,0,'C',0);
$pdf->SetX(115);
$totalche=0; 
$pdf->Cell(25,6,'Monto',0,0,'C',0);
}
}
$linea+=6;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(130,6,'Total de Cheques sin Cobrar',0,0,'R',0);
$pdf->SetX(140);
$pdf->Cell(30,6,number_format($totalche,2,".",","),0,0,'R',0);
}

$sql="SELECT *, date_format(com_fecha, '%d/%m/%Y') AS fecha FROM sgcaf820, sgcaf843 where  nro_cta_ba='$codigo' and com_fecha<='$fecha' and cue_banco=com_cuenta and cobrado='0' and fecha_cobro='$fecha'";
$result=mysql_query($sql);
if (mysql_num_rows($result) > 0) {
$linea+=6;
$pdf->SetFont('Arial','B',9);
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(130,6,'Listado de Depósitos en Tránsito',0,0,'C',1);
$linea+=5;
$pdf->SetFont('Arial','B',8);
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(15,6,'Fecha',0,0,'C',0);
$pdf->SetX(25);
$pdf->Cell(15,6,'Nº',0,0,'C',0);
$pdf->SetX(40);
$pdf->Cell(75,6,'Nombre',0,0,'C',0);
$pdf->SetX(115);
$pdf->Cell(25,6,'Monto',0,0,'C',0);
while($row=mysql_fetch_assoc($result)) {
$linea+=4;
$pdf->SetFont('Arial','B',7);
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(15,6,$row['fecha'],0,0,'C',0);
$pdf->SetX(25);
$pdf->Cell(15,6,$row['com_refere'],0,0,'C',0);
$pdf->SetX(40);
$pdf->Cell(75,6,$row['com_descri'],0,0,'L',0);
$pdf->SetX(115);
$totaldep=$totaldep+$row['com_monto1']; 
$pdf->Cell(25,6,number_format($row['com_monto1'],2,".",","),0,0,'R',0);
$pdf->SetX(120);
if ($linea>=245){
		 	$pdf->AddPage();
						$linea=25;
$linea=40;
$pdf->SetY($linea);
$pdf->SetFont('Arial','B',12);
$linea+=5;
$pdf->SetX(20);
$pdf->Cell(160,0,'CONCILIACION BANCARIA',1,1,'C');
$pdf->SetFont('Arial','',7);
$linea+=5;
$pdf->SetX(165);
$pdf->Cell(20,0,'Realizada el '.date('d/m/Y h:i A'),0,0,'C'); 
	$sql="SELECT *, date_format(fecha_concil, '%d/%m/%Y') AS fecha FROM sgcaf843, sgcaf848 where nro_cta_ba='$codigo' and nro_cta_ba=cuent_banco and fechaconcil='$fecha'";
$resultado=mysql_query($sql);
$fila2 = mysql_fetch_assoc($resultado);
$linea+=1;
$pdf->SetFont('Arial','B',11);
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(71,6,'BANCO ' .$fila2['nombre_ban'],0,0,'L',0);
$pdf->SetX(81);
$pdf->Cell(25,6,'CUENTA Nº',0,0,'L',0);
$pdf->SetX(106);
$pdf->Cell(55,6,$fila2['nro_cta_ba'],0,0,'L',0);
$pdf->SetX(161);
$pdf->Cell(15,6,'Fecha',0,0,'L',0);
$pdf->SetX(176);
$pdf->Cell(24,6,$fila2['fecha'],0,0,'L',0);
$linea+=8;
$pdf->SetFont('Arial','B',9);
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(130,6,'Listado de Depósitos en Tránsito',0,0,'C',1);
$linea+=5;
$pdf->SetFont('Arial','B',8);
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(15,6,'Fecha',0,0,'C',0);
$pdf->SetX(25);
$pdf->Cell(15,6,'Nº',0,0,'C',0);
$pdf->SetX(40);
$pdf->Cell(75,6,'Nombre',0,0,'C',0);
$pdf->SetX(115);
$pdf->Cell(25,6,'Monto',0,0,'C',0);
}
}
$linea+=6;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(130,6,'Total de Depósitos en Tránsito',0,0,'R',0);
$pdf->SetX(140);
$pdf->Cell(30,6,number_format($totaldep,2,".",","),0,0,'R',0);
}
$pdf->AutoPrint(true);
$pdf->Output();
?>