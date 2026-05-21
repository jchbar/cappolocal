<?php

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
<script src="ajaxvoluntario.js" type="text/javascript"></script>
<script language="javascript">
//Creo una función que imprimira en la hoja el valor del porcentanje asi como el relleno de la barra de progreso
function callprogress(vValor){
 document.getElementById("getprogress").innerHTML = vValor;
 document.getElementById("getProgressBarFill").innerHTML = '<div class="ProgressBarFill" style="width: '+vValor+'%;"></div>';
}
</script>
<style type="text/css">
/* Ahora creo el estilo que hara que aparesca el porcentanje y relleno del mismoo*/
      .ProgressBar     { width: 16em; border: 1px solid black; background: #eef; height: 1.25em; display: block; }
      .ProgressBarText { position: absolute; font-size: 1em; width: 16em; text-align: center; font-weight: normal; }
      .ProgressBarFill { height: 100%; background: #aae; display: block; overflow: visible; }
    </style>
</script>

<body <?php if (!$bloqueo) {echo $onload;}?>>

<?php
	$prueba = 2;
	if ($prueba == 1)
	{
		$sqlresbanco="truncate sgcaresb";
		$ressql=mysql_query($sqlresbanco);
		$sqlresbanco="delete from sgcaf820 where com_nrocom='20230210010' or com_nrocom='20230211011' or com_nrocom='20230212012' or com_nrocom='20230210010'";
		$ressql=mysql_query($sqlresbanco) or die(mysql_error());
		$sqlresbanco="delete from sgcaf830 where enc_clave='20230210010' or enc_clave='20230211011' or enc_clave='20230212012' or enc_clave='20230210010'";
		$ressql=mysql_query($sqlresbanco);
		$sqlresbanco="delete from sgcaf820 where com_nrocom='20230212456' or com_nrocom='20230211456' or com_nrocom='20230210456'";
		$ressql=mysql_query($sqlresbanco) or die(mysql_error());
		$sqlresbanco="delete from sgcaf830 where enc_clave='20230212456' or enc_clave='20230211456' or enc_clave='20230210456'";
		$ressql=mysql_query($sqlresbanco);
	}

$readonly=" readonly='readonly'";
include("arriba.php");
$menu61=1;include("menusizda.php");
$ip = $_SERVER['HTTP_CLIENT_IP'];
if (!$ip) {$ip = $_SERVER['REMOTE_ADDR'];}
if (!$accion) {
	echo "<div id='div1'>";
	$sql="select DATE_SUB(NOW(),interval 180 day) as viejos";
	$a_sql=mysql_query($sql);
	$viejo=mysql_fetch_assoc($a_sql);
	$viejo=$viejo['viejos'];
	$sql='SELECT fecha, sum(hab_voluntario) as monto, count(fecha) as cuantos, pertenece FROM t_his200 where hab_voluntario > 0 and (fecha > "'.$viejo.'") and pertenece = "AV" group by fecha desc, pertenece asc ';
	$a_sql=mysql_query($sql);
	// echo $sql; 
	echo "<form action='prav.php?accion=Abonar' name='form1' method='post' enctype='multipart/form-data' onsubmit='return realizar_abono(form1)'>";
	?>
	<fieldset><legend>Relacionar Respuesta Ahorro Voluntario</legend>
	<table>
	<tr><td>Primer Archivo de Devolucion</td><td> <input name="archivo[]" type="file" value="Examinar"></td></tr>
	<tr><td>Segundo Archivo de Devolucion</td><td> <input name="archivo[]" type="file" value="Examinar"></td></tr>
	<tr><td>Tercer Archivo de Devolucion</td><td> <input name="archivo[]" type="file" value="Examinar"></td></tr>


	<tr><td>Primera Fecha Proceso del Banco</td><td><input type="hidden" name="primerafecha" id="primerafecha" value="">
		<span style="background-color: #ff8; cursor: default;"
		onmouseover="this.style.backgroundColor='#ff0';"
		onmouseout="this.style.backgroundColor='#ff8';"
		id="show_d3" 
			><?php  echo '00/00/0000'; ?></span>
		<script type="text/javascript">
		    Calendar.setup({
		        inputField     :    "primerafecha",     // id of the input field
		        // ifFormat       :    "%d/%m/%Y",     // format of the input field (even if hidden, this format will be honored)
		        ifFormat       :    "%Y-%m-%d",     // format of the input field (even if hidden, this format will be honored)
		        displayArea    :    "show_d3",       // ID of the span where the date is to be shown
		        daFormat       :    "%A, %B %d, %Y",// format of the displayed date
		        align          :    "Tl",           // alignment (defaults to "Bl")
		        singleClick    :    true,
				weekNumbers    :    false,  
				dateStatusFunc :    function (date) 
				{ 
					var today = new Date();
					// return (
					  // (date.getTime() > today.getTime()-(1*24*60*60*1000)) 
									  // ) ? true : false;  
				}
			});
		</script>
		</td>
	</tr>

	<table align="center" class="basica 100 hover" width="500" border="1">
	<tr><th width="50">Fecha</th><th width="20">Opcion</th><th width="80">Monto</th><th width="80">Cantidad</th><th width="40">Procesar</th>

	<?php 
	$registros=0;
	while($r=mysql_fetch_assoc($a_sql)) {
		echo '<tr>';
		echo '<td>'.convertir_fechadmy($r['fecha']).'</td>';
		echo '<td>'.$r['pertenece'].'</td>';
		echo '<td align="right">'.number_format($r['monto'],2,".",",").'</td>';
		echo '<td align="right">'.number_format($r['cuantos'],0,".",",").'</td>';
		$registros++;
		$fecha = "'".$r['fecha']."'";
		$fecha.= ',';
		$fecha .= "'".$r['pertenece']."'";
		echo '<td class="centro azul"><h1><input type="checkbox" id="cancelar'.$registros.'" name="cancelar'.$registros.'" onClick="amor_cap('.$fecha.\')"';
		echo '></td></tr>' ;
	}
	echo "<input type = 'hidden' value ='".$registros."' name='registros' id='registros'>";
	echo "<input type = 'hidden' value ='".$fechanomina."' name='fechanomina' id='fechanomina'>";
	echo "<input type = 'hidden' value ='".$tiponomina."' name='tiponomina' id='tiponomina'>";
	echo "<input type = 'hidden' value ='5' name='comisionbancaria' id='comisionbancaria'>";
	echo '</table>';
	echo '</legend>';
	echo '</div>';

	echo '<fieldset><legend>Resumen Para Ahorro Voluntario</legend>';
	echo '<table align="center" class="basica 100 hover" width="300" border="1">';
	echo '<tr><td>Total Nominas </td><td>';
	echo '<input type="text" name="totalnominas" id="totalnominas" size="8" maxlengt="8"  value=0.00 readonly="readonly"></td></tr>';
	echo '<tr><td>Total Registros</td><td>';
	echo '<input type="text" name="totalregistros" id="totalregistros" size="5" maxlengt="5"  value=0  readonly="readonly"></td></tr>';
	echo '</table>';
	echo '</legend>';
	echo '<input type="submit" name="Submit" value="Realizar Abono a Retenciones (Asientos Contables)" />';
	echo '</form>';
}	// if (!$accion) 
if ($accion=='Abonar') 
{
	$registros=$_POST['registros'];
	echo '
		<div class="ProgressBar">
			<div class="ProgressBarText"><span id="getprogress"></span>&nbsp;% completado</div>
			<div id="getProgressBarFill"></div>
		</div>';

	extract($_POST);
	// echo isset($primerafecha);
	// die('la fecha '.empty($primerafecha));
	if (empty($primerafecha)) //  == '00')
		die('<h1>No se definio fecha');
	// phpinfo();
	$fecha = $fechanomina;
	$tipo = $tiponomina;

	// $sql="delete from sgcaf830 where enc_clave = '20230216030'"; 
	// $a_amor=mysql_query($sql);
	// $sql="delete from sgcaf830 where enc_clave = '20230216031'"; 
	// $a_amor=mysql_query($sql);
	// $sql="delete from sgcaf830 where enc_clave = '20230216032'"; 
	// $a_amor=mysql_query($sql);

	// $sql="delete from sgcaf820 where com_nrocom = '20230216030'"; 
	// $a_amor=mysql_query($sql);
	// $sql="delete from sgcaf820 where com_nrocom = '20230216031'"; 
	// $a_amor=mysql_query($sql);
	// $sql="delete from sgcaf820 where com_nrocom = '20230216032'"; 
	// $a_amor=mysql_query($sql);
	// $sql="delete from sgcaresb where substr(fechaproc,1,10) = '2023-01-28'"; 
	// $a_amor=mysql_query($sql);
	// $sql="delete from fhis200 where substr(fecha,1,10) = '2023-02-16'"; 
	// $a_amor=mysql_query($sql);
	// // $sql="delete from t_his200 where fecha = '2023-02-15'"; 
	// // $a_amor=mysql_query($sql);
	// // $sql="delete from t_his200 where fecha = '0000-00-00'"; 
	// // $a_amor=mysql_query($sql);

	// var_dump($archivo);
	// die('espero');

	for ($veces=0;$veces<3;$veces++)	// recorrer los 3 archivos y procesar
	{
		// primero reviso el archivo de indomiciliados  archivo del domingo
		$copiado = 'SI';		// cambiar a no y resolver este problema
		$elarchivo=$nom_arc[$veces];
			// echo 'el archivo '.$elarchivo;

		if(@$_FILES['archivo']['name'][$veces]!=='') 
		{
			$salida='devolucion_banco_voluntario/sica_'.$_FILES['archivo']['name'][$veces];
			$archivosalida=fopen ($salida, "w+");
			$nueva_ruta='devolucion_banco_voluntario/';
			$ruta_total = $_SERVER['DOCUMENT_ROOT'].$nueva_ruta;
			$ruta_total = $_SERVER['DOCUMENT_ROOT']."/cajaweb/devolucion_banco_voluntario/".$_FILES['archivo']['name'][$veces];
			$BASENAMES = basename( $_FILES['archivo']['name'][$veces]);
			$nuevo_nombre=$BASENAMES;
			//		echo 'el archivo '.$elarchivo. ' / '.'$$elarchivo' .'<<<<<';
			if (is_uploaded_file($HTTP_POST_FILES['archivo']['tmp_name'][$veces])) {
				$destino='devolucion_banco_voluntario/';
				$destino.=$_FILES['archivo']['name'][$veces];
				//			echo 'destino '.$destino.'<br>';
				if (move_uploaded_file($_FILES['archivo']['tmp_name'][$veces],$destino));
					else die ('fallo copia');
			} 
			else {
			   	die ("Possible file upload attack. Filename: " . $HTTP_POST_FILES['archivo']['name'][$veces]);
			}
			$archivo_name = $nuevo_nombre; 
			$original = $archivo_name;
			$extension = explode(".",$archivo_name);
			$num = count($extension)-1;
			if (1 == 1) { // (strtoupper($extension[$num]) == "TXT") {
				if($copiado = 'SI') { // $archivo_size < 60000) {
					// separar el archivo con los datos
		// $elarchivo=$nom_arc[2];
	// var_dump($archivo_name);
	// die('espero');
					procesar($archivo_name,$primerafecha,$ip,$archivosalida,$numerocuotas,$veces,$fecha);
				}
				else
					{ echo "el archivo supera los 60kb"; }
				}
			else
				{ echo "el formato de archivo no es valido, solo .txt => ".$original; }
			set_time_limit(30);
		}

		
		
		for ($i=0;$i<$registros;$i++)	// no es necesarios revisar el check si aparece es porq estan seleccionados para hacer el asiento 
		{
			$variable='cancelar'.($i+1);
			if (!empty($$variable)) 
			{
				// $fecha=explode('-',$$variable);
				$b=$fecha[0].'-'.$fecha[1].'-'.$fecha[2];
				// $fecha=$b;
				// $tipo = $_POST['tipo'];

				// $nuevafecha="select date_add('$fecha',INTERVAL ".$veces." DAY) as fecha";
				$nuevafecha="select date_add('$primerafecha',INTERVAL ".$veces." DAY) as fecha";
				$rsqln=mysql_query($nuevafecha);
				$asqln=mysql_fetch_assoc($rsqln);
				$otrafecha=($asqln['fecha']);

				$sql="select * from t_his200 where (hab_voluntario > 0) and (fecha ='$fechanomina') and (pertenece = '$tipo') order by numreg"; 
				// echo $sql.'<br>';
				$a_amor=mysql_query($sql);
				$tiempoestimado=mysql_num_rows($a_amor);
				set_time_limit($tiempoestimado<30?30:$tiempoestimado);
				$ValorTotal=$tiempoestimado;
				$cuantos=0;
				if ($ValorTotal > 0) 
				{
					/*
					if ($tipo == 'AP')
					{
					*/
						$parte='01';
						$concepto='Ahorro Voluntario';
					/*
					}
					elseif ($tipo == 'JP')
						{
							$parte='02';
							$concepto='Jubilados Provincial';
						}
						elseif ($tipo == 'AO')
						{
							$parte='03';
							$concepto='Activos Otros Bancos';
						}
						else
						{
							$parte='04';
							$concepto='Jubilados Otros Bancos';
						}
					*/
					$parte.=$veces;

					// $parte = ($tipo == 'AP'?'01':($tipo == 'JP'?'02':($tipo == 'AO')?'03':'04')).$veces;
					// $concepto = ($tipo == 'AP'?'Activos Provincial':($tipo == 'JP'?'Jubilados Provincial':($tipo == 'AO'?'Activos Otros Bancos':'Jubilados Otros Banco')));
					$referencia='';
					// echo "parte '$parte' tipo '$tipo' concepto '$concepto' otrafecha '$otrafecha'"; 
					$ofecha=explode('-',$otrafecha);
					$b=$ofecha[0].'-'.$ofecha[1].'-'.$ofecha[2];
					$elasiento1=$ofecha[0].$ofecha[1].$ofecha[2].$parte;

					crear_encabezado($elasiento1,$b,'p/r '.$concepto);
					$ofecha=$b;
					echo "<h2>Procesando $concepto</h2><br>";
					$a_amor=mysql_query($sql);
					$tiempoestimado=mysql_num_rows($a_amor);
					$mpasaron = $mnopasaron = $mcomision = 0;
					$calcular_comision = ($veces < 1?true:false);

					while ($r_amor = mysql_fetch_assoc($a_amor)) 
					{
						$cuantos++;
						$porcentaje = $cuantos * 100 / $ValorTotal; //saco mi valor en porcentaje
						echo "<script>callprogress(".round($porcentaje).")</script>"; //llamo a la función JS(JavaScript) para actualizar el progreso
						flush(); //con esta funcion hago que se muestre el resultado de inmediato y no espere a terminar todo el bucle con los 25 registros para recien mostrar el resultado
						ob_flush();

						// revisar si esta indomiciliado
						$mostrar=0;
						$resultado=indomiciliado($r_amor['cedula'], $fechanomina,$motivo,$mostrar, $ofecha);
						$estado=substr($resultado,0,1);

						// echo $estado;

						if ($estado != '2')
						{
							/*
							if ($calcular_comision)
								$lacomision = round($r_amor['hab_voluntario'] * ($comisionbancaria / 100),2);
							else
								$lacomision = 0;
							*/
							$lacomision = $r_amor['comision'];
							$mcomision += $lacomision;
							if ($estado == 0) 
							{
								$mpasaron += $r_amor['hab_voluntario'];
							}
							else 
							{
								$mnopasaron += $r_amor['hab_voluntario'];
							}
							registrar_paso($resultado, $r_amor, $lacomision);
						}
					}

					$desc='Nomina Ahorro Voluntario del '.$otrafecha;
					$referencia = $tipo;
					$asiento = $elasiento1;
					// parte 1
 					hacer_asiento('IngresoBanco', $mpasaron, '+', $desc, $asiento, $b, $referencia);
 					hacer_asiento('RetxCobBco', $mpasaron, '-', $desc, $asiento, $b, $referencia);
					// parte 2
 					hacer_asiento('NomxPag4-', $mpasaron, '-', $desc, $asiento, $b, $referencia);
					hacer_asiento('RetxDisBco', $mpasaron, '+', $desc, $asiento, $b, $referencia);

 					// hacer_asiento('RetxCobBco', $mpasaron, '+', $desc, $asiento, $b, $referencia);
					// hacer_asiento('RetxDisBco', $mpasaron, '-', $desc, $asiento, $b, $referencia);
					// parte 3
					$desc='Comision Banco Retencion del '.$otrafecha;
					hacer_asiento('ComisionDom', $mcomision, '+', $desc, $asiento, $b, $referencia);
					hacer_asiento('IngresoBanco', $mcomision, '-', $desc, $asiento, $b, $referencia);

					// $desc='Nomina NO Cobrada Retencion del '.$otrafecha;
					// hacer_asiento('RetxDisBco', $mnopasaron, '-', $desc, $asiento, $b, $referencia);
 					// hacer_asiento('RetxCobBco', $mnopasaron, '+', $desc, $asiento, $b, $referencia);

 					// servicio_sistema($b, $mcomision); 

				} // el registro esta marcado
			}

		}	// ciclo de registros marcados 
		set_time_limit(30);	
	}	// if (!$accion=='Abonar')
}

function hacer_asiento($cuentabuscar, $monto, $debcre, $desc,$asiento,$fechadelpago,$referencia)
{
	$sql="select nombre from sgcaf000 where tipo='".$cuentabuscar."'";	
	// echo $sql;
	$result=mysql_query($sql) or  die ("El usuario $usuario no tiene permiso para consultar configuración <br>".mysql_error());
	$row = mysql_fetch_assoc($result);
	// var_dump($row);
	$elcargo=$debcre;
	$debe=$monto;
	$haber=0; 
	$concepto=$desc;
	$cuenta1=$row['nombre'];
	// echo 'Generando registro '.$concepto.'<br>';
	agregar_f820($asiento, $fechadelpago, $elcargo, $cuenta1, $concepto, $debe, $haber, 0,$ip,0,$referencia,'','S',0);
}


function registrar_paso($resultado, $registro, $lacomision)
{
	// echo 'llegue';
	$resultado=explode("|",$resultado);
	$estado = $resultado[0];
	$fechadelpago = $resultado[1];
	$motivo = $resultado[2];

	// var_dump($resultado);

	$codigo = $registro['cod_prof'];
	$monto_retencion = $monto = $registro['hab_voluntario'];
	// $monto -= $lacomision;
	// $monto -= $lacomision;
	$dcto2 = $lacomision; 

	$fecha = $registro['fecha'];

	$descripcion='Vol. '.convertir_fechadmy($registro['fecha']).' Bco. '.$fechadelpago.' '.SUBSTR($motivo,0,5);
	$descripcion3='Vol. '.convertir_fechadmy($registro['fecha']).' Com.Bco. '.$fechadelpago;

	// guardar en el historico satisfactorio
	$sql1="insert into fhis200 (cod_prof, hab_voluntario, fecha, descri, pago, ip, procesado, monto_retencion, dcto01, dcto02) values ('$codigo', $monto, '$fecha', '$descripcion', '$fechadelpago', '$ip', now(), $monto_retencion, $lacomision, $dcto2)";
	$sql2="update t_his200 set hab_voluntario = 0 where numreg =". $registro['numreg'];
	// restar comision bancaria ?
	// $sql3="insert into fhis200 (cod_prof, hab_voluntario, fecha, descri, pago, ip, procesado) values ('$codigo', ($lacomision*-1), '$fecha', '$descripcion3', '$fechadelpago', '$ip', now())";

	$r1 = mysql_query($sql1) or die ("El usuario $usuario no tiene permiso para HF200-1.<br>".mysql_error()."<br>".$sql1);
	// if (!mysql_query($sql3)) die ("El usuario $usuario no tiene permiso para HF200-3.<br>".mysql_error()."<br>".$sql1);

	if ($estado == 0) // paso
	{
		// contador historial retenciones
		// marcar socio activo
		// actualizar ahorro / fecha / monto 
		// blanquear t_his200
		$r1 = mysql_query($sql2) or die ("El usuario $usuario no tiene permiso para T200-1.<br>".mysql_error()."<br>".$sql2);
		$sql3="update sgcaf200 set hab_f_extr = hab_f_extr + ".$monto.", ultap_extr='".$registro['fecha']."', ultapm_extr='".$monto."' where cod_prof ='". $registro['cod_prof']."'";
		// , domiciliacion_buena = domiciliacion_buena + 1,  domiciliacion_falla = 0 
		$r1 = mysql_query($sql3) or die ("El usuario $usuario no tiene permiso para F200SA-1.<br>".mysql_error()."<br>".$sql3);
	}
	else
	{
		// guardar en el historico  anulado
		$sql1="insert into fhis200 (cod_prof, hab_voluntario, fecha, descri, pago, ip, procesado, monto_retencion, dcto01, dcto02) values ('$codigo', ($monto*-1), '$fecha', '$descripcion', '$fechadelpago', '$ip', now(), $monto_retencion, $lacomision, $dcto2)";
		$r1=mysql_query($sql1) or die ("El usuario $usuario no tiene permiso para HF200-1.<br>".mysql_error()."<br>".$sql1);
		// contador historial retenciones
		// marcar socio activo con reserva?
		$sql3a="update sgcaf200 set hab_f_prof = hab_f_prof - ".$lacomision." where cod_prof ='". $registro['cod_prof']."'";
		// if (!mysql_query($sql3a)) die ("El usuario $usuario no tiene permiso para F200SD-2.<br>".mysql_error()."<br>".$sql3a);
	}
}


function crear_encabezado($asiento,$fecha,$cuento)
{
	if (substr(trim($asiento),-3)<>'456')
		echo "Realizando Abonos / Registros contables del asiento <strong><a target=\"_blank\" href='editasi2.php?asiento=$asiento'>$asiento </a></strong> $cuento <br>";
	$sql = "INSERT INTO sgcaf830 (enc_clave, enc_fecha, enc_desco, enc_desc1, enc_debe, enc_haber, enc_item, enc_dif, enc_igual, enc_refer, enc_sw, enc_explic) VALUES ('$asiento', '$fecha', '$cuento','',0,0,0,0,0,0,0,'$cuento')"; 
	$r1 = mysql_query($sql) or die ("El usuario $usuario no tiene permiso para añadir Asientos.<br>".$sql);
}

function indomiciliado($cedula, $fecha, &$motivo, $mostrar, $fechaproceso)
{
	$sqlin="select * from sgcaresb where cedula = '$cedula' and (fechanom = '$fecha') and (fechagen = '$fechaproceso') and substr(cadena,1,4)='6210'";
	// if ($cedula=='V-07436077')
		// echo $sqlin;
	$res_in=mysql_query($sqlin);
	$r_in=mysql_fetch_assoc($res_in);
	//	echo $sqlin;
	//	echo $r_in['estatus'].'<br>';
	$motivo='';
	/*
	6210J301781678V02541709       001010824575802000045090000000000146822010824501CGE0001 FONDOS INSUFICIENTES
	12345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890
	---------+---------+---------+---------+---------+---------+---------+---------+---------+---------+---------+
	*/
	if (mysql_num_rows($res_in) > 0)
		if ($r_in['estatus'] == 'AUTORIZADO')
			return '0|'.$r_in['fechagen'].'|AUTORIZADO';
		else {
			if ($mostrar == 1) {
				echo $r_in['cadena'].'<br>';
				echo $sqlin.'<br>';
			}
			$motivo=substr($r_in['cadena'],86,10);
			return '1|'.$r_in['fechagen'].'|'.$motivo;
		}
	else return '2';
}

function procesar($archivo_name,$fechaaporte,$ip,$archivosalida, $numerocuotas, $dias, $fechanom)
{
	// 123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890
	//          1         2         3         4         5         6         7         8         9        10        11        12
	// ---------+---------+---------+---------+---------+---------+---------+---------+---------+---------+---------+---------+
	// 6110J301781678CAPPOUCLA                          201106142011061701082445190100023187VEF795000000APREST201.RECCASCAPPOUCLA
	// 6210J301781678V12019714       001010824575102001406310000000000075012010824501CGE0001 FONDOS INSUFICIENTES
	//echo 'valor '.$_POST['nominasemanal'];
	// $essemanal=($_POST['nominasemanal']==1?1:0);
	$lines = file('devolucion_banco_voluntario/'.$archivo_name);
	$faltoalguno=0;
	// set_time_limit($lines);
	$contadorgeneral=0;
	$hoy = date("Y-m-d");

	extract($_POST);
	// $registros=$_POST['registros'];
	// for ($i=0;$i<$registros;$i++)	// no es necesarios revisar el check si aparece es porq estan seleccionados para hacer el asiento 
	// {
	// 	$variable='cancelar'.($i+1);
	// 	if (!empty($$variable)) 
	// 	{
	// 		$fecha=explode('-',$$variable);
	// 		$b=$fecha[0].'-'.$fecha[1].'-'.$fecha[2];
	// 	}
	// }
	foreach ($lines as $line_num => $linea) {
		$datos = explode("|", $linea);
		if (substr($datos[0],0,3)=='611') {
			$fecha=substr($datos[0],49+8,8);
			$fecha=substr($fecha,0,4).'-'.substr($fecha,4,2).'-'.substr($fecha,6,2);
			$fecha=$fechaaporte;
			$nuevafecha="select date_add('$fecha',INTERVAL ".$dias." DAY) as fecha";
			$rsqln=mysql_query($nuevafecha);
			$asqln=mysql_fetch_assoc($rsqln);
			$fecha=($asqln['fecha']);
		}

		$cadena=$datos[0];
		$nacionalidad=substr($datos[0],14,1).'-';
		$cedula=ceroizq(trim(substr($datos[0],15,8)),8);
		if (($cedula == '02') and (substr($datos[0],0,3)=='621'))
		{
			$cuenta=substr($datos[0],33,20);
			$sql_c="select ced_prof from sgcaf200 where ctan_prof='".$cuenta."'";
			// echo $sql_c.'<br>';
			$laced=mysql_query($sql_c);
			$laced=mysql_fetch_assoc($laced);
			$cedula=($laced['ced_prof']);
			$cedula=substr($cedula,2,8);
		}
		// $cedula = 'V-'.substr($cedula,0,2).'.'.substr($cedula,2,3).'.'.substr($cedula,5,3);
		$cedula = $nacionalidad.$cedula;
		$monto=substr($datos[0],53,15);
		$monto = $monto / 100;
		$estatus = substr($datos[0],78,10);
			
		$sqlresbanco="insert into sgcaresb (cadena, fechagen, cedula, estatus, ip, fechaproc, fechanom, monto, abierta) values ('$cadena', '$fecha', '$cedula', '$estatus', '$ip', now(), '$fechanom', '$monto', 1)";
		$ressql=mysql_query($sqlresbanco);
		//		echo $sqlresbanco;
	}
}

/*
select com_nrocom, com_nroite, com_fecha, com_cuenta,com_debcre, com_tipmov, com_refere, com_descri, com_monto1, com_monto2, com_monto, null as nro_registro, com_ip, cobrado, fecha_cobro  from sgcaf820 where (com_nrocom='20131011007') or (com_nrocom='20131011006') or (com_nrocom='20131011005') or (com_nrocom='20131011004') or (com_nrocom='20131011003') or (com_nrocom='20131011002') or (com_nrocom='20131011001') 
select * from sgcaf830 where (enc_clave='20131011007') or (enc_clave='20131011006') or (enc_clave='20131011005') or (enc_clave='20131011004') or (enc_clave='20131011003') or (enc_clave='20131011002') or (enc_clave='20131011001') 
INSERT INTO `sica`.`sgcaf000` (`tipo`, `nombre`, `idregistro`) VALUES ('CtaDepTransito', '1-01-01-99-01-01-0001', NULL);

select * from sgcaf310 where nropre_sdp='01741037';
SELECT * FROM `sgcaf820` WHERE com_cuenta = '1-02-01-01-02-01-1741' ORDER BY com_fecha;
select * from sgcaamor where nropre='01741037' order by fecha;
select * from sgcaf310 where nropre_sdp='01463017';
select * from sgcaamor where fecha ='2014-09-12' and ((proceso = 1) and (semanal = 1)) and (tipo ='EstatutarioA') order by codsoc;

*/

?>

<?php // include("pie.php");?>

</body></html>

