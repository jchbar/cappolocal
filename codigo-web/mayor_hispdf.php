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
require('fpdf/mysql_table.php');
include("fpdf/comunes.php");
// include ("conex.php"); 

$pdf=new PDF('P','mm','Letter');
$pdf->Open();
$pdf->AddPage();

/*
$pdf->SetFillColor(255,255,255);
$pdf->SetFont('Arial','B',14);
$pdf->SetY(50);
$pdf->SetX(0);
$pdf->MultiCell(220,6,"Planilla de Pre-Inscripción",0,C,0);//

$pdf->Ln();    
*/
$linea=25;
$pdf->SetY($linea);
$pdf->SetX(0);
$pdf->SetFont('Arial','B',11);
$pdf->MultiCell(0,0,"Mayor Analítico",0,C,0);
$pdf->SetY($linea);
$pdf->SetFont('Arial','',8);
$linea+=5;
$pdf->SetX(165);
$pdf->Cell(20,0,'             '.date('d/m/Y'),0,0,'C'); 
//$pdf->Cell(20,0,'             '.date('d/m/Y h:i A'),0,0,'C'); 
$linea+=5;
$pdf->SetY($linea);
$pdf->SetX(10);
//Colores, ancho de línea y fuente en negrita
$pdf->SetFillColor(200,200,200);
$pdf->SetTextColor(0);
$pdf->SetDrawColor(0,0,0);
$pdf->SetLineWidth(.2);
$pdf->SetFont('Arial','B',8);

$anito=substr($fechai,2,2);
$anocompleto='20'.substr($fechai,2,2);
$sql="SELECT DISTINCT * FROM histf810 WHERE cue_codig2 = '$anito$cuenta'";
$result = mysql_query($sql); 
// echo $sql;
$cuento=($fechai?' Desde '.$fechai.' Hasta '.$fechaf:'');
while ($fila = mysql_fetch_assoc($result)) :
	if ($fila[cue_codigo] = $cuenta) {
		$linea+=5;
		$pdf->SetY($linea);
		$pdf->SetX(30);
		$pdf->Cell(100,7,"Cuenta: ".$cuenta." / ".$fila[cue_nombre].$cuento,0,0,'L',0);
		$misaldo=calcular_saldo_local($fila,$fechai);
//		break;
//		exit;
	}

endwhile;


//Cabecera
		$linea+=5;
		$pdf->SetY($linea);
$header=array('Item','Fecha','Asiento','Concepto','Refer.','Debe','Haber','Saldo');
$w=array(20,20, 25, 25, 25, 25, 25, 25);
$p=array(10,20,35,55,110,125, 150, 175);
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
// $misaldo=calcular_saldo_local($fila,$fechai);
if (!$fechai) 
	$sql="SELECT * FROM histf820 WHERE com_cuenta = '$cuenta' ORDER by com_fecha, com_refere";
else {
	$lfi=$fechai;
	$lff=$fechaf;
	$lafi = $lfi; // explode("/",$lfi);
//	$lafi = $lafi[2].'-'.$lafi[1].'-'.$lafi[0];
	$laff = $lff; // explode("/",$lff);
//	$laff = $laff[2].'-'.$laff[1].'-'.$laff[0];
	$sql="SELECT * FROM histf820 WHERE com_cuenta = '$cuenta' AND ((com_fecha >= '$lafi') AND (com_fecha <= '$laff')) ORDER by com_fecha, com_refere";
	}
	// echo $sql;
$result = mysql_query($sql);
if (mysql_num_rows($result) == 0) {
	$linea+=5;
	$pdf->SetY($linea);
	$pdf->SetX($p[6]);
	$pdf->Cell($w[6],6,'Saldo Inicial',0,0,'R',0);
	$pdf->SetX($p[7]);
	$pdf->Cell($w[7],7,number_format($misaldo*$_SESSION['moneda'],$_SESSION['deci'],',','.'),0,0,'R',0);
	$linea+=5;
	$pdf->SetY($linea);
	$pdf->Cell(10,7,'No hay registros para el Nº de Cuenta '.$cuenta,0,0,'L',0);
	$pdf->Output();
	break;
}

