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
/*
$orden=$_GET['orden'];
$fecha=$_GET['desde'];
if (strtoupper($orden)=='CODIGO')
	$orden='cod_prof';
else if (strtoupper($orden)=='CEDULA')
	$orden='ced_prof';
else if (strtoupper($orden)=='NOMBRE')
	$orden='nombre';
else $orden='ubic_prof';

$sql="select ced_prof, concat(trim(ape_prof),' ',nombr_prof) as nombre, statu_prof, netcheque, ctan_prof from sgcaf200, sgcaf700 where (cod_prof=codsoc) and (aprobado =  '$fecha')"; //   limit 30";*/
$token=$_GET['orden'];
$sql="select * from sgcaf370, sgcaf200, sgcaf375 where (sgcaf370.nro_rec = '$token') and (sgcaf370.cod_prof = sgcaf200.cod_prof) and (sgcaf370.nro_rec = sgcaf375.nro_rec)";
$sql="select * from sgcaf370, sgcaf200 where (sgcaf370.nro_rec = '$token') and (sgcaf370.cod_prof = sgcaf200.cod_prof)";
 //  limit 20";
// echo $sql;
$asocio=mysql_query($sql);
$columna=3;
$rpl=300; 	// registros por listado
$crl=0;		// contador de registros por listado
$col_listado=0;
// $arrtitulo="'Lin.Nº','Código','Cédula','Apellidos y Nombres',";
$header[0]='Cuota N°';
$header[1]='Fecha';
$header[2]='Monto';
$header[3]='Amor.Cap.';
$header[4]='Amor.Acum.';
$header[5]='Interes';
$header[6]='Int.Acum.';
$header[7]='Pag.Acum.';
$alto=5;
$salto=$alto;
$w=array(15,20,20,20,20,20,20,20); // ,25,25,25,25,25,25);
$p[0]=30;
for ($posicion=1;$posicion<count($w);$posicion++) 
	$p[$posicion]=$p[$posicion-1]+$w[$posicion-1];

$pdf=new PDF('P','mm','Letter');
$pdf->Open();
$rsocio=mysql_fetch_assoc($asocio);
$linea=encabeza_l_prestamos($header,$w,$p,$pdf,$salto,$alto,$monto,$cuotas,$interes,$cuota,$pinteres,$rsocio,$token);
/*
$sql375="select * from sgcaf375 where nro_rec='$token'";
$arecibo=mysql_query($sql);
while ($rsocio = mysql_fetch_assoc($arecibo)){
	$linea+=$salto;
	$pdf->SetY($linea);
	$cont++;
	$pdf->SetX($p[0]);		$pdf->Cell($w[0],$alto,number_format($rsocio['nro_cta'],0,'',''),0,0,'R',0);
	$pdf->SetX($p[1]);		$pdf->Cell($w[1],$alto,convertir_fechadmy($rsocio["fechaa"]),0,0,'C',0);  
	$pdf->SetX($p[2]);		$pdf->Cell($w[2],$alto,number_format($rsocio["capital"],2,'.',','),0,0,'R',0);  
	$pdf->SetX($p[3]);		$pdf->Cell($w[3],$alto,number_format($rsocio["amor_cap"],2,'.',','),0,0,'R');
	$pdf->SetX($p[4]);		$pdf->Cell($w[4],$alto,number_format($rsocio["amor_ac"],2,'.',','),0,0,'R');
	$pdf->SetX($p[5]);		$pdf->Cell($w[5],$alto,number_format($rsocio["interes"],2,'.',','),0,0,'R');
	$pdf->SetX($p[6]);		$pdf->Cell($w[6],$alto,number_format($rsocio["interesa"],2,'.',','),0,0,'R');
	$pdf->SetX($p[7]);		$pdf->Cell($w[7],$alto,number_format($rsocio["interesa"]+$rsocio["amor_ac"],2,'.',','),0,0,'R');
	if ($linea>=250) {
		$linea+=$alto;
		$pdf->SetY($linea);
		$pdf->SetX($p[0]);
		$pdf->Cell($p[0]+$p[1]+$p[2],0,'  ',1,0,'L',0);
		$linea=encabeza_l_prestamos($header,$w,$p,$pdf,$salto,$alto,$monto,$cuotas,$interes,$cuota,$pinteres,$rsocio,$token);
		$sintitulo=false;
		$linea-=$alto;
		}
}
*/
/*
$linea+=$alto;
$pdf->SetY($linea);
$pdf->SetX($p[0]);
$pdf->Cell($p[0]+$p[1]+$p[2],0,'  ',1,0,'L',0);
$pdf->SetY($linea);
$pdf->SetX($p[0]);
$pdf->SetFont('Arial','B',7);
$pdf->SetX($p[2]);		$pdf->Cell($w[2]+$w[3],$alto,''.number_format($activos+$jubilados,0,'.',',').' Socios',0,0,'R');
$pdf->SetX($p[4]);		$pdf->Cell($w[4],$alto,number_format($monto,2,'.',','),0,0,'R');
$pdf->SetFont('Arial','',7);
*/
$linea+=($alto*6);
$pdf->SetY($linea);
$pdf->SetX(90);
$pdf->Cell(70,0,'',1,0,'C',0);
$linea+=($alto*1);
$pdf->SetY($linea);
$pdf->SetX(90);
$pdf->Cell(70,$alto,'Recibi Conforme',0,0,'C',0);

