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

session_start();

include("conex.php");

$asiento = $_GET['asiento'];

$qry = "SELECT enc_soporte FROM sgcaf830 WHERE enc_clave ='$asiento'";

$res = mysql_fetch_array(mysql_query($qry));

// $tipo = $res[0];
$contenido = $res[0];

// header ("Content-Disposition: inline;\n\n");
// header("Content-type: $tipo");
header("Content-Type: image/jpeg");

print $contenido;

?>