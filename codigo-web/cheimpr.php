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

if ($accionIn=="Anular_Cheque") {
   // echo '<div id="div1">';
	echo "<form action='cheimpr.php?accionIn=Anular_Cheque1' name='form1' method='post' onsubmit='return explicacion_cheque(form1)'>";
	$sql= "SELECT *FROM sgcaf843";
	$result=mysql_query($sql);
	$nro=$codigo; 
    pantalla_act_anular($result,$accionIn,$codigo,$numero,$nombre);
	echo "<input type = 'submit' value = 'Anular'>";
	//echo '</div>';
}

if ($accionIn=="Verificar") 
{
   // echo '<div id="div1">';
/*
   echo $codigo; 
   echo $nombre; 
*/   
	echo "<form action='cheimpr.php?accionIn=Verificar1' name='form1' method='post' onsubmit='return explicacion_cheque(form1)'>";  
	$sql= "SELECT nombre_ban, nro_cta_ba, plantilla FROM sgcaf843 where cod_banco='$codigo' and emitircheque='1'"; 
	$resultado=mysql_query($sql);
	$bb= mysql_fetch_assoc($resultado);
	$nro=$bb['nro_cta_ba']; 
	$nombre=$bb['nombre_ban']; 
	$plantilla=$bb['plantilla']; 
	if ($plantilla == "")
		$plantilla = "cheimpr_pdf.php";
	$sql= "SELECT* FROM sgcaf840 where mche_statu='L' and verificado='1' and mche_banco='$codigo' ORDER BY mche_orden"; 
	$result=mysql_query($sql);
//	echo $sql; 
    pantalla_imprimir($result,$accionIn,$codigo,$nombre,$nro,$plantilla);
	echo "<input type = 'submit' value = 'Imprimir'>";
}
?>
<?php 
if ($accionIn=="Verificar1") 
{
   // echo '<div id="div1">';
   echo $nro; 
   echo $codigo; 
   echo $nombre; 
    
	echo "<p />";
	echo 'limite:';
	echo count($_POST['numero']);
	echo "<p />";
	$a=''; 
	$i=0;
	while ($i<count($_POST['numero'])) 
	{
	echo "<p />";
	echo 'condicion:'; 
	echo $a; 
	echo "<p />";
	echo 'indice:'; 
	echo $i;
	echo "<p />";
	echo $_POST['numero'][$i];
	echo "<p />";
	$numero= $_POST['numero'][$i];
	echo $numero; 
	echo "<p />";
	echo $codigo;  
	//$codigo="0002";
    ?>
<html>
<head>
<title></title>

<script language="JavaScript">
var numero='<? echo $numero;?>'
var codigo='<? echo $codigo;?>'
var nro='<? echo $nro;?>'
var nombre='<? echo $nombre;?>'
var plantilla='<? echo $plantilla;?>'
//checkDoubleConfirmation(); 
ventana_pdf(numero,codigo,nro,nombre,plantilla); 
</script>
</head> 
<body>
</body>
</html>  
<?php
//echo 'hola'; 


$sql="UPDATE sgcaf840 SET mche_statu='I'
		WHERE mche_orden='$numero' and mche_banco='$codigo'";
//		echo $sql;
//		echo "<p />";
		mysql_query($sql)or die ("<p />El usuario $usuario no tiene permisos para añadir Clientes o cuenta ya existente.<br>".mysql_error()."<br>".$sql);
//echo 'a'; 

$i++;
}
$accionIn='';
}	
?>

