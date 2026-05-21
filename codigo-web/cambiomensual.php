<?php
// http://192.168.100.239/cajaweb/cierre_ahorros_pdf.php?fechaaporte=2023-05-31
session_start();

extract($_GET);
extract($_POST);
extract($_SESSION);
$fechaaporte=$_GET['fechaaporte'];
// $fechaaporte='2023-11-30';
// $fechaanterior='2023-09-30';
$fechaaporte_corta = substr($fechaaporte, 0,7);
$ajustar = 0; // 49.84; // 0; // 203.56; //  - 8.56;
$sumar = true;

include("conex.php");
if (!$link OR !$_SESSION['empresa']) {
	include("head.php");
	header("location: noempresa.php");
	exit;
}
include_once('funciones.php');
set_time_limit(500);

$sql = "select * from sgcaf310 where stapre_sdp='A'  and renovado=0 and codpre_sdp<>'046' order by codpre_sdp";
$sql = "select * from sgcaf310 where stapre_sdp='A'  and renovado=0 and codpre_sdp='023' order by codpre_sdp, nropre_sdp";
$resultp=mysql_query($sql) or die (mysql_error());
echo '<table>';
while ($est = mysql_fetch_array($resultp))
{
	$codigo = $est['codsoc_sdp'];
	$prestamo = $est['codpre_sdp'];
	$sqls = "select nombr_prof, ape_prof from sgcaf200 where cod_prof = '".$codigo."'";
	$results=mysql_query($sqls) or die (mysql_error());
	$socio = mysql_fetch_array($results);
	$sqlt = "select * from sgcaf360 where cod_pres = '".$prestamo."'";
	$resultt=mysql_query($sqlt) or die (mysql_error());
	$tipo = mysql_fetch_array($resultt);
	// $cuenta = trim($tipo['cuent_pres']);
	// $sqlc = "select cue_saldo from "

	echo '<tr>';
	echo '<td>'.$est['cedsoc_sdp'].' </td><td>'.$socio['ape_prof'].' '.$socio['nombr_prof'].'</td>';
	echo ' <td>'.$est['nropre_sdp'].'</td>';
	echo ' <td>'.$tipo['descr_pres'].'</td>';
	echo '<td>'.$est['monpre_sdp'].'</td><td>'.$est['monpag_sdp'].'</td>';
	echo '<td>'.($est['monpre_sdp']-$est['monpag_sdp']).'</td>';
	echo ' <td>'.$est['ultcan_sdp'].'/'.$est['nrocuotas'].'</td>';
	echo '<tr>';
}
echo '</table>';






// define('FPDF_FONTPATH','fpdf/font/');
// require('fpdf/mysql_table.php');
// include("fpdf/comunes.php");
// // include ("conex.php"); 
// // echo $fechanomina;

// $fechaanterior= "SELECT fecha FROM sgcafnah ORDER BY fecha DESC LIMIT 1";
// $result=mysql_query($fechaanterior) or die (mysql_error());
// $fechaanterior = mysql_fetch_array($result);
// $fechaanterior = $fechaanterior['fecha'];
// if ($fechaanterior == $fechaaporte)
// 	die('fechas iguales');
// // die('anteropr '.$fechaanterior. ' aporte'.$fechaaporte);

// $probar = false;
// $pdf=new PDF('L','mm','Letter');

// $comisionbancaria = 5;

// $header=array('Ubicación','Código','Cédula/Est','Apellidos y Nombres','Retención','Aporte','Voluntario','Total'); 
// $w=array(15, 20, 15, 70, 20, 20, 20, 20);
// $p=array(20, 45, 65, 95, 170, 190, 215, 240);

// $pdf->SetY($linea);
// $pdf->SetFont('Arial','B',10);
// $pdf->SetX(30);
	
// //Cabecera
// // $pc=$w[0];
// // $p=array();
// // array_push($p,$pc);
// $linea = encabezado($pdf, $linea, $header, $w, $p, $fechaaporte);

