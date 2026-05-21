<?php
include("head.php");
include("paginar.php");

if (!$link OR !$_SESSION['empresa']) {
	include("noempresa.php");
	exit;
}
/*
if ($_GET['emp'] == 1) {$_GET['n'] = 1;}

*/
if ($accion == 'Anadir') 
	$onload="onload=\"foco('lacedula')\""; 
else
	$onload="onload=\"foco('elcodigo')\"";
/*
	//$result = mysql_query("SELECT max(asiento) FROM asientos");
	//$row = mysql_fetch_row($result);
	//$asiento = $row[0] + 1;
	$fila = mysql_fetch_array(mysql_query("SELECT con_compr FROM sgcaf8co"));
	$asiento = $fila[0] + 1;
	mysql_query("UPDATE sgcaf8co SET con_compr = '$asiento' WHERE 1");
	// Tomo el valor de la fecha en que se hizo el último Asiento
	$result = mysql_query("SELECT date_format(con_ultfec,'%d/%m/%y') AS ultfechax FROM sgcaf8co");
	$row = mysql_fetch_array($result);
	$fecha = $row[0];
} else {
	$onload="onload=\"foco('cuenta11')\"";
	$readonly=" readonly='readonly'";
	$asiento = $_POST['asiento'];
	$fecha = $_POST['fecha'];
	$tipo =$_POST['tipo'];
	$referencia =$_POST['referencia'];
}
*/
?>
<script language="Javascript" src="selec_fecha.js" type='text/javascript'></script>
<body <?php if (!$bloqueo) {echo $onload;}?>>


<?php

$readonly=" readonly='readonly'";
include("arriba.php");
$menu61=1;include("menusizda.php");
$cedula = $_GET['cedula'];
$ip = $_SERVER['HTTP_CLIENT_IP'];
if (!$ip) {$ip = $_SERVER['REMOTE_ADDR'];}

if ($accion == 'Buscar')  {
	extract($_POST);
	$elcodigo = trim($_POST['codigo']);
	$lacedula = trim($_POST['cedula']);
	echo $lacedula. ' - ' .$elcodigo . ' - '.$accion;
	if ($elcodigo) { //  != ' ') {
	    $sql="SELECT * FROM sgcaf200 where cod_prof = '$elcodigo'";
		$result=mysql_query($sql);
		$row= mysql_fetch_assoc($result);
		echo "<input type = 'hidden' value ='".$row['cod_prof']."' name='codigo'>"; 
		$cedula=$row['ced_prof'];
		$accion = 'Editar'; } 
	else if ($lacedula) { 
		$cedula=$lacedula;
		$sql="SELECT * FROM sgcaf200 where ced_prof = '$lacedula'";
		$result=mysql_query($sql);
		$row= mysql_fetch_assoc($result);
		echo "<input type = 'hidden' value ='".$row['ced_prof']."' name='cedula'>"; 
		$cedula=$row['ced_prof'];
		$accion = 'Editar'; }
		else $accion = '';
}
if ($accion == 'Anadir1') {
	extract($_POST);
	$codigo = $_POST['elcodigo'];
	$mon_ret = $_POST['mon_ret'];
	$mon_pre = $_POST['mon_pre'];
	$facta=convertir_fecha($_POST['date3']);
	$fdcto=convertir_fecha($_POST['date4']);
	$fdepo=convertir_fecha($_POST['date5']);
	$f1es=convertir_fecha($_POST['date6']);
	$f2es=convertir_fecha($_POST['date7']);
	$f3es=convertir_fecha($_POST['date8']);
	// revisar antes de incluir
	// 1era fecha 
	$primera = $segunda = $tercera = 0;
	$sql="select codpre_sdp, count(codpre_sdp) as cuantos from sgcaf310 where f_1cuo_sdp='$f1es' group by codpre_sdp ";
	$result=mysql_query($sql);
	$cuantos=mysql_fetch_assoc($result);
	$cuantos=$cuantos['cuantos'];
// 	echo $sql;
	if ($cuantos > 0)
		$primera = 1;
	
	$sql="select codpre_sdp, count(codpre_sdp) as cuantos from sgcaf310 where f_1cuo_sdp='$f2es' group by codpre_sdp ";
//	echo $sql;
	$result=mysql_query($sql);
	$cuantos=mysql_fetch_assoc($result);
	$cuantos=$cuantos['cuantos'];
	if ($cuantos > 0)
		$segunda=1;

	$sql="select codpre_sdp, count(codpre_sdp) as cuantos from sgcaf310 where f_1cuo_sdp='$f3es' group by codpre_sdp ";
//	echo $sql;
	$result=mysql_query($sql);
	$cuantos=mysql_fetch_assoc($result);
	$cuantos=$cuantos['cuantos'];
	if ($cuantos > 0)
		$tercera=1;

	$incluir=0;
	if (($primera == 0) and ($segunda == 0) and ($tercera == 0))
		$incluir=1;

	// fin revisar antes de incluir
	if ($incluir == 1)
	{
		$sql="INSERT INTO sgcafact (acta, fecha, mon_ret, mon_pre, eje_ret, eje_pre, f_dcto, f_deposito, especial) 
			VALUES ('$elcodigo', '$facta', $mon_ret, $mon_pre, 0, 0, '$fdcto', '$fdepo', 0)";
		$result=mysql_query($sql);
		$sql="INSERT INTO sgcafact (acta, fecha, mon_ret, mon_pre, eje_ret, eje_pre, f_dcto, f_deposito, especial) 
			VALUES ('$elcodigo', '$facta', $mon_ret, $mon_pre, 0, 0, '$f1es', '$fdepo', 1)";
		$result=mysql_query($sql);
		$sql="INSERT INTO sgcafact (acta, fecha, mon_ret, mon_pre, eje_ret, eje_pre, f_dcto, f_deposito, especial) 
			VALUES ('$elcodigo', '$facta', $mon_ret, $mon_pre, 0, 0, '$f2es', '$fdepo', 1)";
		$result=mysql_query($sql);
		$sql="INSERT INTO sgcafact (acta, fecha, mon_ret, mon_pre, eje_ret, eje_pre, f_dcto, f_deposito, especial) 
			VALUES ('$elcodigo', '$facta', $mon_ret, $mon_pre, 0, 0, '$f3es', '$fdepo', 1)";
		$result=mysql_query($sql);
		$accion="";
	}
	else {
		echo 'No se puede incluir esta acta, la(s) fecha(s) especiales <br>';
		echo ($primera == true?$_POST['date6']:'').'<br>';
		echo ($segunda == true?$_POST['date7']:'').'<br>';
		echo ($tercera == true?$_POST['date8']:'').'<br>';
		echo ' estan ocupadas<br>';
		$accion="Anadir";
	}
}

