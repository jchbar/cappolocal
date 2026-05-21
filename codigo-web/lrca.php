<?php

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
<th>Fecha Nomina</th><th>Fecha Generada</th><th>Registros</th><th>Realizado en</th><th colspan="4">Listados de </th></tr>
<?php
$ord='';
$conta = $_GET['conta'];
if (!$_GET['conta']) {
	$conta = 1;
}

$sql = "SELECT COUNT(fecha) AS cuantos FROM sgcaretucla group by fecha";
$rs = mysql_query($sql);
$row= mysql_fetch_array($rs);
$numasi = $row[cuantos]; 

$sql = "SELECT *, DATE_FORMAT(fecha,'%d/%m/%Y') as fn, DATE_FORMAT(procesado,'%d/%m/%Y') as fp FROM sgcaretucla group by fecha ORDER BY fecha DESC";
$rs = mysql_query($sql." LIMIT ".($conta-1).", 20");

if (pagina($numasi, $conta, 20, "Nominas Retencion UCLA", $ord)) {$fin = 1;}

while($row=mysql_fetch_array($rs)) {
	echo "<tr>";
	echo "<td class='centro'>";
	echo $row['fn'];
	echo "</td><td>";
	echo $row['fp'];
	echo "</td>";
	echo "<td class='dcha'>".number_format($row['registros'],0,'.',',')."</td>";
	echo "<td>";
	echo $row['ip'];
	echo '</td>';
	echo "<td>";
	echo "<a href='verpdfindom.php?archivo=".$row['fecha'].".pdf'>";
	echo "Datos Recibidos UCLA</a>";
	echo "</td>";
	echo "<td>";
	echo "<a href='verpdfindom.php?archivo=".$row['fecha']."r.pdf'>";
	echo "Publicar en Web</a>";
	echo "</td>";
	echo "<td>";
	echo "<a href='verpdfindom.php?archivo=".$row['fecha']."i.pdf'>";
	echo "Indomiciliados</a>";
	echo "</td>";
	echo "<td>";
	echo "<a href='verpdfindom.php?archivo=".$row['fecha']."c.pdf'>";
	echo "Gtos.Adm.</a>";
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
