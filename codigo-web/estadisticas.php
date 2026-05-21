<?php
include("head.php");
include("paginar.php");
extract($_GET);
extract($_POST);
extract($_SESSION);


if (!$link OR !$_SESSION['empresa']) {
	include("noempresa.php");
	exit;
}
?>
<body <?php if (!$bloqueo) {echo $onload;}?>>
<script src="ajaxdev.js" type="text/javascript"></script>
<script language="Javascript" src="selec_fecha.js" type='text/javascript'></script>

<?php

include("arriba.php");
// $menu61=1;
include("menusizda.php");
$ip = $_SERVER['HTTP_CLIENT_IP'];
if (!$ip) {$ip = $_SERVER['REMOTE_ADDR'];}

$ano='2015';

echo '<table>';
$sqlnuevos="select count(cod_prof) as cuantos from sgcaf200 where substr(f_ing_capu,1,4)='$ano' group by substr(f_ing_capu,1,4)";
$result=mysql_query($sqlnuevos);
$rnuevo=mysql_fetch_assoc($result);
echo "<tr><td colspan='3' align='center'><strong>Estadisticas Año $ano</strong> </td></tr>";
echo "<tr><td align='center'>Concepto</td><td align='center'>Detalles</td><td align='center'>Monto(s)</td>";
echo "<tr><td>Socios Inscritos: </td><td>".$rnuevo['cuantos'].'</td>';

$sqlnuevos="select count(tiporeti) as cuantos, sum(ret_ucla) as ucla, sum(ret_capu) as cappo, sum(ret_opsu) as opsu from sgcaf700 where (substr(fechareti,1,4)='$ano' and tiporeti='01') group by substr(fechareti,1,4)";
$result=mysql_query($sqlnuevos);
$rnuevo=mysql_fetch_assoc($result);
echo "<tr><td>Socios Retirados Total: </td><td>".$rnuevo['cuantos']. ' UCLA: <br>Socio:<br> Opsu</td><td align="right">'.number_format($rnuevo['ucla'],2,'.',',') . ' <br>'.number_format($rnuevo['cappo'],2,'.',','). ' <br> '.number_format($rnuevo['opsu'],2,'.',',');
echo '</td></tr>';

$sqlnuevos="select count(codpre_sdp) as cuantos, sum(monpre_sdp) as prestado from sgcaf310 where (substr(f_soli_sdp,1,4)='$ano' and ((codpre_sdp='023') or (codpre_sdp='053') or (codpre_sdp='057') or (codpre_sdp='058'))) group by substr(f_soli_sdp,1,4)";
$result=mysql_query($sqlnuevos);
$rnuevo=mysql_fetch_assoc($result);
echo "<tr><td>Prestamos Flash (23-53-57-58): </td><td align='right'>".$rnuevo['cuantos']. ' </td><td align="right"> '.number_format($rnuevo['prestado'],2,'.',',');
echo '</td></tr>';

echo "<tr><td colspan='3' align='center'><strong>Detalle de Prestamos</strong></td></tr>";
$sql360="select * from sgcaf360 order by cod_pres";
$a360=mysql_query($sql360);
while ($fila = mysql_fetch_assoc($a360)) {
	$cod=$fila['cod_pres'];
	$sqlnuevos="select count(codpre_sdp) as cuantos, sum(monpre_sdp) as prestado from sgcaf310 where (substr(f_soli_sdp,1,4)='$ano' and (codpre_sdp='$cod')) group by substr(f_soli_sdp,1,4)";
	$result=mysql_query($sqlnuevos);
	$rnuevo=mysql_fetch_assoc($result);
	if ($rnuevo['cuantos'] > 0)
	{
		echo "<tr><td>".$fila['cod_pres'].' '.$fila['descr_pres'].": </td><td align='right'>".number_format($rnuevo['cuantos'],0,'.',''). '</td><td align="right"> '.number_format($rnuevo['prestado'],2,'.',',');
		echo '</td></tr>';
	}
}

?>
<?php include("pie.php");?>

</body></html>