// pasar_a_cierre_historico_limpio($link, $fechaaporte);
// ver_si_hay_nuevos($link, $fechaaporte);
// // $sql="select * from t_his200, sgcaf200 where (fecha ='$fechaaporte') and (t_his200.cedula = sgcaf200.ced_prof) order by cedula";
// /*
// $sql = "
// 	select 
// 		ubic_prof as ubicacion, cod_prof as codigo, ced_prof as cedula, 
// 		concat(trim(ape_prof), ' ', trim(nombr_prof)) as nombre, 
// 		hab_f_prof as ret_inicial, hab_f_empr as aporte_inicial, hab_f_extr as extra_inicial, 
// 	(
// 		select sum(hab_prof)
// 		from fhis200 
// 		where 
// 			fhis200.cod_prof = iniciof200.cod_prof and 
// 			(pago > '2021-10-31' and pago <= '".$fechaaporte."') and 
// 			descri <> 'Ahorro Voluntario'
// 	) as mas_retencion,
// 	(
// 		select sum(hab_ucla)
// 		from fhis200 
// 		where 
// 			fhis200.cod_prof = iniciof200.cod_prof and 
// 			(pago > '2021-10-31' and pago <= '".$fechaaporte."')
// 	) as mas_aporte, 
// 	(
// 		select sum(hab_prof)
// 		from fhis200 
// 		where 
// 			fhis200.cod_prof = iniciof200.cod_prof and 
// 			(pago > '2021-10-31' and pago <= '".$fechaaporte."') and 
// 			descri = 'Ahorro Voluntario'
// 	) as mas_extra,

// 	(
// 		select sum(ret_ucla)
// 		from sgcaf700
// 		where 
// 			sgcaf700.codsoc = iniciof200.cod_prof and 
// 			(fechareti >= '2021-10-31' and fechareti <= '".$fechaaporte."') 
// 	) as menos_retencion,
// 	(
// 		select sum(ret_capu)
// 		from sgcaf700
// 		where 
// 			sgcaf700.codsoc = iniciof200.cod_prof and 
// 			(fechareti >= '2021-10-31' and fechareti <= '".$fechaaporte."') 
// 	) as menos_aporte,
// 	(
// 		select sum(ret_volu)
// 		from sgcaf700
// 		where 
// 			sgcaf700.codsoc = iniciof200.cod_prof and 
// 			(fechareti >= '2021-10-31' and fechareti <= '".$fechaaporte."') 
// 	) as menos_extra,
// 	(select upper(statu_prof) from sgcaf200 where iniciof200.cod_prof = sgcaf200.cod_prof) as estatus

// 	from iniciof200 
// 	where f_ing_capu <= '2021-10-31' ";
// 	if ($probar)
// 		$sql.= " and  ((cod_prof = '00133'))";
// 	$sql .= "ORDER BY ubicacion, nombre";
// 		// and  ((cod_prof = '00133') or (cod_prof = '00140'))
// 	// limit 55";
// 		// and  ((cod_prof = '01956') or (cod_prof = '02098') or (cod_prof = '01707'))

// */
// $sql = "DROP TEMPORARY TABLE IF EXISTS result_socios";
// $sql = "
// 	CREATE TEMPORARY TABLE result_socios select 
// 		ubic_prof as ubicacion, cod_prof as codigo, ced_prof as cedula, 
// 		concat(trim(ape_prof), ' ', trim(nombr_prof)) as nombre, 
// 		hab_f_prof as ret_inicial, hab_f_empr as aporte_inicial, hab_f_extr as extra_inicial, 00000.00 as mas_retencion, 00000.00 as mas_aporte, 00000.00 as mas_extra,  00000.00 as menos_retencion, 00000.00 as menos_aporte, 00000.00 as menos_extra, 
// 	(select upper(statu_prof) from sgcaf200 where historico_cierre_ahorros.cod_prof = sgcaf200.cod_prof) as estatus