/* ****************** CABECERA ************************* */
$linea+=5;
$pdf->SetY($linea);
$pdf->SetX($p[6]);
$pdf->Cell($w[6],6,'Saldo Inicial',0,0,'R',0);
$pdf->SetX($p[7]);
$pdf->Cell($w[7],7,number_format($misaldo*$_SESSION['moneda'],$_SESSION['deci'],',','.'),0,0,'R',0);
$debe=$haber=$item=0;
$debem=$haberm=$mesactual=0;
/* ****************** APUNTES ************************** */
while ($fila = mysql_fetch_array($result)) :

	$a=explode("-",$fila["com_fecha"]); 
	if (($mesactual!=$a[1]) && ($mesactual != 0)) {
		$linea+=4;
		$pdf->SetY($linea);
		$pdf->SetX($p[4]);
		$pdf->Cell($w[4],7,"Total Movimientos : ".$mesactual.'/'.$a[0],0,0,'R',0);
		$pdf->SetX($p[5]);
		$pdf->Cell($w[5],7,number_format($debem*$_SESSION['moneda'],$_SESSION['deci'],',','.'),0,0,'R',0);
		$pdf->SetX($p[6]);
		$pdf->Cell($w[6],7,number_format($haberm*$_SESSION['moneda'],$_SESSION['deci'],',','.'),0,0,'R',0);
		$mesactual=$a[1];
		$debem=$haberm=0;
	}
	$item++;
	$linea+=4;
	$pdf->SetY($linea);
	$pdf->SetX($p[0]);
	$pdf->Cell($w[0],7,$item,0,0,'C',0);
	$pdf->SetX($p[1]);
	$pdf->Cell($w[1],7,$a[2]."/".$a[1]."/".substr($a[0],2,2),0,0,'C',0);
	if ($mesactual==0) $mesactual=$a[1];
	$pdf->SetX($p[2]);
	$pdf->Cell($w[2],7,$fila["com_nrocom"],0,0,'L',0);
	$pdf->SetX($p[3]);
	$pdf->Cell($w[3],7,$fila["com_descri"],0,0,'L',0);
	$pdf->SetX($p[4]);
	$pdf->Cell($w[4],7,$fila["com_refere"],0,0,'L',0);
	if ($fila["com_monto1"] == 0)
	{
		$pdf->SetX($p[5]);
	// 	$pdf->Cell($w[5],7,number_format(0*$_SESSION['moneda'],$_SESSION['deci'],',','.'),0,0,'R',0);
	} else {
		$pdf->SetX($p[5]);
		$pdf->Cell($w[5],7,number_format($fila["com_monto1"]*$_SESSION['moneda'],$_SESSION['deci'],',','.'),0,0,'R',0);
		$misaldo+=$fila[com_monto1];
		$debe+=$fila[com_monto1];
		$debem+=$fila[com_monto1];
	}
	if ($fila["com_monto2"] == 0)
	{
		$pdf->SetX($p[6]);
		// $pdf->Cell($w[6],7,number_format(0*$_SESSION['moneda'],$_SESSION['deci'],',','.'),0,0,'R',0);
	} else {
		$pdf->SetX($p[6]);
		$pdf->Cell($w[6],7,number_format($fila["com_monto2"]*$_SESSION['moneda'],$_SESSION['deci'],',','.'),0,0,'R',0);
		$misaldo-=$fila[com_monto2];
		$haber+=$fila[com_monto2];
		$haberm+=$fila[com_monto2];
	}
	$pdf->SetX($p[7]);
	$pdf->Cell($w[7],7,number_format($misaldo*$_SESSION['moneda'],$_SESSION['deci'],',','.'),0,0,'R',0);
	if ($linea >= 240)
	{
		$pdf->AddPage();
		$linea=25;
		$pdf->SetY($linea);
		$pdf->SetX(0);
		$pdf->SetFont('Arial','B',11);
		$pdf->MultiCell(0,0,"Mayor Analítico",0,C,0);
		$pdf->SetY($linea);
		$pdf->SetFont('Arial','',8);
		$linea+=5;
		$pdf->SetX(165);
		$pdf->Cell(20,0,'             '.date('d/m/Y'),0,0,'C'); 
		$linea+=5;
		$pdf->SetY($linea);
		$pdf->SetX(10);
		//Colores, ancho de línea y fuente en negrita
		$pdf->SetFillColor(200,200,200);
		$pdf->SetTextColor(0);
		$pdf->SetDrawColor(0,0,0);
		$pdf->SetLineWidth(.2);
		$pdf->SetFont('Arial','B',8);
	
//		$result = mysql_query("SELECT DISTINCT * FROM sgcaf810 WHERE cue_codigo = '$cuenta'"); 
//		$cuento=($fechai?' Desde '.$fechai.' Hasta '.$fechaf:'');
//		while ($fila = mysql_fetch_assoc($result)) :
//			if ($fila[cue_codigo] = $cuenta) {
				$linea+=5;
				$pdf->SetY($linea);
				$pdf->SetX(30);
				$pdf->Cell(100,7,"Cuenta: ".$cuenta." / ".$fila[cue_nombre].$cuento,0,0,'L',0);
//			break;
//		}

//		endwhile;

		//Cabecera
		$linea+=5;
		$pdf->SetY($linea);
		for($j=0;$j<count($header);$j++)  {
			$pdf->SetX($p[$j]);
		    $pdf->Cell($w[$j],7,$header[$j],0,0,'C',0); }
		$pdf->Ln();
	
		//Restauración de colores y fuentes

		$pdf->SetFillColor(255,255,255);
		$pdf->SetTextColor(0);
		$pdf->SetFont('Arial','',7);
	}
	
