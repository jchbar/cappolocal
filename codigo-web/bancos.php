<?php
include("head.php");
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

if ($accion == 'Anadir1') {
    //echo '1'; 
	extract($_POST);
	$codigo = $_POST['codigo'];
    //echo $nactivo; 
	if ($codigo) {
	//echo '2'; 
		$sql = "select * from sgcaf843 where  nro_cta_ba= '$nro_cta_ba' and emitircheque='1'";
		$result=mysql_query($sql);
		echo $sql; 
		if (mysql_num_rows($result) > 0) die ('No se puede asignar este Nro. de Cuenta ya esta registrada a '.$nombre.'');
		
		$sql = "select * from sgcaf843 where  cue_banco= '$cue_banco' and emitircheque='1'";
		$result=mysql_query($sql);
		echo $sql; 
		if (mysql_num_rows($result) > 0) die ('No se puede asignar este Nro. de Cuenta Contable ya esta registrada a '.$nombre.'');
		
		$sql="INSERT INTO sgcaf843(cod_banco, nombre_ban, nro_cta_ba, cue_banco, estado, emitircheque) 
		VALUES ('$codigo', '$nombre', '$nro_cta_ba', '$cue_banco', '$estado', '1')";
		//echo $sql;
		mysql_query($sql)or die ("<p />El usuario $usuario no tiene permisos para añadir Clientes o cuenta ya existente.<br>".mysql_error()."<br>".$sql);
		$accion="";
		}
}
if ($accion == 'Editar1') {
	extract($_POST);
	$codigo= $_POST['codigo'];
	$sql="UPDATE sgcaf843 SET nombre_ban='$nombre', nro_cta_ba ='$nro_cta_ba', cue_banco='$cue_banco', estado='$estado' WHERE cod_banco='$codigo' and emitircheque='1'";
    	//echo $sql;
		mysql_query($sql) or die ("<p />El usuario $usuario no tiene permisos para modificar Clientes <br>".mysql_error()."<br>".$sql);
		$accion='';
}
if ($accion == 'Borrar1') {
	extract($_POST);
	$codigo = $_POST['codigo'];
	$sql="SELECT *FROM sgcaf840 where mche_banco = '$codigo'";
	$result=mysql_query($sql);
	$row= mysql_fetch_assoc($result);
	if (mysql_num_rows($result) == 0) {
	mysql_query("DELETE FROM sgcaf843 WHERE cod_banco = $codigo") or die ("<p />El usuario $usuario no tiene permisos para borrar Cuentas");
	$accion='';}
	else {	
	echo "<h2><p />Banco $nombre  NO PUEDE SER BORRADO</div></body></html></h2>"; 
	$accion='';}
}
?>
<?php 
if (!$accion) {
//	echo "<div id='div1'>";
    echo "<table class='basica 100 hover' width=''><tr>";
	echo '<th>   </th><th>   </th><th><a href=?ord=cod_banco>Código</a></th><th><a href=?ord=nombre_ban>Nombres</a><br>';
	echo '[ <a href="bancos.php?accion=Anadir"> Nuevo Banco</a> ]</a><br>';
	echo '<th><a href=?ord=nro_cta_ba>Nro. de Cuenta</a></th><th><a href=?ord=cue_banco>Nro. de Cuenta Contable</a><th>Estado</th><br>';
	echo '</th></th>';
	$ord = $_GET['ord'];
	if (!$ord) $ord='cod_banco';
	$sql = "SELECT * FROM sgcaf843 where emitircheque='1' ORDER BY $ord ";
	$rs = mysql_query($sql);
	//echo $sql;

// bucle de listado

	while($row=mysql_fetch_array($rs)) {
		echo "<tr>";
		echo "<td><a href='bancos.php?accion=Editar&codigo=".$row['cod_banco']."'><img src='imagenes/16-em-pencil.png' width='16' height='16' border='0' title='Editar' alt='Editar'/></a></td>";
			echo "<td><a href='bancos.php?accion=Borrar&codigo=".$row['cod_banco']."'><img src='imagenes/16-em-cross.png' width='16' height='16' border='0' title='Eliminar' alt='Eliminar'/></a></td>";
		echo "<td class='centro'>";
		echo $row['cod_banco']."</a></td>";
		echo "<td class='centro'>";
		echo $row['nombre_ban']."</a></td>";
		echo "<td class='centro'>";
		echo $row['nro_cta_ba']."</a></td>";
		echo "<td class='centro'>";
		echo $row['cue_banco']."</a></td>";
		if ($row['estado'] =='1') {
		echo "<td class='centro'>";
		echo "<a><img src='imagenes/24-em-check.png' width='16' height='16' border='0' title='Activo' alt='Activo' /></a></td>";}
		else if ($row['estado'] =='2') {	
		echo "<td class='centro'>";
		echo "<a><img src='imagenes/16-circle-blue-delete.png' width='16' height='16' border='0' title='Inactivo' alt='Inactivo'/></a></td>";}
	}
	echo "</table>";
    
//	echo "</div>";
}
?>
<?php
if ($accion == "Anadir") {
    echo '<div id="div1">';
	echo "<form action='bancos.php?accion=Anadir1' name='form1' method='post' onsubmit='return valban(form1)'>";
	$result=mysql_query($sql);
    pantalla_act2($result,$accion);
	echo "<input type = 'submit' value = 'Grabar Datos'>";
	echo '</div>';
// 	echo "</form>\n";
}
if ($accion == "Editar") {
	echo '<div id="div1">';
	$sql="SELECT * FROM sgcaf843 where cod_banco= '$codigo' and emitircheque='1'";
	//echo $sql;
	$result = mysql_query($sql) or die ('Error 200-1 <br>'.$sql.'<br>'.mysql_error());
	$temp = "";
	echo "<form enctype='multipart/form-data' action='bancos.php?accion=Editar1' name='form1' method='post' onsubmit='return valban(form1)'>";
	pantalla_act($result,$accion);
    echo "<br><input type = 'submit' value = 'Confirmar cambios'></form>\n";
	/*cambiar esto ojo*/
	echo '</div>';
}
if ($accion == "Borrar") {
echo '<div id="div1">';
	$sql="SELECT * FROM sgcaf843 where cod_banco= '$codigo' and emitircheque='1'";
	//echo $sql;
	$result = mysql_query($sql) or die ('Error 200-1 <br>'.$sql.'<br>'.mysql_error());
	$temp = "";
	echo "<form enctype='multipart/form-data' action='bancos.php?accion=Borrar1' name='form1' method='post' onsubmit='return valban1(form1)'>";
	pantalla_act3($result,$accion);
    echo "<br><input type = 'submit' value = 'Confirmar Eliminación'></form>\n";
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
if ($accion == 'Editar') { $lectura = 'readonly = "readonly"'; $activada="disabled" ; } else {$lectura=''; $activada='';}
?>
 <label><fieldset><legend>Banco <?php echo $fila['nombre_ban']; ?></legend>
  	<table width="600" border="1">
    <td class= "blanco b" width="100"  bgcolor="#FFFFCC">Código</td>
   	<td class='rojo'><input name="codigo" type="text" id="codigo" value="<?php echo $fila['cod_banco']; ?>" <?php echo $lectura; ?> size="20" maxlength="20" />*</td> 					
	<td class= "blanco b" width="100"  bgcolor="#FFFFCC" >Nombre
	<td class='rojo'><input name="nombre" type="text" id="nombre" value="<?php echo $fila['nombre_ban']; ?>"size="45" maxlength="50" />*</td><tr>
	
	<td class= "blanco b" width="100"  bgcolor="#FFFFCC">Nro. de Cuenta
	<td class='rojo'><input name="nro_cta_ba" type="text" id="nro_cta_ba" value="<?php echo $fila['nro_cta_ba']; ?>"size="21" maxlength="20" />*</td>
	<td class= "blanco b" width="100"  bgcolor="#FFFFCC" >Nro. de Cuenta Contable 
	<td class='rojo'><input name="cue_banco" type="text" id="cue_banco" value="<?php echo $fila['cue_banco']; ?>"size="26" maxlength="27" />*</td><tr>
	<td class= "blanco b" width="100"  bgcolor="#FFFFCC">Estado
	<td colspan="4">
      <?php $estado=$fila['estado']; ?>
    <input type="radio" name="estado" value="1" <?php if ($estado < 2) echo " checked"?>/> Activo</label> 
	 <input type="radio" name="estado" value="2" <?php if ($estado == 2) echo " checked"?>/> Inactivo</label></td>
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
function pantalla_act2 ($result,$accion){
$deci=$_SESSION['deci'];
$sep_decimal=$_SESSION['sep_decimal'];
$sep_miles=$_SESSION['sep_miles'];
//echo $sql; 
if ($accion == 'Anadir') { $lectura = 'readonly = "readonly"'; $activada="disabled" ; } else {$lectura=''; $activada='';}
?>
  	 <label><fieldset><legend>REGISTRO DE BANCO </legend>
  	<table width="600" border="2">
    <td class= "blanco b" width="100" bgcolor="#FFFFCC">Código</td>
   	<td class='rojo'><input name="codigo" type="text" id="codigo" value="<?php 
	$sql = "select * from sgcaf843 where emitircheque='1' order by cod_banco"; 
		$rs = mysql_query($sql); 
	$num=0;
		while ($row=mysql_fetch_array($rs))
		{	
		if ($num<=$row['cod_banco']);
		{
		$num=$row['cod_banco']; 
		}
		}
		$nunm=$num+1;
		echo ceroizq($nunm,'4'); ?>" <?php echo $lectura; ?> size="20" maxlength="21" />*</td> 					
	<td class= "blanco b" width="100"  bgcolor="#FFFFCC" >Nombre
	<td class='rojo'><input name="nombre" type="text" id="nombre" value="<?php ?>"size="45" maxlength="50" />*</td><tr>
	
	<td class= "blanco b" width="100"  bgcolor="#FFFFCC">Nro. de Cuenta
	<td class='rojo'><input name="nro_cta_ba" type="text" id="nro_cta_ba" value="<?php ?>"size="21" maxlength="20" />*</td>
	<td class= "blanco b" width="100"  bgcolor="#FFFFCC">Nro. de Cuenta Contable 
	<td class='rojo'><input name="cue_banco" type="text" id="cue_banco" value="<?php  ?>"size="26" maxlength="27" />*</td><tr>
	
	<td class= "blanco b" width="100"  bgcolor="#FFFFCC">Estado
	<td colspan="4" >
      <?php ?>
    <input type="radio" name="estado" value="1" <?php if ($estado < 2) echo " checked"?>/> Activo</label> 
	 <input type="radio" name="estado" value="2" <?php if ($estado == 2) echo " checked"?>/> Inactivo</label></td>
	
	
	
	&nbsp;</td></tr> 
</table>
<?php 
}
?>
<?php 
function pantalla_act3($result,$accion)
{
	$deci=$_SESSION['deci'];
	$sep_decimal=$_SESSION['sep_decimal'];
	$sep_miles=$_SESSION['sep_miles'];
	$fila = mysql_fetch_assoc($result);
if ($accion == 'Borrar') { $lectura = 'readonly = "readonly"'; $activada="disabled" ; } else {$lectura=''; $activada='';}
?>
 <label><fieldset><legend>Banco <?php echo $fila['nombre_ban']; ?></legend>
  	<table width="600" border="2">
    <td class= "blanco b" width="100" bgcolor="#FFFFCC">Código</td>
   	<td class='rojo'><input name="codigo" type="text" id="codigo" value="<?php echo $fila['cod_banco']; ?>" <?php echo $lectura; ?> size="20" maxlength="21" />*</td> 					
	<td class= "blanco b" width="100" bgcolor="#FFFFCC">Nombre
	<td class='rojo'><input name="nombre" type="text" id="nombre" value="<?php echo $fila['nombre_ban']; ?>"<?php echo $lectura; ?> size="45" maxlength="50" />*</td><tr>
	
	<td class= "blanco b" width="100" bgcolor="#FFFFCC">Nro. de Cuenta
	<td class='rojo'><input name="nro_cta_ba" type="text" id="nro_cta_ba" value="<?php echo $fila['nro_cta_ba']; ?>" <?php echo $lectura; ?>size="21" maxlength="20" />*</td>
	<td class= "blanco b" width="100" bgcolor="#FFFFCC">Nro. de Cuenta Contable 
	<td class='rojo'><input name="cue_banco" type="text" id="cue_banco" value="<?php echo $fila['cue_banco']; ?>" <?php echo $lectura; ?>size="26" maxlength="27" />*</td><tr>
	
	<td class= "blanco b" width="100" bgcolor="#FFFFCC">Estado
	<td colspan="4">
      <?php $estado=$fila['estado']; ?>
    <input type="radio" name="estado" value="<?php $estado=$fila['estado']; ?>" <?php 
	if ($estado < 2) {
	$a='Activo'; 
	}
	if ($estado == 2) {
	$a='Inactivo';
	}
	echo "checked"
	?>/> <?php echo $a;  ?></label> 
	 </td>
	
	
  &nbsp;</td> </tr>
</table>
<?php 
}
?>