<?php 
if ($accionIn == 'Anular_Cheque1')
 {   //echo '<div id="div1">';
    $hoy = date("d/m/Y");
	$fecha=convertir_fecha($hoy); 
//	echo 'HOLA';
	$sql= "SELECT *FROM sgcaf846 where banco='$codigo' and estatus='+' and descrip='' order by nro_che";
//	echo $sql; 
	$result=mysql_query($sql);
	if (mysql_num_rows($result) == 0) 
	{
	?>
	<html>
<head>
<title></title>
<script language="JavaScript">


var agree3=confirm ("Este Banco no tiene cheque disponible. ¿Desea registrar NUEVA CHEQUERA?");

if (agree3)

history.go(location="http://servercappo/cajaweb/chequeras.php");

else

document.write("");

</script>
</head> 

<body>
</body>

</html> 
	
<?php
	$accionIn="Incluir"; 
	}
	else {
	$sql= "SELECT *FROM sgcaf846 where banco='$codigo' and estatus='+' and descrip='' order by nro_che";
	//echo $sql; 
	$result=mysql_query($sql);
	$fila99 = mysql_fetch_assoc($result);
	$nche=$fila99['nro_che']; 
	
//	echo '1'; 
	$sql="SELECT * FROM sgcaf840, sgcaf843 where mche_orden='$numero' and nro_cta_ba='$codigo' and cod_banco=mche_banco and emitircheque='1'"; 
//	echo "<p />";
	$result=mysql_query($sql); 
	$b= mysql_fetch_assoc($result);
	$nombre= $b['nombre_ban']; 
	$mche_nombr = $b['mche_nombr']; 
	$mche_monto= $b['mche_monto']; 
	$mche_descr= $b['mche_descr'];
	$mche_statu= $b['mche_statu']; 
	$mche_banco= $b['mche_banco']; 
	$mche_prest= $b['mche_prest']; 
	$mche_fecha= $b['mche_fecha'];  
	$sustituto= $b['sustituto'];
	$verificado= $b['verificado']; 
	$fecha_verific= $b['fecha_verific']; 
	$ip_ver= $b['ip_ver'];  
//	echo $sql; 
//	echo "<p />";
//	echo'2'; 
//	echo 'hello'; 
	
	$sql="INSERT INTO sgcaf840(mche_orden,mche_fecha,mche_nombr,mche_monto,mche_descr,mche_statu,mche_banco,mche_prest,verificado,fecha_verific,ip_ver) 
	VALUES ('$nche','$mche_fecha','$mche_nombr','$mche_monto','$mche_descr','$mche_statu','$mche_banco','$mche_prest','$verificado', '$fecha_verific', '$ip_ver')";
//	echo $sql;
//	echo "<p />";
	mysql_query($sql)or die ("<p />El usuario $usuario no tiene permisos para añadir Clientes o cuenta ya existente.<br>".mysql_error()."<br>".$sql);
	
//	echo '3';
	$sql="UPDATE sgcaf840 SET mche_statu='A', sustituto='$nche'
		WHERE mche_orden='$numero' and mche_banco='$mche_banco'";
//		echo $sql;
//		echo "<p />";
		mysql_query($sql)or die ("<p />El usuario $usuario no tiene permisos para añadir Clientes o cuenta ya existente.<br>".mysql_error()."<br>".$sql);
	
	$sql="INSERT INTO sgcaf840E(mche_orden,mche_fecha,mche_nombr,mche_monto,mche_descr,mche_statu,mche_banco,mche_prest, ip, mche_observacion, fecha_borrar, sustituto,verificado,fecha_verific,ip_ver) 
	VALUES ('$numero','$mche_fecha','$mche_nombr','$mche_monto','$mche_descr','$mche_statu','$mche_banco','$mche_prest', '$ip', '$explicacion', '$fecha', '$nche', '$verificado', '$fecha_verific', '$ip_ver')";
//	echo $sql;
//	echo "<p />";
	mysql_query($sql)or die ("<p />El usuario $usuario no tiene permisos para añadir Clientes o cuenta ya existente.<br>".mysql_error()."<br>".$sql);
	
	
//	echo '4';
	$sql="UPDATE sgcaf846 SET descrip='CHEQUE ANULADO SUSTITUIDO POR EL CHEQUE NRO. $nche'
		WHERE nro_che='$numero' and banco='$codigo'";
//		echo $sql;
//		echo "<p />";
		mysql_query($sql)or die ("<p />El usuario $usuario no tiene permisos para añadir Clientes o cuenta ya existente.<br>".mysql_error()."<br>".$sql);
		
//     echo '5'; 
//	$sql="SELECT * FROM sgcaf841 WHERE mche_banco = '$mche_banco' and mche_orden= '$numero'";
	$resultado1=mysql_query($sql);  
//	echo $sql; 
//	echo "<p />";
	while($row1=mysql_fetch_array($resultado1)) 
	{
	$mche_cuent=$row1['mche_cuent']; 
	$mche_debcr=$row1['mche_debcr']; 
	$mche_descr=$row1['mche_descr']; 
	$mche_monto1=$row1['mche_monto1']; 
	$mche_monto2=$row1['mche_monto2']; 
	$mche_monto=$row1['mche_monto']; 
	$mche_banco=$row1['mche_banco']; 
	$registro_original=$row1['registro']; 
//	echo $sql; 
//	echo '6'; 
	$sql="INSERT INTO sgcaf841E(mche_orden,mche_cuent,mche_debcr,mche_descr,mche_monto1,mche_monto2,mche_monto,mche_banco, registro_original) 
	VALUES ('$numero','$mche_cuent','$mche_debcr','$mche_descr','$mche_monto1','$mche_monto2','$mche_monto','$mche_banco', '$registro_original')";
//	echo $sql;
//	echo "<p />";
	mysql_query($sql)or die ("<p />El usuario $usuario no tiene permisos para añadir Clientes o cuenta ya existente.<br>".mysql_error()."<br>".$sql);
	}
	
	$sql="UPDATE sgcaf846 SET descrip='$mche_nombr', ip='$ip', fecha='$mche_fecha', estatus='-'
				WHERE nro_che='$nche' and banco='$codigo'";
//				echo $sql;
				mysql_query($sql)or die ("<p />El usuario $usuario no tiene permisos para añadir Clientes o cuenta ya existente.<br>".mysql_error()."<br>".$sql);
	 
	$sql="SELECT * FROM sgcaf841 WHERE mche_banco = '$mche_banco' and mche_orden= '$numero'";
	$resultado1=mysql_query($sql);  
//	echo $sql; 
//	echo "<p />";
	while($row1=mysql_fetch_array($resultado1)) 
	{
	$mche_cuent=$row1['mche_cuent']; 
	$mche_debcr=$row1['mche_debcr']; 
	$mche_descr=$row1['mche_descr']; 
	$mche_monto1=$row1['mche_monto1']; 
	$mche_monto2=$row1['mche_monto2']; 
	$mche_monto=$row1['mche_monto']; 
	$mche_banco=$row1['mche_banco']; 
	$sql="INSERT INTO sgcaf841(mche_orden,mche_cuent,mche_debcr,mche_descr,mche_monto1,mche_monto2,mche_monto,mche_banco) 
	VALUES ('$nche','$mche_cuent','$mche_debcr','$mche_descr','$mche_monto1','$mche_monto2','$mche_monto','$mche_banco')";
	mysql_query($sql)or die ("<p />El usuario $usuario no tiene permisos para añadir Clientes o cuenta ya existente.<br>".mysql_error()."<br>".$sql);
	}
	$accionIn='';
	}

	echo '</div>';
}

