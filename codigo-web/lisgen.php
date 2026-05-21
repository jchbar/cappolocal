<?php
include("head.php");
?>
<script language="javascript">
function abrirVentana(elorden)
{
window.open("lisgenpdf.php?orden="+elorden,"parte1","top=0,left=395,status=no,toolbar=no,scrollbar=yes,location=no,type=fullWindow,fullscreen");	
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
	echo "<form action='lisgen.php?accion=Listado' name='form1' method='post'>";
	echo '<fieldset><legend>Información Para Emision de Listado General Socios</legend>';
	echo 'Listado ordenado por ';
	$orden='Codigo';
	echo '<select name="elorden" size="1">';
	$sql="select nombre from sgcaf000 where tipo='OrdLisSoc' order by nombre";
	$resultado=mysql_query($sql);
	while ($fila2 = mysql_fetch_assoc($resultado)) {
		echo '<option value="'.$fila2['nombre'].'" '.(($orden==$fila2['nombre'])?'selected':'').'>'.$fila2['nombre'].'</option>';}
 	echo '</select> '; 

	echo '<input type="submit" name="Submit" value="Obtener Reporte" />';
	echo '</legend>';
	echo '</form>';
	echo '</div>';
}	// !$accion
if ($accion=='Listado') {
	echo "<div id='div1'>";
	echo "<form action='lisgen.php?accion=Listo' name='form1' method='post'>"; 
	echo '<fieldset><legend>Recopilando información Para Listado </legend>';
	echo '<h2>Preparando información...</h2>';
	echo '<input type="submit" name="Submit" value="Impresión de Listados" onClick="abrirVentana(';
	echo "'";
	echo $elorden;
	echo "'";
	echo ');">  ';
	echo '</legend>';
	echo '</form>';
	echo '</div>';	
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

