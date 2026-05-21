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

<body <?php if (!$bloqueo) {echo $onload;}?>>

<?php

include("arriba.php");
$menu13=2;include("menusizda.php");

$nivelseleccionado = $_POST['nivelseleccionado'];
if (!isset($nivelseleccionado)) {

echo '<fieldset><legend>Datos para el reporte</legend>';

echo "<form enctype='multipart/form-data' name='form1' action='balgen.php' method='post'>";

echo "Balance General al mes de: ";
// 	$result = mysql_query("SELECT date_format(con_ultfec,'%d/%m/%y') AS ultfechax FROM sgcaf8co");
	$result = mysql_query("SELECT date_format(fech_ejerc,'%d/%m/%Y') AS ultfechax FROM sgcaf100");
	$row = mysql_fetch_assoc($result);
	$fecha = $row['ultfechax'];
	$fecha=convertir_fecha($fecha);
	$fecha=strtotime($fecha);
	$mesdecomprobantes=date('m',$fecha);
	echo '<select name="messeleccionado" size="1">';
	for ($elmes=1; $elmes < 13; $elmes++) {
		echo '<option value="'.$elmes.'" '.(($elmes==$mesdecomprobantes)?'selected':'').'>'.nombremes($elmes).'</option>';}
  	echo '</select> ';
	$anoseleccionado=date('Y',$fecha);
	echo ' del año '."<input type='text' name='anoseleccionado' size='10' maxlength='10' readonly='readonly' value='".$anoseleccionado."'/> <br>";
	echo '<input name="elejercicio" type="checkbox" id="elejercicio" value="1" readonly="readonly" checked>Al Ejercicio<br>';
//	echo '<input name="encero" type="checkbox" id="encero" value="1" checked>No mostrar movimientos en cero (0)<br>';
	$result = mysql_query("SELECT * from sgcafniv order by con_nivel");
	$row = mysql_fetch_array($result);
	$filas = mysql_num_rows($result)+1;
	echo 'Nivel de detalle <select name="nivelseleccionado" size="1">';
	for ($elmes=1; $elmes < $filas; $elmes++) {
		echo '<option value="'.$elmes.'" '.(($elmes==($filas-2))?'selected':'').'>'.$elmes.'</option>';}
  	echo '</select> <br>';
	echo "<input type='submit' name='boton' value=\"Procesar\"'>";

echo '</fieldset><p style="clear:both"><p /> ';
echo '</form>';
}
else
	{
		$messeleccionado=$_POST['messeleccionado'];
		$nivelseleccionado=$_POST['nivelseleccionado'];
		$elejercicio=$_POST['elejercicio'];
//		$encero=$_POST['encero'];
		$anoseleccionado=$_POST['anoseleccionado'];
		$codigo="SELECT cue_codigo, cue_nombre, cue_nivel, cue_saldo, ";
		for ($elmes=1; $elmes < $messeleccionado; $elmes++) {
			$sumar='';
			if ($elmes<10) {$sumar='0'.$elmes; }else {$sumar=$elmes;}
			$codigo.='cue_deb'.$sumar;
			if ($elmes < ($messeleccionado-1)) $codigo.='+';
		}
		if ($messeleccionado == 1) 	$codigo.=" 0 as danterior, ";
		else $codigo.=" as danterior, ";
		for ($elmes=1; $elmes < $messeleccionado; $elmes++) {
			$sumar='';
			if ($elmes<10) $sumar='0'.$elmes; else $sumar=$elmes;
			$codigo.='cue_cre'.$sumar;
			if ($elmes < ($messeleccionado-1)) $codigo.='+';
		}
		if ($messeleccionado < 10) $messeleccionado='0'.$messeleccionado;
		$debeactual='cue_deb'.$messeleccionado;
		$haberactual='cue_cre'.$messeleccionado;
		if ($messeleccionado == 1) 	$codigo.=" 0 as hanterior, ";
		else $codigo.=" as hanterior, ";
		$codigo.=$debeactual." as debe, ".$haberactual .
		" as haber from sgcaf810 where (cue_nivel<='$nivelseleccionado') and (left(cue_codigo,1)<'4') order by cue_codigo, cue_nivel";
// 		echo $codigo."<br>";
		$result= mysql_query($codigo) or die('Error 810-5<br>'.mysql_error());
		$_SESSION['comando']=$codigo; // ['con_nivel'];
	echo '<h1>Balance de General a '.	nombremes($elmes).'/'.$anoseleccionado.'.</h1> Nivel Detalle '.$nivelseleccionado;
	// .'(En Prueba)...problemas con los subtotales...solucionados los subtotales...falta el total balance....';
//	echo "<a href='javascript:print()'>  Imprimir (Igual que en pantalla)  </a>";
	echo "<a target=\"_blank\" href=\"balgenpdf.php?elmes=$elmes&nivelseleccionado=$nivelseleccionado&anoseleccionado=$anoseleccionado&$elejercicio=elejercicio&encero=$encero&messeleccionado=$messeleccionado&\" onClick=\"info.html\', \'\',\'width=250, height=190\')\">Imprimir PDF</a><br><br>"; 
	echo '<table width="800" border="0">';
	echo '<tr>';
    echo '<th width="100">Codigo</th>';
    echo '<th width="200">Cuenta</th>';
    echo '<th width="100"> </th>';
    echo '<th width="100"> </th>';
    echo '<th width="100"> </th>';
    echo '<th width="100"> </th>';
	echo '</tr>';
	$nivelactual='1';
	$ultimonivel='';
	$posicion=$tdebe=$thaber=0;
	$sql="select count(con_nivel) as niveles from sgcafniv";
	$losniveles=mysql_query($sql) or die('Error 810-6<br>'.mysql_error());
	$nivel=mysql_fetch_assoc($losniveles);
	for ($i=0; $i<$nivel['niveles']; $i++) 
		{$arreglo[$i]=0;}
	$nivel=$nivel['niveles'];
	while ($fila = mysql_fetch_assoc($result)) {
		if ($fila['cue_nivel']>$nivelactual) $nivelactual=$fila['cue_nivel'];
		$arreglo[$nivelactual-1]=$posicion;
		if ($nivelactual=='1')
			echo '<tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr>';

///----------------------

			if ($fila['cue_nivel'] < $nivelactual) 
			{
				$pos_actual=$posicion;
				$elnivel=$fila['cue_nivel'];
//				for ($i=($ultimonivel-1); $i>=($elnivel); $i--) 
//				for ($i=1; $i<=6; $i++) 
				$i=$elnivel;
//				echo 'i='.$i.' nivel='.$nivel;
				{
					mysql_data_seek($result,$arreglo[($i-1)]);
					$fila=mysql_fetch_assoc($result);
					if (($fila["cue_saldo"]!=0) || ($fila["danterior"]!=0) || ($fila['debe']!=0) || ($fila["hanterior"]!=0) || ($fila['haber']!=0))
					{
//					echo '<tr> </tr><tr align="right" style="font-style:italic" ><td colspan=2> Total: '.$fila['cue_codigo'].'-'.$i.$fila['cue_nombre'].'</td>';
					echo '<tr> &nbsp;</tr><tr align="right" style="font-style:italic" ><td colspan=2> Total: '.$fila['cue_nombre'].'</td>';
					for ($j=$i+1;$j<$nivel;$j++) echo '<td> &nbsp; </td>';
//						echo '<tr> </tr><tr align="right" style="font-style:italic" ><td colspan=2> Total: '.$fila['cue_nombre'].'</td>';
						$actual=$fila["cue_saldo"]+($fila["danterior"]+$fila['debe'])-($fila["hanterior"]+$fila['haber']);
						for ($j=$fila['cue_nivel'];$j>$nivelseleccionado; $j++) echo '<td>_</td>';
						echo '<td align="right">'.number_format($actual,$deci,".",",");'</td>';
						// <tr><td>&nbsp;</td>
						echo '</tr>';
					}
					mysql_data_seek($result,$pos_actual);		// vuelvo a la posicion original
					$fila=mysql_fetch_assoc($result);
					$nivelactual=$fila['cue_nivel'];
					$arreglo[$nivelactual-1]=$posicion;
				} // < $nivelactual
			}

///----------------------

		$actual=$fila["cue_saldo"]+($fila["danterior"]+$fila['debe'])-($fila["hanterior"]+$fila['haber']);
		if ($actual!=0) //(($fila["cue_saldo"]!=0) || ($fila["danterior"]!=0) || ($fila['debe']!=0) || ($fila["hanterior"]!=0) || ($fila['haber']!=0)) 
		{
//			echo '<tr><td>('.$posicion.')'.$fila["cue_codigo"].'</td>';
			echo '<tr><td>'.$fila["cue_codigo"].'</td>';
			echo '<td>'.$fila["cue_nombre"].'</td>';
			if ($fila['cue_nivel'] == $nivelseleccionado) 
			{
				for ($i=$fila['cue_nivel'];$i>$nivelseleccionado; $i++) echo '<td>_</td>';
				$ultimonivel=$fila['cue_nivel'];
				echo '<td align="right">'.number_format($actual,$deci,".",",").'</td></tr>';
				} 
			}
			
		$posicion++;
		}	// while
// final
				$pos_actual=$posicion;
				$elnivel=$fila['cue_nivel'];
//				for ($i=($ultimonivel-1); $i>=($elnivel); $i--) 
//				for ($i=1; $i<=6; $i++) 
				$i=1; // $elnivel;
//				echo 'i='.$i.' nivel='.$nivel;
				{
					mysql_data_seek($result,$arreglo[($i-1)]);
					$fila=mysql_fetch_assoc($result);
					if (($fila["cue_saldo"]!=0) || ($fila["danterior"]!=0) || ($fila['debe']!=0) || ($fila["hanterior"]!=0) || ($fila['haber']!=0))
					{
//					echo '<tr> </tr><tr align="right" style="font-style:italic" ><td colspan=2> Total: '.$fila['cue_codigo'].'-'.$i.$fila['cue_nombre'].'</td>';
					echo '<tr> &nbsp;</tr><tr align="right" style="font-style:italic" ><td colspan=2> Total: '.$fila['cue_nombre'].'</td>';
					for ($j=$i+1;$j<$nivel;$j++) echo '<td> &nbsp; </td>';
//						echo '<tr> </tr><tr align="right" style="font-style:italic" ><td colspan=2> Total: '.$fila['cue_nombre'].'</td>';
						$actual=$fila["cue_saldo"]+($fila["danterior"]+$fila['debe'])-($fila["hanterior"]+$fila['haber']);
						for ($j=$fila['cue_nivel'];$j>$nivelseleccionado; $j++) echo '<td>_</td>';
						echo '<td align="right">'.number_format($actual,$deci,".",",");'</td>';
						// <tr><td>&nbsp;</td>
						echo '</tr>';
					}
				} // < $nivelactual


// total general
		$codigo="SELECT cue_codigo, cue_nombre, cue_nivel, cue_saldo, ";
		for ($elmes=1; $elmes < $messeleccionado; $elmes++) {
			$sumar='';
			if ($elmes<10) {$sumar='0'.$elmes; }else {$sumar=$elmes;}
			$codigo.='cue_deb'.$sumar;
			if ($elmes < ($messeleccionado-1)) $codigo.='+';
		}
		if ($messeleccionado == 1) 	$codigo.=" 0 as danterior, ";
		else $codigo.=" as danterior, ";
		for ($elmes=1; $elmes < $messeleccionado; $elmes++) {
			$sumar='';
			if ($elmes<10) $sumar='0'.$elmes; else $sumar=$elmes;
			$codigo.='cue_cre'.$sumar;
			if ($elmes < ($messeleccionado-1)) $codigo.='+';
		}
//		if ($messeleccionado < 10) $messeleccionado='0'.$messeleccionado;
		$debeactual='cue_deb'.$messeleccionado;
		$haberactual='cue_cre'.$messeleccionado;
		if ($messeleccionado == 1) 	$codigo.=" 0 as hanterior, ";
		else $codigo.=" as hanterior, ";
		$codigo.=$debeactual." as debe, ".$haberactual .
		" as haber from sgcaf810 where (cue_nivel='1') order by cue_codigo, cue_nivel";
// 		echo $codigo."<br>";
		$result= mysql_query($codigo) or die('Error 810-6<br>'.$codigo.'<br>'.mysql_error());
		$activo=$pasivo=$patrimonio=$ingresos=$egresos=0;
		while ($fila = mysql_fetch_assoc($result)) {
			$actual=$fila["cue_saldo"]+($fila["danterior"]+$fila['debe'])-($fila["hanterior"]+$fila['haber']);
			if (substr($fila['cue_codigo'],0,1)=='1') $activo=$actual; 
			if (substr($fila['cue_codigo'],0,1)=='2') $pasivo=$actual;
			if (substr($fila['cue_codigo'],0,1)=='3') $patrimonio=$actual;
			if (substr($fila['cue_codigo'],0,1)=='4') $ingresos=$actual;
			if (substr($fila['cue_codigo'],0,1)=='5') $egresos=$actual;
		}
/*
		echo 'activo '.$activo;
		echo 'pasivo '.$pasivo;
		echo 'patrimonio '.$patrimonio;
		echo 'ingresos '.$ingresos;
		echo 'egresos '.$egresos;
*/
		echo '<tr> </tr><tr align="right" style="font-style:oblique" ><td colspan=2> Resultado del Ejercicio: </td>';	
		echo '<td ></td>';
		echo '<td align="right">'.number_format($ingresos+$egresos,$deci,".",",").'</td></tr>';
		echo '<tr> </tr><tr align="right" style="font-style:oblique" ><td colspan=2> Total Pasivo+Patrimonio+Resultado: </td>';	
		echo '<td ></td>';
		echo '<td align="right">'.number_format($pasivo+$patrimonio+$ingresos+$egresos,$deci,".",",").'</td></tr>';
		echo '<tr> </tr><tr align="right" style="font-style:oblique" ><td colspan=2> Ecuación Patrimonial: </td>';	
		echo '<td ></td>';
		echo '<td align="right">'.number_format($activo+$pasivo+$patrimonio+$ingresos+$egresos,$deci,".",",").'</td></tr>';


		echo '<td>&nbsp;</td><tr><td>&nbsp;</td></tr>';

	echo '</table>';
	echo '<br><br>';

/*
$f = fopen("datos.csv","w");
$sep = ";";

mysql_data_seek($result,0);
	while($reg = mysql_fetch_array($result) ) {
		$linea = $reg['cue_codigo'] . $sep . $reg['cue_nombre'] . $sep . $reg['cue_saldo']. $sep . $reg['danterior']. $sep . $reg['hanterior']. $sep . $reg['debe']. $sep . $reg['haber']; //pones cada campo separado con $sep.
	fwrite($f,$linea);
	}
fclose($f); 
$fichero = "datos.csv";

 header("Content-type: application/vnd.ms-excel") ;
   //header("Content-Disposition: attachment; filename=kssa.xls" ;
   header ("filename=datos.csv") ;
   /*
header("Content-Description: File Transfer");
header( "Content-Disposition: filename=".basename($fichero) );
header("Content-Length: ".filesize($fichero));
header("Content-Type: application/force-download");
@readfile($fichero);
*/


//	echo "<form enctype='multipart/form-data' name='form1' action='balcom.php' method='post'>";
////	echo '<input type = 'submit'name = 'formu' value = 'Añadir' tabindex='10' onclick='return compruebafecha(form1)'>
//	echo "<input type='submit' name='formu' value=\"Generar archivo CSV\" onclick='return exporta()>";
//	echo "</form>";
	}	// else
echo '</body></html>';

?>
