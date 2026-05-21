<?php
include("head.php");

extract($_GET);
extract($_POST);
extract($_SESSION);


if (!$link OR !$_SESSION['empresa']) {
	include("noempresa.php");
	exit;
}

$readonly=" readonly='readonly'";
include("arriba.php");
// $menu61=1;
include("menusizda.php");
$ip = $_SERVER['HTTP_CLIENT_IP'];
if (!$ip) {$ip = $_SERVER['REMOTE_ADDR'];}
$sql="select * from sgcaf360 where cod_pres='043'";
$res360=mysql_query($sql);
$fila360 = mysql_fetch_assoc($res360);
$sql="select * from polizadif where codpre_sdp='043' AND f_1cuo_sdp='2015-07-31'";
$res200=mysql_query($sql);
$cuenta=$fila360['cuent_pres'];

$hoy=date("Y-m-d", time());
$b=$hoy;
$asiento=date("Ymd", time());
$ultimo="select (con_compr+1) as nuevo from sgcaf8co limit 1";
$aultimo=mysql_query($ultimo);
$rultimo=mysql_fetch_assoc($aultimo);
$elultimo=$rultimo['nuevo'];
$elultimo=ceroizq($elultimo,3);
$ultimo="update sgcaf8co set con_compr ='$elultimo' limit 1";
$aultimo=mysql_query($ultimo);
$asiento.=$elultimo;
$elprestamo='043';
//	echo 'el asiento '.$asiento;
echo "Generando encabezado contable <strong><a target=\"_blank\" href='editasi2.php?asiento=$asiento'>$asiento </a></strong> <br>";
$cuento='p/r Cargo Diferencia Poliza 2015';
$sql = "INSERT INTO sgcaf830 (enc_clave, enc_fecha, enc_desco, enc_desc1, enc_debe, enc_haber, enc_item, enc_dif, enc_igual, enc_refer, enc_sw, enc_explic) VALUES ('$asiento', '$b', '','',0,0,0,0,0,0,0,'$cuento')"; 
if (!mysql_query($sql)) die ("El usuario $usuario no tiene permiso para añadir Asientos.<br>".$sql);
$totalesgral = $intereses_diferidos = $inicial = 0;
$fechaacta=$hoy;
while ($fila200 = mysql_fetch_assoc($res200))
{
	$codigo=substr($fila200['codsoc_sdp'],1,4);
	$laparte=$fila200['codsoc_sdp'];
	$elnumero=$fila200['nropre_sdp'];
	$estatus='A';
	$cuenta1=$cuenta.'-'.$codigo;
	$debe=$fila200['monpre_sdp'];
	$totalesgral+=$debe;
	agregar_f820($asiento, $b, '+', $cuenta1, 'Dif. Poliza 2015', $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
	$sql="select cue_codigo from sgcaf810 where cue_codigo='$cuenta1'";
	$res810=mysql_query($sql);
}
$debe=$totalesgral;
$cuenta1='1-01-02-05-02-01-0008';
agregar_f820($asiento, $b, '-', $cuenta1, 'Dif. Poliza 2015', $debe, $haber, 0,$ip,0,$referencia,'','S',0); 

?>

<?php include("pie.php");?>

</body></html>

