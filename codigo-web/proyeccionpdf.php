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
	
*/

session_start();
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
/*
$pdf->MultiCell(0,0,$eltitulo,0,C,0);
$pdf->SetY($linea);
$pdf->SetFont('Arial','',8);
$linea+=5;
$pdf->SetX(165);
$pdf->Cell(20,0,'Realizada el '.date('d/m/Y h:i A'),0,0,'C'); 
*/
$pdf->SetFont('Arial','',10);
if ($prestamo!='NO')
{
	$consulta = "select * from sgcaf310, sgcaf360, sgcaf200 where (nropre_sdp='$prestamo') and (codpre_sdp=cod_pres) and (codsoc_sdp=cod_prof)";
	$query = mysql_query($consulta);
	$prestamo = mysql_fetch_assoc($query);

	$lafecha=$prestamo['f_1cuo_sdp'];
	$ncuotas=$prestamo['nrocuotas'];
	$monto=$prestamo['monpre_sdp'];
	$_SESSION['monto']=$_elmonto=$c=$monto;
	$_SESSION['cuotas']=$ncuotas;
	$interes=$prestamo['interes_sd'];
//	$concepto='Proyeccion de Pago ';	// falta colocar con intereses amortizables
	if ($lafecha < '2013-07-01') 
		if ($prestamo['int_dif'] == 1)
			$concepto.='Descontada';
		else $concepto.='Amortizada';
	else $concepto.='Amortizada';

	$z=cal_int($interes,$ncuotas,$c,24,0,$i2); // calcular aqui luego la descontada
	if ($concepto == 'Amortizada')
		$z=cal_int($interes,$ncuotas,$c,24,0,$i2); // calcular aqui luego la descontada
	else 
		$z=$monto/$ncuotas;
	$original=$z;
	$_SESSION['socio']=trim($prestamo['cedsoc_sdp']) .' '.trim($prestamo['ape_prof']) . trim($prestamo['nombr_prof']) . ' / ' .trim($prestamo['descr_pres']) .'('.trim($prestamo['cod_pres']) .') /' .trim($prestamo['nropre_sdp']) ;
	$titulo='Proyeccion de Pago ('.$concepto.')';
	
/*
	$estacedula=substr($cedula,0,4).'.'.substr($cedula,4,3).'.'.substr($cedula,7,3);
	$sqlretiro = "select * from sgcaf700 where cedsoc='$estacedula' order by fechareti desc limit 1";
	$qry = mysql_query($sqlretiro);
	$rretiro = mysql_fetch_assoc($qry);
*/
}
else 
{
	$titulo='Proyeccion de Pago ('.$concepto.')';
	$lafecha=$_GET['fecha'];
	$ncuotas=$_GET['ncuotas'];
	$monto=$_GET['monto'];
	$_SESSION['monto']=$_elmonto=$c=$monto;
	$_SESSION['cuotas']=$ncuotas;
	$interes=$_GET['interes']; // / 100;
	$concepto = $_GET['concepto'];
	$z=cal_int($interes,$ncuotas,$c,24,0,$i2); // calcular aqui luego la descontada
	if ($concepto == 'Amortizada')
		$z=cal_int($interes,$ncuotas,$c,24,0,$i2); // calcular aqui luego la descontada
	else 
		$z=$monto/$ncuotas;
	$original=$z;
}

	$z= $original; // $monto/$ncuotas;
	$_lacuota=$z;
	$k = 0;	         // k = contador
	$ia = 0;         // ia = interes acumulado
	$cu = 0;     // cu = cuota
	$ac = 0;     // ac = acumulado
	$tc = $z;     // tc = total cuota
	$ta = 0;     // ta = total acumulado
	$c1 = $c;     //  i1 = interes

$header[0]='N°';
$header[1]='Fecha';
$header[2]='Saldo';
$header[3]='Amort.Cuota';
$header[4]='Amort.Acum.';
$header[5]='Interes';
$header[6]='Int.Acum.';
$header[7]='Pagos Acum.';
$alto=3;
$salto=$alto;
$w=array(8,20,20,20,20,20,20,20); // ,25,25,25,25,25,25);
$p[0]=30;
for ($posicion=1;$posicion<count($w);$posicion++) 
	$p[$posicion]=$p[$posicion-1]+$w[$posicion-1];
