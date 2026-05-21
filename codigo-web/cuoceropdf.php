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
$sql_amor="select *,descr_pres from sgcaf310, sgcaf360 where (stapre_sdp = 'A' and ! renovado) and (codpre_sdp=cod_pres) and (ultcan_sdp = 0 or cuota_ucla = 0) order by codpre_sdp, codsoc_sdp"; //   limit 30"; //  limit 20";
// echo $sql_amor;
$a_amor=mysql_query($sql_amor);
$columna=3;
$rpl=300; 	// registros por listado
$crl=0;		// contador de registros por listado
$col_listado=0;
// $arrtitulo="'Lin.Nº','Código','Cédula','Apellidos y Nombres',";
$header[0]='Lin N°';
$header[1]='Codigo';
$header[2]='Cedula';
$header[3]='Apellidos y Nombres';
$header[4]='Nro Prest.';
$header[5]='Saldo';
$header[6]='Cuota';
$header[7]='NC/CC';
$alto=3;
$salto=$alto;
$w=array(8,13,20,50,25,25,20,20); // ,25,25,25,25,25,25);
$p[0]=20;
for ($posicion=1;$posicion<count($w);$posicion++) 
	$p[$posicion]=$p[$posicion-1]+$w[$posicion-1];
//$p=array(10,18,31,36,76,91,106,131,136,161,196,221,246);

