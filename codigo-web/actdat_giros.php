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
include("paginar.php");

if (!$link OR !$_SESSION['empresa']) {
	include("noempresa.php");
	exit;
}
?>

<body>

<?php
include("arriba.php");
$menu14=1;include("menusizda.php");
if (!isset($_GET['Valor']))
{
	echo '<table align="center" class="basica 100 hover" border="0">';
	echo "<form enctype='multipart/form-data' action='actdat_giros.php?Valor=Grabar' name='form1' id='form1' method='post' ";
	echo '<fieldset><legend>Actualizar Datos de Socio para Giros</legend>';
	$cedbus=$_SESSION['cedula'];
	$sqls="select * from sgcaf200 where ced_prof='$cedbus'";
	$ress=mysql_query($sqls);
	$rsoc=mysql_fetch_assoc($ress);
	echo '<tr><td>Socio </td><td>'.$cedbus.' '.$rsoc['ape_prof']. ' '.$rsoc['nombr_prof'].'</td></tr>';
	echo '<tr><td>Direccion </td><td>';
	echo '<input name="dirn1_prof" type="text" id="dirn1_prof" value ="'.$rsoc['dirn1_prof'].'" maxlength="30" size="30"><br>';
	echo '<input name="dirn2_prof" type="text" id="dirn2_prof" value ="'.$rsoc['dirn2_prof'].'" maxlength="30" size="30"></td></tr>';
	echo '<tr><td>Telefono Casa</td><td>';
	echo '<input name="teln_prof" type="text" id="teln_prof" value ="'.$rsoc['teln_prof'].'" maxlength="12"></td></tr>';
	echo '<tr><td>Telefono Celular #1</td><td>';
	echo '<input name="celn_prof" type="text" id="celn_prof" value ="'.$rsoc['celn_prof'].'" maxlength="12"></td></tr>';
	echo '<tr><td>Telefono Celular #2</td><td>';
	echo '<input name="cel2n_prof" type="text" id="cel2n_prof" value ="'.$rsoc['cel2n_prof'].'" maxlength="12"></td></tr>';
	echo '<tr><td>Telefono Oficina</td><td>';
	echo '<input name="ofin_prof" type="text" id="ofin_prof" value ="'.$rsoc['ofin_prof'].'" maxlength="12"></td></tr>';
	echo '<tr><td colspan="2" align="center"><input type = "submit" value = "Grabar Datos"></td></tr>'; 
	echo '</fieldset>';
	echo '</form>';
}
else 
{
	if ($_GET['Valor']=='Grabar') {
		$sqls="update sgcaf200 set dirn1_prof='".$_POST['dirn1_prof']."', dirn2_prof='".$_POST['dirn2_prof']."', teln_prof='".$_POST['teln_prof']."', celn_prof='".$_POST['celn_prof']."', cel2n_prof='".$_POST['cel2n_prof']."', ofin_prof='".$_POST['ofin_prof']."' where ced_prof='".$_SESSION['cedula']."'";
		$ress=mysql_query($sqls);
		echo '<h1>Datos Actualizados</h1>';
		echo "<form enctype='multipart/form-data' action='actdat_giros.php?Valor=Cerrar' name='form1' id='form1' method='post' ";
		echo '<tr><td colspan="2" align="center"><input type = "submit" value = "Cerrar Ventana"></td></tr>'; 
		echo '</form>';
	}
	else {
		echo "<script languaje='javascript' type='text/javascript'>window.close();</script>";
	}
}
include("pie.php");?></body></html>
