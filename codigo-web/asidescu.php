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

include("head.php");
include("paginar.php");

if (!$link OR !$_SESSION['empresa']) {
	include("noempresa.php");
	exit;
}
?>

<body>

<?php
include("arriba.php");
$menu14=2;include("menusizda.php");

if ($ejecutar AND $ejecutar="totalapu") {
	totalapu("");
}

//echo "<h3>[ <a href=?ejecutar=totalapu>Actualizar totales</a> ]";

if ($_GET['borr'] == 1) {

	$sql = "SELECT enc_clave FROM sgcaf830 WHERE enc_debe != enchaber";
	$result = mysql_query($sql);
	while ($fila = mysql_fetch_array($result)) {
		$sql1 = "DELETE FROM apuntes WHERE asiento = '$fila[0]'";
		if (!mysql_query($sql1)) die ("El usuario $usuario no tiene permiso para borrar Asientos.");
		$sql1 = "DELETE FROM asientos WHERE asiento = '$fila[0]'";
		if (!mysql_query($sql1)) die ("El usuario $usuario no tiene permiso para borrar Asientos.");
		mysql_query("UPDATE factrec SET asiento = '' WHERE asiento = '$fila[0]'");
	}

}

$sql = "SELECT enc_clave FROM sgcaf830 WHERE enc_debe != enc_haber ORDER BY enc_clave";

$rs=mysql_query($sql);

if (mysql_num_rows($rs) == 0) {echo "<p /><span class='b'>No hay asientos descuadrados desde la última vez que se actualizó totales.</span>";echo "</div></body></html>";exit;}

echo "<h3>[ <a href=?ejecutar=totalapu>Actualizar totales</a> ]</h3>";
// echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='?borr=1' onclick=\"return confirm('Confirmar borrado')\">BORRAR ASIENTOS DESCUADRADOS</a>";
$numasi = mysql_num_rows($rs);

$conta = $_GET['conta'];
if (!$_GET['conta']) {
	$conta = 1;
}
$rs = mysql_query($sql." LIMIT ".($conta-1).", 10");

if (pagina($numasi, $conta, 10, "Asientos", $ord)) {$fin = 1;}

echo "<table class='basica 100 hover' width='850'>";

cabasi("");

while ($fila = mysql_fetch_array($rs)){
	asiento($fila['enc_clave'],0,$_SESSION['moneda'],$_SESSION['deci'],$_GET['bojust']);
}

?>

</table>

<?php
pagina($numasi, $conta, 10, "Asientos", $ord);
echo "<h3>[ <a href=?ejecutar=totalapu>Actualizar totales</a> ]</h3>";
include("pie.php");
?>

</body></html>

