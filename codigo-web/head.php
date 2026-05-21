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

include("a_cookies.php");

extract($_GET);
extract($_POST);
extract($_SESSION);

include("conex.php");
include("funciones.php");
/*
include ("calendario/calendario.php");
include ("popcalendario/escribe_formulario.php");  		// cambiando fechas
*/
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es-ES" lang="es">

<head>
<title>CAja de Ahorro y Prestamo</title>
<link rel="shortcut icon" href="animated_favicon1.gif" >
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<meta http-equiv="pragma" content="no-cache" />

<link rel="stylesheet" type="text/css" href="estilo0.css" media="screen" />
<link rel="stylesheet" type="text/css" href="estilo<?php if (!$c) {$c = 1;} echo $c;?>.css" media="screen" />
<!-- <link rel="stylesheet" type="text/css" href="DatePicker.css" media="screen" /> // cambiando fechas -->
<link rel="stylesheet" type="text/css" href="estiloimpr.css" media="print" />
<script language="Javascript" src="javascript.js" type='text/javascript'></script>
<!-- 
// cambiando fechas 
<script language="javascript" src="Acrobat/calendario555.js"></script>
<script language="JavaScript" src="popcalendario/popcalendar.js"></script>
<script language="JavaScript" src="calendario/calender.js"></script>  -->
<script type="text/javascript" src="jquery-1.2.1.pack.js"></script>
<!-- 
// cambiando fechas 
<script language="JavaScript" src="calendarioventana.js"></script>

<!-- <script type="text/javascript" src="calendar.js"></script>
<script type="text/javascript" src="lang/calendar-es.js"></script> -->
<!-- <link rel="stylesheet" type="text/css" href="calender.css" media="print" /> // cambiando fehas -->
<link rel="stylesheet" type="text/css" media="all" href="calendar-green.css" title="green" />

<!-- <link rel="stylesheet" type="text/css" media="all" href="calendar-blue.css" title="green" /> -->
<!-- main calendar program -->
<script type="text/javascript" src="calendar.js"></script>
<!-- language for the calendar -->
<script type="text/javascript" src="lang/calendar-es.js"></script>
<!-- the following script defines the Calendar.setup helper function, which makes
      adding a calendar a matter of 1 or 2 lines of code. -->
<script type="text/javascript" src="calendar-setup.js"></script>

<?php
/*
<link href="estilda.css" rel="stylesheet" type="text/css" media="screen" />
<script type="text/javascript" src="acc_calendar/acc_calendar.js"></script>
*/ ?>

<?php
/* 
<script language="Javascript" src="DatePicker.js" type='text/javascript'></script>
<script language="Javascript" src="codigo.js" type='text/javascript'></script>

<link rel=stylesheet type="text/css" href="s.css">
<script language="javascript1.2" src="codethatcalendarstd.js"></script>
<script language="javascript1.2" src="scroller_ex.js"></script></head>

*/
?>
</head>
