<?php
include("head.php");
include("paginar.php");
//include("popcalendario/escribe_formulario.php");
if (!$link OR !$_SESSION['empresa']) {
	include("noempresa.php");
	exit;
}
if ($accionIn == 'Anadir') 
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
$nactivo=$_GET['nactivo'];
$ip = $_SERVER['HTTP_CLIENT_IP'];
if (!$ip) {$ip = $_SERVER['REMOTE_ADDR'];}
?>
<?php
 
if ($accion == 'Anadir1') {
    //echo '1'; 
	extract($_POST);
	$codigo = $_POST['codigo'];
    //echo $nactivo; 
	if ($codigo) {
	//echo '2'; 
		$sql = "select * from sgcaf640 where descpdep = '$nombre'";
		//echo $sql;
		$result=mysql_query($sql);
		if (mysql_num_rows($result) > 0) die ('No se puede registrar el Departamento '.$nombre.'  ya existe ');
		$sql="INSERT INTO sgcaf640(coddepar, descpdep) 
		VALUES ('$codigo', '$nombre')";
		//echo $sql;
		mysql_query($sql)or die ("<p />El usuario $usuario no tiene permisos para añadir Clientes o cuenta ya existente.<br>".mysql_error()."<br>".$sql);
		$accion="";
		}
}
if ($accion == 'Editar1') {
	extract($_POST);
	$codigo= $_POST['codigo'];
	$sql="UPDATE sgcaf640 SET descpdep='$nombre' WHERE coddepar='$codigo'";
    	//echo $sql;
		mysql_query($sql) or die ("<p />El usuario $usuario no tiene permisos para modificar Clientes <br>".mysql_error()."<br>".$sql);
		$accion='';
}
if ($accion == 'Borrar1') {
	extract($_POST);
	$codigo = $_POST['codigo'];
	$sql="SELECT *FROM sgcaf610 where departa = '$codigo'";
	$result=mysql_query($sql);
	$row= mysql_fetch_assoc($result);
	if (mysql_num_rows($result) == 0) {
	mysql_query("DELETE FROM sgcaf640 WHERE coddepar = $codigo") or die ("<p />El usuario $usuario no tiene permisos para borrar Cuentas");
	$accion='';}
	else {	
	echo "<p />DEPARTAMENTO $nombre  NO PUEDE SER BORRADO</div></body></html>"; 
	$accion='';}
}
?>
<?php 
if (!$accion) {
//	echo "<div id='div1'>";
    echo "<table class='basica 100 hover' width=''><tr>";
	echo '<th>   </th><th><a href=?ord=coddepar>Código</a></th><th><a href=?ord=descpdep>Nombres</a><br>';
	echo '[ <a href="departamentos.php?accion=Anadir"> Nuevo Departamento</a> ]</a><br>';
	echo '</th></th>';
	$ord = $_GET['ord'];
	if (!$ord) $ord='coddepar';
	$sql = "SELECT * FROM sgcaf640 ORDER BY $ord";
	$rs = mysql_query($sql);
	//echo $sql;

// bucle de listado

	while($row=mysql_fetch_array($rs)) {
		echo "<tr>";
		echo "<td><a href='departamentos.php?accion=Borrar&codigo=".$row['coddepar']."'><img src='imagenes/16-em-cross.png' width='16' height='16' border='0' title='Eliminar' alt='Eliminar'/></a></td>";
		echo "<td class='centro'>";
		echo "<a href='departamentos.php?accion=Editar&codigo=".$row['coddepar']."'>";
		echo $row['coddepar']."</a></td>";
		echo "<td class='centro'>";
		echo "<a href='departamentos.php?accion=Editar&codigo=".$row['coddepar']."'>";
		echo $row['descpdep']."</a></td>";
				
	}
	echo "</table>";

//	echo "</div>";
}
?>
<?php
if ($accion == "Anadir") {
	echo "<form action='departamentos.php?accion=Anadir1' name='form1' method='post' onsubmit='return valdep(form1)'>";
	$result=mysql_query($sql);
    pantalla_act2($result,$accion);
	echo "<input type = 'submit' value = 'Grabar Datos'>";
}
if ($accion == "Editar") {
	//echo '<div id="div1">';
	$sql="SELECT * FROM sgcaf640 where coddepar= '$codigo'";
	//echo $sql;
	$result = mysql_query($sql) or die ('Error 200-1 <br>'.$sql.'<br>'.mysql_error());
	echo "<form enctype='multipart/form-data' action='departamentos.php?accion=Editar1' name='form1' method='post' onsubmit='return valdep(form1)'>";
	pantalla_act($result,$accion);
    echo "<br><input type = 'submit' value = 'Confirmar cambios'></form>\n";
	/*cambiar esto ojo*/
	//echo '</div>';
}
if ($accion == "Borrar") {
	$sql="SELECT * FROM sgcaf640 where coddepar= '$codigo'";
	//echo $sql;
	$result = mysql_query($sql) or die ('Error 200-1 <br>'.$sql.'<br>'.mysql_error());
	echo "<form enctype='multipart/form-data' action='departamentos.php?accion=Borrar1' name='form1' method='post' onsubmit='return valdep()'>";
	pantalla_act3($result,$accion);
    echo "<br><input type = 'submit' value = 'Confirmar Eliminación'></form>\n";
}
?>
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
 <label><fieldset><legend>DEPARTAMENTO de <?php echo $fila['descpdep']; ?></legend>
  	<table width="410" border="2">
    <td class= "blanco b" width="50" bgcolor='#FFFFCC'>Código</td>
   	<td class='rojo'><input name="codigo" type="text" id="codigo" value="<?php echo $fila['coddepar']; ?>" <?php echo $lectura; ?> size="4" maxlength="4" />*</td> 					
	<td class= "blanco b" width="50" bgcolor='#FFFFCC'>Nombre
	<td  class='rojo'><input name="nombre" type="text" id="nombre" value="<?php echo $fila['descpdep']; ?>"size="45" maxlength="50" />*</td><tr>
		
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
  	 <label><fieldset><legend>REGISTRO DEPARTAMENTO</legend>
  	<table width="410" border="2">
    <td class= "blanco b" width="50" bgcolor='#FFFFCC'>Código</td>
   	<td class='rojo'><input name="codigo" type="text" id="codigo" value="<?php 
	$sql = "select * from sgcaf640 order by coddepar"; 
		$rs = mysql_query($sql); 
	$num=0;
		while ($row=mysql_fetch_array($rs))
		{	
		if ($num<=$row['coddepar']);
		{
		$num=$row['coddepar']; 
		}
		}
		$nunm=$num+1;
		echo ceroizq($nunm,'3'); ?>" <?php echo $lectura; ?> size="4" maxlength="4" />*</td> 					
	<td class= "blanco b" width="50" bgcolor='#FFFFCC'>Nombre
	<td class='rojo'><input name="nombre" type="text" id="nombre" value="<?php ?>"size="45" maxlength="50" />*</td><tr>
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
 <label><fieldset><legend>DEPARTAMENTO de <?php echo $fila['descpdep']; ?></legend>
  	<table width="410" border="2">
    <td class= "blanco b" width="50" bgcolor='#FFFFCC'>Código</td>
   	<td class='rojo'><input name="codigo" type="text" id="codigo" value="<?php echo $fila['coddepar']; ?>" <?php echo $lectura; ?> size="4" maxlength="4" />*</td> 					
	<td class= "blanco b" width="50" bgcolor='#FFFFCC'>Nombre
	<td class='rojo'><input name="nombre" type="text" id="nombre" value="<?php echo $fila['descpdep']; ?>"<?php echo $lectura; ?> size="45" maxlength="50" />*</td><tr>
  &nbsp;</td> </tr>
</table>
<?php 
}
?>