// 	from historico_cierre_ahorros
// 	where fecha_cierre = '$fechaanterior' ";
// 	if ($probar)
// 		$sql.= " and  ((cod_prof = '00133'))";
// 	$sql .= "ORDER BY ubicacion, nombre";
// $result_socios=mysql_query($sql) or die (mysql_error());
// $sql = "select sum(ret_inicial), sum(aporte_inicial) from result_socios ";
// $result_socios=mysql_query($sql) or die (mysql_error());
// $row = mysql_fetch_array($result_socios);
// // var_dump($row);
// // die('espero');

// // echo " result_socios<br>";

// $sql = "DROP TEMPORARY TABLE IF EXISTS result_ahorro_est";
// $result_ahorro_est=mysql_query($sql) or die (mysql_error());

// $sql = "
// 	CREATE TEMPORARY TABLE result_ahorro_est select 
// 		cod_prof as codigo, sum(hab_prof) as mas_retencion, sum(hab_ucla) as mas_aporte
// 		from fhis200 
// 		where 
// 			(substr(pago,1,7) = '$fechaaporte_corta' and descri <> 'Ahorro Voluntario') 
// 			and (descri NOT LIKE '%FONDO%')
// 			-- and (descri LIKE '%AUTOR%' and descri NOT LIKE '%FONDO%')
// 		group by cod_prof";
// $result_ahorro_est=mysql_query($sql) or die (mysql_error());

// $sql = "select * from result_ahorro_est";
// $result_ahorro_est=mysql_query($sql) or die (mysql_error());
// $registros = mysql_num_rows($result_ahorro_est);
// if ($ajustar > 0)
// 	$ajustar = round($ajustar / $registros,3);
// // die($registros. ' registros '.$ajustar);
// // echo "result_ahorro_est<br>";

// $sql = "DROP TEMPORARY TABLE IF EXISTS result_ahorro_ext";
// $sql = "
// 	CREATE TEMPORARY TABLE result_ahorro_ext select 
// 		cod_prof as codigo, sum(hab_prof) as mas_extra
// 		from fhis200 
// 		where 
// 			(substr(pago,1,7) = '$fechaaporte_corta' and descri = 'Ahorro Voluntario')
// 			and (descri LIKE '%AUTOR%')
// 		group by cod_prof";
// $result_ahorro_ext=mysql_query($sql) or die (mysql_error());
// // echo "result_ahorro_ext<br>";

// $sql = "DROP TEMPORARY TABLE IF EXISTS result_retiros";
// $sql = "
// 		CREATE TEMPORARY TABLE result_retiros select codsoc as codigo, sum(ret_ucla) as menos_retencion, sum(ret_capu) as menos_aporte, sum(ret_volu) as menos_extra 
// 		from sgcaf700
// 		where 
// 			(substr(fechareti,1,7) = '$fechaaporte_corta') 
// 		group by codsoc
// ";
// $result_retiros=mysql_query($sql) or die (mysql_error());
// // echo "result_retiros<br>";
// /*
// $sql = "update result_socios set mas_retencion = result_ahorro_est.mas_retencion, as mas_aporte=result_ahorro_est.mas_aporte where result_socios.codigo = result_ahorro_est.codigo";
// $_update=mysql_query($sql) or die (mysql_error());
// echo "update 1<br>";

// */


// /*
// $retenciones = 0;
// $socios = 0;

// $sql = "select * from result_ahorro_est order by codigo"; 
// $result_ahorro_est=mysql_query($sql) or die (mysql_error());
// while ($est = mysql_fetch_array($result_ahorro_est))
// {
// 	$codigo = $est['codigo'];
// 	$mas_retencion = $est['mas_retencion'];
// 	$mas_aporte = $est['mas_aporte'];
// 	$retenciones += $mas_retencion;

