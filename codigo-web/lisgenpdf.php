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

$sql="select cod_prof, ced_prof, concat(trim(ape_prof),' ',nombr_prof) as nombre, ubic_prof, statu_prof, sueld_prof, aport_prof, aport_empr, hab_f_prof+hab_f_empr+hab_f_extr as haberes, date_format(f_ing_capu, '%d/%m/%Y') AS  f_ing_capu  from sgcaf200 where upper(statu_prof)<> 'RETIRA' order by ".$orden; // . " limit 30"; //  limit 20";
// echo $sql;
$asocio=mysql_query($sql);
$columna=3;
$rpl=49; 	// registros por listado
$rp=0;		// registros por pagina
$crl=0;		// contador de registros por listado
$col_listado=0;
// $arrtitulo="'Lin.Nº','Código','Cédula','Apellidos y Nombres',";
$header[0]='Lin N°';
$header[1]='Codigo';
$header[2]='Cedula';
$header[3]='Apellidos y Nombres';
$header[4]='Ubicacion';
$header[5]='Status';
$header[6]='Ingreso';
$header[7]='Sueldo';
$header[8]='Socio';
$header[9]='UCLA';
$header[10]='Haberes';
$header[11]='Prestamos';
$header[12]='Disponible';
$alto=3;
$salto=$alto;
$w=array(8,13,20,50,20,15,15,20,10,10,20,20,20); // ,25,25,25,25,25,25);
$p[0]=10;
for ($posicion=1;$posicion<count($w);$posicion++) 
	$p[$posicion]=$p[$posicion-1]+$w[$posicion-1];
//$p=array(10,18,31,36,76,91,106,131,136,161,196,221,246);

$pdf=new PDF('L','mm','Letter');
$pdf->Open();
// $registros=mysql_num_rows($a_amor);
// set_time_limit($registros);
$sintitulo=false;
$primeravez = true;
$activos = $jubilados = $retirados = $otros = 0;
$thaberes = $tafectan = $tnoafectan = $tdisponible = 0;
// $rsocio = mysql_fetch_assoc($asocio);
$linea=encabeza_l_prestamos($header,$w,$p,$pdf,$salto,$alto);
// mysql_data_seek ($a_amor, 0);		// volver al principio de la busqueda
while ($rsocio = mysql_fetch_assoc($asocio)){
	$linea+=$salto;
	$pdf->SetY($linea);
	$cont++;
	$rp++;
	$codigo=$rsocio["cod_prof"];
	$cedula=$rsocio["ced_prof"];
	$ahorros=$rsocio["haberes"];
	$afectan=afectan($cedula);
	$noafectan=noafectan($cedula);
	$fianzas=fianzas($codigo);
	$disponible=disponibilidad($ahorros,$afectan,$noafectan,$fianzas);
	
	$tafectan+=$afectan;
	$tnoafectan+=$noafectan;
	$tdisponible+=$disponible;
	$tahorros+=$ahorros;
	
	$pdf->SetX($p[0]);		$pdf->Cell($w[0],$alto,$cont,0,0,'LRTB',0);
	$pdf->SetX($p[1]);		$pdf->Cell($w[1],$alto,$rsocio["cod_prof"],0,0,'C',0); 
	$pdf->SetX($p[2]);		$pdf->Cell($w[2],$alto,$rsocio["ced_prof"],0,0,'LRTB',0);  
	$pdf->SetX($p[3]);		$pdf->Cell($w[3],$alto,$rsocio["nombre"],0,0,'LRTB');
	$pdf->SetX($p[4]);		$pdf->Cell($w[4],$alto,$rsocio["ubic_prof"],0,0,'C');
	$pdf->SetX($p[5]);		$pdf->Cell($w[5],$alto,$rsocio["statu_prof"],0,0,'C');
	$pdf->SetX($p[6]);		$pdf->Cell($w[6],$alto,$rsocio["f_ing_capu"],0,0,'C');
	$pdf->SetX($p[7]);		$pdf->Cell($w[7],$alto,number_format($rsocio["sueld_prof"],2,'.',','),0,0,'R');
	$pdf->SetX($p[8]);		$pdf->Cell($w[8],$alto,number_format($rsocio["aport_prof"],2,'.',','),0,0,'R');
	$pdf->SetX($p[9]);		$pdf->Cell($w[9],$alto,number_format($rsocio["aport_empr"],2,'.',','),0,0,'R');
	$pdf->SetX($p[10]);		$pdf->Cell($w[10],$alto,number_format($rsocio["haberes"],2,'.',','),0,0,'R');
	$pdf->SetX($p[11]);		$pdf->Cell($w[11],$alto,number_format($afectan+$noafectan,2,'.',','),0,0,'R');
	$pdf->SetX($p[12]);		$pdf->Cell($w[12],$alto,number_format($disponible,2,'.',','),0,0,'R');
	// buscar prestamos
	// calcular disponibilidad
	if (strtoupper($rsocio["statu_prof"])=='ACTIVO')
		$activos ++;
	else if (strtoupper($rsocio["statu_prof"])=='JUBILA')
		$jubilados ++;
	else if (strtoupper($rsocio["statu_prof"])=='RETIRA')
		$retirados ++;
	else $otros++;
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
/*
$pdf->SetX($p[3]);		$pdf->Cell($w[3]+$w[4],$alto,'Total Socios Activos',0,0,'R');
$pdf->SetX($p[5]);		$pdf->Cell($w[5],$alto,number_format($activos,0,'.',','),0,0,'R');
$linea+=$alto;
$pdf->SetY($linea);
$pdf->SetX($p[3]);		$pdf->Cell($w[3]+$w[4],$alto,'Total Socios Jubilados',0,0,'R');
$pdf->SetX($p[5]);		$pdf->Cell($w[5],$alto,number_format($jubilados,0,'.',','),0,0,'R');
$linea+=$alto;
$pdf->SetY($linea);
$pdf->SetFont('Arial','B',7);
$pdf->SetX($p[3]);		$pdf->Cell($w[3]+$w[4],$alto,'Total Socios',0,0,'R');
$pdf->SetX($p[5]);		$pdf->Cell($w[5],$alto,number_format($activos+$jubilados,0,'.',','),0,0,'R');
$pdf->SetFont('Arial','',7);
*/
$pdf->SetX($p[3]);		$pdf->Cell($w[3]+$w[4],$alto,'Totales',0,0,'R');
$pdf->SetX($p[10]);		$pdf->Cell($w[10],$alto,number_format($tahorros,2,'.',','),0,0,'R');
$pdf->SetX($p[11]);		$pdf->Cell($w[11],$alto,number_format($tafectan+$tnoafectan,2,'.',','),0,0,'R');
$pdf->SetX($p[12]);		$pdf->Cell($w[12],$alto,number_format($tdisponible,2,'.',','),0,0,'R');
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
$pdf->MultiCell(0,0,"Listado de Socios al ".$hoy,0,C,0);
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
//Cabecera  1
$pdf->SetY($linea);
$pdf->SetX($p[8]);
$pdf->Cell($w[8]+$w[9],$alto,'% Ahorros',1,0,'C',1);
$pdf->SetX($p[10]);
$pdf->Cell($w[10]+$w[11]+$w[12],$alto,'T o t a l',1,0,'C',1);
//Cabecera  2
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
