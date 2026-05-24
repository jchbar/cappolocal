<?php

//Copyright (C) 2000-2006  Antonio Grand�o Botella http://www.antoniograndio.com
//Copyright (C) 2000-2006  Inmaculada Echarri San Adri�n http://www.inmaecharri.com

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

/* *** COMPROBACI�N A�O ****************************** */

function anocont($fecha) {

	$result = mysql_query("SELECT anocont FROM empresa");
	$fila = mysql_fetch_array($result);

	$b = explode("/",$fecha);

	if ($fila[0] != "20".$b[2]) {return 0;}

	return "20".$b[2]."-".$b[1]."-".$b[0];

}

/* *** CABECERA LISTADO ASIENTOS ****************************** */

function cabasi($edborr) {

echo "<tr>";

if ($edborr) {echo "<th width='200' colspan=2></th>";}

echo '<th width="100">Cuenta</th><th width="200">Descripci�n</th><th width="200">Concepto</th><th width="50">Referencia</th><th width="100">Debe</th><th width="100">Haber</th></tr>';
// <th width="100">Descuadre</th></tr>';

}

/* *** LISTAR ASIENTO ****************************** */

function asiento($asiento, $edborr, $por, $deci, $bojust) {

// if ($bojust == $asiento) {mysql_query("UPDATE asientos SET fich = '', tipofich='' WHERE asiento = '$asiento'");}

$result = mysql_query("SELECT * FROM sgcaf830 WHERE enc_clave = $asiento");
if ($result) {$fichero = mysql_fetch_array($result);}

/*
$result = mysql_query("SELECT enc_clave, enc_fecha, sumadebe, sumahaber FROM asientos WHERE asiento = $asiento"); 

if (@mysql_num_rows($result) == 0) {

	echo $mensaje."<p />Asiento <span class='b'>$asiento</span> inexistente o Apunte Hu�rfano.";
	return;

}
*/
$cols = 6;

if ($edborr) {$cols = $cols+2;}

$asi = $fichero; // mysql_fetch_array($result);
$a=explode("-",$asi["enc_fecha"]);
echo "<tr><td class='blanco b' colspan='$cols'>Asiento: <a href='editasi2.php?asiento=$asiento'>".$asiento."</a> Fecha: ";
echo $a[2]."/".$a[1]."/".$a[0]; // substr($a[0],2,2);
// echo $asi["enc_fecha"];
/*
echo " Tipo: ";
echo $asi['tipo'];
if ($asi['tipo'] == "F. Recibida") {
	$temp = mysql_fetch_array(mysql_query("SELECT fact, codigo FROM factrec WHERE asiento = '$asiento'"));
	echo " <a href='gcfacrec.php?fact=$temp[0]&codigo=$temp[1]'>$temp[0]</a>";
}
if ($asi['tipo'] == "F. Emitida") {
	$temp = mysql_fetch_array(mysql_query("SELECT row_id FROM factemi WHERE asiento = '$asiento'"));
	echo " <span class='b'>$temp[0]</span>";
}
*/
echo "</b>";
// if ($fichero[0]) 
if (strlen($asi["enc_soporte"]) > 0)
{echo "&nbsp;&nbsp;&nbsp;&nbsp;<a href='imagen.php?asiento=$asiento' target='_blank'>Ver Soporte</a>&nbsp;&nbsp;&nbsp;<a href='editasi2.php?asiento=$asiento&bojust=$asiento' onclick='return borrar_justificante()'>Borrar Soporte</a>";}

if (trim($fichero[1])) {
	echo "&nbsp;&nbsp;&nbsp;<a onclick=\"amplred('div$asiento')\">Explicaci�n (ver/ocultar)</a>";
	echo "<div id='div$asiento' style='display:none'>".$fichero[1]."</div>";
}
// echo "<a href='javascript:print()'>  Imprimir</a>";
echo "<a href='impcom.php?asiento=".$asiento."'>  Imprimir</a>";

echo "</td></tr>";

// $result = mysql_query("SELECT apuntes.row_id, apuntes.cuenta, concepto, debe, haber, subcuent.descripci_ FROM apuntes LEFT JOIN subcuent ON apuntes.cuenta = subcuent.cuenta WHERE asiento = '$asiento' ORDER BY debe DESC,haber DESC");
// $result = mysql_query("SELECT sgcaf820.nro_registro, sgcaf820.com_cuenta, com_descri, com_monto1, com_monto2, sgcaf810.cue_nombre, com_refere FROM sgcaf820 LEFT JOIN sgcaf810 ON (sgcaf820.com_cuenta = sgcaf810.cue_codigo) WHERE com_nrocom = '$asiento' ORDER BY com_monto1 DESC,com_monto2 DESC");
/*
$elsql="call sp_qry_compr('$asiento')";
 echo $elsql;
$result = mysql_query($elsql) or die(mysql_error());
 echo "resultado ".$result;
 echo mysql_num_rows($result);
*/
//$result = mysql_query("SELECT nro_registro, com_cuenta, com_descri, com_monto1, com_monto2, com_refere FROM sgcaf820 WHERE com_nrocom = '$asiento' ORDER BY com_monto1 DESC,com_monto2 DESC");
$result = mysql_query("SELECT nro_registro, com_cuenta, com_descri, com_monto1, com_monto2, com_refere FROM sgcaf820 WHERE com_nrocom = '$asiento' ORDER BY com_debcre, com_cuenta, com_refere");

while ($fila = mysql_fetch_array($result)) {

	echo "<tr>";
	if ($edborr) {
/*
		echo "<td><a href='editasi2.php?row_id=".$fila[0]."&asiento=$asiento&accion=editapu' target='_self'>Editar</a></td>";
		echo "<td><a href='editasi2.php?row_id=".$fila[0]."&asiento=$asiento&accion=boapu' onclick='return borrar_apunte()'>Borrar</a></td>";
		*/

		echo "<td><a href='editasi2.php?row_id=".$fila[0]."&asiento=$asiento&accion=editapu' target='_self'> <img src='imagenes/16-em-pencil.png' width='16' height='16' border='0' title='Editar' alt='Editar' /></a></td>";
		echo "<td><a href='editasi2.php?row_id=".$fila[0]."&asiento=$asiento&accion=boapu' onclick='return borrar_reg_820()'><img src='imagenes/16-em-cross.png' width='16' height='16' border='0' title='Eliminar'  alt='Eliminar' /></a></td>";
	}
	echo "<td width='100'><a href=\"extractoctas3.php?cuenta=".$fila["com_cuenta"]."&datos='no'\">".$fila["com_cuenta"]."</a></td>";
	
//	$sqlcuenta="call sp_qry_cuenta('".$fila["com_cuenta"]."')";
	$sqlcuenta="SELECT cue_nombre FROM sgcaf810 where cue_codigo = '".$fila["com_cuenta"]."'";
// 	echo $sqlcuenta;
	$rs=mysql_query($sqlcuenta) or die ("<p />Estimado usuario $usuario contacte al administrador C�digo 810-1");
	$filacuenta = mysql_fetch_array($rs);
	echo "<td width='200'>".$filacuenta["cue_nombre"]."</td>";
	
//	echo "<td>".$fila["cue_nombre"]."</td>";
	echo "<td width='200'>".$fila["com_descri"]."</td>";
	echo "<td width='50'>".$fila["com_refere"]."</td><td width='100' class='dcha'>";
	
	if ($fila["com_monto1"] == 0)
	{
		echo "&nbsp;";
	} else {
		echo number_format($fila["com_monto1"]*$por,$deci,'.',',');
	}

	echo "</td><td class='dcha'>";
	
	
	if ($fila["com_monto2"] == 0)
	{
		echo "&nbsp;";
	} else {
		echo number_format($fila["com_monto2"]*$por,$deci,'.',',');
	}
//	echo "</td><td></td></tr>";
	echo "</td></tr>";

}

$elmonto=$asi['enc_debe']-$asi['enc_haber'];
if ($asi['enc_debe']-$asi['enc_haber'] != 0) {
	echo "<tr><td class='rojo dcha b' colspan=".($cols-2);
	echo "<span class='blanco b'> Diferencia de ".number_format(($asi['enc_debe']-$asi['enc_haber'])*$por,$deci,',','.')."</span>";
}
else 
	echo "<tr><td class='blanco dcha b' colspan=".($cols-2).">";
echo "  SubTotales: </td><td class='blanco dcha b'>".number_format($asi['enc_debe']*$por,$deci,',','.')."</td><td class='blanco dcha b'>".number_format($asi['enc_haber']*$por,$deci,',','.')."</td>";
/*
echo "<td class='blanco dcha hover'>";
if ($asi['enc_debe']-$asi['enc_haber'] != 0) {
	echo "<span class='rojo b'>".number_format(($asi['enc_debe']-$asi['enc_haber'])*$por,$deci,',','.')."</span>";
}
echo "</td>";
*/

echo "</tr><tr><td colspan='$cols' class='verde'>&nbsp;</td></tr>
<p>";
  
  }
  
  /* *** ACTUALIZAR UNO O TODOS LOS ASIENTOS ****************************** */
  
  function totalapu($asiento) {
//  $comando="call totalapu('".$asiento."')"; // llamado de procedimiento


  if ($asiento) {$where = "WHERE com_nrocom = '$asiento'";}
  
  $rs = mysql_query("SELECT com_nrocom from sgcaf820 $where");
  if (mysql_num_rows($rs) < 30)
	$minimo=30;
else $minimo=mysql_num_rows($rs);
	set_time_limit($minimo);

  
  while ($fila = mysql_fetch_array($rs)) :
  
  $a = $fila[0];
  
  $rs1=mysql_query("SELECT SUM(com_monto1) AS tot_debe, SUM(com_monto2) AS tot_haber, COUNT(com_nrocom) as tot_items FROM sgcaf820 WHERE com_nrocom = '$a'");
  $fila1 = mysql_fetch_array($rs1);
  
  mysql_query("UPDATE sgcaf830 SET enc_debe = '$fila1[0]', enc_haber = '$fila1[1]', enc_item = '$fila[2]' WHERE enc_clave = '$a'");
  
  endwhile;
  
  return ($fila1[0] - $fila1[1]);

//	$resultado=mysql_query("select totalapu('".$asiento."')"); // llamado de procedimiento  
//	echo '<h1>el resultado $resultado';
	return $resultado;
  
  }
  
  /* *** TIPO DE ASIENTOS ****************************** */
  
  function tipoasi() {
  
  $fp = fopen("tipoasi.txt","r");
  while ($linea= fgets($fp,1024)){$array[] = $linea;}
  fclose($fp);
  return $array;
  
  }
  
  /* *** MONEDA ****************************** */
  
  function moneda() {
  
  $fp = fopen("moneda.txt","r");
  while ($linea= fgets($fp,1024)){$array[] = $linea;}
  fclose($fp);
  return $array;
  
  }
  
  /* *** ACTUALIZAR UNA O TODAS LAS SUBCUENTAS ****************************** */
  
  function totsubcuentas ($subcuenta) {
  
  if ($subcuenta) {$where = "WHERE cuenta = '$subcuenta'";}
  
  $rs = mysql_query("SELECT cuenta from subcuent $where");
  
  while ($fila = mysql_fetch_array($rs)) :
  
  $rs1 = mysql_query("SELECT SUM(debe) AS tot_debe, SUM(haber) AS tot_haber FROM apuntes WHERE apuntes.cuenta = ".$fila['cuenta']);
  $sum = mysql_fetch_array($rs1);
  
  $tot = $sum['tot_debe'] - $sum['tot_haber'];
  
  if ($tot > 0)
  {
  $result = mysql_query("UPDATE subcuent SET saldod = '$tot', saldoa = 0, sdebe = '".$sum['tot_debe']."', shaber = '".$sum['tot_haber']."' WHERE cuenta =".$fila['cuenta']) or die ("El usuario $usuario no tiene permisos para hacer Balances de Comprobaci�n."); 
  } else {
  
  $result = mysql_query("UPDATE subcuent SET saldoa = 0 - '$tot', saldod = 0, sdebe = '".$sum['tot_debe']."', shaber = '".$sum['tot_haber']."' WHERE cuenta =".$fila['cuenta']) or die ("El usuario $usuario no tiene permisos para hacer Balances de Comprobaci�n."); 
  
  }
  
  endwhile;
  
  if ($subcuenta) {totcuenta (substr($subcuenta,0,3));}
  
  }
  
  /* *** ACTUALIZAR UNA O TODAS LAS CUENTAS ****************************** */
  
  function totcuentas ($cuenta) {
  
  if ($cuenta) {$where = "WHERE cuenta = '$cuenta'";}
  
  $rs = mysql_query("SELECT cuenta, subgrupo from cuentas $where");
  
  while ($fila = mysql_fetch_array($rs)) :
  
  $rs1 = mysql_query("SELECT SUM(saldod) AS tot_debe, SUM(saldoa) AS tot_haber FROM subcuent WHERE cuenta LIKE '".$fila['cuenta']."%'");
  $sum = mysql_fetch_array($rs1);
  
  $tot = $sum['tot_debe'] - $sum['tot_haber'];
  
  if ($tot > 0)
  {
  $result = mysql_query("UPDATE cuentas SET sdo3cd = '$tot', sdo3ca = 0, sdebe = '".$sum['tot_debe']."', shaber = '".$sum['tot_haber']."' WHERE cuenta =".$fila['cuenta']) or die ("El usuario $usuario no tiene permisos para hacer Balances de Comprobaci�n."); 
  } else {
  
  $result = mysql_query("UPDATE cuentas SET sdo3ca = 0 - '$tot', sdo3cd = 0, sdebe = '".$sum['tot_debe']."', shaber = '".$sum['tot_haber']."'  WHERE cuenta =".$fila['cuenta']) or die ("El usuario $usuario no tiene permisos para hacer Balances de Comprobaci�n."); 
  
  }
  
  endwhile;
  
  if ($cuenta) {totsubgrupo (substr($fila['subgrupo']));}
  
  }
  
  /* *** ACTUALIZAR UNO O TODOS LOS SUBGRUPOS ****************************** */
  
  function totsubgrupos ($subgrupo) {
  
  if ($subgrupo) {$where = "WHERE subgrupo = '$subgrupo'";}
  
  $rs = mysql_query("SELECT subgrupo from subgrupo $where");
  
  while ($fila = mysql_fetch_array($rs)) :
  
  $rs1 = mysql_query("SELECT SUM(sdo3ca) AS tot_debe, SUM(sdo3cd) AS tot_haber FROM cuentas WHERE cuenta LIKE '".$fila['subgrupo']."%'");
  $sum = mysql_fetch_array($rs1);
  
  $tot = $sum['tot_debe'] - $sum['tot_haber'];
  
  if ($tot > 0)
  {
  $result = mysql_query("UPDATE subgrupo SET sdod2c = '$tot', sdoh2c = 0, sdebe = '".$sum['tot_debe']."', shaber = '".$sum['tot_haber']."' WHERE subgrupo =".$fila['subgrupo']) or die ("El usuario $usuario no tiene permisos para hacer Balances de Comprobaci�n."); 
  } else {
  
  $result = mysql_query("UPDATE subgrupo SET sdoh2c = 0 - '$tot', sdod2c = 0, sdebe = '".$sum['tot_debe']."', shaber = '".$sum['tot_haber']."'  WHERE subgrupo =".$fila['subgrupo']) or die ("El usuario $usuario no tiene permisos para hacer Balances de Comprobaci�n."); 
  
  }
  
  endwhile;
  
  } 
  
