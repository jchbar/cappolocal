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

<body <?php if($accion == "Anadir" OR $accion == "Editar") {echo "onload=\"foco('nombre')\"";}?>>

<?php
include("arriba.php");
$menu12=1;include("menusizda.php");

$codigo = $_GET['codigo'];
?>

<div id='div1'>
<table class='basica 100 hover' width='100%'>
<tr>
<th colspan="12">Fecha de Saldo de Haberes de los Socios</th></tr>
<?php
$ord='';
$conta = $_GET['conta'];
if (!$_GET['conta']) {
	$conta = 1;
}

$sql = "SELECT COUNT(fecha) AS cuantos FROM sgcafnah group by fecha";
$rs = mysql_query($sql);
$row= mysql_fetch_array($rs);
$numasi = $row[cuantos]; 

$sql = "SELECT * FROM sgcafnah group by fecha ORDER BY fecha DESC";
$rs = mysql_query($sql); // ." LIMIT ".($conta-1).", 20");

if (pagina($numasi, $conta, 20, "Saldo de Ahorro", $ord)) {$fin = 1;}

$cuadro=0;
while($row=mysql_fetch_array($rs)) {
	if ($cuadro==0)
		echo "<tr>";
	$cuadro++;
	echo "<td>";
	echo "<a href='verpdfahorro.php?archivo=".$row['fecha'].".pdf'>";
	echo convertir_fechadmy($row['fecha'])."</a>";
	echo "</td>";
	if (($cuadro==12) or (substr($row['fecha'],5,2)=='01'))
	{
		echo "</tr>";
		$cuadro=0;
	}

}
echo "</table>";
pagina($numasi, $conta, 20, "Saldo de Ahorro", $ord);

?>

</div><div id='div2'>

<?php

if ($accion == "Anadir2") {
	extract($_POST);
	$codigo = $_POST['codigo'];
	if ($codigo) {
		$sql="SELECT * FROM sgcaf810 WHERE cue_codigo = '$codigo'";
//		$sql="call sp_qry_cuenta('$codigo')";
		$rs=mysql_query($sql) or die ("<p />El usuario $usuario no tiene permisos para modificar cuentas");
		$fila = mysql_fetch_array($rs);
		if (mysql_num_rows($rs) > 0) {
			echo "<label>Descripción</label><br /><input type = 'text' value ='".$fila['cue_nombre']."' size='40' maxlength='40' name='nombre' readonly='readonly'><br />";
			echo "<label>Saldo Inicial</label><br /><input type = 'text' size='40' maxlength='15' name='saldoi' readonly='readonly' value ='".number_format($fila['cue_saldo'],$_SESSION['deci'],'.',',')."'<br />";
		}
	else {
		echo "<form action='vernompre.php?accion=Anadir1' name='form1' method='post' onsubmit='return gccli(form1)'>";		
/* readonly='readonly' */
//		echo "<form action='vernompre.php?accion=Anadir2' name='form1' method='post'>";
		echo '<input type="hidden" name = "codigo" value ="'.$codigo.'">';		 
//		echo "<label>Código de Cuenta</label><br /><input type = 'text' size='40' maxlength='40' name='codigo'><br />";
		echo "<label>Descripción </label><br /><input type = 'text' size='40' maxlength='40' name='nombre'><br />";
		echo "<label>Saldo Inicial</label><br /><input type = 'text' size='40' maxlength='15' name='saldoi'><br />";
/*
		echo "<form action='vernompre.php?accion=Anadir1' name='form1' method='post' onsubmit='return gccli(form1)'>";

		echo "<label>Descripción </label><br /><input type = 'text' size='40' maxlength='40' name='nombre'><br />";
		echo "<label>Saldo Inicial</label><br /><input type = 'text' size='40' maxlength='15' name='saldoi'><br />";
		echo "<label>Ciudad</label><br /><input type = 'text' size='30' maxlength='30' name='ciudad'><br />";
		echo "<label>Teléfono</label><br /><input type = 'text' size='30' maxlength='30' name='telefono'><br />";
*/
		echo "<input type = 'submit' value = 'Añadir'>";
		echo "</form>\n";
		}
	}
}
if ($accion == "Anadir") {
/* readonly='readonly' */
	echo "<form action='vernompre.php?accion=Anadir2' name='form1' method='post'>";
	echo "<label>Código de Cuenta</label><br /><input type = 'text' size='40' maxlength='40' name='codigo'><br />";
/*
	echo "<form action='vernompre.php?accion=Anadir1' name='form1' method='post' onsubmit='return gccli(form1)'>";

	echo "<label>Descripción </label><br /><input type = 'text' size='40' maxlength='40' name='nombre'><br />";
	echo "<label>Saldo Inicial</label><br /><input type = 'text' size='40' maxlength='15' name='saldoi'><br />";
	echo "<label>Ciudad</label><br /><input type = 'text' size='30' maxlength='30' name='ciudad'><br />";
	echo "<label>Teléfono</label><br /><input type = 'text' size='30' maxlength='30' name='telefono'><br />";
*/
	echo "<input type = 'submit' value = 'Añadir'>";
	echo "</form>\n";

}

