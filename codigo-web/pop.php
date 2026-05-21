<?php
include("head.php");
$form = $_GET["formulario"];
$form2 = $_GET["nomcampo"];
$formato= $_GET["formato"];
$fechai= $_GET["fechai"];
$fechaf= $_GET["fechaf"];
$dom= $_GET["dom"];
$ano= $_GET["ano"];
echo '<form name="form2" method="post">';
echo 'Calendario'; 
echo '<input name="dateArriva3" type="text" id="dateArriva3" value ="'.$valor.'" size="35" readonly="readonly">
<input type="button" value="..." onClick="popUpCalendar (dateArriva3, form2.dateArriva3,\''.$formato.'\',\''.$fechai.'\',\''.$fechaf.'\',\''.$dom.'\',\''.$ano.'\');">';
?>

		<a href="JavaScript:close();" title="Pasar Valor" onClick="window.opener.document.form1.<?echo $_GET["formulario"]?>.value = window.document.form2.dateArriva3.value;" >Enviar
