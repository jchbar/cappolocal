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

<body>

<?php
include("arriba.php");
$menu6=2;include("menusizda.php");

if ($_SESSION['empresa'] == "nuevocat") {

	echo "Nuevocat es una Empresa de prueba, no se puede hacer copia de seguridad.";
	return;

}

if (!$_GET['n']) {

	echo "<a href = '?n=1'>Haga clic aqui para generar una copia de seguridad de la Base de Datos de <span class='b'>".$_SESSION['empresa']."</span></a>";

} else {

	include("backup.php");

}

include("pie.php");?></body></html>

