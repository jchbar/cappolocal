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
	header("location: noempresa.php");
	exit;
}


define('FPDF_FONTPATH','fpdf/font/');
ini_set('memory_limit','100M');
require('fpdf/mysql_table.php');
include("fpdf/comunes.php");
// include ("conex.php"); 

$pdf=new PDF('P','mm','Letter');
$pdf->Open();
$pdf->AddPage();

$linea=25;
$pdf->SetY($linea);
$pdf->SetX(0);
$pdf->SetFont('Arial','B',11);
$pdf->MultiCell(0,0,"Estado de Resultados a ".nombremes($elmes).'/'.$anoseleccionado.'. (Nivel Detalle '.$nivelseleccionado.')',0,C,0);
$pdf->SetY($linea);
$pdf->SetFont('Arial','',8);
$linea+=5;
$pdf->SetX(165);
$pdf->Cell(20,0,'             '.date('d/m/Y h:i A'),0,0,'C'); 
$linea+=5;
$pdf->SetY($linea);
$pdf->SetX(10);
//Colores, ancho de línea y fuente en negrita
$pdf->SetFillColor(200,200,200);
$pdf->SetTextColor(0);
$pdf->SetDrawColor(0,0,0);
$pdf->SetLineWidth(.2);
$pdf->SetFont('Arial','B',8);
	
//Cabecera
$header=array('Cod.Cuenta','Nombre Cuenta',' ',' ',' ',' ');
$w=array(20,70, 25, 25, 25, 25);
$p=array(10,30,75,100,125,150,175,200,225,250);
//$p=array(10,30,250,225,200,175,150,125,100);
for($j=0;$j<count($header);$j++)  {
	$pdf->SetX($p[$j]);
//	echo $pos[$j];
    $pdf->Cell($w[$j],7,$header[$j],0,0,'C',0); }
$pdf->Ln();
	
//Restauración de colores y fuentes

$pdf->SetFillColor(255,255,255);
$pdf->SetTextColor(0);
$pdf->SetFont('Arial','',7);