//$p=array(10,18,31,36,76,91,106,131,136,161,196,221,246);

for ($m=0;$m<$ncuotas;$m++) 
{
	$k = $k + 1;
	$i1 = $c1 * $i2;
	$cu = $z - $i1;
	$c1 = $c1 - $cu;
	$ia = $ia + $i1;
	$ac = $ac + $cu;
	$ta = $ta + $z;
}
$losintereses=$ia;
$z= $original; // $monto/$ncuotas;
$_lacuota=$z;
$k = 0;	         // k = contador
$ia = 0;         // ia = interes acumulado
$cu = 0;     // cu = cuota
$ac = 0;     // ac = acumulado
$tc = $z;     // tc = total cuota
$ta = 0;     // ta = total acumulado
$c1 = $c;     //  i1 = interes

$pdf=new PDF('P','mm','Letter');
$pdf->Open();
$linea=encabeza_l_proyeccion($header,$w,$p,$pdf,$salto,$alto,$titulo,$original, $losintereses,$interes);
	for ($m=0;$m<$ncuotas;$m++) 
	{
		$k = $k + 1;
		$i1 = $c1 * $i2;
		$cu = $z - $i1;
		$c1 = $c1 - $cu;
		$ia = $ia + $i1;
		$ac = $ac + $cu;
		$ta = $ta + $z;

		$linea+=$salto;
		$pdf->SetY($linea);
		$cont++;
		$pdf->SetX($p[0]);		$pdf->Cell($w[0],$alto,$k,0,0,'LRTB',0);
		$pdf->SetX($p[1]);		$pdf->Cell($w[1],$alto,convertir_fechadmy($lafecha),0,0,'LRTB',0);  
		$pdf->SetX($p[2]);		$pdf->Cell($w[2],$alto,number_format($c1,2,".",","),0,0,'R');
		$pdf->SetX($p[3]);		$pdf->Cell($w[3],$alto,number_format($cu,2,".",","),0,0,'R');
		$pdf->SetX($p[4]);		$pdf->Cell($w[4],$alto,number_format($ac,2,".",","),0,0,'R');
		$pdf->SetX($p[5]);		$pdf->Cell($w[5],$alto,number_format($i1,2,".",","),0,0,'R');
		$pdf->SetX($p[6]);		$pdf->Cell($w[6],$alto,number_format($ia,2,".",","),0,0,'R');
		$pdf->SetX($p[7]);		$pdf->Cell($w[7],$alto,number_format($ta,2,".",","),0,0,'R');

		$sql="select date_add('$lafecha',INTERVAL 7 DAY) as fecha";
		$rsql=mysql_query($sql);
		$asql=mysql_fetch_assoc($rsql);
		$lafecha=($asql['fecha']);

		if ($linea>=250) {
			$linea+=$alto;
/*
			$pdf->SetY($linea);
			$pdf->SetX($p[0]);
			$pdf->Cell($p[0]+$p[1]+$p[2],0,'  ',1,0,'L',0);
*/
			$linea=encabeza_l_proyeccion($header,$w,$p,$pdf,$salto,$alto,$titulo,$original, $losintereses, $interes);
//			$linea-=$alto;
		}
}

$pdf->Output();
$_SESSION['socio']='';
set_time_limit(30);
// $pdf->SetX($p[6]);		$pdf->Cell($w[6],$alto,number_format($tcuota,2,'.',','),0,0,'R');

