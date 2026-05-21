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
session_start();
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
require('funciones.php');
$orden=$_GET['orden'];
$fecha=$_GET['desde'];
if (strtoupper($orden)=='CODIGO')
	$orden='cod_prof';
else if (strtoupper($orden)=='CEDULA')
	$orden='ced_prof';
else if (strtoupper($orden)=='NOMBRE')
	$orden='nombre';
else $orden='ubic_prof';

$sql="select * from sgcaviaj where (fecviaje =  '$fecha') group by cedtitular"; //   limit 30"; //  limit 20";
//echo $sql;
$asocio=mysql_query($sql);
$columna=3;
$rpl=300; 	// registros por listado
$crl=0;		// contador de registros por listado
$col_listado=0;
// $arrtitulo="'Lin.Nº','Código','Cédula','Apellidos y Nombres',";
$header[0]='';
$header[1]='Cedula';
$header[2]='Apellidos y Nombres';
$header[3]='Fecha Nac.';
$header[4]='Edad';
$alto=3;
$salto=$alto;
$w=array(8,20,50,30,20); // ,25,25,25,25,25,25);
$p[0]=30;
for ($posicion=1;$posicion<count($w);$posicion++) 
	$p[$posicion]=$p[$posicion-1]+$w[$posicion-1];
//$p=array(10,18,31,36,76,91,106,131,136,161,196,221,246);

$pdf=new PDF('P','mm','Letter');
$pdf->Open();
// $registros=mysql_num_rows($a_amor);
// set_time_limit($registros);
$sintitulo=false;
$primeravez = true;
$activos = $jubilados = 0;
$monto = 0;
// $rsocio = mysql_fetch_assoc($asocio);
$linea=encabeza_l_prestamos($header,$w,$p,$pdf,$salto,$alto);
// mysql_data_seek ($a_amor, 0);		// volver al principio de la busqueda
while ($rsocio = mysql_fetch_assoc($asocio)){
	$sql2="select * from sgcaviaj where (fecviaje = '$fecha') and cedtitular = '".$rsocio['cedtitular']."'";
	$aviaje=mysql_query($sql2);
	$cont=0;
	while ($rviaje = mysql_fetch_assoc($aviaje)){
		$linea+=$salto;
		$pdf->SetY($linea);
		$cont++;
//		$pdf->SetX($p[0]);		$pdf->Cell($w[0],$alto,$cont,0,0,'LRTB',0);
		if ($rsocio['cedtitular'] == $rviaje["cedbene"]) $pdf->SetFont('Arial','B',7);
			$pdf->SetX($p[1]);		$pdf->Cell($w[1],$alto,$rviaje["cedbene"],0,0,'LRTB',0);  
			$pdf->SetX($p[2]);		$pdf->Cell($w[2],$alto,$rviaje["nombre"],0,0,'LRTB');
			$pdf->SetX($p[3]);		$pdf->Cell($w[3],$alto,$rviaje["fecnac"],0,0,'C');
			$pdf->SetX($p[4]);		$pdf->Cell($w[3],$alto,$rviaje["edad"],0,0,'C');
		$pdf->SetFont('Arial','',7);
			
		$monto++;
//		$pdf->SetX($p[4]);		$pdf->Cell($w[4],$alto,number_format($rsocio["netcheque"],2,'.',','),0,0,'R');
//		$monto+=$rsocio["netcheque"];
/*
	if (strtoupper($rsocio["statu_prof"])=='RETIRA')
		$activos ++;
	else
		$jubilados ++;
*/
	if ($linea>=250) {
		$linea+=$alto;
		$pdf->SetY($linea);
		$pdf->SetX($p[0]);
		$pdf->Cell($p[0]+$p[1]+$p[2],0,'  ',1,0,'L',0);
		$linea=encabeza_l_prestamos($header,$w,$p,$pdf,$salto,$alto);
		$sintitulo=false;
		$linea-=$alto;
		}
	}
	$linea+=$alto;
	$pdf->SetY($linea);
	$pdf->SetX($p[2]);		$pdf->Cell($w[2],$alto,'Total grupo',0,0,'LRTB');
	$pdf->SetX($p[3]);		$pdf->Cell($w[3],$alto,$cont,0,0,'L');
	$linea+=$alto;
}

