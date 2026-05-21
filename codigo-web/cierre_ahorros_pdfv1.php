<?php
// http://192.168.100.39/cajaweb/cierre_ahorros_pdf.php?fechaaporte=2022-01-31
session_start();

extract($_GET);
extract($_POST);
extract($_SESSION);
// $fechaaporte='2023-01-31';
// $fechaaporte='2023-02-28';

include("conex.php");
if (!$link OR !$_SESSION['empresa']) {
	include("head.php");
	header("location: noempresa.php");
	exit;
}
include_once('funciones.php');
set_time_limit(500);
define('FPDF_FONTPATH','fpdf/font/');
require('fpdf/mysql_table.php');
include("fpdf/comunes.php");
// include ("conex.php"); 
// echo $fechanomina;

$probar = false;
$pdf=new PDF('L','mm','Letter');
$pdf->Open();
$pdf->AddPage();

$linea=0;
$linea=25;
$pdf->SetY($linea);
$pdf->SetX(0);
$concepto='A';
$pdf->SetFont('Arial','B',18);
$pdf->MultiCell(0,0,"Saldo de Haberes al ".convertir_fechadmy($fechaaporte),0,C,0);
$pdf->SetY($linea);
$pdf->SetFont('Arial','',10);
$pdf->SetFillColor(255,255,255);
$pdf->SetTextColor(0);
$comisionbancaria = 5;

$header=array('Ubicación','Código','Cédula/Est','Apellidos y Nombres','Retención','Aporte','Voluntario','Total'); 
$w=array(15, 20, 15, 70, 20, 20, 20, 20);
$p=array(20, 45, 65, 95, 170, 190, 215, 240);

$pdf->SetY($linea);
$pdf->SetFont('Arial','B',10);
$pdf->SetX(30);
	
//Cabecera
// $pc=$w[0];
// $p=array();
// array_push($p,$pc);
$linea+=5;
$pdf->SetY($linea);
$pdf->SetX($p[0]);
$pdf->Cell(0,0,'  ',1,0,'L',0);
// $linea+=5;
for($i=0;$i<count($header);$i++)
{
	$pdf->SetY($linea);
	$pdf->SetX($p[$i]);
    $pdf->Cell($w[$i],5,$header[$i],0,0,'C',1);
	// $pc+=$w[$i+1];
	// array_push($p,$pc);
}

				$linea+=5;
				$pdf->SetY($linea);
				$pdf->SetX($p[0]);
				$pdf->Cell(0,0,'  ',1,0,'L',0);
				$linea-=5;

pasar_a_cierre_historico_limpio($link, $fechaaporte);
ver_si_hay_nuevos($link, $fechaaporte);

// $sql="select * from t_his200, sgcaf200 where (fecha ='$fechaaporte') and (t_his200.cedula = sgcaf200.ced_prof) order by cedula";
$sql = "
	select 
		ubic_prof as ubicacion, cod_prof as codigo, ced_prof as cedula, 
		concat(trim(ape_prof), ' ', trim(nombr_prof)) as nombre, 
		hab_f_prof as ret_inicial, hab_f_empr as aporte_inicial, hab_f_extr as extra_inicial, 
	(
		select sum(hab_prof)
		from fhis200 
		where 
			fhis200.cod_prof = iniciof200.cod_prof and 
			(pago > '2021-10-31' and pago <= '".$fechaaporte."') and 
			descri <> 'Ahorro Voluntario'
	) as mas_retencion,
	(
		select sum(hab_ucla)
		from fhis200 
		where 
			fhis200.cod_prof = iniciof200.cod_prof and 
			(pago > '2021-10-31' and pago <= '".$fechaaporte."')
	) as mas_aporte, 
	(
		select sum(hab_prof)
		from fhis200 
		where 
			fhis200.cod_prof = iniciof200.cod_prof and 
			(pago > '2021-10-31' and pago <= '".$fechaaporte."') and 
			descri = 'Ahorro Voluntario'
	) as mas_extra,

	(
		select sum(ret_ucla)
		from sgcaf700
		where 
			sgcaf700.codsoc = iniciof200.cod_prof and 
			(fechareti >= '2021-10-31' and fechareti <= '".$fechaaporte."') 
	) as menos_retencion,
	(
		select sum(ret_capu)
		from sgcaf700
		where 
			sgcaf700.codsoc = iniciof200.cod_prof and 
			(fechareti >= '2021-10-31' and fechareti <= '".$fechaaporte."') 
	) as menos_aporte,
	(
		select sum(ret_volu)
		from sgcaf700
		where 
			sgcaf700.codsoc = iniciof200.cod_prof and 
			(fechareti >= '2021-10-31' and fechareti <= '".$fechaaporte."') 
	) as menos_extra,
	(select upper(statu_prof) from sgcaf200 where iniciof200.cod_prof = sgcaf200.cod_prof) as estatus

	from iniciof200 
	where f_ing_capu <= '2021-10-31' ";
	if ($probar)
		$sql.= " and  ((cod_prof = '00133'))";
	$sql .= "ORDER BY ubicacion, nombre";
		// and  ((cod_prof = '00133') or (cod_prof = '00140'))
	// limit 55";
		// and  ((cod_prof = '01956') or (cod_prof = '02098') or (cod_prof = '01707'))
