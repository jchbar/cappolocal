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

if ($_GET['n'] == 1) {
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
	$tipo =$_POST['tipo'];
	$referencia =$_POST['referencia'];
}

?>

<body <?php if (!$bloqueo) {echo $onload;}?>>

<?php

include("arriba.php");
$menu11=1;include("menusizda.php");

if ($debe OR $haber) {
	include ("altaasigral2.php");
//	$cuadre = totalapu($asiento);
}

?>

<form enctype='multipart/form-data' name='form1' action='altaasigral.php' method='post' onSubmit="return altaasigral(form1)">

<label>Asiento</label>
<input type='text' name='asiento' value="<?php echo $asiento;?>" size='8' <?php echo $readonly;?>> 

<label>Fecha</label>
<input type='text' name='fecha' size='8' maxlength='8' value="<?php echo $fecha;?>" <?php echo $readonly;?>>
<input type="button" name="selfecha" value="..."  onclick="displayDatePicker('fecha','','dmy');">


<?php
/*
<label>Tipo</label> <select name='tipo' <?php echo $readonly;?>>

$array = tipoasi();
foreach ($array as $clave=>$valor) {
	echo "<option value='$valor' ";
	if ($valor == "General") {echo " selected='selected'";}
	echo ">$valor</option>";
}
</select>
*/
?>
<p /> 

<?php
if (!$_POST['asiento']) {
	$temp = "Primer Registro:";
} else {
	$temp = "Siguiente Registro:";
	$expli = mysql_fetch_array(mysql_query("SELECT enc_explic FROM sgcaf830 WHERE enc_clave = '".$_POST['asiento']."'"));
}
?>

<fieldset><legend><?php echo $temp;?></legend>

<br /><label>Cuenta</label><br /><input type='text' name='cuenta1' size='20' >


<?php
/*
<select name='cuenta11' onFocus="seleccionacuenta1(form1)" onChange="seleccionacuenta1(form1) onFocus="form1.cuenta11.focus()"> 

$result = mysql_query("SELECT cue_codigo, cue_nombre FROM sgcaf810 where cue_nivel = '6' ORDER by cue_codigo");
while ($row = mysql_fetch_row($result)){
echo "<option value='".$row[0]."'";
if($row[0] == $_POST['cuenta1']) {echo " selected";}
echo ">".$row[0].'/'.$row[1]."</option>";
}
</select>
*/
?>
<br />

<label>Descripción</label><br />
<input type='text' name='concepto' value="<?php echo $_POST['concepto'];?>" size='35'>
<br />

<label># Referencia</label><br />
<input type='text' name='referencia' value="<?php echo $_POST['referencia'];?>" size='11' maxlength='11'>
<br />

<label>Debe</label><br />
<input type='text' name='debe' size='11' maxlength='11' value = 0> Bs.F <?php // &#8364; ?>
<br />

<label>Haber</label><br />
<input type='text' name='haber' size='11' maxlength='11' value =0 > Bs.F <?php // &#8364; ?>
<br />


<?php

echo "<label>Soporte Contable</label> <input type='file' name='fich' size='19' maxlength='19'>";
if ($_POST['asiento']) {echo " (Si el asiento ya tiene un soporte será sustituído)";}
echo "<br /><label>Explicación</label> <textarea name='explicacion' rows='6' cols='90'>$expli[0]</textarea>";
echo "<p />";
if ($_GET['n'] == 1) {
	echo "<input type='submit' name='boton' value=\"Guardar Asiento\" onclick='return compruebafecha(form1)'>";
} else {
	echo "<input type='submit' name='boton' value=\"Guardar Registro\" onclick='return compruebafecha(form1)'>";
	if ($debe OR $haber) {
		echo "&nbsp;&nbsp;&nbsp;<a href='altaasigral.php?n=1'";
		if ($cuadre) {echo " onclick=\"return confirm('Asiento descuadrado ¿Continuar con nuevo Asiento?')\"";}
		echo ">Crear nuevo Asiento</a>";
	}
}
?>
</fieldset><p style="clear:both"><p /> 
</form>

<?php

echo $mensaje;

if ($anadido) {

	echo "<table class='basica 100' width='800'>";
	cabasi(2);
	asiento($asiento,"1",$_SESSION['moneda'],$_SESSION['deci'],$_GET['bojust']);
	echo "</table>";

}

?>

</div></body></html>
