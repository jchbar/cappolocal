<?php
global $MYSQLI_CONN;
require_once 'mysql_compat.php';

//Copyright (C) 2000-2006  Antonio Grand�o Botella http://www.antoniograndio.com
//Copyright (C) 2000-2006  Inmaculada Echarri San Adri�n http://www.inmaecharri.com

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

require("final.php");
$MYSQLI_CONN = mysqli_connect($Servidor,$Usuario, $Password, '',3306) or die ("<p /><br /><p /><div style='text-align:center'>Disculpe... En estos momentos no hay conexi�n con el servidor, estamos realizando modificaciones.... int�ntalo m�s tarde. Gracias....</div>");
$link = $MYSQLI_CONN; 
/*
//***********************************************************

if ($_POST['crearbd']) {include ("crearemp.php");}

//***********************************************************

if ($_GET['emp'] == 1) {
	$_POST['usuario1'] = 'administrador';
	$_POST['empresa1'] = 'nuevocat';
	$_POST['password1'] = "admin";
}

*/
// echo 'usuario1'.$_POST['usuario1'];
if ($_POST['usuario1']) {
//	echo $bdd.$_POST['empresa1'];
	// mysqli_select_db($bdd.$_POST['empresa1'], $link);
	// if (mysql_select_db($bdd.$_POST['empresa1'], $link)) {
	if (mysql_select_db($bdd, $link)) {

/*
	echo $_POST['usuario1'];
	echo $_POST['empresa1'];
	echo $_POST['password1'];

*/
// 	echo "entre 1";
//		if (!mysql_query("SELECT * FROM grupplan")) {include("plangralconta.php");}
//		$fila = mysql_fetch_array(mysql_query("SELECT * FROM usuarios WHERE usuario = '".$_POST['usuario1']."' AND clave = '".$_POST['password1']."'"));
//		$fila = mysql_fetch_array(mysql_query("SELECT * FROM sgcapass WHERE apellido = '".$_POST['usuario1']."' AND nombre = '".$_POST['password1']."'"));
		$comando="SELECT * FROM sgcapass WHERE alias = '".$_POST['usuario1']."' AND password = PASSWORD('".$_POST['password1']."')";
		$fila = mysql_fetch_array(mysql_query($comando));
		if (!$fila) {
//			echo "no fila";
			session_unset();session_destroy();mysql_close($link);return;
		} else {
			$_SESSION['empresa']= $_POST['empresa1'];
			$_SESSION['usuario'] = $_POST['usuario1'];
			$_SESSION['auto'] = $fila['perm'];
			$sql='select con_nivel from sgcafniv'; //  order by con_nivel desc limit 1';
			$fila = (mysql_query($sql));
			$fila = mysql_num_rows($fila);
			$_SESSION['maxnivel']=$fila; // ['con_nivel'];
		}
	} else {
		session_unset();session_destroy();mysql_close($link);return;
	}
}

if ($_POST['accion'] == 'desc') {

	session_unset();
	mysql_close($link);
	return;

}

if (substr(strrchr($_SERVER['SCRIPT_NAME'], "/"), 1) == "empresa.php"){
	
	session_unset();
	return;
	
}

/*
$sql = "ALTER TABLE `definiti` ADD `bloq` INT( 1 ) NOT NULL";
mysql_query($sql);

// Updates for tasaBCV module
$sql = "ALTER TABLE `sgcaf360` ADD `enUSD` TINYINT( 1 ) NOT NULL DEFAULT '0'";
mysql_query($sql);

$sql = "CREATE TABLE IF NOT EXISTS `sgcatasa` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `fecha` date NOT NULL,
  `montobs` decimal(18,3) NOT NULL DEFAULT '0.000',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1";
mysql_query($sql);
*/

// if (!$_SESSION['empresa']) {return;}

// mysql_select_db($bdd.$_SESSION['empresa'], $link);
mysql_select_db($bdd, $link);
// echo $_SESSION['empresa'];
$bloq = mysql_fetch_array(mysql_query("SELECT bloq FROM definiti"));

if ($bloq[0] AND !$renum) {

	$bloqueo = "<p /><br /><div class='centro rojo b' />La base de datos est� bloqueada para tareas de mantenimiento. Intentarlo en unos segundos.</div>";

}
/*
$fila = mysql_fetch_array(mysql_query("SELECT ultasi FROM empresa"));
if (!$fila[0]) {
	$fila1 = mysql_fetch_row(mysql_query("SELECT max(asiento) FROM asientos"));
	mysql_query("UPDATE empresa SET ultasi = '$fila1[0]' WHERE 1");
}
*/

?>
