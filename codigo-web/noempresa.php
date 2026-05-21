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

	if (!$link OR !$_SESSION['empresa']) {
	echo "<body onload=\"foco('empresa1')\">";
	include("arriba.php");
	include("menusizda.php");
	echo "<p />No te has logueado <span class='b'>coloca los datos arriba indicados</span> y a trabajar";
/*
	echo "<p />No has elegido una <span class='b'>Empresa</span> con la que trabajar.
	Puedes elegir una de estas opciones:
	<br /><br />
	<ul>
	<li><a href='empresa.php'>Crear nueva <span class='b'>Empresa</span></a><p /></li>
	<li><a href='?emp=1'>Usar la de pruebas <span class='b'>Nuevocat</span></a><p /></li>
	<li>Rellenar los datos pedidos arriba con los de una Empresa que hayas creado anteriormente.</li>
	</ul>";
*/	include("pie.php");
	echo "</div></body></html>";
	exit;
}
?>