if ($accion == 'Editar1') {
	extract($_POST);
	$codigo = $_POST['elcodigo'];
	$laletraced = $_POST['laletraced'];
	$num = 1;
	$lafechaingresomysql=convertir_fecha($lafechaingreso);
	$fejubilacionmysql=convertir_fecha($fejubilacion);
	$lafechanacmysql=convertir_fecha($lafechanac);
	$fing_uclamysql=convertir_fecha($fing_ucla);
	$lafecharetiromysql=convertir_fecha($lafecharetiro);
	$lacedula=$laletraced.'-'.$lacedula;
	$eltelefonoh=$elareatelefonoh.$eltelefonoh ;
	$elcelular1=$elareacelular1.$elcelular1;
	$elcelular2=$elareacelular2.$elcelular2;
	$eltelefonot=$elareatelefonot.$eltelefonot;
	$elfax=$elareafax.$elfax;
	if ($elemail) {
		$sql = "SELECT * FROM sgcaf200 WHERE mail_prof = '$elemail'";
		$result=mysql_query($sql);
		if (mysql_num_rows($result) > 1)
			die ('No se puede asignar esta dirección de email, ya esta registrada ');
		while($row=mysql_fetch_assoc($result)) {
			if ($row['ced_prof'] <> $lacedula)
				die ('No se puede asignar esta dirección de email, ya esta registrada a nombre de '.$row['ape_prof'].' '.$row['nombr_prof']);
		}
	}
	$sql="UPDATE sgcaf200 SET nombr_prof = '$elnombre', ape_prof = '$elapellido', dire1_prof = '$ladireccionh1',  
		dire2_prof = '$ladireccionh2', telf_prof = '$eltelefonoh', escu_prof = '$laescuela', dept_prof = '$eldpto', 
		ubic_prof = '$laubicacion',  f_ing_ucla = '$fing_uclamysql', tipo_prof='$eltipoafiliado', sueld_prof='$elsueldo',
		f_ing_capu = '$lafechaingresomysql', f_ret_capu= '$lafecharetiromysql', statu_prof = '$elestatus', 
		jubilado = '$fejubilacionmysql', escuela = '$laescuela',
		cargo = '$elcargo', tipo_cuenta = '$eltipocuenta', nro_cuenta = '$elnrocuenta', dirn1_prof='$ladireccionh1',  
		dirn2_prof = '$ladireccionh2', teln_prof = '$eltelefonoh', celn_prof = '$elcelular1', cel2n_prof = '$elcelular2',
		ofin_prof = '$eltelefonot', ctan_prof = '$elnrocuenta', lnaci_prof = '$ellugar', fnaci_prof = '$lafechanacmysql',
		libre_prof = '$elejercicio', nombr_empr = '$laempresa', dire1_empr = '$ladirecciont1', dire2_empr = '$ladirecciont2',
		tele_empr = '$eltelefonot', ext_empr = '$laextension', fax_empr = '$elfax', mail_prof = '$elemail', sexo_prof = '$optsexo',
		ubic_prof = '$laubicacion', cta_nva = '$elnrocuenta' , civil_prof = '$elcivil'
	WHERE ced_prof = '$lacedula'";
//	echo $sql;
	mysql_query($sql) or die ("<p />El usuario $usuario no tiene permisos para modificar Clientes <br>".mysql_error()."<br>".$sql);
	$accion='';
}

