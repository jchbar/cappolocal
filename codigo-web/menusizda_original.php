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

if ($_SESSION['empresa']) {

//	$valores = mysql_fetch_array(mysql_query("SELECT menus FROM usuarios WHERE usuario = '".$_SESSION['usuario']."'"));
	$valores = mysql_fetch_array(mysql_query("SELECT menus FROM sgcapass WHERE alias = '".$_SESSION['usuario']."'"));

	for($i=0;$i<strlen($valores[0]);$i++) {
		$array[$i] = substr($valores[0],$i,1);
	}

	if ($_GET['val']) {
		$array[$_GET['men']] = $_GET['val'];
//		echo $array[$_GET['men']];
//		echo $_GET['val'];
	}
	if ($array) {$cad = implode('',$array);}
	mysql_query("UPDATE usuarios SET menus = '$cad' WHERE usuario = '".$_SESSION['usuario']."'");

} else {

	$array = array(1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1);
	$cad = implode('',$array);

}

?>

<div id = 'menusizda'>

<?php

	echo "<div class='redond1'>";
		bordesup();
		tit(0, $array[0], "tit", "Contabilidad");
		if ($array[0] == 1) {
			tit1(1, $array[1], "Asientos");
			if ($array[1] == 1) {
				$menu = "";
				$menu[] = "<a href='altaasim.php?n=1'>» Simple</a>";
				$menu[] = "<a href='altaasigral.php?n=1'>» General</a>";
				$menu[] = "<a href='editasi2.php'>» Buscar/Editar</a>";
//				$menu[] = "<a href='altaasi.php'>» Simples</a>";
//				$menu[] = "<a href='facrec1.php'>» Facturas Recibidas</a>";
//				$menu[] = "<a href='facemi1.php'>» Facturas Emitidas</a>";
// 				$menu[] = "<a href='regsocios.php'>» Actualizar Socios</a>";
				menu($menu,$menu11);
			}
			tit1(2, $array[2], "Cuentas");
			if ($array[2] == 1) {
				$menu = "";
				$menu[] = "<a href='cuentas.php'>» Alta</a>";
				$menu[] = "<a href='reiniciar.php'>» Reiniciar</a>";
//				$menu[] = "<a href='extractoctas.php'>» Extracto de Subcuenta</a>";
//				$menu[] = "<a href='extractoctas2.php'>» Extracto de Subgrupo</a>";
//				$menu[] = "<a href='listactas.php'>» Listado Subcuentas</a>";
//				$menu[] = "<a href='listactas3.php'>» Listado Cuentas</a>";
//				$menu[] = "<a href='listactas2.php'>» Listado Subgrupos</a>";
//				$menu[] = "<a href='editcuen1.php'>» Editar</a>";
//				$menu[] = "<a href='borrarcta.php'>» Borrar</a>";
				menu($menu,$menu12);
			}
			tit1(3, $array[3], "Balances");
			if ($array[3] == 1) {
				$menu = "";
				$menu[] = "<a href='balcom.php'>» Comprobación</a>";
				$menu[] = "<a href='balgen.php'>» General</a>";
/*
				$menu[] = "<a href='balcecompro.php'>» Balances</a>";
				$menu[] = "<a href='pgc.php'>» Plan Gral. Contab.</a>";
				$menu[] = "<a href='busquedas.php'>» Búsquedas</a>";
				$menu[] = "<a href='otroslistados.php'>» Otros Listados</a>";
				$menu[] = "<a href='procper.php'>» Procesos Periódicos</a>";
				$menu[] = "<a href='control.php'>» Control de tablas</a>";
				$menu[] = "<a href='listados.php'>» Listados</a>";
*/
				menu($menu,$menu13);
			}
			tit1(4, $array[4], "Reportes");
			if ($array[4] == 1) {
				$menu = "";
				$menu[] = "<a href='diario.php'>» Diario</a>";
				$menu[] = "<a href='asidescu.php'>» Descuadrados</a>";
				$menu[] = "<a href='extractoctas3.php'>» Mayor Analitico</a>";
				
/*				$menu[] = "<a href='balcom.php'>»Comprobación</a>";
				$menu[] = "<a href='balcecompro.php'>» Balances</a>";
				$menu[] = "<a href='pgc.php'>» Plan Gral. Contab.</a>";
				$menu[] = "<a href='busquedas.php'>» Búsquedas</a>";
				$menu[] = "<a href='otroslistados.php'>» Otros Listados</a>";
				$menu[] = "<a href='procper.php'>» Procesos Periódicos</a>";
				$menu[] = "<a href='control.php'>» Control de tablas</a>";
				$menu[] = "<a href='listados.php'>» Listados</a>";
*/
				menu($menu,$menu14);
			}
		}
	bordeinf($array[0]);
	echo "</div>";

