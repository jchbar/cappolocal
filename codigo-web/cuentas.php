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
$codsudeca = $_POST['codsudeca'];
$nomsudeca = $_POST['nomsudeca'];
// echo $accion. ' - ' .$codigo.'-'.$nombre.'-'.$saldoi;
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
		$sql="INSERT INTO sgcaf810 (cue_codigo, cue_nombre, cue_saldo,cue_nivel,cue_deb01,cue_deb02,cue_deb03,cue_deb04,cue_deb05,cue_deb06,cue_deb07,cue_deb08,cue_deb09,cue_deb10,cue_deb11,cue_deb12,cue_deb13,cue_deb14,cue_deb15,cue_deb16,cue_deb17,cue_deb18,cue_deb19,cue_cre01,cue_cre02,cue_cre03,cue_cre04,cue_cre05,cue_cre06,cue_cre07,cue_cre08,cue_cre09,cue_cre10,cue_cre11,cue_cre12,cue_cre13,cue_cre14,cue_cre15,cue_cre16,cue_cre17,cue_cre18,cue_cre19, codsudeca, nomsudeca) VALUES ('$codigo', '$nombre', $saldoi,'".$elnivel."',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,'$codsudeca', '$nomsudeca')" ;
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
/*	if ($cuenta AND $_SESSION['auto'] > 4) {
		$result = mysql_query("SELECT cuenta FROM subcuent WHERE cuenta = '$cuenta'");
		if ($result) {
			$num = mysql_num_rows($result);
		}
	} 
	if (!$num) { */
		$sql="UPDATE sgcaf810 SET cue_nombre = '$nombre', cue_saldo= $saldoi, codsudeca = '$codsudeca', nomsudeca = '$nomsudeca'  WHERE cue_codigo = '$codigo'";
//		echo $sql;
		mysql_query($sql) or die ("<p />El usuario $usuario no tiene permisos para modificar Clientes");
/*
		mysql_query("INSERT INTO subcuent (cuenta, descripci_) VALUES ('$cuenta', '$cliente')"); 
	} else {
		echo "UPDATE clientes SET cliente = '$cliente', domicilio = '$domicilio', telefono = '$telefono', ciudad = '$ciudad' WHERE codigo = '$codigo'";
		mysql_query("UPDATE clientes SET cliente = '$cliente', domicilio = '$domicilio', telefono = '$telefono', ciudad = '$ciudad' WHERE codigo = '$codigo'") or die ("<p />El usuario $usuario no tiene permisos para modificar Clientes"); 
		if($_SESSION['auto'] > 4) {
			echo "<div class='solocontable'>Se ha modificado el Cliente, pero no ha sido posible asignarle el número de Subcuenta</div><p />";
		}
	}		
*/
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
	echo "<th>Movimiento</th><th>Código Cuenta</th><th>Descripción<br /></th><th>Saldo Inicial</th><th>Saldo Actual</th><th>Nivel</th><th>Código SUDECA</th><th>Nombre SUDECA</tr>";
	$row=mysql_fetch_array($rs);
	$elnivel=$row['cue_nivel'];
	echo "<tr>";
	echo "<td class='centro'>";
	if ($elnivel==$_SESSION['maxnivel']) {echo "SI";} else {echo "NO";}
	echo "</td><td>";
	echo "<a href='cuentas.php?accion=Editar&codigo=".$row['cue_codigo']."'>";
	echo $row['cue_codigo']."</a>";
	echo "</td><td>".$row['cue_nombre']."</td>";
	echo "<td class='dcha'>".number_format($row['cue_saldo'],$_SESSION['deci'],'.',',')."</td>";
	echo "<td class='dcha'>".number_format($row['cue_actual'],$_SESSION['deci'],'.',',')."</td>";
	echo "<td>".$row['cue_nivel']."</td>";
	if ($elnivel==$_SESSION['maxnivel']) {
		echo "<td><a href=\"extractoctas3.php?cuenta=".$row["cue_codigo"]."&datos='no'\" target='_self'> <img src='imagenes/16-em-pencil.png' width='16' height='16' border='0' /></a>"; }
	else {echo "";}
	echo "</td>";
	echo '<td>'.$row['codsudeca']."</td>";
	echo '<td>'.$row['nomsudeca']."</td>";
	echo "</tr>";
	echo '</table>';
