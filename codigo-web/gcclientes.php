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

<body <?php if($accion == "Anadir" OR $accion == "Editar") {echo "onload=\"foco('cliente')\"";}?>>

<?php
include("arriba.php");
$menu51=2;include("menusizda.php");

$codigo = $_GET['codigo'];

if ($accion == 'Anadir1' AND $dni) {
	mysql_query("INSERT INTO clientes (dni, cliente, domicilio, ciudad, telefono) VALUES ('$dni', '$cliente', '$domicilio', '$ciudad', '$telefono')" ) or die ("<p />El usuario $usuario no tiene permisos para añadir Clientes o DNI ya existente.");
	$accion="";
}

if ($accion == 'Editar1') {

	extract($_POST);
	$codigo = $_POST['codigo'];
	$num = 1;
	if ($cuenta AND $_SESSION['auto'] > 4) {
		$result = mysql_query("SELECT cuenta FROM subcuent WHERE cuenta = '$cuenta'");
		if ($result) {
			$num = mysql_num_rows($result);
		}
	}
	if (!$num) {
		mysql_query("UPDATE clientes SET cliente = '$cliente', domicilio = '$domicilio', telefono = '$telefono', cuenta = '$cuenta', ciudad = '$ciudad' WHERE codigo = '$codigo'") or die ("<p />El usuario $usuario no tiene permisos para modificar Clientes");
		mysql_query("INSERT INTO subcuent (cuenta, descripci_) VALUES ('$cuenta', '$cliente')"); 
	} else {
		echo "UPDATE clientes SET cliente = '$cliente', domicilio = '$domicilio', telefono = '$telefono', ciudad = '$ciudad' WHERE codigo = '$codigo'";
		mysql_query("UPDATE clientes SET cliente = '$cliente', domicilio = '$domicilio', telefono = '$telefono', ciudad = '$ciudad' WHERE codigo = '$codigo'") or die ("<p />El usuario $usuario no tiene permisos para modificar Clientes"); 
		if($_SESSION['auto'] > 4) {
			echo "<div class='solocontable'>Se ha modificado el Cliente, pero no ha sido posible asignarle el número de Subcuenta</div><p />";
		}
	}		





	//mysql_query("UPDATE clientes SET cliente = '$cliente', domicilio = '$domicilio', ciudad = '$ciudad', telefono = '$telefono' WHERE dni = '$dni'") or die ("<p />El usuario $usuario no tiene permisos para modificar Clientes"); 

}

if ($accion == 'Borrar') {

	mysql_query("DELETE FROM clientes WHERE codigo = $codigo") or die ("<p />El usuario $usuario no tiene permisos para borrar Clientes");

}

// <table class='basica 100 hover' width='100%'>
?>

<div id='div1'>

<table class='basica 100 hover' width='100%'>
<tr>
<th>Validado</th><th>D.N.I.</th><th>Cliente<br />[ <a href='gcclientes.php?accion=Anadir'>Añadir Cliente</a> ]</th><th>Domicilio</th><th>Ciudad</th><th>Teléfono</th></tr>

<?php

$rs=mysql_query("SELECT dni, cliente, domicilio, ciudad, telefono, cuenta, codigo FROM clientes ORDER BY cliente");

// bucle de listado

while($row=mysql_fetch_array($rs)) {

	echo "<tr>";
	echo "<td class='centro'>";
	if ($row['cuenta']) {echo "SÍ";} else {echo "NO";}
	echo "</td><td>";
	echo "<a href='gcclientes.php?accion=Editar&codigo=".$row['codigo']."'>";
	echo $row['dni']."</a>";
	echo "</td><td>".$row['cliente']."</td>";
	echo "<td>".$row['domicilio']."</td>";
	echo "<td>".$row['ciudad']."</td>";
	echo "<td>".$row['telefono']."</td></tr>";

}

echo "</table>";

?>

</div><div id='div2'>

<?php

if ($accion == "Anadir") {

	echo "<form action='gcclientes.php?accion=Anadir1' name='form1' method='post' onsubmit='return gccli(form1)'>";
	echo "<label>D.N.I.</label><br /><input type = 'text' size='40' maxlength='40' name='dni'><br />";
	echo "<label>Cliente</label><br /><input type = 'text' size='40' maxlength='40' name='cliente'><br />";
	echo "<label>Domicilio</label><br /><input type = 'text' size='40' maxlength='80' name='domicilio'><br />";
	echo "<label>Ciudad</label><br /><input type = 'text' size='30' maxlength='30' name='ciudad'><br />";
	echo "<label>Teléfono</label><br /><input type = 'text' size='30' maxlength='30' name='telefono'><br />";
	echo "<input type = 'submit' value = 'Añadir'>";
	echo "</form>\n";

}

if ($accion == "Editar") {

	$result = mysql_query("SELECT * FROM clientes WHERE codigo = $codigo");
	$fila = mysql_fetch_array($result);

	$temp = "";
	if ($fila['cuenta']) {
		if ($_SESSION['auto'] < 5) {
		$temp = " readonly='readonly'";
			echo "<span class='rojo'>¡Atención! El Cliente no puede ser borrado, ni modificada la denominación, porque se encuentra validado en Contabilidad</span><p />";
		} else {
		echo "<div class='solocontable'>¡Atención! El Cliente se encuentra validado en Contabilidad como una Subcuenta, si se modifica aquí, los cambios no quedarán reflejados en la tabla de Subcuentas.</div>";
		}	
	}

	echo "<form action='gcclientes.php?accion=Editar1' name='form1' method='post' onsubmit='return gccli(form1)'>";
	echo "<input type = 'hidden' value ='".$fila['codigo']."' name='codigo'>";
	echo "<label>DNI</label><br /><input type = 'text' value ='".$fila['dni']."' size='40' maxlength='40' name='dni'><br />";
	echo "<label>Cliente</label><br /><input type = 'text' value ='".$fila['cliente']."' size='40' maxlength='40' name='cliente'><br />";
	echo "<label>Domicilio</label><br /><input type = 'text' value ='".$fila['domicilio']."' size='40' maxlength='80' name='domicilio'><br />";
	echo "<label>Ciudad</label><br /><input type = 'text' value ='".$fila['ciudad']."' size='30' maxlength='30' name='ciudad'><br />";
	echo "<label>Teléfono</label><br /><input type = 'text' value ='".$fila['telefono']."' size='30' maxlength='30' name='telefono'><p />";
	if (!$fila['cuenta'] AND $_SESSION['auto'] > 4) {
		echo "<div class='solocontable'>Asignar Subcuenta<br /><input type='text' size='8' name='cuenta'></div><p />";
	}
	echo "<input type = 'submit' value = 'Confirmar cambios'></form>\n";
	if (!$temp) {
		echo "<p /><form action='gcclientes.php?accion=Borrar' name='form2' method='post'>\n";
		echo "<input type='hidden' name='dni' value=".$dni.">\n";
		echo "<input type='submit' value='Borrar Cliente' onclick='return borrar_cli()'></form>\n";
	}

}

?>

</div>

<?php include("pie.php");?></body></html>
