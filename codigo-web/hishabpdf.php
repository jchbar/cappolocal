<?php
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
$pdf=new PDF('L','mm','Letter');
$pdf->Open();
$linea=0;
if ($aportespagos == '1') {
	if ($accion == "fechaa") {
		$sql="Select cod_prof, hab_prof, hab_ucla, 0 as ret_opsu, descri, date_format(fecha, '%d/%m/%Y') AS fechax from fhis200 where cod_prof= '$codigo' and ((fecha >= '$lfi') AND (fecha<= '$lff') AND ('$lff'<= '2007-12-31')) ORDER by fecha";
		$sql1="CREATE TEMPORARY TABLE la4 select cod_prof as codsoc, hab_prof, hab_ucla, descri, fecha, date_format(fecha, '%d/%m/%Y') AS fechax from fhis200 where cod_prof= '$codigo' and ((fecha >= '$lfi') AND (fecha<= '$lff') AND ('$lff'<= '2007-12-31')) ";
		$sql2="CREATE TEMPORARY TABLE la5 select codsoc,   ret_capu as hab_prof, ret_ucla as hab_ucla, ret_opsu, 'Retiro de Haberes' as descri, fechareti as fecha, date_format(fechareti, '%d/%m/%Y') AS fechax  from sgcaf700 where codsoc='$codigo' and ((fechareti >= '$lfi') AND (fechareti<= '$lff') AND ('$lff'<= '2007-12-31'))";
		$f4=mysql_query($sql1) or die(mysql_error());
		$f5=mysql_query($sql2) or die(mysql_error());
		$sql3="CREATE TEMPORARY TABLE la6 select * from la4 union select * from la5 ";
		$f3=mysql_query($sql3) or die(mysql_error());

		$sql="select * from la6 order by fecha";		
		$result=mysql_query($sql);
		$sql="SELECT ape_prof, nombr_prof, ced_prof, cod_prof FROM sgcaf200 WHERE cod_prof= '$codigo'";
		$resultado=mysql_query($sql);
		$fila2 = mysql_fetch_assoc($resultado);
		encabezado($linea,$pdf,$fila2, $w, $p);
		$cuadro=0;
		$posicion=0;
		$alto=4;
		$linea+=$alto;
		mostrar($result, $pdf, $linea, $w, $p, $fila2);
/*
	while ($row = mysql_fetch_array($result))
//	echo $sql;
       {
	 //posicion celda, alto,contenido,bordes que mostramos(left,right top botton),0, alineacion izquierda,relleno
		 //imprimo nombre, apellidos y localidad
		 $pdf->SetY($linea);
		 $pdf->SetX($p[$posicion]);
		 $pdf->Cell($w[0],$alto,$row["fechax"],$cuadro,0,'C',0); 
		if ($row['descri']!='Retiro de Haberes')
		{
			$posicion++;
			$pdf->SetX($p[$posicion]);
			$pdf->Cell($w[1],$alto,number_format($row["hab_prof"],2,".",","),$cuadro,0,'R',0);  
			$posicion++;
			$pdf->SetX($p[$posicion]);
			$pdf->Cell($w[2],$alto,number_format($row["hab_ucla"],2,".",","),$cuadro,0,'R',0);
			$posicion++;
			$pdf->SetX($p[$posicion]);
			$pdf->Cell($w[3],$alto,trim($row["descri"]),$cuadro,0,'L',0);
			$posicion++;
			$posicion++;
		}
		else 
		{
			$posicion++;
			$posicion++;
			$posicion++;
			$cuadro=1;
			$pdf->SetX($p[$posicion]);
			$pdf->Cell($w[3],$alto,trim($row["descri"]),$cuadro,0,'L',0);
			$posicion++;
			$pdf->SetX($p[$posicion]);
			$pdf->Cell($w[4],$alto,number_format($row["hab_prof"]+$row['hab_ucla'],2,".",","),$cuadro,0,'R',0);
			$posicion++;
			$cuadro=0;
		}
		if ($posicion>8)
		{
			$posicion=0;
			 $linea+=$alto;
		}
		 if ($linea>=185){
			 encabezado($linea,$pdf,$fila2, $w, $p);
			 $linea+=$alto;
		 }

		 }
*/
}
}
/******************************************************************************************************************************/
else if ($aportespagos == '2'){
	if ($accion == "fechad") {
//	      $sql="Select cod_prof, hab_prof, hab_ucla, descri, date_format(fecha, '%d/%m/%Y') AS fechax from fhis200 where cod_prof= '$codigo' and ((fecha >= '$lfi') AND (fecha<= '$lff') AND ('$lfi'>= '2008-01-01')) ORDER by fecha";
//		$result=mysql_query($sql);

		$sql1="CREATE TEMPORARY TABLE la4 select cod_prof as codsoc, hab_prof, hab_ucla, descri, fecha, date_format(fecha, '%d/%m/%Y') AS fechax from fhis200 where cod_prof= '$codigo' and ((fecha >= '$lfi') AND (fecha<= '$lff') AND ('$lfi'> '2007-12-31')) ";
		$sql2="CREATE TEMPORARY TABLE la5 select codsoc,   ret_capu as hab_prof, ret_ucla as hab_ucla, 'Retiro de Haberes' as descri, fechareti as fecha, date_format(fechareti, '%d/%m/%Y') AS fechax  from sgcaf700 where codsoc='$codigo' and ((fechareti >= '$lfi') AND (fechareti<= '$lff') AND ('$lfi'> '2007-12-31'))";
		$f4=mysql_query($sql1) or die(mysql_error());
		$f5=mysql_query($sql2) or die(mysql_error());
		$sql3="CREATE TEMPORARY TABLE la6 select * from la4 union select * from la5 ";
		$f3=mysql_query($sql3) or die(mysql_error());

		$sql="select * from la6 order by fecha";		
		$result=mysql_query($sql);
		$sql="SELECT ape_prof, nombr_prof, ced_prof, cod_prof FROM sgcaf200 WHERE cod_prof= '$codigo'";
		$resultado=mysql_query($sql);
		$fila2 = mysql_fetch_assoc($resultado);
		encabezado($linea,$pdf,$fila2, $w, $p);
		$cuadro=0;
		$posicion=0;
		$alto=4;
		$linea+=$alto;
		mostrar($result, $pdf, $linea, $w, $p, $fila2);

/*
$resultado=mysql_query($sql);
$fila2 = mysql_fetch_assoc($resultado);
$linea+=2;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->SetFillColor(200,200,200);
$pdf->SetTextColor(0);
$pdf->SetDrawColor(0,0,0);
$pdf->SetLineWidth(.2);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(0,6,'DATOS DEL ASOCIADO',0,0,'C',1);
$linea+=7;
$pdf->SetFont('Arial','B',8);
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(32,6,'Nombre del Asociado:',0,0,'L',0);
$pdf->SetX(42);
$pdf->Cell(60,6,$fila2['ape_prof'].''.$fila2['nombr_prof'],0,0,'L',0);
$pdf->SetX(102);
$pdf->Cell(13,6,'Cédula:',0,0,'L',0);
$pdf->SetX(115);
$pdf->Cell(40,6,$fila2['ced_prof'],0,0,'L',0);
$pdf->SetX(155);
$pdf->Cell(13,6,'Código:',0,0,'L',0);
$pdf->SetX(168);
$pdf->Cell(32,6,$fila2['cod_prof'],0,0,'L',0);
$linea+=7;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->SetFillColor(200,200,200);
$pdf->SetTextColor(0);
$pdf->SetDrawColor(0,0,0);
$pdf->SetLineWidth(.2);
$pdf->SetFont('Arial','B',8);
$ts = $tu = 0;
$header=array('Fecha','Haberes Socio','Haberes Patrono','Descripción','Monto de Retiro','Fecha de Retiro');
//Colores, ancho de línea y fuente en negrita
    $pdf->SetFillColor(200,200,200);
    $pdf->SetTextColor(0);
    $pdf->SetDrawColor(0,0,0);
    $pdf->SetLineWidth(.2);
    $pdf->SetFont('Arial','B',7);
//Cabecera
    $w=array(20,25,25,70,25,25);
    for($i=i+n;$i<count($header);$i++)
	    $pdf->Cell($w[$i],7,$header[$i],1,0,'C',1);
		$pdf->Ln();
//Restauración de colores y fuentes
  $pdf->SetFillColor(200,200,200);
    $pdf->SetTextColor(0);
    $pdf->SetFont('Arial','',7);
	//Buscamos y listamos los proveedores
	 //posicion celda, alto,contenido,bordes que mostramos(left,right top botton),0, alineacion izquierda,relleno
		 //imprimo nombre, apellidos y localidad
//	echo 'orden '.$orden;
//	echo 'ord '.$ord;
//	$ord= $orden;
$linea+=3;
	while ($row = mysql_fetch_array($result))
//	echo $sql;
       {
	 //posicion celda, alto,contenido,bordes que mostramos(left,right top botton),0, alineacion izquierda,relleno
		 //imprimo nombre, apellidos y localidad
		 $linea+=5;
		 $pdf->SetY($linea);
		 $pdf->Cell($w[0],5,$row["fechax"],0,0,'C',0); 
		 $pdf->Cell($w[1],5,number_format($row["hab_prof"],2,".",","),0,0,'R',0);  
		 $pdf->Cell($w[2],5,number_format($row["hab_ucla"],2,".",","),0,0,'R',0);
		 $pdf->Cell($w[3],5,trim($row["descri"]),0,0,'L',0);
		 $ts+=$row ["hab_prof"];
		 $tu+=$row["hab_ucla"];
		 if ($linea>=245){
		 	$pdf->AddPage();
						$linea=25;
$linea=30;
$pdf->SetY($linea);
$pdf->SetX(0);
$pdf->SetFont('Arial','B',14);
$pdf->MultiCell(0,0,"H I S T O R I A L E S   D E   H A B E R E S",0,C,0);
$pdf->SetY($linea);
$pdf->SetFont('Arial','',7);
$linea+=5;
$pdf->SetX(165);
$pdf->Cell(20,0,'Realizada el '.date('d/m/Y h:i A'),0,0,'C'); 
$linea+=2;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->SetFillColor(200,200,200);
$pdf->SetTextColor(0);
$pdf->SetDrawColor(0,0,0);
$pdf->SetLineWidth(.2);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(0,6,'DATOS DEL ASOCIADO',0,0,'C',1);
$linea+=7;
$pdf->SetFont('Arial','B',8);
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(32,6,'Nombre del Asociado:',0,0,'L',0);
$pdf->SetX(42);
$pdf->Cell(60,6,$fila2['ape_prof'].''.$fila2['nombr_prof'],0,0,'L',0);
$pdf->SetX(102);
$pdf->Cell(13,6,'Cédula:',0,0,'L',0);
$pdf->SetX(115);
$pdf->Cell(40,6,$fila2['ced_prof'],0,0,'L',0);
$pdf->SetX(155);
$pdf->Cell(13,6,'Código:',0,0,'L',0);
$pdf->SetX(168);
$pdf->Cell(32,6,$fila2['cod_prof'],0,0,'L',0);
$linea+=7;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->SetFillColor(200,200,200);
$pdf->SetTextColor(0);
$pdf->SetDrawColor(0,0,0);
$pdf->SetLineWidth(.2);
$pdf->SetFont('Arial','B',8);
$header=array('Fecha','Haberes Socio','Haberes Patrono','Descripción','Monto de Retiro','Fecha de Retiro');
//Colores, ancho de línea y fuente en negrita
    $pdf->SetFillColor(200,200,200);
    $pdf->SetTextColor(0);
    $pdf->SetDrawColor(0,0,0);
    $pdf->SetLineWidth(.2);
    $pdf->SetFont('Arial','B',7);
//Cabecera
    $w=array(20,25,25,70,25,25);
    for($i=i+n;$i<count($header);$i++)
	    $pdf->Cell($w[$i],7,$header[$i],1,0,'C',1);
		$pdf->Ln();
//Restauración de colores y fuentes
  $pdf->SetFillColor(200,200,200);
    $pdf->SetTextColor(0);
    $pdf->SetFont('Arial','',7);
	$linea+=3;
	//Buscamos y listamos los proveedores
	 //posicion celda, alto,contenido,bordes que mostramos(left,right top botton),0, alineacion izquierda,relleno
		 //imprimo nombre, apellidos y localidad
//	echo 'orden '.$orden;
//	echo 'ord '.$ord;
//	$ord= $orden;
		 }
		 }
		$sql="select montoreti,date_format(fechareti, '%d/%m/%Y') AS fechaz FROM sgcaf700 WHERE codsoc='$codigo' ORDER BY fechareti";
$resultado=mysql_query($sql);
        //Restauración de colores y fuentes
  $pdf->SetFillColor(200,200,200);
    $pdf->SetTextColor(0);
    $pdf->SetFont('Arial','',7);
while($row1=mysql_fetch_assoc($resultado)) {
$linea+=5;
		 $pdf->SetY($linea);
		 $pdf->Cell($w[0],5,$row[""],0,0,'C',0); 
		 $pdf->Cell($w[1],5,$row [""],0,0,'R',0);  
		 $pdf->Cell($w[2],5,$row[""],0,0,'R',0);
		 $pdf->Cell($w[3],5,$row[""],0,0,'R',0);
         $pdf->Cell($w[4],5,number_format($row1["montoreti"],2,".",","),0,0,'R',0);
         $pdf->Cell($w[5],5,$row1["fechaz"],0,0,'R',0);		
		 if ($linea>=245){
		 	$pdf->AddPage();
						$linea=25;
$linea=30;
$pdf->SetY($linea);
$pdf->SetX(0);
$pdf->SetFont('Arial','B',14);
$pdf->MultiCell(0,0,"H I S T O R I A L E S   D E   H A B E R E S",0,C,0);
$pdf->SetY($linea);
$pdf->SetFont('Arial','',7);
$linea+=5;
$pdf->SetX(165);
$pdf->Cell(20,0,'Realizada el '.date('d/m/Y h:i A'),0,0,'C'); 
$linea+=2;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->SetFillColor(200,200,200);
$pdf->SetTextColor(0);
$pdf->SetDrawColor(0,0,0);
$pdf->SetLineWidth(.2);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(0,6,'DATOS DEL ASOCIADO',0,0,'C',1);
$linea+=7;
$pdf->SetFont('Arial','B',8);
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(32,6,'Nombre del Asociado:',0,0,'L',0);
$pdf->SetX(42);
$pdf->Cell(60,6,$fila2['ape_prof'].''.$fila2['nombr_prof'],0,0,'L',0);
$pdf->SetX(102);
$pdf->Cell(13,6,'Cédula:',0,0,'L',0);
$pdf->SetX(115);
$pdf->Cell(40,6,$fila2['ced_prof'],0,0,'L',0);
$pdf->SetX(155);
$pdf->Cell(13,6,'Código:',0,0,'L',0);
$pdf->SetX(168);
$pdf->Cell(32,6,$fila2['cod_prof'],0,0,'L',0);
$linea+=7;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->SetFillColor(200,200,200);
$pdf->SetTextColor(0);
$pdf->SetDrawColor(0,0,0);
$pdf->SetLineWidth(.2);
$pdf->SetFont('Arial','B',8);
$header=array('Fecha','Haberes Socio','Haberes Patrono','Descripción','Monto de Retiro','Fecha de Retiro');
//Colores, ancho de línea y fuente en negrita
    $pdf->SetFillColor(200,200,200);
    $pdf->SetTextColor(0);
    $pdf->SetDrawColor(0,0,0);
    $pdf->SetLineWidth(.2);
    $pdf->SetFont('Arial','B',7);
//Cabecera
    $w=array(20,25,25,70,25,25);
    for($i=i+n;$i<count($header);$i++)
	    $pdf->Cell($w[$i],5,$header[$i],1,0,'C',1);
		$pdf->Ln();
//Restauración de colores y fuentes
  $pdf->SetFillColor(200,200,200);
    $pdf->SetTextColor(0);
    $pdf->SetFont('Arial','',7);
	$linea+=3;
	//Buscamos y listamos los proveedores
	 //posicion celda, alto,contenido,bordes que mostramos(left,right top botton),0, alineacion izquierda,relleno
		 //imprimo nombre, apellidos y localidad
//	echo 'orden '.$orden;
//	echo 'ord '.$ord;
//	$ord= $orden;
		 }
		 }	 	  
		$linea+=2;
		$pdf->SetY($linea);
		$pdf->SetX(10);
		 $pdf->Cell($w[1],5,number_format($ts,2,".",","),0,0,'R',0);  
		 $pdf->Cell($w[2],5,number_format($tu,2,".",","),0,0,'R',0);
*/
}
/*********************************************************************************************************************************/
if ($accion=="fechaxxx") {
	$sql="Select cod_prof, hab_prof, hab_ucla, descri, date_format(fecha, '%d/%m/%Y') AS fechax from fhis200 where cod_prof= '$codigo' and (fecha >= '2008-01-01') ORDER by fecha";
		$result=mysql_query($sql);
		$sql="SELECT ape_prof, nombr_prof, ced_prof, cod_prof FROM sgcaf200 WHERE cod_prof= '$codigo'";
$resultado=mysql_query($sql);
$fila2 = mysql_fetch_assoc($resultado);
$linea+=2;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->SetFillColor(200,200,200);
$pdf->SetTextColor(0);
$pdf->SetDrawColor(0,0,0);
$pdf->SetLineWidth(.2);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(0,6,'DATOS DEL ASOCIADO',0,0,'C',1);
$linea+=7;
$pdf->SetFont('Arial','B',8);
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(32,6,'Nombre del Asociado:',0,0,'L',0);
$pdf->SetX(42);
$pdf->Cell(60,6,$fila2['ape_prof'].''.$fila2['nombr_prof'],0,0,'L',0);
$pdf->SetX(102);
$pdf->Cell(13,6,'Cédula:',0,0,'L',0);
$pdf->SetX(115);
$pdf->Cell(40,6,$fila2['ced_prof'],0,0,'L',0);
$pdf->SetX(155);
$pdf->Cell(13,6,'Código:',0,0,'L',0);
$pdf->SetX(168);
$pdf->Cell(32,6,$fila2['cod_prof'],0,0,'L',0);
$linea+=7;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->SetFillColor(200,200,200);
$pdf->SetTextColor(0);
$pdf->SetDrawColor(0,0,0);
$pdf->SetLineWidth(.2);
$pdf->SetFont('Arial','B',8);
$header=array('Fecha','Haberes Socio','Haberes Patrono','Descripción','Monto de Retiro','Fecha de Retiro');
//Colores, ancho de línea y fuente en negrita
    $pdf->SetFillColor(200,200,200);
    $pdf->SetTextColor(0);
    $pdf->SetDrawColor(0,0,0);
    $pdf->SetLineWidth(.2);
    $pdf->SetFont('Arial','B',7);
//Cabecera
    $w=array(20,25,25,70,25,25);
    for($i=i+n;$i<count($header);$i++)
	    $pdf->Cell($w[$i],5,$header[$i],1,0,'C',1);
		$pdf->Ln();
//Restauración de colores y fuentes
  $pdf->SetFillColor(200,200,200);
    $pdf->SetTextColor(0);
    $pdf->SetFont('Arial','',7);
	//Buscamos y listamos los proveedores
	 //posicion celda, alto,contenido,bordes que mostramos(left,right top botton),0, alineacion izquierda,relleno
		 //imprimo nombre, apellidos y localidad
//	echo 'orden '.$orden;
//	echo 'ord '.$ord;
//	$ord= $orden;
$linea+=3;
	while ($row = mysql_fetch_array($result))
//	echo $sql;
       {
	 //posicion celda, alto,contenido,bordes que mostramos(left,right top botton),0, alineacion izquierda,relleno
		 //imprimo nombre, apellidos y localidad
		 $linea+=5;
		 $pdf->SetY($linea);
		 $pdf->Cell($w[0],5,$row["fechax"],0,0,'C',0); 
		 $pdf->Cell($w[1],5,$row ["hab_prof"],0,0,'R',0);  
		 $pdf->Cell($w[2],5,number_format($row["hab_ucla"],2,".",","),0,0,'R',0);
		 $pdf->Cell($w[3],5,trim($row["descri"]),0,0,'L',0);
		 if ($linea>=245){
		 	$pdf->AddPage();
						$linea=25;
$linea=30;
$pdf->SetY($linea);
$pdf->SetX(0);
$pdf->SetFont('Arial','B',14);
$pdf->MultiCell(0,0,"H I S T O R I A L E S   D E   H A B E R E S",0,C,0);
$pdf->SetY($linea);
$pdf->SetFont('Arial','',7);
$linea+=5;
$pdf->SetX(165);
$pdf->Cell(20,0,'Realizada el '.date('d/m/Y h:i A'),0,0,'C'); 
$linea+=2;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->SetFillColor(200,200,200);
$pdf->SetTextColor(0);
$pdf->SetDrawColor(0,0,0);
$pdf->SetLineWidth(.2);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(0,6,'DATOS DEL ASOCIADO',0,0,'C',1);
$linea+=7;
$pdf->SetFont('Arial','B',8);
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(32,6,'Nombre del Asociado:',0,0,'L',0);
$pdf->SetX(42);
$pdf->Cell(60,6,$fila2['ape_prof'].''.$fila2['nombr_prof'],0,0,'L',0);
$pdf->SetX(102);
$pdf->Cell(13,6,'Cédula:',0,0,'L',0);
$pdf->SetX(115);
$pdf->Cell(40,6,$fila2['ced_prof'],0,0,'L',0);
$pdf->SetX(155);
$pdf->Cell(13,6,'Código:',0,0,'L',0);
$pdf->SetX(168);
$pdf->Cell(32,6,$fila2['cod_prof'],0,0,'L',0);
$linea+=7;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->SetFillColor(200,200,200);
$pdf->SetTextColor(0);
$pdf->SetDrawColor(0,0,0);
$pdf->SetLineWidth(.2);
$pdf->SetFont('Arial','B',8);
$header=array('Fecha','Haberes Socio','Haberes Patrono','Descripción','Monto de Retiro','Fecha de Retiro');
//Colores, ancho de línea y fuente en negrita
    $pdf->SetFillColor(200,200,200);
    $pdf->SetTextColor(0);
    $pdf->SetDrawColor(0,0,0);
    $pdf->SetLineWidth(.2);
    $pdf->SetFont('Arial','B',7);
//Cabecera
    $w=array(20,25,25,70,25,25);
    for($i=i+n;$i<count($header);$i++)
	    $pdf->Cell($w[$i],5,$header[$i],1,0,'C',1);
		$pdf->Ln();
//Restauración de colores y fuentes
  $pdf->SetFillColor(200,200,200);
    $pdf->SetTextColor(0);
    $pdf->SetFont('Arial','',7);
	 $linea+=3;
	//Buscamos y listamos los proveedores
	 //posicion celda, alto,contenido,bordes que mostramos(left,right top botton),0, alineacion izquierda,relleno
		 //imprimo nombre, apellidos y localidad
//	echo 'orden '.$orden;
//	echo 'ord '.$ord;
//	$ord= $orden;
		 }
		 }
		$sql="select montoreti,date_format(fechareti, '%d/%m/%Y') AS fechaz FROM sgcaf700 WHERE codsoc='$codigo' ORDER BY fechareti";
$resultado=mysql_query($sql);
        //Restauración de colores y fuentes
  $pdf->SetFillColor(200,200,200);
    $pdf->SetTextColor(0);
    $pdf->SetFont('Arial','',7);
while($row1=mysql_fetch_assoc($resultado)) {
$linea+=5;
		 $pdf->SetY($linea);
		 $pdf->Cell($w[0],5,$row[""],0,0,'C',0); 
		 $pdf->Cell($w[1],5,$row [""],0,0,'R',0);  
		 $pdf->Cell($w[2],5,$row[""],0,0,'R',0);
		 $pdf->Cell($w[3],5,$row[""],0,0,'R',0);
         $pdf->Cell($w[4],5,number_format($row1["montoreti"],2,".",","),0,0,'R',0);
         $pdf->Cell($w[5],5,$row1["fechaz"],0,0,'R',0);		
		 
		 
		 if ($linea>=245){
		 	$pdf->AddPage();
						$linea=25;
$linea=30;
$pdf->SetY($linea);
$pdf->SetX(0);
$pdf->SetFont('Arial','B',14);
$pdf->MultiCell(0,0,"H I S T O R I A L E S   D E   H A B E R E S",0,C,0);
$pdf->SetY($linea);
$pdf->SetFont('Arial','',7);
$linea+=5;
$pdf->SetX(165);
$pdf->Cell(20,0,'Realizada el '.date('d/m/Y h:i A'),0,0,'C'); 
$linea+=2;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->SetFillColor(200,200,200);
$pdf->SetTextColor(0);
$pdf->SetDrawColor(0,0,0);
$pdf->SetLineWidth(.2);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(0,6,'DATOS DEL ASOCIADO',0,0,'C',1);
$linea+=7;
$pdf->SetFont('Arial','B',8);
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(32,6,'Nombre del Asociado:',0,0,'L',0);
$pdf->SetX(42);
$pdf->Cell(60,6,$fila2['ape_prof'].''.$fila2['nombr_prof'],0,0,'L',0);
$pdf->SetX(102);
$pdf->Cell(13,6,'Cédula:',0,0,'L',0);
$pdf->SetX(115);
$pdf->Cell(40,6,$fila2['ced_prof'],0,0,'L',0);
$pdf->SetX(155);
$pdf->Cell(13,6,'Código:',0,0,'L',0);
$pdf->SetX(168);
$pdf->Cell(32,6,$fila2['cod_prof'],0,0,'L',0);
$linea+=7;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->SetFillColor(200,200,200);
$pdf->SetTextColor(0);
$pdf->SetDrawColor(0,0,0);
$pdf->SetLineWidth(.2);
$pdf->SetFont('Arial','B',8);
$header=array('Fecha','Haberes Socio','Haberes Patrono','Descripción','Monto de Retiro','Fecha de Retiro');
//Colores, ancho de línea y fuente en negrita
    $pdf->SetFillColor(200,200,200);
    $pdf->SetTextColor(0);
    $pdf->SetDrawColor(0,0,0);
    $pdf->SetLineWidth(.2);
    $pdf->SetFont('Arial','B',7);
//Cabecera
    $w=array(20,25,25,70,25,25);
    for($i=i+n;$i<count($header);$i++)
	    $pdf->Cell($w[$i],5,$header[$i],1,0,'C',1);
		$pdf->Ln();
//Restauración de colores y fuentes
  $pdf->SetFillColor(200,200,200);
    $pdf->SetTextColor(0);
    $pdf->SetFont('Arial','',7);
	 $linea+=3;
	//Buscamos y listamos los proveedores
	 //posicion celda, alto,contenido,bordes que mostramos(left,right top botton),0, alineacion izquierda,relleno
		 //imprimo nombre, apellidos y localidad
//	echo 'orden '.$orden;
//	echo 'ord '.$ord;
//	$ord= $orden;
		 }
		 }	 
}
}
$pdf->Output();

