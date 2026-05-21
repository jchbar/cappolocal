<?php
	include("head.php");
?>
<script language="javascript">
function abrir2Ventanas(fechadescuento)
{
window.open("cuobanvolpdf1.php?fechadescuento="+fechadescuento,"parte1","top=0,left=395,status=no,toolbar=no,scrollbar=yes,location=no,type=fullWindow,fullscreen");	// los primeros 500 socios	width=385,height=180,
window.open("cuobanvolpdf4.php?fechadescuento="+fechadescuento,"banco","top=0,left=395,status=no,toolbar=no,scrollbar=yes,location=no,type=fullWindow,fullscreen");
// "width=385,height=180,top=0,left=395,status,toolbar=1,scrollbar s,location");	// el listado a banco
window.open("cuobanvolpdf5.php?fechadescuento="+fechadescuento,"amortiza","top=0,left=395,status=no,toolbar=no,scrollbar=yes,location=no,type=fullWindow,fullscreen");
}
</script>
<script language="javascript">
//Creo una función que imprimira en la hoja el valor del porcentanje asi como el relleno de la barra de progreso
function callprogress(vValor){
 document.getElementById("getprogress").innerHTML = vValor;
 document.getElementById("getProgressBarFill").innerHTML = '<div class="ProgressBarFill" style="width: '+vValor+'%;"></div>';
}
</script>
<style type="text/css">
/* Ahora creo el estilo que hara que aparesca el porcentanje y relleno del mismoo*/
      .ProgressBar     { width: 16em; border: 1px solid black; background: #eef; height: 1.25em; display: block; }
      .ProgressBarText { position: absolute; font-size: 1em; width: 16em; text-align: center; font-weight: normal; }
      .ProgressBarFill { height: 100%; background: #aae; display: block; overflow: visible; }
    </style>
</script>
<script language="javascript">
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
	var anterior = today.getFullYear()-1;
	var actual = today.getFullYear()+1;
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
<?php
/*
 <script language="Javascript" src="selec_fecha.js" type='text/javascript'></script>
*/
?>
<?php
include("paginar.php");

extract($_GET);
extract($_POST);
extract($_SESSION);


if (!$link OR !$_SESSION['empresa']) {
	include("noempresa.php");
	exit;
}
?>
<body <?php if (!$bloqueo) {echo $onload;}?>>


<?php

$readonly=" readonly='readonly'";
include("arriba.php");
// $menu61=1;
include("menusizda.php");
$ip = $_SERVER['HTTP_CLIENT_IP'];
if (!$ip) {$ip = $_SERVER['REMOTE_ADDR'];}
// echo '<h1>Desarrollando.... pronto estara lista</h1>';
if (!$accion) {
	echo "<div id='div1'>";
	echo "<form action='cuobanvol.php?accion=ListadoDeCuotas' name='form1' method='post'>";
	echo '<fieldset><legend>Información Para Descuentos de Prestamos</legend>';
	echo 'Fecha en que se realiza el descuento: ';
	$fechadelabono=date("d")."/".date('m')."/".date("Y"); 
?>
<script type="text/javascript">
// setActiveStyleSheet(this, 'green');
setActiveStyleSheet(document.getElementById("defaultTheme"), "green");
</script>
	</b> <input type="text" name="fechadelpago" id="sel3" size="12" readonly
><input type="reset" value=" ... "
onclick="return showCalendar('sel3', '%d/%m/%Y');"><br />
	<?php 
	// echo '<br>Nominas Normales <input type="checkbox" name="nominasnormales" value = "on" checked align="right"/><br />';
	echo '<br><input type="hidden" name="nominasnormales" value = "on" checked align="right"/><br />';
	echo '<input type="submit" name="Submit" value="Enviar" />';
	echo '</legend>';
	echo '</form>';
	echo '</div>';
}	// !$accion
// echo 'accion = '.$accion;
// echo 'nominas '.$_POST['nominasnormales'];
// recordar bloquear la base de datos durante este proceso y luego liberarla
if (($accion=='ListadoDeCuotas') and ($nominasnormales == 'on')) {
	echo 'La fecha del día de hoy es: '. date("d/m/Y"). ' La hora local del servidor es: '. date("G:i:s").'<br>'; 
	$fechadescuento=convertir_fecha($_POST['fechadelpago']);		// revisar que no hayan nominas con esa fecha
	$sql="delete from sgcanoprvol where fecha = '$fechadescuento' ";
	$resultado=mysql_query($sql);
	$sql_amor="delete from sgcaamorvol where ('$fechadescuento' = fecha)"; //  limit 30"; //  limit 20";
	$resultado=mysql_query($sql_amor);
//	echo $sql;
	$sql="select count(fecha) as cuantos, ip from sgcanoprvol where fecha = '$fechadescuento' group by fecha";
	$resultado=mysql_query($sql);
	if (mysql_num_rows($resultado)>0) {
		$registro=mysql_fetch_assoc($resultado);
		echo '<h2>No se puede procesar esta nomina existe una ya realizada con '.$registro['cuantos'].' registro generada desde la IP '.$registro['ip'].'</h2>';
		exit;
	}
	// verificar codigos contra cedulas 
	$sql="select cod_prof, ced_prof from sgcaf200 where ced_prof='V-03855577' order by cod_prof";
	$sql="select cod_prof, ced_prof from sgcaf200 order by cod_prof";
	$a_200=mysql_query($sql);
	$registros=mysql_num_rows($a_200);
	$ValorTotal=$registros;
	if ($registros < 30)
		$registros = 30;
	$todobien=true;
	$cuantos=0;
	echo '
	 <div class="ProgressBar">
      <div class="ProgressBarText"><span id="getprogress"></span>&nbsp;% completado</div>
      <div id="getProgressBarFill"></div>
    </div>';
	set_time_limit($registros);
//	echo $registros;
	while ($r200 = mysql_fetch_assoc($a_200))
	{
		$cobuscar=$r200['cod_prof'];
		$cebuscar=$r200['ced_prof'];
		$sql2="select codsoc_sdp, cedsoc_sdp, nropre_sdp from sgcaf310 where codsoc_sdp='$cobuscar'";

//		echo $sql2.'<br>';
		$a_310=mysql_query($sql2);
		while ($r310 = mysql_fetch_assoc($a_310))
		{


//			echo $cebuscar .' / '.$r310['cedsoc_sdp'].'<br>';
//			$estacedula=substr($r310['cedsoc_sdp'],0,4).substr($r310['cedsoc_sdp'],4,3).substr($r310['cedsoc_sdp'],7,3);
			$estacedula=substr($cebuscar,0,4).'.'.substr($cebuscar,4,3).'.'.substr($cebuscar,7,3);
//			die($estacedula);
// echo $estacedula. ' '.$r310['cedsoc_sdp'].'<br>';
			if ($estacedula != $r310['cedsoc_sdp'])
			{
				echo $cobuscar. ' - '.$estacedula. ' - '.$r310['cedsoc_sdp'].'<br>';
				$todobien=false;
			}
		}
	}
	echo "</div>";
	if (! $todobien)
		die ('<h2> Las siguiente informacion tiene problemas en prestamo, corregir');
	$fechaarchivo=explode('-',$fechadescuento);
	$fechaarchivo=$fechaarchivo[0].$fechaarchivo[1].$fechaarchivo[2];
	$nombre_archivo = 'nompre/'.$fechaarchivo.'domiciliacionvoluntario.txt';
	$contenido = $nombre;
	fopen($nombre_archivo, 'w');

	// Asegurarse primero de que el archivo existe y puede escribirse sobre el.
	$registros=mysql_num_rows($a_200);
	if ($registros < 30)
		$registros = 30;
	set_time_limit($registros);
	if (is_writable($nombre_archivo)) {

		// En nuestro ejemplo estamos abriendo $nombre_archivo en modo de adicion.
		// El apuntador de archivo se encuentra al final del archivo, asi que
		// alli es donde ira $contenido cuando llamemos fwrite().
		if (!$gestor = fopen($nombre_archivo, 'a')) {
			echo "<h2>No se puede abrir el archivo ($nombre_archivo) revise permisologia</h2>";
			exit;
		}
		else {

			echo "<div id='div1'>";
			echo "<form action='cuobanvol.php?accion=Abonar' name='form1' method='post' onsubmit='return realizo_abono(form1)'>";
			echo '<input type="hidden" name="nombre_archivo" value = "'.$nombre_archivo.'"/>';
			echo '<input type="hidden" name="nominasnormales" value = "on"/>';
			$fechadescuento=$_POST['fechadelpago'];
			echo '<fieldset><legend>Recopilando información Para Descuentos de Prestamos al '.$fechadescuento.'</legend>';
			echo '<h2>Preparando información...</h2>';
			$fechadescuento=convertir_fecha($fechadescuento);
			$sql_360="select * from sgcaf360 where (tipo = 'VOLUNTARIO') order by cod_pres";
			$a_360=mysql_query($sql_360);
			$sql_200="select cod_prof, ced_prof, concat(ape_prof, ' ', nombr_prof) as nombre, ctan_prof from sgcaf200 where (ucase(statu_prof) != 'RETIRA') and (tipo_socio='P') and (ced_prof='V-03855577' or ced_prof='V-16770549'  or ced_prof='V-17572385') order by ced_prof";
			$sql_200="select cod_prof, ced_prof, concat(ape_prof, ' ', nombr_prof) as nombre, ctan_prof from sgcaf200 where (ucase(statu_prof) != 'RETIRA') and (ucase(statu_prof) != 'SUSPEN') and (tipo_socio='P') order by ced_prof";
			$a_200=mysql_query($sql_200);
			$registros = mysql_num_rows($a_200);
			$ValorTotal=$registros;
			$cuantos=0;
			while ($r200 = mysql_fetch_assoc($a_200))
			{
				$cedula=$r200['ced_prof'];
				$micedula=substr($cedula,0,4).'.'.substr($cedula,4,3).'.'.substr($cedula,7,4);
				
				$cuantos++;
				$porcentaje = $cuantos * 100 / $ValorTotal; //saco mi valor en porcentaje
				echo "<script>callprogress(".round($porcentaje).")</script>"; //llamo a la función JS(JavaScript) para actualizar el progreso
				flush(); //con esta funcion hago que se muestre el resultado de inmediato y no espere a terminar todo el bucle con los 25 registros para recien mostrar el resultado
				ob_flush();


				// original sin sp_
				$sql_310="select * from sgcaf310 where (stapre_sdp='A') and (cedsoc_sdp='$micedula') and (f_1cuo_sdp <= '$fechadescuento') order by codpre_sdp";
				$a_310=mysql_query($sql_310);
//				echo $sql_310.'<br>';
				// fin original sin sp_

/*
				// prueba con sp_
				$sql_310="call sp_items_a_descontar('$micedula','$fechadescuento')";
				$a_310=mysql_query($sql_310);
//				echo mysql_num_rows($a_310).'<br>';
				// fin prueba con sp_
*/

				revisar_prestamo($r200,$a_310,$a_360,$fechadescuento,$micedula,$ip,$gestor);
			} // ($r200 = mysql_fetch_assoc($a_200))
			echo '<input type="hidden" name="fechadescuento" value="'.$fechadescuento.'">';
			echo '<h2>Información Lista...</h2><br>';
			echo '<h2>Se ha generado el archivo '.$nombre_archivo.'<br> para su procesamiento a banco</h2>';
			echo '<input type="submit" name="Submit" value="Impresión de Listados" onClick="abrir2Ventanas(';
			echo "'";
			echo $fechadescuento;
			echo "'";
//			echo "'".'&downloadfile='.$nombre_archivo.'&';
			echo ');">  ';
//			echo '<input type="submit" name="Submit" value="Realizar Abono " />';
			echo '</legend>';
			echo '</form>';
			echo '</div>';	
		}
		fclose($gestor);
/*
		$downloadfile=nombre_archivo;
		echo 'header ("Content-Disposition: attachment; filename=\".$downloadfile.\"" )';
//		header ("Content-Disposition: attachment; filename=\"exportar.txt\"" );
		echo 'header("Content-Type: application/force-download")';
		echo 'header("Content-Transfer-Encoding: binary")';
		echo 'header("Content-Length: ".strlen($downloadfile))';
// 		header("Content-Length: ".strlen($filecontent));
		echo 'header("Pragma: no-cache")';
		echo 'header("Expires: 0")' ;
		echo $downloadfile;
*/
	}
	else {
		echo "<h2>No se puede crear el archivo ($nombre_archivo) revise permisologia</h2>";
		exit;
	}
	echo 'La fecha final de hoy es: '. date("d/m/Y"). ' La hora local del servidor es: '. date("G:i:s").'<br>'; 
	set_time_limit(30);
}	// ($accion=='ListadoDeCuotas')
if (($accion=='Abonar')) { // and ($nominasnormales == 'on')) {
// if ($nominasnormales == 'on') {
	$fechadescuento=$_POST['fechadescuento'];
	$nombre_archivo=$_POST['nombre_archivo'];
//	echo '<input type="hidden" name="nombre_archivo" value = "'.$nombre_archivo.'"/>';
	echo "<div id='div1'>";
	
	echo '<h2>Puede proceder luego de la impresion de los listados a <br>realizar el abono a prestamos y el asiento contable y';
	echo '<br>recuerde obtener descargar el archivo </h2><h1>'.$nombre_archivo.'</h1><h2> para enviar al banco</h2>';
//	echo "<form action='bajpre.php' name='form1' method='post'>";
//	echo '<input type="submit" name="Submit" value="Descargar Archivo TXT"';
//	echo '</form>';
	/// hacer el asiento ver asiento original con intereses y demas
/*	echo '<script language="JavaScript" src="cuobanpdf1.php?fechadescuento=$fechadescuento"></script>';  */
//	echo '<a href="" onClick="abrir2Ventanas();">KK</a>';
//	echo "<a target=\"_blank\" href=\"cuobanpdf1.php?fechadescuento=$fechadescuento\" onClick=\"info.html\', \'\',\'width=250, height=190\')\">Imprimir Listados de Descuentos</a>"; 	

	echo "<form action='cuobanvol.php?accion=Asiento' name='form1' method='post' onsubmit='return realiza_asiento_montepio(form1)'>";
	$fechadescuento=$_POST['fechadescuento'];
	echo '<input type="hidden" name="fechadescuento" value = "'.$fechadescuento.'">';
	echo '<input type="hidden" name="nombre_archivo" value = "'.$nombre_archivo.'"/>';
	echo '<input type="submit" name="procesar" value="Generar Asiento Contable" />';
	echo '</form>';
	echo '</div>';
}	// ($accion=='ImpresionListados') 
if (($accion=='Asiento')) { 
/////
	$fechadescuento=$_POST['fechadescuento'];
	$sql_360="select * from sgcaf360 where tipo='VOLUNTARIO' order by cod_pres"; //  limit 30"; //  limit 20";
	$a_360=mysql_query($sql_360);
	$columna=3;
	$rpl=300; 	// registros por listado
	$crl=0;		// contador de registros por listado
	$col_listado=0;
	$nuevoarchivo=false;
	$condicion_sql='select codigo, cedula, nombre, nrocta, ';
	$col_listado=0;
	$max_cols=mysql_num_rows($a_360);
	echo 'Realizando Calculo<br>';
	while ($r360 = mysql_fetch_assoc($a_360))
	{
		$col_listado++;
		$columna++;
		if (trim($r360['desc_cor'])!='') ;// $header[$columna]=$r360['desc_cor'] ;
		else ; // $header[$columna]=substr($r360['descr_pres'],0,12);
		$totales[$col_listado]=0;
		$campo='colpre'.$col_listado;
		$condicion_sql.=' colpre'.$col_listado;
		if ($col_listado != $max_cols) 
		{
			$arrtitulo.=', ';
			$condicion_sql.=', ';
		}
	}
	$sql_nopr=$condicion_sql." from sgcanoprvol where ('$fechadescuento' = fecha) order by nombre "; //  limit 20";
	$a_nopr=mysql_query($sql_nopr);
	$registros=mysql_num_rows($a_nopr);
	set_time_limit($registros);
	$lascolumnas=mysql_num_fields($a_nopr);
	while ($r_nopr = mysql_fetch_assoc($a_nopr)){
		$t1=0;
		for ($prestamos=1;$prestamos<=$lascolumnas;$prestamos++) {		// sumatoria de los prestamos
			$item='colpre'.$prestamos;
			$t1+=$r_nopr[$item];
			$totales[$prestamos]+=$r_nopr[$item];
		}
	}
	$general=0;
	for ($i=1;$i<count($totales);$i++)
		if ($totales[$i]!=0) {
			$general+=$totales[$i];
	}
	set_time_limit(30);

	$b=$fechadescuento;
	$b2 = date("Y-m-d");
	$c=explode('-',$b2);
	$asiento=$c[0].$c[1].$c[2].'451';
	echo "Generado Asiento Contable <strong><a target=\"_blank\" href='editasi2.php?asiento=$asiento'>$asiento </a></strong> <br>";
	$cuento='Nomina por cobrar al Banco de fecha '.convertir_fechadmy($b);
	$sql = "INSERT INTO sgcaf830 (enc_clave, enc_fecha, enc_desco, enc_desc1, enc_debe, enc_haber, enc_item, enc_dif, enc_igual, enc_refer, enc_sw, enc_explic) VALUES ('$asiento', '$b', '$cuento','',0,0,0,0,0,0,0,'$cuento')"; 
	if (!mysql_query($sql)) die ("El usuario $usuario no tiene permiso para añadir Asientos.<br>".$sql);

	$sql="select * from sgcaf000 where tipo='CtaPrexCobAmo'";
	$sql="select * from sgcaf000 where tipo='CtaDepTransito'";
	$result=mysql_query($sql); 
	$cuentas=mysql_fetch_assoc($result);
	$cuenta_amortizacion=trim($cuentas['nombre']);
	$sql="select * from sgcaf000 where tipo='CtaPrexCobBco'";
	$result=mysql_query($sql); 
	$cuentas=mysql_fetch_assoc($result);
	$cuentabanco=trim($cuentas['nombre']);			
	$referencia='';
	$debe=$general;
	agregar_f820($asiento, $b2, '+', $cuentabanco, 'Amort. Prest. p/cobrar banco al '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
	agregar_f820($asiento, $b2, '-', $cuenta_amortizacion, 'Total Retenciones del '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 	

	$nombre_archivo=$_POST['nombre_archivo'];
	echo '<form action="depositotxt.php" method="post" name="form1" enctype="multipart/form-data">';
	echo '<input type="hidden" name="archivo" value = "'.$nombre_archivo.'">';
	echo '<input type="submit" name="procesar" value="Descargar Archivo '.$nombre_archivo.'" />';
	echo '</form>';
	
	$comando = "update sgcaf8co set fechanominamiercoles= now()";
	$resultado=mysql_query($comando);
	

/////
}


function revisar_prestamo($r200,$a_310,$a_360,$fechadescuento,$micedula,$ip,$gestor)
{
	$primeravez=0;
	$totalxsocio=0;
	while ($r310 = mysql_fetch_assoc($a_310))
	{
		if (! $r310['renovado'])
		{
			if ($r310['stapre_sdp'] == 'A')
			{
				acumular($r200,$r310,$a_360,$fechadescuento,$micedula,$primeravez,$ip,$totalxsocio) ;
				// echo $micedula. ' ' .$totalxsocio.'<br>';
			}
			else ;
		}
		else 
			if ($r310['stapre_sdp'] == 'A')
				if ($r310['paga_hasta'] >= $fechadescuento)
					acumular($r200,$r310,$a_360,$fechadescuento,$micedula,$primeravez,$ip,$totalxsocio);
	}
	if ($totalxsocio > 0){	// meterlo en el listado a banco
		listadotxt($r200,$totalxsocio,$gestor);
	}
}

function listadotxt($r200,$totalxsocio,$gestor)
{
//0201082457570200015888V07333526        00000000000008937ABARCA DE G.TERESA G.                                                 00CAPPOUCL              *
//0201082457510200129328V16770549        00000000000000010Xx  CARRASCO R. TONDIS MIGUEL                                         00CAPPOUCL              *
	$cadena='02'.$r200['ctan_prof'];
	$cadena.=substr($r200['ced_prof'],0,1).substr($r200['ced_prof'],2,8).replicate(' ',8);
	$monto=$totalxsocio*100;
				// echo $micedula. ' ' .$totalxsocio.'<br>';
//	$monto=explode('.',$totalxsocio);
	// quito el punto
	$sinpunto='';

	for ($i=0;$i<strlen($monto);$i++)
		if (substr($monto,$i,1)!= '.')
			$sinpunto.=substr($monto,$i,1);



/*
	$sinpunto=$monto[0]; // .$monto[1];
	$decimal=(($totalxsocio-$monto[0])*100)/100; // $monto[1];
	if ($decimal==0) $sinpunto.='00' ;
		else if ($decimal<10) $sinpunto.='0'.substr($decimal,3,1);
			else $sinpunto.=substr($decimal,0,2);
	echo $decimal. ' ';

*/
	$monto=ceroizq($sinpunto,17);
	$cadena.=$monto;
	$nombre=trim($r200['nombre']);
	if ($nombre == '') $nombre='CAPPOUCLA - REVISAR';
	$nombre=substr(trim($nombre),0,40);
	$rellenar=replicate(' ',40-strlen($nombre));
	$cadena.=$nombre.$rellenar;
	$cadena.=replicate(' ',30).'00'.'CAPPOUCL'.replicate(' ',14).'*'.chr(13).chr(10);


	if (fwrite($gestor, $cadena) === FALSE) {
		echo "No se puede escribir al archivo ($nombre_archivo)";
		exit;
	}
	if (($totalsocio == 37.50)) //  or ($totalsocio == 56.60) or ($totalsocio == 30))
	{
		echo $monto.'<br>';
		die($cadena);
	}

}

function replicate($caracterarepetir,$cantidaddeveces)
{
	$resultado='';
	for ($i=0;$i<$cantidaddeveces;$i++)
		$resultado.=$caracterarepetir;
	return $resultado;
}


function acumular($r200,$r310,$a_360,$fechadescuento,$micedula,&$primeravez,$ip,&$totalxsocio)
{
	if ($r310['cuota_ucla'] == 0) {
		$actualiza="update sgcaf310 set cuota_ucla=".$r310['cuota']." where registro =".$r310['registro'];
		$ractualiza=mysql_query($actualiza);
		$lacuota = $r310['cuota'];
	}
	else $lacuota = $r310['cuota_ucla'];
	mysql_data_seek ($a_360, 0);		// volver al principio de la busqueda
	$nombre=trim($r200['nombre']);
	$codigo=$r310['codsoc_sdp'];
	$posicion=0;
	while ($r360 = mysql_fetch_assoc($a_360))
	{
		$posicion++;
		if ($r310['codpre_sdp']==$r360['cod_pres']) {
//			echo $r310['nropre_sdp'].' - ' .$r310['codpre_sdp'].' '; // . ' - '.$r360['cod_pres'];
			$lacolumnapres='colpre'.$posicion;
			$lacolumnanro='colnro'.$posicion;
			$elnumero=$r310['nropre_sdp'];
			if ($primeravez == 0) {
				$nrocuenta=$r200['ctan_prof'];
				$sql_pre="insert into sgcanoprvol (fecha, cedula, codigo, nombre, ".$lacolumnapres.", ".$lacolumnanro.", proceso, ip, nrocta) values ('$fechadescuento','$micedula','$codigo','$nombre','$lacuota','$elnumero',1, '$ip', '$nrocuenta')";
					$primeravez = 1;
			}
			else $sql_pre="update sgcanoprvol set ".$lacolumnapres." = ".$lacolumnapres."+'$lacuota', ".$lacolumnanro." = '$elnumero' where (cedula='$micedula')" ;
//			if ($micedula=='V-11.267.811')  echo $sql_pre;
			if (mysql_query($sql_pre)) {
				$saldo=$r310['monpre_sdp']-$r310['monpag_sdp'];
				$tipo=$r310['codpre_sdp'];
				$capital=$lacuota;
				$coriginal=$lacuota;
				$totalxsocio+=$capital;
				$i2=0;
//				echo $r360['i_max_pres']. ' - ' .$r310['nrocuotas'].' - '.$saldo.' - '.'<br>';
				$interes=cal_int($r360['i_max_pres'],$r310['nrocuotas'],$saldo,$factor_divisible = 24,$z=0,$i2);
//				echo $i2;
				$interes=round(($saldo*$i2),2);
				$cu=round($lacuota,2) - $interes;
				$la_cuota=$interes+$cu;
				if ($saldo <= 0) {
					$interes=0;
					$capital=$coriginal;
//					echo 'entre '.$capital;
				}
				$cuent_p=trim($r360['cuent_pres']).'-'.substr($codigo,1,4);
				$cuent_i=trim($r360['cuent_int']).'-'.substr($codigo,1,4);
				$cuent_d=trim($r360['otro_int']);
				$nrocuota=$r310['ultcan_sdp']+1;
				$tipoprestamo=$r360['tipo'];
				$pos310=$r310['registro'];
				if ($r360['tipo'] == 'Comercial')
//					$sql_amor="insert into sgcaamor (fecha, codsoc, nropre, cedula, nombre, saldo, capital, interes, cuota, codpre, cuent_p, cuent_i, cuent_d, ip, nrocuota, proceso, tipo, pos310, semanal) values ('$fechadescuento', '$codigo', '$elnumero', '$micedula', '$nombre', '$saldo', '".($capital-$interes)."', '$interes', '$la_cuota', '$tipo', '$cuent_p', '$cuent_i', '$cuent_d', '$ip','$nrocuota', 1, '$tipoprestamo', '$pos310', 1)";
					$sql_amor="insert into sgcaamorvol (fecha, codsoc, nropre, cedula, nombre, saldo, capital, interes, cuota, codpre, cuent_p, cuent_i, cuent_d, ip, nrocuota, proceso, tipo, pos310, semanal, diferido) values ('$fechadescuento', '$codigo', '$elnumero', '$micedula', '$nombre', '$saldo', '".($capital)."', '$interes', '$la_cuota', '$tipo', '$cuent_p', '$cuent_i', '$cuent_d', '$ip','$nrocuota', 1, '$tipoprestamo', '$pos310', 1, 1)";
				else 
				{
					if ($r360['int_dif'] == 0)
						$sql_amor="insert into sgcaamorvol (fecha, codsoc, nropre, cedula, nombre, saldo, capital, interes, cuota, codpre, cuent_p, cuent_i, cuent_d, ip, nrocuota, proceso, tipo, pos310, semanal, diferido) values ('$fechadescuento', '$codigo', '$elnumero', '$micedula', '$nombre', '$saldo', '".($capital-$interes)."', '$interes', '$la_cuota', '$tipo', '$cuent_p', '$cuent_i', '$cuent_d', '$ip','$nrocuota', 1, '$tipoprestamo', '$pos310', 1, 0)";
					if ($r360['int_dif'] == 1)
						$sql_amor="insert into sgcaamorvol (fecha, codsoc, nropre, cedula, nombre, saldo, capital, interes, cuota, codpre, cuent_p, cuent_i, cuent_d, ip, nrocuota, proceso, tipo, pos310, semanal, diferido) values ('$fechadescuento', '$codigo', '$elnumero', '$micedula', '$nombre', '$saldo', '".($capital)."', '$interes', '$la_cuota', '$tipo', '$cuent_p', '$cuent_i', '$cuent_d', '$ip','$nrocuota', 1, '$tipoprestamo', '$pos310', 1, 1)";
				}
				
				$result2=mysql_query($sql_amor) or die($sql_amor);
//				echo $sql_amor.'<br>';
			}
//			echo $sql_pre.'----'.$posicion.'<br>';
		}	// ($r360 = mysql_fetch_assoc($a_360))
	} // ($r310 = mysql_fetch_assoc($a_310))
}


?>

<?php include("pie.php");?>

</body></html>

