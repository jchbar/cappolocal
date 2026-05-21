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

if ($_POST['moneda']) {
	$b = explode("|",$_POST['moneda']);
	$_SESSION['moneda'] = $b[0];
	$_SESSION['deci'] = $b[1];
	$_SESSION['sep_decimal'] = $b[2];
	$_SESSION['sep_miles'] = $b[3];
}
if (!$_SESSION['moneda']) {
	$_SESSION['moneda'] = 1;
	$_SESSION['deci'] = 2;
	$_SESSION['sep_decimal']='.';
	$_SESSION['sep_miles']=',';
}

include("win.php");

?>

<a name='inicio'></a>

<div id='arriba' class='transp'>

<a href='index.php' style='float:left;padding:0 3em 0 1em'><img src='socioweb2.PNG' alt='' /></a>

<div style='float:left;display:inline;padding-left:10px;padding-top:3px'>

<?php
// echo "la sesion".$_SESSION['empresa'];
if (!$_SESSION['empresa']) {
//	echo "entre 1";

	echo "<form name='form0' method='post' action='index.php' class='inline'>";
//	echo "Empresa <input type='text' name='empresa1' size='20' maxlength='20' value='sica'/> Usuario <input type='text' name='usuario1' size='20' maxlength='20' value='administrador' /> Clave <input type='password' name='password1' size='15' maxlength='15' value='maestra' /> <input type='submit' value='Conectar' />\n";
//	echo "Empresa <input type='text' name='empresa1' size='20' maxlength='20' value='a1931075_contabl'/> Usuario <input type='text' name='usuario1' size='20' maxlength='20' value='a1931075_jchbar' /> Clave <input type='password' name='password1' size='15' maxlength='15' value='abc12345' /> <input type='submit' value='Conectar' />\n";
//	echo "Empresa <input type='text' name='empresa1' size='20' maxlength='20' value='heroscom_sica'/> Usuario <input type='text' name='usuario1' size='20' maxlength='20' value='administrador' /> Clave <input type='password' name='password1' size='15' maxlength='15' value='abc12345' /> <input type='submit' value='Conectar' />\n";
// remoto
//	echo "<input type='hidden' name='empresa1' size='20' maxlength='20' value='sica'/>";
//	echo "Usuario <input type='text' name='usuario1' size='20' maxlength='20' value='' /> Clave <input type='password' name='password1' size='15' maxlength='15' value='' /> <input type='submit' value='Conectar' />\n";
//local
	echo "<input type='hidden' name='empresa1' size='20' maxlength='20' value='sica_aparte'/>";
	echo "Usuario <input type='text' name='usuario1' size='20' maxlength='20' value='administrador' /> Clave <input type='password' name='password1' size='15' maxlength='15' value='vima0905' /> <input type='submit' value='Conectar' />\n";
	echo "<br /><span style='font-size:.8em'>Para Crear una Empresa llenar el formulario en la seccion <strong>Contactenos</strong> en <a href='http://www.heros.com.ve' class='b'> www.heros.com.ve </a>";
	echo "</span></form>";
/*
	echo "<br /><span style='font-size:.8em'>Puedes <a href='empresa.php' class='b'>Crear una Empresa</a>";
	echo " o usar la de pruebas <a href='";
	if (substr(strrchr($_SERVER['SCRIPT_NAME'], "/"), 1) == "empresa.php") {echo "index.php";}
	echo "?emp=1' class='b'>Nuevocat</a>.";
	echo "</span></form>";
*/
} else {
//	echo "entre 2";

	if ($_SESSION['usuario']) {
//	echo "entre 3";
		echo "<form name='form0' method='post' action='index.php' style='display:inline'>";
		echo "Empresa <input type='text' name='empresa1' size='15' maxlength='12' readonly='readonly' value='".$_SESSION['empresa']."' /> Usuario <input type='text' name='usuario1' size='15' maxlength='15'  readonly='readonly' value='".$_SESSION['usuario']."' /> <input type='submit' value='Desconectar' /></form>";
//		echo "&nbsp;&nbsp;<a href='equiv.php'>Ver en</a> ";
//		include("moneda.php");
//		echo "&nbsp;<a href='javascript:void(0)' onclick=\"show('calc');calcula('',calcu,event)\">Calculadora</a><br /><span style='font-size:.8em'>(Recuerda pulsar en <span class='b'>DESCONECTAR</span> al finalizar.)</span>\n";
	}
}

?>

</div>

<?php
?>

<div style='float:right;display:inline;padding-left:20px;padding-top:3px;font-size:0.8em'>
<?php
//<div style='position:absolute;bottom:0px;right:20px;font-size:0.8em'>
//<div style="text-align: center;"><a href="http://www.danasoft.com/">

// </a></div>
// <div style="font-family: arial,sans-serif; font-size: 11px;"><p>

//<a href="http://www.dealighted.com/"></a></p></div>

// echo "<a href='empresa.php'>Crear Empresa</a> | <a href='index.php?x=1'>Acerca de CatWin Net</a> | ";
echo "<a href='index.php?x=1'>Acerca de CAP.1.0</a> | ";
echo date("d-m-Y H:i", time())." CAP v. 1.0 | ";include("colores.php");

?>
</div>
</div>

<div id="linea"></div>

<?php

// winop("calc.php", "", "85px", "1px", "25%", "", "1", "calc","", 1,'calc');

include ("log.php");?>
