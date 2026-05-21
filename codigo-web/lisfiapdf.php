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
if (strtoupper($orden)=='CODIGO')
	$orden='cod_prof';
else if (strtoupper($orden)=='CEDULA')
	$orden='ced_prof';
else if (strtoupper($orden)=='NOMBRE')
	$orden='nombre';
else $orden='ubic_prof';

$sql="select codsoc_fia, codfia_fia, nropre_fia, monto_fia, monlib_fia, cod_prof, ced_prof, concat(trim(ape_prof),' ',nombr_prof) as afianzado, concat(trim(ape_prof),' ',nombr_prof) as fiador from sgcaf320, sgcaf200 where tipmov_fia='F' and (cod_prof=codsoc_fia) order by nropre_fia"; // . " limit 30"; //  limit 20";
// echo $sql;
$asocio=mysql_query($sql);
$columna=3;
$rpl=55; 	// registros por listado
$rp=0;		// registros por pagina
$crl=0;		// contador de registros por listado
$col_listado=0;
$header[0]='Prestamo';
$header[1]='Codigo';
$header[2]='Cedula';
$header[3]='Apellidos y Nombres Afianzado';
$header[4]='Codigo';
$header[5]='Nombre Fiador';
$header[6]='Monto';
$header[7]='Saldo';
$alto=3;
$salto=$alto;
$w=array(15,13,15,50,15,50,15,15); 
$p[0]=10;
for ($posicion=1;$posicion<count($w);$posicion++) 
	$p[$posicion]=$p[$posicion-1]+$w[$posicion-1];

$pdf=new PDF('P','mm','Letter');
$pdf->Open();
$sintitulo=false;
$primeravez = true;
$tsaldo = $tfianza = 0;
$rsocio = mysql_fetch_assoc($asocio);
$np=$rsocio['nropre_fia'];
$linea=encabeza_l_prestamos($header,$w,$p,$pdf,$salto,$alto);

// primer registro 
$linea+=$salto;
$pdf->SetY($linea);
$cont++;
$pdf->SetX($p[0]);		$pdf->Cell($w[0],$alto,$np,0,0,'LRTB',0);
$pdf->SetX($p[1]);		$pdf->Cell($w[1],$alto,$rsocio["cod_prof"],0,0,'C',0); 
$pdf->SetX($p[2]);		$pdf->Cell($w[2],$alto,$rsocio["ced_prof"],0,0,'L',0);  
$pdf->SetX($p[3]);		$pdf->Cell($w[3],$alto,$rsocio["nombre"],0,0,'L');

mysql_data_seek ($asocio, 0);		// volver al principio de la busqueda
while ($rsocio = mysql_fetch_assoc($asocio)){
	$rp++;
	if ($np==$rsocio['nropre_fia']) {
		$pdf->SetX($p[4]);		$pdf->Cell($w[4],$alto,$rsocio["codfia_fia"],0,0,'C');
// 		echo $rsocio["codfia_fia"];
		$fiador=$rsocio["codfia_fia"];
		$sfiador="select concat(trim(ape_prof),' ',nombr_prof) as fiador from sgcaf200 where cod_prof='$fiador'";
		$afiador=mysql_query($sfiador);
		$rfiador=mysql_fetch_assoc($afiador);
		$pdf->SetX($p[5]);		$pdf->Cell($w[5],$alto,$rfiador["fiador"],0,0,'L');
		$pdf->SetX($p[6]);		$pdf->Cell($w[6],$alto,number_format($rsocio["monto_fia"],2,'.',','),0,0,'R');
		$pdf->SetX($p[7]);		$pdf->Cell($w[7],$alto,number_format($rsocio["monlib_fia"],2,'.',','),0,0,'R');
		$tsaldo+=$rsocio["monlib_fia"];
		$tfianza+=$rsocio["monto_fia"];
		$linea+=$salto;
		$pdf->SetY($linea);
	}
	else {
//		$linea+=$salto;
//		$pdf->SetY($linea);
		$cont++;
//		$rp++;
		$np=$rsocio['nropre_fia'];
		$pdf->SetX($p[0]);		$pdf->Cell($w[0],$alto,$np,0,0,'LRTB',0);
		$pdf->SetX($p[1]);		$pdf->Cell($w[1],$alto,$rsocio["cod_prof"],0,0,'C',0); 
		$pdf->SetX($p[2]);		$pdf->Cell($w[2],$alto,$rsocio["ced_prof"],0,0,'L',0);  
		$pdf->SetX($p[3]);		$pdf->Cell($w[3],$alto,$rsocio["afianzado"],0,0,'L');

		$pdf->SetX($p[4]);		$pdf->Cell($w[4],$alto,$rsocio["codfia_fia"],0,0,'C');
		$fiador=$rsocio["codfia_fia"];
		$sfiador="select concat(trim(ape_prof),' ',nombr_prof) as fiador from sgcaf200 where cod_prof='$fiador'";
		$afiador=mysql_query($sfiador);
		$rfiador=mysql_fetch_assoc($afiador);
		$pdf->SetX($p[5]);		$pdf->Cell($w[5],$alto,$rfiador["fiador"],0,0,'L');
		$pdf->SetX($p[6]);		$pdf->Cell($w[6],$alto,number_format($rsocio["monto_fia"],2,'.',','),0,0,'R');
		$pdf->SetX($p[7]);		$pdf->Cell($w[7],$alto,number_format($rsocio["monlib_fia"],2,'.',','),0,0,'R');
		$tsaldo+=$rsocio["monlib_fia"];
		$tfianza+=$rsocio["monto_fia"];
		$linea+=$salto;
		$pdf->SetY($linea);
	}

	if ($rp >= $rpl) // linea>=($rpl*$alto)) {
//	if (linea>=($rpl*$alto))
	{
		$linea+=$alto;
		$pdf->SetY($linea);
		$pdf->SetX($p[0]);
		$pdf->Cell(0,0,'  ',1,0,'L',0);
		$linea=encabeza_l_prestamos($header,$w,$p,$pdf,$salto,$alto);
		$sintitulo=false;
		$linea-=$alto;
		$rp=0;
		}
}

$linea+=$alto;
$pdf->SetY($linea);
$pdf->SetX($p[0]);
$pdf->Cell(0,0,'  ',1,0,'L',0);
$linea+=$alto;
$pdf->SetY($linea);

$pdf->SetX($p[05]);		$pdf->Cell($w[05],$alto,'Totales',0,0,'R');
$pdf->SetX($p[06]);		$pdf->Cell($w[06],$alto,number_format($tfianza,2,'.',','),0,0,'R');
$pdf->SetX($p[07]);		$pdf->Cell($w[07],$alto,number_format($tsaldo,2,'.',','),0,0,'R');
// $pdf->SetX($p[12]);		$pdf->Cell($w[12],$alto,number_format($tdisponible,2,'.',','),0,0,'R');
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
$hoy = date("d")."/".date('m')."/".date("Y"); 
$pdf->MultiCell(0,0,"Listado de Fiadores al ".$hoy,0,C,0);
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
/*
//Cabecera  1
$pdf->SetY($linea);
$pdf->SetX($p[8]);
$pdf->Cell($w[8]+$w[9],$alto,'% Ahorros',1,0,'C',1);
$pdf->SetX($p[10]);
$pdf->Cell($w[10]+$w[11]+$w[12],$alto,'T o t a l',1,0,'C',1);
//Cabecera  2
*/
$linea+=$alto;
$pdf->SetY($linea);
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