$pdf->Output();
set_time_limit(30);
// $pdf->SetX($p[6]);		$pdf->Cell($w[6],$alto,number_format($tcuota,2,'.',','),0,0,'R');

////////////////////////////////////////////////////
function encabeza_l_prestamos($header,$w,$p,&$pdf,$salto,$alto,$monto,$cuotas,$interes,$cuota,$pinteres,$elsocio,$elprestamo)
{
$pdf->AddPage();
$linea=25;
$pdf->SetY($linea);
$pdf->SetX(0);
$pdf->SetFont('Arial','B',18);
$hoy = date("d")."-".date('m')."-".date("Y"); 
$hoy = convertir_fechadmy($_GET['desde']);
$pdf->MultiCell(0,0,"Recibo de Pago Nro. ".$elprestamo,0,C,0);
$pdf->SetY($linea);
$linea+=$alto;
$linea+=$alto;
$pdf->SetY($linea);
$pdf->MultiCell(0,0,"Por Bs. ".number_format($elsocio['monto'],2,'.',','),0,C,0);
$pdf->SetY($linea);
$pdf->SetFont('Arial','',12);
$pdf->SetY($linea);
$pdf->SetX(170);
$pdf->Cell(20,0,''.date('d/m/Y h:i A'),0,0,'L'); 
$linea+=$alto;
$linea+=$alto;
$linea+=$alto;
$pdf->SetY($linea);
$procesado=convertir_fechadmy($elsocio['proceso']);
$hemos= 'Hemos recibido del socio(a) '.trim($elsocio['ape_prof']). ' '.trim($elsocio['nombr_prof']);
$hemos.=' portador(a) de la Cédula de Identidad '.$elsocio['ced_prof'] .' identificado(a) con el código ';
$hemos.=$elsocio['cod_prof'] .' la cantidad de Bs '.num2letras($elsocio['monto'],true). ' (Bs. ';
$hemos.=number_format($elsocio['monto'],2,'.',',').') por concepto de: '; // .$elsocio['descri1'] ;
$sql375="select * from sgcaf375, sgcaf310, sgcaf360 where nro_rec = '$elprestamo' and nropre_sdp=nro_pre and codpre_sdp=cod_pres";
//echo $sql375;
$result=mysql_query($sql375);
while ($r375 = mysql_fetch_assoc($result))
{
	$hemos.=' Pago a prestamo Nro. '.$r375['nro_pre']. ' de '.trim($r375['descr_pres']).' de Bs . '.number_format($r375['monto'],2,',','.').' / ';
	$forma=$r375['forma'];
	$ndeposito=$r375['nro_che'];
	$banco=$r375['banco'];
}
$sqlbanco='select nombre from sgcaf000 where tipo="FormaPago" and substr(nombre,1,1)="'.$forma.'"';
$resultado=mysql_query($sqlbanco);
$fila2 = mysql_fetch_assoc($resultado);
$forma=$fila2['nombre'];

$sqlbanco="select * from sgcaf843 where recibirpago=1 and cod_banco='$banco'";
$resultado=mysql_query($sqlbanco);
$fila2 = mysql_fetch_assoc($resultado);
$banco=$fila2['nombre_ban'];

$hemos.=' s/comprobante contable '.$_SESSION['elasiento'].' procesado el '.$procesado.' segun '.$forma .' numero '.$ndeposito .' en '.$banco;

$pdf->SetX(10);
// $pdf->Cell(20,$alto*8,$hemos,0,0,'L',0);
$pdf->MultiCell(0,$alto*2,$hemos,0,'L'); 

/*
$pdf->SetX($p[0]);
$pdf->Cell($w[0],$alto,'Bs.Prestamo',0,0,'L',0);
$pdf->SetX($p[1]);
$pdf->Cell($w[1],$alto,number_format($monto,2,'.',','),0,0,'R',0);
$pdf->SetX($p[3]);
$pdf->Cell($w[3],$alto,'Nro. Cuotas',0,0,'L',0);
$pdf->SetX($p[4]);
$pdf->Cell($w[4],$alto,number_format($cuotas,0,'.',','),0,0,'R',0);
$pdf->SetX($p[6]);
$pdf->Cell($w[6],$alto,'Bs.Cuota',0,0,'L',0);
$pdf->SetX($p[7]);
$pdf->Cell($w[7],$alto,number_format($cuota,2,'.',','),0,0,'R',0);

$linea+=$alto;
$pdf->SetY($linea);
$pdf->SetX($p[0]);
$pdf->Cell($w[0],$alto,'Intereses',0,0,'L',0);
$pdf->SetX($p[1]);
$pdf->Cell($w[1],$alto,number_format($interes,2,'.',','),0,0,'R',0);
$pdf->SetX($p[3]);
$pdf->Cell($w[3],$alto,'% Interes',0,0,'L',0);
$pdf->SetX($p[4]);
$pdf->Cell($w[4],$alto,number_format($pinteres,2,'.',','),0,0,'R',0);
$pdf->SetX($p[6]);
$pdf->Cell($w[6],$alto,'Neto a Recibir',0,0,'L',0);
$pdf->SetX($p[7]);
$pdf->Cell($w[7],$alto,number_format($monto-$interes,2,'.',','),0,0,'R',0);

$linea+=$alto;
$pdf->SetY($linea);
$pdf->SetX($p[0]);
$pdf->Cell($w[0],$alto,'Socio',0,0,'L',0);
$pdf->SetX($p[1]);
$pdf->Cell($w[1]+$w[2]+$w[3],$alto,$elsocio,0,0,'L',0);
$pdf->SetX($p[4]);
$pdf->Cell($w[4],$alto,'Tipo Pres.',0,0,'L',0);
$pdf->SetX($p[5]);
$pdf->Cell($w[5],$alto,$elprestamo,0,0,'L',0);
*/
$linea+=($alto*6);
//Títulos de las columnas
/*
$linea+=$alto;
$pdf->SetY($linea);
$pdf->SetFont('Arial','B',10);
$pdf->SetX($p[0]);
$pdf->Cell(0,$alto,$nombreprestamo,0,0,'L',0);
$pdf->SetFont('Arial','',7);
*/
/*
$linea+=$alto;
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
// $linea+=$salto;
$pdf->SetY($linea);
$pdf->SetX($p[0]);
$pdf->Cell($p[0]+$p[1]+$p[2]+15,0,'  ',1,0,'L',0);
*/
return $linea;
}
?>
