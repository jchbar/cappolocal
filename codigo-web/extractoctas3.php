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
?>
<script language="Javascript" src="selec_fecha.js" type='text/javascript'></script>
<?php

if (!$link OR !$_SESSION['empresa']) {
	include("noempresa.php");
	exit;
}
?>

<body <?php if (!$cuenta AND !$bloqueo) {echo "onload=\"foco('cuenta')\"";}?>>

<?php
include("arriba.php");
$menu14=3;include("menusizda.php");

if (!$cuenta) {

	echo "<form enctype='multipart/form-data' method='post' name='form1'>Cuenta: ";
//	echo "<input type='text' name='cuenta' size='20' maxlength='20'> \n";
?>
	<input type="text" size="30" tabindex="1" name="cuenta" id="inputString" onKeyUp="lookup(this.value);" onBlur="fill();" value ="<?php echo $cuenta;?>" autocomplete="off"/>
			<div class="suggestionsBox" id="suggestions" style="display: none;">
				<img src="upArrow.png" style="position: relative; top: -12px; left: 70px; "  alt="upArrow" />
				<div class="suggestionList" id="autoSuggestionsList">
				</div>
			</div>
		</div> 

<br>
<script type="text/javascript">
// setActiveStyleSheet(this, 'green');
setActiveStyleSheet(document.getElementById("defaultTheme"), "green");
</script>
	Desde:</b> <input type="text" name="date3" id="sel3" size="12" readonly
><input type="reset" value=" ... "
onclick="return showCalendar('sel3', '%d/%m/%Y');"><br />

<script type="text/javascript">
// setActiveStyleSheet(this, 'green');
setActiveStyleSheet(document.getElementById("defaultTheme"), "green");
</script>
<br>
	Hasta:</b> <input type="text" name="date4" id="sel4" size="12" readonly
><input type="reset" value=" ... "
onclick="return showCalendar('sel4', '%d/%m/%Y');"><br />


<?php 
//	solicitar_fechas();
	
	echo "<input type='submit' value='Buscar/Generar'></form> \n";
	include("pie.php");
	echo "</div></body></html>";
	exit;

}

$datos1= 'si';
if ($datos AND $datos = 'no') {
	$datos1 = '';
}

// $result = mysql_query("SELECT DISTINCT cue_codigo, cue_nombre, cue_saldo FROM sgcaf810 WHERE cue_codigo = '$cuenta'"); 
$result = mysql_query("SELECT DISTINCT * FROM sgcaf810 WHERE cue_codigo = '$cuenta'"); 


// $elsql="call sp_qry_cuenta('".$cuenta."')";
// $result = mysql_query($elsql); 

if (mysql_num_rows($result) == 0) {

		echo "<p /><br /><p />No existe el Nº de Cuenta <span class='b'>$cuenta</span> en la tabla Cuentas.";
		exit;

}
/*
echo $_POST['date3'];
echo $_POST['date4'];
*/
$fechai=$_POST['date3'];
$fechaf=$_POST['date4'];
$cuento=($fechai?' Desde '.$fechai.' Hasta '.$fechaf:'');
while ($fila = mysql_fetch_assoc($result)) :

	if ($fila[cue_codigo] = $cuenta) {
//		echo "<h2>Cuenta: ".$cuenta." ".$fila[cue_nombre].$cuento." <a href='javascript:print()'>  Imprimir</a>"."</h2>";
		echo "<h2>Cuenta: ".$cuenta." ".$fila[cue_nombre].$cuento." ";
	echo "<a target=\"_blank\" href=\"mayorpdf.php?cuenta=$cuenta&fechai=$fechai&fechaf=$fechaf&encero=$encero&\" onClick=\"info.html\', \'\',\'width=250, height=190\')\">Imprimir </a>"."</h2>"; 
		break;
	}

endwhile;