function encabezado(&$linea, $pdf, $fila2, &$w, &$p)
{
	$pdf->AddPage();
	$linea=25;
	$linea=30;
	$pdf->SetY($linea);
	$pdf->SetX(0);
	$pdf->SetFont('Arial','B',14);
	$pdf->MultiCell(0,0,"--H I S T O R I A L E S   D E  H A B E R E S--",0,C,0);
	$pdf->SetY($linea);
	$pdf->SetFont('Arial','',7);
	$linea+=5;
	$pdf->SetX(215);
	$pdf->Cell(20,0,'Realizada el '.date('d/m/Y h:i A'),0,0,'C'); 
	$linea+=2;
	$pdf->SetY($linea);
	$pdf->SetX(10);
	$pdf->SetFillColor(200,200,200);
	$pdf->SetTextColor(0);
	$pdf->SetDrawColor(0,0,0);
	$pdf->SetLineWidth(.2);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(0,6,'DATOS DEL ASOCIADO',0,0,'C',1);
	$linea+=7;
	$pdf->SetFont('Arial','B',8);
	$pdf->SetY($linea);
	$pdf->SetX(10);
	$pdf->Cell(32,6,'Nombre del Asociado:',0,0,'L',0);
	$pdf->SetX(42);
	$pdf->Cell(60,6,$fila2['ape_prof'].''.$fila2['nombr_prof'],0,0,'L',0);
	$pdf->SetX(102);
	$pdf->Cell(13,6,'Cédula:',0,0,'L',0);
	$pdf->SetX(115);
	$pdf->Cell(40,6,$fila2['ced_prof'],0,0,'L',0);
	$pdf->SetX(155);
	$pdf->Cell(13,6,'Código:',0,0,'L',0);
	$pdf->SetX(168);
	$pdf->Cell(32,6,$fila2['cod_prof'],0,0,'L',0);
	$linea+=7;
	$pdf->SetY($linea);
	$pdf->SetX(10);
	$pdf->SetFillColor(200,200,200);
	$pdf->SetTextColor(0);
	$pdf->SetDrawColor(0,0,0);
	$pdf->SetLineWidth(.2);
	$pdf->SetFont('Arial','B',8);
	$header=array('Fecha','Haberes Socio','Haberes Patrono','Descripción','Monto de Retiro','Fecha','Haberes Socio','Haberes Patrono','Descripción','Monto de Retiro'); // ,'Fecha de Retiro');
	//Colores, ancho de línea y fuente en negrita
    $pdf->SetFillColor(200,200,200);
    $pdf->SetTextColor(0);
    $pdf->SetDrawColor(0,0,0);
    $pdf->SetLineWidth(.2);
    $pdf->SetFont('Arial','B',7);
	//Cabecera
    $w=array(15,20,20,40,20,  15,20,20,40,20);
	$p=array(10,25,45,65,105, 125,140,160,180,220);
    for($i=i+n;$i<count($header);$i++)
	    $pdf->Cell($w[$i],5,$header[$i],1,0,'C',1);
		$pdf->Ln();
	//Restauración de colores y fuentes
	$pdf->SetFillColor(200,200,200);
    $pdf->SetTextColor(0);
    $pdf->SetFont('Arial','',7);
	$linea+=3;
	return $linea;
}