if ($accion == 'Borrar') {
	extract($_POST);
	$codigo = $_POST['codigo'];
	mysql_query("DELETE FROM sgcaf810 WHERE cue_codigo = $codigo") or die ("<p />El usuario $usuario no tiene permisos para borrar Cuentas");

}


// <table class='basica 100 hover' width='100%'>
?>
<?php 
if (!$accion) {
//	echo "<div id='div1'>";
/*
	echo "<form action='regacta.php?accion=Buscar' name='form1' method='post'>";
    echo 'Numero Acta ';
	echo '<input name="codigo" type="text" id="codigo" value=""  size="6" maxlength="6" />';
	echo "<input type = 'submit' value = 'Buscar'>";
	echo '</form>';
*/
	echo "<table class='basica 100 hover' ><tr>"; // width='100%'
	echo '<td colspan="2"></td><td align="center" colspan="2">Presupuestado</td><td align="center" colspan="2">Ejecutado</td></tr>';
	echo '<tr><th>Acta #</th><th>Fecha</th><th>Retiro</th><th>Prestamo</th><th>Retiro</th><th>Prestamo</th><th>Descuento</th><th>Deposito</th></tr>';
	echo '<tr><td colspan="8" align="center">[ <a href="regacta.php?accion=Anadir">Nueva Acta</a> ]</td></tr>';
	$ord = $_GET['ord'];
	if (!$ord) $ord='acta';
	$conta = $_GET['conta'];
	if (!$_GET['conta']) {
		$conta = 1;
	}
$sql = "SELECT COUNT(acta) AS cuantos FROM sgcafact ";
	$rs = mysql_query($sql);
	$row= mysql_fetch_array($rs);
	$numasi = $row[cuantos]; 
	
	
	$sql = "SELECT * FROM sgcafact ORDER BY $ord desc "." LIMIT ".($conta-1).", 20";
	$rs = mysql_query($sql);
//	echo $sql;

	if (pagina($numasi, $conta, 20, "Actas", $ord)) {$fin = 1;}

// bucle de listado

	while($row=mysql_fetch_array($rs)) {
		echo "<tr>";
		echo "<td align='center'>";
//		echo "<a href='regacta.php?accion=Editar&codigo=".$row['cod_prof']."'>";
		echo ($row['especial']==0?'<strong>':'').$row['acta']."</td>";
		echo "<td align='center'>";
//		echo "<a href='regacta.php?accion=Editar&cedula=".$row['ced_prof']."'>";
		echo ($row['especial']==0?'<strong>':'').convertir_fechadmy($row['fecha'])."</strong></td>";
		echo "<td align='center'>";
//		echo "<a href='regacta.php?accion=Editar&cedula=".$row['ced_prof']."'>";
//		echo trim($row['ape_prof']). ' '.trim($row['nombr_prof'])."</a></td>";
		echo ($row['especial']==0?'<strong>':'').number_format($row['mon_ret'],2,'.',',')."</strong></td>";
		echo "<td align='right'>";
		echo ($row['especial']==0?'<strong>':'').number_format($row['mon_pre'],2,'.',',')."</strong></td>";
		echo "<td align='right'>";
		echo ($row['especial']==0?'<strong>':'').number_format($row['eje_ret'],2,'.',',')."</strong></td>";
		echo "<td align='right'>";
		echo ($row['especial']==0?'<strong>':'').number_format($row['eje_pre'],2,'.',',')."</strong></td>";

		echo "<td align='center'>";
		echo convertir_fechadmy($row['f_dcto'])."</td>";
		echo "<td align='center'>";
		echo convertir_fechadmy($row['f_deposito'])."</td>";

		echo "</tr>";
	}

	echo "</table>";

	pagina($numasi, $conta, 20, "Asociados", $ord);
//	echo "</div>";
}
?>

