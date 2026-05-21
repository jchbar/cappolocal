<?php
/*
update temp_ahorros set cod_prof = (select cod_prof from sgcaf200 where temp_ahorros.cedula = sgcaf200.ced_prof)
*/
include("head.php");
include("paginar.php");

extract($_GET);
extract($_POST);
extract($_SESSION);


if (!$link OR !$_SESSION['empresa']) {
	include("noempresa.php");
	exit;
}
?>
<body <?php if (!$bloqueo) {echo $onload;}?>>
<script src="ajaxahorro.js" type="text/javascript"></script>


<?php

$readonly=" readonly='readonly'";
include("arriba.php");
$menu61=1;include("menusizda.php");
$cedula = $_GET['cedula'];
$ip = $_SERVER['HTTP_CLIENT_IP'];
if (!$ip) {$ip = $_SERVER['REMOTE_ADDR'];}
// echo 'aretenciones' .$aretenciones[0];
if (!$accion) {
//	echo "<div id='div1'>";
	echo "<form action='aportes.php?accion=sinpago' name='form1' method='post'>";
	echo '<input type="checkbox" name="aportespagos" value = "on"/> Aportes No pagados <br />';
	echo '<input type="submit" name="Submit" value="Enviar" />';
	echo '</form>';
}
// echo 'aportes '.$aportespagos;
if ($aportespagos == 'on') {
	if (($accion == "sinpago")) { //  and ($aportespagos == 'on')) {
		echo "<form action='aportes.php?accion=pedirarchivo' name='form1' method='post'>";
		echo '<input type="hidden" name="aportespagos" value = "on"/>';
		echo 'Fecha del Aporte ';
		$fechaaporte=date("d")."/".date('m')."/".date("Y"); 
		$hoy = date("d/m/Y");
/*
$hoy1 = mktime(0,0,0,date("m"),date("d"),date("Y")); 
$h = date("d/m/Y",$hoy1);
$mas = $hoy1+7257600;  
$meses = date("d/m/Y",$mas); 
escribe_formulario(fechaaporte, form1.fechaaporte, 'd/m/yyyy', $fechaaporte, '', $meses, '0', '10'); 
*/
?>
	<input type="hidden" name="fechaaporte" id="fechaaporte" value=" <?php  echo '00/00/0000'; ?>"/>
   <span style="background-color: #ff8; cursor: default;"
         onmouseover="this.style.backgroundColor='#ff0';"
         onmouseout="this.style.backgroundColor='#ff8';"
         id="show_d3" 
   ><?php  echo '00/00/0000'; ?></span> *</td></tr>
<script type="text/javascript">
    Calendar.setup({
//		showAt(220, 250)
//		position       : 	{100,300},
        inputField     :    "fechaaporte",     // id of the input field
        ifFormat       :    "%d/%m/%Y",     // format of the input field (even if hidden, this format will be honored)
        displayArea    :    "show_d3",       // ID of the span where the date is to be shown
        daFormat       :    "%A, %B %d, %Y",// format of the displayed date
        align          :    "Tl",           // alignment (defaults to "Bl")
        singleClick    :    true,
		weekNumbers    :    true, 

// desactivacion de 18 años pa' tras


		dateStatusFunc :    function (date) { // disable weekend days (Saturdays == 6 and Subdays == 0)
						var today = new Date();
						return (
//							  (date.getTime() < today.getTime()-((365*18)*24*60*60*1000))
							  (date.getTime() > today.getTime()-(1*24*60*60*1000)) 
							  // || date.getTime() > today.getTime()+(10*24*60*60*1000))	date.getDay() == 0 || 
							  ) ? true : false;  }
    });
</script>
<?php		
		echo '<input type="submit" name="Submit" value="Enviar" />';
		echo '</form>';
	}

	if ($accion == "pedirarchivo") {
		echo '<div id="div1">';
		echo '<fieldset>';
		echo '<legend>Fecha de Nomina: '.$fechaaporte."</legend><br>";
		$lafecha=convertir_fecha($fechaaporte);
		$sql = "select * from t_his200 where fecha = '$lafecha' limit 1";
		$rs=(mysql_query($sql)) or die ("<p />Estimado usuario $usuario contacte al administrador Código Nomi-3- <br>".mysql_error()."<br>".$sql);
		if (mysql_num_rows($rs) > 0)
			die ("<h1>No se puede procesar nómina con esta fecha. Ya existe otra con la misma fecha ($fechaaporte)</h1>");
		echo '<form action="aportes.php" method="post" name="form1" enctype="multipart/form-data">';
		echo '<input type="hidden" name="aportespagos" value = "on"/>';
		echo '<input type="hidden" name="accion" value="verificar">';
		echo '<input type="hidden" name="fechaaporte" value="'.$fechaaporte.'" /> Aportes No pagados <br />';
		echo '<input name="archivo" type="file" value="Examinar"><br>';
		echo '<input type="submit" name="Submit" value="Procesar" />';
//		echo "<td><img src='imagenes/animadas/checklist_sm_wht.gif' width='36' height='36' border='0' /></td>";
//		echo 'Verificando archivo <br>';
		echo '</fieldset>';
		echo '</form>';
	}

	if ($accion == "verificar") {
		echo '<div id="div1">';
		//------------------------------------------
		$copiado = 'SI';		// cambiar a no y resolver este problema
		if(@$_FILES['archivo']!=='') // {
			$nueva_ruta='/nominas/';
			$ruta_total = $_SERVER['DOCUMENT_ROOT'].$nueva_ruta;
			$ruta_total = $_SERVER['DOCUMENT_ROOT']."/cajaweb/nominas/".$_FILES['archivo']['name'];
			$BASENAMES = basename( $_FILES['archivo']['name']);
			$nuevo_nombre=$BASENAMES;
/*

original

			$nueva_ruta='/nominas/';
//			$ruta_total = $_SERVER['DOCUMENT_ROOT'].$nueva_ruta;
			$ruta_total = $nueva_ruta;
			$BASENAMES = basename( $_FILES['archivo']['name']);
			$nuevo_nombre=$BASENAMES;
			// echo 'BASENAMES'.$BASENAMES.'<br>';
			$nuevo_nombre_completo = $nuevo_nombre; // .'.'.detecta_extension($BASENAMES);
			// echo 'nuevo nombre '.$nuevo_nombre.'<br>';
			$ruta_total = $ruta_total . $nuevo_nombre_completo;
			echo 'ruta total '.$ruta_total.'<br>';
			if(@move_uploaded_file($_FILES['archivo']['tmp_name'], $ruta_total)) {
				echo "El archivo ha subido al servidor correctamente: ".$nueva_ruta . $nuevo_nombre_completo ;
				$copiado='SI';
				} 
			else { echo 'Ha ocurrido un error al subir el archivo';}
			}
*/
		if (is_uploaded_file($HTTP_POST_FILES['archivo']['tmp_name'])) {
//		    copy($HTTP_POST_FILES['archivo']['tmp_name'], "/nominas");
			} else {
		    	echo "Possible file upload attack. Filename: " . $HTTP_POST_FILES['archivo']['name'];
			}
/*
			echo 'http '. $HTTP_POST_FILES['archivo']['tmp_name'];
			echo $ruta_total.'<br>'; 
			echo 'resultado '.move_uploaded_file($HTTP_POST_FILES['archivo']['tmp_name'], $ruta_total);
*/
			//------------------------------------------

			if (move_uploaded_file($HTTP_POST_FILES['archivo']['tmp_name'], $ruta_total))
			{
				echo '<fieldset><legend>Fecha de la Nomina: '.$fechaaporte.'</legend><br>';
				//	$archivo_name = $_POST['archivo_name'];
				$archivo_name = $nuevo_nombre; 
				$original = $archivo_name;
			//	echo 'http: '.$HTTP_POST_FILES['archivo']['tmp_name'];
				$extension = explode(".",$archivo_name);
				$num = count($extension)-1;
				if (1 == 1) { // (strtoupper($extension[$num]) == "TXT") {
					if($copiado = 'SI') { // $archivo_size < 60000) {
			//			 if (1 == 1) { // (move_uploaded_file($HTTP_POST_FILES['archivo']['tmp_name'], "nominas/".$archivo)) {
			//			 if (move_uploaded_file($archivo_name, $archivo_name)) {
						// if(!copy($archivo, "nominas/".$archivo_name)) {
			//				echo "error al copiar el archivo"; }
			//			else { // echo "archivo subido con exito <br>";
							// separar el archivo con los datos
						procesar($archivo_name,$fechaaporte,$ip);
			//				}
					}
				else
					{ echo "el archivo supera los 60kb"; }
				}
				else
					{ echo "el formato de archivo no es valido, solo .txt => ".$original; }
				echo '</fieldset>';
			}
			else die('fallo la copia');
				
		echo '</div>';
		set_time_limit(30);
	}
	if ($accion == "preparar") { 
		// preparar la impresion
		echo '<div id="div1">';
		// imprimo y preparo proceso
		echo '<fieldset>';
		echo "<td><img src='imagenes/animadas/printingjob_md_wht.gif' width='36' height='36' border='0' /></td>";
		echo "<a target=\"_blank\" href=\"aportesimp.php?elarchivo=$elarchivo&proceso=$proceso&fechaaporte=$fechaaporte\" onClick=\"info.html\', \'\',\'width=250, height=190\')\">Imprimir Reporte</a><br>"; 
		echo "<td><img src='imagenes/animadas/checklist_sm_wht.gif' width='36' height='36' border='0' /></td>";
//		echo 'Verificando archivo <br>';
		echo "<a href=\"aportes.php?accion=almacenar&elarchivo=$elarchivo&aportespagos=on&proceso=$proceso&fechaaporte=$fechaaporte\" onClick=\"info.html\', \'\',\'width=250, height=190\')\">Guardar información</a>"; 
		echo '</fieldset>';
		echo '</div>';
	}

	if ($accion == "almacenar") { 
		// preparar la impresion
		echo '<div id="div1">';
		echo '<fieldset><legend>Procesando información...</legend><br />' ;
		$sql="select * from sgcanomi where proceso='$proceso'";
		$rs=(mysql_query($sql)) or die ("<p />Estimado usuario $usuario contacte al administrador Código Nomi-3- <br>".mysql_error()."<br>".$sql);
		$registros=mysql_num_rows($rs);
		if ($registros < 30)
			set_time_limit(30);	
		else set_time_limit($registros);	
		echo 'Agregando información...<br />' ;
		$emonto = $emonto2 = 0;
		while ($row = mysql_fetch_assoc($rs))
		{
			$sql2="insert into t_his200 (cod_prof, hab_prof, hab_ucla, fecha, ip, proceso,cedula) values ('".$row[socio]."', '".$row[monto]."', '".$row[monto2]."', '".$row[fecha]."', '$ip', '".$row[proceso]."', '".$row[cedula]."')";
			$emonto+=$row[monto];
			$emonto2+=$row[monto2];
			$lafechaa=$row[fecha];
			$rs2=(mysql_query($sql2)) or die ("<p />Estimado usuario $usuario contacte al administrador Código Nomi-4- <br>".mysql_error()."<br>".$sql2);
			$sqls="select * from sgcaf200 where cod_prof='".$row['socio']."'";
			$sqlas=mysql_query($sqls);
			$sqlrs=mysql_fetch_assoc($sqlas);
			if ($sqlrs['f_ing_capu'] == '0000-00-00')
			{
				echo 'Actualizando estatus de '.$sqlrs['ape_prof'].' '.$sqlrs['nombr_prof'].' '.$sqlrs['ced_prof'].'<br>';
				$sqls="update sgcaf200 set statu_prof='Activo', f_ing_capu='".$row['fecha']."' where cod_prof='".$row['socio']."'";
				$sqlas=mysql_query($sqls);
				
			}
		}	
		echo 'Información Agregada...<br />' ;
		$lafecha=explode("-",$lafechaa);
		$asiento=$lafecha[2].$lafecha[1].$lafecha[0].'010';
		$b=explode("/",$fechaaporte); // $rs[fecha]; 12/1/2011
		$b=$b[2].'-'.$b[1].'-'.$b[0];
		$explicacion='';
//		echo 'Generando Comprobante Contable...'.$asiento.'<br />' ;

	echo "Realizando Abonos / Registros contables del asiento <strong><a target=\"_blank\" href='editasi2.php?asiento=$asiento'>$asiento </a></strong><br>";

		$desc='Nomina x Cobrar Retencion/Aporte del '.$fechaaporte;
		$explicacion=$desc;
		$sql = "INSERT INTO sgcaf830 (enc_clave, enc_fecha, enc_desco, enc_desc1, enc_debe, enc_haber, enc_item, enc_dif, enc_igual, enc_refer, enc_sw, enc_explic) VALUES ('$asiento', '$b', '$desc','',0,0,0,0,0,0,0,\"$explicacion\")"; 
		if (!mysql_query($sql)) die ("El usuario $usuario no tiene permiso para añadir Asientos.<br>".mysql_error()."<br>".$sql);
		$sql="select nombre from sgcaf000 where tipo='NomxCobr'";
		$result=mysql_query($sql) or  die ("El usuario $usuario no tiene permiso para consultar configuración <br>".mysql_error());
		$row = mysql_fetch_assoc($result);
		$elcargo='+';
		$debe=$emonto;
		$haber=0;
		$concepto=$desc;
		$referencia=$asiento;
		$cuenta1=$row[nombre];
		agregar_f820($asiento, $b, '+', $cuenta1, $concepto, $debe, $haber, 0,$ip,0,$referencia,'','S',0);
		$row = mysql_fetch_assoc($result);
		$cuenta1=$row[nombre];
		$debe=$emonto2;
		agregar_f820($asiento, $b, '+', $cuenta1, $concepto, $debe, $haber, 0,$ip,0,$referencia,'','S',0);
		$elcargo='-';
		$debe=$emonto;
		$haber=$emonto;
		$row = mysql_fetch_assoc($result);
		$cuenta1=$row[nombre];
		agregar_f820($asiento, $b, '-', $cuenta1, $concepto, $debe, $haber, 0,$ip,0,$referencia,'','S',0);
		$row = mysql_fetch_assoc($result);
		$cuenta1=$row[nombre];
		$debe=$emonto2;
		$haber=$emonto2;
		agregar_f820($asiento, $b, '-', $cuenta1, $concepto, $debe, $haber, 0,$ip,0,$referencia,'','S',0);
		set_time_limit(30);	
		echo 'Proceso finalizado...<br />' ;
		echo '</fieldset>';
		echo '</div>';
	}
}
else
{
	// estan pagando
	if (($accion == "sinpago")) { //  and ($aportespagos == 'on')) {
	echo '<fieldset><legend>Resumen Para Retencion/Aporte</legend>';
	echo '<table align="center" class="basica 100 hover" width="300" border="1">';
	echo '<tr><td>Ahorro Socio</td><td>';
	echo '<input type="text" name="totalnominasocio" id="totalnominasocio" size="8" maxlengt="8"  value=0.00 readonly="readonly"></td></tr>';
	echo '<tr><td>Total Registros Socio</td><td>';
	echo '<input type="text" name="totalregistrosocio" id="totalregistrosocio" size="5" maxlengt="5"  value=0  readonly="readonly"></td></tr>';
	echo '<tr><td>Ahorro UCLA</td><td>';
	echo '<input type="text" name="totalnominaucla" id="totalnominaucla" size="8" maxlengt="8"  value=0.00 readonly="readonly"></td></tr>';
	echo '<tr><td>Total Registros UCLA</td><td>';
	echo '<input type="text" name="totalregistroucla" id="totalregistroucla" size="5" maxlengt="5"  value=0  readonly="readonly"></td></tr>';
	echo '<tr><td>Total Nominas </td><td>';
	echo '<input type="text" name="totalnominas" id="totalnominas" size="8" maxlengt="8"  value=0.00 readonly="readonly"></td></tr>';
	echo '<tr><td>Total Registros</td><td>';
	echo '<input type="text" name="totalregistros" id="totalregistros" size="5" maxlengt="5"  value=0  readonly="readonly"></td></tr>';
	echo '</table>';
	echo '</legend>';
//	echo '<input type="submit" name="Submit" value="Realizar Proceso Retencion/Aporte (Asientos Contables)" />';
	echo '</form>';
	echo '</fieldset>';

		echo '<div id="div1">';
		echo "<form action='aportes.php?accion=fechadepago' name='form1' method='post'>";
		$sql='select fecha, count(fecha) as cantidad, sum(hab_prof) as socio, sum(hab_ucla) as ucla from t_his200 group by fecha order by fecha desc';
		$resultado=mysql_query($sql);
/*
//		funciona para seleccionar una sola fecha 
		echo 'Seleccionar Fecha de Nómina ';
		echo '<select name="fechadeaporte" size="1">';
		while ($fila2 = mysql_fetch_assoc($resultado)) {
			echo '<option value="'.$fila2['fecha'].'" >'.convertir_fechadmy($fila2['fecha']).' - '.$fila2['cantidad'].' - '.number_format($fila2['socio'],2,',','.').' - '.number_format($fila2['ucla'],2,',','.').'</option>';}
	 	echo '</select> '; 
*/
//		seleccionando varios items
		echo "<table class='basica 100 hover' width='100%'>";
		$registrosr = $registrosa = 0;
		while ($fila2 = mysql_fetch_assoc($resultado)) {
			if (($fila2['cantidad'] > 0) and (($fila2['socio'] > 0) or ($fila2['ucla'] > 0)))
				if ($fila2['socio'] > 0)
				{
					$registrosr++;
					echo '<td class="centro azul">';
					echo '<input type="checkbox" id="retencion'.$registrosr.'" name="retencion'.$registrosr.'" value="'.$fila2["fecha"] .'"  onClick="relacion_ahorro()" > </td>';
					echo '<td class="centro azul">Retención</td>';
					echo '<td class="centro azul">' .convertir_fechadmy($fila2['fecha']).' </td><td  class="dcha azul">'.$fila2['cantidad'].' </td><td  class="dcha azul"> '.number_format($fila2['socio'],2,'.',',').'</td></tr>';
//					echo '<td class="centro azul"><input type="checkbox" id="retencion'.$registrosr.'" name="retenciones[]" value='.$fila2["fecha"] .'  onClick="relacion_ahorro()" > </td>' .'<td class="centro azul">Retención</td> <td class="centro azul">' .convertir_fechadmy($fila2['fecha']).' </td><td  class="dcha azul">'.$fila2['cantidad'].' </td><td  class="dcha azul"> '.number_format($fila2['socio'],2,'.',',').'</td></tr>';
				}
				if ($fila2['ucla'] > 0)
				{
					$registrosa++;
					echo '<td class="centro rojo"><input type="checkbox" id="aporte'.$registrosa.'" name="aporte'.$registrosa.'" value='.$fila2["fecha"] .'  onClick="relacion_ahorro()" > </td>' .'<td class="centro rojo">Aportes</td> <td class="centro rojo">' .convertir_fechadmy($fila2['fecha']).' </td><td class="rojo dcha">'.$fila2['cantidad'].' </td><td  class="rojo dcha"> '.number_format($fila2['ucla'],2,'.',',').'</td></tr>';
//					echo '<td class="centro rojo"><input type="checkbox" id="aporte'.$registrosa.'" name="aportes[]" value='.$fila2["fecha"] .'  onClick="relacion_ahorro()" > </td>' .'<td class="centro rojo">Aportes</td> <td class="centro rojo">' .convertir_fechadmy($fila2['fecha']).' </td><td class="rojo dcha">'.$fila2['cantidad'].' </td><td  class="rojo dcha"> '.number_format($fila2['ucla'],2,'.',',').'</td></tr>';
				}
			}
		echo '</table>';		
		echo "<input type = 'hidden' value ='".$registrosr."' name='registrosr' id='registrosr'>";
		echo "<input type = 'hidden' value ='".$registrosa."' name='registrosa' id='registrosa'>";
		echo '<input type="submit" name="Submit" value="Enviar" />';
		echo '</form>';
		echo '</div>';
	}
	if (($accion == "fechadepago")) { //  and ($aportespagos == 'on')) {
		echo '<div id="div1">';
//		echo "<form action='aportes.php?accion=quepagaron&fechadeaporte=$fechadeaporte&' name='form1' method='post'>"; // una sola fecha
		echo "<form action='aportes.php?accion=fechaseleccionada&' name='form1' method='post'>";
		// para varias fechas busco en mysql y lo E
		$eltotal=0;
		$proceso = time();
		$proceso = date("Y-m-d h:i:s",$proceso);
		for ($i=0; $i<$_POST['registrosr'];$i++) {
//			echo "<br />value $i = ".$_POST['retenciones'][$i];
			$variable='retencion'.($i+1);
//			echo '$'.$variable;
//			echo '$$'.$_POST[$variable];
			if ($$variable != "")
			{
//				$sql='select sum(hab_prof) as socio from t_his200 where fecha="'.$_POST['retenciones'][$i].'" group by fecha';
				$sql='select sum(hab_prof) as socio from t_his200 where fecha="'.$$variable.'" group by fecha';
//			echo $sql.'<br>';
				$resultado=mysql_query($sql);
				$fila2 = mysql_fetch_assoc($resultado);
				$eltotal+=$fila2['socio'];
//				$sql="insert into sgcatnom (proceso, fecha, tipo) values ('$proceso', '".$_POST['retenciones'][$i]."', 'R')";
				$sql="insert into sgcatnom (proceso, fecha, tipo) values ('$proceso', '".$_POST[$variable]."', 'R')";
//			echo $sql;
				$resultado=mysql_query($sql) or die(mysql_error());
			}
		}
		$x=0;
		for ($i=0; $i<$_POST['registrosa'];$i++) {
//			echo "<br />value $i = ".$_POST['aportes'][$i];
			$variable='aporte'.($i+1);
//			echo '$'.$variable;
//			echo '$$'.$_POST[$variable];
			if ($$variable != "")
			{
				$sql='select sum(hab_ucla) as ucla from t_his200 where fecha="'.$$variable.'" group by fecha';
//			$sql='select sum(hab_ucla) as ucla from t_his200 where fecha="'.$_POST['aportes'][$i].'" group by fecha';
//				echo $sql.'<br>';
				$resultado=mysql_query($sql);
				$fila2 = mysql_fetch_assoc($resultado);
				$eltotal+=$fila2['ucla'];
//				$sql="insert into sgcatnom (proceso, fecha, tipo) values ('$proceso', '".$_POST['aportes'][$i]."', 'A')";
				$sql="insert into sgcatnom (proceso, fecha, tipo) values ('$proceso', '".$_POST[$variable]."', 'A')";
				$resultado=mysql_query($sql)  or die(mysql_error());
//				echo $sql.'<br>';
			}
		}
//		$eret=array_envia($aretenciones);
		echo '<h1>Monto de los Pagos a Realizar '.number_format($eltotal,2,'.',',').'</h1>';
		//
		echo 'Fecha en que se realizo el pago: ';
/*
		$fechadelpago=date("d")."/".date('m')."/".date("Y"); 
$hoy = date("d/m/Y");
$hoy1 = mktime(0,0,0,date("m"),date("d"),date("Y")); 
$h = date("d/m/Y",$hoy1);
$mas = $hoy1+7257600;  
$meses = date("d/m/Y",$mas); 
escribe_formulario(fechadelpago, form1.fechadelpago, 'd/m/yyyy', $fechadelpago, '', $meses, '0', '10'); 
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
	<input type="hidden" name="fechadelpago" id="fechadelpago" value=" <?php  echo $fechanueva; ?>"/>
   <span style="background-color: #ff8; cursor: default;"
         onmouseover="this.style.backgroundColor='#ff0';"
         onmouseout="this.style.backgroundColor='#ff8';"
         id="show_ingcapu" 
   ><?php  echo '  / /  '; ?></span> *
<script type="text/javascript">
    Calendar.setup({
//		showAt(220, 250)
//		position       : 	{100,300},
        inputField     :    "fechadelpago",     // id of the input field
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
<?php		
		
		echo '<br>Indique el número de referencia: ';
		echo '<input type="text" name="referencia" size="8" maxlengt="8"  />';
		echo '<input type="submit" name="Submit" value="Continuar" />';
		echo '<input type="hidden" name="proceso" value="'.$proceso.'">';
		echo '</form>';
		echo '</div>';		
	}
/* queda sin efecto por ser para uno solo
	if (($accion == "quepagaron")) { //  and ($aportespagos == 'on')) {
		echo '<div id="div1">';
		$sql="select sum(hab_prof) as socio, sum(hab_ucla) as ucla from t_his200 where fecha='$fechadeaporte' group by fecha";
		$resultado=mysql_query($sql);
		$fila2 = mysql_fetch_assoc($resultado);
		if (($fila2['socio'] != 0) and ($fila2['ucla'] != 0)) {
			echo "<form action='aportes.php?accion=fechaseleccionada&fechadeaporte=$fechadeaporte&' name='form1' method='post'>";
			echo '<input type="checkbox" name="lopagadoes" value = "on"/> Cancelaron Retenciones <br />';
			echo '<input type="submit" name="Submit" value="Continuar" />';
			echo '</form>'; }
		else if ($fila2['socio'] != 0) {
			$lopagadoes='on';
			echo 'Se realizará el pago solo de Retenciones de Socio';
			}
			else {
			$lopagadoes='off';
			echo 'Se realizará el pago solo de Aportes de Socio';
			}
		echo '</div>';		
	}
*/
	if (($accion == "fechaseleccionada")) { //  and ($aportespagos == 'on')) {
		echo '<div id="div1">';
/* funciona bien para una sola fecha 
		echo $fechadeaporte. ' - ' .convertir_fecha($fechadelpago);
		$sql="select * from t_his200 where fecha='$fechadeaporte' order by cod_prof";
		$resultado=mysql_query($sql);
		while ($fila2 = mysql_fetch_assoc($resultado)) {
			echo $fila2['cod_prof'].'<br>';
			$monto=$fila2['hab_prof'];
			$sql="insert into fhis200 (cod_prof, hab_prof, hab_ucla, fecha, descri, pago, ip) values (".$fila2['cod_prof'].",'$monto',". $fila2['fecha'].", '$descripcion', '$ip')";
		}
*/
		$fechapago=convertir_fecha($fechadelpago);
		$sql='insert into t_210 (f_ult_apo, tipo_soc) values ("'.$fechapago.'","T")';
		$result=mysql_query($sql);
//		$sql='select * from t_210 where f_ult_apo="'.$fechapago . '" and tipo_soc ="T"';
		$sql='select * from t_210 where tipo_soc = "T"';
		$result=mysql_query($sql);
		$fila1 = mysql_fetch_assoc($result);
// 		$sql='delete from t_210 where f_ult_apo="'.$fechapago . '" and tipo_soc ="T"';
		$sql='delete from t_210 where tipo_soc = "T"';
		$result=mysql_query($sql);
		$comprobante = $fila1['f_ult_apo'];
//		echo 'fecha generada '.$comprobante;
		$comprobante = explode("-", $comprobante);
		$asiento = $comprobante[2].$comprobante[1].$comprobante[0].'011';

//		echo 'Generando Comprobante Contable...'.$asiento.'<br />' ;
		echo "Generando Comprobante / Registros contables del asiento <strong><a target=\"_blank\" href='editasi2.php?asiento=$asiento'>$asiento </a></strong><br>";
		$desc='Cancelación Retencion y/o Aporte realizada el '.$fechadelpago;
		$explicacion=$desc;
		$b=explode('/',$fechadelpago);
		$b=$b[2].'-'.$b[1].'-'.$b[0];
//		echo 'la fecha '.$b;
		$sql = "INSERT INTO sgcaf830 (enc_clave, enc_fecha, enc_desco, enc_desc1, enc_debe, enc_haber, enc_item, enc_dif, enc_igual, enc_refer, enc_sw, enc_explic) VALUES ('$asiento', '".$b."', '$desc','',0,0,0,0,0,0,0,\"$explicacion\")"; 
		if (!mysql_query($sql)) die ("El usuario $usuario no tiene permiso para añadir Asientos.<br>".mysql_error()."<br>".$sql);

		$eltotal=$totalaporte=$totalahorro=0;
		$sql='select * from sgcatnom where proceso="'.$proceso.'"';
		$resultado=mysql_query($sql);
		while ($fila1 = mysql_fetch_assoc($resultado)) {
			$sql="select * from t_his200 where fecha='".$fila1['fecha']."' order by cod_prof";
//			echo $sql.'<br>';
			$resulta2=mysql_query($sql);
			$registros=mysql_num_rows($resulta2);
			$descripcion=($fila1['tipo']=='R'?'Ret.':'Apt.').' del '.convertir_fechadmy($fila1['fecha']).' pagado el ('.$fechadelpago.')';
			echo '<h2>'.$descripcion.'</h2><br>';

			$sql="insert into aportep (tipo, fecha) values ('".$fila1['tipo']."','".$fila1['fecha']."')";
			$resulta_aportep=mysql_query($sql);
//			echo $sql;

			$subtotal = 0;			
			$cuantos =0;
			while ($enfila2 = mysql_fetch_assoc($resulta2)) {
//				echo $enfila2['cod_prof'].'<br>';
				set_time_limit($registros);	
				if ($fila1['tipo']=='R') {
					$monto=$enfila2['hab_prof'];
					$totalahorro+=$monto;
					$sql1="insert into fhis200 (cod_prof, hab_prof, fecha, descri, pago, ip) values ('".$enfila2['cod_prof']."',$monto, '". $enfila2['fecha']."', '$descripcion', '$fechapago', '$ip')";
					$sql2="update t_his200 set hab_prof = 0 where numreg =". $enfila2['numreg'];
					$sql3="update sgcaf200 set hab_f_prof = hab_f_prof + ".$monto.", ultap_prof='".$enfila2['fecha']."', ultapm_prof='".$monto."' where cod_prof ='". $enfila2['cod_prof']."'";
					if (!mysql_query($sql1)) die ("El usuario $usuario no tiene permiso para HF200-1.<br>".mysql_error()."<br>".$sql1);
					if (!mysql_query($sql2)) die ("El usuario $usuario no tiene permiso para T200-1.<br>".mysql_error()."<br>".$sql2);
					if (!mysql_query($sql3)) die ("El usuario $usuario no tiene permiso para F200-1.<br>".mysql_error()."<br>".$sql3);
					$cuantos++;
					}
				else {
					$monto=$enfila2['hab_ucla'];
					$totalaporte+=$monto;
					$sql1="insert into fhis200 (cod_prof, hab_ucla, fecha, descri, pago, ip) values ('".$enfila2['cod_prof']."',$monto, '". $enfila2['fecha']."', '$descripcion', '$fechapago', '$ip')";
					$sql2="update t_his200 set hab_ucla = 0 where numreg =". $enfila2['numreg'];
					$sql3="update sgcaf200 set hab_f_empr = hab_f_empr + ".$monto.", ultap_emp='".$enfila2['fecha']."', ultapm_emp='".$monto."' where cod_prof ='". $enfila2['cod_prof']."'";
					if (!mysql_query($sql1)) die ("El usuario $usuario no tiene permiso para HF200-2.<br>".mysql_error()."<br>".$sql1);
					if (!mysql_query($sql2)) die ("El usuario $usuario no tiene permiso para T200-2.<br>".mysql_error()."<br>".$sql2);
					if (!mysql_query($sql3)) die ("El usuario $usuario no tiene permiso para F200-2.<br>".mysql_error()."<br>".$sql3);
					$cuantos++;
					}
				$subtotal+=$monto;
				$eltotal+=$monto;
			}
// 			echo 'cuantos '.$cuantos;
			// genero registro parcial del pago
			if ($fila1['tipo']=='R') {
				$sql3="select nombre from sgcaf000 where tipo='NomxPag2+'";	 // cargo a ahorros x distribuir
				hacer_asiento('NomxPag2+',$subtotal,'+',$descripcion,$asiento,$b,$referencia);
				$sql4="select nombre from sgcaf000 where tipo='NomxPag2-'";	 // abono a cuenta x cobrar ahorros
				hacer_asiento('NomxPag2-',$subtotal,'-',$descripcion,$asiento,$b,$referencia);
			}
			else {
				$sql3="select nombre from sgcaf000 where tipo='NomxPag3+'";	// cargo a aportes x distribuir
				$sql4="select nombre from sgcaf000 where tipo='NomxPag3-'";	 // abono cuenta x cobrar aportes
				hacer_asiento('NomxPag3+',$subtotal,'+',$descripcion,$asiento,$b,$referencia);
				hacer_asiento('NomxPag3-',$subtotal,'-',$descripcion,$asiento,$b,$referencia);
			}
		}
		// elimino la info para no crear basura 
		$sql='delete from sgcatnom where proceso="'.$proceso.'"';
		$resultado=mysql_query($sql);
		hacer_asiento('NomxPag1+',$eltotal,'+',$desc,$asiento,$b,$referencia);
		if ($totalahorro != 0) 
			hacer_asiento('NomxPag4-',$totalahorro,'-','Retenciones cancelada el '.$fechadelpago,$asiento,$b,$referencia);
		if ($totalaporte != 0) 
			hacer_asiento('NomxPag5-',$totalaporte,'-','Aportes cancelado el '.$fechadelpago,$asiento,$b,$referencia);
		echo '<h2>Proceso Completado </h2><br>';
		echo '</div>';		
		set_time_limit(30);	
/*
		3-1-1-01-01-0001	3-01-01-01-01-01-0001
		3-1-1-02-02-0002 	3-01-03-01-01-01-0002
------------------------
		$sql="select nombre from sgcaf000 where tipo='NomxPag1+'";	// cargo a banco
		$result=mysql_query($sql) or  die ("El usuario $usuario no tiene permiso para consultar configuración <br>".mysql_error());
		$row = mysql_fetch_assoc($result);
		$elcargo='+';
		$debe=$emonto;
		$haber=0;
		$concepto=$desc;
		$referencia=$asiento;
		$cuenta1=$row[nombre];
		agregar_f820($asiento, $b, '+', $cuenta1, $concepto, $debe, $haber, 0,$ip,0,$referencia,'','S',0);
		$row = mysql_fetch_assoc($result);
		$cuenta1=$row[nombre];
		$debe=$emonto2;
		agregar_f820($asiento, $b, '+', $cuenta1, $concepto, $debe, $haber, 0,$ip,0,$referencia,'','S',0);

------------------------
*/
}
}

function hacer_asiento($cuentabuscar, $monto, $debcre, $desc,$asiento,$fechadelpago,$referencia)
{
		$sql="select nombre from sgcaf000 where tipo='".$cuentabuscar."'";	
		$result=mysql_query($sql) or  die ("El usuario $usuario no tiene permiso para consultar configuración <br>".mysql_error());
		$row = mysql_fetch_assoc($result);
		$elcargo=$debcre;
//		if ($elcargo == '+') {
			$debe=$monto;
			$haber=0; // }
/*
		else {
			$debe=0;
			$haber=$monto; }
*/
		$concepto=$desc;
// 		$referencia=$asiento;
		$cuenta1=$row[nombre];
		echo 'Generando registro '.$concepto.'<br>';
		agregar_f820($asiento, $fechadelpago, $elcargo, $cuenta1, $concepto, $debe, $haber, 0,$ip,0,$referencia,'','S',0);
}

function array_envia($array) {
    $tmp = serialize($array);
    $tmp = urlencode($tmp);
    return $tmp;
} 

function array_recibe($url_array) {
    $tmp = stripslashes($url_array);
    $tmp = urldecode($tmp);
    $tmp = unserialize($tmp);
   return $tmp;
} 

function procesar($archivo_name,$fechaaporte,$ip)
{
	echo 'Verificación de archivo <br>';
	$lines = file('nominas/'.$archivo_name);
	$faltoalguno=0;
	set_time_limit($lines);
	foreach ($lines as $line_num => $linea) {
		$datos = explode("|", $linea);
		$cedula=ceroizq(trim(substr($datos[0],0,8)),8);
		$cedula = 'V-'.$cedula;
		$sql='select ape_prof, nombr_prof from sgcaf200 where ced_prof="'.$cedula.'"';
		$result=mysql_query($sql) or die ("<p />El usuario $usuario no tiene permisos para generar consulta<br>".mysql_error()."<br>".$sql);
		if (mysql_num_rows($result) == 0) {
			echo 'La cédula '.$cedula.' no esta registrada <br>';
			$faltoalguno = 1; }
					
// 					echo substr($datos[0],0,8).' - '.substr($datos[0],10,4).' - '.substr($datos[0],16,10).'<br>';
//					echo $datos[0].' - '.$datos[1].' - '.$datos[2].' - '.$datos[3].' - '.$datos[4].' - '.$datos[5].' - '.$datos[6].' - '.$datos[7].'<br>';
				}
		if ($faltoalguno == 0) 
		{
			$lafecha=convertir_fecha($fechaaporte);
//			echo $lafecha;
			$proceso = time();
			$proceso = date("Y-m-d h:i:s",$proceso);
			echo 'Convirtiendo archivo <br>';
			foreach ($lines as $line_num => $line) {
				$datos = explode("|", $line);
				$codigo=substr($datos[0],10,4);
				$monto=trim(substr($datos[0],14,12));
				$cedula=ceroizq(trim(substr($datos[0],0,8)),8);
				$cedula = 'V-'.$cedula;
				$sql='select ape_prof, nombr_prof, cod_prof from sgcaf200 where ced_prof="'.$cedula.'"';
				$result=mysql_query($sql) or die ("<p />El usuario $usuario no tiene permisos para generar consulta<br>".mysql_error()."<br>".$sql);
				$row_socio=mysql_fetch_assoc($result);
				$socio = $row_socio['cod_prof'];
				$sql="select * from sgcanomi where cedula = '$cedula' and proceso='$proceso'";
				$rs=(mysql_query($sql)) or die ("<p />Estimado usuario $usuario contacte al administrador Código Nomi-1- <br>".mysql_error()."<br>".$elsql); 
				$row=mysql_num_rows($rs);
				if ($row == 0)
				{ 
					//echo '.';
					$sql="insert into sgcanomi (archivo, fecha, cedula, socio, nombre, codigo, monto, proceso, ip) VALUES ('$archivo_name','$lafecha','$cedula', '$socio', '".trim($row_socio['ape_prof'])." ,".trim($row_socio['nombr_prof'])."', '$codigo', '$monto', '$proceso', '$ip')";
				}
				else {
					// echo ':';
					$sql = "update sgcanomi set codigo2='$codigo', monto2='$monto' where cedula = '$cedula' and proceso='$proceso'";
					}
				$rs=(mysql_query($sql)) or die ("<p />Estimado usuario $usuario contacte al administrador Código Nomi-2- <br>".mysql_error()."<br>".$sql);
				}
				echo "<a href=\"aportes.php?accion=preparar&aportespagos=on&elarchivo=$archivo_name&proceso=$proceso&fechaaporte=$fechaaporte\" onClick=\"info.html\', \'\',\'width=250, height=190\')\">Continuar proceso</a>"; 
			}

}
// ALTER TABLE `sgcanomi` ADD INDEX ( `cedula` )  
?>

<?php include("pie.php");?>

</body></html>

