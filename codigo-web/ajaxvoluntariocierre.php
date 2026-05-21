<?php
session_start();
extract($_GET);
extract($_POST);
extract($_SESSION);

include("conex.php");
include("funciones.php");

$suma=$reintegros=0;
/*
// $suma='xx--'.$_GET['totalregistros'].'--xx';
$losregistros=$_GET['totalregistros'];
for ($i=0;$i<$losregistros;$i++)
{
//	${'cancelar'.$i};
//	$variable=${'cancelar'.$i};
	$variable='cancelar'.($i+1);
//	echo 'la variable '.$$variable;
	$sql="SELECT fecha, sum(cuota) as monto, count(fecha) as cuantos FROM t_his200 where ((hab_prof > 0) and (fecha ='".$$variable."') and (pertenece = ".$$variable2.") group by fecha";
	$sql='SELECT fecha, sum(hab_prof) as monto, count(fecha) as cuantos FROM t_his200 where hab_prof > 0 and (fecha > "'.$viejo.'") and pertenece IS NOT NULL group by fecha desc, pertenece asc ';
//	echo $sql;
	$a_310=mysql_query($sql);
	$r_310=mysql_fetch_assoc($a_310);
	$saldo+=$r_310['monto'];
	$cuantos+=$r_310['cuantos'];
}
*/
	$fecha = $_GET['fecha'];

	$sql="SELECT fecha, sum(hab_voluntario) as monto, count(fecha) as cuantos 
		FROM t_his200 
		where ((hab_voluntario > 0) and (fecha ='$fecha')) group by fecha";
	// $sql='SELECT fecha, sum(hab_prof) as monto, count(fecha) as cuantos FROM t_his200 where hab_prof > 0 and (fecha > "'.$viejo.'") and pertenece IS NOT NULL group by fecha desc, pertenece asc ';
	// echo $sql;
	$a_310=mysql_query($sql);
	$r_310=mysql_fetch_assoc($a_310);
	$saldo+=$r_310['monto'];
	$cuantos+=$r_310['cuantos'];

header("Content-Type: text/xml");
echo '<?xml version="1.0" encoding="utf-8"?>';
echo "<resultados>";
// echo utf8_encode("<cuota>$cuota</cuota>");		// sirve asi y como esta abajo tambien
echo "<totalregistros>".number_format($cuantos,0,'.','')."</totalregistros>";
echo "<totalnominas>".number_format($saldo,2,'.',',')."</totalnominas>";
echo "<fechanomina>".$fecha."</fechanomina>";
// echo "<tiponomina>".$tipo."</tiponomina>";
echo "</resultados>";


?>