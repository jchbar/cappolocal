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
if ($_GET['emp'] == 1) {$_GET['n'] = 1;}

extract($_GET);
extract($_POST);
// if ($_GET['n'] == 1) {
if (!$asiento) {
	$onload="onload=\"foco('asiento')\"";
	//$result = mysql_query("SELECT max(asiento) FROM asientos");
	//$row = mysql_fetch_row($result);
	//$asiento = $row[0] + 1;
	$fila = mysql_fetch_array(mysql_query("SELECT con_compr FROM sgcaf8co"));
	$asiento = $fila[0] + 1;
	mysql_query("UPDATE sgcaf8co SET con_compr = '$asiento' WHERE 1");
	// Cojo el valor de la fecha en que se hizo el último Asiento
	$result = mysql_query("SELECT date_format(con_ultfec,'%d/%m/%y') AS ultfechax FROM sgcaf8co");
	$row = mysql_fetch_array($result);
	$fecha = $row[0];
} else {
	$onload="onload=\"foco('cuenta11')\"";
	$readonly=" readonly='readonly'";
	$asiento = $_POST['asiento'];
	$fecha = $_POST['fecha'];
	$fecha = $_POST['fecha'];
	$tipo =$_POST['tipo'];
	$debcre= $_POST['debcre'];
	$cuenta1= $_POST['cuenta1'];
	$referencia =$_POST['referencia'];
	$elmonto=$_POST['elmonto'];
}

?>

<script language="Javascript" src="selec_fecha.js" type='text/javascript'></script>
<body <?php if (!$bloqueo) {echo $onload;}?>>

<?php

include("arriba.php");
$menu11=1;include("menusizda.php");

/*
if ($elmonto) {
	include ("altaasim2.php");
//	$cuadre = totalapu($asiento);
}
*/

?>

<form enctype='multipart/form-data' name='form1' action='precie.php' method='post' onSubmit="return valprecierre(form1)">

<label>Asiento</label>
<input type='text' name='asiento' value="<?php echo $asiento;?>" maxlength="11" size="11" <?php echo $readonly;?> > 

<label>Fecha</label>

<?php
/*
<input type='text' name='lafecha' size='8' maxlength='8' value="<?php echo $fecha;>" <?php echo $readonly;>>
<input type="button" name="selfecha" value="..."  onclick="displayDatePicker('fecha','','dmy');">

/*el calendario se encuentra en el archivo popCalendar.js el cual se despliega según la función escribe_formulario(com_fecha, form1.com_fecha, 'd/m/yyyy',($fila5['com_fecha']), $mesant, $hoy, '1', '3') 
enviando las variables a calendarioventana.js que se encarga de abrir la nueva ventana que se encuentra en el archivo pop.php. 
funcionamiento del calendario 
escribe_formulario(com_fecha, form1.com_fecha, 'd/m/yyyy',($fila5['com_fecha']), $mesant, $hoy, '1', '3')
1. com_fecha es el nombre del campo de texto. 
2. form1.com_fecha es el nombre del formulario con el campo del texto.
3. d/m/yyyy es el formato con el retorna la fecha.
4. $fila5['com_fecha'] es el valor de la variable.
en caso de no restringir por fechas dejar el espacio con comillas ''. 
5. $mesant es la variable que tiene la información de la restricción de fecha un mes atras del día de hoy.
6. $hoy es la variable que tiene el día de hoy como restricción del día de hoy.
7. '1' para restringir sabado y domingo. '0'en caso de no restringir.
8. '3' es la cantidad de años con respecto al año actual 2009-2006.*/


if (!$_POST['asiento']) {
    $hoy = date("d/m/Y");
	$sql='select adddate(current_date(),30) as fecha';
	$result=mysql_query($sql);
	$adelante=mysql_fetch_assoc($result);
	$fecha=$adelante['fecha'];
//	escribe_formulario(fecha, form1.fecha, 'd/m/yyyy', '', '', $fecha, '0', '1'); 
?>
	<script type="text/javascript">
	// setActiveStyleSheet(this, 'green');
	setActiveStyleSheet(document.getElementById("defaultTheme"), "green");
	</script>
	</b> <input type="text" name="fecha" id="fecha" size="12" readonly
	><input type="reset" value=" ... "
	onclick="return showCalendar('fecha', '%d/%m/%Y');"><br />
<?php
	echo '<p /> ';
	$temp = "Accion";
} else {
	$temp = "Realizando asientos";
	echo $fecha.'<p />';
	echo "<input type = 'hidden' value ='".$fecha."' name='fecha'>"; 
//	$temp = "Siguiente Registro:";
//	$expli = mysql_fetch_array(mysql_query("SELECT enc_explic FROM sgcaf830 WHERE enc_clave = '".$_POST['asiento']."'"));
}
?>

