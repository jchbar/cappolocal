<?php
include("head.php");
include("paginar.php");

if (!$link OR !$_SESSION['empresa']) {
	include("noempresa.php");
	exit;
}
if ($accion == 'Desincorporar') 
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
	  $sql="SELECT * FROM sgcaf610 where nidentif = '$nactivo' and motivodes<>''";
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
    	echo '<a href="desact.php"><input type="button" name="boton" value="regresar" tabindex="3">';
		}
		} 
	else if ($cta) {  
		$cta=$cta;
		$sql="SELECT * FROM sgcaf610 where cta_contab = '$cta' and motivodes<>''";
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
    	echo '<a href="desact.php"><input type="button" name="boton" value="regresar" tabindex="3">';
		}
}
}
if ($accion == 'Desincorporar1') {
    //echo '4'; 
	extract($_POST);
	$nactivo = $_POST['nactivo'];
	//echo $nactivo; 
    $fechazzmysql=convertir_fecha($fechazz);
	$sql="select codmot, motivodesc from sgcaf650 where codmot='$cod'";
	$resultado=mysql_query($sql);
	$fila5 = mysql_fetch_assoc($resultado);
    $motivo= $fila5['motivodesc'];
			
	if ($nactivo) {
	    //echo 'xx';
		$sql = "select * from sgcaf610";
		$result=mysql_query($sql);
		//echo $sql; 
		$sql="UPDATE sgcaf610 SET fechades='$fechazzmysql', motivodes='$motivo', observdes='$observacion1', codmotdes='$cod'
	WHERE cta_contab='$cta'";
		//echo $sql;
		mysql_query($sql)or die ("<p />El usuario $usuario no tiene permisos para añadir Clientes o cuenta ya existente.<br>".mysql_error()."<br>".$sql);
		$accion="";
		}
}
if ($accion == 'Editar1') {
	extract($_POST);
	$nactivo= $_POST['nactivo'];
	$num = 1;
	$fechaxmysql=convertir_fecha($fechax);
	$fechazmysql=convertir_fecha($fechaz);
	$sql="UPDATE sgcaf610 SET descrip='$descrip', costo='$costo', nidentif='$nactivo', depacfecha='$depracum', departa='$eldpto', valoract='$valoract', fechaad='$fechaxmysql', depanual='$depanual', comprobant='$comprobant', depmensual='$depmensual', vida_util='$vida', ultima_dep='$fechazmysql' 
	WHERE cta_contab='$cta'";
    //echo $sql;
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
	echo "<form action='desact.php?accion=Buscar' name='form1' method='post'>\n";
    echo 'Identificación ';
	echo '<input name="nactivo" type="text" id="nactivo" value=""  size="7" maxlength="7" />';
	echo 'Nro. de Cuenta ';
	echo '<input name="cta" type="text" id="cta" value=""  size="22" maxlength="21" />';
	echo "<input type = 'submit' value = 'Buscar'>";
	echo '</form>';
	echo "<table class='basica 100 hover' width='%' ><tr>";
	echo '<p /><th><a href=?ord=nidentif>Identificación</a></th><th><a href=?ord=cta_contab>Nro. de Cuenta</th><th><a href=?ord=descrip>Descripción</a>';
	echo '<br>[ <a href="desact.php?accion=Desincorporar"> Desincorporar Activo Fijo</a> ]</a>';
	echo '<th><a href=?ord=fechades>Fecha de Desincorporación<th>Valor de <br> Desincorporación<th>Motivo de Desincorporación</th></th>';
	$ord = $_GET['ord'];
	if (!$ord) $ord='cta_contab';
	$conta = $_GET['conta'];
	if (!$_GET['conta']) {
		$conta = 1;
	}
$sql = "SELECT COUNT(nidentif) AS cuantos FROM sgcaf610 where motivodes <>''";
	$rs = mysql_query($sql);
	$row= mysql_fetch_array($rs);
	$numasi = $row[cuantos]; 
	
	$sql = "SELECT * FROM sgcaf610 where motivodes <>'' ORDER BY $ord "." LIMIT ".($conta-1).", 20";
	$rs = mysql_query($sql);
	//echo $sql;

	if (pagina($numasi, $conta, 20, "Activos Fijos Desincorporados", $ord)) {$fin = 1;}

// bucle de listado

	while($row=mysql_fetch_array($rs)) {
		echo "<tr>";
		echo "<td class='centro'>";
		echo "<a href='desact.php?accion=Editar&nactivo=".$row['nidentif']."'>";
		echo $row['nidentif']."</a></td>";
		echo "<td class='centro'>";
		echo "<a href='desact.php?accion=Editar&cta=".$row['cta_contab']."'>";
		echo $row['cta_contab']."</a></td>";
		echo "<td class='centro'>";
		echo "<a href='desact.php?accion=Editar&cta=".$row['cta_contab']."'>";
		echo $row['descrip']."</a></td>";
		echo "<td class='centro'>";
		echo convertir_fechadmy($row['fechades'])."</a></td>";
		echo "<td class='dcha'>";
		if ($row['depacfecha'] > $row['costo']){
			 $valor = '0,00'; }
			else {			 
			 $valor = $row['costo'] - $row ['depacfecha']; if ($valor < 0) { $valor = '0,00';}}
		echo number_format($valor,2,'.',',')."</td>";
	  	echo "<td class='centro'>";
		echo $row['motivodes']."</a></td>";
	 	echo "</tr>";
	}
	echo "</table>";

	pagina($numasi, $conta, 20, "Activos Fijos Desincorporados", $ord);
//	echo "</div>";
}
?>
<?php
if ($accion == "Desincorporar") {
    //echo '1'; 
	echo '<div id="div1">';
	echo "<form action='desact.php?accion=Verificar1' name='form1' method='post' onsubmit='return valsoc(form1)'>";
	$sql='SELECT * FROM sgcaf610 WHERE cta_contab= "xx"';
	$result = mysql_query($sql) or die ('Error 200-1 <br>'.$sql.'<br>'.mysql_error());
	echo "DESINCORPORACIÓN DE ACTIVOS FIJOS";
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
     //echo '2';
	echo '<div id="div1">';
	echo "<form action='desact.php?accion=Verificar2' name='form1' method='post' onsubmit='return valact1(form1)'>";
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
    //echo '3';
  echo '<div id="div1">';
	echo "<form action='desact.php?accion=Desincorporar1' name='form1' method='post' onsubmit='return valact3(form1)'>";
	echo '<input type="hidden" name="nro" value="'.$nro.'">';
	echo '<input type="hidden" name="nactivo1" value="'.$nactivo1.'">';
	$sql='SELECT * FROM sgcaf610 WHERE cta_contab= "'.$nro.''.$nactivo1.'"';
	$result1=mysql_query($sql);
	if (mysql_num_rows($result1) == 0) {
     		echo "<p />Código <span class='b'>$nro$nactivo1</span> no esta registrado</div></body></html>";
	echo '<div style="clear:both"></div>';
    echo '<p /><div class="noimpri" style="clear:both;text-align:center">';
    echo '<a href="desact.php?accion=Desincorporar"><input type="button" name="boton" value="regresar" tabindex="3">';
		 
					exit;     }
	else {
	$sql='SELECT *,date_format(fechaad, "%d/%m/%Y") AS fechax, date_format(ultima_dep, "%d/%m/%Y") AS fechaz, date_format(fechades, "%d/%m/%Y") AS fechazz FROM sgcaf610 WHERE cta_contab= "'.$nro.''.$nactivo1.'" and motivodes=" "';
    $result=mysql_query($sql);
    pantalla_act2($result,$accion,$nro,$nactivo1);
	echo "<input type = 'submit' value = 'Grabar Datos'>";}
	echo '</div>';
// 	echo "</form>\n";
}