/*	
	SET @debe  = (SELECT SUM(com_monto1) FROM sgcaf820 WHERE com_nrocom=pcom_nrocom);
	SET @haber = (SELECT SUM(com_monto2) FROM sgcaf820 WHERE com_nrocom=pcom_nrocom);
	SET @items = (SELECT COUNT(com_nrocom) FROM sgcaf820 WHERE com_nrocom=pcom_nrocom);
	UPDATE sgcaf830 SET enc_debe=@debe, enc_haber=@haber, enc_item=@items WHERE enc_clave = pcom_nrocom;
*/

function ifecha() {

$ndia = date('N');
$nmes = date('n');

$semana['1'] = "Lunes";
$semana['2'] = "Martes";
$semana['3'] = "Mi�rcoles";
$semana['4'] = "Jueves";
$semana['5'] = "Viernes";
$semana['6'] = "S�bado";
$semana['7'] = "Domingo";

$mes[1] = "Enero";
$mes[2] = "Febrero";
$mes[3] = "Marzo";
$mes[4] = "Abril";
$mes[5] = "Mayo";
$mes[6] = "Junio";
$mes[7] = "Julio";
$mes[8] = "Agosto";
$mes[9] = "Septiembre";
$mes[10] = "Octubre";
$mes[11] = "Noviembre";
$mes[12] = "Diciembre";

return $semana[$ndia]." ".date("d")." de ".$mes[$nmes]." de ".date("Y");

}