// 	$sql = "select codigo from  result_socios where codigo = '$codigo'";
// 	$ret=mysql_query($sql) or die (mysql_error());

// 	if (mysql_num_rows($ret) > 0)
// 	{
// 		$sql = "update result_socios set mas_retencion = mas_retencion + ".($mas_retencion). ", mas_aporte = mas_aporte + ".($mas_aporte);
// 		// $sql.= ", mas_extra = mas_extra + ".($mas_extra-$menos_extra);
// 		$sql.= " where codigo = '$codigo'";
// 		echo $sql.'<br>';
// 		$ret=mysql_query($sql) or die (mysql_error());
// 	}
// 	else 
// 		die($sql);
// }
// $sql = "select sum(mas_retencion), sum(mas_aporte), sum(menos_retencion), sum(menos_aporte) from result_socios";
// $result_socios=mysql_query($sql) or die (mysql_error());
// $row = mysql_fetch_array($result_socios);
// var_dump($row);
// echo 'las retenciones ';
// die($retenciones);
// die('espero');

// */
// $sql = "select * from result_socios order by codigo";
// $result_socios=mysql_query($sql) or die (mysql_error());
// while ($row = mysql_fetch_array($result_socios))
// {
// 	$codigo = $row['codigo'];
// 	// echo 'a'.$codigo;
// 	$sql = "select sum(mas_retencion) as mas_retencion, sum(mas_aporte) as mas_aporte from result_ahorro_est where codigo = '$codigo'";
// 	$est=mysql_query($sql) or die (mysql_error());

// 	$sql = "select * from result_ahorro_ext where codigo = '$codigo'";
// 	$ext=mysql_query($sql) or die (mysql_error());

// 	$sql = "select * from result_retiros where codigo = '$codigo'";
// 	$ret=mysql_query($sql) or die (mysql_error());

// 	if (mysql_num_rows($est) > 0)
// 	{
// 		$est = mysql_fetch_array($est);
// 		$mas_retencion = $est['mas_retencion'];
// 		$mas_aporte = $est['mas_aporte'];
// 		$socios++;
// 		$retenciones += $mas_retencion;
// 	}
// 	else 
// 	{
// 		$mas_retencion = $mas_aporte = 0;
// 	}

// 	if (mysql_num_rows($ext) > 0)
// 	{
// 		$ext = mysql_fetch_array($ext);
// 		$mas_extra = $$ext['mas_extra'];
// 	}
// 	else $mas_extra = 0;

// 	if (mysql_num_rows($ret) > 0)
// 	{
// 		$ret = mysql_fetch_array($ret);
// 		// var_dump($ret);
// 		$menos_retencion = $ret['menos_retencion'];
// 		$menos_aporte = $ret['menos_aporte'];
// 		$menos_extra = $ret['menos_extra'];
// 	}
// 	else 
// 		$menos_retencion = $menos_aporte = $menos_extra = 0;

// 	// $sql = "update result_socios set mas_retencion = mas_retencion + ".($mas_retencion-$menos_retencion). ", mas_aporte = mas_aporte + ".($mas_aporte-$menos_aporte);
// 	// $sql.= ", mas_extra = mas_extra + ".($mas_extra-$menos_extra);
// 	// $sql.= " where codigo = '$codigo'";

// 	$sql = "update result_socios set mas_retencion = ".($est['mas_retencion']-$ret['menos_retencion']). ", mas_aporte= ".($est['mas_aporte']-$ret['menos_aporte']);
// 	$sql.= ", mas_extra = ".($ext['mas_extra']-$ret['menos_extra']);
// 	$sql.= " where codigo = '$codigo'";

// 	// $sql = "update result_socios set mas_retencion = ".($est['mas_retencion']). ", mas_aporte= ".($est['mas_aporte']);
// 	// // $sql.= ", mas_extra = ".($ext['mas_extra']);
// 	// $sql.= " where codigo = '$codigo'";
// // 
// 	// echo $sql.'<br>';
// 	// die('espero');
// 	$unico=mysql_query($sql) or die (mysql_error(). $sql);
// 	// if (mysql_query($sql)) // (mysql_num_rows($unico) > 0)
// 	// 	echo '';
// 	// else echo ':';