$pdf=new PDF('P','mm','Letter');
$pdf->Open();
// $registros=mysql_num_rows($a_amor);
// set_time_limit($registros);
$sintitulo=false;
$primeravez = true;
$santerior = $tcuota = $sactual = $tinteres = 0;
$gsanterior = $gtcuota = $gsactual = $gtinteres = 0;
$r_amor = mysql_fetch_assoc($a_amor);
$elanterior=$r_amor['codpre_sdp'];
$nombreprestamo=$r_amor['descr_pres'];
$linea=encabeza_l_prestamos($header,$w,$p,$pdf,$salto,$alto,$fechadescuento,$r_amor['descr_pres']);
/*
$pdf->SetY($linea);
$pdf->SetX($p[0]);
$pdf->Cell(0,$alto,$r_amor['descr_pres'],0,0,'LRTB',0); */
mysql_data_seek ($a_amor, 0);		// volver al principio de la busqueda
while ($r_amor = mysql_fetch_assoc($a_amor)){
	if ($elanterior == $r_amor['codpre_sdp']) {
		$linea+=$salto;
		$pdf->SetY($linea);
		$cont++;
		$pdf->SetX($p[0]);		$pdf->Cell($w[0],$alto,$cont,0,0,'LRTB',0);
		$pdf->SetX($p[1]);		$pdf->Cell($w[1],$alto,$r_amor["codsoc_sdp"],0,0,'LRTB',0); 
		$pdf->SetX($p[2]);		$pdf->Cell($w[2],$alto,$r_amor["cedsoc_sdp"],0,0,'LRTB',0);  
		$sql_200="select concat(trim(ape_prof),' ',nombr_prof) as nombre from sgcaf200 where cod_prof='".$r_amor['codsoc_sdp']."'";
		$a_200=mysql_query($sql_200);
//		echo $sql_200;
		$r_200=mysql_fetch_assoc($a_200);
		$pdf->SetX($p[3]);		$pdf->Cell($w[3],$alto,$r_200["nombre"],0,0,'LRTB');
		$pdf->SetX($p[4]);		$pdf->Cell($w[4],$alto,$r_amor["nropre_sdp"],0,0,'LRTB');
		$pdf->SetX($p[5]);		$pdf->Cell($w[5],$alto,number_format($r_amor["monpre_sdp"]-$r_amor["monpag_sdp"],2,'.',','),0,0,'R');
		$pdf->SetX($p[6]);		$pdf->Cell($w[6],$alto,number_format($r_amor["cuota"],2,'.',','),0,0,'R');
		$pdf->SetX($p[7]);		$pdf->Cell($w[7],$alto,number_format($r_amor["ultcan_sdp"],0,'.',',').'/'.number_format($r_amor["nrocuotas"],0,'.',','),0,0,'R');
		$ultimo=$r_amor['otro_int'];
		$santerior+=($r_amor["monpre_sdp"]-$r_amor["monpag_sdp"]);
		$tcuota+=$r_amor["cuota"];

		$gsanterior+=($r_amor["monpre_sdp"]-$r_amor["monpag_sdp"]);
		$gtcuota+=$r_amor["cuota"];
	}
	else {
		$linea+=$alto;
		$pdf->SetY($linea);
		$pdf->SetX($p[0]);
		$pdf->Cell(0,0,'  ',1,0,'L',0);
		$linea+=$alto;
		$pdf->SetY($linea);
		$pdf->SetX($p[3]);		$pdf->Cell($w[3]+$w[4],$alto,'Subtotal '.trim($nombreprestamo),0,0,'R');
		$pdf->SetX($p[5]);		$pdf->Cell($w[5],$alto,number_format($santerior,2,'.',','),0,0,'R');
		$pdf->SetX($p[6]);		$pdf->Cell($w[6],$alto,number_format($tcuota,2,'.',','),0,0,'R');

		$elanterior=$r_amor['codpre_sdp'];
		$nombreprestamo=$r_amor['descr_pres'];
		$linea=encabeza_l_prestamos($header,$w,$p,$pdf,$salto,$alto,$fechadescuento,$r_amor['descr_pres']);
		$santerior = $tcuota = $sactual = $tinteres = 0;
		$linea-=$alto;
		// repito para imprimir el primero 

		$linea+=$alto;
		$pdf->SetY($linea);
		$cont++;
		$pdf->SetX($p[0]);		$pdf->Cell($w[0],$alto,$cont,0,0,'LRTB',0);
		$pdf->SetX($p[1]);		$pdf->Cell($w[1],$alto,$r_amor["codsoc_sdp"],0,0,'LRTB',0); 
		$pdf->SetX($p[2]);		$pdf->Cell($w[2],$alto,$r_amor["cedsoc_sdp"],0,0,'LRTB',0);  
		$sql_200="select concat(trim(ape_prof),' ',nombr_prof) as nombre from sgcaf200 where cod_prof='".$r_amor['codsoc_sdp']."'";
		$a_200=mysql_query($sql_200);
//		echo $sql_200;
		$r_200=mysql_fetch_assoc($a_200);
		$pdf->SetX($p[3]);		$pdf->Cell($w[3],$alto,$r_200["nombre"],0,0,'LRTB');
		$pdf->SetX($p[4]);		$pdf->Cell($w[4],$alto,$r_amor["nropre_sdp"],0,0,'LRTB');
		$pdf->SetX($p[5]);		$pdf->Cell($w[5],$alto,number_format($r_amor["monpre_sdp"]-$r_amor["monpag_sdp"],2,'.',','),0,0,'R');
		$pdf->SetX($p[6]);		$pdf->Cell($w[6],$alto,number_format($r_amor["cuota"],2,'.',','),0,0,'R');
		$pdf->SetX($p[7]);		$pdf->Cell($w[7],$alto,number_format($r_amor["ultcan_sdp"],0,'.',',').'/'.number_format($r_amor["nrocuotas"],0,'.',','),0,0,'R');
		$ultimo=$r_amor['otro_int'];
		$santerior+=($r_amor["monpre_sdp"]-$r_amor["monpag_sdp"]);
		$tcuota+=$r_amor["cuota"];

		$gsanterior+=($r_amor["monpre_sdp"]-$r_amor["monpag_sdp"]);
		$gtcuota+=$r_amor["cuota"];

		//
	}
	if ($linea>=250) {
		$linea+=$alto;
		$pdf->SetY($linea);
		$pdf->SetX($p[0]);
		$pdf->Cell(0,0,'  ',1,0,'L',0);
		$linea=encabeza_l_prestamos($header,$w,$p,$pdf,$salto,$alto,$fechadescuento,$r_amor['descr_pres']);
		$sintitulo=false;
		$linea-=$alto;
		}
}

$linea+=$alto;
$pdf->SetY($linea);
$pdf->SetX($p[0]);
$pdf->Cell(0,0,'  ',1,0,'L',0);
$linea+=$alto;
$pdf->SetY($linea);
$pdf->SetX($p[3]);		$pdf->Cell($w[3]+$w[4],$alto,'Subtotal '.trim($nombreprestamo),0,0,'R');
$pdf->SetX($p[5]);		$pdf->Cell($w[5],$alto,number_format($santerior,2,'.',','),0,0,'R');
$pdf->SetX($p[6]);		$pdf->Cell($w[6],$alto,number_format($tcuota,2,'.',','),0,0,'R');

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
$pdf->SetFont('Arial','',7);
$pdf->Output();
set_time_limit(30);

////////////////////////////////////////////////////
function encabeza_l_prestamos($header,$w,$p,&$pdf,$salto,$alto,$fechadescuento,$nombreprestamo)
{
$pdf->AddPage();
$linea=25;
$pdf->SetY($linea);
$pdf->SetX(0);
$pdf->SetFont('Arial','B',14);
$pdf->MultiCell(0,0,"Cuotas de Prestamos en 0 al ".convertir_fechadmy($fechadescuento),0,C,0);
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