$result=mysql_query($sql) or die (mysql_error());
$emontor = $emontoe = $emontoa = 0;
$activos = $jubilados = $otros = $retirados = 0;
$maxlineas=165;
$pdf->SetFont('Arial','',10);
$cont='0'; 
$columna=0;
while ($row = mysql_fetch_array($result))
{
	$retencion = ($row['ret_inicial']+$row['mas_retencion'])-$row['menos_retencion'];
	$aporte = ($row['aporte_inicial']+$row['mas_aporte'])-$row['menos_aporte'];
	$extra = ($row['extra_inicial']+$row['mas_extra'])-$row['menos_extra'];

	//posicion celda, alto,contenido,bordes que mostramos(left,right top botton),0, alineacion izquierda,relleno
	if (($retencion+$aporte+$extra) > 0)
	{
		$linea+=4;
		$pdf->SetY($linea);
		$pdf->SetX($p[0+$columna]);
		$pdf->Cell($w[0+$columna],5,$row['ubicacion'],0,0,'LRTB',0); 
		$pdf->SetX($p[1+$columna]);
		$asterisco=substr($row['estatus'],0,1);
		if ($asterisco == 'A')
			$activos++;
		else 
			if ($asterisco == 'J')
				$jubilados++;
			else 
				if ($asterisco == 'R')
					$retirados++;
				else 
					$otros++;
		$pdf->Cell($w[1+$columna],5,$row['codigo'],0,0,'LRTB',0); 
		$codigo = $row['codigo'];

		$pdf->SetX($p[2+$columna]);
		if ($probar)
			$pdf->Cell($w[2+$columna],5,$row['ret_inicial'].'/' .$row['mas_retencion'],0,0,'LRTB',0); 
		else 
			$pdf->Cell($w[2+$columna],5,$row['cedula'].' ' .$asterisco,0,0,'LRTB',0); 
		$cont++; 
		$acotado = substr(trim($row["nombre"]).' '.trim($row["nombr_prof"]),0,30);
		$pdf->SetX($p[3+$columna]);
	    $pdf->Cell($w[3+$columna],5,$acotado,0,0,'LRTB',0);  

		$pdf->SetX($p[4+$columna]);
		$pdf->Cell($w[4+$columna],5,number_format($retencion,2,".",","),0,0,'R',0);
		$pdf->SetX($p[5+$columna]);
		$pdf->Cell($w[5+$columna],5,number_format($aporte,2,".",","),0,0,'R',0);
		$pdf->SetX($p[6+$columna]);
		$pdf->Cell($w[6+$columna],5,number_format($extra,2,".",","),0,0,'R',0);
		$pdf->SetX($p[7+$columna]);
		$pdf->Cell($w[7+$columna],5,number_format($retencion+$aporte+$extra,2,".",","),0,0,'R',0);

		$sql2 = "update historico_cierre_ahorros set hab_f_prof = $retencion, hab_f_empr = $aporte, hab_f_extr = $extra where cod_prof = '$codigo' and fecha_cierre = '$fechaaporte'";
		$result2=mysql_query($sql2) or die (mysql_error());


		// $pdf->SetX($p[8+$columna]);
		// $pdf->Cell($w[7+$columna],5,substr($row['estatus'],0,1),0,0,'R',0);
		// $pdf->Cell($w[8+$columna],5,substr($row['estatus'],0,1),0,0,'R',0);

		// ubicacion	codigo	cedula	nombre	ret_inicial	aporte_inicial	extra_inicial	mas_retencion	mas_aporte	mas_extra	menos_retencion	menos_aporte	menos_extra

		$emontor+=$retencion;
		$emontoa+=$aporte;
		$emontoe+=$extra;
		if ($linea >= $maxlineas)
		{
			{
				$linea+=5;
				$pdf->SetY($linea);
				$pdf->SetX($p[0]);
				$pdf->Cell(0,0,'  ',1,0,'L',0);
				// $linea+=5;
				$pdf->SetY($linea);
				$pdf->SetX($p[3]);
			    $pdf->Cell($w[3],5,'Van....',0,0,'C',0);  

				$pdf->SetX($p[4+$columna]);
				$pdf->Cell($w[4+$columna],5,number_format($emontor,2,".",","),0,0,'R',0);
				$pdf->SetX($p[5+$columna]);
				$pdf->Cell($w[5+$columna],5,number_format($emontoa,2,".",","),0,0,'R',0);
				$pdf->SetX($p[6+$columna]);
				$pdf->Cell($w[6+$columna],5,number_format($emontoe,2,".",","),0,0,'R',0);
				$pdf->SetX($p[7+$columna]);
				$pdf->Cell($w[7+$columna],5,number_format($emontor+$emontoa+$emontoe,2,".",","),0,0,'R',0);
				$linea+=5;
				$pdf->SetY($linea);
				$pdf->SetX($p[0]);
				$pdf->Cell(0,0,'  ',1,0,'L',0);

				$columna = 0;
				$pdf->AddPage();
				$linea=25;
				$pdf->SetY($linea);
				$pdf->SetX(0);
				$pdf->SetFont('Arial','B',18);
				$concepto='A';
				// $pdf->MultiCell(0,0,"Listado de Retención/Aportes al ".$fechanomina,0,C,0);
				$pdf->MultiCell(0,0,"Saldo de Haberes al ".convertir_fechadmy($fechaaporte),0,C,0);
				$pdf->SetY($linea);
				$pdf->SetFont('Arial','',10);
				$pdf->SetFillColor(255,255,255);
				$pdf->SetTextColor(0);

				$linea+=5;
				$pdf->SetFont('Arial','',10);
				$pdf->SetY($linea);
				$pdf->SetX($p[0]);
				$pdf->Cell(0,0,'  ',1,0,'L',0);

				$linea+=1;
				$pdf->SetY($linea);
				$pdf->SetFont('Arial','B',10);
				$pdf->SetX(30);
				
				//Cabecera

				for($i=0;$i<count($header);$i++)
				{
					$pdf->SetY($linea);
					$pdf->SetX($p[$i]);
				    $pdf->Cell($w[$i],5,$header[$i],0,0,'C',1);
				}
				$linea+=5;
				$pdf->SetY($linea);
				$pdf->SetX($p[0]);
				$pdf->Cell(0,0,'  ',1,0,'L',0);
				$linea-=5;
			}
		}
		$pdf->SetFont('Arial','',10);
	}
};
$linea+=5;
$pdf->SetY($linea);
$pdf->SetX($p[0]);
$pdf->Cell(0,0,'  ',1,0,'L',0);
$pdf->SetY($linea);

