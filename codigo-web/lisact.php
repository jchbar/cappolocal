<?php
include("head.php");
include("paginar.php");
if (!$link OR !$_SESSION['empresa']) {
	include("noempresa.php");
	exit;
}
if ($accion == 'Anadir') 
	$onload="onload=\"foco('cta')\""; 
else
	$onload="onload=\"foco('nactivo')\"";
?>

<body <?php if (!$bloqueo) {echo $onload;}?>>

 
<?php
 
$readonly=" readonly='readonly'";
include("arriba.php");
$menu61=1;include("menusizda.php");
$cta = $_GET['cta'];
$_SESSION['nro']=$nro; 
$nactivo=$_GET['nactivo'];
$ip = $_SERVER['HTTP_CLIENT_IP'];
if (!$ip) {$ip = $_SERVER['REMOTE_ADDR'];}

if ($accion == 'Buscar')  {
	extract($_POST);
	$nactivo = trim($_POST['nactivo']);
	$cta = trim($_POST['cta']);
	echo $cta. ' - ' .$nactivo . ' - '.$accion;
	if ($nactivo) { //  != ' ') {
  $sql="SELECT * FROM sgcaf610 where nidentif = '$nactivo' and motivodes='' and fechades = '0000-00-00'";
		$result=mysql_query($sql);
		$row= mysql_fetch_assoc($result);
		if (mysql_num_rows($result) > 0) {
		echo "<input type = 'hidden' value ='".$row['nidentif']."' name='nactivo'>"; 
		$cta=$row['cta_contab'];
		$accion = 'Editar'; }
		else {
		echo "<p />NO SE ENCUENTRA ACTIVO FIJO</div></body></html>"; 
		  echo '<div style="clear:both"></div>';
    echo '<p /><div class="noimpri" style="clear:both;text-align:center">';
    echo '<a href="lisact.php"><input type="button" name="boton" value="regresar" tabindex="3">';

		
		}
		} 
	else if ($cta) {  
		$cta=$cta;
		$sql="SELECT * FROM sgcaf610 where cta_contab = '$cta' and motivodes='' and fechades = '0000-00-00'";
		$result=mysql_query($sql);
		$row= mysql_fetch_assoc($result);
		if (mysql_num_rows($result) > 0) {
		echo "<input type = 'hidden' value ='".$row['cta_contab']."' name='cta'>"; 
		$nactivo=$row['nidentif'];
		$accion = 'Editar'; }
		else {
		echo "<p />NO SE ENCUENTRA ACTIVO FIJO</div></body></html>"; 
		  echo '<div style="clear:both"></div>';
    echo '<p /><div class="noimpri" style="clear:both;text-align:center">';
    echo '<a href="lisact.php"><input type="button" name="boton" value="regresar" tabindex="3">';

		
		}
}
}
if ($accion == 'Anadir1') {
    //echo '1'; 
	extract($_POST);
	$nactivo = $_POST['nactivo'];
    $com_fechamysql=convertir_fecha($com_fecha);
    echo $nactivo; 
	
	if ($nactivo) {
	//echo '2'; 
		$sql = "select * from sgcaf610 where nidentif= '$nactivo'";
		$result=mysql_query($sql);
		echo $sql; 
		if (mysql_num_rows($result) > 0) die ('No se puede asignar esta identificación ya esta registrada a '.$descrip.'');
		$sql = "select * from sgcaf610 where cta_contab = '$nro'";
		echo $sql;
		$result=mysql_query($sql);
		if (mysql_num_rows($result) > 0) die ('No se puede registrar '.$descrip.'  ya existe ');
		$sql="INSERT INTO sgcaf610(descrip, cta_contab, costo, nidentif, departa, fechaad, comprobant, vida_util, ip, observ, valoract, depanual, depmensual, depacfecha) 
		VALUES ('$descrip', '$nro', '$costo', '$nactivo', '$eldpto', '$com_fechamysql', '$comprobant', '$vida1', '$ip', '$observacion', '$valor', '$danual','$dmensual','$dacum')";
		//echo $sql;
		mysql_query($sql)or die ("<p />El usuario $usuario no tiene permisos para añadir Clientes o cuenta ya existente.<br>".mysql_error()."<br>".$sql);
		$accion="";
		}
}
if ($accion == 'Editar1') {
	extract($_POST);
	$nactivo= $_POST['nactivo'];
	$num = 1;	
echo $costo;
echo $costo1;
		if ($costo1<>$costo)
		{
		echo "<form enctype='multipart/form-data' action='lisact.php?accion=Editar2' name='form1' method='post' onsubmit='return valact0(form1)'>";
		echo '<input type="hidden" name="descrip" value="'.$descrip.'">';
		echo '<input type="hidden" name="cta" value="'.$cta.'">';
		echo '<input type="hidden" name="nro" value="'.$nro.'">';
		echo '<input type="hidden" name="costo" value="'.$costo.'">';
		echo '<input type="hidden" name="vida" value="'.$vida.'">';
		echo '<input type="hidden" name="nactivo" value="'.$nactivo.'">';
		echo '<input type="hidden" name="eldpto" value="'.$eldpto.'">';
		echo '<input type="hidden" name="fechax" value="'.$fechax.'">';
		echo '<input type="hidden" name="comprobant" value="'.$comprobant.'">';
		echo '<input type="hidden" name="depracum" value="'.$depracum.'">';
		echo '<input type="hidden" name="costo1" value="'.$costo1.'">';
		echo '<input type="hidden" name="depanual" value="'.$depanual.'">';
		echo '<input type="hidden" name="depmensual" value="'.$depmensual.'">';
		echo '<input type="hidden" name="observacion" value="'.$observacion.'">';
		echo '<input type="hidden" name="fechaz" value="'.$fechaz.'">';
		pantalla_act4($accion,$descrip,$costo,$vida,$cta,$nro,$nactivo,$eldpto,$fechax,$comprobant,$depracum,$observacion,$fechaz, $depmensual, $depanual, $costo1);
		echo "<input type = 'submit' value = 'Guardar Datos'>";
		} 
		else {
		$fechaxmysql=convertir_fecha($fechax);
		$fechazmysql=convertir_fecha($fechaz);
		$sql="UPDATE sgcaf610 SET descrip='$descrip', costo='$costo', nidentif='$nactivo', depacfecha='$depracum', departa='$eldpto', fechaad='$fechaxmysql', depanual='$depanual', comprobant='$comprobant', depmensual='$depmensual', vida_util='$vida', observ='$observacion', ultima_dep='$fechazmysql' 
		WHERE cta_contab='$cta'";
    	echo $sql;
		mysql_query($sql) or die ("<p />El usuario $usuario no tiene permisos para modificar Clientes <br>".mysql_error()."<br>".$sql);
		$accion='';
		}
}
if ($accion == 'Editar2') {
	extract($_POST);
	extract($_POST);
	$nactivo= $_POST['nactivo'];
	$num = 1;
		$fechaxmysql=convertir_fecha($fechax);
		$fechazmysql=convertir_fecha($fechaz);
		$sql="UPDATE sgcaf610 SET descrip='$descrip', costo='$costo', nidentif='$nactivo', depacfecha='$depracum', departa='$eldpto', fechaad='$fechaxmysql', depanual='$depanual', comprobant='$comprobant', depmensual='$depmensual', vida_util='$vida', observ='$observacion', ultima_dep='$fechazmysql' 
		WHERE cta_contab='$cta'";
    	echo $sql;
		mysql_query($sql) or die ("<p />El usuario $usuario no tiene permisos para modificar Clientes <br>".mysql_error()."<br>".$sql);
		$accion='';
}
if ($accion == 'Borrar') {
	extract($_POST);
	$nactivo = $_POST['nactivo'];
	mysql_query("DELETE FROM sgcaf810 WHERE cue_codigo = $codigo") or die ("<p />El usuario $usuario no tiene permisos para borrar Cuentas");
}
?>
<?php 
if (!$accion) {
//	echo "<div id='div1'>";
  	echo "<form action='lisact.php?accion=Buscar' name='form1' method='post'>\n";
    echo 'Identificación ';
	echo '<input name="nactivo" type="text" id="nactivo" value=""  size="7" maxlength="7" />';
	echo 'Nro. de Cuenta ';
	echo '<input name="cta" type="text" id="cta" value=""  size="22" maxlength="21" />';
	echo "<input type = 'submit' value = 'Buscar'>";
	echo '</form>';
	echo "<table class='basica 100 hover' width=''><tr>";
	echo '<th><a href=?ord=nidentif>Identificación</a></th><th><a href=?ord=cta_contab>Nro. de Cuenta</th><th><a href=?ord=descrip>Descripción</a><br>';
	echo '[ <a href="lisact.php?accion=Anadir"> Nuevo Activo Fijo</a> ]</a><br>';
	echo '<th><a href=?ord=fechaad>Fecha <br> de Adquisición</th><th>Valor <br> Actual</th></th>';
	
	$ord = $_GET['ord'];
	if (!$ord) $ord='cta_contab';
	$conta = $_GET['conta'];
	if (!$_GET['conta']) {
		$conta = 1;
	}
$sql = "SELECT COUNT(nidentif) AS cuantos FROM sgcaf610 where motivodes='' and fechades = '0000-00-00'";
	$rs = mysql_query($sql);
	$row= mysql_fetch_array($rs);
	$numasi = $row[cuantos]; 
	
	$sql = "SELECT *, date_format(fechaad, '%d/%m/%Y') AS fechax FROM sgcaf610 where motivodes='' and fechades = '0000-00-00' ORDER BY $ord "." LIMIT ".($conta-1).", 20";
	$rs = mysql_query($sql);
	//echo $sql;

	if (pagina($numasi, $conta, 20, "Activos Fijos", $ord)) {$fin = 1;}

// bucle de listado

	while($row=mysql_fetch_array($rs)) {
		echo "<tr>";
		echo "<td class='centro'>";
		echo "<a href='lisact.php?accion=Editar&nactivo=".$row['nidentif']."'>";
		echo $row['nidentif']."</a></td>";
		echo "<td class='centro'>";
		echo "<a href='lisact.php?accion=Editar&cta=".$row['cta_contab']."'>";
		echo $row['cta_contab']."</a></td>";
		echo "<td class='centro'>";
		echo "<a href='lisact.php?accion=Editar&cta=".$row['cta_contab']."'>";
		echo $row['descrip']."</a></td>";
		echo "<td class='centro'>";
		echo $row['fechax']."</a></td>";
	    echo "<td class='dcha'>";
		

		if ($row['valoract']  < 0) { $valor = '0,00';
		echo number_format($valor,2,'.',',')."</td>";}
	    else 
		{echo number_format($row['valoract'],2,'.',',')."</td>";}
		echo "</tr>";
	}
	echo "</table>";

	pagina($numasi, $conta, 20, "Activos Fijos", $ord);
//	echo "</div>";
}
?>
<?php
if ($accion == "Anadir") {
	echo '<div id="div1">';
	echo "<form action='lisact.php?accion=Verificar1' name='form1' method='post' onsubmit='return valsoc(form1)'>";
	$sql='SELECT * FROM sgcaf610 WHERE cta_contab= "xx"';
	$result = mysql_query($sql) or die ('Error 200-1 <br>'.$sql.'<br>'.mysql_error());
	echo "REGISTRO DE ACTIVOS FIJOS";
	echo "<p />";
	echo "<form enctype='multipart/form-data' method='post' name='form1'>Nro. de Cuenta";
	echo '<select name="nro" size="1">';
	$sql="select codigoact, descripact from sgcaf620 order by codigoact";
	$resultado=mysql_query($sql);
				while ($fila2 = mysql_fetch_assoc($resultado)) {
				echo '<option value="'.$fila2['codigoact'].'" '.(($nro==$fila2['codigoact'])?'selected':'').'>'.$fila2['descripact'].''.$fila2['codigoact'].'</option>';}
	 	echo '</select> ';  
		//echo $sql;
	echo "<input type = 'submit' value = 'Enviar'>";
	echo '</div>';
// 	echo "</form>\n";
    //echo $nro; 
}

