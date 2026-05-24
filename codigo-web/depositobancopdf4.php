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
// $link = @mysql_connect("localhost","root", "",'',65536) or die ("<p /><br /><p /><div style='text-align:center'>En estos momentos no hay conexi�n con el servidor, int�ntalo m�s tarde.</div>");
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
// $sql_360="select * from sgcaf360 where (dcto_sem) order by cod_pres"; //  limit 30"; //  limit 20";
$sql_360=$_SESSION['albanco'];
$a_360=mysql_query($sql_360);
$columna=3;
$rpl=300; 	// registros por listado
$crl=0;		// contador de registros por listado
$col_listado=0;
$nuevoarchivo=false;
$condicion_sql='select codigo, cedula, nombre, nrocta, ';
$col_listado=0;
// $arrtitulo="'Lin.N�','C�digo','C�dula','Apellidos y Nombres',";
$header[0]='Lin N�';
$header[1]='Codigo';
$header[2]='Cedula';
$header[3]='Apellidos y Nombres';
$header[4]='Nro Cuenta';
$max_cols=mysql_num_rows($a_360);
$es_usd = array();
while ($r360 = mysql_fetch_assoc($a_360))
{
	$col_listado++;
	$columna++;
	$es_usd[$col_listado] = $r360['enUSD'];
//	if (($col_listado >= 1) and ($col_listado <= $max_cols)){
//		$arrtitulo.=$r360['desc_cor'];
	if (trim($r360['desc_cor'])!='') ;// $header[$columna]=$r360['desc_cor'] ;
	else ; // $header[$columna]=substr($r360['descr_pres'],0,12);
	$totales[$col_listado]=0;
	$campo='colpre'.$col_listado;
	$condicion_sql.=' colpre'.$col_listado.', colnro'.$col_listado;
	if ($col_listado != $max_cols) {
		$arrtitulo.=', ';
		$condicion_sql.=', ';
		}
//	}
//	else break;
}
// $columna++;
$header[5]='Total Dcto';
$alto=4;
$salto=$alto;
$w=array(8,13,25,70,30,30); // ,25,25,25,25,25,25);
$p[0]=30;
for ($posicion=1;$posicion<count($w);$posicion++) 
	$p[$posicion]=$p[$posicion-1]+$w[$posicion-1];
//$p=array(10,18,31,36,76,91,106,131,136,161,196,221,246);

$pdf=new PDF('P','mm','Letter');
$pdf->Open();
$linea=encabeza_l_prestamos($header,$w,$p,$pdf,$salto,$alto,$fechadescuento);
$sql_nopr=$condicion_sql." from sgcanopr where ('$fechadescuento' = fecha) order by nombre "; //  limit 20";
// echo $sql_nopr;
$a_nopr=mysql_query($sql_nopr);
$registros=mysql_num_rows($a_nopr);
set_time_limit($registros);
//echo $registros;
$lascolumnas=mysql_num_fields($a_nopr)-4;
while ($r_nopr = mysql_fetch_assoc($a_nopr)){
	$t1=0;
	for ($prestamos=1;$prestamos<=$max_cols;$prestamos++) {		// sumatoria de los prestamos
		$item='colpre'.$prestamos;
		$nroitem='colnro'.$prestamos;
		$monto_actual = $r_nopr[$item];
		if ($es_usd[$prestamos] == 1) {
			$monto_actual = convertir_monto_usd_bs($monto_actual, $r_nopr[$nroitem], $r_nopr['cedula']);
		}
		$t1+=$monto_actual;
		$totales[$prestamos]+=$monto_actual;
	}
	if ($t1 > 0) {
	$linea+=$salto;
	$pdf->SetY($linea);
	$cont++;
	$pdf->SetX($p[0]);
	$pdf->Cell($w[0],$alto,$cont,0,0,'LRTB',0);
	$pdf->SetX($p[1]);
	$pdf->Cell($w[1],$alto,$r_nopr["codigo"],0,0,'LRTB',0); 
	$pdf->SetX($p[2]);
	$pdf->Cell($w[2],$alto,$r_nopr["cedula"],0,0,'LRTB',0);  
	$pdf->SetX($p[3]);
	$pdf->Cell($w[3],$alto,$r_nopr["nombre"],0,0,'LRTB');
	$pdf->SetX($p[4]);
	$pdf->Cell($w[4],$alto,$r_nopr["nrocta"],0,0,'LRTB');
	$posicion=3;
	$pdf->SetY($linea);
	$pdf->SetX($p[5]);
	$pdf->Cell($w[5],$alto,number_format($t1,2,".",","),0,0,'R',0);
	if ($linea>=250) {
		$linea+=$alto;
		$pdf->SetY($linea);
		$pdf->SetX($p[0]);
		$pdf->Cell(0,0,'  ',1,0,'L',0);
		$linea=encabeza_l_prestamos($header,$w,$p,$pdf,$salto,$alto,$fechadescuento);
		}
	}
}
$general=0;
for ($i=1;$i<=count($totales);$i++)
	if ($totales[$i]!=0) {
		$general+=$totales[$i];
/*
		if ($linea>=250) {
			$pdf->SetX($p[0]);
			$pdf->Cell(0,0,'  ',1,0,'L',0);
			$linea=encabeza_l_prestamos($header,$w,$p,$pdf,$salto,$alto,$fechadescuento);
		}
*/
	}
$linea+=$alto;
$pdf->SetY($linea);
$pdf->SetX($p[4]);
$pdf->SetFont('Arial','B',10);
$pdf->Cell($w[4],$alto,'Total General',0,0,'L',1);
$pdf->SetX($p[5]);
$pdf->Cell($w[5],$alto,number_format($general,2,".",","),0,0,'R',1);
$pdf->SetFont('Arial','',7);
header('Content-Type: application/pdf');
$pdf->Output();
set_time_limit(30);

////////////////////////////////////////////////////
function encabeza_l_prestamos($header,$w,$p,&$pdf,$salto,$alto,$fechadescuento)
{
$pdf->AddPage();
$linea=25;
$pdf->SetY($linea);
$pdf->SetX(0);
$pdf->MultiCell(0,0,"Descuento de Pr�stamos (Banco) al ".convertir_fechadmy($fechadescuento),0,C,0);
$pdf->SetY($linea);
$pdf->SetFont('Arial','',7);
$linea+=5;
$pdf->SetX(170);
$pdf->Cell(20,0,'Realizado el '.date('d/m/Y h:i A'),0,0,'L'); 
//T�tulos de las columnas
$linea+=5;
$pdf->SetY($linea);
//$header=array($$arrtitulo);
//Colores, ancho de l�nea y fuente en negrita
$pdf->SetFillColor(200,200,200);
$pdf->SetTextColor(0);
$pdf->SetDrawColor(0,0,0);
$pdf->SetLineWidth(.2);
$pdf->SetFont('Arial','B',10);
//Cabecera
for($i=0;$i<6;$i++){
	$pdf->SetY($linea);
	$pdf->SetX($p[$i]);
	$pdf->Cell($w[$i],$alto,$header[$i],1,0,'C',1);
}
//Restauraci�n de colores y fuentes
$pdf->SetFillColor(224,235,255);
$pdf->SetTextColor(0);
$pdf->SetFont('Arial','',10);
$linea+=$salto;
$linea+=$salto;
$pdf->SetY($linea);
$pdf->SetX($p[0]);
$pdf->Cell(0,0,'  ',1,0,'L',0);
return $linea;
}
?>
