<?php

$asient='20140918';
$numero=99;
$fecha='2014-09-18';
$ip = $_SERVER['HTTP_CLIENT_IP'];
if (!$ip) {$ip = $_SERVER['REMOTE_ADDR'];}

require("final.php");
$link = @mysql_connect($Servidor,$Usuario, $Password,'',65536) or die ("<p /><br /><p /><div style='text-align:center'>Disculpe... En estos momentos no hay conexión con el servidor, estamos realizando modificaciones.... inténtalo más tarde. Gracias....</div>");
mysql_select_db('sica', $link);

$sql="select fecha from sgcaamor where fecha>='2014-06-06' and proceso=2 group by fecha ";
$rsql=mysql_query($sql);
while($r=mysql_fetch_assoc($rsql)) 
{
	$buscar=$r['fecha'];
	$numero++;
	$continuo=ceroizq($numero,3);
	$asiento=$asient.$continuo;
	$total = $debe = $haber = 0;

	echo "Realizando Abonos / Registros contables del asiento <strong><a target=\"_blank\" href='editasi2.php?asiento=$asiento'>$asiento </a></strong> $cuento <br>";
	$cuento = "Amort. No Registr. ".convertir_fechadmy($buscar);
	$sql = "INSERT INTO sgcaf830 (enc_clave, enc_fecha, enc_desco, enc_desc1, enc_debe, enc_haber, enc_item, enc_dif, enc_igual, enc_refer, enc_sw, enc_explic) VALUES ('$asiento', '$fecha', '$cuento','',0,0,0,0,0,0,0,'$cuento')"; 
	if (!mysql_query($sql)) die ("El usuario $usuario no tiene permiso para añadir Asientos.<br>".$sql);

	$sql1="select * from sgcaamor where fecha='$buscar' and proceso=2 order by codsoc, fecha ";
	$rsql1=mysql_query($sql1);
	while($r1=mysql_fetch_assoc($rsql1)) 
	{
		$cuenta = $r1['cuent_p'];
		$capital = $r1['capital'];
		$fecha = $r1['fecha'];
		$referencia = $r1['nropre'];
		$sql2="select * from sgcaf820 where com_cuenta = '$cuenta' and com_monto2=$capital and com_fecha='$fecha'";
		$r2=mysql_query($sql2);
		if (mysql_num_rows($r2) < 1)
		{
			$debe=$capital;
			agregar_f820($asiento, $fecha, '-', $cuenta, 'Ret. Prest.Est. del '.convertir_fechadmy($buscar), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
			$total+=$debe;
			echo "Agregado $cuenta por $debe";
		}
	}
	$debe=$total;
	$cuenta='1-02-01-01-03-03-0003';
	agregar_f820($asiento, $fecha, '+', $cuenta, 'Amort. No Registr. '.convertir_fechadmy($buscar), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
}


function agregar_f820 ($pcom_nrocom, $pcom_fecha, $pcom_debcre, $pcom_cuenta, $pcom_descri, $elmonto, $pcom_monto2, $pcom_monto, $pcom_ip, $pcom_nroite, $pcom_refere, $pcom_tipmov, $agregar, $registro)
{
	$pcom_monto1 = $pcom_monto2 = 0;
	if (($pcom_debcre =='+') or ($pcom_debcre == '1') or ($pcom_debcre == on)) 
		{ $pcom_monto1=$elmonto; $pcom_debcre = '+';}
		else { $pcom_debcre= '-';  $pcom_monto2 = $elmonto;} 
	if ($agregar == 'S') {
		$elsql="INSERT INTO sgcaf820 (
com_nrocom, com_fecha, com_debcre, com_cuenta, com_descri, com_monto1, com_monto2, com_monto, com_ip, com_nroite, com_refere, com_tipmov) VALUES (
'$pcom_nrocom', '$pcom_fecha', '$pcom_debcre', '$pcom_cuenta', '$pcom_descri', '$pcom_monto1', '$pcom_monto2', '$pcom_monto', '$pcom_ip', '$pcom_nroite', '$pcom_refere', '$pcom_tipmov')"; 
//		$elsql="call sp_inc_r_820 (
// '$pcom_nrocom', '$pcom_fecha', '$pcom_debcre', '$pcom_cuenta', '$pcom_descri', '$pcom_monto1', '$pcom_monto2', '$pcom_monto', '$pcom_ip', '$pcom_nroite', '$pcom_refere', '$pcom_tipmov')"; 	
}
	else if ($agregar == 'N') {
			$elsql="UPDATE sgcaf820 SET com_debcre='$pcom_debcre', com_cuenta='$pcom_cuenta', com_descri='$pcom_descri', com_monto1='$pcom_monto1', com_monto2='$pcom_monto2', com_ip='$pcom_ip', com_nroite='$pcom_nroite', com_refere='$pcom_refere', com_tipmov='$pcom_tipmov' WHERE nro_registro=$registro"; 
		}
		else {
			$elsql="DELETE FROM sgcaf820 WHERE nro_registro = $registro";
			}
//	echo $elsql.'<br>';
	$rs=(mysql_query($elsql)) or die ("<p />Estimado usuario $usuario contacte al administrador Código 820-1- <br>".mysql_error()."<br>".$elsql);
// $final = explode(" ", microtime());
// $tiempo = ($final[1] + $final[0]) - ($comienzo[1] - $comienzo[0]); 
// echo "comando ejecutado en $tiempo segundos";
	
	$elsql="SELECT SUM(com_monto1) as debe, SUM(com_monto2) AS haber, COUNT(com_nrocom) as items FROM sgcaf820 WHERE com_nrocom='$pcom_nrocom'";
	$rs=(mysql_query($elsql)) or die ("<p />Estimado usuario $usuario contacte al administrador Código 830-1");
	$fila = mysql_fetch_assoc($rs);
	if (mysql_num_rows($rs) > 0) {
		$elsql="UPDATE sgcaf830 SET enc_debe='$fila[debe]', enc_haber='$fila[haber]', enc_item='$fila[items]',enc_fecha='$pcom_fecha' WHERE enc_clave = '$pcom_nrocom'";
// 		echo $elsql;
		$rs=(mysql_query($elsql)) or die ("<p />Estimado usuario $usuario contacte al administrador Código 830-2<br>".$sql);
	}
	// actualizar los niveles en la 810
	$losniveles = mysql_query("SELECT * FROM sgcafniv order by con_nivel"); 
	if (mysql_num_rows($losniveles) == 0) {
		die("<p /><br /><p />No se han definido los niveles<span class='b'> error Niv-1</span> en la tabla");
		exit;
	}
	
	$elmes=strtotime($pcom_fecha);
	$elmes=date("m", $elmes);
	$primero=strlen($elmes);
//	echo $pcom_fecha.'-'.$elmes . '-'.$primero;
	if (($elmes < 10) and ($primero < 2)) $elmes='0'.$elmes;
	for ($i = mysql_num_rows($losniveles) - 1; $i >= 0; $i--) {
    	if (!mysql_data_seek($losniveles, $i)) {
	        echo "Cannot seek to row $i: " . mysql_error() . "\n";
	        continue;
    	}
	    if (!($niveles = mysql_fetch_assoc($losniveles))) {
	        continue;
	    }

		$fila = $niveles ;
		$elnivel=$fila['con_nivel'];
		$codigo=substr($pcom_cuenta,0,$elnivel);
		$debito='cue_deb'.$elmes;
		$credito='cue_cre'.$elmes;
		$eldebe=$pcom_monto1;
		$elhaber=$pcom_monto2;
		$sql="update sgcaf810 set $debito=$debito+'$eldebe', $credito=$credito+'$elhaber' where cue_codigo='$codigo'";
//		echo $sql."<br>";
		$result = mysql_query($sql) or die('Error en la F810-3 '.$sql.' '.mysql_error()); 		
	}
}

function convertir_fechadmy($mifecha)
{
//	$mifecha=strtotime($mifecha);
//	echo $mifecha;
	$a=explode("-",$mifecha); 
	$elano=substr($a[0],0,2);
	if ($elano="20") $b=$a[2]."/".$a[1]."/".trim($a[0]);
	else $b=$a[2]."/".$a[1]."/"."20".trim($a[0]);
//	if ($elano="20") $b=(($a[2]<10)?'0'.$a[2]:$a[2])."/".(($a[1]<10)?'0'.$a[1]:$a[1])."/".$a[0];
//	else $b=$b=(($a[2]<10)?'0'.$a[2]:$a[2])."/".(($a[1]<10)?'0'.$a[1]:$a[1])."/"."20".$a[0];
	if ($mifecha=='--') $b='00/00/0000';
return $b;
}

function ceroizq($laultima,$digitos)
{
	$tamano=$digitos-strlen($laultima);
	$nuevacadena="";
	// echo $tamano;
	// (5-$tamano)=$posicion)
	for ($posicion=1;$posicion <= $tamano;$posicion++) {
		$nuevacadena=$nuevacadena."0"; 
		// echo $nuevacadena."-";
		}
		// echo $nuevacadena."---------".$laultima;
	$nuevacadena=$nuevacadena.$laultima;
	// echo $nuevacadena;
	return $nuevacadena;
		
}

?>
