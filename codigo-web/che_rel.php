<?php
include("head.php");
include("paginar.php");
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

if ($accionIn=="Verificar2") 
{
// echo 'hola'; 
$h= date("d/m/Y");
$hoy= convertir_fecha($h);
//echo $fechadesde; 
//echo $fechahasta;
//echo $codigo;
//echo $chequehasta; 
//echo $chequedesde;   
   // echo '<div id="div1">';
   
    ?>
<html>
<head>
<title></title>

<script language="JavaScript">
var codigo='<? echo $codigo;?>'
var nombre='<? echo $nombre;?>'
var fechadesde='<? echo $fechadesde;?>'
var fechahasta='<? echo $fechahasta;?>'
var chequedesde='<? echo $chequedesde;?>'
var chequehasta='<? echo $chequehasta;?>'
//checkDoubleConfirmation(); 
//alert ('hola'); 
  mi_ventana = window.open("che_rela_pdf.php?codigo=" + codigo + "&nombre=" + nombre + "&fechadesde=" + fechadesde + "&fechahasta=" + fechahasta + "&chequedesde=" + chequedesde + "&chequehasta=" + chequehasta, "","width=1200,height=500,left=5,top=135,scrollbar=no,menubar=no,statusbar=no,status=no,resizable=YES,location=NO,toolbar=NO,personalbar=NO") 
</script>
</head> 
<body>
</body>
</html>  
   
<?php 
$sql="INSERT INTO sgcaf847(banco,fechadesde,fechahasta,chequedesde,chequehasta,fecha_relac,procesado) 
VALUES ('$codigo','$fechadesde','$fechahasta','$chequedesde','$chequehasta','$hoy','0')";
// echo $sql;
//	echo "<p />";
mysql_query($sql)or die ("<p />El usuario $usuario no tiene permisos para añadir Clientes o cuenta ya existente.<br>".mysql_error()."<br>".$sql);
	$accionIn=''; 
}
?>

<?php
if (!$accionIn) {
//	echo "<div id='div1'>";
	echo "<form action='che_rel.php?accionIn=Verificar' name='form1' method='post' onsubmit='return explicacion_cheque(form1)'>";
    $sql="SELECT * FROM sgcaf843 where emitircheque='1' ORDER BY cod_banco";
	$result=mysql_query($sql);
//	echo $sql; 
    pantalla_act_banco($result,$accionIn);
	echo "<input type = 'submit' value = 'Enviar'>";
}

if ($accionIn=="Verificar1") 
{
   // echo '<div id="div1">';
  	$fechadesde=convertir_fecha($desde);
    $fechahasta=convertir_fecha($hasta);
	$sql="SELECT * FROM sgcaf843, sgcaf840 where nro_cta_ba='$codigo' and cod_banco=mche_banco and mche_statu<>'L' and mche_fecha>='$fechadesde' and mche_fecha<='$fechahasta' order by mche_orden ASC";
	$resultado=mysql_query($sql);
    if (mysql_num_rows($resultado) == 0) 
	{
	$accionIn='Verificar'; 
	$texto='NO SE ENCUENTRAN CHEQUES. INTRODUCIR NUEVAS FECHAS'; 
	$codigo=$codigo; 
	$nombre=$nombre; 
	}
	else {
	echo "<form action='che_rel.php?accionIn=Verificar2' name='form1' method='post' onsubmit='return valrangocheque(form1)'>";  
	$sql= "SELECT nombre_ban FROM sgcaf843 where nro_cta_ba='$codigo' and emitircheque='1'"; 
	$result=mysql_query($sql);
//	echo $sql; 
	pantalla_verificar1($result,$accionIn,$codigo,$nombre,$desde,$hasta);
	echo "<input type = 'submit' value = 'Enviar'>";
	}
}


if ($accionIn=="Verificar") 
{
   // echo '<div id="div1">';
    echo "<form action='che_rel.php?accionIn=Verificar1' name='form1' method='post' onsubmit='return valrangofecha(form1)'>";  
	$sql= "SELECT nombre_ban FROM sgcaf843 where nro_cta_ba='$codigo' and emitircheque='1'"; 
	$result=mysql_query($sql);
	$b= mysql_fetch_assoc($result);
	$nombre=$b['nombre_ban']; 
//	echo $sql; 
	echo '<h2> '.$texto.' </h2>'; 
    pantalla_verificar($result,$accionIn,$codigo,$nombre);
	echo "<input type = 'submit' value = 'Enviar'>";
}

?>