// 	if ($ajustar > 0)
// 	{
// 		if ($sumar)
// 			$sql = "update result_socios set mas_retencion = mas_retencion + ".$ajustar;
// 		else 
// 			$sql = "update result_socios set mas_retencion = mas_retencion - ".$ajustar;
// 		$sql.= " where codigo = '$codigo'";
// 		$unico=mysql_query($sql) or die (mysql_error(). $sql);
// 	}
// 	// if (mysql_query($sql)) // (mysql_num_rows($unico) > 0)
// 	// 	echo '';
// 	// else echo ':';



// 	// $retencion = ($row['ret_inicial']+$row['mas_retencion'])-$row['menos_retencion'];
// 	// $aporte = ($row['aporte_inicial']+$row['mas_aporte'])-$row['menos_aporte'];
// 	// $extra = ($row['extra_inicial']+$row['mas_extra'])-$row['menos_extra'];
// }

// $emontor = $emontoe = $emontoa = 0;
// $activos = $jubilados = $otros = $retirados = 0;
// $maxlineas=165;
// $pdf->SetFont('Arial','',10);
// $cont='0'; 
// $columna=0;

// // $sql = "select sum(mas_retencion), sum(mas_aporte), sum(menos_retencion), sum(menos_aporte) from result_socios";
// // $result_socios=mysql_query($sql) or die (mysql_error());
// // $row = mysql_fetch_array($result_socios);
// // echo $retenciones.'<br>';
// // echo $socios.'<br>';
// // var_dump($row);
// // die('espero');

// $sql = "select * from result_socios";
// $result_socios=mysql_query($sql) or die (mysql_error());
// while ($row = mysql_fetch_array($result_socios))
// {
// 	// $codigo = $row['codigo'];
// 	// echo 'b'.$codigo;
// 	$retencion = ($row['ret_inicial']+$row['mas_retencion'])-$row['menos_retencion'];
// 	$aporte = ($row['aporte_inicial']+$row['mas_aporte'])-$row['menos_aporte'];
// 	$extra = ($row['extra_inicial']+$row['mas_extra'])-$row['menos_extra'];
// 	//posicion celda, alto,contenido,bordes que mostramos(left,right top botton),0, alineacion izquierda,relleno
// 	if (($retencion+$aporte+$extra) > 0)
// 	{
// 		$linea+=4;
// 		$pdf->SetY($linea);
// 		$pdf->SetX($p[0+$columna]);
// 		$pdf->Cell($w[0+$columna],5,$row['ubicacion'],0,0,'LRTB',0); 
// 		$pdf->SetX($p[1+$columna]);
// 		$asterisco=substr($row['estatus'],0,1);
// 		if ($asterisco == 'A')
// 			$activos++;
// 		else 
// 			if ($asterisco == 'J')
// 				$jubilados++;
// 			else 
// 				if ($asterisco == 'R')
// 					$retirados++;
// 				else 
// 					$otros++;
// 		$pdf->Cell($w[1+$columna],5,$row['codigo'],0,0,'LRTB',0); 
// 		$codigo = $row['codigo'];

// 		$pdf->SetX($p[2+$columna]);
// 		if ($probar)
// 			$pdf->Cell($w[2+$columna],5,$row['ret_inicial'].'/' .$row['mas_retencion'],0,0,'LRTB',0); 
// 		else 
// 			$pdf->Cell($w[2+$columna],5,$row['cedula'].' ' .$asterisco,0,0,'LRTB',0); 
// 		$cont++; 
// 		$acotado = substr(trim($row["nombre"]).' '.trim($row["nombr_prof"]),0,30);
// 		$pdf->SetX($p[3+$columna]);
// 	    $pdf->Cell($w[3+$columna],5,$acotado,0,0,'LRTB',0);  