<?php

/*
if ($accion == "Anadir2") {
	extract($_POST);
	$codigo = $_POST['codigo'];
	if ($codigo) {
		$sql="SELECT * FROM sgcaf810 WHERE cue_codigo = '$codigo'";
//		$sql="call sp_qry_cuenta('$codigo')";
		$rs=mysql_query($sql) or die ("<p />El usuario $usuario no tiene permisos para modificar cuentas");
		$fila = mysql_fetch_array($rs);
		if (mysql_num_rows($rs) > 0) {
			echo "<label>Descripción</label><br /><input type = 'text' value ='".$fila['cue_nombre']."' size='40' maxlength='40' name='nombre' readonly='readonly'><br />";
			echo "<label>Saldo Inicial</label><br /><input type = 'text' size='40' maxlength='15' name='saldoi' readonly='readonly' value ='".number_format($fila['cue_saldo'],$_SESSION['deci'],'.',',')."'<br />";
		}
	else {
		echo "<form action='cuentas.php?accion=Anadir1' name='form1' method='post' onsubmit='return gccli(form1)'>";		
		echo "<form action='cuentas.php?accion=Anadir2' name='form1' method='post'>";
		echo "<label>Código de Cuenta</label><br /><input type = 'text' size='40' maxlength='40' name='codigo'><br />";
		echo "<label>Descripción </label><br /><input type = 'text' size='40' maxlength='40' name='nombre'><br />";
		echo "<label>Saldo Inicial</label><br /><input type = 'text' size='40' maxlength='15' name='saldoi'><br />";
		echo "<input type = 'submit' value = 'Añadir'>";
		echo "</form>\n";
		}
	}
}
*/
if ($accion == "Anadir") {
	echo '<div id="div1">';
	echo "<form action='regacta.php?accion=Anadir1' name='form1' method='post' onsubmit='return valsoc(form1)'>";
	$sql='SELECT * FROM sgcafact WHERE acta = "xx"';
	$result = mysql_query($sql) or die ('Error 200-1 <br>'.$sql.'<br>'.mysql_error());
	pantalla_acta($result,$accion);
	echo "<input type = 'submit' value = 'Grabar Datos'>";
	echo '</div>';
// 	echo "</form>\n";

}

if ($accion == "Editar") {
	echo '<div id="div1">';
	$sql='SELECT * FROM sgcaf200 WHERE ced_prof= "'.$cedula.'"';
	$result = mysql_query($sql) or die ('Error 200-1 <br>'.$sql.'<br>'.mysql_error());
	$temp = "";
	echo "<form enctype='multipart/form-data' action='regacta.php?accion=Editar1' name='form1' method='post' onsubmit='return valsoc(form1)'>";
	pantalla_acta($result,$accion);
	echo "<br><input type = 'submit' value = 'Confirmar cambios'></form>\n";
	echo "<a href='regbenef.php?cedula=".$cedula."'>Actualizar Beneficiarios";
	echo '</div>';
/*	if (!$temp) {
		echo "<p /><form action='cuentas.php?accion=Borrar' name='form2' method='post'>\n";
		echo "<input type='hidden' name='codigo' value=".$codigo.">\n";
		echo "<input type='submit' value='Borrar Cuenta' onclick='return borrar_cuenta()'></form>\n";
	}
*/
}
?>

<?php include("pie.php");?></body></html>