// $result = mysql_query("SELECT *, subcuent.cuenta, subcuent.saldod, subcuent.saldoa FROM apuntes LEFT JOIN subcuent ON apuntes.cuenta = subcuent.cuenta WHERE apuntes.cuenta LIKE '$cuenta%' ORDER by fecha"); 
// $result = mysql_query("SELECT *, sgcaf810.cue_codigo, sgcaf810.cue_nombre, sgcaf810.cue_saldo FROM sgcaf820 LEFT JOIN cue_codigo ON sgcaf820.com_cuenta = sgcaf810.cue_codigo WHERE sgcaf820.com_cuenta LIKE '$cuenta%' ORDER by com_fecha"); 
$misaldo=calcular_saldo($fila,$fechai);
// $result = mysql_query("SELECT *, sgcaf810.cue_codigo, sgcaf810.cue_nombre, sgcaf810.cue_saldo FROM sgcaf820,sgcaf810 WHERE sgcaf820.com_cuenta LIKE '$cuenta' and sgcaf820.com_cuenta = sgcaf810.cue_codigo ORDER by com_fecha,com_refere");
if (!$fechai) 
	$sql="SELECT * FROM sgcaf820 WHERE com_cuenta = '$cuenta' ORDER by com_fecha, com_refere";
else {
	$lfi=convertir_fecha($fechai);
	$lff=convertir_fecha($fechaf);
	$sql="SELECT * FROM sgcaf820 WHERE com_cuenta = '$cuenta' AND ((com_fecha >= '$lfi') AND (com_fecha <= '$lff')) ORDER by com_fecha, com_refere";
	}
//$result = mysql_query($sql);
/*
	revisar este bloque q no funciona
$sql="call sp_qry_mayor01('".$cuenta."')";
echo $sql;
*/
$result = mysql_query($sql);

if (mysql_num_rows($result) == 0) {

	echo "<p /><br /><p />No hay registros para el Nº de Cuenta <span class='b'>$cuenta</span>.";
	exit;

}

/* ****************** CABECERA ************************* */
	
echo "<table class='basica 100 hover' width='850'> \n"; 
// echo '<tr><th width="100">Fecha</th><th width="150">Cuenta</th><th width="100">Asiento</th><th width="400">Concepto</th><th width="150">Referencia</th><th width="150" class="dcha">Debe</th><th width="150">Haber</th><th width="150">Saldo</th> \n';
echo '<tr><th width="40">Item</th><th width="80">Fecha</th><th width="100">Asiento</th><th width="450">Concepto</th><th width="150">Referencia</th><th width="150">Debe</th><th width="150">Haber</th><th width="150">Saldo</th>';
// echo '<tr><th>&nbsp;<th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th><th>';
echo '<tr><td class="dcha b" colspan="7">Saldo Inicial <td class="dcha b" >';
echo number_format($misaldo*$_SESSION['moneda'],$_SESSION['deci'],'.',',');
echo '</th></td>';
$debe=$haber=$item=0;
$debem=$haberm=$mesactual=0;
/* ****************** APUNTES ************************** */
while ($fila = mysql_fetch_array($result)) :

	if ($mesactual!=$a[1]) {
		echo "<tr><td class='blanco dcha b' colspan='5'>Totales Mes : ".$mesactual.'/'.$a[0].' '."</td><td class='blanco dcha b'>".number_format($debem*$_SESSION['moneda'],$_SESSION['deci'],'.',',')."</td><td class='blanco dcha b'>".number_format($haberm*$_SESSION['moneda'],$_SESSION['deci'],'.',',')."</td></tr>\n";
		$mesactual=$a[1];
		$debem=$haberm=0;
	}

	$a=explode("-",$fila["com_fecha"]); 
	if (($mesactual!=$a[1]) && ($mesactual != 0)) {
		echo "<tr><td class='blanco dcha b' colspan='5'>Totales Mes : ".$mesactual.'/'.$a[0].' '."</td><td class='blanco dcha b'>".number_format($debem*$_SESSION['moneda'],$_SESSION['deci'],'.',',')."</td><td class='blanco dcha b'>".number_format($haberm*$_SESSION['moneda'],$_SESSION['deci'],'.',',')."</td></tr>\n";
		$mesactual=$a[1];
		$debem=$haberm=0;
	}

	$item++;
	echo "<tr><td class='centro' >".$item."</td>";
	echo "<td>".$a[2]."/".$a[1]."/".substr($a[0],2,2)."</td>";
	if ($mesactual==0) $mesactual=$a[1];
	echo "<td><a href='editasi2.php?asiento=".$fila["com_nrocom"]."'>". $fila["com_nrocom"]."</a><td>".$fila["com_descri"]."</td>";	
	echo "<td>".$fila["com_refere"]."</td><td class='dcha'>";
	if ($fila["com_monto1"] == 0)
	{
		echo "&nbsp;";
	} else {
		echo number_format($fila["com_monto1"]*$_SESSION['moneda'],$_SESSION['deci'],'.',',');
		$misaldo+=$fila[com_monto1];
		$debe+=$fila[com_monto1];
		$debem+=$fila[com_monto1];
	}
	echo "</td><td class='dcha'>";
	if ($fila["com_monto2"] == 0)
	{
		echo "&nbsp;";
	} else {
		echo number_format($fila["com_monto2"]*$_SESSION['moneda'],$_SESSION['deci'],'.',',');
		$misaldo-=$fila[com_monto2];
		$haber+=$fila[com_monto2];
		$haberm+=$fila[com_monto2];
	}

	echo "</td><td class='dcha'>";
	echo number_format($misaldo,$_SESSION['deci'],'.',',');
	echo "</td></tr> \n";

