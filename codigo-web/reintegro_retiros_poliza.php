<?php
include("head.php");

extract($_GET);
extract($_POST);
extract($_SESSION);


if (!$link OR !$_SESSION['empresa']) {
	include("noempresa.php");
	exit;
}

$fecha = '2018-12-29';
$codigo = '043';
$nuevafecha = '2018-12-26';
$sql = "SELECT registro FROM sgcaf310, sgcaf200 WHERE ((f_1cuo_sdp =  '".$fecha."') AND codpre_sdp =  '".$codigo."' AND polizaactiva =0) AND codsoc_sdp = cod_prof and tipo_socio='P'";
$res200=mysql_query($sql);
while ($fila200 = mysql_fetch_assoc($res200))
{
	$sql = "update sgcaf310 set f_1cuo_sdp='".$nuevafecha."', stapre_sdp='A', renovado=0 where registro = ".$fila200['registro'];
	echo $fila200['registro'].'<br>';
	if (!mysql_query($sql)) die ("El usuario $usuario no tiene permiso para añadir Asientos.<br>".$sql);
}

?>

<?php include("pie.php");?>

</body></html>