endwhile;

/* fin de mes */
$linea+=4;
$pdf->SetY($linea);
$pdf->SetX($p[4]);
$pdf->Cell($w[4],7,"Total Movimientos : ".$mesactual.'/'.$a[0],0,0,'R',0);
$pdf->SetX($p[5]);
$pdf->Cell($w[5],7,number_format($debem*$_SESSION['moneda'],$_SESSION['deci'],',','.'),0,0,'R',0);
$pdf->SetX($p[6]);
$pdf->Cell($w[6],7,number_format($haberm*$_SESSION['moneda'],$_SESSION['deci'],',','.'),0,0,'R',0);

/* ****************** SUMAS Y FIN DE TABLA*************** */
$linea+=4;
$pdf->SetY($linea);
$pdf->SetX($p[4]);
$pdf->Cell($w[4],7,"Totales: ".$mesactual.'/'.$a[0],0,0,'R',0);
$pdf->SetX($p[5]);
$pdf->Cell($w[5],7,number_format($debe*$_SESSION['moneda'],$_SESSION['deci'],',','.'),0,0,'R',0);
$pdf->SetX($p[6]);
$pdf->Cell($w[6],7,number_format($haber*$_SESSION['moneda'],$_SESSION['deci'],',','.'),0,0,'R',0);
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

function calcular_saldo_local($registro,$fechai)
{
	$a=explode("/",$fechai); 
	$elmesi=$a[1];
	$elsaldo=$registro['cue_saldo'];
//	echo 'el saldo'.$elsaldo;
	if ((! $fechai)) //  or ($elmes = '1'))
		return ($elsaldo);
/*	for ($i=1; $i<$elmesi; $i++)
	{
		if ($i<10) $mes='0'.$i; else $mes=$i;
		$debe='$registro["cue_deb'.$mes.'"]';
		$debe='$registro["cue_cre'.$mes.'"]';
		echo $debe; // $registro."['".$debe."']";
		$elsaldo+=$debe; // $registro["'".$debe."'"];
		$elsaldo-=$haber;
		echo $elsaldo."<br>";
	} 
*/
	$meses=$debe=$haber=0;
/*
	foreach ($registro as $indice => $valor) {
//		echo "$registro[$indice]";
//		echo "indice =".$indice;
//		echo "valor =".$valor;
	if (substr($indice,0,7)=='cue_deb') $meses++;
	if ($meses < $elmesi ) {
// 		echo $meses.'-'.$elmesi.'/'."<br>";
		if (substr($indice,0,7)=='cue_deb') $debe+=$valor;  // echo $valor; }
			elseif (substr($indice,0,7)=='cue_cre') $haber+=$valor; // echo $haber; }
			}
	}
	$elsaldo=$elsaldo+($debe-$haber);
*/	
return $elsaldo;
}

?> 