if ($accionIn == 'Anular_Cheque2')
 {   //echo '<div id="div1">';
    $hoy = date("d/m/Y");
	$fecha=convertir_fecha($hoy); 
//	echo $codigov; 
//	echo $numero; 
	$sql= "SELECT *FROM sgcaf843 where nro_cta_ba='$codigo' and emitircheque='1'";
//	echo $sql; 
	$result=mysql_query($sql);
    $fila666 = mysql_fetch_assoc($result);
	$cue_banco= $fila666['cue_banco']; 
	$cod_banco= $fila666['cod_banco'];
	$nombre= $fila666['nombre_ban'];
//	echo 'HOLA';

	$sql= "SELECT * FROM sgcaf846 where banco='$codigo' and estatus='+' and descrip='' order by nro_che";
//	echo $sql; 
	$result=mysql_query($sql);
    $fila99 = mysql_fetch_assoc($result);
	$nche=$fila99['nro_che']; 
//	echo '1'; 
	$sql="SELECT * FROM sgcaf840, sgcaf843 where mche_orden='$numero' and nro_cta_ba='$codigov' and cod_banco=mche_banco and emitircheque='1'"; 
//	echo "<p />";
	$result=mysql_query($sql); 
	$b= mysql_fetch_assoc($result);
	$nombre1= $b['nombre_ban']; 
	$cue_bancov= $b['cue_banco']; 
	$mche_nombr = $b['mche_nombr']; 
	$mche_monto= $b['mche_monto']; 
	$mche_descr= $b['mche_descr'];
	$mche_statu= $b['mche_statu']; 
	$mche_banco= $b['mche_banco']; 
	$mche_prest= $b['mche_prest']; 
	$mche_fecha= $b['mche_fecha'];  
	$sustituto= $b['sustituto'];
	$sustituto= $b['sustituto'];
	$verificado= $b['verificado']; 
	$fecha_verific= $b['fecha_verific']; 
	$ip_verific= $b['ip_verific'];

	$sql="INSERT INTO sgcaf840(mche_orden,mche_fecha,mche_nombr,mche_monto,mche_descr,mche_statu,mche_banco,mche_prest,verificado, fecha_verific, ip_ver) 
	VALUES ('$nche','$mche_fecha','$mche_nombr','$mche_monto','$mche_descr','$mche_statu','$cod_banco','$mche_prest',
	 '$verificado', '$fecha_verific', '$ip_ver')";
	mysql_query($sql)or die ("<p />El usuario $usuario no tiene permisos para añadir Clientes o cuenta ya existente.<br>".mysql_error()."<br>".$sql);

	$sql="UPDATE sgcaf840 SET mche_statu='A', sustituto='$nche'
		WHERE mche_orden='$numero' and mche_banco='$mche_banco'";
		mysql_query($sql)or die ("<p />El usuario $usuario no tiene permisos para añadir Clientes o cuenta ya existente.<br>".mysql_error()."<br>".$sql);
	
	$sql="INSERT INTO sgcaf840E(mche_orden,mche_fecha,mche_nombr,mche_monto,mche_descr,mche_statu,mche_banco,mche_prest, ip, mche_observacion, fecha_borrar, sustituto,  verificado, fecha_verific, ip_ver) 
	VALUES ('$numero','$mche_fecha','$mche_nombr','$mche_monto','$mche_descr','$mche_statu','$mche_banco','$mche_prest', '$ip', '$explicacion', '$fecha', '$nche', '$verificado', '$fecha_verific', '$ip_ver')";

	mysql_query($sql)or die ("<p />El usuario $usuario no tiene permisos para añadir Clientes o cuenta ya existente.<br>".mysql_error()."<br>".$sql);
	
	$sql="UPDATE sgcaf846 SET descrip='CHEQUE ANULADO SUSTITUIDO POR EL CHEQUE NRO. $nche del Banco $nombre'
		WHERE nro_che='$numero' and banco='$codigov'";

		mysql_query($sql)or die ("<p />El usuario $usuario no tiene permisos para añadir Clientes o cuenta ya existente.<br>".mysql_error()."<br>".$sql);
		
	$sql="SELECT * FROM sgcaf841 WHERE mche_banco = '$mche_banco' and mche_orden= '$numero'";
	$resultado1=mysql_query($sql);  

	while($row1=mysql_fetch_array($resultado1)) 
	{
	$mche_cuent=$row1['mche_cuent']; 
	$mche_debcr=$row1['mche_debcr']; 
	$mche_descr=$row1['mche_descr']; 
	$mche_monto1=$row1['mche_monto1']; 
	$mche_monto2=$row1['mche_monto2']; 
	$mche_monto=$row1['mche_monto']; 
	$mche_banco=$row1['mche_banco']; 
	$registro_original=$row1['registro']; 

	$sql="INSERT INTO sgcaf841E(mche_orden,mche_cuent,mche_debcr,mche_descr,mche_monto1,mche_monto2,mche_monto,mche_banco, registro_original) 
	VALUES ('$numero','$mche_cuent','$mche_debcr','$mche_descr','$mche_monto1','$mche_monto2','$mche_monto','$mche_banco', '$registro_original')";

	mysql_query($sql)or die ("<p />El usuario $usuario no tiene permisos para añadir Clientes o cuenta ya existente.<br>".mysql_error()."<br>".$sql);
	}
	
	$sql="UPDATE sgcaf846 SET descrip='$mche_nombr', ip='$ip', fecha='$mche_fecha', estatus='-'
				WHERE nro_che='$nche' and banco='$codigo'";

	mysql_query($sql)or die ("<p />El usuario $usuario no tiene permisos para añadir Clientes o cuenta ya existente.<br>".mysql_error()."<br>".$sql);

	$sql="SELECT * FROM sgcaf841 WHERE mche_banco = '$mche_banco' and mche_orden= '$numero'";
	$resultado1=mysql_query($sql);  

	while($row1=mysql_fetch_array($resultado1)) 
	{
	$mche_cuent=$row1['mche_cuent']; 
	$mche_debcr=$row1['mche_debcr']; 
	$mche_descr=$row1['mche_descr']; 
	$mche_monto1=$row1['mche_monto1']; 
	$mche_monto2=$row1['mche_monto2']; 
	$mche_monto=$row1['mche_monto']; 
	$mche_banco=$row1['mche_banco']; 
	if ($cue_bancov==$mche_cuent) 
	{ echo 'hhh'; 
	echo $mche_cuent=$cue_banco;
	}
	else {
	echo 'ttt'; 
	echo $mche_cuent=$row1['mche_cuent']; 
	}

	$sql="INSERT INTO sgcaf841(mche_orden,mche_cuent,mche_debcr,mche_descr,mche_monto1,mche_monto2,mche_monto,mche_banco) 
	VALUES ('$nche','$mche_cuent','$mche_debcr','$mche_descr','$mche_monto1','$mche_monto2','$mche_monto','$cod_banco')";

	mysql_query($sql)or die ("<p />El usuario $usuario no tiene permisos para añadir Clientes o cuenta ya existente.<br>".mysql_error()."<br>".$sql);

	}
	$accionIn='';
	//echo '</div>';
	
}