//Buscamos y listamos 
/*--------------------------------------------*/
	$nivelactual='1';
	$ultimonivel='';
	$posicion=$tdebe=$thaber=0;
	$sql="select count(con_nivel) as niveles from sgcafniv";
	$losniveles=mysql_query($sql) or die('Error 810-6<br>'.mysql_error());
	$nivel=mysql_fetch_assoc($losniveles);
	for ($i=0; $i<$nivel['niveles']; $i++) 
		{$arreglo[$i]=0;}
	$nivel=$nivel['niveles'];
	$codigo=$_SESSION['comando'];
	$result= mysql_query($codigo) or die('Error 810-5');
	while ($fila = mysql_fetch_assoc($result)) {
		if ($fila['cue_nivel']>$nivelactual) $nivelactual=$fila['cue_nivel'];
		$arreglo[$nivelactual-1]=$posicion;
		if ($nivelactual=='1')
			{
				// pagina nueva
			}
///----------------------

			if ($fila['cue_nivel'] < $nivelactual) 
			{
				$pos_actual=$posicion;
				$elnivel=$fila['cue_nivel'];
				$i=$elnivel;
				{
					mysql_data_seek($result,$arreglo[($i-1)]);
					$fila=mysql_fetch_assoc($result);
					if (($fila["cue_saldo"]!=0) || ($fila["danterior"]!=0) || ($fila['debe']!=0) || ($fila["hanterior"]!=0) || ($fila['haber']!=0))
					{
						$actual=$fila["cue_saldo"]+($fila["danterior"]+$fila['debe'])-($fila["hanterior"]+$fila['haber']);
						if ($actual != 0) {
							$linea+=5;
							$pdf->SetY($linea);
							$pdf->SetX($p[1]);
		  					$pdf->Cell($w[1],7,'Total '.$fila['cue_nombre'],0,0,'R',0);
							$espacio=($_SESSION['maxnivel']-$fila['cue_nivel'])+1;
							$pdf->SetX($p[$espacio]);
			  				$pdf->Cell($w[$espacio],7,number_format($actual,$deci,".",","),0,0,'R',0);
						}
					}
					mysql_data_seek($result,$pos_actual);		// vuelvo a la posicion original
					$fila=mysql_fetch_assoc($result);
					$nivelactual=$fila['cue_nivel'];
					$arreglo[$nivelactual-1]=$posicion;
				} // < $nivelactual
			}

///----------------------

		$actual=$fila["cue_saldo"]+($fila["danterior"]+$fila['debe'])-($fila["hanterior"]+$fila['haber']);
		if ($actual!=0) {
			if ($linea > 240) {
				$pdf->AddPage();
				$linea=25;
				$pdf->SetY($linea);
				$pdf->SetX(0);
				$pdf->SetFont('Arial','B',11);
				$pdf->MultiCell(0,0,"Estado de Resultados a ".nombremes($elmes).'/'.$anoseleccionado.'. (Nivel Detalle '.$nivelseleccionado.')',0,C,0);
				$pdf->SetY($linea);
				$pdf->SetFont('Arial','',8);
				$linea+=5;
				$pdf->SetX(165);
				$pdf->Cell(20,0,'             '.date('d/m/Y h:i A'),0,0,'C'); 
				$linea+=5;
				$pdf->SetY($linea);
				$pdf->SetX(10);
				//Colores, ancho de línea y fuente en negrita
				$pdf->SetFillColor(200,200,200);
				$pdf->SetTextColor(0);
				$pdf->SetDrawColor(0,0,0);
				$pdf->SetLineWidth(.2);
				$pdf->SetFont('Arial','B',8);
	
				//Cabecera
				$header=array('Cod.Cuenta','Nombre Cuenta',' ',' ',' ',' ');
				$w=array(20,70, 25, 25, 25, 25);
				$p=array(10,30,75,100,125,150,175,200,225,250);
				//$p=array(10,30,250,225,200,175,150,125,100);
				for($j=0;$j<count($header);$j++)  {
					$pdf->SetX($p[$j]);
				    $pdf->Cell($w[$j],7,$header[$j],0,0,'C',0); }
				$pdf->Ln();
	
				//Restauración de colores y fuentes

				$pdf->SetFillColor(255,255,255);
				$pdf->SetTextColor(0);
				$pdf->SetFont('Arial','',7);

			}
			$linea+=5;
			$pdf->SetY($linea);
			$pdf->SetX($p[0]);
 			$pdf->Cell($w[0],7,$fila["cue_codigo"],0,0,'L',0);
			$pdf->SetX($p[1]);
 			$pdf->Cell($w[1],7,$fila["cue_nombre"],0,0,'L',0);
			if ($fila['cue_nivel'] == $nivelseleccionado) 
			{
				$ultimonivel=$fila['cue_nivel'];
				$espacio=($_SESSION['maxnivel']-$fila['cue_nivel'])+1;
				$pdf->SetX($p[$espacio]);
	 			$pdf->Cell($w[$espacio],7,number_format($actual,$deci,".",","),0,0,'R',0);
				} 
			}
			
		$posicion++;
		}	// while
// final
				$pos_actual=$posicion;
				$elnivel=$fila['cue_nivel'];
				$i=1; // $elnivel;
				{
					mysql_data_seek($result,$arreglo[($i-1)]);
					$fila=mysql_fetch_assoc($result);
					if (($fila["cue_saldo"]!=0) || ($fila["danterior"]!=0) || ($fila['debe']!=0) || ($fila["hanterior"]!=0) || ($fila['haber']!=0))
					{
					$linea+=5;
					$pdf->SetY($linea);
					$pdf->SetX($p[1]);
		  			$pdf->Cell($w[1],7,'Total '.$fila['cue_nombre'],0,0,'R',0);
						$actual=$fila["cue_saldo"]+($fila["danterior"]+$fila['debe'])-($fila["hanterior"]+$fila['haber']);
						$pdf->SetX($p[$j]);
			  			$pdf->Cell($w[$j],7,number_format($actual,$deci,".",","),0,0,'R',0);
					}
				} // < $nivelactual


