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

if ($accionIn=='Verificar1') {
    $contr=$control; 
	$sql="SELECT *, date_format(mche_fecha, '%d/%m/%Y') AS fecha, date_format(mche_fecha, '%y%m%d') AS fechax FROM sgcaf843, sgcaf840 where nro_cta_ba='$codigo' and cod_banco=mche_banco and mche_statu<>'L' and mche_fecha>='$fechadesde' and mche_fecha<='$fechahasta' and mche_orden>='$chequedesde' and mche_orden<='$chequehasta' order by mche_orden ASC";
	$resultado =mysql_query($sql);
	while($row1=mysql_fetch_array($resultado)) 
	{
//			echo $row1['mche_nombr']; 
			$fecha=$row1['mche_fecha']; 
	        $xx= $row1['fechax']; 
			$x2=explode('-',$fecha);
			$x2=$x2[2].$x2[1].substr($x2[0],2,2);
			$elbanco=$row1['mche_banco']; 
//			echo "<p />"; 
			$control_1++; 
	    	$control= ceroizq ($control_1,4); 
			$codi=$x2.''.$control; 
			$codi=ceroizq($codi,11);
//			echo $codi; 
			$descripcion=$row1['mche_descr']; 
//			echo "<p />"; 
			$elasiento=$codi;
			$sql = "INSERT INTO sgcaf830 (enc_clave, enc_fecha, enc_desco, enc_desc1, enc_debe, enc_haber, enc_item, enc_dif, enc_igual, enc_refer, enc_sw, enc_explic) VALUES ('$codi', '$fecha','$descripcion','',0,0,0,0,0,0,0,'$descripcion')"; 
//			echo $sql.'<br>';

			echo "Generando encabezado contable <strong><a target=\"_blank\" href='editasi2.php?asiento=$elasiento'>$elasiento </a></strong> <br>";

			if (!mysql_query($sql)) die ("El usuario $usuario no tiene permiso para añadir Asientos.<br>".$sql);
			
			if ($row1['mche_statu']=='A') 
			{
//			echo 'hoal'; 
			$sql="SELECT * FROM sgcaf841 where mche_orden='".$row1['mche_orden']."' and mche_banco='".$row1['mche_banco']."' and mche_cuent='".$row1['cue_banco']."'"; 	
			$result =mysql_query($sql);
//			echo $sql; 
	             while($row=mysql_fetch_array($result)) 
		    	{
				$debcr=$row['mche_debcr']; 
				$descr=$row['mche_descr']; 
				$cuent=$row['mche_cuent']; 
				$monto1=$row['mche_monto1']; 
				$monto2=$row['mche_monto2'];
				$numero=$row['mche_orden'];
/*
				echo $debcr; 
				echo "<p />"; 
				echo $cuent; 
				echo "<p />"; 
				echo $monto1; 
				echo "<p />"; 
				echo $monto2; 
				echo "<p />"; 
				echo $numero; 
*/			    			
				agregar_f820($codi, $fecha, '-', $cuent, $descr, $monto1, $monto2, 0,$ip,0,$numero,'','S',0);
				agregar_f820($codi, $fecha, '+', $cuent, $descr, $monto1, $monto2, 0,$ip,0,$numero,'','S',0);
				}
			
			}
			else 
			{
//			echo 'hello'; 
	        
			$sql="SELECT * FROM sgcaf841 where mche_orden='".$row1['mche_orden']."' and mche_banco='".$row1['mche_banco']."'"; 	
			$result =mysql_query($sql);
//			echo $sql; 
	              while($row=mysql_fetch_array($result)) 
				{
				$debcr=$row['mche_debcr']; 
				$cuent=$row['mche_cuent']; 
				if ($debcr=='+') 
				{
				$monto1=$row['mche_monto1']; 
				$monto2= '0';
				}
				else if ($debcr=='-') 
				{
				$monto1=$row['mche_monto2']; 
				$monto2='0';
				}
				$descr=$row['mche_descr']; 
				$numero=$row['mche_orden'];
/*
				echo $debcr; 
				echo "<p />"; 
				echo $cuent; 
				echo "<p />"; 
				echo $monto1; 
				echo "<p />"; 
				echo $monto2; 
				echo "<p />"; 
				echo $numero; 
				echo "<p />"; 
*/				
				agregar_f820($codi, $fecha, $debcr, $cuent, $descr, $monto1, $monto2, 0,$ip,0,$numero,'','S',0);
				}
				
             }
	
	}
 	$h = date("d/m/Y"); 
	$hoy=convertir_fecha($h);
   $sql="UPDATE sgcaf847 SET procesado='1', fechadecomp='$hoy', ip='$ip'  WHERE banco='$codigo' and registro='$registro'";
//    echo $sql;
	mysql_query($sql) or die ("<p />El usuario $usuario no tiene permisos para modificar Clientes <br>".mysql_error()."<br>".$sql);
	///////////////////////
//    echo $control_1;
    $sql="UPDATE sgcafcon SET control='$contr' WHERE control <> '' and banco='$elbanco' ";
//    echo $sql;
	mysql_query($sql) or die ("<p />El usuario $usuario no tiene permisos para modificar Clientes <br>".mysql_error()."<br>".$sql);
///////////////////////////	
$accionIn='';
}