if ($accionIn=="Incluir") {
    //echo '<div id="div1">';
	echo "<form action='cheimpr.php?accionIn=Anular_Cheque2' name='form1' method='post' onsubmit='return val(form1)'>";
	$sql= "SELECT * FROM sgcaf843, sgcaf845, sgcaf846 where nro_cta_ba = nro_ban  and descrip ='' and emitircheque='1'";
	echo $numero;
	echo $codigo; 
	$codigov=$codigo; 
	$result=mysql_query($sql);
    pantalla_act_in($result,$accionIn, $numero, $codigov);
	echo "<input type = 'submit' value = 'Enviar'>";
	//echo '</div>';
}


if (!$accionIn) {
//	echo "<div id='div1'>";
	echo "<form action='cheimpr.php?accionIn=Verificar' name='form1' method='post' onsubmit='return explicacion_cheque(form1)'>";
   
    $sql="SELECT * FROM sgcaf840, sgcaf843 where mche_statu='L' and mche_banco= cod_banco and verificado='1' and emitircheque='1' group BY mche_banco "; 

	$result=mysql_query($sql);
	if (mysql_num_rows($result) > 0) {
	echo 'Banco';
	echo '<select name="codigo" size="1">';
	 		//$resultado=mysql_query($sql);
			while ($fila2 = mysql_fetch_assoc($result)) 
			{
			echo '<option value="'.$fila2['cod_banco'].'" '.(($banco==$fila2['cod_banco'])?'selected':'').'>'.$fila2['nombre_ban'].''.$fila2['nro_cta_ba'].'</option>';
			}
			echo '</select> ';
			echo "<input type = 'submit' value = 'Buscar'>";
	        echo '</form>';
			}
	else {
	echo "<p /><h2>NO SE ENCUENTRA CHEQUES PARA IMPRIMIR</ h2></div></body></html>";
	echo "<p /><br /><p /><td>"; 
	}
}