// 		$pdf->SetX($p[4+$columna]);
// 		$pdf->Cell($w[4+$columna],5,number_format($retencion,2,".",","),0,0,'R',0);
// 		$pdf->SetX($p[5+$columna]);
// 		$pdf->Cell($w[5+$columna],5,number_format($aporte,2,".",","),0,0,'R',0);
// 		$pdf->SetX($p[6+$columna]);
// 		$pdf->Cell($w[6+$columna],5,number_format($extra,2,".",","),0,0,'R',0);
// 		$pdf->SetX($p[7+$columna]);
// 		$pdf->Cell($w[7+$columna],5,number_format($retencion+$aporte+$extra,2,".",","),0,0,'R',0);

// 		$sql2 = "update historico_cierre_ahorros set hab_f_prof = $retencion, hab_f_empr = $aporte, hab_f_extr = $extra where cod_prof = '$codigo' and fecha_cierre = '$fechaaporte'";
// 		$result2=mysql_query($sql2) or die (mysql_error());


// 		// $pdf->SetX($p[8+$columna]);
// 		// $pdf->Cell($w[7+$columna],5,substr($row['estatus'],0,1),0,0,'R',0);
// 		// $pdf->Cell($w[8+$columna],5,substr($row['estatus'],0,1),0,0,'R',0);

// 		// ubicacion	codigo	cedula	nombre	ret_inicial	aporte_inicial	extra_inicial	mas_retencion	mas_aporte	mas_extra	menos_retencion	menos_aporte	menos_extra

// 		$emontor+=$retencion;
// 		$emontoa+=$aporte;
// 		$emontoe+=$extra;
// 		if ($linea >= $maxlineas)
// 		{
// 			{
// 				$linea+=5;
// 				$pdf->SetY($linea);
// 				$pdf->SetX($p[0]);
// 				$pdf->Cell(0,0,'  ',1,0,'L',0);
// 				// $linea+=5;
// 				$pdf->SetY($linea);
// 				$pdf->SetX($p[3]);
// 			    $pdf->Cell($w[3],5,'Van....',0,0,'C',0);  

// 				$pdf->SetX($p[4+$columna]);
// 				$pdf->Cell($w[4+$columna],5,number_format($emontor,2,".",","),0,0,'R',0);
// 				$pdf->SetX($p[5+$columna]);
// 				$pdf->Cell($w[5+$columna],5,number_format($emontoa,2,".",","),0,0,'R',0);
// 				$pdf->SetX($p[6+$columna]);
// 				$pdf->Cell($w[6+$columna],5,number_format($emontoe,2,".",","),0,0,'R',0);
// 				$pdf->SetX($p[7+$columna]);
// 				$pdf->Cell($w[7+$columna],5,number_format($emontor+$emontoa+$emontoe,2,".",","),0,0,'R',0);
// 				$linea+=5;
// 				$pdf->SetY($linea);
// 				$pdf->SetX($p[0]);
// 				$pdf->Cell(0,0,'  ',1,0,'L',0);

// 				$linea = encabezado($pdf, $linea, $header, $w, $p, $fechaaporte);
// 				/*
// 				$columna = 0;
// 				$pdf->AddPage();
// 				$linea=25;
// 				$pdf->SetY($linea);
// 				$pdf->SetX(0);
// 				$pdf->SetFont('Arial','B',18);
// 				$concepto='A';
// 				// $pdf->MultiCell(0,0,"Listado de Retención/Aportes al ".$fechanomina,0,C,0);
// 				$pdf->MultiCell(0,0,"Saldo de Haberes al ".convertir_fechadmy($fechaaporte),0,C,0);
// 				$pdf->SetY($linea);
// 				$pdf->SetFont('Arial','',10);
// 				$pdf->SetFillColor(255,255,255);
// 				$pdf->SetTextColor(0);