<?php
function pantalla_act_banco($result,$accionIn) {
$deci=$_SESSION['deci'];
$sep_decimal=$_SESSION['sep_decimal'];
$sep_miles=$_SESSION['sep_miles'];
//echo $sql; 
if ($accionIn == '!$accionIn') { $lectura = 'readonly = "readonly"'; $activada="disabled" ; } else {$lectura=''; $activada='';}
?>
  	 <fieldset><legend>DATOS PARA LA RELACIÓN DE CHEQUES</legend>
  	<table width="270" border="3">
     <td class= "blanco b" width="50" bgcolor='#FFFFCC'>Banco<td class='rojo'>
	 	<?php
			$codigo=$fila['nro_cta_ba'];
			echo '<select name="codigo" size="1">';
			while ($fila2 = mysql_fetch_assoc($result)) 
			{
			echo '<option value="'.$fila2['nro_cta_ba'].'" '.(($banco==$fila2['nro_cta_ba'])?'selected':'').'>'.$fila2['nombre_ban'].''.$fila2['nro_cta_ba'].'</option>';
		    }
			
	 	echo '</select> '; 
	    ?> *</td><tr>
</table>
 	&nbsp;</td></tr> 

<?php 
}
?>

<?php
function pantalla_verificar($result,$accionIn,$codigo,$nombre) {
$deci=$_SESSION['deci'];
$sep_decimal=$_SESSION['sep_decimal'];
$sep_miles=$_SESSION['sep_miles'];
//echo $sql; 
if ($accionIn == 'Verificar') { $lectura = 'readonly = "readonly"'; $activada="disabled" ; } else {$lectura=''; $activada='';}
?>
  	 <fieldset><legend>DATOS PARA LA RELACIÓN DE CHEQUES del Banco <?php echo $nombre ?></legend>
  	<table width="340" border="3">
    <td class= "blanco b" width="50" bgcolor="#FFFFCC">Nro. de Cuenta<td class='rojo' colspan='3' width="120" >
	<input name="codigo" type="text" id="codigo" value="<?php echo $codigo ?>" <?php echo $lectura; ?>size="20" maxlength="20" />*</td><tr>
	
	<td class= "blanco b" width="50" bgcolor="#FFFFCC" rowspan='2'>Fecha
	<td class= "blanco b" width="50" bgcolor="#FFFFCC">Desde<td width="60" class='rojo'>
	<input type="hidden" name="nombre" value="<?php echo $nombre;?>">
	  <?php 
/*
$hoy = date("d/m/Y");
	  //echo $hoy; 
$hoy1 = mktime(0,0,0,date("m"),date("d"),date("Y")); 
$h = date("d/m/Y",$hoy1);
$mas = $hoy1+604800;  
$semana = date("d/m/Y",$mas); 
escribe_formulario(desde, form1.desde, 'd/m/yyyy', '', '', $semana, '0', '10')?>*</td><tr>
<td class= "blanco b" width="50" bgcolor="#FFFFCC">Hasta<td width="60" class='rojo'>
	  <?php 
escribe_formulario(hasta, form1.hasta, 'd/m/yyyy', '', '', $semana, '0', '10')?>
*/
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
	<input type="hidden" name="desde" id="desde" value=" <?php  echo $fechanueva; ?>"/>
   <span style="background-color: #ff8; cursor: default;"
         onmouseover="this.style.backgroundColor='#ff0';"
         onmouseout="this.style.backgroundColor='#ff8';"
         id="show_desde" 
   ><?php  echo ($hoy); ?></span> *
<script type="text/javascript">
    Calendar.setup({
//		showAt(220, 250)
//		position       : 	{100,300},
        inputField     :    "desde",     // id of the input field
        ifFormat       :    "%d/%m/%Y",     // format of the input field (even if hidden, this format will be honored)
        displayArea    :    "show_desde",       // ID of the span where the date is to be shown
//        daFormat       :    "%A, %B %d, %Y",// format of the displayed date
        daFormat       :    "%B %d, %Y",// format of the displayed date
        align          :    "Tl",           // alignment (defaults to "Bl")
        singleClick    :    true,
		weekNumbers    :    false, 
//		range          :     <?php echo $rango; ?>,

/*
		dateStatusFunc :    function (date) { // disable weekend days (Saturdays == 6 and Subdays == 0)
						var today = new Date();
						return (
							  (date.getTime() < today.getTime()-((5)*24*60*60*1000)
//							  (date.getTime() > today.getTime()-(10*24*60*60*1000)) 
							  || date.getTime() > today.getTime()+(10*24*60*60*1000))	
//							  date.getDay() == 0 || 
							  ) ? true : false;  }
*/
				    });
</script>

*</td><tr>
<td class= "blanco b" width="50" bgcolor="#FFFFCC">Hasta<td width="60" class='rojo'>
	  <?php 
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
	<input type="hidden" name="hasta" id="hasta" value=" <?php  echo $fechanueva; ?>"/>
   <span style="background-color: #ff8; cursor: default;"
         onmouseover="this.style.backgroundColor='#ff0';"
         onmouseout="this.style.backgroundColor='#ff8';"
         id="show_hasta" 
   ><?php  echo ($hoy); ?></span> *
<script type="text/javascript">
    Calendar.setup({
//		showAt(220, 250)
//		position       : 	{100,300},
        inputField     :    "hasta",     // id of the input field
        ifFormat       :    "%d/%m/%Y",     // format of the input field (even if hidden, this format will be honored)
        displayArea    :    "show_hasta",       // ID of the span where the date is to be shown
//        daFormat       :    "%A, %B %d, %Y",// format of the displayed date
        daFormat       :    "%B %d, %Y",// format of the displayed date
        align          :    "Tl",           // alignment (defaults to "Bl")
        singleClick    :    true,
		weekNumbers    :    false, 
//		range          :     <?php echo $rango; ?>,

/*
		dateStatusFunc :    function (date) { // disable weekend days (Saturdays == 6 and Subdays == 0)
						var today = new Date();
						return (
							  (date.getTime() < today.getTime()-((5)*24*60*60*1000)
//							  (date.getTime() > today.getTime()-(10*24*60*60*1000)) 
							  || date.getTime() > today.getTime()+(10*24*60*60*1000))	
//							  date.getDay() == 0 || 
							  ) ? true : false;  }
*/
				    });
</script>

*</td><tr>

</table>
 	&nbsp;</td></tr> 

<?php 
}
?>

 <?php