if ($accion == "Verificar1") {
	echo '<div id="div1">';
	echo "<form action='lisact.php?accion=Verificar2' name='form1' method='post' onsubmit='return valact1(form1)'>";
	echo '<input type="hidden" name="nro" value="'.$nro.'">';
	$sql='SELECT * FROM sgcaf620 WHERE codigoact= "'.$nro.'"';
	//echo $sql; 
	$result = mysql_query($sql) or die ('Error 200-1 <br>'.$sql.'<br>'.mysql_error());
    pantalla_act1($result,$accion,$nro);
    echo "<input type = 'submit' value = 'Enviar'>";
	echo '</div>';
// 	echo "</form>\n";
}
if ($accion == "Verificar2") {
    echo '<div id="div1">';
	echo "<form action='lisact.php?accion=Verificar3' name='form1' method='post' onsubmit='return valact2(form1)'>";
	echo '<input type="hidden" name="nro" value="'.$nro.'">';
	echo '<input type="hidden" name="nactivo1" value="'.$nactivo1.'">';
	$sql='SELECT * FROM sgcaf610 WHERE cta_contab= "'.$nro.''.$nactivo1.'"';
	$result=mysql_query($sql);
	if (mysql_num_rows($result) <> 0) {
    		echo "<p />Activo Fijo Nro.<span class='b'>$nro$nactivo1</span> esta registrado</div></body></html>";
	  	   echo "<p /><br /><p /><td><a href='lisact.php?accion=Editar&cta=$nro$nactivo1'>Ir a Editar</a>";
		   		   		   					exit;     }    
	else {
	$sql='SELECT * FROM sgcaf810 WHERE cue_codigo= "'.$nro.''.$nactivo1.'"';
    $result=mysql_query($sql);
	if (mysql_num_rows($result) == 0) {
           echo "<p />Código <span class='b'>$nro$nactivo1</span> no esta registrado</div></body></html>";
		     echo '<div style="clear:both"></div>';
    echo '<p /><div class="noimpri" style="clear:both;text-align:center">';
    echo '<a href="lisact.php?accion=Anadir"><input type="button" name="boton" value="regresar" tabindex="3">';
						exit;     }
	else {
    pantalla_act2($result,$accion,$nro,$nactivo1);
	echo "<input type = 'submit' value = 'Enviar'>";}
	echo '</div>';
// 	echo "</form>\n";
}
}
if ($accion == "Verificar3") {
    echo '<div id="div1">';
	echo "<form action='lisact.php?accion=Anadir1' name='form1' method='post' onsubmit='return valact2(form1)'>";
	echo '<input type="hidden" name="nro" value="'.$nro.'">';
	echo '<input type="hidden" name="nactivo1" value="'.$nactivo1.'">';
	echo '<input type="hidden" name="descrip" value="'.$descrip.'">';
	echo '<input type="hidden" name="eldpto" value="'.$eldpto.'">';
	echo '<input type="hidden" name="com_fecha" value="'.$com_fecha.'">';
	echo '<input type="hidden" name="comprobant" value="'.$comprobant.'">';
	echo '<input type="hidden" name="observacion" value="'.$observacion.'">';
	echo '<input type="hidden" name="costo" value="'.$costo.'">';
	echo '<input type="hidden" name="vida1" value="'.$vida1.'">';
	$sql='SELECT * FROM sgcaf810 WHERE cue_codigo= "'.$nro.''.$nactivo1.'"';
    $result=mysql_query($sql);
    pantalla_act3($result,$accion,$nro,$nactivo1,$descrip,$eldpto,$com_fecha,$comprobant,$observacion,$costo,$vida1);
	echo "<input type = 'submit' value = 'Grabar Datos'>";
	echo '</div>';
// 	echo "</form>\n";
}
if ($accion == "Editar") {
	echo '<div id="div1">';
	$sql="SELECT *,date_format(fechaad, '%d-%m-%Y') AS fechax, substr(cta_contab,1,17) as maxi, date_format(ultima_dep, '%d/%m/%Y') AS fechaz FROM sgcaf610, sgcaf640 WHERE coddepar=departa AND (cta_contab= '$cta' or nidentif= '$nactivo')";
	//echo $sql;
	$result = mysql_query($sql) or die ('Error 200-1 <br>'.$sql.'<br>'.mysql_error());
	$temp = "";
	echo "<form enctype='multipart/form-data' action='lisact.php?accion=Editar1' name='form1' method='post' onsubmit='return valact0(form1)'>";
	pantalla_act($result,$accion);
    echo "<br><input type = 'submit' value = 'Confirmar cambios'></form>\n";
	/*cambiar esto ojo*/
	echo '</div>';
}
?>
<?php include("pie.php");?>
</body></html>
<?php
function pantalla_act($result,$accion)
{
	$deci=$_SESSION['deci'];
	$sep_decimal=$_SESSION['sep_decimal'];
	$sep_miles=$_SESSION['sep_miles'];
	$fila = mysql_fetch_assoc($result);
	/*Calculo para determinar el limite de un mes hacia atras desde el día de hoy para el calendario*/
			$hoy = date("d/m/Y"); 
			$hoy1 = mktime(0,0,0,date("m"),date("d"),date("Y")); 
			$h = date("d/m/Y",$hoy1);
			$ant = $hoy1-2592000; 
			$mesant = date("d/m/Y",$ant);
	echo "<input type = 'hidden' value ='".$fila['cta_contab']."' name='cta'>";
	if ($accion == 'Editar') { $lectura = 'readonly = "readonly"'; $activada="disabled" ; } else {$lectura=''; $activada='';}
	if ($accion == 'Anadir') {
		$ingreso=date("d/m/Y", time());
		}
	else  $nactivo=$fila['nifentif'];
//	<form id="form1" name="form1" method="post" action="">
?>
 <fieldset><legend>Información del Activo Fijo </legend>
	<table width="600" border="2">
    <tr><td class= "blanco b" width="127">Descripción</td>
    <td colspan="3" class= "rojo"><input name="descrip" type="text" id="descrip" size="87" maxlength="85" onChange="conMayusculas(this)" value="<?php echo $fila['descrip'] ?>" />*</td></tr>
	  
	<td class= "blanco b" width="127">Nro. de Cuenta</td>
    <td width="80" class="blanco b"><input type="hidden" name="nro" value="<?php echo $fila['cta_contab']; ?>"/><?php echo $fila['cta_contab']; ?></td>
	<td class= "blanco b" width="127">Costo</td>
    <td width="70" class= "rojo"><input name="costo" type="text" id="costo" style="text-align:right" size="25" maxlength="25" onChange="conMayusculas(this)" value="<?php echo number_format ($fila['costo'],2,'.','') ?>" />*</td></tr>
    <tr>
    <input type="hidden" name="costo1" value="<?php echo $fila['costo'];?>">
	
	<tr><td class= "blanco b" width="127">Identificación</td>
    <td width="80" class="blanco b">  <input type="hidden" name="nactivo" value="<?php echo $fila['nidentif']?>"/><?php echo $fila['nidentif']?></td>  
	<td class= "blanco b" width="127">Depreciación Acumulada</td>
    <td width="70" class="blanco b" align="right"><input type="hidden" name="depracum" value="<?php echo number_format ($fila['depacfecha'],2,'.','');?>"/><?php echo number_format ($fila['depacfecha'],2,'.',''); ?></td></tr>
	  
    <td class= "blanco b">Departamento</td>
    <td class="rojo" colspan="1">
		<?php
			$elcdpto=$fila['departa'];
			echo '<select name="eldpto" size="1">';
			$sql="select descpdep, coddepar from sgcaf640 order by coddepar";
			$resultado=mysql_query($sql);
			//echo $sql;
			while ($fila2 = mysql_fetch_assoc($resultado)) {
				echo '<option value="'.$fila2['coddepar'].'" '.(($elcdpto==$fila2['coddepar'])?'selected':'').'>'.$fila2['descpdep'].'</option>';}
	 	echo '</select> '; 
		?>*
		 <?php $valor = $fila['costo'] - $fila ['depacfecha']; if ($valor < 0) { $valor = '0,00';} ?>
		</td>
	  <td class= "blanco b" width="127">Valor Actual</td>
      <td width="70" class="blanco b" align="right"><input type="hidden" name="valoract" value="<?php echo number_format ($fila['valoract'],2,'.','');?>"/><?php echo number_format ($valor,2,'.','') ?></td></tr>
		
     	<td class= "blanco b">Fecha de Adquisición</td><td class="rojo">
		<?php 

//	 	escribe_formulario(fechax, form1.fechax, 'd/m/yyyy',convertir_fechadmy($fila['fechaad']), $mesant, $hoy, '1', '3') 
	$hoy = date("d/m/Y");
    $fechanueva=explode('/',$hoy);
	$fechanueva=$fechanueva[1].'/'.$fechanueva[0].'/'.$fechanueva[2];
	$sqlano='select substr(fech_ejerc,1,4) as ano from sgcaf100';
	$sqlfano=mysql_query($sqlano);
	$sqlrano=mysql_fetch_assoc($sqlfano);
	$rango=$sqlrano['ano'];
	$sqlano='select substr(now(),1,4)';
	$sqlfano=mysql_query($sqlano);
	$sqlrano=mysql_fetch_assoc($sqlfano);
	if ($sqlrano['ano'] > $rango)
		$rango.=', '.$sqlrano['ano'];
	?>
	<input type="hidden" name="fechax" id="fechax" value=" <?php  echo $fechanueva; ?>"/>
   <span style="background-color: #ff8; cursor: default;"
         onmouseover="this.style.backgroundColor='#ff0';"
         onmouseout="this.style.backgroundColor='#ff8';"
         id="show_ingcapu" 
   ><?php  echo ($hoy); ?></span> *
<script type="text/javascript">
    Calendar.setup({
//		showAt(220, 250)
//		position       : 	{100,300},
        inputField     :    "fechax",     // id of the input field
        ifFormat       :    "%d/%m/%Y",     // format of the input field (even if hidden, this format will be honored)
        displayArea    :    "show_ingcapu",       // ID of the span where the date is to be shown
//        daFormat       :    "%A, %B %d, %Y",// format of the displayed date
        daFormat       :    "%B %d, %Y",// format of the displayed date
        align          :    "Tl",           // alignment (defaults to "Bl")
        singleClick    :    true,
		weekNumbers    :    false, 
		range          :     <?php echo $rango; ?>,

// desactivacion de 18 años pa' tras


/*
		dateStatusFunc :    function (date) { // disable weekend days (Saturdays == 6 and Subdays == 0)
						var today = new Date();
						return (
//							  (date.getTime() < today.getTime()-((365*18)*24*60*60*1000))
							  (date.getTime() > today.getTime()-(1*24*60*60*1000)) 
							  // || date.getTime() > today.getTime()+(10*24*60*60*1000))	date.getDay() == 0 || 
							  ) ? true : false;  }
*/
					    });
</script>
        
		</td>
		<td class= "blanco b" width="127">Depreciación Anual</td>
      	<td width="70" class="blanco b" align="right"><input type="hidden" name="depanual" value="<?php echo number_format ($fila['depanual'],2,'.','');?>"/><?php echo number_format ($fila['depanual'],2,'.','') ?></td></tr>
	 	
		<td class= "blanco b" width="127">Nro. de  Comprobante</td>
	  	<td width="80" class="rojo"><input name="comprobant" type="text" id="comprobant" size="15" maxlength="15" onChange="conMayusculas(this)" value="<?php echo $fila['comprobant'] ?>" />*</td>
	  	<td class= "blanco b" width="100">Depreciación Mensual</td>
      	<td width="70" class="blanco b" align="right"><input type="hidden" name="depmensual" value="<?php echo number_format ($fila['depmensual'],2,'.','');?>"/><?php echo number_format($fila['depmensual'],2,'.',''); ?></td></tr>
	    
		<td class= "blanco b" width="127">Vida Útil</td>
		<td width="70" class="blanco b" align="left"><input type="hidden" name="vida" value="<?php echo number_format($fila['vida_util'],2,'.','');?>"/><?php echo number_format($fila['vida_util'],0,'.',''); ?></td>
	     <td class= "blanco b">Última Depreciación</td>
    	 <td width="70" class="blanco b" align="right"><input type="hidden" name="fechaz" value="<?php echo $fechaz;?>"/><?php echo $fila['fechaz']; ?></td></tr>
	 
      <td class= "blanco b">Observación</td><td colspan="3" class= "rojo">
	<input name="observacion" type="text" id="observacion" size="87" maxlength="05" onChange="conMayusculas(this)" value="<?php echo $fila['observ'];?>"/>*</td><tr>
		
  &nbsp;</td> </tr>
</table>
<?php 
}
?>
   </td>
    </tr>
