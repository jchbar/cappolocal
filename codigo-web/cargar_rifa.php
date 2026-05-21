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
$sql="select * from sgcaf360 where cod_pres='RIF'";
$res360=mysql_query($sql);
$fila360 = mysql_fetch_assoc($res360);
$sql="select * from sgcaf200 where upper(statu_prof)='ACTIVO' or upper(statu_prof)='JUBILA' order by cod_prof";
$res200=mysql_query($sql);
$monpre_sdp=400;
$primerdcto='2014-12-31';
$lascuotas=2;
$cuota=200;
$cuenta=$fila360['cuent_pres'];
$interes_sd=0;

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
$elprestamo='RIF';
//	echo 'el asiento '.$asiento;
echo "Generando encabezado contable <strong><a target=\"_blank\" href='editasi2.php?asiento=$asiento'>$asiento </a></strong> <br>";
$cuento='p/r Cargo Rifa  ';
$sql = "INSERT INTO sgcaf830 (enc_clave, enc_fecha, enc_desco, enc_desc1, enc_debe, enc_haber, enc_item, enc_dif, enc_igual, enc_refer, enc_sw, enc_explic) VALUES ('$asiento', '$b', '','',0,0,0,0,0,0,0,'$cuento')"; 
if (!mysql_query($sql)) die ("El usuario $usuario no tiene permiso para añadir Asientos.<br>".$sql);
$totalesgral = $intereses_diferidos = $inicial = 0;
$fechaacta=$hoy;
while ($fila200 = mysql_fetch_assoc($res200))
{
	$codigo=substr($fila200['cod_prof'],1,4);
	$cedula=$fila200['ced_prof'];
	$micedula=substr($cedula,0,4).'.'.substr($cedula,4,3).'.'.substr($cedula,7,4);
	$laparte=$fila200['cod_prof'];
	$elnumero=numero_prestamo($micedula, $laparte);
	$estatus='A';
	$sql="insert into sgcaf310 (codsoc_sdp, cedsoc_sdp, nropre_sdp, codpre_sdp, f_soli_sdp, f_1cuo_sdp, monpre_sdp, monpag_sdp, nrofia_sdp, stapre_sdp, tipo_fianz, cuota, nrocuotas, interes_sd, cuota_ucla, netcheque, nro_acta, fecha_acta, ip, inicial, intereses, quien) values ('$laparte', '$micedula', '$elnumero','$elprestamo','$hoy', '$primerdcto', $monpre_sdp, 0, 0, '$estatus', '',$cuota, $lascuotas, $interes_sd, $cuota, $monpre_sdp, '$nroacta', '$fechaacta', '$ip', $inicial, $intereses_diferidos, '".$_SERVER['REMOTE_ADDR']."')";
	if (!mysql_query($sql)) die ("El usuario $usuario no tiene permiso para añadir Asientos.<br>".$sql);
	$cuenta1=$cuenta.'-'.$codigo;
	$debe=$monpre_sdp;
	$totalesgral+=$debe;
	agregar_f820($asiento, $b, '+', $cuenta1, 'Prestamo RIFA ', $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
	$sql="select cue_codigo from sgcaf810 where cue_codigo='$cuenta1'";
	$res810=mysql_query($sql);
	if (mysql_num_rows($res810) < 1)
	{
		$sql="insert into sgcaf810 (cue_codigo, cue_nombre, cue_nivel, cue_saldo) values ('$cuenta1', '".trim($fila200['ape_prof']).' '.trim($fila200['nombr_prof'])."', '7', 0)";
		$res810=mysql_query($sql);
	}
}
$debe=$totalesgral;
agregar_f820($asiento, $b, '-', $cuenta1, 'Prestamo RIFA ', $debe, $haber, 0,$ip,0,$referencia,'','S',0); 

?>

<?php include("pie.php");?>

</body></html>