if ($accion == "Editar") {
    //echo '5'; 
	echo '<div id="div1">';
	$sql="SELECT * , date_format(fechaad, '%d/%m/%Y') AS fechax, date_format(ultima_dep, '%d/%m/%Y') AS fechaz, date_format(fechades, '%d/%m/%Y') AS fechazz FROM sgcaf610, sgcaf640 WHERE coddepar=departa AND (cta_contab= '$cta' or nidentif= '$nactivo')";
	//echo $sql;
	$result = mysql_query($sql) or die ('Error 200-1 <br>'.$sql.'<br>'.mysql_error());
	$temp = "";
	pantalla_act($result,$accion);
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
	echo "<input type = 'hidden' value ='".$fila['cta_contab']."' name='cta'>";
	if ($accion == 'Editar') { $lectura = 'readonly = "readonly"'; $activada="disabled" ; } else {$lectura=''; $activada='';}
	if ($accion == 'Desincorporar') {
		$ingreso=date("d/m/Y", time());
		}
	else  $nactivo=$fila['nifentif'];
//	<form id="form1" name="form1" method="post" action="">
?>
 <fieldset><legend>Información del Activo Fijo </legend>
  <table width="600" align='center'  border="2">
     
     <tr><td class= "blanco b" width="127" bgcolor='#FFFFFF'>Descripción
      <td colspan="3" class= "blanco b" bgcolor='#FFFFCC'><?php echo $fila['descrip'] ?></td></tr>
	  
	    <td class= "blanco b" width="127"  bgcolor='#FFFFFF'>Nro. de Cuenta</td>
      <td width="80" class="blanco b" bgcolor='#FFFFCC'><?php echo $fila['cta_contab']; ?></td>
	  <td class= "blanco b" width="127" bgcolor='#FFFFFF'>Costo</td>
      <td width="70" class="blanco b" align='right' bgcolor='#FFFFCC'><?php echo number_format ($fila['costo'],2,'.','') ?></td></tr>
    <tr>
	
	 <tr><td class= "blanco b" width="127"  bgcolor='#FFFFFF'>Identificación</td>
      <td width="80" class="blanco b" bgcolor='#FFFFCC'><?php echo $fila['nidentif']?></td>  
	  <td class= "blanco b" width="127"  bgcolor='#FFFFFF'>Depreciación Acumulada</td>
      <td width="70" class="blanco b" align='right' bgcolor='#FFFFCC'><?php echo number_format ($fila['depacfecha'],2,'.','')?></td></tr>
	  
       <td class= "blanco b"  bgcolor='#FFFFFF'>Departamento</td>
      <td class="blanco b" colspan="1" bgcolor='#FFFFCC'>
	   <?php
		    $elcdpto=$fila['departa'];
			$sql="select descpdep, coddepar from sgcaf640 where coddepar='$elcdpto'";
			$resultado=mysql_query($sql);
			//echo $sql; 
			$fila2 = mysql_fetch_assoc($resultado);
			echo $fila2['descpdep'];
	 		?>
		</td>
	  <td class= "blanco b" width="127"  bgcolor='#FFFFFF'>Valor Actual</td>
	   <?php if ($fila['depacfecha'] > $fila['costo']){
			 $valor = '0,00'; }
			else {			 
			 $valor = $fila['costo'] - $fila ['depacfecha']; if ($valor < 0) { $valor = '0,00';}}?>
      <td width="70" class="blanco b" align='right' bgcolor='#FFFFCC'><?php echo number_format ($valor,2,'.','') ?></td></tr>
		
     	<td class= "blanco b"  bgcolor='#FFFFFF'>Fecha de Adquisición</td><td class="blanco b" bgcolor='#FFFFCC'>
		<?php 
	  echo $fila['fechax'] ?></td>
		  <td class= "blanco b" width="127"  bgcolor='#FFFFFF'>Depreciación Anual</td>
      <td width="70" class="blanco b" align='right' bgcolor='#FFFFCC'><?php echo number_format ($fila['depanual'],2,'.','') ?></td></tr>
	 	   
	  <td class= "blanco b" width="127"  bgcolor='#FFFFFF'>Nro. de  Comprobante</td>
	  <td width="80" class="blanco b" bgcolor='#FFFFCC'><?php echo $fila['comprobant'] ?></td>
	  <td class= "blanco b" width="100"  bgcolor='#FFFFFF'>Depreciación Mensual</td>
      <td width="70" class="blanco b" align='right' bgcolor='#FFFFCC'><?php echo number_format($fila['depmensual'],2,'.','') ?></td></tr>
	    
	<td class= "blanco b" width="127"  bgcolor='#FFFFFF'>Vida Útil</td>
	<td class="blanco b" colspan="1" bgcolor='#FFFFCC'><?php echo number_format($fila['vida_util'],0,',','.') ?>
	</td>
	     <td class= "blanco b"  bgcolor='#FFFFFF'>Última Depreciación</td><td class="blanco b" bgcolor='#FFFFCC'>
     <?php 
	echo $fila['fechaz'] ?></td></tr>
  &nbsp;</td> </tr>
  </table>
  </fieldset>
  <fieldset><legend>Información Desincorporación del Activo Fijo</legend>
  <table width="600" border="1">
  	<tr><td class= "blanco b" width="70"  bgcolor='#FFFFFF'>Fecha de Desincorporación</td>
	<td class="blanco b" width="250" bgcolor='#FFFFCC'>
	<?php 
    echo $fila['fechazz']?></td></tr>
		    <td class= "blanco b" width="70"  bgcolor='#FFFFFF'>Motivo</td>
      <td width="250" class="blanco b" bgcolor='#FFFFCC'><?php echo $fila['motivodes'] ?></td></tr>
	  <td class= "blanco b" width="70"  bgcolor='#FFFFFF'>Observaciones</td>
      <td width="250" class="blanco b" bgcolor='#FFFFCC'><?php echo $fila['observdes'] ?></td></tr>
  &nbsp;</td> </tr>
</table>

<?php 
}
?>
   </td>
    </tr>