<?php
function pantalla_acta($result,$accion)
{
	$deci=$_SESSION['deci'];
	$sep_decimal=$_SESSION['sep_decimal'];
	$sep_miles=$_SESSION['sep_miles'];
	$fila = mysql_fetch_assoc($result);
	if ($accion == 'Editar') { $lectura = 'readonly = "readonly"'; $activada="disabled" ; } else {$lectura=''; $activada='';}
	if ($accion == 'Anadir') {
		$elcodigo=nuevo_codigo(); 
		$ingreso=date("d/m/Y", time());
		}
	else  $elcodigo=$fila['acta'];
?>
  <label><fieldset><legend>Información para el Acta</legend>
  <table width="639" border="1">
    <tr><td width="200" >C&oacute;digo</td>
    <td width="163" class="rojo"><input name="elcodigo" type="text" id="elcodigo" readonly = "readonly" value="<?php echo $elcodigo; ?>"  size="6" maxlength="6" />*</td>
    <td width="138">Fecha</td>
      <td width="173" class="rojo"> 
	<script type="text/javascript">
	// setActiveStyleSheet(this, 'green');
	setActiveStyleSheet(document.getElementById("defaultTheme"), "green");
	</script>
	</b> <input type="text" name="date3" id="sel3" size="12" readonly
	><input type="reset" value=" ... "
	onclick="return showCalendar('sel3', '%d/%m/%Y');"><br />
    </td>
    </tr>
	  
    <tr><td >Monto Retiro Estimado</td>
      <td class="rojo"><input name="mon_ret" type="text" id="mon_ret" size="10" maxlength="10" value="0.00" /></td>
      <td >Monto Prestamo Estimado</td>
      <td class="rojo"><input name="mon_pre" type="text" id="mon_pre" size="10" maxlength="10" value="0.00" /></td>
     </tr>

     <tr>
      <td>Fecha de Descuento</td>
      <td class="rojo" >
		<script type="text/javascript">
		// setActiveStyleSheet(this, 'green');
		setActiveStyleSheet(document.getElementById("defaultTheme"), "green");
		</script>
		<input type="text" name="date4" id="sel4" size="12" readonly
		><input type="reset" value=" ... "
		onclick="return showCalendar('sel4', '%d/%m/%Y');">
	   </td>

      <td>Fecha Deposito</td>
      <td class="rojo" >
		<script type="text/javascript">
		// setActiveStyleSheet(this, 'green');
		setActiveStyleSheet(document.getElementById("defaultTheme"), "green");
		</script>
		<input type="text" name="date5" id="sel5" size="12" readonly
		><input type="reset" value=" ... "
		onclick="return showCalendar('sel5', '%d/%m/%Y');">
	  </td>
    </tr>
    
    <tr>
    <td>1er Descuento Especial</td>
    <td>
	<script type="text/javascript">
	// setActiveStyleSheet(this, 'green');
	setActiveStyleSheet(document.getElementById("defaultTheme"), "green");
	</script>
	<input type="text" name="date6" id="sel6" size="12" readonly
	><input type="reset" value=" ... "
	onclick="return showCalendar('sel6', '%d/%m/%Y');">
    </td>

    <td>2do Descuento Especial</td>
    <td>
	<script type="text/javascript">
	// setActiveStyleSheet(this, 'green');
	setActiveStyleSheet(document.getElementById("defaultTheme"), "green");
	</script>
	<input type="text" name="date7" id="sel7" size="12" readonly
	><input type="reset" value=" ... "
	onclick="return showCalendar('sel7', '%d/%m/%Y');">
    </td>
	</tr>
    
    <tr>
    <td>3er Descuento Especial</td>
    <td>
	<script type="text/javascript">
	// setActiveStyleSheet(this, 'green');
	setActiveStyleSheet(document.getElementById("defaultTheme"), "green");
	</script>
	<input type="text" name="date8" id="sel8" size="12" readonly
	><input type="reset" value=" ... "
	onclick="return showCalendar('sel8', '%d/%m/%Y');">
    </td>
    </tr>
        
    

</table>
</fieldset> 
<?php 
}

function nuevo_codigo()
{
	$sql="select acta from sgcafact order by acta desc limit 1";
	$resulta2=mysql_query($sql);
	$fila2 = mysql_fetch_assoc($resulta2);
	$ultimo=$fila2['acta'];
	$digitos=5;
	$ultimo++;
	$ultimo=ceroizq($ultimo,$digitos);
	return $ultimo;
}

?>
