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
// include ("conex.php"); 
$pdf=new PDF('P','mm','Letter');
$pdf->Open();
$pdf->AddPage();

$linea=35;
$pdf->SetY($linea);
$pdf->SetX(0);
$pdf->MultiCell(0,0,"Listados de Saldos de Haberes ",0,C,0);
$pdf->SetY($linea);
$pdf->SetFont('Arial','',7);
$linea+=5;
$pdf->SetX(165);
$pdf->Cell(20,0,'Realizada el '.date('d/m/Y h:i A'),0,0,'C'); 
//Títulos de las columnas
$linea+=5;
$pdf->SetY($linea);
$header=array('Nº','Código','Cédula','Apellidos y Nombres','Haberes Socio','Haberes Patrono','Haberes Voluntario','Haberes Capitalizable','Total');
//Colores, ancho de línea y fuente en negrita
    $pdf->SetFillColor(200,200,200);
    $pdf->SetTextColor(0);
    $pdf->SetDrawColor(0,0,0);
    $pdf->SetLineWidth(.2);
    $pdf->SetFont('Arial','B',7);
//Cabecera
    $w=array(8,12,15,45,20,22,24,27,18);
    for($i=i+n;$i<count($header);$i++)
	    $pdf->Cell($w[$i],5,$header[$i],1,0,'C',1);
		$pdf->Ln();
//Restauración de colores y fuentes
  $pdf->SetFillColor(224,235,255);
    $pdf->SetTextColor(0);
    $pdf->SetFont('Arial','',7);
	$to1+=0;
    $to2+=0;
	$to3+=0;
	$to4+=0;
	$to5+=0;
	$cont=0;
//Buscamos y listamos los proveedores
	 //posicion celda, alto,contenido,bordes que mostramos(left,right top botton),0, alineacion izquierda,relleno
		 //imprimo nombre, apellidos y localidad
//	echo 'orden '.$orden;
//	echo 'ord '.$ord;
//	$ord= $orden;
   	$consulta = "SELECT cod_prof, ced_prof, ape_prof, nombr_prof, hab_f_prof, hab_f_empr, hab_f_extr, hab_f_capi FROM sgcaf200 WHERE hab_f_prof!=0 and hab_f_empr!=0 ORDER BY $ord ";
	$query = mysql_query($consulta);
$linea+=3;
	while ($row = mysql_fetch_array($query))
//	echo $sql;
       {
	 //posicion celda, alto,contenido,bordes que mostramos(left,right top botton),0, alineacion izquierda,relleno
		 //imprimo nombre, apellidos y localidad
		 $linea+=5;
		 $pdf->SetY($linea);
		 $cont++;
		 $pdf->Cell($w[0],5,$cont,0,0,'LRTB',0);
		 $pdf->Cell($w[1],5,$row["cod_prof"],0,0,'LRTB',0); 
		 $pdf->Cell($w[2],5,$row ["ced_prof"],0,0,'LRTB',0);  
		 $pdf->Cell($w[3],5,$row["ape_prof"].' '.$row["nombr_prof"],0,0,'LRTB');
		 $to1+=$row["hab_f_prof"];
		 $to2+=$row["hab_f_empr"];
		 $to3+=$row["hab_f_extr"];
		 $to4+=$row["hab_f_capi"];
		 $to5+=$t1;
		 $pdf->Cell($w[4],5,number_format($row["hab_f_prof"],2,".",","),0,0,'R',0);
         $pdf->Cell($w[5],5,number_format($row["hab_f_empr"],2,".",","),0,0,'R',0);
	     $pdf->Cell($w[6],5,number_format($row["hab_f_extr"],2,".",","),0,0,'R',0);
		 $pdf->Cell($w[7],5,number_format($row["hab_f_capi"],2,".",","),0,0,'R',0);
		 $t1=$row["hab_f_prof"]+$row["hab_f_empr"]+$row["hab_f_extr"]+$row["hab_f_capi"];
		 $pdf->Cell($w[8],5,number_format($t1,2,".",","),0,0,'R',0);
		 if ($linea>=245){
		 	$pdf->AddPage();
						$linea=25;
$pdf->SetY($linea);
$pdf->SetX(0);
$pdf->SetFont('Arial','b',14);
$pdf->MultiCell(0,0,"Listados de Saldos de Haberes ",0,C,0);
$pdf->SetY($linea);
$pdf->SetFont('Arial','',7);
$linea+=5;
$pdf->SetX(165);
$pdf->Cell(20,0,'Realizada el '.date('d/m/Y h:i A'),0,0,'C'); 
//Títulos de las columnas
$linea+=5;
$pdf->SetY($linea);
$header=array('Nº','Código','Cédula','Apellidos y Nombres','Haberes Socio','Haberes Patrono','Haberes Voluntario','Haberes Capitalizable','Total');
//Colores, ancho de línea y fuente en negrita
    $pdf->SetFillColor(200,200,200);
    $pdf->SetTextColor(0);
    $pdf->SetDrawColor(0,0,0);
    $pdf->SetLineWidth(.2);
    $pdf->SetFont('Arial','B',7);
//Cabecera
    $w=array(8,13,15,40,20,22,25,30,18);
    for($i=i+n;$i<count($header);$i++)
	    $pdf->Cell($w[$i],7,$header[$i],1,0,'C',1);
		$pdf->Ln();
//Restauración de colores y fuentes
  $pdf->SetFillColor(224,235,255);
    $pdf->SetTextColor(0);
    $pdf->SetFont('Arial','',7);
	$linea+=5;
	//Buscamos y listamos los proveedores
	 //posicion celda, alto,contenido,bordes que mostramos(left,right top botton),0, alineacion izquierda,relleno
		 //imprimo nombre, apellidos y localidad
//	echo 'orden '.$orden;
//	echo 'ord '.$ord;
//	$ord= $orden;
   	 //posicion celda, alto,contenido,bordes que mostramos(left,right top botton),0, alineacion izquierda,relleno
		 //imprimo nombre, apellidos y localidad
		 };
};
$linea+=10;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->SetFillColor(200,200,200);
$pdf->SetTextColor(0);
$pdf->SetDrawColor(0,0,0);
$pdf->SetLineWidth(.2);
$pdf->Cell(76,7,'Totales'.'  '. $cont ,0,0,'C',1);
$pdf->Cell(20,7,number_format($to1,2,".",","),0,0,'R',1);
$pdf->Cell(22,7,number_format($to2,2,".",","),0,0,'R',1);
$pdf->Cell(25,7,number_format($to3,2,".",","),0,0,'R',1);
$pdf->Cell(30,7,number_format($to4,2,".",","),0,0,'R',1);
$pdf->Cell(18,7,number_format($to5,2,".",","),0,0,'R',1);
$pdf->Output();
function convertir_fechadmy1($mifecha)
{
//	$mifecha=strtotime($mifecha);
//	echo $mifecha;
	$a=explode("-",$mifecha); 
	$elano=substr($a[0],0,2);
	if ($elano="20") $b=$a[2]."/".$a[1]."/".$a[0];
	else $b=$a[2]."/".$a[1]."/"."20".$a[0];
	if ($mifecha=='--') $b='00/00/0000';
return $b;
}
?>