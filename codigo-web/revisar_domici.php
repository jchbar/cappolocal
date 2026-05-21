<?php
// 101
// 102
// 116
// 117


error_reporting(E_ALL);
ini_set('display_errors',true);
session_start();
extract($_GET);
extract($_POST);
extract($_SESSION);
include("conex.php");
set_time_limit(1000);
$procesar = $_GET['procesar'];

if ($procesar == 1)
{
	$sql="select cod_prof from sgcaf200"; //  where cod_prof='00114'";
	$result= mysql_query($sql) or die('Error 200');
	while ($fila = mysql_fetch_assoc($result)) {
		$codigo = $fila['cod_prof'];
		$sql = "update sgcaf200 set domiciliacion_buena=0, domiciliacion_falla = 0, ultimo_falla=NULL, ultimo_buena=NULL where cod_prof='$codigo'";
		$result2= mysql_query($sql) or die('Error h200');
		// $sql = "select * from fhis200 where cod_prof='$codigo' and substr(fecha,1,4)>='2023' order by fecha";

		$sql = "select fecha from fhis200 where cod_prof='$codigo' and substr(fecha,1,4)>='2023' group by fecha order by fecha ";
		// echo $sql;
		// , substr(trim(descri), -5)
		// , substr(trim(descri), -5) 
		$result2= mysql_query($sql) or die('Error h200');
		$fechas = array();
		$contador = 0;
		while ($fila2 = mysql_fetch_assoc($result2)) {
			array_push($fechas, $fila2['fecha']);
			$fecha = $fila2['fecha'];
			// var_dump($fecha);


			$sql = "select cod_prof, count('*') as cuantos, fecha, substr(descri, 1,15) as descripcion, substr(trim(descri), -5) as respuesta  from fhis200 where cod_prof='$codigo' and fecha='$fecha' group by fecha, substr(descri, 1,15), substr(trim(descri), -5) order by fecha ";
			// echo $sql;
			$autoriza = false;
			$result4= mysql_query($sql) or die('Error h200');
			while ($fila4 = mysql_fetch_assoc($result4)) {
				// $arreglo = array();
				// $arreglo_falla = array();
				$contador++;
				// echo $contador.'->'.$fila4['cod_prof'].' ' .$fila4['descripcion'].$fila4['respuesta'].$fila4['cuantos'];
				if ($fila4['respuesta'] == 'AUTOR')
					$autoriza = true;
			}
			// echo ' '.$autoriza.'<br>';
			if ($autoriza)
			{
				$sql = "update sgcaf200 set domiciliacion_buena=domiciliacion_buena+1, ultimo_buena='".$fecha."' where cod_prof='$codigo'";
				// domiciliacion_falla = 0, 
				$result3= mysql_query($sql) or die('Error 200-3');
			}
			else 
			{
				$sql = "update sgcaf200 set domiciliacion_buena=0, domiciliacion_falla = domiciliacion_falla+1, ultimo_falla='".$fecha."' where cod_prof='$codigo'";
				$result3= mysql_query($sql) or die('Error 200-3');
			}
		}
	}
}
// $sql="select cod_prof, domiciliacion_buena, domiciliacion_falla, ultimo_falla, ultimo_buena   from sgcaf200 where cod_prof='00114'";
// $result= mysql_query($sql) or die('Error 200');
// echo '<br>'. $sql;
// set_time_limit(30);

$sql = "select date_sub(now(),interval 90 day) as fechaAnterior";
$result= mysql_query($sql) or die('Error fechaAnterior');
$filaA = mysql_fetch_assoc($result);
$fechaAnterior = $filaA['fechaAnterior'];
$fechaAnterior = substr($fechaAnterior,0,10);
// echo 'fechaAnterior'.$fechaAnterior;

// $sql = "SELECT cod_prof, ape_prof, nombr_prof, domiciliacion_falla, ultimo_falla FROM `sgcaf200` WHERE (datediff(NOW(), '".$fechaAnterior."')) between 15 and 90) order by domiciliacion_falla desc";
$sql = "SELECT cod_prof, ced_prof, ape_prof, nombr_prof, domiciliacion_falla, date_format(ultimo_falla,'%d/%m/%Y') as ultimo_fallaf, date_format(date_add(ultimo_falla, interval 90 day),'%d/%m/%Y') as hasta FROM `sgcaf200` WHERE ultimo_falla between '".$fechaAnterior."' and NOW() order by ultimo_falla desc";
// echo $sql;
$result= mysql_query($sql) or die('Error 200');
$contador=0;

echo '<table>';
while ($fila = mysql_fetch_assoc($result)) {
	$contador++;
	// echo $contador.' '.$fila['cod_prof']. ' '.$fila['ced_prof'].' '.$fila['ape_prof'].' '.$fila['nombr_prof'].' '.$fila['ultimo_falla'].'<br>';
	echo '<tr>';
		echo '<td>'.$contador.'</td>';
		echo '<td>'.$fila['cod_prof'].'</td>';
		echo '<td>'.$fila['ced_prof'].'</td>';
		echo '<td>'.$fila['ape_prof']. ' '.$fila['nombr_prof'].'</td>';
		echo '<td>'.$fila['domiciliacion_falla'].'</td>';
		echo '<td>'.$fila['ultimo_fallaf'].'</td>';
		echo '<td>'.$fila['hasta'].'</td>';
	echo '</tr>';
}
echo '</table>';