echo '</fieldset>';
}

// <table class='basica 100 hover' width='100%'>
?>

<?php
	echo "<form action='cuentas.php?accion=Buscar' enctype='multipart/form-data' method='post' name='form1'>Cuenta: ";
//	echo "<input type='text' name='cuenta' size='20' maxlength='20'> \n";
?>
	<input type="text" size="30" tabindex="1" name="cuenta" id="inputString" onKeyUp="lookup(this.value);" onBlur="fill();" value ="<?php echo $cuenta;?>" autocomplete="off"/>
<?php
echo "<input type='submit' value='Buscar'></form> \n";
?>
			<div class="suggestionsBox" id="suggestions" style="display: none;">
				<img src="upArrow.png" style="position: relative; top: -12px; left: 70px; "  alt="upArrow" />
				<div class="suggestionList" id="autoSuggestionsList">
				</div>
			</div>
		</div> 

<div id='div1'>


<table class='basica 100 hover' width='100%'>
<tr>
<th>Movimiento</th><th>Código Cuenta</th><th>Descripción<br />[ <a href='cuentas.php?accion=Anadir'>Añadir Cuenta</a> ]</th><th>Saldo Inicial</th><th>Saldo Actual</th><th>Nivel</th><th>Codigo SUDECA</th><th>Nombre SUDECA</th><th> </th></tr>
<?php
$ord='';
$conta = $_GET['conta'];
if (!$_GET['conta']) {
	$conta = 1;
}

$m = microtime();
$comienzo = explode(" ", $m);

/* MANERA 1 
$sql = "SELECT cue_codigo FROM sgcaf810 ORDER BY cue_codigo";
$rs = mysql_query($sql);
$numasi = mysql_num_rows($rs);  */
/* MANERA 2 */
$sql = "SELECT COUNT(cue_codigo) AS cuantos FROM sgcaf810";
$rs = mysql_query($sql);
$row= mysql_fetch_array($rs);
$numasi = $row[cuantos]; 

$final = explode(" ", microtime());
$tiempo = ($final[1] + $final[0]) - ($comienzo[1] - $comienzo[0]); 
// echo "Esta página fue generada en $tiempo segundos";

$sql = "SELECT cue_codigo, cue_nombre, cue_saldo, cue_nivel, codsudeca, nomsudeca, (cue_saldo-(cue_cre01+cue_cre02+cue_cre03+cue_cre04+cue_cre05+cue_cre06)+(cue_deb01+cue_deb02+cue_deb03+cue_deb04+cue_deb05+cue_deb06)) as cue_actual FROM sgcaf810 ORDER BY cue_codigo";
// $sql = "SELECT * FROM sgcaf810 ORDER BY cue_codigo";
//echo $sql." LIMIT ".($conta-1).", 10";

$rs = mysql_query($sql." LIMIT ".($conta-1).", 20");

if (pagina($numasi, $conta, 20, "Cuentas", $ord)) {$fin = 1;}


// $rs=mysql_query("SELECT cue_codigo, cue_nombre, cue_saldo, cue_nivel FROM sgcaf810 ORDER BY cue_codigo");

// bucle de listado

