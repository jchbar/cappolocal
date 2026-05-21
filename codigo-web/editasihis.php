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
?>

<body
<?php
if (!$bloqueo AND $asiento AND $accion AND ($accion == 'altaapu' OR $accion == 'editapu')) {echo " onload=\"foco('cuenta1')\"";}
else {echo " onload=\"foco('asiento')\"";}
?>
>

<?php
include("arriba.php");
$menu11=3;include("menusizda.php");

if (!$asiento) {

	echo "<form method='post' name='form1'>\n";
	echo "Asiento: <input type='text' name='asiento'>\n";
	echo "<input type='submit' name = 'formu' value='Buscar Asiento'>\n";
	echo "</form>\n";
	echo "</div></body></html>";
	exit;

}

$nomatach = $_FILES['fich']['name'];
if ($nomatach AND $asiento) {
	$tipo1    = $_FILES["fich"]["type"];
	$archivo1 = $_FILES["fich"]["tmp_name"];
	$tamanio1 = $_FILES["fich"]["size"];
	$fp = fopen($archivo1, "rb");
    $contenido = fread($fp, $tamanio1);
    $contenido = addslashes($contenido);
    fclose($fp);
//	mysql_query("UPDATE asientos SET fich = '$contenido', tipofich = '$tipo1' WHERE asiento = '$asiento'");
}

if ($explicacion) {
	mysql_query("UPDATE sgcaf830 SET enc_explic = \"$explicacion\" WHERE enc_clave = '$asiento'");
//	mysql_query("UPDATE asientos SET explicacion = \"$explicacion\" WHERE asiento = '$asiento'");
}

/*
if ($accion == "altaapu1" AND ($elmonto >=0)) { // ($debe != 0 OR $haber != 0)) {
	include ("altaapu1.php");
 }

if ($accion == "editapu1" && ($elmonto >=0)) { // ($debe != 0 OR $haber != 0)) {
 	include ("editapu1.php");
}

if ($accion == "boapu") {
 	include ("borrapu1.php");
}

if ($accion == "boasi") {
 	$sql = "DELETE FROM sgcaf820 WHERE com_nrocom = '$asiento'";
	if (!mysql_query($sql)) die ("El usuario $usuario no tiene permiso para borrar Asientos.");
	$sql = "DELETE FROM sgcaf830 WHERE enc_clave = '$asiento'";
	if (!mysql_query($sql)) die ("El usuario $usuario no tiene permiso para borrar Asientos.");
//	mysql_query("UPDATE factrec SET asiento = '' WHERE asiento = '$asiento'");
	echo "Asiento<span class='b'> ".$asiento." </span>borrado.\n";

	$cuento='borrado de asiento '.$asiento;
	$ip = $_SERVER['HTTP_CLIENT_IP'];
	if (!$ip) {$ip = $_SERVER['REMOTE_ADDR'];}
	$usuario=$_SERVER['REMOTE_ADDR'];
	$sql_bita="insert into sgcabita (cuento, ip, quien) values ('$cuento', '$ip', '$usuario')";
	$a_bita=mysql_query($sql_bita);

	echo "</div></body></html>";exit;
}
*/

if ($asiento) {
	$result = mysql_query("SELECT enc_clave, enc_explic FROM histf830 WHERE enc_clave = '$asiento'");
	if (mysql_num_rows($result) == 0) {
		echo "<p />Asiento <span class='b'>$asiento</span> inexistente o Apunte Huérfano.</div></body></html>";
		exit;
	}
	$fila = mysql_fetch_array($result);
}

if (($_SERVER['REMOTE_ADDR']=='192.168.1.9') or ($_SERVER['REMOTE_ADDR']=='192.168.1.100') or ($_ENV['COMPUTERNAME']=='JCHB-DM1')) // solo estas maquinas borran
// echo "<a href='editasi2.php?asiento=$asiento&accion=boasi' onclick='return borrar_asiento()'>Borrar Asiento</a>";

// echo "&nbsp;&nbsp;&nbsp; - &nbsp;&nbsp;&nbsp;<a href='editasi2.php?asiento=$asiento&accion=altaapu'>Añadir Registro</a><p />";

/*
echo "<form enctype='multipart/form-data' name='justificante' action='editasi2.php?asiento=$asiento' method='post'>";
echo "<label>Soporte</label> <input type='file' name='fich' size='19' maxlength='19'>";
echo " (Si el asiento ya tiene un justificante será sustituído)";
echo "<br /><label>Explicación</label> <textarea name='explicacion' rows='6' cols='90'>$fila[1]</textarea>";
echo " <input type='submit' name='boton' value=\" >> \">";
echo "</form>";
*/

echo "<table class='basica 100 hover' width='850'>";
// width='100%'
cabasi_his(2);
// totalapu_his($asiento);
asiento_his($asiento,"1",$_SESSION['moneda'],$_SESSION['deci'],$_GET['bojust']);


echo "</table><p />";

/*
if ($accion == 'editapu') {
	include ("editapu.php");
}

if ($accion == 'altaapu') {
	include ("altaapu.php");
}
*/
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
// echo 'pantalla_asiento '.$fechax;
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
<input type = 'text' size='11' maxlength='11' name='elmonto' value='<?php echo $elmonto;?>' tabindex='8'>
<input type = "hidden" name="fecha" value="<?php echo $fechax;?>">
</td>
</tr>
<tr>
<?php
}
?>