if (!$accionIn) {
//	echo "<div id='div1'>";
	$sql="SELECT *, date_format(fechadesde, '%d/%m/%Y') AS fechadesde, date_format(fechahasta, '%d/%m/%Y') AS fechahasta FROM sgcaf847, sgcaf843 where banco=nro_cta_ba and procesado='0'";
	$result=mysql_query($sql);
//	echo $sql; 
    pantalla($result,$accionIn);
}

if ($accionIn=='Verificar') {
//	echo "<div id='div1'>";
     $fechadesde=convertir_fecha($fechadesde);
     $fechahasta=convertir_fecha($fechahasta);
	echo "<form action='che_compr.php?accionIn=Verificar1' name='form1' method='post' onsubmit='return explicacion_cheque(form1)'>";
    $sql="SELECT *, date_format(mche_fecha, '%d/%m/%Y') AS fecha FROM sgcaf843, sgcaf840 where nro_cta_ba='$codigo' and cod_banco=mche_banco and mche_statu<>'L' and mche_fecha>='$fechadesde' and mche_fecha<='$fechahasta' and mche_orden>='$chequedesde' and mche_orden<='$chequehasta' order by mche_orden ASC";
	$codigocuenta=$_POST['codigo'];
	$codigo_banco=$_POST['banco'];
    $sql="SELECT *, date_format(mche_fecha, '%d/%m/%Y') AS fecha FROM sgcaf840, SGCAF843 where nro_cta_ba='$codigo' and mche_statu<>'L' and mche_fecha>='$fechadesde' and mche_fecha<='$fechahasta' and mche_orden>='$chequedesde' and mche_orden<='$chequehasta' order by mche_orden ASC";
//	'$codigo_banco'=mche_banco  and 
	$result=mysql_query($sql);
//	echo $sql; 
    pantalla_verificar($result,$accionIn,$codigo,$nombre, $fechadesde,$fechahasta, $chequedesde, $chequehasta,$registro);
	echo "<input type = 'submit' value = 'Procesar'>";
}

?>

<?php
function pantalla($resultado,$accionIn) {
$deci=$_SESSION['deci'];
$sep_decimal=$_SESSION['sep_decimal'];
$sep_miles=$_SESSION['sep_miles'];
//echo $sql; 
if ($accionIn == 'Verificar') { $lectura = 'readonly = "readonly"'; $activada="disabled" ; } else {$lectura=''; $activada='';}
?>
  
  	<?php 
	echo "<table class='basica 100 hover' width='100'>"; 
	echo '<th colspan="7">DATOS PARA GENERAR COMPROBANTES</th><tr>';
	echo '<tr><th align="center" width="10" rowspan="2">Nombre</th><th align="center" width="10" rowspan="2">Nro. de Cuenta</th><th align="center" width="150" colspan="2">FECHA</th><th align="center" width="150" colspan="2">NRO. DE CHEQUES</th><th align="center" width="10" rowspan="2"></th></tr>';
	echo'<tr><th align="center" width="30">Desde</th><th align="center" width="30">Hasta</th><th align="center" width="30">Desde</th><th align="center" width="30">Hasta</th></tr>'; 
	while($row1=mysql_fetch_array($resultado)) 
	{
        echo "<tr>";
  		echo "<td class='centro'>";
		echo $row1['nombre_ban']."</a></td>";
		echo "<td class='centro'>";
		echo $row1['nro_cta_ba']."</a></td>";
		echo "<td class='centro'>";
		echo $row1['fechadesde']."</a></td>";
		echo "<td class='centro'>";
		echo $row1['fechahasta']."</a></td>";
		echo "<td class='centro'>";
		echo $row1['chequedesde']."</a></td>";
		echo "<td class='centro'>";
		echo $row1['chequehasta']."</a></td>";
		echo "<td><a href='che_compr.php?accionIn=Verificar&codigo=".$row1['nro_cta_ba']."&fechadesde=".$row1['fechadesde']."&fechahasta=".$row1['fechahasta']."&chequedesde=".$row1['chequedesde']."&chequehasta=".$row1['chequehasta']."&nombre=".$row1['nombre_ban']."&registro=".$row1['registro']."'><img src='imagenes/icon_get_world.gif' width='16' height='16' border='0' title='Generar Comprobantes' alt='Generar Comprobantes'/></a></td>";
        echo "<tr>";
	}
	
	?>
 	&nbsp;</td></tr> 

<?php 
}
?>

