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
	//header("location: noempresa.php");
	exit;
}
 define('FPDF_FONTPATH','fpdf/font/');
require('fpdf/mysql_table.php');
include("fpdf/comunes.php");
require('funciones.php');
/*
$sql_360="select * from sgcaf360 where (dcto_sem) order by cod_pres"; //  limit 30"; //  limit 20";
$a_360=mysql_query($sql_360);
*/
$columna=3;
$rpl=300; 	// registros por listado
$crl=0;		// contador de registros por listado
$col_listado=0;
$nuevoarchivo=false;
$condicion_sql='select codigo, cedula, nombre, nrocta, ';
$col_listado=0;
// $arrtitulo="'Lin.Nº','Código','Cédula','Apellidos y Nombres',";
// $header[0]='Lin N°';
$header[0]='Codigo';
$header[1]='Cedula';
$header[2]='Apellidos y Nombres';
$header[3]='Nro Cuenta';
$header[4]='Codigo';
$header[5]='Cedula';
$header[6]='Apellidos y Nombres';
$header[7]='Nro Cuenta';
// $header[4]='Total Dcto';
$alto=3;
$salto=$alto;
$w=array(10,15,50,30, 10,15,50,30); // ,25,25,25,25,25,25);
$p=array(10,20,35,80,110,120,135,175); // ,25,25,25,25,25,25);
/*
$p[0]=30;
for ($posicion=1;$posicion<count($w);$posicion++) 
	$p[$posicion]=$p[$posicion-1]+$w[$posicion-1];
//$p=array(10,18,31,36,76,91,106,131,136,161,196,221,246);
*/

$pdf=new PDF('P','mm','Letter');
$pdf->Open();
$linea=encabeza_l_prestamos($header,$w,$p,$pdf,$salto,$alto,$fechadescuento);
$sql_nopr=$condicion_sql." from sgcanopr where ('$fechadescuento' = fecha) order by cedula "; //  limit 20";
// echo $sql_nopr;
$sql_nopr=$_SESSION['comandosql'];
$t1=$_SESSION['monto'];
$a_nopr=mysql_query($sql_nopr);
$registros=mysql_num_rows($a_nopr);
set_time_limit($registros);
// echo $registros;
// $lascolumnas=mysql_num_fields($a_nopr)-4;
$unavez=false;
while ($r_nopr = mysql_fetch_assoc($a_nopr)){
	$cont++;
	if ($unavez == false)
	{
		$linea+=$salto;
		$unavez=true;
		$colsum=4;
	}
	else {
		$colsum=0;
		$unavez=false;
	}
	$pdf->SetY($linea);
	$pdf->SetX($p[0+$colsum]);
//	$pdf->Cell($w[0],$alto,$cont,0,0,'LRTB',0);
//	$pdf->SetX($p[1]);
	$pdf->Cell($w[0+$colsum],$alto,$r_nopr["cod_prof"],0,0,'LRTB',0); 
	$pdf->SetX($p[1+$colsum]);
	$pdf->Cell($w[1+$colsum],$alto,$r_nopr["ced_prof"],0,0,'LRTB',0);  
	$pdf->SetX($p[2+$colsum]);
	$pdf->Cell($w[2+$colsum],$alto,$r_nopr["nombre"],0,0,'LRTB');
	$pdf->SetX($p[3+$colsum]);
	$pdf->Cell($w[3+$colsum],$alto,$r_nopr["ctan_prof"],0,0,'LRTB');
/*
	$pdf->SetY($linea);
	$pdf->SetX($p[5]);
	$pdf->Cell($w[5],$alto,number_format($t1,2,".",","),0,0,'R',0);
*/
	$general+=$t1;
	if (($linea>=250) and ($unavez == false)){
		$linea+=$alto;
		$pdf->SetY($linea);
		$pdf->SetX($p[0]);
		$pdf->Cell(0,0,'  ',1,0,'L',0);
		$linea=encabeza_l_prestamos($header,$w,$p,$pdf,$salto,$alto,$fechadescuento);
		}
}
/*
$general=0;
for ($i=1;$i<count($totales);$i++)
	if ($totales[$i]!=0) {
		$general+=$totales[$i];
	}
*/
$linea+=$alto;
$pdf->SetY($linea);
$pdf->SetX($p[3]);
$pdf->SetFont('Arial','B',10);
$pdf->Cell($w[3],$alto,'Total General',0,0,'L',1);
$pdf->SetX($p[5]);
$pdf->Cell($w[5],$alto,number_format($general,2,".",","),0,0,'R',1);
$pdf->SetFont('Arial','',7);
$pdf->Output();
set_time_limit(30);

////////////////////////////////////////////////////
function encabeza_l_prestamos($header,$w,$p,&$pdf,$salto,$alto,$fechadescuento)
{
$pdf->AddPage();
$linea=25;
$pdf->SetY($linea);
$pdf->SetX(20);
$pdf->SetFont('Arial','',14);
$titulo=($_SESSION['mutuo']==1?'Mutuo Auxilio':'Montepio');
$pdf->MultiCell(180,5,$titulo." al ".convertir_fechadmy($fechadescuento). ' '.$_SESSION['beneficiario'].' Monto '.number_format($_SESSION['monto'],2,".",","),0,C,0);
$pdf->SetY($linea);
$pdf->SetFont('Arial','',7);
//$linea+=5;
$pdf->SetX(170);
$pdf->Cell(20,0,'Realizado el '.date('d/m/Y h:i A'),0,0,'L'); 
//Títulos de las columnas
$linea+=10;
$pdf->SetY($linea);
//$header=array($$arrtitulo);
//Colores, ancho de línea y fuente en negrita
$pdf->SetFillColor(200,200,200);
$pdf->SetTextColor(0);
$pdf->SetDrawColor(0,0,0);
$pdf->SetLineWidth(.2);
$pdf->SetFont('Arial','B',7);
//Cabecera
for($i=0;$i<8;$i++){
	$pdf->SetY($linea);
	$pdf->SetX($p[$i]);
	$pdf->Cell($w[$i],$alto,$header[$i],0,0,'C',0);
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