function  pantalla_verificar1($result,$accionIn,$codigo,$nombre,$desde,$hasta) {
$deci=$_SESSION['deci'];
$sep_decimal=$_SESSION['sep_decimal'];
$sep_miles=$_SESSION['sep_miles'];
//echo $sql; 
if ($accionIn == 'Verificar1') { $lectura = 'readonly = "readonly"'; $activada="disabled" ; } else {$lectura=''; $activada='';}
?>
  	 <fieldset><legend>DATOS PARA LA RELACIÓN DE CHEQUES del Banco <?php echo $nombre ?></legend>
  	<table width="340" border="3">
    <td class= "blanco b" width="60" bgcolor="#FFFFCC">Nro. de Cuenta<td class='rojo' colspan='3' width="120" >
	<input name="codigo" type="text" id="codigo" value="<?php echo $codigo ?>" <?php echo $lectura; ?>size="20" maxlength="20" />*</td><tr>
	
	<td class= "blanco b" width="50" bgcolor="#FFFFCC" rowspan='2'>Fecha
	<td class= "blanco b" width="50" bgcolor="#FFFFCC">Desde<td width="60" class='rojo'>
	 <input name="desde" type="text" id="desde" value="<?php echo $desde ?>" <?php echo $lectura; ?>size="20" maxlength="20" />*</td><tr>

<td class= "blanco b" width="50" bgcolor="#FFFFCC">Hasta<td width="60" class='rojo'>
	<input name="hasta" type="text" id="hasta" value="<?php echo $hasta ?>" <?php echo $lectura; ?>size="20" maxlength="20" />*</td><tr>
	
	<td class= "blanco b" width="50" bgcolor="#FFFFCC" rowspan='2'>Cheques
	<td class= "blanco b" width="50" bgcolor="#FFFFCC">Desde<td width="100" class='rojo'>
	  	<?php
		 $fechadesde=convertir_fecha($desde);
         $fechahasta=convertir_fecha($hasta);
			echo '<select name="chequedesde" size="1">';
			$sql="SELECT * FROM sgcaf843, sgcaf840 where nro_cta_ba='$codigo' and cod_banco=mche_banco and mche_statu<>'L' and mche_fecha>='$fechadesde' and mche_fecha<='$fechahasta' order by mche_orden ASC";
			$resultado=mysql_query($sql);
			while ($fila = mysql_fetch_assoc($resultado)) {
			echo '<option value="'.$fila['mche_orden'].'" '.(($banco==$fila['mche_orden'])?'selected':'').'>'.$fila['mche_nombr'].'['.$fila['mche_orden'].']</option>';
				}
	 	echo '</select> '; 
//		echo $sql;
		?></td><tr>

<td class= "blanco b" width="50" bgcolor="#FFFFCC">Hasta<td width="60" class='rojo'>
	  	<?php
		 $fechadesde=convertir_fecha($desde);
         $fechahasta=convertir_fecha($hasta);
			echo '<select name="chequehasta" size="1">';
			$sql="SELECT * FROM sgcaf843, sgcaf840 where nro_cta_ba='$codigo' and cod_banco=mche_banco and mche_statu<>'L' and mche_fecha>='$fechadesde' and mche_fecha<='$fechahasta' order by mche_orden DESC";
			$resultado=mysql_query($sql);
			while ($fila = mysql_fetch_assoc($resultado)) {
			echo '<option value="'.$fila['mche_orden'].'" '.(($banco==$fila['mche_orden'])?'selected':'').'>'.$fila['mche_nombr'].'['.$fila['mche_orden'].']</option>';
				}
	 	echo '</select> '; 
		?></td><tr>
<input type="hidden" name="nombre" value="<?php echo $nombre;?>">
<input type="hidden" name="fechadesde" value="<?php echo $fechadesde;?>">
<input type="hidden" name="fechahasta" value="<?php echo $fechahasta;?>">
</table>
 	&nbsp;</td></tr> 

<?php 
}
?>