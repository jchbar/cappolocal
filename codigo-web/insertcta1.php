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

echo "<form name=form2 method=post>\n";
echo "<label>Empresa</label>: <input type='text' name='bdatos' size='12' maxlength='12' value='$bdatos'>\n";
echo " <label>Usuario</label>: <input type='text' name='usuariocop' size='15' maxlength='15' value='$usuariocop'>\n";
echo " <label>Clave</label>: <input type='password' name='clavecop' size='16' maxlength='16' value='$clavecop'>\n";

echo "<p /><select name='tabla'><option value='subgrupo' ";
if ($tabla == 'subgrupo') {
	echo "selected";
}
echo ">Subgrupos<option value='cuentas' ";
if ($tabla == 'cuentas') {
	echo "selected";
}
echo ">Cuentas<option value='subcuent' ";
if ($tabla == 'subcuent') {
	echo "selected";
}
echo ">Subcuentas</select>\n";

echo "<input type='submit' value = ' >> VER >> '>\n";
echo "</form>\n";

if ($bdatos) {

	if (mysql_select_db($bdatos, $link)) {

		$fila = mysql_fetch_array(mysql_query("SELECT * FROM usuarios WHERE usuario = '$usuariocop' AND clave = '$clavecop'"));

		if (!$fila) {return;}

		if ($tabla == 'subgrupo') {
			$sql = "SELECT subgrupo, descripci_ FROM ".$tabla." ORDER by descripci_";
		}
		if ($tabla == 'cuentas') {
			$sql = "SELECT cuenta, descripcio FROM ".$tabla." ORDER by descripcio";
		}
		if ($tabla == 'subcuent') {
			$sql = "SELECT cuenta, descripci_ FROM ".$tabla." ORDER by descripci_";
		}

		echo "<form name='form3' method='post'>\n";

		echo "<select name='cue' size='10'>\n";
		$result = mysql_query($sql);
		while ($row = mysql_fetch_row($result)){
			echo "<option value='".$row[0]." - ".$row[1]."'";
			if ($cue AND $row[0]." - ".$row[1] == $cue) {
					echo " selected ";
			}
			echo ">".$row[0]." - ".$row[1];
		}
		echo "</select>";
		echo "<input type='hidden' name='bdatos' value='$bdatos'>\n";
		echo "<input type='hidden' name='usuariocop' value='$usuariocop'>\n";
		echo "<input type='hidden' name='clavecop' value='$clavecop'>\n";
		echo "<input type='hidden' name='bdatos' value='".$bdatos."'><input type='hidden' name='tabla' value=".$tabla.">\n";
		echo "<p /><input type='submit' value='Añadir a empresa ".$empresa."'></form>\n";

	}

}

?>