?>


<?php 
function pantalla_imprimir($result,$accionIn,$codigo,$nombre,$nro, $plantilla)
{
$deci=$_SESSION['deci'];
$sep_decimal=$_SESSION['sep_decimal'];
$sep_miles=$_SESSION['sep_miles'];
//echo $sql; 
if ($accionIn == 'Anular_Cheque') { $lectura = 'readonly = "readonly"'; $activada="disabled" ; } else {$lectura=''; $activada='';}
?>
<label><fieldset><legend>IMPRESIÓN DE CHEQUES del Banco <?php echo $nombre?></legend>
  	<table width="350" border="3">
	<?php 
	echo "<table class='basica 100 hover' width='100%'>"; 
	$i=0;
	while($row1=mysql_fetch_array($result)) 
	{
	if ($row1['mche_monto'] > 0) 
	{
	echo '<td class="centro azul" width="10" ><input type="checkbox" name="numero[].$i" value='.$row1["mche_orden"] .'> 
	</td>' .'<td  class="centro negro b" width="50">'.$row1['mche_orden'].' </td> 
	</td>' .'<td  class="izq negro b" width="190">'.$row1['mche_nombr'].' </td><td  class="negro b dcha" width="30"> '.number_format($row1['mche_monto'],2,'.',',').'</td></tr>';
	}
	if ($row1['mche_monto'] == 0)
	{
	echo '<td class="centro azul" width="10" > 
	</td>' .'<td  class="centro rojo b" width="50 ">'.$row1['mche_orden'].' </td> 
	</td>' .'<td  class="izq rojo b" width="190">'.$row1['mche_nombr'].' </td><td  class="rojo b dcha " width="30"> '.number_format($row1['mche_monto'],2,'.',',').'</td></tr>';
	}
	}
	?>
	<input type="hidden" name="nro" value="<?php echo $nro;?>">
	<input type="hidden" name="codigo" value="<?php echo $codigo;?>">
	<input type="hidden" name="nombre" value="<?php echo $nombre;?>">
	<input type="hidden" name="plantilla" value="<?php echo $plantilla;?>">
</table>
	
		&nbsp;</td></tr> 

<?php 
}
?>

