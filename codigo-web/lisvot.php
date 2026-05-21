<?php
include("head.php");
//include("popcalendario/escribe_formulario.php");
?>
<script language="javascript">
function abrirVentana(fechadescuento)
{
// window.open("06_Inventario_actuallist.asp","prueba1", "width=385,height=180,top=0,left=0',status,toolbar =1,scrollbars,location");
// window.open("leftmenu.htm","prueba2","width=385,he ight=180,top=0,left=395,status,toolbar=1,scrollbar s,location");
window.open("lisvotpdf1.php?fechadescuento="+fechadescuento,"parte1","top=0,left=395,status=no,toolbar=no,scrollbar=yes,location=no,type=fullWindow,fullscreen");	
window.open("lisvotpdf2.php?fechadescuento="+fechadescuento,"parte2","top=0,left=395,status=no,toolbar=no,scrollbar=yes,location=no,type=fullWindow,fullscreen");	
window.open("lisvotpdf3.php?fechadescuento="+fechadescuento,"parte3","top=0,left=395,status=no,toolbar=no,scrollbar=yes,location=no,type=fullWindow,fullscreen");	
// los primeros 500 socios	width=385,height=180,
/*
window.open("cuobanpdf2.php?fechadescuento="+fechadescuento,"parte2","top=0,left=395,status=no,toolbar=no,scrollbar=yes,location=no,type=fullWindow,fullscreen");
// "width=385,height=180,top=0,left=395,status,toolbar=1,scrollbar s,location");	// los demas
window.open("cuobanpdf3.php?fechadescuento="+fechadescuento,"resumen","top=0,left=395,status=no,toolbar=no,scrollbar=yes,location=no,type=fullWindow,fullscreen");
//,"width=385,height=180,top=0,left=395,status,toolbar=1,scrollbar s,location");	// resumen de los montos
window.open("cuobanpdf4.php?fechadescuento="+fechadescuento,"banco","top=0,left=395,status=no,toolbar=no,scrollbar=yes,location=no,type=fullWindow,fullscreen");
// "width=385,height=180,top=0,left=395,status,toolbar=1,scrollbar s,location");	// el listado a banco
window.open("cuobanpdf5.php?fechadescuento="+fechadescuento,"amortiza","top=0,left=395,status=no,toolbar=no,scrollbar=yes,location=no,type=fullWindow,fullscreen");
// "width=385,height=180,top=0,left=395,status,toolbar=1,scrollbar s,location");	// amortizacion / capital
//window.open("cuobanpdf6.php?fechadescuento="+fechadescuento,"descargar","top=0,left=395,status=no,toolbar=no,scrollbar=yes,location=no,type=fullWindow,fullscreen");
// "width=385,height=180,top=0,left=395,status,toolbar=1,scrollbar s,location");	// amortizacion / capital
*/
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
	echo "<form action='lisvot.php?accion=Listado' name='form1' method='post'>";
	echo '<fieldset><legend>Información Para Listado de Votaciones</legend>';
	echo 'Reporte de Votaciones al: ';
	$fechadelabono=date("d")."/".date('m')."/".date("Y"); 
	echo $fechadelabono;
	$hoy = date("d")."/".date('m')."/".date("Y"); 
	echo '<input type="hidden" name="fechadelpago" value="'.$hoy.'"/><br>';
	echo 'Listar Socios';
	echo '<input type="checkbox" id="activos" name="activos">Activos';
	echo '<input type="checkbox" id="jubilados" name="jubilados">Jubilados<br>';
	
	echo '<input type="submit" name="Submit" value="Obtener Reporte a la Fecha" />';
	echo '</legend>';
	echo '</form>';
	echo '</div>';
}	// !$accion
if ($accion=='Listado') {
//	$fechadescuento=convertir_fecha($_POST['fechadelpago']);		// revisar que no hayan nominas con esa fecha
	extract($_POST);
	if ((! isset($activos)) and (! isset($jubilados)))
		echo '<h1>Debe seleccionar algún item para generar listado</h1>';
	else {
		echo "<div id='div1'>";
		echo "<form action='lisvot.php?accion=Listo' name='form1' method='post'>"; //  onsubmit='return realizo_abono(form1)'>";
		$fechadescuento=$_POST['fechadelpago'];
		echo '<fieldset><legend>Recopilando información Para Listado de Votaciones al '.$fechadescuento.'</legend>';
		echo '<h2>Preparando información...</h2>';
		echo '<input type="submit" name="Submit" value="Generar Listados" onClick="abrirVentana(';
		echo "'";
		echo $fechadescuento;
		echo '&activos='.(isset($activos)?'1':'0');
		echo '&jubilados='.(isset($jubilados)?'1':'0');

		echo "'";
		echo ');">  ';
		echo '</legend>';
		echo '</form>';
		echo '</div>';	
	}
}	// ($accion=='Listado')
if (($accion=='Listo')) { // and ($nominasnormales == 'on')) {
// if ($nominasnormales == 'on') {
	$fechadescuento=$_POST['fechadescuento'];
	$nombre_archivo=$_POST['nombre_archivo'];
//	echo '<input type="hidden" name="nombre_archivo" value = "'.$nombre_archivo.'"/>';
	echo "<div id='div1'>";
	
	echo '<h2>Listado Generado...</h2>';
	echo '</div>';
}	// ($accion=='Listo') 

?>

<?php include("pie.php");?>

</body></html>