function mostrar ($result, &$pdf, &$linea, $w, $p, $fila2)
{
	$alto=4;
	$posicion=0;
	while ($row = mysql_fetch_array($result))
//	echo $sql;
       {
	 //posicion celda, alto,contenido,bordes que mostramos(left,right top botton),0, alineacion izquierda,relleno
		 //imprimo nombre, apellidos y localidad
		 $pdf->SetY($linea);
		 $pdf->SetX($p[$posicion]);
		 $pdf->Cell($w[0],$alto,$row["fechax"],$cuadro,0,'C',0); 
		if ($row['descri']!='Retiro de Haberes')
		{
			$posicion++;
			$pdf->SetX($p[$posicion]);
			$pdf->Cell($w[1],$alto,number_format($row["hab_prof"],2,".",","),$cuadro,0,'R',0);  
			$posicion++;
			$pdf->SetX($p[$posicion]);
			$pdf->Cell($w[2],$alto,number_format($row["hab_ucla"],2,".",","),$cuadro,0,'R',0);
			$posicion++;
			$pdf->SetX($p[$posicion]);
			$pdf->Cell($w[3],$alto,trim($row["descri"]),$cuadro,0,'L',0);
			$posicion++;
			$posicion++;
		}
		else 
		{
			$posicion++;
			$posicion++;
			$posicion++;
			$cuadro=1;
			$pdf->SetX($p[$posicion]);
			$pdf->Cell($w[3],$alto,trim($row["descri"]),$cuadro,0,'L',0);
			$posicion++;
			$pdf->SetX($p[$posicion]);
			$pdf->Cell($w[4],$alto,number_format($row["hab_prof"]+$row['hab_ucla']+$row['ret_opsu'],2,".",","),$cuadro,0,'R',0);
			$posicion++;
			$cuadro=0;
		}
		if ($posicion>8)
		{
			$posicion=0;
			 $linea+=$alto;
		}
		 if ($linea>=185){
			 encabezado($linea,$pdf,$fila2, $w, $p);
			 $linea+=$alto;
		 }

		 }
}
?>