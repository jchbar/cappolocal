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

?>

<form action='editasi2.php?asiento=<?php echo $asiento; ?>&amp;nro_registro=<?php echo $nro_registro; ?>&amp;accion=editapu1' name='form1' onsubmit="return validar(form1)" method='post'>

<table class='basica'>
<tr><th>Fecha</th><th>Cuenta</th><th>Concepto</th><th>Referencia</th><th>Debe</th><th >Haber</th></tr>

<tr><td>

<input type = 'text' value =<?php echo $a[2]."/".$a[1]."/".substr($a[0],2,2); ?> size='8' maxlength='8' name='fecha' readonly='readonly'>

</td><td>

<input type='text' name='cuenta1' value ='<?php echo $fila['com_cuenta']; ?>' readonly='readonly' size='20' onfocus='form1.cuenta.focus()' tabindex='1'>

</td><td>

<input type = 'text' value ='<?php echo $fila['com_descri']; ?>' size='35' maxlength='254' name='concepto' tabindex='3'>

</td><td>
<input type = 'text' value ='<?php echo $fila['com_refere']; ?>' size='10' maxlength='10' name='referencia' tabindex='3'>

</td><td>

<input type = 'text' value = '<?php if ($fila['com_monto1'] != 0) {echo $fila['com_monto1'];} else {echo '';} ?>' size='11' maxlength='11' name='debe' tabindex='4'>

</td><td>

<input type = 'text' value = '<?php if ($fila['com_monto2'] != 0) {echo $fila['com_monto2'];} else {echo '';} ?>' size='11' maxlength='11' name='haber' tabindex='5'>

</td></tr>

<tr><td>&nbsp;</td><td colspan='4'>

<select name='cuenta' tabindex='2' onchange='seleccionacuentaeditasi2(form1)' onfocus='seleccionacuentaeditasi2(form1)'>

<?php
/*
$result = mysql_query("SELECT cue_codigo, cue_nombre FROM sgcaf810 where cue_nivel ='6' ORDER by cue_codigo ");
while ($row = mysql_fetch_row($result)){
echo "<option value='".$row[0]."*".$row[1]."' ";

if ($row[0] == $fila['cuenta'])
	{
	echo "selected";
	}

echo ">".$row[1]."\n";
}
*/
?>

</select>

</td></tr>

<tr><td colspan='5' class='dcha'>

<input type = 'submit' name = 'formu' value = "Confirmar cambios" tabindex='6' onclick='return compruebafecha(form1)'>

</td></tr>

</table>

</form>


