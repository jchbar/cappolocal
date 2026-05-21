<?php
include("head.php");
?>
<script language="javascript">

function abrirVentana(elorden)
{
window.open("lisingpdf.php?orden="+elorden,"parte1","top=0,left=395,status=no,toolbar=no,scrollbar=yes,location=no,type=fullWindow,fullscreen");	
}

// <script type="text/javascript">

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
    cal.setRange(1990, 2020);        // min/max year allowed.
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
<?php
// include("paginar.php");

extract($_GET);
extract($_POST);
extract($_SESSION);


if (!$link OR !$_SESSION['empresa']) {
	include("noempresa.php");
	exit;
}
?>
<body onLoad="showFlatCalendar()">
 <?php // if (!$bloqueo) {echo $onload;}>>
?>

<?php

$readonly=" readonly='readonly'";
include("arriba.php");
// $menu61=1;
include("menusizda.php");
$ip = $_SERVER['HTTP_CLIENT_IP'];
if (!$ip) {$ip = $_SERVER['REMOTE_ADDR'];}
if (!$accion) {
	echo "<div id='div1'>";
	echo "<form action='lising.php?accion=Listado' name='form1' method='post'>";
	echo '<fieldset><legend>Información Para Listado de Ingreso de Socios</legend>';
/*
	echo 'Listado ordenado por ';
	$orden='Codigo';
	echo '<select name="elorden" size="1">';
	$sql="select nombre from sgcaf000 where tipo='OrdLisSoc' order by nombre";
	$resultado=mysql_query($sql);
	while ($fila2 = mysql_fetch_assoc($resultado)) {
		echo '<option value="'.$fila2['nombre'].'" '.(($orden==$fila2['nombre'])?'selected':'').'>'.$fila2['nombre'].'</option>';}
 	echo '</select> '; 
*/
?>
<script type="text/javascript">
// setActiveStyleSheet(this, 'green');
setActiveStyleSheet(document.getElementById("defaultTheme"), "green");
</script>
	Desde:</b> <input type="text" name="date3" id="sel3" size="12" readonly="readonly"
><input type="reset" value=" ... "
onclick="return showCalendar('sel3', '%d/%m/%Y');"><br />
	Hasta:</b> <input type="text" name="date4" id="sel1" size="12" readonly="readonly"
><input type="reset" value=" ... "
onclick="return showCalendar('sel1', '%d/%m/%Y');">
	<?php 
	echo '<input type="submit" name="Submit" value="Obtener Reporte" />';
	echo '</legend>';
	echo '</form>';
	echo '</div>';
}	// !$accion
if ($accion=='Listado') {
	echo '<div id="display" style="float: right; clear: both;"></div>';
	echo "<div id='div1'>";

	echo "<form action='lising.php?accion=Listo' name='form1' method='post'>"; 
	echo '<fieldset><legend>Recopilando información Para Listado </legend>'; 
	echo '<h2>Preparando información...</h2>';
	echo '<input type="submit" name="Submit" value="Impresión de Listado" onClick="abrirVentana(';
	echo "'";
	echo $elorden;
	echo "&desde=".convertir_fecha($date3)."&hasta=".convertir_fecha($date4);
	echo "'";
	echo ');">  ';
	echo '</legend>';
	echo '</form>';
	echo '</div>';	
}	// ($accion=='Listado')
if (($accion=='Listo')) { // and ($nominasnormales == 'on')) {
// if ($nominasnormales == 'on') {
//	$fechadescuento=$_POST['fechadescuento'];
//	$nombre_archivo=$_POST['nombre_archivo'];
//	echo '<input type="hidden" name="nombre_archivo" value = "'.$nombre_archivo.'"/>';
	echo "<div id='div1'>";
	
	echo '<h2>Listado Generado...</h2>';
	echo '</div>';
}	// ($accion=='Listo') 

?>

<?php include("pie.php");?>

</body></html>