//***********************************************

	echo "<div class='redond1'>";
		bordesup();
		tit(5, $array[5], "tit", "Asociados");
		if ($array[5] == 1) {
			tit1(1, $array[1], "Actualizar");
			if ($array[6] == 1) {
				$menu = "";
				$menu[] = "<a href='regsocios.php'>» Socios</a>";
				$menu[] = "<a href='regbenef.php'>» Beneficiarios</a>";
				$menu[] = "<a href='aportes.php'>» Aportes Ordinarios</a>";
				$menu[] = "<a href='retiros.php'>» Retirar Haberes</a>";
/*
				$menu[] = "<a href='pidafecha.htm'>» fecha</a>";
				$menu[] = "<a href='gcproveedores.php'>» Proveedores</a>";
				$menu[] = "<a href='gcclientes.php'>» Clientes</a>";
				$menu[] = "<a href='gcinventario.php'>» Inventario</a>";
				$menu[] = "<a href='gcfacrec.php'>» F. Recibidas</a>";
				$menu[] = "<a href='gcpedidos.php'>» Pedidos</a>";
				$menu[] = "<a href='gcpedcli.php'>» Pedidos por Cliente</a>";
				$menu[] = "<a href='gcfactemi.php'>» Asignar Facturas a Pedidos</a>";
				$menu[] = "<a href='gcaltafactemi.php'>» F. Emitidas</a>";
				$menu[] = "<a href='gclistados.php'>» Listados</a>";
*/
				menu($menu,$menu61);
			}
			tit1(2, $array[7], "Reportes");
			if ($array[7] == 1) {
				$menu = "";
				$menu[] = "<a href='habsoc.php'>» Haberes</a>";
				$menu[] = "<a href='edocta.php'>» Estados de Cuentas</a>";
				$menu[] = "<a href='hishab.php'>» Histórico de Haberes</a>";
/*
				$menu[] = "<a href='balcom.php'>»Comprobación</a>";
				$menu[] = "<a href='balcecompro.php'>» Balances</a>";
				$menu[] = "<a href='pgc.php'>» Plan Gral. Contab.</a>";
				$menu[] = "<a href='busquedas.php'>» Búsquedas</a>";
				$menu[] = "<a href='otroslistados.php'>» Otros Listados</a>";
				$menu[] = "<a href='procper.php'>» Procesos Periódicos</a>";
				$menu[] = "<a href='control.php'>» Control de tablas</a>";
				$menu[] = "<a href='listados.php'>» Listados</a>";
*/
				menu($menu,$menu14);
			}
		}
	bordeinf($array[5]);
	echo "</div>";

//***********************************************

	echo "<div class='redond1'>";
		bordesup();
		tit(5, $array[5], "tit", "Activos Fijos");
		if ($array[5] == 1) {
			tit1(1, $array[1], "Actualizar");
			if ($array[6] == 1) {
				$menu = "";
				$menu[] = "<a href='lisact.php?accion=Anadir'>» Incorporar</a>";
				$menu[] = "<a href='desact.php'>» Desincorporar</a>";
				$menu[] = "<a href='depact.php'>» Depreciar</a>";
				menu($menu,$menu61);
			}
			tit1(2, $array[7], "Reportes");
			if ($array[7] == 1) {
				$menu = "";
				$menu[] = "<a href='lisact.php'>» Activos Fijos</a>";
				$menu[] = "<a href='lisdes.php'>» Desincorporados</a>";
				$menu[] = "<a href='lisdes.php'>» Depreciacion</a>";
				$menu[] = "<a href='listot.php'>» Totalmente Depr.</a>";
/*
				$menu[] = "<a href='balcom.php'>»Comprobación</a>";
				$menu[] = "<a href='balcecompro.php'>» Balances</a>";
				$menu[] = "<a href='pgc.php'>» Plan Gral. Contab.</a>";
				$menu[] = "<a href='busquedas.php'>» Búsquedas</a>";
				$menu[] = "<a href='otroslistados.php'>» Otros Listados</a>";
				$menu[] = "<a href='procper.php'>» Procesos Periódicos</a>";
				$menu[] = "<a href='control.php'>» Control de tablas</a>";
				$menu[] = "<a href='listados.php'>» Listados</a>";
*/
				menu($menu,$menu14);
			}
		}
	bordeinf($array[5]);
	echo "</div>";