</table>
</fieldset>
<?php 
function pantalla_act1($result,$accion,$nro) {
if ($accion == 'Verificar1') { $lectura = 'readonly = "readonly"'; $activada="disabled" ; } else {$lectura=''; $activada='';}

?>
   <fieldset><legend>REGISTRO DE ACTIVO FIJO</legend>
  <table width="600" border="2">
       <td class= "blanco b" width="100">Nro. de Cuenta</td>
   	  <td><input name="nro" type="text" id="nro" value="<?php echo $nro; ?>" <?php echo $lectura; ?> size="15" maxlength="07" />*</td> 	 
	    
	   <td class= "blanco b" width="100" >Identificación</td>
     <td><input type="text" size="15" maxlength="04" tabindex='5' name='nactivo1' id="inputString4" onKeyUp="lookup4(this.value);" onBlur="fill4();" value ="<?php echo $nactivo1?>" autocomplete="off"/>
			<div class="suggestionsBox" id="suggestions4" style="display: none;">
				<img src="upArrow.png" style="position: relative; top: -12px; left: 70px; "  alt="upArrow" />
				<div class="suggestionList4" id="autoSuggestionsList4">
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
function pantalla_act2($result,$accion,$nro,$nactivo1)
{
	$deci=$_SESSION['deci'];
	$sep_decimal=$_SESSION['sep_decimal'];
	$sep_miles=$_SESSION['sep_miles'];
	$fila = mysql_fetch_assoc($result);
$hoy = date("d/m/Y"); 
$hoy1 = mktime(0,0,0,date("m"),date("d"),date("Y")); 
$h = date("d/m/Y",$hoy1);
$ant = $hoy1-2592000; 
$mesant = date("d/m/Y",$ant);
	echo "<input type = 'hidden' value ='".$fila['cta_contab']."' name='cta'>";
	if ($accion == 'Editar') { $lectura = 'readonly = "readonly"'; $activada="disabled" ; } else {$lectura=''; $activada='';}
	if ($accion == 'Desincorporar') {
		$ingreso=date("d/m/Y", time());
		}
	else  $nactivo=$fila['nifentif'];
//	<form id="form1" name="form1" method="post" action="">
?>
 <fieldset><legend>Información del Activo Fijo </legend>
  <table width="600" align='center'  border="2">
     
     <tr><td class= "blanco b" width="127" bgcolor='#FFFFFF'>Descripción
      <td colspan="3" class= "blanco b" bgcolor='#FFFFCC'><?php echo $fila['descrip'] ?></td></tr>
	  
	    <td class= "blanco b" width="127"  bgcolor='#FFFFFF'>Nro. de Cuenta</td>
      <td width="80" class="blanco b" bgcolor='#FFFFCC'><?php echo $fila['cta_contab']; ?></td>
	  <td class= "blanco b" width="127" bgcolor='#FFFFFF'>Costo</td>
      <td width="70" class="blanco b" align='right' bgcolor='#FFFFCC'><?php echo number_format ($fila['costo'],2,'.','') ?></td></tr>
    <tr>
	
	 <tr><td class= "blanco b" width="127" bgcolor='#FFFFFF'>Identificación</td>
      <td width="80" class="blanco b" bgcolor='#FFFFCC'>
	   <input type="hidden" name="nactivo" value="<?php echo $fila['nidentif']?>"/>  <?php echo $fila['nidentif']?></td>  
		  <td class= "blanco b" width="127"  bgcolor='#FFFFFF'>Depreciación Acumulada</td>
      <td width="70" class="blanco b" align='right' bgcolor='#FFFFCC'><?php echo number_format ($fila['depacfecha'],2,'.','')?></td></tr>
	  
       <td class= "blanco b"  bgcolor='#FFFFFF'>Departamento</td>
      <td class="blanco b" colspan="1" bgcolor='#FFFFCC'>
	   <?php
		    $elcdpto=$fila['departa'];
			$sql="select descpdep, coddepar from sgcaf640 where coddepar='$elcdpto'";
			$resultado=mysql_query($sql);
			//echo $sql; 
			$fila2 = mysql_fetch_assoc($resultado);
			echo $fila2['descpdep'];
	 		?>
		</td>
			  <td class= "blanco b" width="127"  bgcolor='#FFFFFF'>Valor Actual</td>
	  <?php if ($fila['depacfecha'] > $fila['costo']){
			 $valor = '0,00'; }
			else {			 
			 $valor = $fila['costo'] - $fila ['depacfecha']; if ($valor < 0) { $valor = '0,00';}}?>
      <td width="70" class="blanco b" align='right' bgcolor='#FFFFCC'><?php echo number_format ($valor,2,'.','') ?></td></tr>
		
     	<td class= "blanco b"  bgcolor='#FFFFFF'>Fecha de Adquisición</td><td class="blanco b" bgcolor='#FFFFCC'>
		<?php 
	  echo $fila['fechax'] ?></td>
		  <td class= "blanco b" width="127"  bgcolor='#FFFFFF'>Depreciación Anual</td>
      <td width="70" class="blanco b" align='right' bgcolor='#FFFFCC'><?php echo number_format ($fila['depanual'],2,'.','') ?></td></tr>
	 	   
	  <td class= "blanco b" width="127"  bgcolor='#FFFFFF'>Nro. de  Comprobante</td>
	  <td width="80" class="blanco b" bgcolor='#FFFFCC'><?php echo $fila['comprobant'] ?></td>
	  <td class= "blanco b" width="100"  bgcolor='#FFFFFF'>Depreciación Mensual</td>
      <td width="70" class="blanco b" align='right' bgcolor='#FFFFCC'><?php echo number_format($fila['depmensual'],2,'.','') ?></td></tr>
	    
	<td class= "blanco b" width="127"  bgcolor='#FFFFFF'>Vida Útil</td>
	<td class="blanco b" colspan="1" bgcolor='#FFFFCC'><?php echo number_format($fila['vida_util'],0,',','.') ?>
	</td>
	     <td class= "blanco b"  bgcolor='#FFFFFF'>Última Depreciación</td><td class="blanco b" bgcolor='#FFFFCC'>
     <?php 
	echo $fila['fechaz'] ?></td></tr>
  &nbsp;</td> </tr>
  </table>
  </fieldset>
  <fieldset><legend>Información Desincorporación del Activo Fijo</legend>
  <table width="600" border="1">
  	<tr><td class= "blanco b" bgcolor='#FFFFFF'>Fecha de Desincorporación</td><td class="rojo" bgcolor='#FFFFCC'>
       <?php $hoy=date("d/m/Y", time());
	    $mini=convertir_fechadmy($fila['fechades']);
//		escribe_formulario(fechazz, form1.fechazz, 'd/m/yyyy',convertir_fechadmy($fila['fechades']), $mesant, $hoy, '1', '3') 
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
	<input type="hidden" name="fechazz" id="fechazz" value=" <?php  echo $fechanueva; ?>"/>
   <span style="background-color: #ff8; cursor: default;"
         onmouseover="this.style.backgroundColor='#ff0';"
         onmouseout="this.style.backgroundColor='#ff8';"
         id="show_ingcapu" 
   ><?php  echo ($hoy); ?></span> *
<script type="text/javascript">
    Calendar.setup({
//		showAt(220, 250)
//		position       : 	{100,300},
        inputField     :    "fechazz",     // id of the input field
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
</td></tr>
	     <td class= "blanco b" width="130" bgcolor='#FFFFFF'>Motivo</td>
      <td width="80" class="rojo" bgcolor='#FFFFCC'>
	  <input type="hidden" name="mini" value="<?php echo $mini;?>"/>
	   <?php
	  echo '<select name="cod" size="1">';
	$sql="select codmot, motivodesc from sgcaf650 order by codmot";
	$resultado=mysql_query($sql);
				while ($fila3 = mysql_fetch_assoc($resultado)) {
				echo '<option value="'.$fila3['codmot'].'" '.(($motivo==$fila3['codmot'])?'selected':'').'>'.$fila3['motivodesc'].'</option>';}
	 	echo '</select>';?>*
			</td></tr>
    <td class= "blanco b" width="70"  bgcolor='#FFFFFF'>Observaciones</td>
      <td width="250" class="rojo" bgcolor='#FFFFCC'colspan="3">
	 <textarea name="observacion1" type="text" id="observacion1" onChange="conMayusculas(this)" cols="80" rows="03" maxlength="05" value="<?php echo $fila['observdes'];?>" /></textarea>*</td><tr>	
	&nbsp;</td></tr>
</table>
<?php 
}
?>