// 				$linea+=5;
// 				$pdf->SetFont('Arial','',10);
// 				$pdf->SetY($linea);
// 				$pdf->SetX($p[0]);
// 				$pdf->Cell(0,0,'  ',1,0,'L',0);

// 				$linea+=1;
// 				$pdf->SetY($linea);
// 				$pdf->SetFont('Arial','B',10);
// 				$pdf->SetX(30);
				
// 				//Cabecera

// 				for($i=0;$i<count($header);$i++)
// 				{
// 					$pdf->SetY($linea);
// 					$pdf->SetX($p[$i]);
// 				    $pdf->Cell($w[$i],5,utf8_encode($header[$i]),0,0,'C',1);
// 				}
// 				$linea+=5;
// 				$pdf->SetY($linea);
// 				$pdf->SetX($p[0]);
// 				$pdf->Cell(0,0,'  ',1,0,'L',0);
// 				$linea-=5;
// 				*/
// 			}
// 		}
// 		$pdf->SetFont('Arial','',10);
// 	}
// };
// $linea+=5;
// $pdf->SetY($linea);
// $pdf->SetX($p[0]);
// $pdf->Cell(0,0,'  ',1,0,'L',0);
// $pdf->SetY($linea);

// $pdf->SetX($p[3]);
// $pdf->Cell($w[3],5,'Total....',0,0,'C',0);  

// $pdf->SetX($p[4+$columna]);
// $pdf->Cell($w[4+$columna],5,number_format($emontor,2,".",","),0,0,'R',0);
// $pdf->SetX($p[5+$columna]);
// $pdf->Cell($w[5+$columna],5,number_format($emontoa,2,".",","),0,0,'R',0);
// $pdf->SetX($p[6+$columna]);
// $pdf->Cell($w[6+$columna],5,number_format($emontoe,2,".",","),0,0,'R',0);
// $pdf->SetX($p[7+$columna]);
// $pdf->Cell($w[7+$columna],5,number_format($emontor+$emontoa+$emontoe,2,".",","),0,0,'R',0);
// $linea+=5;
// $pdf->SetY($linea);
// $pdf->SetX($p[0]);
// $pdf->Cell(0,0,'  ',1,0,'L',0);


// $linea+=5;
// $pdf->SetY($linea);

// $pdf->SetX($p[0]);
// $pdf->Cell($w[0]+$w[1],5,'Activos: '.number_format($activos,0,".",","),1,0,'C',0);  

// // $pdf->SetX($p[1+$columna]);
// // $pdf->Cell($w[1+$columna],5,number_format($activos,0,".",","),0,0,'R',0);

// // $linea+=5;
// // $pdf->SetY($linea);

// $pdf->SetX($p[2]);
// $pdf->Cell($w[0]+$w[1],5,'Jubilados: '.number_format($jubilados,0,".",","),1,0,'C',0);  

// // $pdf->SetX($p[3+$columna]);
// // $pdf->Cell($w[3+$columna],5,number_format($jubilados,0,".",","),0,0,'R',0);

// $linea+=5;
// $pdf->SetY($linea);

// $pdf->SetX($p[0]);
// $pdf->Cell($w[0]+$w[1],5,'Retirados: '.number_format($retirados,0,".",","),1,0,'C',0);  

// // $pdf->SetX($p[5+$columna]);
// // $pdf->Cell($w[5+$columna],5,number_format($retirados,0,".",","),0,0,'R',0);


// // $linea+=5;
// // $pdf->SetY($linea);

// $pdf->SetX($p[2]);
// $pdf->Cell($w[0]+$w[1],5,'Otros: '.number_format($otros,0,".",","),1,0,'C',0);  

// // $pdf->SetX($p[4+$columna]);
// // $pdf->Cell($w[4+$columna],5,number_format($otros,0,".",","),0,0,'R',0);

