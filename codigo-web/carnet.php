<?php
include("head.php");
//include("popcalendario/escribe_formulario.php");
?>
<script src="ajxmut.js" type="text/javascript"></script>
<script language="Javascript" src="selec_fecha.js" type='text/javascript'></script>
<script language="javascript">
function abrir2Ventanas(cedula, decanato)
{
// window.open("06_Inventario_actuallist.asp","prueba1", "width=385,height=180,top=0,left=0',status,toolbar =1,scrollbars,location");
// window.open("leftmenu.htm","prueba2","width=385,he ight=180,top=0,left=395,status,toolbar=1,scrollbar s,location");
window.open("carnetpdf.php?cedula="+cedula+"&decanato="+decanato,"parte1","top=0,left=395,status=no,toolbar=no,scrollbar=yes,location=no,type=fullWindow,fullscreen");	// los primeros 500 socios	width=385,height=180,
}
</script>
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
	echo "<form action='carnet.php?accion=EmitirCarnet' name='form1' method='post'>";
	echo '<fieldset><legend>Información Para Generar Carnet </legend>';

	echo '<br>Cedula del Socio: ';
?>
	<input type="text" size="20" tabindex='5' name='inputString' id="inputString" onKeyUp="lookup_socios(this.value);" onBlur="fill_socios();" value ="" autocomplete="off"/>
	<div class="suggestionsBox" id="suggestions" style="display: none;">
	<img src="upArrow.png" style="position: relative; top: -12px; left: 70px; " alt="upArrow" />
	<div class="suggestionList" id="autoSuggestionsList">
	</div>
	</div>

<?php
//	echo '<br>Mutuo Auxilio<input type="checkbox" name="nominasnormales" value = "on" checked align="right"/><br />';
	echo 'Decanato: ';	
	echo "<input type = 'text' size='20' maxlength='20' name='decanato' value='' tabindex='8'><br>";


	echo '<input type="submit" name="Submit" value="Imprimir Carnet" />';
	echo '<input type="submit" name="Submit" value="Cargar Foto" />';
	echo '</legend>';
	echo '</div>';
}	// !$accion
if (($_POST['Submit']=='Imprimir Carnet')) { // and ($nominasnormales == 'on')) {
	$fechadescuento=convertir_fecha($_POST['date3']);		// revisar que no hayan nominas con esa fecha

	$cedula=$_POST['inputString'];

	$otrosql="select concat(trim(ape_prof), ' ', trim(nombr_prof)) as nombre from sgcaf200 where ced_prof='$cedula'";
	$result=mysql_query($otrosql);
	$filas=mysql_fetch_assoc($result);

	echo '<input type="submit" name="Submit" value="Imprimir Carnet" onClick="abrir2Ventanas(';
	echo "'";
	echo $cedula;
	echo "', '";
	echo $_POST['decanato'];
	echo "'";
	echo ');">  ';
	echo '</legend>';
	echo '</form>';
	echo '</div>';	
}	// ($accion=='EmitirCarnet')
///
if (($_POST['Submit']=='Cargar Foto')) { // and ($nominasnormales == 'on')) {
	echo '<form action="carnet.php" method="post" name="form1" enctype="multipart/form-data">';
	$cedula=$_POST['inputString'];
	echo '<input name="inputString" type="hidden" value="'.$cedula.'">';
	echo '<input name="archivo" type="file" value="Examinar">';
	echo '<input type="submit" name="Submit" value="Subir Foto"> ';
	echo '</form>';
}	// ($accion=='Cargar foto')
///
if (($_POST['Submit']=='Subir Foto')) { // and ($nominasnormales == 'on')) {
	echo '<div id="div1">';
	$copiado = 'SI';		// cambiar a no y resolver este problema
	if(@$_FILES['archivo']!=='') // {
		$nueva_ruta='/fotos2/';
		$ruta_total = $_SERVER['DOCUMENT_ROOT'].$nueva_ruta;
		$ruta_total = $_SERVER['DOCUMENT_ROOT']."/cajaweb/fotos2/".$_FILES['archivo']['name'];
		$BASENAMES = basename( $_FILES['archivo']['name']);
		$nuevo_nombre=$BASENAMES;
//		echo $HTTP_POST_FILES['archivo']['tmp_name'];
		
		
		if (is_uploaded_file($HTTP_POST_FILES['archivo']['tmp_name'])) {
		    copy($HTTP_POST_FILES['archivo']['tmp_name'], "/fotos");
			} else {
		    	echo "Possible file upload attack. Filename: " . $HTTP_POST_FILES['archivo']['name'];
			}
/*
		echo 'http '. $HTTP_POST_FILES['archivo']['tmp_name'];
		echo $ruta_total.'<br>'; 
*/
//		echo 'resultado '.move_uploaded_file($HTTP_POST_FILES['archivo']['tmp_name'], $ruta_total);
		if ((move_uploaded_file($HTTP_POST_FILES['archivo']['tmp_name'], $ruta_total)) > 0)
			echo 'Archivo cargado correctamente';
		else 
			echo 'Fallo la carga de Archivo';
		set_time_limit(30);
	}
///
	echo '</div>';

?>

<?php include("pie.php");?>

</body></html>