function convertir_fecha($mifecha)
{
//	echo $mifecha;
	$a=explode("/",$mifecha); 
	$elano=substr($a[0],0,2);
// 	if ($elano="20") $b=$a[2]."-".$a[1]."-".$a[0];
//	if ($elano="20") $b=$a[2].'-'.(($a[1]<10)?'0'.$a[1]:$a[1])."-".(($a[0]<10)?'0'.$a[0]:$a[0]);
//	else $b="20".$a[2]."-".(($a[1]<10)?'0'.$a[1]:$a[1])."-".(($a[0]<10)?'0'.$a[0]:$a[0]);
//	die('fecha recibida'.$mifecha);
	if ($elano="20") $b=$a[2].'-'.$a[1]."-".trim($a[0]);
	else $b="20".$a[2]."-".$a[1]."-".trim($a[0]);
	if ($mifecha=='//') $b='0000-00-00';
return $b;
}

function convertir_fechadmy($mifecha)
{
//	$mifecha=strtotime($mifecha);
//	echo $mifecha;
	$a=explode("-",$mifecha); 
	$elano=substr($a[0],0,2);
	if ($elano="20") $b=$a[2]."/".$a[1]."/".trim($a[0]);
	else $b=$a[2]."/".$a[1]."/"."20".trim($a[0]);
//	if ($elano="20") $b=(($a[2]<10)?'0'.$a[2]:$a[2])."/".(($a[1]<10)?'0'.$a[1]:$a[1])."/".$a[0];
//	else $b=$b=(($a[2]<10)?'0'.$a[2]:$a[2])."/".(($a[1]<10)?'0'.$a[1]:$a[1])."/"."20".$a[0];
	if ($mifecha=='--') $b='00/00/0000';
return $b;
}

function solicitar_fechas()
{
	$fechai="01/01/".date("Y");
	$fechaf=date("d")."/".date('n')."/".date("Y"); 
	echo 'Fecha Inicio: ';
	escribe_formulario_fecha_vacio("fechai","form1",$fechai,2,''); 
	// <input type='text' name='fechai' size='10' maxlength='10' value="<?php echo $fechai >">
	// <input type="button" name="selfechai" value="..."  onclick='displayDatePicker("fechai","","dmy")' />
	echo 'Fecha Final: ';
	// <input type='text' name='fechaf' size='10' maxlength='10' value=" <?php echo $fechaf >">
	// <input type="button" name="selfechaf" value="..."  onclick='displayDatePicker("fechaf","","dmy")' />
	escribe_formulario_fecha_vacio("fechaf","form1",$fechaf,3,''); 
}

function calcular_saldo($registro,$fechai)
{
	$a=explode("/",$fechai); 
	$elmesi=$a[1];
	$elsaldo=$registro['cue_saldo'];
	if ((! $fechai)) //  or ($elmes = '1'))
		return ($elsaldo);
/*	for ($i=1; $i<$elmesi; $i++)
	{
		if ($i<10) $mes='0'.$i; else $mes=$i;
		$debe='$registro["cue_deb'.$mes.'"]';
		$debe='$registro["cue_cre'.$mes.'"]';
		echo $debe; // $registro."['".$debe."']";
		$elsaldo+=$debe; // $registro["'".$debe."'"];
		$elsaldo-=$haber;
		echo $elsaldo."<br>";
	} 
*/
	$meses=$debe=$haber=0;
	foreach ($registro as $indice => $valor) {
//		echo "$registro[$indice]";
//		echo "indice =".$indice;
//		echo "valor =".$valor;
	if (substr($indice,0,7)=='cue_deb') $meses++;
	if ($meses < $elmesi ) {
// 		echo $meses.'-'.$elmesi.'/'."<br>";
		if (substr($indice,0,7)=='cue_deb') $debe+=$valor;  // echo $valor; }
			elseif (substr($indice,0,7)=='cue_cre') $haber+=$valor; // echo $haber; }
			}
	}
	$elsaldo=$elsaldo+($debe-$haber);
	
return $elsaldo;
}

function procese($elmes,$niveles)
{
//	$sql="select com_cuenta, com_debcre, sum(com_monto1) as debe, sum(com_monto2) as haber from sgcaf820 where month(com_fecha)=$elmes group by com_cuenta order by com_cuenta";
	$sql="select fech_ejerc from sgcaf100 limit 1";
	$result=mysql_query($sql);
	$registro=mysql_fetch_assoc($result);
	$ano=$registro['fech_ejerc'];
	$ano=explode('-',$ano);
	$ano=$ano[0];
	$numero=$niveles['con_nivel'];
	// chequeo de fallas
	if ($elmes < 10)
		$mimes='0'.$elmes;
	else $mimes=$elmes;
	$sql="select com_nrocom, sum(com_monto1) as debe, sum(com_monto2) as haber,sum(com_monto1)-sum(com_monto2) as diferencia from sgcaf820 where substr(com_fecha,1,7)='$ano-$mimes' and length(trim(com_cuenta))=length('5-07-01-06-01-01-0001') group by com_nrocom order by sum(com_monto1)-sum(com_monto2) desc";
//	echo $sql;
	echo '<br>';
	$hay=false;
	$result = mysql_query($sql) or die('Error en la F820-4'.$sql.' '.mysql_error()); 
	while ($fila = mysql_fetch_assoc($result)) {
//		echo substr(trim($fila['diferencia']),1,4).'<br>';
		if (substr(trim($fila['diferencia']),0,4)!= '0.00') {
			echo "<strong><a target=\"_blank\" href='editasi2.php?asiento=".$fila['com_nrocom']."'>".$fila['com_nrocom']."</a></strong> <br>";
			echo $fila['debe'].' '.$fila['haber'].' '.$fila['diferencia'].' '.'<br>';
			$hay=true;
		}
	}
	if ($hay == true) {
		die('<h1>Revisar los comprobantes anteriores que tienen inconveniente</h1>');
	}

	$sql="select left(com_cuenta,$numero) as reducido, com_cuenta, com_debcre, sum(com_monto1) as debe, sum(com_monto2) as haber from sgcaf820 where month(com_fecha)=$elmes and year(com_fecha)=$ano group by reducido order by reducido";
// 	echo $sql;
	$result = mysql_query($sql) or die('Error en la F820-3 '.$sql.' '.mysql_error()); 
	if (mysql_num_rows($result) == 0) {
		echo "<p /><br /><p />No existen movimientos en el mes <span class='b'>$elmes</span> revisar";
		exit;
	}
//	echo mysql_num_rows($result)."<br>";
	set_time_limit(mysql_num_rows($result));
	$ValorTotal=mysql_num_rows($result);
	$cuantos=0;
//	echo "<div id='progreso' style='position:relative; padding:0px;width:850px;height:20px;left:25px;'>";
	while ($fila = mysql_fetch_assoc($result)) {
		calcule_810($fila,$niveles,$elmes);
/*
		echo "<div style='float:left;margin:5px 0px 0px 1px;width:2px;height:12px;background:red;color:red;'> </div>";;
		flush();
		ob_flush();
*/
		$cuantos++;
		$porcentaje = $cuantos * 100 / $ValorTotal; //saco mi valor en porcentaje
		echo "<script>callprogress(".round($porcentaje).")</script>"; //llamo a la funci�n JS(JavaScript) para actualizar el progreso
//		echo $porcentaje.'<br>';
		flush(); //con esta funcion hago que se muestre el resultado de inmediato y no espere a terminar todo el bucle con los 25 registros para recien mostrar el resultado
		ob_flush();
	}
	echo "</div>";
/*
	echo "<script>";
//	echo "document.getElementById('progreso').style.displaye='none';";

	echo "</script>";
*/
}

