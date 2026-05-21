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
extract($_POST);
extract($_GET);
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
	$lafecha = $_POST['fecha'];
	$tipo =$_POST['tipo'];
	$debcre= $_POST['debcre'];
	$cuenta1= $_POST['cuenta1'];
	$referencia =$_POST['referencia'];
	$elmonto=$_POST['elmonto'];
}

?>

<body <?php if (!$bloqueo) {echo $onload;}?>>

<?php

include("arriba.php");
$menu11=2;include("menusizda.php");

if ($elmonto) {
	include ("altaasigral2.php");
//	$cuadre = totalapu($asiento);
}

?>

<form enctype='multipart/form-data' name='form1' action='altaasigral.php' method='post' onSubmit="return altaasigral(form1)">

<label>Asiento</label>
<input type='text' name='asiento' value="<?php echo $asiento;?>" maxlength="11" size="11" <?php echo $readonly;?> > 

<label>Fecha</label>
<?php
// <input type='text' name='lafecha' size='8' maxlength='8' value="<?php echo $fecha;>" <?php echo $readonly;>>
// <input type="button" name="selfecha" value="..."  onclick="displayDatePicker('fecha','','dmy');">
?>
	
<?php
if (!$_POST['asiento']) {
	$temp = "Primer Registro:";
	$hoy = date("d/m/Y");
//	escribe_formulario(fecha, form1.fecha, 'd/m/yyyy', '', '', $hoy, '0', '10'); 
    $fechanueva=explode('/',$hoy);
	$fechanueva=$fechanueva[1].'/'.$fechanueva[0].'/'.$fechanueva[2];
	$sqlano='select substr(fech_ejerc,1,4) as ano from sgcaf100';
	$sqlfano=mysql_query($sqlano);
	$sqlrano=mysql_fetch_assoc($sqlfano);
	$rango=$sqlrano['ano'];
	$sqlano='select substr(now(),1,4) as ano';
	$sqlfano=mysql_query($sqlano);
	$sqlrano=mysql_fetch_assoc($sqlfano);
	if ($sqlrano['ano'] > $rango)
		$rango.=', '.$sqlrano['ano'];
//	echo 'el rango '.$rango; // .' ---- '.$sqlrano['ano'];
	?>
	<input type="hidden" name="fecha" id="fecha" value=" <?php  echo $fechanueva; ?>"/>
   <span style="background-color: #ff8; cursor: default;"
         onmouseover="this.style.backgroundColor='#ff0';"
         onmouseout="this.style.backgroundColor='#ff8';"
         id="show_ingcapu" 
   ><?php  echo ($hoy); ?></span> *
<script type="text/javascript">
    Calendar.setup({
//		showAt(220, 250)
//		position       : 	{100,300},
        inputField     :    "fecha",     // id of the input field
        ifFormat       :    "%d/%m/%Y",     // format of the input field (even if hidden, this format will be honored)
        displayArea    :    "show_ingcapu",       // ID of the span where the date is to be shown
//        daFormat       :    "%A, %B %d, %Y",// format of the displayed date
        daFormat       :    "%B %d, %Y",// format of the displayed date
        align          :    "Tl",           // alignment (defaults to "Bl")
        singleClick    :    true,
		weekNumbers    :    false, 
		range          :     [<?php echo $rango; ?>],

// desactivacion de 18 años pa' tras


/*
		dateStatusFunc :    function (date) { // disable weekend days (Saturdays == 6 and Subdays == 0)
						var today = new Date();
						return (
//							  (date.getTime() < today.getTime()-((365*18)*24*60*60*1000))
							  (date.getTime() > today.getTime()-(1*24*60*60*1000)) 
							  // || date.getTime() > today.getTime()+(10*24*60*60*1000))	date.getDay() == 0 || 
							  ) ? true : false;  }
*/
					    });
</script>
<?
} else {
	echo $fecha.'<p />';
	echo "<input type = 'hidden' value ='".$fecha."' name='fecha'>"; 
	$temp = "Siguiente Registro:";
	$expli = mysql_fetch_array(mysql_query("SELECT enc_explic FROM sgcaf830 WHERE enc_clave = '".$_POST['asiento']."'"));
}
?>

<fieldset><legend><?php echo $temp;?></legend>

<?php
pantalla_asiento($fecha,$elcargo, $cuenta1, $concepto, $referencia, $elmonto);
echo "<label>Soporte Contable</label> <input type='file' name='fich' size='19' maxlength='19'>";
if ($_POST['asiento']) {echo " (Si el asiento ya tiene un soporte será sustituído)";}
echo "<br /><label>Explicación</label> <textarea name='explicacion' rows='4' cols='90'>$expli[0]</textarea>";
echo "<p />";
if ($_GET['n'] == 1) {
	echo "<input type='submit' name='boton' value=\"Guardar Asiento\" onclick='return compruebafecha(form1)'>";
} else {
	echo "<input type='submit' name='boton' value=\"Guardar Registro\" onclick='return compruebafecha(form1)'>";
	if ($elmonto) {
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

<?php 
function pantalla_asiento($fechax,$elcargo, $cuenta1, $concepto, $referencia, $elmonto)
{
// <th width="50">Fecha</th>
// <tr><td>
// <input type = 'text' maxlength='8' size='8' name='fecha' value='<?php echo $fechax;>' readonly='readonly' tabindex='3'>
// </td>
?>
<table class='basica 100' width='800'>
<tr><th width="40"> </th><th width="100">Cuenta</th><th width="200">Concepto</th><th width="80">Referencia</th><th width="80">Monto</th></tr>
<td>
<?php 
$activar=' ';
if (($elcargo == '+')) {$activar='checked="checked"'; } else { $activar = ' '; }
//  || ($elcargo = 1)
// value="<?php echo $elcargo;>" 
?>
<input name="elcargo" type="checkbox" tabindex='4' <?php echo $activar;?> /> 
Cargo
</td><td>
<input type="text" size="20" tabindex='5' name='cuenta1' id="inputString" onKeyUp="lookup(this.value);" onBlur="fill();" value ="<?php echo $cuenta1;?>" autocomplete="off"/>
			<div class="suggestionsBox" id="suggestions" style="display: none;">
				<img src="upArrow.png" style="position: relative; top: -12px; left: 70px; "  alt="upArrow" />
				<div class="suggestionList" id="autoSuggestionsList">
				</div>
			</div>
		</div>

</td><td>
<input type = 'text' size='40' maxlength='60' name='concepto' tabindex='6' value ="<?php echo $concepto?>">
</td><td>
<input type = 'text' value ='<?php echo $referencia?>' size='10' maxlength='10' name='referencia' tabindex='7'>
</td><td>
<input type = 'text' size='12' maxlength='12' name='elmonto' value='<?php echo $elmonto;?>' tabindex='8'>
</td>
</tr>
<tr>
<?php
}
?>