<?php
function pantalla_verificar($result,$accionIn,$codigo,$nombre, $fechadesde,$fechahasta, $chequedesde, $chequehasta, $registro) {
$deci=$_SESSION['deci'];
$sep_decimal=$_SESSION['sep_decimal'];
$sep_miles=$_SESSION['sep_miles'];
//echo $sql; 
if ($accionIn == 'Verificar') { $lectura = 'readonly = "readonly"'; $activada="disabled" ; } else {$lectura=''; $activada='';}
?>
   	<table width="500" border="3">
  	<?php 
	$primero=mysql_fetch_assoc($result);
	$elcodigo=$primero['mche_banco'];
	 $sql="SELECT * from sgcafcon WHERE banco='$elcodigo' ";
	 $resultado =mysql_query($sql);
	 $h= mysql_fetch_assoc($resultado); 	
	 $control_1=$h['control'];  
	 mysql_data_seek($result, 0);  
	echo "<table class='basica 100 hover' width='60%'>"; 
	echo '<th colspan="7">DATOS PARA GENERAR COMPROBANTES</th><tr>';
	echo '<th colspan="7">BANCO '.$nombre.'   [ '.$codigo.' ] </th><tr>';
	echo '<th >Nro.de Cheque</th>';
	echo '<th>Descripción'; 
	echo '</th><th>Monto Bs. </th><th>Fecha</th><th>Nro. de Control</th><th>Comprobante</th>';
    echo "<tr>";
	echo '</th></th>';
	$control_U= $control_1;  
	while($row1=mysql_fetch_array($result)) 
	{
	 if ($row1["mche_statu"]=='A')
		 { 
		 $mche_nombre='***CHEQUE ANULADO***'; 
		 $mche_monto='0.00'; 
		 }
		 else 
		 { 
		 $mche_nombre=$row1["mche_nombr"]; 
		 $mche_monto=number_format($row1["mche_monto"],2,".",","); 
		 }
        echo "<tr>";
  		echo "<td class='centro'>";
		echo $row1['mche_orden']."</td>";
		echo "<td class='centro'>";
		echo $mche_nombre."</td>";
		echo "<td class='dcha'>";
		echo $mche_monto."</td>";
		echo "<td class='dcha'>";
		echo $row1['fecha']."</td>";
		$control_U++; 
		$control= ceroizq ($control_U,4); 
	   	echo "<td class='centro'>";
		echo $control."</td>";
	   	echo "<td class='centro'>";
		$micontrol=explode('/',$row1['fecha']);
		$micontrol=$micontrol[0].$micontrol[1].substr($micontrol[2],2,2).$control;
		$micontrol=ceroizq($micontrol,11);
		echo $micontrol."</td>";
	    echo "<tr>";
	}
//	echo $control; 
	?>
	 <input type="hidden" name="codigo" value="<?php echo $codigo;?>">
	  <input type="hidden" name="fechadesde" value="<?php echo $fechadesde;?>">
	   <input type="hidden" name="fechahasta" value="<?php echo $fechahasta;?>">
	    <input type="hidden" name="chequedesde" value="<?php echo $chequedesde;?>">
		 <input type="hidden" name="chequehasta" value="<?php echo $chequehasta;?>">
		  <input type="hidden" name="control" value="<?php echo $control;?>">
	  	   <input type="hidden" name="control_1" value="<?php echo $control_1;?>">
		      <input type="hidden" name="registro" value="<?php echo $registro;?>">
	</table>

<?php 
// 	&nbsp;</td></tr> 
}
?>