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
if (strtoupper($orden)=='CODIGO')
	$orden='cod_prof';
else if (strtoupper($orden)=='CEDULA')
	$orden='ced_prof';
else if (strtoupper($orden)=='NOMBRE')
	$orden='nombre';
else $orden='ubic_prof';
*/

$sql="select (@a:=@a+1) as linea, escuela, cod_prof, ced_prof, nombre from (";
$sql.="SELECT @a :=0, escuela, cod_prof, ced_prof, concat( trim( ape_prof ) , ' ', nombr_prof ) AS nombre FROM sgcaf200 where (";
// if ($activos == 1)
	$sql.='(upper(statu_prof)="ACTIVO" or ';
// if ($jubilados == 1)
	$sql.='upper(statu_prof)="JUBILA") and ';
$sql.='(tipo_socio = "P" or tipo_socio = "E") ';
$sql.=') order by nombre )Tabla1'; // . " limit 30"; //  limit 20";

/*
SELECT (
@n := @n +1
)linea, escuela, cod_prof, ced_prof, nombre
FROM (

SELECT @n :=0, escuela, cod_prof, ced_prof, concat( trim( ape_prof ) , ' ', nombr_prof ) AS nombre
FROM sgcaf200
WHERE (
statu_prof = "Activo"
OR statu_prof = "Jubila"
AND (tipo_socio = "P" or tipo_socio = "E")
)
ORDER BY escuela, nombre
)Tabla1
*/

// echo $sql;
$asocio=mysql_query($sql);
$columna=3;
$rpl=40; 	// registros por listado
$rp=0;		// registros por pagina
$crl=0;		// contador de registros por listado
$col_listado=0;
$header[0]='Linea';
$header[1]='Codigo';
$header[2]='Cedula';
$header[3]='Apellidos y Nombres';
/*
$header[4]='Codigo';
$header[5]='Nombre Fiador';
$header[6]='Monto';
$header[7]='Saldo';
*/
$alto=5;
$salto=$alto;
$w=array(25,23,35,110); // ,15,50,15,15); 
$p[0]=10;
for ($posicion=1;$posicion<count($w);$posicion++) 
	$p[$posicion]=$p[$posicion-1]+$w[$posicion-1];

$pdf=new PDF('P','mm','Letter');
$pdf->Open();
$sintitulo=false;
$primeravez = true;
/*
$rsocio = mysql_fetch_assoc($asocio);
$np=$rsocio['escuela'];
$sqlesc="select * from escuelas order by nombre";
$aesc=mysql_query($sqlesc);
while ($rescuela = mysql_fetch_assoc($aesc)){
	$np=$rescuela['codigo'];
	$rp = 0;
	$cont=0;
	mysql_data_seek ($asocio, 0);		// volver al principio de la busqueda
*/
	while ($rsocio = mysql_fetch_assoc($asocio)){
//		if ($np==$rsocio['escuela']) {
			if ($rp == 0)
				$linea=encabeza_l_prestamos($header,$w,$p,$pdf,$salto,$alto,$np);
			$rp++;
			$linea+=$salto;
			$cont++;
			$pdf->SetY($linea);
			$pdf->SetX($p[0]);		$pdf->Cell($w[0],$alto,ceroizq(trim($rsocio['linea']),5),0,0,'C',0);
			$pdf->SetX($p[1]);		$pdf->Cell($w[1],$alto,$rsocio["cod_prof"],0,0,'C',0); 
			$pdf->SetX($p[2]);		$pdf->Cell($w[2],$alto,$rsocio["ced_prof"],0,0,'C',0);  
			$pdf->SetX($p[3]);		$pdf->Cell($w[3],$alto,$rsocio["nombre"],0,0,'L');
//		}

/*
	else {

		$linea+=$alto;
		$pdf->SetY($linea);
		$pdf->SetX($p[0]);
		$pdf->Cell(0,0,'  ',1,0,'L',0);
		$pdf->SetX($p[3]);		$pdf->Cell($w[3],$alto,'Total Electores en este Decanato '.number_format($cont,0,'.',','),0,0,'L');
		$linea=encabeza_l_prestamos($header,$w,$p,$pdf,$salto,$alto,$rsocio);
		$sintitulo=false;
		$linea-=$alto;
		$rp=0;
		$cont=1;

		$linea+=$salto;
		$linea+=$salto;
		$pdf->SetY($linea);
		$np=$rsocio['escuela'];
		$pdf->SetX($p[0]);		$pdf->Cell($w[0],$alto,ceroizq(trim($rsocio['linea']),5),0,0,'C',0);
		$pdf->SetX($p[1]);		$pdf->Cell($w[1],$alto,$rsocio["cod_prof"],0,0,'C',0); 
		$pdf->SetX($p[2]);		$pdf->Cell($w[2],$alto,$rsocio["ced_prof"],0,0,'C',0);  
		$pdf->SetX($p[3]);		$pdf->Cell($w[3],$alto,$rsocio["nombre"],0,0,'L');
	}
*/
		if ($rp >= $rpl) // linea>=($rpl*$alto)) {
		{
			$linea+=$alto;
			$pdf->SetY($linea);
			$pdf->SetX($p[0]);
			$pdf->Cell(0,0,'  ',1,0,'L',0);
			$linea=encabeza_l_prestamos($header,$w,$p,$pdf,$salto,$alto,$np);
			$sintitulo=false;
			$rp=1;
		}
	}
	if ($rp > 0) {
		$linea+=$alto;
		$pdf->SetY($linea);
		$pdf->SetX($p[0]);
		$pdf->Cell(0,0,'  ',1,0,'L',0);
		$pdf->SetX($p[3]);		$pdf->Cell($w[3],$alto,'Total Electores '.number_format($cont,0,'.',','),0,0,'L');
		$linea+=$alto;
		$pdf->SetY($linea);
	}