function calcule_810($registro,$niveles,$elmes)
{
	$elcodigo=$registro['com_cuenta'];
	if ($elmes < 10) $elmes='0'.$elmes;
	// regreso al bof niveles
// 	$filas=mysql_num_rows($niveles);
//	mysql_data_seek($niveles, 0);
//	while ($fila = mysql_fetch_assoc($niveles)) 
	$fila = $niveles ;
	{
		$elnivel=$fila['con_nivel'];
		$codigo=substr($elcodigo,0,$elnivel);
		$debito='cue_deb'.$elmes;
		$credito='cue_cre'.$elmes;
		$eldebe=$registro['debe'];
		$elhaber=$registro['haber'];
		$sql="select cue_codigo from sgcaf810 where cue_codigo='$codigo'";
//		echo $sql;
		$result = mysql_query($sql) or die('Error en la F810-4 '.$sql.' '.mysql_error()); 		
		if ((mysql_num_rows($result) < 1) and (strlen(trim($codigo)) > 16)) // and ($codigo != '')  and ($elnivel = '7') 
		{ 	// no existe la cuenta y la creo
			// busco el socio
			$socio=explode('-',$codigo);
			$socio='0'.$socio[6];
			$sql="select ape_prof, nombr_prof from sgcaf200 where cod_prof='$socio'";

// 1-01-02-01-08-08-0249
//			echo $sql;
			$result = mysql_query($sql) or die('Error en la F200-1 '.$sql.' '.mysql_error()); 
			$rsocio=mysql_fetch_assoc($result);
			$nombre=trim($rsocio['ape_prof']). ' '.$rsocio['nombr_prof'];
			$sql="insert into sgcaf810 (cue_codigo, cue_nombre, cue_nivel, cue_saldo) values ('$codigo', '$nombre', '7', 0)";
//			echo $sql;
			$result = mysql_query($sql) or die('Error en la F810-5 '.$sql.' '.mysql_error()); 
		}
		$sql="update sgcaf810 set $debito=$debito+'$eldebe', $credito=$credito+'$elhaber' where cue_codigo='$codigo'";
		$result = mysql_query($sql) or die('Error en la F810-3 '.$sql.' '.mysql_error()); 		
		$sql="select cue_codigo from sgcaf810 where cue_codigo='$codigo'";
		$result = mysql_query($sql) or die('Error en la F810-4 '.$sql.' '.mysql_error()); 
		if (mysql_num_rows($result) < 1)
			echo $sql.'<br>';
	}
//	echo $codigo;
}

function nombremes($numeromes) {

$nmes = $numeromes;
$mes[1] = "Enero";
$mes[2] = "Febrero";
$mes[3] = "Marzo";
$mes[4] = "Abril";
$mes[5] = "Mayo";
$mes[6] = "Junio";
$mes[7] = "Julio";
$mes[8] = "Agosto";
$mes[9] = "Septiembre";
$mes[10] = "Octubre";
$mes[11] = "Noviembre";
$mes[12] = "Diciembre";

return $mes[$nmes];
}

function chequear_procesar($mesprocesar,$losniveles,$elmes)
{
if ($mesprocesar== 1) {
	$delmes=$elmes;
	echo "Procesando ".nombremes($elmes)." <br>";
	if ($delmes < 10) $delmes='0'.$delmes;
	$debito='cue_deb'.$delmes;
	$credito='cue_cre'.$delmes;
	$sql="update sgcaf810 set $debito=0, $credito=0";
	$result = mysql_query($sql) or die('Error en la F810-4 '.$sql.' '.mysql_error()); 		
	
	for ($i = mysql_num_rows($losniveles) - 1; $i >= 0; $i--) {
    	if (!mysql_data_seek($losniveles, $i)) {
	        echo "Cannot seek to row $i: " . mysql_error() . "\n";
	        continue;
    	}
	    if (!($niveles = mysql_fetch_assoc($losniveles))) {
	        continue;
	    }
//		echo "nivel tomado ".$niveles['con_nivel'];
		procese($elmes,$niveles);
		set_time_limit(60);
	}
	echo "Procesado ".nombremes($elmes)." <br>";
	}
}

function cuenta_810($codigo, $nombre)
{
	$sql810="select cue_codigo from sgcaf810 where cue_codigo='$codigo'";
	$a810=mysql_query($sql810);
	$c810=mysql_num_rows($a810);
	if ($c810 == 0)	{ // no existe la agrego
		$sql810="insert into sgcaf810 (cue_codigo, cue_nombre, cue_nivel, cue_saldo) values 
			('$codigo', '$nombre', '7', 0)";
		$r810=mysql_query($sql810);
	}
}

function agregar_f820 ($pcom_nrocom, $pcom_fecha, $pcom_debcre, $pcom_cuenta, $pcom_descri, $elmonto, $pcom_monto2, $pcom_monto, $pcom_ip, $pcom_nroite, $pcom_refere, $pcom_tipmov, $agregar, $registro)
{
	global $global_tasa_usd;
	if (isset($global_tasa_usd) && $global_tasa_usd > 0) {
		$elmonto = $elmonto * $global_tasa_usd;
	}
	$pcom_monto1 = $pcom_monto2 = 0;
	if (($pcom_debcre =='+') or ($pcom_debcre == '1') or ($pcom_debcre == on)) 
		{ $pcom_monto1=$elmonto; $pcom_debcre = '+';}
		else { $pcom_debcre= '-';  $pcom_monto2 = $elmonto;} 
	if ($agregar == 'S') {
		$elsql="INSERT INTO sgcaf820 (
com_nrocom, com_fecha, com_debcre, com_cuenta, com_descri, com_monto1, com_monto2, com_monto, com_ip, com_nroite, com_refere, com_tipmov) VALUES (
'$pcom_nrocom', '$pcom_fecha', '$pcom_debcre', '$pcom_cuenta', '$pcom_descri', '$pcom_monto1', '$pcom_monto2', '$pcom_monto', '$pcom_ip', '$pcom_nroite', '$pcom_refere', '$pcom_tipmov')"; 
//		$elsql="call sp_inc_r_820 (
// '$pcom_nrocom', '$pcom_fecha', '$pcom_debcre', '$pcom_cuenta', '$pcom_descri', '$pcom_monto1', '$pcom_monto2', '$pcom_monto', '$pcom_ip', '$pcom_nroite', '$pcom_refere', '$pcom_tipmov')"; 	
}
	else if ($agregar == 'N') {
			$elsql="UPDATE sgcaf820 SET com_debcre='$pcom_debcre', com_cuenta='$pcom_cuenta', com_descri='$pcom_descri', com_monto1='$pcom_monto1', com_monto2='$pcom_monto2', com_ip='$pcom_ip', com_nroite='$pcom_nroite', com_refere='$pcom_refere', com_tipmov='$pcom_tipmov' WHERE nro_registro=$registro"; 
		}
		else {
			$elsql="DELETE FROM sgcaf820 WHERE nro_registro = $registro";
			}