$linea+=$alto;
$pdf->SetY($linea);
$pdf->SetX($p[2]);		$pdf->Cell($w[2],$alto,'Total Personas ',0,0,'LRTB');
$pdf->SetX($p[3]);		$pdf->Cell($w[3],$alto,$monto,0,0,'L');
/*
$pdf->SetY($linea);
$pdf->SetX($p[0]);
$pdf->Cell($p[0]+$p[1]+$p[2],0,'  ',1,0,'L',0);
*/
/*
$linea+=$alto;
$pdf->SetY($linea);
$pdf->SetX($p[3]);		$pdf->Cell($w[3]+$w[4],$alto,'Total Socios Activos',0,0,'R');
$pdf->SetX($p[5]);		$pdf->Cell($w[5],$alto,number_format($activos,0,'.',','),0,0,'R');
$linea+=$alto;
$pdf->SetY($linea);
$pdf->SetX($p[3]);		$pdf->Cell($w[3]+$w[4],$alto,'Total Socios Jubilados',0,0,'R');
$pdf->SetX($p[5]);		$pdf->Cell($w[5],$alto,number_format($jubilados,0,'.',','),0,0,'R');
$linea+=$alto;
*/
/*
$pdf->SetY($linea);
$pdf->SetX($p[0]);
$pdf->SetFont('Arial','B',7);
$pdf->SetX($p[2]);		$pdf->Cell($w[2]+$w[3],$alto,''.number_format($activos+$jubilados,0,'.',',').' Socios',0,0,'R');
$pdf->SetX($p[4]);		$pdf->Cell($w[4],$alto,number_format($monto,2,'.',','),0,0,'R');
$pdf->SetFont('Arial','',7);
*/
$pdf->Output();
set_time_limit(30);
// $pdf->SetX($p[6]);		$pdf->Cell($w[6],$alto,number_format($tcuota,2,'.',','),0,0,'R');

////////////////////////////////////////////////////
function encabeza_l_prestamos($header,$w,$p,&$pdf,$salto,$alto)
{
$pdf->AddPage();
$linea=25;
$pdf->SetY($linea);
$pdf->SetX(0);
$pdf->SetFont('Arial','B',14);
$hoy = date("d")."-".date('m')."-".date("Y"); 
$hoy = convertir_fechadmy($_GET['desde']);
$pdf->MultiCell(0,0,"Personas Registradas para Viaje el ".$hoy,0,C,0);
$pdf->SetY($linea);
$pdf->SetFont('Arial','',7);
$linea+=5;
$pdf->SetX(220);
// $pdf->Cell(20,0,'Realizado el '.date('d/m/Y h:i A'),0,0,'L'); 
//Títulos de las columnas
/*
$linea+=$alto;
$pdf->SetY($linea);
$pdf->SetFont('Arial','B',10);
$pdf->SetX($p[0]);
$pdf->Cell(0,$alto,$nombreprestamo,0,0,'L',0);
$pdf->SetFont('Arial','',7);
*/
$linea+=5;
$pdf->SetY($linea);
//$header=array($$arrtitulo);
//Colores, ancho de línea y fuente en negrita
$pdf->SetFillColor(200,200,200);
$pdf->SetTextColor(0);
$pdf->SetDrawColor(0,0,0);
$pdf->SetLineWidth(.2);
$pdf->SetFont('Arial','B',7);
//Cabecera
for($i=0;$i<count($w);$i++){
	$pdf->SetY($linea);
	$pdf->SetX($p[$i]);
	$pdf->Cell($w[$i],$alto,$header[$i],1,0,'C',1);
}
//Restauración de colores y fuentes
$pdf->SetFillColor(224,235,255);
$pdf->SetTextColor(0);
$pdf->SetFont('Arial','',7);
$linea+=$salto;
$linea+=$salto;
$pdf->SetY($linea);
$pdf->SetX($p[0]);
$pdf->Cell($p[0]+$p[1]+$p[2],0,'  ',1,0,'L',0);
return $linea;
}
?>
