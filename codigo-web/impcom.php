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
<script language="javascript">
function abrirVentana(asiento)
{
window.open("impcompdf.php?asiento="+asiento,"parte1","top=0,left=395,status=no,toolbar=no,scrollbar=yes,location=no,type=fullWindow,fullscreen");
}
</script>

<body
<?php
if (!$bloqueo AND $asiento AND $accion AND ($accion == 'altaapu' OR $accion == 'editapu')) {echo " onload=\"foco('cuenta1')\"";}
else {echo " onload=\"foco('asiento')\"";}
?>
>

<?php
include("arriba.php");
$menu11=3;include("menusizda.php");
$accion=$_GET['accion'];
extract($_POST);
extract($_GET);
/*
echo 'accion '.$accion;
echo 'asiento '.$asiento;
*/
if ((!$asiento) and (!$accion)) {
	echo 'entre ';
	echo "<div id='div1'>";
	echo "<form method='post' name='form1' action='impcom.php?accion=Revisar'>\n";
	echo '<fieldset><legend>Indique Tipo de Impresion para el asiento '.$asiento.'</legend>';
	echo "Asiento: <input type='text' name='asiento'>\n";
	echo "<input type='submit' name = 'formu' value='Buscar Asiento'>\n";
	echo "<h2><input type='checkbox' name='formato' checked>Impresion en Hoja Blanca</h2>";
	echo "<h2><input type='checkbox' name='agrupar' checked>Agrupado por Cuentas </h2><br>";
	echo '</fieldset>';
	echo "</form>\n";
	echo "</div></body></html>";
	exit;
}


if (($asiento) and (!$accion)) {
	echo "<div id='div1'>";
	echo "<form action='impcom.php?accion=Listo' name='form1' method='post'>"; //  onsubmit='return realizo_abono(form1)'>";
	$asiento=$_GET['asiento'];
	$result = mysql_query("SELECT enc_clave, enc_explic FROM sgcaf830 WHERE enc_clave = '$asiento'");
	if (mysql_num_rows($result) == 0) {
		echo "<p />Asiento <span class='b'>$asiento</span> inexistente o Apunte Huérfano.</div></body></html>";
		exit;
	}
	echo '<fieldset><legend>Indique Tipo de Impresion para el asiento '.$asiento.'</legend>';
	echo "<input type='hidden' name='asiento' value='$asiento'>";
	echo "<h2><input type='checkbox' name='formato' checked>Impresion en Hoja Blanca</h2>";
	echo "<h2><input type='checkbox' name='agrupar' checked>Agrupado por Cuentas </h2><br>";
	echo '<input type="submit" name="Submit" value="Continuar">';
	echo '</legend>';
	echo '</fieldset>';
	echo '</form>';
	echo '</div>';	
//	$fila = mysql_fetch_array($result);
}
if (($asiento) and ($accion=='Revisar')) {
	echo "<div id='div1'>";
	echo "<form action='impcom.php?accion=Listo' name='form1' method='post'>"; //  onsubmit='return realizo_abono(form1)'>";
	$asiento=$_POST['asiento'];
	$result = mysql_query("SELECT enc_clave, enc_explic FROM sgcaf830 WHERE enc_clave = '$asiento'");
	if (mysql_num_rows($result) == 0) {
		echo "<p />Asiento <span class='b'>$asiento</span> inexistente o Apunte Huérfano.</div></body></html>";
		exit;
	}
	echo '<fieldset><legend>Indique Tipo de Impresion para el asiento '.$asiento.'</legend>';
	echo "<input type='hidden' name='asiento' value='$asiento'>";
	echo "<input type='hidden' name='formato' ".(isset($formato)?'1':'0').'>';
	echo "<input type='hidden' name='agrupar' ".(isset($agrupar)?'1':'0').'>';
	echo '<input type="submit" name="Submit" value="Continuar">';
	echo '</legend>';
	echo '</fieldset>';
	echo '</form>';
	echo '</div>';	
//	$fila = mysql_fetch_array($result);
}

if ((asiento) and ($accion=='Listo')) {
	echo "<div id='div1'>";
	extract($_POST);
	echo '<fieldset><legend>Imprimir el asiento '.$asiento.'</legend>';
	echo '<input type="submit" name="Submit" value="Impresión Asiento" onClick="abrirVentana(';
	echo "'";
	echo $asiento;
	echo "&hoja=";
	echo (isset($formato)?'1':'0');
	echo "&agrupar=";
	echo (isset($agrupar)?'1':'0');
	echo "'";
	echo ');">  ';
	echo '</legend>';
	echo '</fieldset>';
//	echo '</form>';
	echo '</div>';	
}
?>