while($row=mysql_fetch_array($rs)) {
	$elnivel=$row['cue_nivel'];
	echo "<tr>";
	echo "<td class='centro'>";
	if ($elnivel==$_SESSION['maxnivel']) {echo "SI";} else {echo "NO";}
	echo "</td><td>";
	echo "<a href='cuentas.php?accion=Editar&codigo=".$row['cue_codigo']."'>";
	echo $row['cue_codigo']."</a>";
	echo "</td><td>".$row['cue_nombre']."</td>";
	echo "<td class='dcha'>".number_format($row['cue_saldo'],$_SESSION['deci'],'.',',')."</td>";
	echo "<td class='dcha'>".number_format($row['cue_actual'],$_SESSION['deci'],'.',',')."</td>";
	echo "<td>".$row['cue_nivel']."</td>";
	echo '<td>'.$row['codsudeca']."</td>";
	echo '<td>'.$row['nomsudeca']."</td>";
	if ($elnivel==$_SESSION['maxnivel']) {
		echo "<td><a target=\"_blank\" href=\"extractoctas3.php?cuenta=".$row["cue_codigo"]."&datos='no'\" target='_self'> <img src='imagenes/informe.png' width='16' height='16' border='0' title='Ver Mayor Analitico'  alt='Mayor Analitico' /></a>"; }
	else {echo "<td> </td>";}
	echo "</td>";
	echo "</tr>";

}

echo "</table>";

pagina($numasi, $conta, 20, "Cuentas", $ord);

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
		echo "<form action='cuentas.php?accion=Anadir1' name='form1' method='post' onsubmit='return gccli(form1)'>";		
/* readonly='readonly' */
//		echo "<form action='cuentas.php?accion=Anadir2' name='form1' method='post'>";
		echo '<input type="hidden" name = "codigo" value ="'.$codigo.'">';		 
//		echo "<label>Código de Cuenta</label><br /><input type = 'text' size='40' maxlength='40' name='codigo'><br />";
		echo "<label>Descripción </label><br /><input type = 'text' size='40' maxlength='40' name='nombre'><br />";
		echo "<label>Saldo Inicial</label><br /><input type = 'text' size='40' maxlength='15' name='saldoi'><br />";
		echo "<label>Codigo SUDECA </label><br /><input type = 'text' size='40' maxlength='40' name='codsudeca'><br />";
		echo "<label>Nombre SUDECA </label><br /><input type = 'text' size='40' maxlength='40' name='codsudeca'><br />";
/*
		echo "<form action='cuentas.php?accion=Anadir1' name='form1' method='post' onsubmit='return gccli(form1)'>";

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
	echo "<form action='cuentas.php?accion=Anadir2' name='form1' method='post'>";
	echo "<label>Código de Cuenta</label><br /><input type = 'text' size='40' maxlength='40' name='codigo'><br />";
/*
	echo "<form action='cuentas.php?accion=Anadir1' name='form1' method='post' onsubmit='return gccli(form1)'>";

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
	echo "<form action='cuentas.php?accion=Editar1' name='form1' method='post' onsubmit='return gccli(form1)'>";
	echo "<input type = 'hidden' value ='".$fila['cue_codigo']."' name='codigo'>";
	echo "<label>Descripción</label><br /><input type = 'text' value ='".$fila['cue_nombre']."' size='40' maxlength='40' name='nombre'><br />";
//	echo "<label>Saldo Inicial</label><br /><input type = 'text' value ='".number_format($fila['cue_saldo'],$_SESSION['deci'],'.',',')."' size='40' maxlength='40' name='saldoi'><br />";
	echo "<label>Saldo Inicial</label><br /><input type = 'text' value ='".number_format($fila['cue_saldo'],$_SESSION['deci'],'.','')."' size='40' maxlength='40' name='saldoi'><br />";

	echo "<label>Codigo SUDECA</label><br /><input type = 'text' value ='".$fila['codsudeca']."' size='40' maxlength='40' name='codsudeca'><br />";
	echo "<label>Nombre SUDECA</label><br /><input type = 'text' value ='".$fila['nomsudeca']."' size='40' maxlength='40' name='nomsudeca'><br />";
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
		echo "<p /><form action='cuentas.php?accion=Borrar' name='form2' method='post'>\n";
		echo "<input type='hidden' name='codigo' value=".$codigo.">\n";
		echo "<input type='submit' value='Borrar Cuenta' onclick='return borrar_cuenta()'></form>\n";
	}

}

?>

</div>

<?php include("pie.php");?></body></html>