// total general
		$codigo="SELECT cue_codigo, cue_nombre, cue_nivel, cue_saldo, ";
		for ($elmes=1; $elmes < $messeleccionado; $elmes++) {
			$sumar='';
			if ($elmes<10) {$sumar='0'.$elmes; }else {$sumar=$elmes;}
			$codigo.='cue_deb'.$sumar;
			if ($elmes < ($messeleccionado-1)) $codigo.='+';
		}
		if ($messeleccionado == 1) 	$codigo.=" 0 as danterior, ";
		else $codigo.=" as danterior, ";
		for ($elmes=1; $elmes < $messeleccionado; $elmes++) {
			$sumar='';
			if ($elmes<10) $sumar='0'.$elmes; else $sumar=$elmes;
			$codigo.='cue_cre'.$sumar;
			if ($elmes < ($messeleccionado-1)) $codigo.='+';
		}
//		if ($messeleccionado < 10) $messeleccionado='0'.$messeleccionado;
		$debeactual='cue_deb'.$messeleccionado;
		$haberactual='cue_cre'.$messeleccionado;
		if ($messeleccionado == 1) 	$codigo.=" 0 as hanterior, ";
		else $codigo.=" as hanterior, ";
		$codigo.=$debeactual." as debe, ".$haberactual .
		" as haber from sgcaf810 where (cue_nivel='1') order by cue_codigo, cue_nivel";
// 		echo $codigo."<br>";
		$result= mysql_query($codigo) or die('Error 810-7<br>'.$codigo.'<br>'.mysql_error());
		$activo=$pasivo=$patrimonio=$ingresos=$egresos=0;
		while ($fila = mysql_fetch_assoc($result)) {
			$actual=$fila["cue_saldo"]+($fila["danterior"]+$fila['debe'])-($fila["hanterior"]+$fila['haber']);
			if (substr($fila['cue_codigo'],0,1)=='1') $activo=$actual; 
			if (substr($fila['cue_codigo'],0,1)=='2') $pasivo=$actual;
			if (substr($fila['cue_codigo'],0,1)=='3') $patrimonio=$actual;
			if (substr($fila['cue_codigo'],0,1)=='4') $ingresos=$actual;
			if (substr($fila['cue_codigo'],0,1)=='5') $egresos=$actual;
		}
		$linea+=5;
		$pdf->SetY($linea);
		$pdf->SetX($p[1]);
		$pdf->Cell($w[1],7,'Resultado del Ejercicio:',0,0,'R',0);
		$ultimo=$_SESSION['maxnivel'];
		$pdf->SetX($p[$ultimo-1]);
		$pdf->Cell($w[2],7,number_format($ingresos+$egresos,$deci,".",","),0,0,'R',0);
/*
		$linea+=5;
		$pdf->SetY($linea);
		$pdf->SetX($p[1]);
		$pdf->Cell($w[1],7,'Total Pasivo+Patrimonio+Resultado:',0,0,'R',0);
		$pdf->SetX($p[$ultimo]);
		$pdf->Cell($w[2],7,number_format($pasivo+$patrimonio+$ingresos+$egresos,$deci,".",","),0,0,'R',0);
		$linea+=5;
		$pdf->SetY($linea);
		$pdf->SetX($p[1]);
		$pdf->Cell($w[1],7,'Ecuación Patrimonial',0,0,'R',0);
		$pdf->SetX($p[$ultimo]);
		$pdf->Cell($w[2],7,number_format($activo+$pasivo+$patrimonio+$ingresos+$egresos,$deci,".",","),0,0,'R',0);
*/
$pdf->Output();

function nombremes($numeromes) {

$nmes = $numeromes;
$mes[1] = "Enero";
$mes[2] = "Febrero";
$mes[3] = "Marzo";
$mes[4] = "Abril";
$mes[5] = "Mayo";
$mes[6] = "Junio";
$mes[7] = "Julio";
$mes[8] = "Agosto";
$mes[9] = "Septiembre";
$mes[10] = "Octubre";
$mes[11] = "Noviembre";
$mes[12] = "Diciembre";

return $mes[$nmes];
}

?> 