</table>
</fieldset><?php 
function pantalla_act1($result,$accion,$nro) {
$fila666 = mysql_fetch_assoc($result);
if ($accion == 'Verificar1') { $lectura = 'readonly = "readonly"'; $activada="disabled" ; } else {$lectura=''; $activada='';}

?>
   <fieldset><legend>REGISTRO DE ACTIVO FIJO <?php echo $fila666['descripact']; ?></legend>
  		<table width="600" border="2">
     	<td class= "blanco b" width="100">Nro. de Cuenta</td>
   	 	<td><input name="nro" type="text" id="nro" value="<?php echo $nro; ?>" <?php echo $lectura; ?> size="15" maxlength="07" />*</td> 	 
	    <td class= "blanco b" width="100" >Identificación</td>
     	<td><input type="text" size="15" maxlength="04" tabindex='5' name='nactivo1' id="inputString3" onKeyUp="lookup3(this.value);" onBlur="fill3();" value ="<?php echo $nactivo1?>" autocomplete="off"/>
		<div class="suggestionsBox" id="suggestions3" style="display: none;">
				<img src="upArrow.png" style="position: relative; top: -12px; left: 70px; "  alt="upArrow" />
				<div class="suggestionList3" id="autoSuggestionsList3">
				</div>
			</div>
		</div>
	  *</td><tr>  
	  &nbsp;</td> </tr>
</table>
 <?php 
 }
