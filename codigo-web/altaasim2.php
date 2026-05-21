<?php

//Copyright (C) 2000-2006  Antonio Grandío Botella http://www.antoniograndio.com
//Copyright (C) 2000-2006  Inmaculada Echarri San Adrián http://www.inmaecharri.com

//This file is part of Catwin.

//CatWin is free software; you can redistribute it and/or modify
//it under the terms of the GNU General Public License as published by
//the Free Software Foundation; either version 2 of the License, or
//(at your option) any later version.

//CatWin is distributed in the hope that it will be useful,
//but WITHOUT ANY WARRANTY; without even the implied warranty of
//MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//GNU General Public License for more details:
//http://www.gnu.org/copyleft/gpl.html

//You should have received a copy of the GNU General Public License
//along with Catwin Net; if not, write to the Free Software
//Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

if (!$link OR !$_SESSION['empresa']) {
	return;
}

/* *********** COMPROBACIÓN Nº ASIENTO **************** */

if (mysql_num_rows(mysql_query("SELECT enc_clave FROM sgcaf830 WHERE enc_clave = '$asiento'")) AND $_GET['n'] == 1){
	$mensaje = "No se ha añadido el Asiento: Asiento <span class='b'>$asiento</span> ya existe.<p />";
}

/* ***************COMPROBACIÓN AÑO ****************** */

// $result = mysql_query("SELECT anocont FROM empresa");
$result = mysql_query("SELECT date_format(con_lpini,'%y') AS anocont FROM sgcaf8co");
$fila = mysql_fetch_array($result);

$b = explode("/",$fecha);

// if ($fila[0] != "20".$b[2]) { $mensaje = "No se ha añadido el Asiento: El año no es el del ejercicio actual ($fila[0])<p />"; }

/* **************************************************** */

if (trim($mensaje) == "" ) { // AND $asiento <= 9999999000

//	$a=explode("/",$fecha); 
	$a=explode("/",$_POST['fecha']); 
	$b=$a[2]."-".$a[1]."-".$a[0];
	$nomatach = $_FILES['fich']['name'];
	if ($nomatach) {
		$tipo1    = $_FILES["fich"]["type"];
		$archivo1 = $_FILES["fich"]["tmp_name"];
		$tamanio1 = $_FILES["fich"]["size"];
		$fp = fopen($archivo1, "rb");
	    $contenido = fread($fp, $tamanio1);
	    $contenido = addslashes($contenido);
	    fclose($fp);
	}
	
	if ((reviso($cuenta1)) AND (reviso($cuenta2))) {

		if (!mysql_num_rows(mysql_query("SELECT enc_clave FROM sgcaf830 WHERE enc_clave = '$asiento'"))){
			$sql = "INSERT INTO sgcaf830 (enc_clave, enc_fecha, enc_desco, enc_desc1, enc_debe, enc_haber, enc_item, enc_dif, enc_igual, enc_refer, enc_sw, enc_explic) VALUES ('$asiento', '$b', '','',0,0,0,0,0,0,0,\"$explicacion\")"; 
			if (!mysql_query($sql)) die ("El usuario $usuario no tiene permiso para añadir Asientos.");
		}
		if ($nomatach) {
			mysql_query("UPDATE sgcaf830 SET enc_explic = \"$explicacion\" WHERE enc_clave = '$asiento'");
		}
		$ip = $_SERVER['HTTP_CLIENT_IP'];
		if (!$ip) {$ip = $_SERVER['REMOTE_ADDR'];}

//		$sql = "INSERT INTO sgcaf820 (com_nrocom, com_fecha, com_debcre, com_cuenta, com_descri, com_monto1, com_monto2, com_monto, com_ip, com_nroite, com_refere, com_tipmov) VALUES ('$asiento', '$b', '', '$cuenta1', '$concepto', '$debe', '$haber', 0,'$ip',0,$referencia,'')";
//		$sql = "INSERT INTO apuntes (asiento, fecha, tipo, cuenta, concepto, debe, haber, ip) VALUES ('$asiento', '$b', '$tipo', '$cuenta1', '$concepto', '$debe', '$haber', '$ip')";
//		$sql = "call sp_inc_r_820 ('$asiento', '$b', '', '$cuenta1', '$concepto', '$debe', '$haber', 0,'$ip',0,$referencia,'')";
//		if (!mysql_query($sql)) die ("El usuario $usuario no tiene permiso para añadir Apuntes.");
		$elcargo='+';
		$debe=$elmonto;
		agregar_f820($asiento, $b, '+', $cuenta1, $concepto, $debe, $haber, 0,$ip,0,$referencia,'','S',0);
		$elcargo='-';
		agregar_f820($asiento, $b, '-', $cuenta2, $concepto, $debe, $haber, 0,$ip,0,$referencia,'','S',0);
		mysql_query("UPDATE sgcaf8co SET con_ultfec = '$b'");
		$anadido = 1;
	}
	else { echo '<h2> No se ha agregado información, una de las cuentas presenta problemas</h2>'; }
}

function reviso($lacuenta)
{
	$sql2="SELECT * FROM sgcaf810 where cue_codigo ='$lacuenta'";
	$salida=mysql_query($sql2);
//	echo $sql2;
	$filas = mysql_num_rows($salida);
	return ($filas == 0?false:true);
}
?>
