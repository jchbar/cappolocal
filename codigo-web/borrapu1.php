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
	return;
}

// mysql_query("DELETE FROM sgcaf820 WHERE nro_registro = $row_id") or die ("Estimado usuario $usuario se ha generado el error 820-2");

$b = $fecha; 
agregar_f820($asiento, $b, $elcargo, $cuenta1, $concepto, $debe, $haber, 0,$ip,0,$referencia,'','E',$row_id);
$result = mysql_query("SELECT * FROM sgcaf830 WHERE enc_clave = $asiento");

if (mysql_num_rows($result) == 0)
{
	mysql_query("DELETE FROM sgcaf830 WHERE enc_clave = $asiento");
// 	mysql_query("UPDATE factrec SET asiento = '' WHERE asiento = '$asiento'");
	echo "<p />Se borró el último apunte de Asiento $asiento, por tanto se ha borrado el asiento $asiento en tabla Asientos";
}

$row_id = 0;
//$nuevo = 0;

?>