?>
<?php
function pantalla_act2($result,$accion,$nro,$nactivo1) {
$deci=$_SESSION['deci'];
$sep_decimal=$_SESSION['sep_decimal'];
$sep_miles=$_SESSION['sep_miles'];
/*Calculo para determinar el limite de un mes hacia atras desde el día de hoy para el calendario*/
		$hoy = date("d/m/Y"); 
		$hoy1 = mktime(0,0,0,date("m"),date("d"),date("Y")); 
		$h = date("d/m/Y",$hoy1);
		$ant = $hoy1-2592000; 
		$mesant = date("d/m/Y",$ant);
   $fila3 = mysql_fetch_assoc($result);
    //echo $sql; 
if ($accion == 'Verificar2') { $lectura = 'readonly = "readonly"'; $activada="disabled" ; } else {$lectura=''; $activada='';}
$ult=substr($nro,14,17); 
?>
   	<fieldset><legend>REGISTRO DE ACTIVO FIJO</legend>
  	<table width="600" border="2">
    <td class= "blanco b" width="100">Nro. de Cuenta</td>
   	<td><input name="nro" type="text" id="nro" value="<?php echo $nro,$nactivo1; ?>" <?php echo $lectura; ?> size="20" maxlength="21" />*</td> 
    <td class= "blanco b" width="100" >Identificación
	<td><input name="nactivo" type="text" id="nactivo" style="text-align:center" value="<?php echo $ult,$nactivo1 ?>" <?php echo $lectura; ?> size="15" maxlength="07" />*</td><tr>
	
	<td class= "blanco b">Descripción</td><td colspan="3">
	<input name="descrip" type="text" id="descrip" size="85" maxlength="85" onChange="conMayusculas(this)" value="<?php echo $fila3['cue_nombre'] ?>" /></td></tr>
	
	<td class= "blanco b">Departamento</td>
    <td class="rojo" colspan="1">
	<?php
			// <input name="eldpto" type="text" id="eldpto" value="<?php echo $fila['cargo'] >" size="12" maxlength="12" />
			echo '<select name="eldpto" size="1">';
			$sql="select descpdep, coddepar from sgcaf640 order by coddepar";
			$resultado=mysql_query($sql);
			//echo $sql;
			while ($fila2 = mysql_fetch_assoc($resultado)) {
				echo '<option value="'.$fila2['coddepar'].'" '.(($elcdpto==$fila2['coddepar'])?'selected':'').'>'.$fila2['descpdep'].'</option>';}
	 	echo '</select> '; 
		?>*
		<?php
		$sql="SELECT *, date_format(com_fecha, '%d-%m-%Y') AS com_fecha FROM sgcaf820 where com_cuenta='".$nro.''.$nactivo1."' and com_debcre='+' order by com_fecha desc limit 1";
		$resultado=mysql_query($sql);
        $fila5 = mysql_fetch_assoc($resultado);
		//echo $sql;
		?>
		<td class= "blanco b" width="127">Nro. de  Comprobante</td>
	  <td width="80" class="rojo"><input name="comprobant" type="text" id="comprobant" size="15" maxlength="15" onChange="conMayusculas(this)" value="<?php echo $fila5['com_nrocom'] ?>" />*</td></tr>
	 
		<td class= "blanco b">Fecha de Adquisición</td><td class="rojo">
		<?php 
//		escribe_formulario(com_fecha, form1.com_fecha, 'd/m/yyyy',($fila5['com_fecha']), $mesant, $hoy, '1', '3') 
	$hoy = date("d/m/Y");
    $fechanueva=explode('/',$hoy);
	$fechanueva=$fechanueva[1].'/'.$fechanueva[0].'/'.$fechanueva[2];
	$sqlano='select substr(fech_ejerc,1,4) as ano from sgcaf100';
	$sqlfano=mysql_query($sqlano);
	$sqlrano=mysql_fetch_assoc($sqlfano);
	$rango=$sqlrano['ano'];
	$sqlano='select substr(now(),1,4)';
	$sqlfano=mysql_query($sqlano);
	$sqlrano=mysql_fetch_assoc($sqlfano);
	if ($sqlrano['ano'] > $rango)
		$rango.=', '.$sqlrano['ano'];
	?>
	<input type="hidden" name="com_fecha" id="com_fecha" value=" <?php  echo $fechanueva; ?>"/>
   <span style="background-color: #ff8; cursor: default;"
         onmouseover="this.style.backgroundColor='#ff0';"
         onmouseout="this.style.backgroundColor='#ff8';"
         id="show_ingcapu" 
   ><?php  echo ($hoy); ?></span> *
<script type="text/javascript">
    Calendar.setup({
//		showAt(220, 250)
//		position       : 	{100,300},
        inputField     :    "com_fecha",     // id of the input field
        ifFormat       :    "%d/%m/%Y",     // format of the input field (even if hidden, this format will be honored)
        displayArea    :    "show_ingcapu",       // ID of the span where the date is to be shown
//        daFormat       :    "%A, %B %d, %Y",// format of the displayed date
        daFormat       :    "%B %d, %Y",// format of the displayed date
        align          :    "Tl",           // alignment (defaults to "Bl")
        singleClick    :    true,
		weekNumbers    :    false, 
		range          :     <?php echo $rango; ?>,

// desactivacion de 18 años pa' tras


/*
		dateStatusFunc :    function (date) { // disable weekend days (Saturdays == 6 and Subdays == 0)
						var today = new Date();
						return (
//							  (date.getTime() < today.getTime()-((365*18)*24*60*60*1000))
							  (date.getTime() > today.getTime()-(1*24*60*60*1000)) 
							  // || date.getTime() > today.getTime()+(10*24*60*60*1000))	date.getDay() == 0 || 
							  ) ? true : false;  }
*/
					    });
</script>
        *</td> 
	  	<td class= "blanco b" width="127">Costo</td>
      	<td width="70" class="rojo"><input name="costo" type="text" id="costo" style="text-align:right" size="25" maxlength="25" onChange="conMayusculas(this)" value="<?php echo number_format ($fila5['com_monto1'])?>"/>*</td><tr>
	  	<input type="hidden" name="hoy" value="<?php echo $hoy;?>"><tr>
	 	<tr>
	  	<tr>
		<td class= "blanco b" width="127">Vida Útil</td><td width="70" class="rojo">
		<?php $sql='SELECT * FROM sgcaf620 WHERE codigoact= "'.$nro.'"';
			//echo $sql; 
			$resultado=mysql_query($sql);
			$fila4 = mysql_fetch_assoc($resultado);
      		$maxvida1=$fila4['timemax'];
			$vida1=$fila4['timemax'];
			echo '<select name="vida1" size="1">';
			//echo $sql;
			$max1=$maxvida1 + 1;
			$min1=1;
			while ($max1 > $min1){
			$max1=$max1-1;
				echo '<option value="'.$max1.'" '.(($vida1==$max1)?'selected':'').'>'.$max1.'</option>';}
	 		echo '</select> '; 
		?>*
     	<tr>	
	 	<td class= "blanco b">Observación</td><td colspan="3">
	 	<textarea name="observacion" type="text" id="observacion" onChange="conMayusculas(this)" cols="80" rows="03" maxlength="05" value="<?php echo $fila['observ'] ?>" /></textarea>*</td><tr>		 
	  &nbsp;</td></tr> 
</table>	 
<?php 
}
?>
<?php
function pantalla_act3 ($result,$accion,$nro,$nactivo1,$descrip,$eldpto,$com_fecha,$comprobant,$observacion,$costo,$vida1){
$deci=$_SESSION['deci'];
$sep_decimal=$_SESSION['sep_decimal'];
$sep_miles=$_SESSION['sep_miles'];
//echo $sql; 
if ($accion == 'Verificar3') { $lectura = 'readonly = "readonly"'; $activada="disabled" ; } else {$lectura=''; $activada='';}
$ult=substr($nro,14,17); 
?>
  	 <fieldset><legend>REGISTRO DE ACTIVO FIJO</legend>
  	<table width="600" border="2">
    <td class= "blanco b" width="100">Nro. de Cuenta</td>
   	<td><input name="nro" type="text" id="nro" value="<?php echo $nro ?>" <?php echo $lectura; ?> size="20" maxlength="21" />*</td> 					
	<td class= "blanco b" width="100" >Identificación
	<td><input name="nactivo" type="text" id="nactivo" style="text-align:center" value="<?php echo $ult ?>" <?php echo $lectura; ?> size="15" maxlength="07" />*</td><tr>
	
	<td class= "blanco b">Descripción</td><td colspan="3">
	<input name="descrip" type="text" id="descrip" size="85" maxlength="85" onChange="conMayusculas(this)" value="<?php echo $descrip?>" <?php echo $lectura; ?> /></td></tr>
	
	<td class= "blanco b">Departamento</td>
    <td class="rojo" colspan="1">
	   <?php
	   		$elcdpto=$eldpto;
			$sql="select descpdep, coddepar from sgcaf640 where coddepar='$elcdpto'";
			$resultado=mysql_query($sql);
			//echo $sql; 
			$fila2 = mysql_fetch_assoc($resultado);
		?>
		<input name="elcdpto" type="text" id="elcdpto" size="15" maxlength="25" onChange="conMayusculas(this)" value="<?php echo $fila2['descpdep'];?>" <?php echo $lectura; ?>/>*</td>
		<td class= "blanco b" width="127">Costo</td>
	    <td width="70" class="rojo"><input name="costo" type="text" id="costo" style="text-align:right" size="15" maxlength="25" onChange="conMayusculas(this)" value="<?php echo number_format ($costo,2,'.','')?>" <?php echo $lectura; ?>/>*</td><tr> 
	
		<td class= "blanco b">Fecha de Adquisición</td><td class="rojo">
		<input name="com_fecha" type="text" id="com_fecha" value="<?php echo $com_fecha;?>" <?php echo $lectura; ?> size="15" maxlength="07" />*</td>
	  	<td class= "blanco b" width="100" >Depreciación Acumulada
	  	<td><input name="dacum" type="text" id="dacum" style="text-align:right" value="<?php $dacum=0; echo number_format ($dacum,2,'.','')?>" <?php echo $lectura; ?> size="15" maxlength="07" />*</td><tr>
		
	 	<td class= "blanco b" width="127">Vida Útil</td>
       	<td width="70" class="rojo"><input name="vida1" type="text" id="vida1" style="text-align:center" size="05" maxlength="05" onChange="conMayusculas(this)" value="<?php echo $vida1?>" <?php echo $lectura; ?> />*</td>
	   	<td class= "blanco b" width="100" >Valor Actual
	  	<td><input name="valor" type="text" id="valor" style="text-align:right" value="<?php $valor=$costo;  echo number_format ($valor,2,'.','') ?>" <?php echo $lectura; ?> size="15" maxlength="07" />*</td><tr>	
	    <tr>
	  
	    <td class= "blanco b" width="127">Nro. de  Comprobante</td>
	  	<td width="80" class="rojo"><input name="comprobant" type="text" id="comprobant" size="15" maxlength="15" onChange="conMayusculas(this)" value="<?php echo $comprobant ?>"<?php echo $lectura; ?> />*</td>
	   	<td class= "blanco b" width="100" >Depreciación Anual
	  	<td><input name="danual" type="text" id="danual" style="text-align:right" value="<?php $danual=$costo/$vida1;  echo number_format ($danual,2,'.','')?>" <?php echo $lectura; ?> size="15" maxlength="07" />*</td><tr>	
	  
	  <td><td></td>
	  	<td class= "blanco b" width="100" >Depreciación Mensual
	  	<td><input name="dmensual" type="text" id="dmensual" style="text-align:right" value="<?php $dmensual=$danual/12; echo number_format ($dmensual,2,'.','')?>" <?php echo $lectura; ?> size="15" maxlength="07" />*</td><tr>	
	 	<tr>	
	 	<td class= "blanco b">Observación</td><td colspan="3">
		<input name="observacion" type="text" id="observacion" size="87" maxlength="05" onChange="conMayusculas(this)" value="<?php echo $observacion?>" <?php echo $lectura; ?> />*</td><tr>
	&nbsp;</td></tr> 
</table>
<?php 
}
?>
<?php
function pantalla_act4 ($accion,$descrip,$costo,$vida,$cta,$nro,$nactivo,$eldpto,$fechax,$comprobant,$depracum,$observacion,$fechaz, $depmensual, $depanual, $costo1) {
$deci=$_SESSION['deci'];
$sep_decimal=$_SESSION['sep_decimal'];
$sep_miles=$_SESSION['sep_miles'];
//echo $sql; 
if ($accion == 'Editar1') { $lectura = 'readonly = "readonly"'; $activada="disabled" ; } else {$lectura=''; $activada='';}
?>
   		<fieldset><legend>Modificación de Costo de <?php echo $descrip; ?></legend>
  		<table width="500" border="2">
  		<td class= "blanco b" width="130" colspan ="2" style="text-align:center" >Viejo</td><td class= "blanco b" width="130" colspan= "2" style="text-align:center">Nuevo</td></tr>
 		
		<td class= "blanco b" width="130">Costo</td>
	    <td width="50" class="rojo"><input name="costo1" type="text" id="costo1" style="text-align:right" size="15" maxlength="25" onChange="conMayusculas(this)" value="<?php echo number_format ($costo1,2,'.','')?>" <?php echo $lectura; ?>/>*</td>
		<td class= "blanco b" width="130">Costo</td>
		<td width="50" class="rojo"><input name="costo" type="text" id="costo" style="text-align:right" size="15" maxlength="25" onChange="conMayusculas(this)" value="<?php echo number_format ($costo,2,'.','')?>" <?php echo $lectura; ?>/>*</td></tr>	
		
		<td class= "blanco b" width="130" >Depreciación Anual 
	  	<td width="50" class="rojo"><input name="depanual1" type="text" id="depanual1" style="text-align:right" value="<?php  echo number_format ($depanual,2,'.','')?>" <?php echo $lectura; ?> size="15" maxlength="07" />*</td> 
	   	<td class= "blanco b" width="130" >Depreciación Anual
	  	<td width="50" class="rojo"><input name="depanual" type="text" id="depanual" style="text-align:right" value="<?php $depanual=$costo/$vida;  echo number_format ($depanual,2,'.','')?>" <?php echo $lectura; ?> size="15" maxlength="07" />*</td> </tr>
	  	  
	  	<td class= "blanco b" width="130" >Depreciación Mensual 
	  	<td width="50" class="rojo"><input name="depmensual1" type="text" id="depmensual1" style="text-align:right" value="<?php  echo number_format ($depmensual,2,'.','')?>" <?php echo $lectura; ?> size="15" maxlength="07" />*</td>
	    <td class= "blanco b" width="130" >Depreciación Mensual 
	  	<td width="50" class="rojo"><input name="depmensual" type="text" id="depmensual" style="text-align:right" value="<?php $depmensual=$depanual/12; echo number_format ($depmensual,2,'.','')?>" <?php echo $lectura; ?> size="15" maxlength="07" />*</td><tr>

		<input type="hidden" name="cta" value="<?php echo  $cta ?>">
		<input type="hidden" name="nro" value="<?php echo $nro ?>">
		<input type="hidden" name="nactivo" value="<?php echo $nactivo ?>">
		<input type="hidden" name="eldpto" value="<?php echo $eldpto ?>">
		<input type="hidden" name="fechax" value="<?php echo $fechax ?>">
		<input type="hidden" name="comprobant" value="<?php echo $comprobant ?>">
		<input type="hidden" name="depracum" value="<?php echo $depracum ?>">
		<input type="hidden" name="observacion" value="<?php  echo $observacion ?>">
		<input type="hidden" name="fechaz" value="<?php echo $fechaz ?>">
		</td></tr> 
</table>
<?php 
}
?>
<?php 
/*el calendario se encuentra en el archivo popCalendar.js el cual se despliega según la función escribe_formulario(com_fecha, form1.com_fecha, 'd/m/yyyy',($fila5['com_fecha']), $mesant, $hoy, '1', '3') 
enviando las variables a calendarioventana.js que se encarga de abrir la nueva ventana que se encuentra en el archivo pop.php. 
funcionamiento del calendario 
escribe_formulario(com_fecha, form1.com_fecha, 'd/m/yyyy',($fila5['com_fecha']), $mesant, $hoy, '1', '3')
1. com_fecha es el nombre del campo de texto. 
2. form1.com_fecha es el nombre del formulario con el campo del texto.
3. d/m/yyyy es el formato con el retorna la fecha.
4. $fila5['com_fecha'] es el valor de la variable.
en caso de no restringir por fechas dejar el espacio con comillas ''. 
5. $mesant es la variable que tiene la información de la restricción de fecha un mes atras del día de hoy.
6. $hoy es la variable que tiene el día de hoy como restricción del día de hoy.
7. '1' para restringir sabado y domingo. '0'en caso de no restringir.
8. '3' es la cantidad de años con respecto al año actual 2006-2009.*/
?>