// set_time_limit(30);

// $pdf->Output();



// // para los listados
// $archivo = 'reportesahorros/'.$fechaaporte.'.pdf';
// $sql = "SELECT * FROM sgcafnah where fecha='$fechaaporte'";
// $result=mysql_query($sql) or die (mysql_error());
// if (mysql_num_rows($result) < 1)
// {
// 	$sql = "insert into sgcafnah (fecha) values ('$fechaaporte')";
// 	$result=mysql_query($sql) or die (mysql_error());
// }
// if (!$probar)
// 	$pdf->Output($archivo);

// function encabezado($pdf, $linea, $header, $w, $p, $fechaaporte)
// {
// 	$pdf->Open();
// 	$pdf->AddPage();

// 	$linea=0;
// 	$linea=25;
// 	$pdf->SetY($linea);
// 	$pdf->SetX(0);
// 	$concepto='A';
// 	$pdf->SetFont('Arial','B',18);
// 	$pdf->MultiCell(0,0,"Saldo de Haberes al ".convertir_fechadmy($fechaaporte),0,C,0);
// 	$pdf->SetY($linea);
// 	$pdf->SetFont('Arial','',10);
// 	$pdf->SetFillColor(255,255,255);
// 	$pdf->SetTextColor(0);

// 	$linea+=5;
// 	$pdf->SetY($linea);
// 	$pdf->SetX($p[0]);
// 	$pdf->Cell(0,0,'  ',1,0,'L',0);
// 	// $linea+=5;
// 	for($i=0;$i<count($header);$i++)
// 	{
// 		$pdf->SetY($linea);
// 		$pdf->SetX($p[$i]);
// 	    $pdf->Cell($w[$i],5,utf8_decode($header[$i]),0,0,'C',1);
// 		// $pc+=$w[$i+1];
// 		// array_push($p,$pc);
// 	}

// 	$linea+=5;
// 	$pdf->SetY($linea);
// 	$pdf->SetX($p[0]);
// 	$pdf->Cell(0,0,'  ',1,0,'L',0);
// 	$linea-=5;
// 	return $linea;
// }


// function pasar_a_cierre_historico_limpio($link, $fechaaporte)
// {
// 	$sql = "delete from historico_cierre_ahorros where fecha_cierre = '$fechaaporte'";
// 	$result=mysql_query($sql) or die (mysql_error());
// 	$sql = "insert into historico_cierre_ahorros select *, '$fechaaporte' as fecha_cierre from sgcaf200";
// 	$result=mysql_query($sql) or die (mysql_error());
// 	$sql = "update historico_cierre_ahorros set hab_f_prof = 0, hab_f_empr = 0, hab_f_extr = 0 where  fecha_cierre = '$fechaaporte'";
// 	$result=mysql_query($sql) or die (mysql_error());
// }

// function ver_si_hay_nuevos($link, $fechaaporte)
// {
// 	$sql = "select cod_prof from historico_cierre_ahorros where fecha_cierre = '$fechaaporte'";
// 	$result=mysql_query($sql) or die (mysql_error());
// 	while ($row = mysql_fetch_array($result))
// 	{
// 		$codigo = $row['cod_prof'];
// 		$sql = "select cod_prof from iniciof200 where cod_prof = '$codigo'";
// 		$result2=mysql_query($sql) or die (mysql_error());
// 		if (mysql_num_rows($result2) < 1)
// 		{
// 	// 		// nuevo 
// 			$sql = "insert into iniciof200 select * from sgcaf200 where cod_prof='$codigo'";
// 			// echo $sql;
// 			$result3=mysql_query($sql) or die (mysql_error());
// 			$sql = "update iniciof200 set hab_f_prof = 0, hab_f_empr = 0, hab_f_extr = 0  where cod_prof='$codigo'";
// 			$result3=mysql_query($sql) or die (mysql_error());
// 		}
// 	}
// }

// ?> 
