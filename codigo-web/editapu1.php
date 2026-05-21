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
$b = explode("/",$fecha);

/*
if ($fila[0] != "20".$b[2])

{
echo "El año no es el del ejercicio actual ($fila[0])";
	exit;

}
*/
/* **************************************************** */

$a=explode("/",$fecha); 

$fecha = "20".$a[2]."-".$a[1]."-".$a[0];

$ip = $_SERVER['HTTP_CLIENT_IP'];
if (!$ip) {$ip = $_SERVER['REMOTE_ADDR'];}
echo $row_id;
// $result = mysql_query("UPDATE apuntes SET fecha = '$fecha', cuenta = '$cuenta', concepto = '$concepto', debe = '$debe', haber = '$haber', ip = '$ip' WHERE row_id = '$row_id'") or die ("El usuario $usuario no tiene permisos para modificar Apuntes"); 
agregar_f820($asiento, $fecha, $elcargo, $cuenta1, $concepto, $elmonto, $haber, 0,$ip,0,$referencia,'','N',$row_id);

$row_id = 0;
//$body = 0;

?>