//	echo $elsql.'<br>';
	$rs=(mysql_query($elsql)) or die ("<p />Estimado usuario $usuario contacte al administrador C�digo 820-1- <br>".mysql_error()."<br>".$elsql);
// $final = explode(" ", microtime());
// $tiempo = ($final[1] + $final[0]) - ($comienzo[1] - $comienzo[0]); 
// echo "comando ejecutado en $tiempo segundos";
	
	$elsql="SELECT SUM(com_monto1) as debe, SUM(com_monto2) AS haber, COUNT(com_nrocom) as items FROM sgcaf820 WHERE com_nrocom='$pcom_nrocom'";
	$rs=(mysql_query($elsql)) or die ("<p />Estimado usuario $usuario contacte al administrador C�digo 830-1");
	$fila = mysql_fetch_assoc($rs);
	if (mysql_num_rows($rs) > 0) {
		$elsql="UPDATE sgcaf830 SET enc_debe='$fila[debe]', enc_haber='$fila[haber]', enc_item='$fila[items]',enc_fecha='$pcom_fecha' WHERE enc_clave = '$pcom_nrocom'";
// 		echo $elsql;
		$rs=(mysql_query($elsql)) or die ("<p />Estimado usuario $usuario contacte al administrador C�digo 830-2<br>".$sql);
	}
	// actualizar los niveles en la 810
	$losniveles = mysql_query("SELECT * FROM sgcafniv order by con_nivel"); 
	if (mysql_num_rows($losniveles) == 0) {
		die("<p /><br /><p />No se han definido los niveles<span class='b'> error Niv-1</span> en la tabla");
		exit;
	}
	
	$elmes=strtotime($pcom_fecha);
	$elmes=date("m", $elmes);
	$primero=strlen($elmes);
//	echo $pcom_fecha.'-'.$elmes . '-'.$primero;
	if (($elmes < 10) and ($primero < 2)) $elmes='0'.$elmes;
	for ($i = mysql_num_rows($losniveles) - 1; $i >= 0; $i--) {
    	if (!mysql_data_seek($losniveles, $i)) {
	        echo "Cannot seek to row $i: " . mysql_error() . "\n";
	        continue;
    	}
	    if (!($niveles = mysql_fetch_assoc($losniveles))) {
	        continue;
	    }

		$fila = $niveles ;
		$elnivel=$fila['con_nivel'];
		$codigo=substr($pcom_cuenta,0,$elnivel);
		$debito='cue_deb'.$elmes;
		$credito='cue_cre'.$elmes;
		$eldebe=$pcom_monto1;
		$elhaber=$pcom_monto2;
		$sql="update sgcaf810 set $debito=$debito+'$eldebe', $credito=$credito+'$elhaber' where cue_codigo='$codigo'";
//		echo $sql."<br>";
		$result = mysql_query($sql) or die('Error en la F810-3 '.$sql.' '.mysql_error()); 		
	}
}

function exporta($result)
{
$f = fopen("datos.csv","w");
$sep = ";";

mysql_data_seek($result,0);
	while($reg = mysql_fetch_array($result) ) {
		$linea = $reg['cue_codigo'] . $sep . $reg['cue_nombre'] . $sep . $reg['cue_saldo']. $sep . $reg['danterior']. $sep . $reg['hanterior']. $sep . $reg['debe']. $sep . $reg['haber']; //pones cada campo separado con $sep.
	fwrite($f,$linea);
	}
fclose($f); 
$fichero = "./datos.csv";
header("Content-Description: File Transfer");
header( "Content-Disposition: filename=".basename($fichero) );
header("Content-Length: ".filesize($fichero));
header("Content-Type: application/force-download");
@readfile($fichero);
}

function ahorros($cedula)
{	
	$ahorros=0;
	$sql="select * from sgcaf200 where ced_prof='".$cedula."'";
	$resultado2=mysql_query($sql);
	$fila2 = mysql_fetch_assoc($resultado2);
	$ahorros=$fila2['hab_f_prof']+$fila2['hab_f_empr']+$fila2['hab_f_extr']+$fila2['hab_opsu'];
	return $ahorros;
}
function afectan($cedula)
{
	$afectan=0;
	$micedula=substr($cedula,0,4).'.'.substr($cedula,4,3).'.'.substr($cedula,7,4);
	$sql="select sum(monpre_sdp-monpag_sdp) as saldo from sgcaf360,sgcaf310 where (cedsoc_sdp='$micedula') and (codpre_sdp=cod_pres) and  (retab_pres= 1) and (stapre_sdp='A') and (renovado=0) group by cedsoc_sdp ";
//	echo $sql;
	$resultado2=mysql_query($sql);
	if (mysql_num_rows($resultado2) > 0)
	{
		$fila2 = mysql_fetch_assoc($resultado2);
		$afectan=$fila2['saldo'];
	}
	return $afectan;
}

function noafectan($cedula)
{
	$noafectan=0;
	$micedula=substr($cedula,0,4).'.'.substr($cedula,4,3).'.'.substr($cedula,7,4);
	$sql="select sum(monpre_sdp-monpag_sdp) as saldo from sgcaf360,sgcaf310 where (cedsoc_sdp='$micedula') and (codpre_sdp=cod_pres) and  (retab_pres= 0) and (stapre_sdp='A') and (renovado=0)  group by cedsoc_sdp ";
	$resultado2=mysql_query($sql);
	if (mysql_num_rows($resultado2) > 0)
	{
		$fila2 = mysql_fetch_assoc($resultado2);
		$noafectan=$fila2['saldo'];
//		echo $sql;
	}
	return $noafectan;
}

function fianzas($elcodigo)
{
	$fianzas=0;
	$sql="select (monto_fia-monlib_fia) as saldo from sgcaf320 where codfia_fia = '$elcodigo' and (tipmov_fia = 'F') group by codsoc_fia ";
	$resultado2=mysql_query($sql);
	if (mysql_num_rows($resultado2) > 0)
	{
		$fila2 = mysql_fetch_assoc($resultado2);
		$fianzas=$fila2['saldo'];
	}
	return $fianzas;
}

function disponibilidad($ahorros,$afectan,$noafectan,$fianzas)
{
	$sql="select por_dispon from sgcaf100 limit 0,1 ";
	$resultado2=mysql_query($sql);
	$porcentaje=20;
	if (mysql_num_rows($resultado2) > 0)
	{
		$fila2 = mysql_fetch_assoc($resultado2);
		$porcentaje=$fila2['por_dispon'];
	}
	$porcentaje=100-$porcentaje;
	$porcentaje/=100;
	return (($ahorros*$porcentaje)-($afectan+$fianzas))	;
}