$pdf->SetX($p[3]);
$pdf->Cell($w[3],5,'Total....',0,0,'C',0);  

$pdf->SetX($p[4+$columna]);
$pdf->Cell($w[4+$columna],5,number_format($emontor,2,".",","),0,0,'R',0);
$pdf->SetX($p[5+$columna]);
$pdf->Cell($w[5+$columna],5,number_format($emontoa,2,".",","),0,0,'R',0);
$pdf->SetX($p[6+$columna]);
$pdf->Cell($w[6+$columna],5,number_format($emontoe,2,".",","),0,0,'R',0);
$pdf->SetX($p[7+$columna]);
$pdf->Cell($w[7+$columna],5,number_format($emontor+$emontoa+$emontoe,2,".",","),0,0,'R',0);
$linea+=5;
$pdf->SetY($linea);
$pdf->SetX($p[0]);
$pdf->Cell(0,0,'  ',1,0,'L',0);


$linea+=5;
$pdf->SetY($linea);

$pdf->SetX($p[0]);
$pdf->Cell($w[0]+$w[1],5,'Activos: '.number_format($activos,0,".",","),1,0,'C',0);  

// $pdf->SetX($p[1+$columna]);
// $pdf->Cell($w[1+$columna],5,number_format($activos,0,".",","),0,0,'R',0);

