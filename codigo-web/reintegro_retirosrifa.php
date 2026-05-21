<?php
include("head.php");

extract($_GET);
extract($_POST);
extract($_SESSION);


if (!$link OR !$_SESSION['empresa']) {
	include("noempresa.php");
	exit;
}

$fecha = '2018-12-31';
$codigo = 'RIF';
$nuevafecha = '2018-12-26';
$sql = "SELECT registro, cod_prof, monpag_sdp  FROM sgcaf310, sgcaf200 WHERE ((f_1cuo_sdp =  '".$fecha."') AND codpre_sdp =  '".$codigo."' AND stapre_sdp='C') AND codsoc_sdp = cod_prof and tipo_socio='P'";
echo $sql;
$res200=mysql_query($sql);
$cuantos=0;
while ($fila200 = mysql_fetch_assoc($res200))
{
	$sql = "update sgcaf310 set f_1cuo_sdp='".$nuevafecha."', stapre_sdp='A', renovado=0, monpre_sdp=".$fila200['monpag_sdp'].", monpag_sdp=0, netcheque = ".$fila200['monpag_sdp']." where registro = ".$fila200['registro'];
	$cuantos++;
	echo $fila200['registro'].' - '.$fila200['cod_prof'].' - '.$fila200['monpag_sdp'].'<br>';
	if (!mysql_query($sql)) die ("El usuario $usuario no tiene permiso para añadir Asientos.<br>".$sql);
	$sql = "update sgcaf200 set rifa_2018 =0 where cod_prof = '".$fila200['cod_prof']."'";
	if (!mysql_query($sql)) die ("El usuario $usuario no tiene permiso <br>".$sql);
}

echo 'cuantos = '.$cuantos;
?>

<?php include("pie.php");?>

</body></html>