function ceroizq($laultima,$digitos)
{
	$tamano=$digitos-strlen($laultima);
	$nuevacadena="";
	// echo $tamano;
	// (5-$tamano)=$posicion)
	for ($posicion=1;$posicion <= $tamano;$posicion++) {
		$nuevacadena=$nuevacadena."0"; 
		// echo $nuevacadena."-";
		}
		// echo $nuevacadena."---------".$laultima;
	$nuevacadena=$nuevacadena.$laultima;
	// echo $nuevacadena;
	return $nuevacadena;
		
}


function cedad($fncido)
{

     $fdhoy = explode("@", date('d@m@Y'));
     $fpncido = explode('/', $fncido);

     if($fdhoy[1] == $fpncido[1])
     {
          if($fdhoy[0] >= $fpncido[0])
          {
               $edad = $fdhoy[2] - $fpncido[2];
          }else{
               $edad = $fdhoy[2] - $fpncido[2] - 1;
          }
     }elseif($fdhoy[1] <= $fpncido[1])
     {
          $edad = $fdhoy[2] - $fpncido[2] - 1;
     }elseif($fdhoy[1] > $fpncido[1])
     {
          $edad = $fdhoy[2] - $fpncido[2];
     }

     return $edad . ' a�os ';
}

function dias_pasados($fechai, $fechaf)
{
	$fi = explode('-', $fechai);
	$ff = explode('-', $fechaf);
	$anoi=$fi[0];
	$mesi=$fi[1];
	$diai=$fi[2];
	$anof=$ff[0];
	$mesf=$ff[1];
	$diaf=$ff[2];
//	echo 'la fecha'.$anoi.$mesi.$diai.'<br>';
	//calculo timestam de las dos fechas
	$timestamp1 = mktime(0,0,0,$mesi,$diai,$anoi);
	$timestamp2 = mktime(0,0,0,$mesf,$diaf,$anof); 
	//resto a una fecha la otra
	$segundos_diferencia = $timestamp1 - $timestamp2;
	//echo $segundos_diferencia;
	//convierto segundos en d�as
	$dias_diferencia = $segundos_diferencia / (60 * 60 * 24); 
	//obtengo el valor absoulto de los d�as (quito el posible signo negativo)
	$dias_diferencia = abs($dias_diferencia);
	//quito los decimales a los d�as de diferencia
	$dias_diferencia = floor($dias_diferencia); 
	return $dias_diferencia;
}

/*! 
  @function num2letras () 
  @abstract Dado un n?mero lo devuelve escrito. 
  @param $num number - N?mero a convertir. 
  @param $fem bool - Forma femenina (true) o no (false). 
  @param $dec bool - Con decimales (true) o no (false). 
  @result string - Devuelve el n?mero escrito en letra. 

*/ 
function num2letras($num, $fem = true, $dec = true) { 
//if (strlen($num) > 14) die("El n?mero introducido es demasiado grande"); 
   $matuni[2]  = "dos"; 
   $matuni[3]  = "tres"; 
   $matuni[4]  = "cuatro"; 
   $matuni[5]  = "cinco"; 
   $matuni[6]  = "seis"; 
   $matuni[7]  = "siete"; 
   $matuni[8]  = "ocho"; 
   $matuni[9]  = "nueve"; 
   $matuni[10] = "diez"; 
   $matuni[11] = "once"; 
   $matuni[12] = "doce"; 
   $matuni[13] = "trece"; 
   $matuni[14] = "catorce"; 
   $matuni[15] = "quince"; 
   $matuni[16] = "dieciseis"; 
   $matuni[17] = "diecisiete"; 
   $matuni[18] = "dieciocho"; 
   $matuni[19] = "diecinueve"; 
   $matuni[20] = "veinte"; 
   $matunisub[2] = "dos"; 
   $matunisub[3] = "tres"; 
   $matunisub[4] = "cuatro"; 
   $matunisub[5] = "quin"; 
   $matunisub[6] = "seis"; 
   $matunisub[7] = "sete"; 
   $matunisub[8] = "ocho"; 
   $matunisub[9] = "nove"; 

   $matdec[2] = "veint"; 
   $matdec[3] = "treinta"; 
   $matdec[4] = "cuarenta"; 
   $matdec[5] = "cincuenta"; 
   $matdec[6] = "sesenta"; 
   $matdec[7] = "setenta"; 
   $matdec[8] = "ochenta"; 
   $matdec[9] = "noventa"; 
   $matsub[3]  = 'mill'; 
   $matsub[5]  = 'bill'; 
   $matsub[7]  = 'mill'; 
   $matsub[9]  = 'trill'; 
   $matsub[11] = 'mill'; 
   $matsub[13] = 'bill'; 
   $matsub[15] = 'mill'; 
   $matmil[4]  = 'millones'; 
   $matmil[6]  = 'billones'; 
   $matmil[7]  = 'de billones'; 
   $matmil[8]  = 'millones de billones'; 
   $matmil[10] = 'trillones'; 
   $matmil[11] = 'de trillones'; 
   $matmil[12] = 'millones de trillones'; 
   $matmil[13] = 'de trillones'; 
   $matmil[14] = 'billones de trillones'; 
   $matmil[15] = 'de billones de trillones'; 
   $matmil[16] = 'millones de billones de trillones'; 

   $num = trim((string)@$num); 
   if ($num[0] == '-') { 
      $neg = 'menos '; 
      $num = substr($num, 1); 
   }else 
      $neg = ''; 
   while ($num[0] == '0') $num = substr($num, 1); 
   if ($num[0] < '1' or $num[0] > 9) $num = '0' . $num; 
   $zeros = true; 
   $punt = false; 
   $ent = ''; 
   $fra = ''; 
   for ($c = 0; $c < strlen($num); $c++) { 
      $n = $num[$c]; 
      if (! (strpos(".,'''", $n) === false)) { 
         if ($punt) break; 
         else{ 
            $punt = true; 
            continue; 
         } 

      }elseif (! (strpos('0123456789', $n) === false)) { 
         if ($punt) { 
            if ($n != '0') $zeros = false; 
            $fra .= $n; 
         }else 

            $ent .= $n; 
      }else 

         break; 

   } 
   $ent = '     ' . $ent; 
   if ($dec and $fra and ! $zeros) { 
      $fin = ' coma'; 
      for ($n = 0; $n < strlen($fra); $n++) { 
         if (($s = $fra[$n]) == '0') 
            $fin .= ' cero'; 
         elseif ($s == '1') 
            $fin .= $fem ? ' una' : ' un'; 
         else 
            $fin .= ' ' . $matuni[$s]; 
      } 
   }else 
      $fin = ''; 
   if ((int)$ent === 0) return 'Cero ' . $fin; 
   $tex = ''; 
   $sub = 0; 
   $mils = 0; 
   $neutro = false; 
   while ( ($num = substr($ent, -3)) != '   ') { 
      $ent = substr($ent, 0, -3); 
      if (++$sub < 3 and $fem) { 
         $matuni[1] = 'una'; 
         $subcent = 'os' ; // 'as'; 
      }else{ 
         $matuni[1] = $neutro ? 'un' : 'uno'; 
         $subcent = 'os'; 
      } 
      $t = ''; 
      $n2 = substr($num, 1); 
      if ($n2 == '00') { 
      }elseif ($n2 < 21) 
         $t = ' ' . $matuni[(int)$n2]; 
      elseif ($n2 < 30) { 
         $n3 = $num[2]; 
         if ($n3 != 0) $t = 'i' . $matuni[$n3]; 
         $n2 = $num[1]; 
         $t = ' ' . $matdec[$n2] . $t; 
      }else{ 
         $n3 = $num[2]; 
         if ($n3 != 0) $t = ' y ' . $matuni[$n3]; 
         $n2 = $num[1]; 
         $t = ' ' . $matdec[$n2] . $t; 
      } 
      $n = $num[0]; 
      if ($n == 1) { 
         $t = ' ciento' . $t; 
      }elseif ($n == 5){ 
         $t = ' ' . $matunisub[$n] . 'ient' . $subcent . $t; 
      }elseif ($n != 0){ 
         $t = ' ' . $matunisub[$n] . 'cient' . $subcent . $t; 
      } 
      if ($sub == 1) { 
      }elseif (! isset($matsub[$sub])) { 
         if ($num == 1) { 
            $t = ' mil'; 
         }elseif ($num > 1){ 
            $t .= ' mil'; 
         } 
      }elseif ($num == 1) { 
         $t .= ' ' . $matsub[$sub] . '?n'; 
      }elseif ($num > 1){ 
         $t .= ' ' . $matsub[$sub] . 'ones'; 
      }   
      if ($num == '000') $mils ++; 
      elseif ($mils != 0) { 
         if (isset($matmil[$sub])) $t .= ' ' . $matmil[$sub]; 
         $mils = 0; 
      } 
      $neutro = true; 
      $tex = $t . $tex; 
   } 
   $tex = $neg . substr($tex, 1) . $fin; 
   return ucfirst($tex); 
}
function suma_fechas($fecha,$ndias)
{
if (preg_match("/[0-9]{1,2}\/[0-9]{1,2}\/([0-9][0-9]){1,2}/",$fecha))
list($dia,$mes,$a�o)=split("/", $fecha);
if (preg_match("/[0-9]{1,2}-[0-9]{1,2}-([0-9][0-9]){1,2}/",$fecha))
list($dia,$mes,$a�o)=split("-",$fecha);
$nueva = mktime(0,0,0, $mes,$dia,$a�o) + $ndias * 24 * 60 * 60;
$nuevafecha=date("d/m/Y",$nueva);
return ($nuevafecha);
}

