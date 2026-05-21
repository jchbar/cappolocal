<?php
/*  
  
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
	
include("head.php");

if (!$link OR !$_SESSION['empresa']) {
	include("noempresa.php");
	exit;
}
	*/

// $link = @mysql_connect("localhost","root", "",'',65536) or die ("<p /><br /><p /><div style='text-align:center'>En estos momentos no hay conexión con el servidor, inténtalo más tarde.</div>");
// mysql_select_db($_POST['sica'], $link);

session_start();

// include("fpdf/a_cookies.php");

extract($_GET);
extract($_POST);
extract($_SESSION);

include("conex.php");
if (!$link OR !$_SESSION['empresa']) {
	include("head.php");
	header("location: noempresa.php");
	exit;
}

//phpinfo();
//echo $_SERVER["HTTP_REFERER"];
//$archivoabuscar='http://cappobck/cajaweb/devoluciones/'.$_POST['archivo'];
$archivoabuscar='devoluciones/'.$_POST['archivo'];
$contenido = ''; // Contenido del archivo
//$archivoabierto=fopen("'".$archivoabuscar."'", "r");
$archivoabierto=fopen($archivoabuscar, "r");
//echo $archivoabuscar.' --id archivo-- '.$archivoabierto;
header( "Content-Type: application/octet-stream");
header( "Content-Disposition: attachment; filename=".$_POST['archivo'].""); 
$lines = file($archivoabuscar);
foreach ($lines as $line_num => $linea) {
	$datos = explode("|", $linea);
	$contenido.=$datos[0]
	;
}
print($contenido);
fclose($archivoabierto);
?> 
