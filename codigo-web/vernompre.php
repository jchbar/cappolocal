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
/*
if ($accion == 'Anadir1') {
	extract($_POST);
	$codigo = $_POST['codigo'];
	if ($codigo) {
		$sql='select * from sgcafniv order by con_nivel';
		$result=mysql_query($sql);
		$tamano=strlen(trim($codigo));
		$niveles = 0;
		$elnivel=0;
		while($row=mysql_fetch_assoc($result)) {
			$niveles ++;
			if ($tamano == $row['con_nivel'])
				$elnivel=$niveles;
			}
		$sql="INSERT INTO sgcaf810 (cue_codigo, cue_nombre, cue_saldo,cue_nivel,cue_deb01,cue_deb02,cue_deb03,cue_deb04,cue_deb05,cue_deb06,cue_deb07,cue_deb08,cue_deb09,cue_deb10,cue_deb11,cue_deb12,cue_deb13,cue_deb14,cue_deb15,cue_deb16,cue_deb17,cue_deb18,cue_deb19,cue_cre01,cue_cre02,cue_cre03,cue_cre04,cue_cre05,cue_cre06,cue_cre07,cue_cre08,cue_cre09,cue_cre10,cue_cre11,cue_cre12,cue_cre13,cue_cre14,cue_cre15,cue_cre16,cue_cre17,cue_cre18,cue_cre19) VALUES ('$codigo', '$nombre', $saldoi,'".$elnivel."',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0)" ;
// 		echo $sql;
		if ($elnivel != 0) 
			if (! mysql_query($sql))
				die ("<p />El usuario $usuario no tiene permisos para añadir Clientes o cuenta ya existente.");
			else echo 'Cuenta agregada satisfactoriamente...<br>';
			 // nivel != 0
		else echo '<h2>La Cuenta no se ha definido correctamente. NO se ha agregado...</h2>';
		}	// codigo
		$accion="";
}	// accion

if ($accion == 'Editar1') {
	extract($_POST);
	$codigo = $_POST['codigo'];
	$num = 1;
		$sql="UPDATE sgcaf810 SET cue_nombre = '$nombre', cue_saldo= $saldoi WHERE cue_codigo = '$codigo'";
		mysql_query($sql) or die ("<p />El usuario $usuario no tiene permisos para modificar Clientes");
	//mysql_query("UPDATE clientes SET cliente = '$cliente', domicilio = '$domicilio', ciudad = '$ciudad', telefono = '$telefono' WHERE dni = '$dni'") or die ("<p />El usuario $usuario no tiene permisos para modificar Clientes"); 

}

if ($accion == 'Borrar') {
	extract($_POST);
	$codigo = $_POST['codigo'];
	mysql_query("DELETE FROM sgcaf810 WHERE cue_codigo = '$codigo'") or die ("<p />El usuario $usuario no tiene permisos para borrar Cuentas");
	$accion="";

}

echo $_GET['accion'];
if ($_GET['accion']=='Buscar')
{
	$sql="SELECT cue_codigo, cue_nombre, cue_saldo, cue_nivel, (cue_saldo-(cue_cre01+cue_cre02+cue_cre03+cue_cre04+cue_cre05+cue_cre06)+(cue_deb01+cue_deb02+cue_deb03+cue_deb04+cue_deb05+cue_deb06)) as cue_actual FROM sgcaf810 where cue_codigo='".$_POST['cuenta']."' ORDER BY cue_codigo";
//	echo $sql;
	$rs = mysql_query($sql); // ." LIMIT ".($conta-1).", 20");
	echo "<fieldset><legend>Consulta Solicitada</legend>";
	echo "<table class='basica 100 hover' width='100%'>";
	echo "<tr>";
	echo "<th>Movimiento</th><th>Código Cuenta</th><th>Descripción<br /></th><th>Saldo Inicial</th><th>Saldo Actual</th><th>Nivel</th></tr>";
	$row=mysql_fetch_array($rs);
	$elnivel=$row['cue_nivel'];
	echo "<tr>";
	echo "<td class='centro'>";
	if ($elnivel==$_SESSION['maxnivel']) {echo "SI";} else {echo "NO";}
	echo "</td><td>";
	echo "<a href='vernompre.php?accion=Editar&codigo=".$row['cue_codigo']."'>";
	echo $row['cue_codigo']."</a>";
	echo "</td><td>".$row['cue_nombre']."</td>";
	echo "<td class='dcha'>".number_format($row['cue_saldo'],$_SESSION['deci'],'.',',')."</td>";
	echo "<td class='dcha'>".number_format($row['cue_actual'],$_SESSION['deci'],'.',',')."</td>";
	echo "<td>".$row['cue_nivel']."</td>";
	if ($elnivel==$_SESSION['maxnivel']) {
		echo "<td><a href=\"extractoctas3.php?cuenta=".$row["cue_codigo"]."&datos='no'\" target='_self'> <img src='imagenes/16-em-pencil.png' width='16' height='16' border='0' /></a>"; }
	else {echo "";}
	echo "</td>";
	echo "</tr>";
	echo '</table>';
echo '</fieldset>';
}
*/
// <table class='basica 100 hover' width='100%'>
?>

<div id='div1'>
<table class='basica 100 hover' width='100%'>
<tr>
<th>Fecha Nomina</th><th>Fecha Generada</th><th>Registros</th><th>Realizado en</th><th colspan="3">Listados de </th></tr>
<?php
$ord='';
$conta = $_GET['conta'];
if (!$_GET['conta']) {
	$conta = 1;
}

$sql = "SELECT COUNT(fechanom) AS cuantos FROM sgcafnob group by fechanom";
$rs = mysql_query($sql);
$row= mysql_fetch_array($rs);
$numasi = $row[cuantos]; 

$sql = "SELECT * FROM sgcafnob group by fechanom ORDER BY fechanom DESC";
$rs = mysql_query($sql." LIMIT ".($conta-1).", 20");

if (pagina($numasi, $conta, 20, "Nominas Generadas", $ord)) {$fin = 1;}

while($row=mysql_fetch_array($rs)) {
	echo "<tr>";
	echo "<td class='centro'>";
	echo $row['fechanom'];
	echo "</td><td>";
	echo $row['fechagen'];
	echo "</td>";
	echo "<td class='dcha'>".number_format($row['registros'],0,'.',',')."</td>";
	echo "<td>";
	echo $row['ipgen'];
	echo '</td>';
	echo "<td>";
	echo "<a href='verpdf.php?archivo=".$row['fechanom']."amortizacion.pdf'>";
	echo "Amortizacion</a>";
	echo "</td>";
	echo "<td>";
	echo "<a href='verpdf.php?archivo=".$row['fechanom']."banco.pdf'>";
	echo "Banco</a>";
	echo "</td>";
	echo "<td>";
	echo "<a href='verpdf.php?archivo=".$row['fechanom']."cuotas.pdf'>";
	echo "Cuotas</a>";
	echo "</td>";
	echo "</tr>";

}
echo "</table>";
pagina($numasi, $conta, 20, "Nominas Generadas", $ord);

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

?>

</div>

<?php include("pie.php");?></body></html>