<fieldset><legend><?php echo $temp;?></legend>

<?php
if (!$_POST['asiento']) 
	echo "<input type='submit' name='boton' value=\"Realizar PreCierre\" tabindex='10' onclick='return valfecha(form1)'>";
else {
	$sql="select nombre from sgcaf000 where tipo='CueCierre'";
	$resultado=mysql_query($sql);
	$niveles=mysql_num_rows($resultado);
	if ($niveles == 0)
		echo '<h1>No se ha definido la cuenta de cierre de ejercicio. Procedimiento cancelado</h2>';
	else {
		$asiento = $_POST['asiento'];
		$cuentacierre=mysql_fetch_assoc($resultado);
		$cuentacierre=$cuentacierre['nombre'];
		$ip = $_SERVER['HTTP_CLIENT_IP'];
		if (!$ip) {$ip = $_SERVER['REMOTE_ADDR'];}
		
		echo "Realizando registros contables del asiento <strong><a target=\"_blank\" href='editasi2.php?asiento=$asiento'>$asiento </a></strong><br>";
		$sql='select con_cding, con_cdegr from sgcaf8co limit 1';
		$resultado=mysql_query($sql);
		$datos=mysql_fetch_assoc($resultado);
		$ingreso=$datos['con_cding'];
		$egreso=$datos['con_cdegr'];
		$sql='select * from sgcafniv order by con_nivel';
		$resultado=mysql_query($sql);
		$niveles=mysql_num_rows($resultado);
		$fecha=convertir_fecha($_POST['fecha']);
//		$a=explode("/",$fecha); 
//		$b=$a[2]."-".$a[1]."-".$a[0];
		$b=$fecha;
		$sql = "INSERT INTO sgcaf830 (enc_clave, enc_fecha, enc_desco, enc_desc1, enc_debe, enc_haber, enc_item, enc_dif, enc_igual, enc_refer, enc_sw, enc_explic) VALUES ('$asiento', '$b', 'PreCierre Contable','',0,0,0,0,0,0,0,'PreCierre Contable')"; 
		if (!mysql_query($sql)) die ("El usuario $usuario no tiene permiso para añadir Asientos.<br>".$sql);
		$sql="select cue_codigo, cue_nombre from sgcaf810 where (left(cue_codigo,1)='$ingreso' or left(cue_codigo,1)='$egreso') and cue_nivel = '$niveles' order by cue_codigo"; 
		$resultado=mysql_query($sql);
		$tdebe = $thaber = $total=0;
//		echo $sql;
		while($row=mysql_fetch_assoc($resultado)) {
			$lacuenta=$row['cue_codigo'];
			$debe=buscar_saldo_f810($lacuenta);
			$donde='+';
			if (substr($lacuenta,0,1) == $ingreso)
				$donde=(($debe <= 0)?'+':'-');
			else 
				$donde=(($debe <= 0)?'+':'-');
			if ($donde == '+')
				$tdebe+=abs($debe);
			else $thaber+=abs($debe);
			if ($debe != 0)
				agregar_f820($asiento, $b, $donde, $lacuenta, 'PreCierre '.$row['cue_nombre'], abs($debe), $haber, 0,$ip,0,$referencia,'','S',0); 
		}
		$donde=(($tdebe >= $thaber)?'-':'+');
		$debe=$tdebe-$thaber;
		agregar_f820($asiento, $b, $donde, $cuentacierre, 'Resultado del Ejercicio', abs($debe), $haber, 0,$ip,0,$referencia,'','S',0); 
		$sql="update definiti set final='1', cierre='$b' ";
		$resultado=mysql_query($sql);
		echo '<h1>Recuerde realizar los ajustes respectivos de las reservas</h1>';
	}
}
/*
	if ($elmonto) {
		echo "&nbsp;&nbsp;&nbsp;<a href='altaasim.php?n=1'";
		if ($cuadre) {echo " onclick=\"return confirm('Asiento descuadrado ¿Continuar con nuevo Asiento?')\"";}
		echo ">Crear nuevo Asiento</a>";
	}
}
*/
?>
</fieldset><p style="clear:both">
<?php // <p /> 
?>
</form>

