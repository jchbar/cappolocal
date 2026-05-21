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

<script src="ajxconc.js" type="text/javascript"></script>
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
if ($accionIn=="Verificar") 
{
   // echo '<div id="div1">';
    $sql= "SELECT *, date_format(enc_fecha, '%d/%m/%Y') as fecha FROM sgcaf830 where enc_clave='$numero'"; 
	$result=mysql_query($sql);
	if (mysql_num_rows($result) == 0)
	{
	echo '<h2>EL NÚMERO DE COMPROBANTE NO EXISTE <h2>'; 
	}
	else if (mysql_num_rows($result) <> 0)
	{
	echo "<form action='cam_fech.php?accionIn=Procesar' name='form1' method='post' onsubmit='return explicacion_cheque(form1)'>";
    pantalla_verificar($result,$accionIn,$numero);
	echo "<input type = 'submit' value = 'Procesar'>";
	echo '</form>';
	}
}

if ($accionIn=="Procesar") 
{
 
    $fecha=convertir_fecha($fecha);
	$fecha=strtotime($fecha);
    $mes=date('m',$fecha);
	$ano=date('Y',$fecha);
	
    $fechanueva=convertir_fecha($fechanueva);
	$fechan=strtotime($fechanueva);
    $mesn=date('m',$fechan);
	$anon=date('Y',$fechan);
	echo $fechanueva; 
	echo $numero; 
	
	/////////////////////////////////////////FECHA ORIGINAL Y FECHA NUEVA SON IGUALES///////////////////////////////////
	if ($mes==$mesn and $ano==$anon)
	{
	$sql= "SELECT * FROM sgcaf820 where com_nrocom='$numero'"; 
	$rs=mysql_query($sql);
	while ($row = mysql_fetch_assoc($rs))
		{
	$sql="UPDATE sgcaf820 SET com_fecha='$fechanueva' WHERE com_nrocom ='$numero'";
//    echo $sql;
	mysql_query($sql) or die ("<p />El usuario $usuario no tiene permisos para modificar Clientes <br>".mysql_error()."<br>".$sql);
	    }
	$sql="UPDATE sgcaf830 SET enc_fecha='$fechanueva' WHERE enc_clave ='$numero'";
    echo $sql;
	mysql_query($sql) or die ("<p />El usuario $usuario no tiene permisos para modificar Clientes <br>".mysql_error()."<br>".$sql);
	} 
	/////////////////////////////////////////FECHA ORIGINAL Y FECHA NUEVA SON DIFERENTES///////////////////////////////////
	else 
	{
	$sql= "SELECT * FROM sgcaf820 where com_nrocom='$numero'"; 
	$rs=mysql_query($sql);
	while ($row = mysql_fetch_assoc($rs))
		{
	$sql="UPDATE sgcaf820 SET com_fecha='$fechanueva' WHERE com_nrocom ='$numero'";
    echo $sql;
	mysql_query($sql) or die ("<p />El usuario $usuario no tiene permisos para modificar Clientes <br>".mysql_error()."<br>".$sql);
	    }
	$sql="UPDATE sgcaf830 SET enc_fecha='$fechanueva' WHERE enc_clave ='$numero'";
//    echo $sql;
	mysql_query($sql) or die ("<p />El usuario $usuario no tiene permisos para modificar Clientes <br>".mysql_error()."<br>".$sql);
	}
	$accionIn=''; 
}

if (!$accionIn) 
{
	echo '<div id="div1">';
 	echo "<form action='cam_fech.php?accionIn=Verificar' name='form1' method='post' onsubmit='return explicacion_cheque(form1)'>";
    pantalla_act_comprobante($result,$accionIn);
	echo "<input type = 'submit' value = 'Buscar'>";
	echo '</form>';
}   


?>

<?php
function pantalla_act_comprobante($result,$accionIn) {
$deci=$_SESSION['deci'];
$sep_decimal=$_SESSION['sep_decimal'];
$sep_miles=$_SESSION['sep_miles'];
//echo $sql; 
if ($accionIn == '!$accionIn') { $lectura = 'readonly = "readonly"'; $activada="disabled" ; } else {$lectura=''; $activada='';}
?>
  	 <label><fieldset><legend>CAMBIO DE FECHA</legend>
  	<table width="270" border="3">
     <td class= "blanco b" width="130" bgcolor='#FFFFCC'>Nro. de Comprobante<td class='rojo'>
	 <input name="numero" type="text" id="numero" value="<?php  ?>" <?php ?>size="20" maxlength="20" />*</td><tr>
	
</table>
 	&nbsp;</td></tr> 

<?php 
}
?>

