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

if (!$link OR !$_SESSION['empresa']) {
	include("noempresa.php");
	exit;
}
?>

<body <?php if (!$cuenta AND !$bloqueo) {echo "onload=\"foco('cuenta')\"";}?>>

<?php
include("arriba.php");
$menu12=2;include("menusizda.php");

if (!$cuenta) {

	echo "<form method='post' name='form1'>Subcuenta: <input type='text' name='cuenta' size='8' maxlength='8'> \n";
	echo "<input type='submit' value='Buscar'></form> \n";
	include("pie.php");
	echo "</div></body></html>";
	exit;

}

$datos1= 'si';
if ($datos AND $datos = 'no') {
	$datos1 = '';
}

// $result = mysql_query("SELECT cuenta, descripci_, ctacte, telefono, telefono2 FROM subcuent WHERE cuenta = $cuenta"); 
$result = mysql_query("SELECT cue_codigo, cue_nombre FROM sgcaf810 WHERE cue_codigo = $cuenta"); 
echo "SELECT cue_codigo, cue_nombre FROM sgcaf810 WHERE cue_codigo = $cuenta";

if (mysql_num_rows($result) == 0) {

		echo "<p /><br /><p />No existe el Nº de Subcuenta <span class='b'>$cuenta</span> en la tabla Subcuentas.";
		exit;

}

while ($fila = mysql_fetch_array($result)) :

	if ($fila[0] = $cuenta) {
// 		echo "<h2>Subcuenta: ".$cuenta." ".$fila[1]."</h2>";
// 		echo "Cuenta Corriente: ".$fila[2]." - Teléfono: ".$fila[3]." / $fila[4]<p />";
		echo "<h2>Cuenta: ".$cuenta." ".$fila[0]."</h2>";
		echo "Descripción: ".$fila[1]."<p />";
		break;
	}

endwhile;

$result = mysql_query("SELECT * FROM sgcaf820 WHERE com_cuenta = $cuenta ORDER by fecha"); 

if (mysql_num_rows($result) == 0) {

	echo "<p /><br /><p />No hay Registros para el Nº de Cuenta <span class='b'>$cuenta</span>.";
	exit;

}

/* ****************** CABECERA ************************* */
	
echo "<table class='basica 100 hover' width='100%'> \n"; 
echo "<tr><th class='b'>Fecha</th><th class='b'>Asiento</th><th class='b'>Concepto</th><th class='dcha b'>Debe</th><th class='dcha b'>Haber</th></tr> \n";

/* ****************** APUNTES ************************** */
$nombre_archivo=$cuenta;
fopen($nombre_archivo, 'w');
if (is_writable($nombre_archivo)) {

		// En nuestro ejemplo estamos abriendo $nombre_archivo en modo de adicion.
		// El apuntador de archivo se encuentra al final del archivo, asi que
		// alli es donde ira $contenido cuando llamemos fwrite().
	if (!$gestor = fopen($nombre_archivo, 'a')) {
		echo "<h2>No se puede abrir el archivo ($nombre_archivo) revise permisologia</h2>";
		exit;
	}
}

while ($fila = mysql_fetch_array($result)) :

	$a=explode("-",$fila["com_fecha"]); 
//	echo "<tr><td>".$a[2]."/".$a[1]."/".substr($a[0],2,2)."</td><td><a href=editasi2.php?asiento=".$fila["asiento"].">". $fila["asiento"]."</a><td class=1>".$fila["concepto"]."</td><td class='dcha'>";
	echo "<tr><td>".$a[2]."/".$a[1]."/".substr($a[0],2,2)."</td><td><a href=editasi2.php?asiento=".$fila["com_nrocom"].">". $fila["com_nrocom"]."</a><td class=1>".$fila["concepto"]."</td><td class='dcha'>";
	
	if ($fila["com_monto1"] == 0)
	{
	echo "&nbsp;";
	} else {
	echo number_format($fila["com_monto1"]*$_SESSION['moneda'],$_SESSION['deci'],',','.');
	}
	echo "</td><td class='dcha'>";
	
	if ($fila["com_monto2"] == 0)
	{
	echo "&nbsp;";
	} else {
	echo number_format($fila["com_monto2"]*$_SESSION['moneda'],$_SESSION['deci'],',','.');
	}

	echo "</td></tr> \n";
	$cadena=$a.','.$fila["com_nrocom"].','.$fila["concepto"].','.$fila["com_monto1"].','.$fila["com_monto2"];
	echo $cadena.'<br>';
	if (fwrite($gestor, $cadena) === FALSE) {
		echo "No se puede escribir al archivo ($nombre_archivo)";
		// exit;
	}
fclose($gestor);


endwhile;

/* ****************** SUMAS Y FIN DE TABLA*************** */


$sql="SELECT SUM(com_monto1) AS tot_debe, SUM(com_monto2) AS tot_haber FROM sgcaf820 WHERE com_cuenta=$cuenta"; 
$result=mysql_query($sql); 
$row=mysql_fetch_array($result);

echo "<tr><td colspan='3' class='blanco dcha b'>SALDO: ".number_format(($row['tot_debe']-$row['tot_haber'])*$_SESSION['moneda'],$_SESSION['deci'],',','.')."</td><td class='blanco dcha b'>".number_format($row['tot_debe']*$_SESSION['moneda'],$_SESSION['deci'],',','.')."</td><td class='blanco dcha b'>".number_format($row['tot_haber']*$_SESSION['moneda'],$_SESSION['deci'],',','.')."</td></tr>\n";

echo "</table><p /> \n"; 

include("pie.php");?></body></html>
