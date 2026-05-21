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
$sql="SELECT * FROM sgcaf220 WHERE reg_afi = $reg_afi";
$result = mysql_query($sql);
$fila = mysql_fetch_array($result);
// $a=explode("-",$fila["com_fecha"]); 
?>

<form action='regbenef.php?cedula=<?php echo $cedula; ?>&amp;row_id=<?php echo $fila['reg_afi']; ?>&amp;accion=editben1' name='form1' onsubmit="return compruebabeneficiario(form1)" method='post'>

<?php
// $fecha=$a[2]."/".$a[1]."/".substr($a[0],2,2);
// if (($fila['com_debcre']== '+')) $elmonto=$fila['com_monto1']; else $elmonto=$fila['com_monto2'];
pantalla_beneficiarios($fila['afi_afi'],$fila['nom_afi'], $fila['nac_afi'], $fila['par_afi'], $fila['rec_afi'], $fila['por_afi'],  $fila['sv_afi']);
?>

<tr><td colspan='7' class='dcha'>

<input type = 'submit' name = 'formu' value = "Confirmar cambios" tabindex='10' onclick='return compruebabeneficiario(form1)'>

</td></tr>

</table>

</form>