<?php
function pantalla_verificar($result,$accionIn, $numero) {
$deci=$_SESSION['deci'];
$sep_decimal=$_SESSION['sep_decimal'];
$sep_miles=$_SESSION['sep_miles'];
//echo $sql; 
$row=mysql_fetch_assoc($result);
if ($accionIn == 'Verificar') { $lectura = 'readonly = "readonly"'; $activada="disabled" ; } else {$lectura=''; $activada='';}
?>
  	 <label><fieldset><legend>CAMBIO DE FECHA</legend>
  	<table width="350" border="3">
     <td class= "blanco b" width="120" bgcolor='#FFFFCC'>Nro. de Comprobante<td class='rojo' width="130">
	 <input name="numero" type="text" id="numero" value="<?php echo $numero;   ?>" <?php echo $lectura; ?> size="12" maxlength="12" /></td>
	  <td class= "blanco b" width="130" bgcolor='#FFFFCC'>Fecha<td class='rojo' width="130">
	 <input name="fecha" type="text" id="fecha" value="<?php echo $row['fecha']; ?>" <?php echo $lectura; ?> size="10" maxlength="10" /></td><tr>
	 <td class= "blanco b" width="130" bgcolor='#FFFFCC'>Concepto<td class='rojo' width="150" colspan="3">
	 <input name="concepto" type="text" id="concepto" value="<?php echo $row['enc_desco']; ?>" <?php echo $lectura; ?> size="60" maxlength="60" /></td><tr>
	  <td class= "blanco b" width="130" bgcolor='#FFFFCC'>Monto<td class='rojo' width="130">
	 <input name="monto" type="text" id="monto" value="<?php echo number_format($row['enc_debe'],2,".",","); ?>" <?php echo $lectura; ?> size="12" maxlength="12" /></td>
	  <td class= "blanco b" width="130" bgcolor='#FFFFCC'>Fecha Nueva<td class='rojo' width="130">

<!--
	<input type="hidden" name="fechanueva" id="fechanueva" value=" <?php  echo ($row['fecha']); ?>"/>
-->
	<?php
    $fechanueva=explode('/',$row['fecha']);
	$fechanueva=$fechanueva[1].'/'.$fechanueva[0].'/'.$fechanueva[2];
	$sqlano='select substr(fech_ejerc,1,4) as ano from sgcaf100';
	$sqlfano=mysql_query($sqlano);
	$sqlrano=mysql_fetch_assoc($sqlfano);
	$rango=$sqlrano['ano'];
	$sqlano='select substr(now(),1,4) as ano';
	$sqlfano=mysql_query($sqlano);
	$sqlrano=mysql_fetch_assoc($sqlfano);
	if ($sqlrano['ano'] > $rango)
		$rango.=', '.$sqlrano['ano'];
	?>
	<input type="hidden" name="fechanueva" id="fechanueva" value=" <?php  echo $fechanueva; ?>"/>
   <span style="background-color: #ff8; cursor: default;"
         onmouseover="this.style.backgroundColor='#ff0';"
         onmouseout="this.style.backgroundColor='#ff8';"
         id="show_ingcapu" 
   ><?php  echo ($row['fecha']); ?></span> *
<script type="text/javascript">
    Calendar.setup({
//		showAt(220, 250)
//		position       : 	{100,300},
        inputField     :    "fechanueva",     // id of the input field
        ifFormat       :    "%d/%m/%Y",     // format of the input field (even if hidden, this format will be honored)
        displayArea    :    "show_ingcapu",       // ID of the span where the date is to be shown
//        daFormat       :    "%A, %B %d, %Y",// format of the displayed date
        daFormat       :    "%B %d, %Y",// format of the displayed date
        align          :    "Tl",           // alignment (defaults to "Bl")
        singleClick    :    true,
		weekNumbers    :    false, 
		range          :     [<?php echo $rango; ?>],

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
  </tr>
  <tr>

	<?php 	
/*
$hoy = date("d/m/Y");
$hoy1 = mktime(0,0,0,date("m"),date("d"),date("Y")); 
$h = date("d/m/Y",$hoy1);
$mas = $hoy1+14515200;  
$meses = date("d/m/Y",$mas);  
escribe_formulario(fechanueva, form1.fechanueva, 'd/m/yyyy', $fechanueva,'31/12/2008', $meses, '1', '10');  ?></td>
*/
?>
</table>
 	&nbsp;</td></tr> 

<?php 
}
?>
