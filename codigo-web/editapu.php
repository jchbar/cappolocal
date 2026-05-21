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

$result = mysql_query("SELECT * FROM sgcaf820 WHERE nro_registro = $row_id");

$fila = mysql_fetch_array($result);

$a=explode("-",$fila["com_fecha"]); 

/*
<form action='editasi2.php?asiento=<?php echo $asiento; ?>&amp;row_id=<?php echo $fila['nro_registro']; ?>&amp;accion=editapu1' name='form1' onsubmit="return validar(form1)" method='post'>
*/
?>

<form action='editasi2.php?asiento=<?php echo $asiento; ?>&amp;row_id=<?php echo $fila['nro_registro']; ?>&amp;accion=editapu1' name='form1' method='post'>

<?php
$fecha=$a[2]."/".$a[1]."/".substr($a[0],2,2);
if (($fila['com_debcre']== '+')) $elmonto=$fila['com_monto1']; else $elmonto=$fila['com_monto2'];
pantalla_asiento($fecha,$fila['com_debcre'], $fila['com_cuenta'], $fila['com_descri'], $fila["com_refere"], $elmonto);
?>

<tr><td colspan='6' class='dcha'>

<?php 
// <input type = 'submit' name = 'formu' value = "Confirmar cambios" tabindex='10' onclick='return compruebafecha(form1)'> ?>
<input type = 'submit' name = 'formu' value = "Confirmar cambios" tabindex='10' onclick='return compruebafecha(form1)'>

</td></tr>

</table>

</form>


