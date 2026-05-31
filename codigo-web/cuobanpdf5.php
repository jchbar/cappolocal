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
$sql_amor="select *,descr_pres from sgcaamor, sgcaf360 where ('$fechadescuento' = fecha) and (proceso=1) and (codpre=cod_pres) order by codpre, codsoc"; //  limit 30"; //  limit 20";
//echo $sql_amor;
$a_amor=mysql_query($sql_amor);
$columna=3;
$rpl=300; 	// registros por listado
$crl=0;		// contador de registros por listado
$col_listado=0;
$nuevoarchivo=false;
$condicion_sql='select codigo, cedula, nombre, nrocta, ';
$col_listado=0;
// $arrtitulo="'Lin.Nº','Código','Cédula','Apellidos y Nombres',";
$header[0]='Lin N°';
$header[1]='Codigo';
$header[2]='Cedula';
$header[3]='Apellidos y Nombres';
$header[4]='Nro Prest.';
$header[5]='Saldo Ant.';
$header[6]='Capital';
$header[7]='Saldo Actual';
$header[8]='Interes';
$header[9]='Cuota';
$alto=3;
$salto=$alto;
$w=array(8,13,20,50,25,25,25,25,25,25); // ,25,25,25,25,25,25);
$p[0]=20;
for ($posicion=1;$posicion<count($w);$posicion++) 
	$p[$posicion]=$p[$posicion-1]+$w[$posicion-1];
//$p=array(10,18,31,36,76,91,106,131,136,161,196,221,246);

