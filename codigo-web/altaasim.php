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
?>
<script language="Javascript" src="selec_fecha.js" type='text/javascript'></script>

<?php

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

<body <?php if (!$bloqueo) {echo $onload;}?>>

<?php

include("arriba.php");
$menu11=1;include("menusizda.php");

if ($elmonto) {
	include ("altaasim2.php");
//	$cuadre = totalapu($asiento);
}

?>

<form enctype='multipart/form-data' name='form1' action='altaasim.php' method='post' onSubmit="return altaasim(form1)">

<label>Asiento</label>
<input type='text' name='asiento' value="<?php echo $asiento;?>" maxlength="11" size="11" <?php echo $readonly;?> > 

<label>Fecha</label>

<?php
/*
<input type='text' name='lafecha' size='8' maxlength='8' value="<?php echo $fecha;>" <?php echo $readonly;>>
<input type="button" name="selfecha" value="..."  onclick="displayDatePicker('fecha','','dmy');">
*/


if (!$_POST['asiento']) {
/*
    $hoy = date("d/m/Y");
	escribe_formulario(fecha, form1.fecha, 'd/m/yyyy', '', '', $hoy, '0', '10'); 

	echo '<p /> ';
*/
	$hoy = date("d/m/Y");
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
	<?php 
	$temp = "Primer Registro:";
} else {
	echo $fecha.'<p />';
	echo "<input type = 'hidden' value ='".$fecha."' name='fecha'>"; 
	$temp = "Siguiente Registro:";
	$expli = mysql_fetch_array(mysql_query("SELECT enc_explic FROM sgcaf830 WHERE enc_clave = '".$_POST['asiento']."'"));
}
?>

<fieldset><legend><?php echo $temp;?></legend>

<?php
pantalla_asiento_simple($fecha,$debcre, $cuenta1, $cuenta2, $concepto, $referencia, $elmonto);
echo "<label>Soporte Contable</label> <input type='file' name='fich' size='19' maxlength='19'>";
if ($_POST['asiento']) {echo " (Si el asiento ya tiene un soporte será sustituído)";}
echo "<br /><label>Explicación</label> <textarea name='explicacion' rows='4' cols='90'>$expli[0]</textarea>";
// echo "<p />";
if ($_GET['n'] == 1) {
	echo "<input type='submit' name='boton' value=\"Guardar Asiento\" tabindex='10' onclick='return valfecha(form1)'> ";
//	echo "<input type='submit' name='boton' value=\"Guardar Asiento\" tabindex='10' onclick='return reviso()'> ";
} else {
	echo "<input type='submit' name='boton' value=\"Guardar Registro\" tabindex='10' onclick='return valfecha(form1)'>";
	if ($elmonto) {
		echo "&nbsp;&nbsp;&nbsp;<a href='altaasim.php?n=1'";
		if ($cuadre) {echo " onclick=\"return confirm('Asiento descuadrado ¿Continuar con nuevo Asiento?')\"";}
		echo ">Crear nuevo Asiento</a>";
	}
}
?>
</fieldset><p style="clear:both">
<?php // <p /> 
?>
</form>

<?php

// echo $mensaje;

if ($anadido) {

	echo "<table class='basica 100' width='800'>";
	cabasi(2);
	asiento($asiento,"1",$_SESSION['moneda'],$_SESSION['deci'],$_GET['bojust']);
	echo "</table>";

}

?>

</div></body></html>

<?php
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
<input type = 'text' size='12' maxlength='12' name='elmonto' value='<?php echo $elmonto;?>' tabindex='9'>
</td>
</tr>
<tr>

<?php
}