<?php

// echo $mensaje;

/*
if ($anadido) {

	echo "<table class='basica 100' width='800'>";
	cabasi(2);
	asiento($asiento,"1",$_SESSION['moneda'],$_SESSION['deci'],$_GET['bojust']);
	echo "</table>";

}
*/

function buscar_saldo_f810($cuenta)
{
	$sql_f810="select cue_saldo from sgcaf810 where cue_codigo='$cuenta'";
//	echo $sql_f810;
	$lacuentas=mysql_query($sql_f810); //  or die ("<p />El usuario $usuario no pudo conseguir el saldo contable<br>".mysql_error()."<br>".$sql);
	$lacuenta=mysql_fetch_assoc($lacuentas);
	$saldoinicial=$lacuenta['cue_saldo'];
	
	$sql_f820="select com_monto1, com_monto2 from sgcaf820, sgcaf100 where com_cuenta='$cuenta' and substr(com_fecha,1,4)=substr(fech_ejerc,1,4) order by com_fecha";
//	echo $sql_f820;
	$lacuentas=mysql_query($sql_f820); //  or die ("<p />El usuario $usuario no pudo conseguir los movimientos contables<br>".mysql_error()."<br>".$sql);
	while($lascuenta=mysql_fetch_assoc($lacuentas)) {
		$saldoinicial+=$lascuenta['com_monto1'];
//		echo $saldoinicial.'<br>';
		$saldoinicial-=$lascuenta['com_monto2'];
//		echo $saldoinicial.'<br>';
	}
return round($saldoinicial,2);
}

?>

</div></body></html>

<?php
/*
function pantalla_asiento_simple($fechax,$elcargo, $cuenta1, $cuenta2, $concepto, $referencia, $elmonto)
{
?>
<table class='basica 100' width='800'>
<tr><th width="100">Cuenta Debe</th><th width="100">Cuenta Haber</th><th width="200">Concepto</th><th width="80">Referencia</th><th width="80">Monto</th></tr>
<?php
// <th width="50">Fecha</th>
// <tr><td>
// <input type = 'text' maxlength='8' size='8' name='fecha' value='<?php echo $fechax;>' readonly='readonly' tabindex='3'>
// </td>
?>
<td width="100"> 
<?php 
$activar=' ';
if (($elcargo == '+')) {$activar='checked="checked"'; } else { $activar = ' '; }
//  || ($elcargo = 1)
// <input type='text' name='cuenta1' size='20' maxlengt='20' tabindex='5' value ="<?php echo $cuenta1;>"><br>
// <input type='text' name='cuenta2' size='20' maxlengt='20' tabindex='6' value ="<?php echo $cuenta2;>">
?>
	<input type="text" size="20" tabindex='5' name='cuenta1' id="inputString" onKeyUp="lookup(this.value);" onBlur="fill();" value ="<?php echo $cuenta1;?>" autocomplete="off"/>
			<div class="suggestionsBox" id="suggestions" style="display: none;">
				<img src="upArrow.png" style="position: relative; top: -12px; left: 70px; "  alt="upArrow" />
				<div class="suggestionList" id="autoSuggestionsList">
				</div>
			</div>
		</div>
</td><td width="100">
<input type="text" size="20" tabindex='5' name='cuenta2' id="inputString2" onKeyUp="lookup2(this.value);" onBlur="fill2();" value ="<?php echo $cuenta2;?>" autocomplete="off"/>
			<div class="suggestionsBox2" id="suggestions2" style="display: none;">
				<img src="upArrow.png" style="position: relative; top: -12px; left: 70px; "  alt="upArrow" />
				<div class="suggestionList2" id="autoSuggestionsList2">
				</div>
			</div>
		</div>
</td><td>
<input type = 'text' size='40' maxlength='60' name='concepto' tabindex='7' value ="<?php echo $concepto?>">
</td><td>
<input type = 'text' value ='<?php echo $referencia?>' size='10' maxlength='10' name='referencia' tabindex='8'>
</td><td>
<input type = 'text' size='11' maxlength='11' name='elmonto' value='<?php echo $elmonto;?>' tabindex='9'>
</td>
</tr>
<tr>

<?php
}
*/
?>