////////////////////////////////////////////////////
function encabeza_l_proyeccion($header,$w,$p,&$pdf,$salto,$alto,$titulo,$z, $ia, $interes)
{
$pdf->AddPage();
$linea=25;
$pdf->SetY($linea);
$pdf->SetX(0);
$pdf->SetFont('Arial','B',14);
$hoy = date("d")."-".date('m')."-".date("Y"); 
$hoy = convertir_fechadmy($_GET['desde']);
$pdf->MultiCell(0,0,$titulo,0,'C',0);
$pdf->SetY($linea);
$pdf->SetFont('Arial','',7);
if ($_SESSION['socio'] != '')
{
	$linea+=5;
	$pdf->SetY($linea);
	$pdf->MultiCell(0,0,$_SESSION['socio'],0,'C',0);
	if (substr($titulo,-12)=='(Descontada)')
		$neto=$_SESSION['monto']-$ia;
	else $neto=$_SESSION['monto'];
	$linea+=5;
	$pdf->SetY($linea);
	$pdf->MultiCell(0,0,'Neto Deposito = '.number_format($neto,2,'.',','),0,'C',0);
}
$linea+=5;
$pdf->SetY($linea);

// $pdf->SetX(220);
$pdf->SetX($p[0]);
$pdf->Cell($w[0],$alto,'Cuota =',0,0,'L',0);
$pdf->SetX($p[1]);
$pdf->Cell($w[1],$alto,number_format($z,2,".",","),0,0,'L',0);
$pdf->SetX($p[2]);
$pdf->Cell($w[2],$alto,'#Cuotas =',0,0,'L',0);
$pdf->SetX($p[3]);
$pdf->Cell($w[3],$alto,number_format($_SESSION['cuotas'],0,".",","),0,0,'L',0);
$pdf->SetX($p[4]);
$pdf->Cell($w[4],$alto,'Monto =',0,0,'L',0);
$pdf->SetX($p[5]);
$pdf->Cell($w[5],$alto,number_format($_SESSION['monto'],2,".",","),0,0,'L',0);
$pdf->SetX($p[6]);
$pdf->Cell($w[6],$alto,'Intereses ('.number_format($interes,2,".",",").'%)=',0,0,'L',0);
$pdf->SetX($p[7]+10);
$pdf->Cell($w[7],$alto,number_format($ia,2,".",","),0,0,'L',0);

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
$pdf->SetFont('Arial','',7);
/*
//Restauración de colores y fuentes
$pdf->SetFillColor(224,235,255);
$pdf->SetTextColor(0);
$linea+=$salto;
$linea+=$salto;
$pdf->SetY($linea);
$pdf->SetX($p[0]);
$pdf->Cell($p[0]+$p[1]+$p[2],0,'  ',1,0,'L',0);
*/
return $linea;
}

function convertir_fechadmy($mifecha)
{
//	$mifecha=strtotime($mifecha);
//	echo $mifecha;
	$a=explode("-",$mifecha); 
	$elano=substr($a[0],0,2);
	if ($elano="20") $b=$a[2]."/".$a[1]."/".$a[0];
	else $b=$a[2]."/".$a[1]."/"."20".$a[0];
//	if ($elano="20") $b=(($a[2]<10)?'0'.$a[2]:$a[2])."/".(($a[1]<10)?'0'.$a[1]:$a[1])."/".$a[0];
//	else $b=$b=(($a[2]<10)?'0'.$a[2]:$a[2])."/".(($a[1]<10)?'0'.$a[1]:$a[1])."/"."20".$a[0];
	if ($mifecha=='--') $b='00/00/0000';
return $b;
}

function cal_int($interes,$mcuotas,$mmonpre_sdp,$factor_divisible = 12,$z=0,&$i2)
{
	if ($interes > 0) {
		$i = ((($interes / 100)) / $factor_divisible);
		$i2 = $i;
		$i_ = 1 + $i;
		$i_ = pow($i_,$mcuotas); 	// exponenciacion 
		$i_ = 1 / $i_;
		$i__ = 1 - $i_;
		$i___ = $i / $i__;
		$z = $mmonpre_sdp * $i___;
	}
	if ($interes ==0)
		$z = $mmonpre_sdp / $mcuotas;
/*
	    ((1 + i)^n) - 1
	i =-----------------
	           i
*/
	return $z;
}


?>