//***********************************************

	echo "<div class='redond1'>";
		bordesup();
		tit(6, $array[6], "tit", "Otros");
		if ($array[6] == 1) {
			tit1(1, $array[1], "Título");
			if ($array[1] == 1) {
				$menu = "";
				$menu[] = "<a href='notas.php'>» Anotaciones</a>";
				$menu[] = "<a href='copiasegu.php'>» Respaldar Datos</a>";
/*				$menu[] = "<a href='usuarios.php'>» Usuarios</a>";
				$menu[] = "<a href='config.php'>» Configuración</a>";
*/
				menu($menu,$menu6);
			}
		}
	bordeinf($array[6]);
	echo "</div>";

//***********************************************
	?>


<?
/*
	<div class='centro'>
	<a href="http://validator.w3.org/check?uri=referer"><img src="http://www.w3.org/Icons/valid-xhtml10" alt="Valid XHTML 1.0 Transitional" height="31" width="88" /></a>
	</div>
*/
?>
</div>

<div class='pag wid83'>

<?php

extract($_GET);
extract($_POST);

if($bloqueo) {echo $bloqueo; echo "</div></body></html>";exit;}

/*
if(($menu11 OR $menu12 OR $menu13) AND $_SESSION['auto'] < 5) {echo "<p />Opcion sólo disponible para usuarios Contables</div></body></html>";
//exit;
}
*/

//************************************

function bordesup() {
	echo "<b class='rtop'><b class='r1'></b><b class='r2'></b><b class='r3'></b><b class='r4'></b></b>";
}

function bordeinf($estado) {
	if ($estado == 1) {
		echo "<b class='rbottom'><b class='r4'></b><b class='r3'></b><b class='r2'></b><b class='r1'></b></b>";
	} else {
		echo "<b class='rbottom1'><b class='r4'></b><b class='r3'></b><b class='r2'></b><b class='r1'></b></b>";
	}
}

function tit($men, $estado, $class , $tit) {

	echo "<div class='$class'>";
	if (!$_SESSION['empresa']) {echo " $tit</div>";return;}
	if ($estado == 1) {
		$m=2;$img="menos";$alt="Reducir";
	} else {
		$m=1;$img="mas";$alt="Ampliar";
	}
	echo "<a href='?men=$men&val=$m' title=\"$alt\">";
	if ($_SESSION['empresa']) { echo "<img src='$img.png' alt='' />";}
	echo " ".$tit;
	echo "</a>";
 	echo "</div>";

}

function tit1($men, $estado, $tit) {

	if ($estado == 1) {
		$m=2;$img="menos";$alt="Reducir";
	} else {
		$m=1;$img="mas";$alt="Ampliar";
	}

	$tit1[0] = "<a href='?men=$men&val=$m' title=\"$alt\" class='b'>";
	if ($_SESSION['empresa']) { $tit1[0] .= "<img src='$img.png' alt='' />";}
	$tit1[0] .= " ".$tit."</a>";

	if (!$_SESSION['empresa']) {$tit1[0] = "<div class='b' style='padding-left:1em'>$tit</div>";}

	menu($tit1,0);

}

function menu($menus, $num) {

	$n = 1;
	echo "<ul class='navv'>";
	foreach ($menus as $elem) {
		if ($elem) {
			if ($num == $n) {$tipo = " id='active'";} else {$tipo = '';}
			echo "<li".$tipo.">".$elem."</li>";
		}
		$n++;
	}
	echo "</ul>";

}