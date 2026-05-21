<?php
session_start();
extract($_GET);
extract($_POST);
extract($_SESSION);

include("conex.php");
include("funciones.php");

$losregistros=$_GET['totalregistrosr'];
$saldos = $saldou = $cuantoss = $cuantosu = 0;
for ($i=0;$i<$losregistros;$i++)
{
//	${'cancelar'.$i};
//	$variable=${'cancelar'.$i};
	$variable='retencion'.($i+1);
//	echo 'la variable '.$$variable;
//	$sql="SELECT fecha, sum(cuota) as monto, count(fecha) as cuantos FROM sgcaamor where ((proceso = 1) and (semanal = 1)) and (fecha ='".$$variable."')  group by fecha";
		$sql="select fecha, count(fecha) as cantidad, sum(hab_prof) as socio, sum(hab_ucla) as ucla from t_his200 where (fecha ='".$$variable."') group by fecha";
//	echo $sql;
	$a_310=mysql_query($sql);
	$r_310=mysql_fetch_assoc($a_310);
	$saldos+=$r_310['socio'];
	$cuantoss+=$r_310['cantidad'];
}

$losregistros=$_GET['totalregistrosa'];
for ($i=0;$i<$losregistros;$i++)
{
//	${'cancelar'.$i};
//	$variable=${'cancelar'.$i};
	$variable='aporte'.($i+1);
//	echo 'la variable '.$$variable;
//	$sql="SELECT fecha, sum(cuota) as monto, count(fecha) as cuantos FROM sgcaamor where ((proceso = 1) and (semanal = 1)) and (fecha ='".$$variable."')  group by fecha";
		$sql="select fecha, count(fecha) as cantidad, sum(hab_prof) as socio, sum(hab_ucla) as ucla from t_his200 where (fecha ='".$$variable."') group by fecha";
//	echo $sql;
	$a_310=mysql_query($sql);
	$r_310=mysql_fetch_assoc($a_310);
	$saldou+=$r_310['ucla'];
	$cuantosu+=$r_310['cantidad'];
}

header("Content-Type: text/xml");
echo '<?xml version="1.0" encoding="utf-8"?>';
echo "<resultados>";
// echo utf8_encode("<cuota>$cuota</cuota>");		// sirve asi y como esta abajo tambien
echo "<totalregistrosocio>".number_format($cuantoss,0,'.','')."</totalregistrosocio>";
echo "<totalnominasocio>".number_format($saldos,2,'.',',')."</totalnominasocio>";

echo "<totalregistroucla>".number_format($cuantosu,0,'.','')."</totalregistroucla>";
echo "<totalnominaucla>".number_format($saldou,2,'.',',')."</totalnominaucla>";

echo "<totalregistros>".number_format(($cuantoss+$cuantosu),0,'.','')."</totalregistros>";
echo "<totalnominas>".number_format(($saldos+$saldou),2,'.',',')."</totalnominas>";
echo "</resultados>";


?>