$pdf=new PDF('L','mm','Letter');
$pdf->Open();
// $registros=mysql_num_rows($a_amor);
// set_time_limit($registros);
$sintitulo=false;
$primeravez = true;
$santerior = $tcuota = $sactual = $tinteres = 0;
$gsanterior = $gtcuota = $gsactual = $gtinteres = 0;
$r_amor = mysql_fetch_assoc($a_amor);
$elanterior=$r_amor['codpre'];
$ctainteres=$r_amor['otro_int'];
$nombreprestamo=$r_amor['descr_pres'];
$linea=encabeza_l_prestamos($header,$w,$p,$pdf,$salto,$alto,$fechadescuento,$r_amor['descr_pres']);
/*
$pdf->SetY($linea);
$pdf->SetX($p[0]);
$pdf->Cell(0,$alto,$r_amor['descr_pres'],0,0,'LRTB',0); */
mysql_data_seek ($a_amor, 0);		// volver al principio de la busqueda
while ($r_amor = mysql_fetch_assoc($a_amor)){
	if ($elanterior == $r_amor['codpre']) {
		$linea+=$salto;
		$pdf->SetY($linea);
		$cont++;
		$pdf->SetX($p[0]);		$pdf->Cell($w[0],$alto,$cont,0,0,'LRTB',0);
		$pdf->SetX($p[1]);		$pdf->Cell($w[1],$alto,$r_amor["codsoc"],0,0,'LRTB',0); 
		$pdf->SetX($p[2]);		$pdf->Cell($w[2],$alto,$r_amor["cedula"],0,0,'LRTB',0);  
		$pdf->SetX($p[3]);		$pdf->Cell($w[3],$alto,$r_amor["nombre"],0,0,'LRTB');
		$pdf->SetX($p[4]);		$pdf->Cell($w[4],$alto,$r_amor["nropre"],0,0,'LRTB');
		$pdf->SetX($p[5]);		$pdf->Cell($w[5],$alto,number_format($r_amor["saldo"],2,'.',','),0,0,'R');
		$pdf->SetX($p[6]);		
		if ($r_amor["diferido"] == 0)
			$pdf->Cell($w[6],$alto,number_format($r_amor["capital"],2,'.',','),0,0,'R');
		else 
			$pdf->Cell($w[6],$alto,number_format($r_amor["capital"]-$r_amor["interes"],2,'.',','),0,0,'R');
		$pdf->SetX($p[7]);		$pdf->Cell($w[7],$alto,number_format($r_amor["saldo"]-$r_amor['cuota'],2,'.',','),0,0,'R');
		$pdf->SetX($p[8]);		$pdf->Cell($w[8],$alto,number_format($r_amor["interes"],2,'.',','),0,0,'R');
		$pdf->SetX($p[9]);		$pdf->Cell($w[9],$alto,number_format(($r_amor["interes"]+$r_amor['capital']),2,'.',','),0,0,'R');
		$ultimo=$r_amor['otro_int'];
		$santerior+=$r_amor["saldo"];
		if ($r_amor["diferido"] == 0)
			$tcuota+=$r_amor["capital"];
		else 
			$tcuota+=$r_amor["capital"]-$r_amor["interes"];
		$sactual+=$r_amor["saldo"]-$r_amor['cuota'];
		$tinteres+=$r_amor["interes"];

		$gsanterior+=$r_amor["saldo"];
		if ($r_amor["diferido"] == 0)
			$gtcuota+=$r_amor["capital"];
		else
			$gtcuota+=$r_amor["capital"]-$r_amor["interes"];
		$gsactual+=$r_amor["saldo"]-$r_amor['cuota'];
		$gtinteres+=$r_amor["interes"];
	}
	else {
		$linea+=$alto;
		$pdf->SetY($linea);
		$pdf->SetX($p[0]);
		$pdf->Cell(0,0,'  ',1,0,'L',0);
		$linea+=$alto;
		$pdf->SetY($linea);
		$pdf->SetX($p[3]);		$pdf->Cell($w[3]+$w[4],$alto,'Subtotal '.trim($nombreprestamo). ' ('.$ctainteres.')',0,0,'R');
		$pdf->SetX($p[5]);		$pdf->Cell($w[5],$alto,number_format($santerior,2,'.',','),0,0,'R');
		$pdf->SetX($p[6]);		$pdf->Cell($w[6],$alto,number_format($tcuota,2,'.',','),0,0,'R');
		$pdf->SetX($p[7]);		$pdf->Cell($w[7],$alto,number_format($sactual,2,'.',','),0,0,'R');
		$pdf->SetX($p[8]);		$pdf->Cell($w[8],$alto,number_format($tinteres,2,'.',','),0,0,'R');
		$pdf->SetX($p[9]);		$pdf->Cell($w[9],$alto,number_format($tcuota+$tinteres,2,'.',','),0,0,'R');

		$elanterior=$r_amor['codpre'];
		$ctainteres=$r_amor['otro_int'];
		$nombreprestamo=$r_amor['descr_pres'];
		$linea=encabeza_l_prestamos($header,$w,$p,$pdf,$salto,$alto,$fechadescuento,$r_amor['descr_pres']);
		$sintitulo=false;
		$santerior = $tcuota = $sactual = $tinteres = 0;
		// repito para imprimir el primero 

		$linea+=$salto;
		$pdf->SetY($linea);
		$cont++;
		$pdf->SetX($p[0]);		$pdf->Cell($w[0],$alto,$cont,0,0,'LRTB',0);
		$pdf->SetX($p[1]);		$pdf->Cell($w[1],$alto,$r_amor["codsoc"],0,0,'LRTB',0); 
		$pdf->SetX($p[2]);		$pdf->Cell($w[2],$alto,$r_amor["cedula"],0,0,'LRTB',0);  
		$pdf->SetX($p[3]);		$pdf->Cell($w[3],$alto,$r_amor["nombre"],0,0,'LRTB');
		$pdf->SetX($p[4]);		$pdf->Cell($w[4],$alto,$r_amor["nropre"],0,0,'LRTB');
		$pdf->SetX($p[5]);		$pdf->Cell($w[5],$alto,number_format($r_amor["saldo"],2,'.',','),0,0,'R');
		$pdf->SetX($p[6]);		
		if ($r_amor["diferido"] == 0)
			$pdf->Cell($w[6],$alto,number_format($r_amor["capital"],2,'.',','),0,0,'R');
		else 
			$pdf->Cell($w[6],$alto,number_format($r_amor["capital"]-$r_amor["interes"],2,'.',','),0,0,'R');
		$pdf->SetX($p[7]);		$pdf->Cell($w[7],$alto,number_format($r_amor["saldo"]-$r_amor['cuota'],2,'.',','),0,0,'R');
		$pdf->SetX($p[8]);		$pdf->Cell($w[8],$alto,number_format($r_amor["interes"],2,'.',','),0,0,'R');
//		$pdf->SetX($p[9]);		$pdf->Cell($w[9],$alto,number_format(($r_amor["interes"]+$r_amor['capital']),2,'.',','),0,0,'R');
		$pdf->SetX($p[9]);		$pdf->Cell($w[9],$alto,number_format(($r_amor["cuota"]),2,'.',','),0,0,'R');
		$santerior+=$r_amor["saldo"];
		if ($r_amor["diferido"] == 0)
			$tcuota+=$r_amor["capital"];
		else 
			$tcuota+=$r_amor["capital"]-$r_amor["interes"];
		$sactual+=$r_amor["saldo"]-$r_amor['cuota'];
		$tinteres+=$r_amor["interes"];

		$gsanterior+=$r_amor["saldo"];
		if ($r_amor["diferido"] == 0)
			$gtcuota+=$r_amor["capital"];
		else
			$gtcuota+=$r_amor["capital"]-$r_amor["interes"];
		$gsactual+=$r_amor["saldo"]-$r_amor['cuota'];
		$gtinteres+=$r_amor["interes"];

		//
	}
	if ($linea>=180) {
		$linea+=$alto;
		$pdf->SetY($linea);
		$pdf->SetX($p[0]);
		$pdf->Cell(0,0,'  ',1,0,'L',0);
		$linea=encabeza_l_prestamos($header,$w,$p,$pdf,$salto,$alto,$fechadescuento,$r_amor['descr_pres']);
		$sintitulo=false;
		}
}