endwhile;

/* fin de mes */
echo "<tr><td class='blanco dcha b' colspan='5'>Totales Mes : ".$mesactual.'/'.$a[0].' '."</td><td class='blanco dcha b'>".number_format($debem*$_SESSION['moneda'],$_SESSION['deci'],'.',',')."</td><td class='blanco dcha b'>".number_format($haberm*$_SESSION['moneda'],$_SESSION['deci'],'.',',')."</td></tr>\n";

/* ****************** SUMAS Y FIN DE TABLA*************** */


/*
$sql="SELECT SUM(com_monto1) AS tot_debe, SUM(com_monto2) AS tot_haber FROM sgcaf820 WHERE com_cuenta LIKE '$cuenta%'"; 
$result=mysql_query($sql); 
$row=mysql_fetch_array($result);
echo "<tr><td class='blanco dcha b' colspan='4'>SALDO: ".number_format(($row['tot_debe']-$row['tot_haber'])*$_SESSION['moneda'],$_SESSION['deci'],'.',',')."</td><td class='blanco dcha b'>".number_format($row['tot_debe']*$_SESSION['moneda'],$_SESSION['deci'],'.',',')."</td><td class='blanco dcha b'>".number_format($row['tot_haber']*$_SESSION['moneda'],$_SESSION['deci'],'.',',')."</td></tr>\n";
*/
echo "<tr><td class='blanco dcha b' colspan='5'>Totales : "."</td><td class='blanco dcha b'>".number_format($debe*$_SESSION['moneda'],$_SESSION['deci'],'.',',')."</td><td class='blanco dcha b'>".number_format($haber*$_SESSION['moneda'],$_SESSION['deci'],'.',',')."</td></tr>\n";

echo "</table><p /> \n"; 


$codigo='0'.substr($cuenta,-4);
$sql="select ced_prof from sgcaf200 where cod_prof='$codigo'";
// echo $sql;
$result=mysql_query($sql);
$registro=mysql_fetch_assoc($result);
$cedula=$registro['ced_prof'];

echo '<br>Numero de cedula '.$cedula;


include("pie.php");?></body></html>
