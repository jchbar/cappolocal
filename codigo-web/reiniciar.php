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

<body <?php if (!$cuenta AND !$bloqueo) {echo "onload=\"foco('cuenta')\"";}?>>

<?php
include("arriba.php");
$menu12=2;include("menusizda.php");

// if (!$enero) {
	$validado=' '; // checked="checked"';
	echo "<form enctype='multipart/form-data' action='reiniciar.php' method='post' name='form1'> \n";
	echo "<br>";
	echo '<fieldset><legend> Primer Trimestre </legend>';
	echo '<input name="enero" type="checkbox" tabindex="1" value="1" >'.$validado.'Enero';
	echo '<input name="febrero" type="checkbox" tabindex="2" value="1"'.$validado.'>Febrero';
	echo '<input name="marzo" type="checkbox" tabindex="3" value="1"'.$validado.'>Marzo';
	echo '</fieldset>';

	echo '<fieldset><legend> Segundo Trimestre </legend>';
	echo '<input name="abril" type="checkbox" tabindex="4" value="1"'.$validado.'>Abril';
	echo '<input name="mayo" type="checkbox" tabindex="5" value="1"'.$validado.'>Mayo';
	echo '<input name="junio" type="checkbox" tabindex="6" value="1"'.$validado.'>Junio';
	echo '</fieldset>';

	echo '<fieldset><legend> Tercer Trimestre </legend>';
	echo '<input name="julio" type="checkbox" tabindex="7" value="1"'.$validado.'>Julio';
	echo '<input name="agosto" type="checkbox" tabindex="8" value="1"'.$validado.'>Agosto';
	echo '<input name="septiembre" type="checkbox" tabindex="9" value="1"'.$validado.'>Septiembre';
	echo '</fieldset>';

	echo '<fieldset><legend> Cuarto Trimestre </legend>';
	echo '<input name="octubre" type="checkbox" tabindex="10" value="1"'.$validado.'>Octubre';
	echo '<input name="noviembre" type="checkbox" tabindex="11" value="1"'.$validado.'>Noviembre';
	echo '<input name="diciembre" type="checkbox" tabindex="12" value="1"'.$validado.'>Diciembre <br>';
	echo '</fieldset>';
	
	echo "<input type='submit' value='Procesar'></form> \n";
//	include("pie.php");
//	echo "</div></body></html>";
//	exit;

// }

$losniveles = mysql_query("SELECT * FROM sgcafniv order by con_nivel"); 
if (mysql_num_rows($losniveles) == 0) {
		die("<p /><br /><p />No se han definido los niveles<span class='b'> error Niv-1</span> en la tabla");
		exit;
}
chequear_procesar($enero,$losniveles,1);
chequear_procesar($febrero,$losniveles,2);
chequear_procesar($marzo,$losniveles,3);
chequear_procesar($abril,$losniveles,4);
chequear_procesar($mayo,$losniveles,5);
chequear_procesar($junio,$losniveles,6);
chequear_procesar($julio,$losniveles,7);
chequear_procesar($agosto,$losniveles,8);
chequear_procesar($septiembre,$losniveles,9);
chequear_procesar($octubre,$losniveles,10);
chequear_procesar($noviembre,$losniveles,11);
chequear_procesar($diciembre,$losniveles,12);

include("pie.php");?>
</body></html>