/*
<script>

var oldLink = null;
// code to change the active stylesheet
function setActiveStyleSheet(link, title) {
  var i, a, main;
  for(i=0; (a = document.getElementsByTagName("link")[i]); i++) {
    if(a.getAttribute("rel").indexOf("style") != -1 && a.getAttribute("title")) {
      a.disabled = true;
      if(a.getAttribute("title") == title) a.disabled = false;
    }
  }
  if (oldLink) oldLink.style.fontWeight = 'normal';
  oldLink = link;
  link.style.fontWeight = 'bold';
  return false;
}

// This function gets called when the end-user clicks on some date.
function selected(cal, date) {
  cal.sel.value = date; // just update the date in the input field.
  if (cal.dateClicked && (cal.sel.id == "sel1" || cal.sel.id == "sel3"))
    // if we add this call we close the calendar on single-click.
    // just to exemplify both cases, we are using this only for the 1st
    // and the 3rd field, while 2nd and 4th will still require double-click.
    cal.callCloseHandler();
}

// And this gets called when the end-user clicks on the _selected_ date,
// or clicks on the "Close" button.  It just hides the calendar without
// destroying it.
function closeHandler(cal) {
  cal.hide();                        // hide the calendar
//  cal.destroy();
  _dynarch_popupCalendar = null;
}

// This function shows the calendar under the element having the given id.
// It takes care of catching "mousedown" signals on document and hiding the
// calendar if the click was outside.
function showCalendar(id, format, showsTime, showsOtherMonths) {
  var el = document.getElementById(id);
  if (_dynarch_popupCalendar != null) {
    // we already have some calendar created
    _dynarch_popupCalendar.hide();                 // so we hide it first.
  } else {
    // first-time call, create the calendar.
    var cal = new Calendar(1, null, selected, closeHandler);
    // uncomment the following line to hide the week numbers
    // cal.weekNumbers = false;
    if (typeof showsTime == "string") {
      cal.showsTime = true;
      cal.time24 = (showsTime == "24");
    }
    if (showsOtherMonths) {
      cal.showsOtherMonths = true;
    }
    _dynarch_popupCalendar = cal;                  // remember it in the global var
	var today = new Date();
	var anterior = today.getFullYear();
	var actual = today.getFullYear();
    cal.setRange(anterior, actual);        // min/max year allowed.
    cal.create();
  }
  _dynarch_popupCalendar.setDateFormat(format);    // set the specified date format
  _dynarch_popupCalendar.parseDate(el.value);      // try to parse the text in field
  _dynarch_popupCalendar.sel = el;                 // inform it what input field we use

  // the reference element that we pass to showAtElement is the button that
  // triggers the calendar.  In this example we align the calendar bottom-right
  // to the button.
  _dynarch_popupCalendar.showAtElement(el.nextSibling, "Br");        // show the calendar

  return false;
}

var MINUTE = 60 * 1000;
var HOUR = 60 * MINUTE;
var DAY = 24 * HOUR;
var WEEK = 7 * DAY;

// If this handler returns true then the "date" given as
// parameter will be disabled.  In this example we enable
// only days within a range of 10 days from the current
// date.
// You can use the functions date.getFullYear() -- returns the year
// as 4 digit number, date.getMonth() -- returns the month as 0..11,
// and date.getDate() -- returns the date of the month as 1..31, to
// make heavy calculations here.  However, beware that this function
// should be very fast, as it is called for each day in a month when
// the calendar is (re)constructed.

function isDisabled(date) {
  var today = new Date();
   return (Math.abs(date.getTime() - today.getTime()) / DAY) > 10;
}
function flatSelected(cal, date) {
  var el = document.getElementById("preview");
  el.innerHTML = date;
}

function showFlatCalendar() {
  var parent = document.getElementById("display");

  // construct a calendar giving only the "selected" handler.
  var cal = new Calendar(0, null, flatSelected);

  // hide week numbers
  cal.weekNumbers = false;

  // We want some dates to be disabled; see function isDisabled above
  cal.setDisabledHandler(isDisabled);
  cal.setDateFormat("%A, %B %e");

  // this call must be the last as it might use data initialized above; if
  // we specify a parent, as opposite to the "showCalendar" function above,
  // then we create a flat calendar -- not popup.  Hidden, though, but...
  cal.create(parent);

  // ... we can show it here.
  cal.show();

}

</script>
*/
?>