if ($accion == "Editar") {
	$sql='SELECT * FROM sgcaf810 WHERE cue_codigo = "'.$codigo.'"';
//		$sql="call sp_qry_cuenta('$codigo')";
//		echo "sentencia ".$sql;
// echo "prueba xxx" . mysql_query("call sp_qry_cuenta('$codigo')");
// echo mysql_query($sql); 
	$result = mysql_query($sql); // "call sp_qry_cuenta('$codigo')");
 //	echo "resultado ". $result;
	$fila = mysql_fetch_array($result);
	$temp = "";
/*
	if ($fila['cuenta']) {
		if ($_SESSION['auto'] < 5) {
		$temp = " readonly='readonly'";
			echo "<span class='rojo'>¡Atención! El Cliente no puede ser borrado, ni modificada la denominación, porque se encuentra validado en Contabilidad</span><p />";
		} else {
		echo "<div class='solocontable'>¡Atención! El Cliente se encuentra validado en Contabilidad como una Subcuenta, si se modifica aquí, los cambios no quedarán reflejados en la tabla de Subcuentas.</div>";
		}	
	}
*/
	echo "<form action='vernompre.php?accion=Editar1' name='form1' method='post' onsubmit='return gccli(form1)'>";
	echo "<input type = 'hidden' value ='".$fila['cue_codigo']."' name='codigo'>";
	echo "<label>Descripción</label><br /><input type = 'text' value ='".$fila['cue_nombre']."' size='40' maxlength='40' name='nombre'><br />";
	echo "<label>Saldo Inicial</label><br /><input type = 'text' value ='".number_format($fila['cue_saldo'],$_SESSION['deci'],'.',',')."' size='40' maxlength='40' name='saldoi'><br />";
/*
	echo "<label>Domicilio</label><br /><input type = 'text' value ='".$fila['domicilio']."' size='40' maxlength='80' name='domicilio'><br />";
	echo "<label>Ciudad</label><br /><input type = 'text' value ='".$fila['ciudad']."' size='30' maxlength='30' name='ciudad'><br />";
	echo "<label>Teléfono</label><br /><input type = 'text' value ='".$fila['telefono']."' size='30' maxlength='30' name='telefono'><p />";
	if (!$fila['cuenta'] AND $_SESSION['auto'] > 4) {
		echo "<div class='solocontable'>Asignar Subcuenta<br /><input type='text' size='8' name='cuenta'></div><p />";
	}
*/
	echo "<input type = 'submit' value = 'Confirmar cambios'></form>\n";
	if (!$temp) {
		echo "<p /><form action='vernompre.php?accion=Borrar' name='form2' method='post'>\n";
		echo "<input type='hidden' name='codigo' value=".$codigo.">\n";
		echo "<input type='submit' value='Borrar Cuenta' onclick='return borrar_cuenta()'></form>\n";
	}

}

/*
INSERT INTO `sica`.`sgcafnah` (`fecha` ,`carpeta` ,`archivo` ,`registro`)VALUES ('2001-04-30', '', '', NULL), ('2001-05-31', '', '', NULL);
INSERT INTO `sica`.`sgcafnah` (`fecha` ,`carpeta` ,`archivo` ,`registro`) VALUES ('2001-06-30', '', '', NULL), ('2001-07-31', '', '', NULL);
INSERT INTO `sica`.`sgcafnah` (`fecha` ,`carpeta` ,`archivo` ,`registro`) VALUES ('2001-08-31', '', '', NULL), ('2001-09-30', '', '', NULL);
INSERT INTO `sica`.`sgcafnah` (`fecha` ,`carpeta` ,`archivo` ,`registro`) VALUES ('2001-10-31', '', '', NULL), ('2001-11-30', '', '', NULL), ('2001-12-31', '', '', NULL);

INSERT INTO `sica`.`sgcafnah` (`fecha` ,`registro`) VALUES ('2002-01-31', NULL), ('2002-02-28', NULL), ('2002-03-31', NULL);
INSERT INTO `sica`.`sgcafnah` (`fecha` ,`registro`) VALUES ('2002-04-30', NULL), ('2002-05-31', NULL), ('2002-06-30', NULL);
INSERT INTO `sica`.`sgcafnah` (`fecha` ,`registro`) VALUES ('2002-07-31', NULL), ('2002-08-31', NULL), ('2002-09-30', NULL);
INSERT INTO `sica`.`sgcafnah` (`fecha` ,`registro`) VALUES ('2002-10-31', NULL), ('2002-11-30', NULL), ('2002-12-31', NULL);

INSERT INTO `sica`.`sgcafnah` (`fecha` ,`registro`) VALUES ('2002-01-31', NULL), ('2002-02-28', NULL), ('2002-03-31', NULL);
INSERT INTO `sica`.`sgcafnah` (`fecha` ,`registro`) VALUES ('2002-04-30', NULL), ('2002-05-31', NULL), ('2002-06-30', NULL);
INSERT INTO `sica`.`sgcafnah` (`fecha` ,`registro`) VALUES ('2002-07-31', NULL), ('2002-08-31', NULL), ('2002-09-30', NULL);
INSERT INTO `sica`.`sgcafnah` (`fecha` ,`registro`) VALUES ('2002-10-31', NULL), ('2002-11-30', NULL), ('2002-12-31', NULL);

INSERT INTO `sica`.`sgcafnah` (`fecha` ,`registro`) VALUES ('2015-01-31', NULL), ('2015-02-28', NULL), ('2015-03-31', NULL);
INSERT INTO `sica`.`sgcafnah` (`fecha` ,`registro`) VALUES ('2015-04-30', NULL), ('2015-05-31', NULL), ('2015-06-30', NULL);
INSERT INTO `sica`.`sgcafnah` (`fecha` ,`registro`) VALUES ('2015-07-31', NULL), ('2015-08-31', NULL), ('2015-09-30', NULL);
INSERT INTO `sica`.`sgcafnah` (`fecha` ,`registro`) VALUES ('2015-10-31', NULL), ('2015-11-30', NULL), ('2015-12-31', NULL);
*/
?>

</div>

<?php include("pie.php");?></body></html>