// $linea+=5;
// $pdf->SetY($linea);

$pdf->SetX($p[2]);
$pdf->Cell($w[0]+$w[1],5,'Jubilados: '.number_format($jubilados,0,".",","),1,0,'C',0);  

// $pdf->SetX($p[3+$columna]);
// $pdf->Cell($w[3+$columna],5,number_format($jubilados,0,".",","),0,0,'R',0);

$linea+=5;
$pdf->SetY($linea);

$pdf->SetX($p[0]);
$pdf->Cell($w[0]+$w[1],5,'Retirados: '.number_format($retirados,0,".",","),1,0,'C',0);  

// $pdf->SetX($p[5+$columna]);
// $pdf->Cell($w[5+$columna],5,number_format($retirados,0,".",","),0,0,'R',0);


// $linea+=5;
// $pdf->SetY($linea);

$pdf->SetX($p[2]);
$pdf->Cell($w[0]+$w[1],5,'Otros: '.number_format($otros,0,".",","),1,0,'C',0);  

// $pdf->SetX($p[4+$columna]);
// $pdf->Cell($w[4+$columna],5,number_format($otros,0,".",","),0,0,'R',0);

set_time_limit(30);

$pdf->Output();



// para los listados
$archivo = 'reportesahorros/'.$fechaaporte.'.pdf';
$sql = "SELECT * FROM sgcafnah where fecha='$fechaaporte'";
$result=mysql_query($sql) or die (mysql_error());
if (mysql_num_rows($result) < 1)
{
	$sql = "insert into sgcafnah (fecha) values ('$fechaaporte')";
	$result=mysql_query($sql) or die (mysql_error());
}
if (!$probar)
	$pdf->Output($archivo);


function encabeza_aportes($pdf,$linea)
{
//	return $linea;
}

function pasar_a_cierre_historico_limpio($link, $fechaaporte)
{
	$sql = "delete from historico_cierre_ahorros where fecha_cierre = '$fechaaporte'";
	$result=mysql_query($sql) or die (mysql_error());
	$sql = "insert into historico_cierre_ahorros select *, '$fechaaporte' as fecha_cierre from sgcaf200";
	$result=mysql_query($sql) or die (mysql_error());
	$sql = "update historico_cierre_ahorros set hab_f_prof = 0, hab_f_empr = 0, hab_f_extr = 0 where  fecha_cierre = '$fechaaporte'";
	$result=mysql_query($sql) or die (mysql_error());
}

function ver_si_hay_nuevos($link, $fechaaporte)
{
	$sql = "select cod_prof from historico_cierre_ahorros where fecha_cierre = '$fechaaporte'";
	$result=mysql_query($sql) or die (mysql_error());
	while ($row = mysql_fetch_array($result))
	{
		$codigo = $row['cod_prof'];
		$sql = "select cod_prof from iniciof200 where cod_prof = '$codigo'";
		$result2=mysql_query($sql) or die (mysql_error());
		if (mysql_num_rows($result2) < 1)
		{
	// 		// nuevo 
			$sql = "insert into iniciof200 select * from sgcaf200 where cod_prof='$codigo'";
			// echo $sql;
			$result3=mysql_query($sql) or die (mysql_error());
			$sql = "update iniciof200 set hab_f_prof = 0, hab_f_empr = 0, hab_f_extr = 0  where cod_prof='$codigo'";
			$result3=mysql_query($sql) or die (mysql_error());
		}
	}
}

?> 