$linea+=$alto;
$pdf->SetY($linea);
$pdf->SetX($p[0]);
$pdf->Cell(0,0,'  ',1,0,'L',0);
$linea+=$alto;
$pdf->SetY($linea);
$pdf->SetX($p[3]);		$pdf->Cell($w[3]+$w[4],$alto,'Subtotal '.trim($nombreprestamo). ' ('.$ctainteres.')',0,0,'R');
$pdf->SetX($p[5]);		$pdf->Cell($w[5],$alto,number_format($santerior,2,'.',','),0,0,'R');
$pdf->SetX($p[6]);		$pdf->Cell($w[6],$alto,number_format($tcuota,2,'.',','),0,0,'R');
$pdf->SetX($p[7]);		$pdf->Cell($w[7],$alto,number_format($sactual,2,'.',','),0,0,'R');
$pdf->SetX($p[8]);		$pdf->Cell($w[8],$alto,number_format($tinteres,2,'.',','),0,0,'R');
$pdf->SetX($p[9]);		$pdf->Cell($w[9],$alto,number_format($tcuota+$tinteres,2,'.',','),0,0,'R');


$linea+=$alto;
$pdf->SetY($linea);
$pdf->SetX($p[0]);
$pdf->Cell(0,0,'  ',1,0,'L',0);
$linea+=$alto;
$pdf->SetY($linea);
$pdf->SetX($p[3]);
$pdf->SetFont('Arial','B',7);
$pdf->Cell($w[4],$alto,'Total General',0,0,'L',0);
$pdf->SetX($p[5]);		$pdf->Cell($w[5],$alto,number_format($gsanterior,2,'.',','),0,0,'R');
$pdf->SetX($p[6]);		$pdf->Cell($w[6],$alto,number_format($gtcuota,2,'.',','),0,0,'R');
$pdf->SetX($p[7]);		$pdf->Cell($w[7],$alto,number_format($gsactual,2,'.',','),0,0,'R');
$pdf->SetX($p[8]);		$pdf->Cell($w[8],$alto,number_format($gtinteres,2,'.',','),0,0,'R');
$pdf->SetX($p[9]);		$pdf->Cell($w[9],$alto,number_format($gtcuota+$gtinteres,2,'.',','),0,0,'R');
$pdf->SetFont('Arial','',7);
header('Content-Type: application/pdf');
$pdf->Output('reportesprestamos/'.$fechadescuento.'amortizacion.pdf');
$pdf->Output();
set_time_limit(30);

////////////////////////////////////////////////////
function encabeza_l_prestamos($header,$w,$p,&$pdf,$salto,$alto,$fechadescuento,$nombreprestamo)
{
$pdf->AddPage();
$linea=25;
$pdf->SetY($linea);
$pdf->SetX(0);
$pdf->MultiCell(0,0,"Amortizacion / Capital al ".convertir_fechadmy($fechadescuento),0,C,0);
$pdf->SetY($linea);
$pdf->SetFont('Arial','',7);
$linea+=5;
$pdf->SetX(220);
$pdf->Cell(20,0,'Realizado el '.date('d/m/Y h:i A'),0,0,'L'); 
//Títulos de las columnas
$linea+=$alto;
$pdf->SetY($linea);
$pdf->SetFont('Arial','B',10);
$pdf->SetX($p[0]);
$pdf->Cell(0,$alto,$nombreprestamo,0,0,'L',0);
$pdf->SetFont('Arial','',7);
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
$pdf->Cell(0,0,'  ',1,0,'L',0);
return $linea;
}
?>