// }

/*
$pdf->SetX($p[05]);		$pdf->Cell($w[05],$alto,'Totales',0,0,'R');
$pdf->SetX($p[06]);		$pdf->Cell($w[06],$alto,number_format($tfianza,2,'.',','),0,0,'R');
$pdf->SetX($p[07]);		$pdf->Cell($w[07],$alto,number_format($tsaldo,2,'.',','),0,0,'R');
// $pdf->SetX($p[12]);		$pdf->Cell($w[12],$alto,number_format($tdisponible,2,'.',','),0,0,'R');
*/
$pdf->Output();
set_time_limit(30);
// $pdf->SetX($p[6]);		$pdf->Cell($w[6],$alto,number_format($tcuota,2,'.',','),0,0,'R');

////////////////////////////////////////////////////
function encabeza_l_prestamos($header,$w,$p,&$pdf,$salto,$alto,$np)
{
$pdf->AddPage();
$linea=25;
$pdf->SetY($linea);
$pdf->SetX(0);
$pdf->SetFont('Arial','B',14);
$hoy = date("d")."/".date('m')."/".date("Y"); 
$pdf->MultiCell(0,0,"Listado de General de Votacion al ".$hoy,0,C,0);
$pdf->SetY($linea);
$linea+=$alto;
$pdf->SetY($linea);
$pdf->SetFont('Arial','B',10);
$pdf->SetX(10);
/*
$decanato=$np; // $rsocio['escuela'];
$sqld="select nombre from escuelas where codigo='$decanato'";
// echo $sqld;
$adec=mysql_query($sqld);
$rdec=mysql_fetch_assoc($adec);
$pdf->Cell(200,$alto,'Decanato: '.$rdec['nombre'],0,0,'L',0);
//  $linea+=$alto;
$pdf->SetX(220);
// $pdf->Cell(20,0,'Realizado el '.date('d/m/Y h:i A'),0,0,'L'); 
//Títulos de las columnas
*/
/*
$linea+=$alto;
$pdf->SetY($linea);
$pdf->SetFont('Arial','B',10);
$pdf->SetX($p[0]);
$pdf->Cell(0,$alto,$nombreprestamo,0,0,'L',0);
$pdf->SetFont('Arial','',7);
*/
// $linea+=$alto;
// $pdf->SetY($linea);
//$header=array($$arrtitulo);
//Colores, ancho de línea y fuente en negrita
$pdf->SetFillColor(200,200,200);
$pdf->SetTextColor(0);
$pdf->SetDrawColor(0,0,0);
$pdf->SetLineWidth(.2);
$pdf->SetFont('Arial','B',12);
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
$pdf->SetFont('Arial','',10);
/*
$linea+=$salto;
$linea+=$salto;
$pdf->SetY($linea);
$pdf->SetX($p[0]);
$pdf->Cell(0,0,'  ',1,0,'L',0);
*/
return $linea;
}
?>
