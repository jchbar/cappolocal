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

/*
echo "<form action='editasi2.php?asiento=".$asiento."&accion=altaapu1' name='form1' onsubmit='return validar(form1)' method=post>\n";
*/
echo "<form action='editasi2.php?asiento=".$asiento."&accion=altaapu1' name='form1' method=post>\n";


	echo "<table class='basica'> \n"; 

	echo "<tr><th>Asiento</th><th>Descripcion</th></tr>\n";

	echo "<tr><td>\n";

	echo "<input type = 'text' value ='$asiento' size='11' maxlength='11' name='asiento' readonly='readonly' onfocus='form1.fecha.focus()' tabindex='1'>";
	echo "</td><td>\n";
	$sql="SELECT *, date_format(enc_fecha, '%d/%m/%y') AS fechax FROM sgcaf830 WHERE enc_clave = $asiento";
	$result = mysql_query($sql); 
	$row = mysql_fetch_array($result);
	echo "<input type = text value ='".$row['enc_explic']."' size=150 maxlength=150 name=tipo readonly='readonly' tabindex=2>";
// 	echo "<input type = hydden name=fecha value ='".$result['fechax']."'>";
	$elmonto=abs($row[enc_debe]-$row[enc_haber]);
	echo "</td></tr></table><p />\n";

pantalla_asiento($row['enc_fecha'],$elcargo, $cuenta1, $concepto, $fila["com_refere"], $elmonto);
?>

<tr>

<td colspan='7' class='dcha'>

<input type = 'submit'name = 'formu' value = 'Añadir' tabindex='10' onclick='return compruebafecha(form1)'>

</td>

</tr>

</table>

</form>
