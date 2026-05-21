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

<body<?php if (!$bloqueo) {echo " onload=\"foco('subgrupo')\"";}?>>

<?php
include("arriba.php");
$menu12=1;include("menusizda.php");

if (!$subcuenta AND !$cuenta AND !$subgrupo AND !$cue) {

	echo "<span class='azul b'>Dar de alta un subgrupo, cuenta o subcuenta directamente <span class='rojo'>(evitar usar comillas en las descripciones)</span>:</span>\n";
	echo "<form method='post' name='form1'>\n";
	echo "<p class='centro'><label>Nº Subgrupo:</label> <input type='text' name='subgrupo' size='2' maxlength='2'> (Dos dígitos y menor de 99)<br />\n";
	echo "<label>Descripción:</label> <input type='text' name='descripci1_' size='71'><br /> \n";
	echo "<p class='centro'><label>Nº Cuenta:</label> <input type='text' name='cuenta' size='3' maxlength='3'> (Tres dígitos y menor de 999)<br />\n";
	echo "<label>Descripción:</label> <input type='text' name='descripci2_' size='71'><br /> \n";
	echo "<p class='centro'><label>Nº Subcuenta:</label> <input type='text' name='subcuenta' size='8' maxlength='8'> (Ocho dígitos y menor de 99999999)<br />\n";
	echo "<label>Descripción:</label> <input type='text' name='descripci3_' size='71'><br /> \n";
	echo "<label>Naturaleza</label> <select name='natura'><option value='Activo'>Activo</option><option value='Pasivo'>Pasivo</option><option value='Neto'>Neto</option><option value='Ingreso'>Ingreso</option><option value='Gasto'>Gasto</option></select><br />";
	echo "<input type='hidden' name='bdatos' value=".$bdatos."><input type='hidden' name='tabla' value=".$tabla.">\n";
	echo "";
	echo "<label>Cuenta Corriente:</label> <input type='text' name='ctacte' size='23' maxlength=23><br /><label>Teléfono1:</label> <input type='text' name='telefono' size='20' maxlength='20'> &nbsp; <label>Teléfono2:</label> <input type='text' name='telefono2' size='20' maxlength='20'><br />\n";
	echo "<p class='centro'><input type='submit' VALUE='Alta'><br /></form>\n";

	echo "<hr><span class='azul b'>O elegir cuentas usadas en otras empresas:</span>\n";
	include ("insertcta1.php");
	echo "</div></body></html>";
	exit;

}

if ($cue) {

	$temp = strpos($cue, ' - ');
	if ($temp == 2) {
		$subgrupo = substr($cue,0,2);
		$descripci1_ = substr($cue,5);
	}
	if ($temp == 3) {
		$cuenta = substr($cue,0,3);
		$descripci2_ = substr($cue,6);
	}
	if ($temp == 6) {
		$subcuenta = substr($cue,0,6);
		$descripci3_ = substr($cue,9);
	}

}


$temp = 1;

if ($subgrupo AND $subgrupo <= 9)
{
	echo "<p />Subgrupo no introducido. El Subgrupo debe de tener 2 dígitos.<br />No se puede añadir este Subgrupo.<p />\n";
	$temp = 2;
}

if ($subgrupo AND $subgrupo >= 99)
{
	echo "<p />Subgrupo no introducido. El Subgrupo debe de tener 2 dígitos y debe de ser menor de 99.<br />No se puede añadir este Subgrupo.<p />\n";
	$temp = 2;

}

if ($cuenta AND $cuenta <= 99)
{
	echo "<p />Cuenta no introducida. La Cuenta debe de tener 3 dígitos.<br />No se puede añadir esta Cuenta.<p />\n";
	$temp = 2;

}

if ($cuenta AND $cuenta >= 999)
{
	echo "<p />Cuenta no introducida. La Cuenta debe de tener 3 dígitos y debe de ser menor de 999.<br />No se puede añadir esta Cuenta.<p />\n";
	$temp = 2;

}


if ($subcuenta AND $subcuenta <= 9999999)
{
	echo "<p />Subcuenta no introducida. La Subcuenta debe de tener 8 dígitos.<br />No se puede añadir esta Subcuenta. El Plan General de Contabilidad Español de 2007 no contempla más que 9 grupos de Subcuentas.<p />\n";
	$temp = 2;

}

if ($subcuenta AND $subcuenta >= 99999999) {

	echo "<p />Subcuenta no introducida. La Subcuenta debe de tener 8 dígitos y debe de ser menor de 99999999.<br />No se puede añadir esta Subcuenta. El Plan General de Contabilidad Español de 2007 no contempla más que 9 grupos de Subcuentas.<p />\n";
	$temp = 2;

}

if ($subgrupo AND $temp == 1) {

	$sql = "INSERT INTO subgrupo (subgrupo,descripci_) VALUES (\"".$subgrupo."\",\"".$descripci1_."\")";
	$rs = mysql_query($sql) or die ("El usuario $usuario no tiene permisos para añadir Subgrupos o Subgrupo ya existe.</div></body></html>");
	printf("<p /><span class='b'> Se ha dado de alta el Subgrupo ".$subgrupo."  -  ".$descripci1_."</span> </p>");

}

if ($cuenta AND $temp == 1) {

	$temp = substr($cuenta,0,2);
	$tempo = mysql_num_rows(mysql_query("SELECT * from subgrupo WHERE subgrupo = $temp"));
	if ($tempo == 1) {
		$sql = "INSERT INTO cuentas (cuenta,descripcio) VALUES (\"".$cuenta."\",\"".$descripci2_."\")";
		$rs = mysql_query($sql) or die ("El usuario $usuario no tiene permisos para añadir Cuentas o Cuenta ya existe.</div></body></html>");
		printf("<p /><span class='b'> Se ha dado de alta la cuenta ".$cuenta."  -  ".$descripci2_."</span> <p />");
	} else {
		printf("<p /><span class='b'> No se ha dado de alta la cuenta ".$cuenta."  -  ".$descripci2_." porque no existe el subgrupo ".$temp."</span> <p />");
	}
}

if ($subcuenta AND $temp == 1) {

	$temp = substr($subcuenta,0,3);
	$tempo = mysql_num_rows(mysql_query("SELECT * from cuentas WHERE cuenta = $temp"));
	if ($tempo == 1) {
		$sql = "INSERT INTO subcuent (cuenta,descripci_, natura, ctacte,telefono, telefono2) VALUES (\"".$subcuenta."\",\"".$descripci3_."\",\"".$natura."\",\"".$ctacte."\",\"".$telefono."\",\"".$telefono2."\")";
		$rs = mysql_query($sql) or die ("El usuario $usuario no tiene permisos para añadir Subcuentas o Subcuenta ya existe.</div></body></html>");
		printf("<p /><span class='b'> Se ha dado de alta la Subcuenta ".$subcuenta." - ".$descripci3_."</span> <p />");
	} else {
		printf("<p /><span class='b'> No se ha dado de alta la Subcuenta ".$subcuenta."  -  ".$descripci3_." porque no existe la cuenta ".$temp."</span> <p />");
	}

}

//***************************************************************************************************

include("pie.php");?></body></html>
