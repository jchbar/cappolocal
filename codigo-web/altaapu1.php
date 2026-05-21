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

/* ***************COMPROBACIÓN AÑO ****************** */

/*
$result = mysql_query("SELECT anocont FROM empresa");
$fila = mysql_fetch_array($result);

*/
$b = $fecha; 
/*
explode("/",$fecha);
echo $b[2];
if ($b == "00/00/0000")
	$b = explode("-",$fecha);

/*
if ($fila[0] != "20".$b[2])

{
echo "El año no es el del ejercicio actual ($fila[0])";
	exit;
}
*/
/* **************************************************** */
// $a=explode("/",$fecha); 
// $b="20".$b[2]."-".$b[1]."-".$b[0];
// $b=$b[2]."-".$b[1]."-".$b[0];
// echo 'la fecha '.$fecha;
// echo ' b '.$b;
	
$ip = $_SERVER['HTTP_CLIENT_IP'];
if (!$ip) {$ip = $_SERVER['REMOTE_ADDR'];}
$haber = $debe = 0;
$debe = $elmonto;
// $sql = "INSERT INTO apuntes (asiento, fecha, tipo, cuenta, concepto, debe, haber, ip) VALUES ('$asiento', '$b', '$tipo', '$cuenta1', '$concepto', '$debe', '$haber', '$ip')";
// $sql = "call sp_inc_r_820 ('$asiento', '$b', '', '$cuenta1', '$concepto', '$debe', '$haber', 0,'$ip',0,$referencia,'')";
// echo "instruccion a ejecutar ".$sql;
// $resultado = mysql_query($sql);
// if (! $resultado) { echo 'fallo el sp de mysql ';
// 					die (mysql_error()); };
// if (!mysql_query($sql)) die ("El usuario $usuario no tiene permiso para añadir Apuntes.");
agregar_f820($asiento, $b, $elcargo, $cuenta1, $concepto, $debe, $haber, 0,$ip,0,$referencia,'','S',0);

?>