function restar_fechas($fecha,$ndias)
{
if (preg_match("/[0-9]{1,2}\/[0-9]{1,2}\/([0-9][0-9]){1,2}/",$fecha))
list($dia,$mes,$a�o)=split("/", $fecha);
if (preg_match("/[0-9]{1,2}-[0-9]{1,2}-([0-9][0-9]){1,2}/",$fecha))
list($dia,$mes,$a�o)=split("-",$fecha);
$nueva = mktime(0,0,0, $mes,$dia,$a�o) - $ndias * 24 * 60 * 60;
$nuevafecha=date("d/m/Y",$nueva);
return ($nuevafecha);
}

function restaradministrativos($montoprestamo)
{
	$sql_deduccion="select * from sgcaf311 where activar = 1";
	$a_deduccion=mysql_query($sql_deduccion);
	$d_obligatorias=0;
	while($r_deduccion=mysql_fetch_assoc($a_deduccion)) {
		if ($r_deduccion['porcentaje'] == 0)
			$monto_deduccion=$r_deduccion['monto'];
		else $monto_deduccion=($montoprestamo)*($r_deduccion['porcentaje']/100);
		$d_obligatorias+=$monto_deduccion;
		}
	return $d_obligatorias;
}
	
function cal_int($interes,$mcuotas,$mmonpre_sdp,$factor_divisible = 12,$z=0,&$i2)
{
	if ($interes > 0) {
		$i = ((($interes / 100)) / $factor_divisible);
		$i2 = $i;
/*
		$sql="CREATE TEMPORARY TABLE interes_temp (
		elinteres decimal (12,8) NOT NULL default 0.00
      ) ;";
		$rsql=mysql_query($sql);
// 		$asql=mysql_fetch_assoc($rsql);
		$sql="insert into interes_temp (elinteres) values ('$i2');";
		$rsql=mysql_query($sql);
// 		$asql=mysql_fetch_assoc($rsql);
*/		
//		echo ' lo que trae i2 '.$i2.'<br>';
		$i_ = 1 + $i;
		$i_ = pow($i_,$mcuotas); 	// exponenciacion 
		$i_ = 1 / $i_;
		$i__ = 1 - $i_;
		$i___ = $i / $i__;
		$z = $mmonpre_sdp * $i___;
	}
	if ($interes ==0)
		$z = $mmonpre_sdp / $mcuotas;
/*
	    ((1 + i)^n) - 1
	i =-----------------
	           i
*/
	return $z;
}


function actualizar_fiador($socio,$monto,$prestamo)
{
	if ($prestamo!='x')
		$sql="select * from sgcaf320 where nropre_fia='$prestamo' and tipmov_fia='F'";
	else $sql="select * from sgcaf320 where codsoc_fia='$socio' and tipmov_fia='F'";
	$resultado = mysql_query($sql) or die('1.-'.mysql_error());
	while ($registro = mysql_fetch_assoc($resultado)) {
		$aliberar = $monto * ($registro['porafi_fia'] / 100);
		$liberado = $registro['monlib_fia'] + $aliberar;
		$actualizar="update sgcaf320 set monlib_fia = $liberado' ";
		if ($liberado >= $registro['monto_fia'])
			$actualizar .= ", tipmov_fia = 'L' ";
		$actualizar.= " where registro = ".$registro['registro'];
		$resulta2=mysql_query($actualizar) or die('2.-'.mysql_error());
	}
}

function existe_cuenta($codigo)
{
	$sql="select cue_codigo from sgcaf810 where cue_codigo='$codigo'";
	$result = mysql_query($sql) or die('Error en la F810-4 '.$sql.' '.mysql_error()); 		
	if ((mysql_num_rows($result) < 1))
	{ 	// no existe la cuenta y la creo
		// busco el socio
		$socio=explode('-',$codigo);
		$socio='0'.$socio[6];
		$sql="select ape_prof, nombr_prof from sgcaf200 where cod_prof='$socio'";
		$result = mysql_query($sql) or die('Error en la F200-1 '.$sql.' '.mysql_error()); 
		$rsocio=mysql_fetch_assoc($result);
		$nombre=trim($rsocio['ape_prof']). ' '.$rsocio['nombr_prof'];
		$sql="insert into sgcaf810 (cue_codigo, cue_nombre, cue_nivel, cue_saldo) values ('$codigo', '$nombre', '7', 0)";
		$result = mysql_query($sql) or die('Error en la F810-5 '.$sql.' '.mysql_error()); 
	}
}

/* *** CABECERA LISTADO ASIENTOS HISTORICOS *** */

function cabasi_his($edborr) {

echo "<tr>";

//if ($edborr) {echo "<th width='200' colspan=0></th>";}

echo '<th width="100">Cuenta</th><th width="200">Descripci�n</th><th width="200">Concepto</th><th width="50">Referencia</th><th width="100">Debe</th><th width="100">Haber</th></tr>';
// <th width="100">Descuadre</th></tr>';

}

/* *** LISTAR ASIENTO HISTORICO *** */

