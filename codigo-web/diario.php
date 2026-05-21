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
$menu14=1;include("menusizda.php");

if ($ejecutar AND $ejecutar="totalapu") {
	totalapu("");
}

echo "<h3 class='noimpri'>[ <a href=?ejecutar=totalapu>Actualizar totales</a> ]";

if ($ord == "pfecha") {

	echo " [<a href=?ord=>Ordenar por Nº Asiento</a> ]";

} else {

	echo " [<a href=?ord=pfecha>Ordenar por Fecha</a>]";

}

?>

</h3><br />

<?php

// $tot = mysql_fetch_array(mysql_query("SELECT SUM(sumadebe) AS sumadebe, SUM(sumahaber) AS sumahaber FROM asientos"));
// $tot = mysql_fetch_array(mysql_query("SELECT SUM(enc_debe) AS sumadebe, SUM(enc_haber) AS sumahaber FROM sgcaf830"));

if ($ord == "pfecha") {
	$sql = "SELECT enc_clave FROM sgcaf830 ORDER BY enc_fecha, enc_clave";
} else {
	$sql = "SELECT enc_clave FROM sgcaf830 ORDER BY enc_clave";
}

$rs=mysql_query($sql);
$numasi = mysql_num_rows($rs);

if (mysql_num_rows($rs) == 0) {
	echo "<p />No hay Asientos.<p /></div></body></html>";exit;
}

$conta = $_GET['conta'];
if (!$_GET['conta']) {
	$conta = 1;
}
$rs = mysql_query($sql." LIMIT ".($conta-1).", 10");

if (pagina($numasi, $conta, 10, "Asientos", $ord)) {$fin = 1;}

?>

<table class='basica 100 hover' width='850'>

<?php cabasi("");

while ($fila = mysql_fetch_array($rs)){

	asiento($fila[0],"", $_SESSION['moneda'], $_SESSION['deci'],$_GET['bojust']);

}

if ($fin) {

	echo "<tr class='b'><td class='blanco dcha' colspan='4'>Totales: </td><td class='blanco dcha'>".number_format($tot[0]*$_SESSION['moneda'],$_SESSION['deci'],'.',',')."</td><td class='blanco dcha'>".number_format($tot[1]*$_SESSION['moneda'],$_SESSION['deci'],'.',',')."</td>";

/*
	echo "<td class='blanco dcha rojo'>";
	if ($tot[0]-$tot[1] != 0) {
		echo number_format(($tot[0]-$tot[1])*$_SESSION['moneda'],$_SESSION['deci'],'.',',');
	}
	echo "</td>";
*/
	echo "</tr>";

}

?>

</table>

<?php

pagina($numasi, $conta, 10, "Asientos", $ord);

echo "<h3 class='noimpri'>[ <a href=?ejecutar=totalapu>Actualizar totales</a> ]";

if ($ord == "pfecha") {
	echo " [<a href='?ord='>Ordenar por Nº Asiento</a> ]";
} else {
	echo " [<a href='?ord=pfecha'>Ordenar por Fecha</a>]";
}

?>

</h3>

<?php include("pie.php");?></body></html>
