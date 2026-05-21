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

echo "<form action='editasi2.php?asiento=".$asiento."&accion=altaapu1' name='form1' onsubmit='return validar(form1)' method=post>\n";


	echo "<table class='basica'> \n"; 

	echo "<tr><th>Asiento</th><th>Descripcion</th></tr>\n";

	echo "<tr><td>\n";

	echo "<input type = 'text' value ='$asiento' size='11' maxlength='11' name='asiento' readonly='readonly' onfocus='form1.fecha.focus()' tabindex='1'>";
	echo "</td><td>\n";
	$sql="SELECT *, date_format(enc_fecha, '%d/%m/%y') AS fechax FROM sgcaf830 WHERE enc_clave = $asiento";
	$result = mysql_query($sql); 
	$row = mysql_fetch_array($result);
	echo "<input type = text value ='".$row['enc_explic']."' size=150 maxlength=150 name=tipo readonly='readonly' tabindex=2>";
	$elmonto=abs($row[enc_debe]-$row[enc_haber]);
	echo "</td></tr></table><p />\n";

pantalla_asiento($row['fechax'],$elcargo, $cuenta1, $concepto, $fila["com_refere"], $elmonto);
?>

<table class='basica'>
<?php
// <tr><th>Fecha</th><th>Cuenta</th><th>Concepto</th><th class='dcha'>Debe &#8364;</th><th class='dcha'>Haber &#8364</th></tr>
?>
<tr><th width="100">Fecha</th><th width="100"> </th><th width="100">Cuenta</th><th width="200">Concepto</th><th width="100">Referencia</th><th width="100">Monto</th></tr>

<tr><td>

<input type = 'text' maxlength='8' size='8' name='fecha' value=<?php echo $row['fechax'];?> readonly='readonly' tabindex='3'>

</td><td>

<?php 
// <th width="100">Haber</th>
// size='8' 
// <input type='text' name='cuenta1' size='20' onfocus='form1.cuenta.focus()' tabindex=0>
?>
  <label><input name="elcargo" type="checkbox" value="0" tabindex='4'/> Cargo</label>

</td><td>
<input type='text' name='cuenta1' size='20' maxlengt='20' tabindex='5'>
</td><td>

<input type = 'text' value ='' size='60' maxlength='60' name='concepto' tabindex='6'>

</td><td>

<input type = 'text' value ='<?php $fila["com_refere"]?>' size='20' maxlength='20' name='referencia' tabindex='7'>

</td><td>
<input type = 'text' size='11' maxlength='11' name='elmonto' value='<?php echo $elmonto?>' tabindex='8'>

</td>

</tr>

<?php
/*
</td><td>
<input type = 'text' size='11' maxlength='11' name='haber' value "<?php $elmonto ?>" tabindex='9'>
<tr><td>&nbsp;</td><td colspan='6'>
<select name='cuenta' onchange='seleccionacuentaeditasi2(form1)' onfocus='seleccionacuentaeditasi2(form1)' tabindex=4>

$result = mysql_query("SELECT cue_codigo, cue_nombre FROM sgcaf810 where cue_nivel ='6' ORDER by cue_codigo ");
while ($row = mysql_fetch_row($result)){
echo "<option value='".$row[0]."'>".$row[0].' ' .$row[1]."\n";
}

</select>
</td>
</tr>
*/
?>

<tr>

<td colspan='7' class='dcha'>

<input type = 'submit'name = 'formu' value = 'Añadir' tabindex='10' onclick='return compruebafecha(form1)'>

</td>

</tr>

</table>

</form>