function asiento_his($asiento, $edborr, $por, $deci, $bojust) {

// if ($bojust == $asiento) {mysql_query("UPDATE asientos SET fich = '', tipofich='' WHERE asiento = '$asiento'");}

$result = mysql_query("SELECT * FROM histf830 WHERE enc_clave = $asiento");
if ($result) {$fichero = mysql_fetch_array($result);}

$cols = 4;

if ($edborr) {$cols = $cols+2;}

$asi = $fichero; // mysql_fetch_array($result);
$a=explode("-",$asi["enc_fecha"]);
echo "<tr><td class='blanco b' colspan='$cols'>Asiento: ".$asiento."</a> Fecha: ";
echo $a[2]."/".$a[1]."/".$a[0]; // substr($a[0],2,2);
echo "</b>";
//if ($fichero[0]) {echo "&nbsp;&nbsp;&nbsp;&nbsp;<a href='imagen.php?asiento=$asiento' target='_blank'>Ver Soporte</a>&nbsp;&nbsp;&nbsp;<a href='editasi2.php?asiento=$asiento&bojust=$asiento' onclick='return borrar_justificante()'>Borrar Soporte</a>";}

/*
if (trim($fichero[1])) {
	echo "&nbsp;&nbsp;&nbsp;<a onclick=\"amplred('div$asiento')\">Explicaci�n (ver/ocultar)</a>";
	echo "<div id='div$asiento' style='display:none'>".$fichero[1]."</div>";
}
*/
// echo "<a href='javascript:print()'>  Imprimir</a>";
//echo "<a href='impcom.php?asiento=".$asiento."'>  Imprimir</a>";

echo "</td></tr>";

$comando="SELECT nro_registro, com_cuenta, com_descri, com_monto1, com_monto2, com_refere FROM histf820 WHERE com_nrocom = '$asiento' ORDER BY com_debcre, com_cuenta, com_refere";
$result = mysql_query($comando);
// echo $comando;

while ($fila = mysql_fetch_array($result)) {

	echo "<tr>";
	if ($edborr) {
/*
		echo "<td><a href='editasi2.php?row_id=".$fila[0]."&asiento=$asiento&accion=editapu' target='_self'>Editar</a></td>";
		echo "<td><a href='editasi2.php?row_id=".$fila[0]."&asiento=$asiento&accion=boapu' onclick='return borrar_apunte()'>Borrar</a></td>";
		*/

//		echo "<td><a href='editasi2.php?row_id=".$fila[0]."&asiento=$asiento&accion=editapu' target='_self'> <img src='imagenes/16-em-pencil.png' width='16' height='16' border='0' title='Editar' alt='Editar' /></a></td>";
//		echo "<td><a href='editasi2.php?row_id=".$fila[0]."&asiento=$asiento&accion=boapu' onclick='return borrar_reg_820()'><img src='imagenes/16-em-cross.png' width='16' height='16' border='0' title='Eliminar'  alt='Eliminar' /></a></td>";
	}
	echo "<td width='100'>".$fila["com_cuenta"]."</a></td>";
	
	$sqlcuenta="SELECT cue_nombre FROM histf810 where cue_codigo = '".$fila["com_cuenta"]."'"; // revisar
	$rs=mysql_query($sqlcuenta) or die ("<p />Estimado usuario $usuario contacte al administrador C�digo 810-1");
	$filacuenta = mysql_fetch_array($rs);
	echo "<td width='200'>".$filacuenta["cue_nombre"]."</td>";
	echo "<td width='200'>".$fila["com_descri"]."</td>";
	echo "<td width='50'>".$fila["com_refere"]."</td><td width='100' class='dcha'>";
	
	if ($fila["com_monto1"] == 0)
	{
		echo "&nbsp;";
	} else {
		echo number_format($fila["com_monto1"]*$por,$deci,'.',',');
	}

	echo "</td><td class='dcha'>";
	
	
	if ($fila["com_monto2"] == 0)
	{
		echo "&nbsp;";
	} else {
		echo number_format($fila["com_monto2"]*$por,$deci,'.',',');
	}
	echo "</td></tr>";

}

$elmonto=$asi['enc_debe']-$asi['enc_haber'];
if ($asi['enc_debe']-$asi['enc_haber'] != 0) {
	echo "<tr><td class='rojo dcha b' colspan=".($cols-2);
	echo "<span class='blanco b'> Diferencia de ".number_format(($asi['enc_debe']-$asi['enc_haber'])*$por,$deci,',','.')."</span>";
}
else 
	echo "<tr><td class='blanco dcha b' colspan=".($cols-2).">";
echo "  SubTotales: </td><td class='blanco dcha b'>".number_format($asi['enc_debe']*$por,$deci,',','.')."</td><td class='blanco dcha b'>".number_format($asi['enc_haber']*$por,$deci,',','.')."</td>";

echo "</tr><tr><td colspan='$cols' class='verde'>&nbsp;</td></tr>
<p>";
  
  }
  
 /* *** ACTUALIZAR UNO O TODOS LOS ASIENTOS HISTORICOS ****************************** */
  
  function totalapu_his($asiento) {
  
  if ($asiento) {
		$where = "WHERE com_nrocom = '$asiento'";
	}
  
  $rs = mysql_query("SELECT com_nrocom from histf820 $where");
  
  while ($fila = mysql_fetch_array($rs)) :
  
  $a = $fila[0];
  
  $rs1=mysql_query("SELECT SUM(com_monto1) AS tot_debe, SUM(com_monto2) AS tot_haber, COUNT(com_nrocom) as tot_items FROM histf820 WHERE com_nrocom = '$a'");
 $fila1 = mysql_fetch_array($rs1);
  
	  mysql_query("UPDATE histf830 SET enc_debe = '$fila1[0]', enc_haber = '$fila1[1]', enc_item = '$fila[2]' WHERE enc_clave = '$a'");
  
  endwhile;
  
  return ($fila1[0] - $fila1[1]);
  
  }
  
function numero_prestamo($micedula, $laparte)
{
	// determino nuevo numero de prestamo
//	$sql_310="select nropre_sdp from sgcaf310 where (cedsoc_sdp='$micedula') and (substr(nropre_sdp,1,5)='$laparte') order by nropre_sdp desc limit 1";
//	echo $sql_310;
	$sql_310="select count(nropre_sdp) as cantidad from sgcaf310 where (cedsoc_sdp='$micedula') group by cedsoc_sdp";
//	echo $sql_310;
	$a_310=mysql_query($sql_310);
	$elnumero=mysql_fetch_assoc($a_310);
	$elnumero=$elnumero['cantidad'];
//	$elnumero=substr($elnumero['nropre_sdp'],5,3);
echo 'este es el '.$elnumero;
	$buscarhasta=500;
//	for ($unnumero=$elnumero; $unnumero < $buscarhasta; $unnumero++)
	for ($unnumero=$elnumero; $unnumero < ($elnumero+$buscarhasta); $unnumero++)
	{
		$elnumero=$elnumero+1;
		$lnumero=$laparte.ceroizq($elnumero,3);
		echo 'Buscando '.$lnumero;
		$sql_310="select * from sgcaf310 where (nropre_sdp='$lnumero')";
		$a_310=mysql_query($sql_310);
		if ((mysql_num_rows($a_310) < 1))
		{
			$unnumero = $buscarhasta+1;
			break;
		}
//		else echo ' ya existe<br>';
	}
	$elnumero=$lnumero;
	// fin de generar nuevo numero
	return $elnumero;
	}

	function convertir_monto_usd_bs($monto, $nropre, $cedula) {
	$sql = "SELECT idtasa FROM sgcaf310 WHERE nropre_sdp = '$nropre' AND cedsoc_sdp = '$cedula' LIMIT 1";
	$res = mysql_query($sql);
	if ($res && mysql_num_rows($res) > 0) {
	$row = mysql_fetch_assoc($res);
	$idtasa = $row['idtasa'];
	if ($idtasa > 0) {
	$sql_tasa = "SELECT montobs FROM sgcatasa WHERE id = '$idtasa' LIMIT 1";
	$res_tasa = mysql_query($sql_tasa);
	if ($res_tasa && mysql_num_rows($res_tasa) > 0) {
	$row_tasa = mysql_fetch_assoc($res_tasa);
	return $monto * $row_tasa['montobs'];
	}
	}
	}
	return $monto;
	}
	?>