<?php 
function pantalla_act_anular($result,$accionIn,$codigo,$numero,$nombre){
$deci=$_SESSION['deci'];
$sep_decimal=$_SESSION['sep_decimal'];
$sep_miles=$_SESSION['sep_miles'];
$filaxxx = mysql_fetch_assoc($result);
//echo $sql; 
if ($accionIn == 'Anular_Cheque') {$lectura = 'readonly = "readonly"'; $activada="disabled" ; } else {$lectura=''; $activada='';}
?>
<label><fieldset><legend>IMPRESIÓN DE CHEQUES del Banco <?php echo $nombre?>/ Anulación</legend>
  	<table width="360" border="3">
	<td class= "blanco b" colspan='5' width="50" style="text-align:center" bgcolor="#FFFFCC">Encabezado del Cheque <tr>
		
	  <td class= "blanco b" width="50" bgcolor="#FFFFCC">Nro. de Cuenta<td>
	<input name="codigo" type="text" id="codigo" value="<?php echo $codigo ?>" <?php echo $lectura; ?>size="20" maxlength="20" /></td>
	 <td class= "blanco b" width="50" bgcolor="#FFFFCC">Nro. de Cheque<td>
	 <input name="numero" type="text" id="numero" value="<?php echo $numero?>" <?php  echo $lectura; ?>size="8" maxlength="8" /></td> <tr>
	 
 <td class= "blanco b" width="50" bgcolor="#FFFFCC">Explicación<td td  colspan='3' class="rojo">
  	<?php
			echo '<select name="explicacion" size="1">';
			$sql="select * from sgcaf000 where tipo='Anulado'";
			$resultado=mysql_query($sql);
			while ($fila = mysql_fetch_assoc($resultado)) {
				echo '<option value="'.$fila['nombre'].'" '.(($explicacion==$fila['nombre'])?'selected':'').'>'.$fila['nombre'].'</option>';
				}
	
	 	echo '</select> '; 
		?>*</td><tr>
		 
		 </td><tr>
	 </tr>
</table>
	
		&nbsp;</td></tr> 

<?php 
}
?>


<?php
function pantalla_act_in($result,$accionIn, $numero, $codigov) {
$deci=$_SESSION['deci'];
$sep_decimal=$_SESSION['sep_decimal'];
$sep_miles=$_SESSION['sep_miles'];
//echo $sql; 
if ($accionIn == 'VerificarIn') { $lectura = 'readonly = "readonly"'; $activada="disabled" ; } else {$lectura=''; $activada='';}
?>
  	 <label><fieldset><legend>BANCOS CON CHEQUES DISPONIBLES</legend>
  	<table width="260" border="3">
     <td class= "blanco b" width="50">Nro. de Cuenta<td>
	  <input type="hidden" name="codigov" value="<?php echo $codigov;?>">
	 	<?php
			$codigo=$fila['nro_cta_ba'];
			echo '<select name="codigo" size="1">';
			$sql="SELECT * FROM sgcaf843, sgcaf846 where estatus ='+' and banco=nro_cta_ba and estado='1' and emitircheque='1' order by nro_cta_ba";
			$resultado=mysql_query($sql);
			while ($fila2 = mysql_fetch_assoc($resultado)) {
			 if ($jj<>$fila2['nro_cta_ba'])
			 {
				echo '<option value="'.$fila2['nro_cta_ba'].'" '.(($banco==$fila2['nro_cta_ba'])?'selected':'').'>'.$fila2['nombre_ban'].''.$fila2['nro_cta_ba'].'</option>';
			 }
			 $jj=$fila2['nro_cta_ba']; 
			 }
	 	echo '</select> '; 
	    ?> 
	  <input type="hidden" name="numero" value="<?php echo $numero;?>">
	  <input type="hidden" name="cod" value="<?php echo $fila2['cod_banco'];?>">
	  <input type="hidden" name="cue_banco" value="<?php echo $fila2['cue_banco'];?>">	
	 *</td><tr>
</table>
 	&nbsp;</td></tr> 

<?php 
}
?>