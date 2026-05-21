<?php
// ALTER TABLE `t_his200` ADD `comision` DECIMAL( 18, 3 ) NOT NULL DEFAULT '0';
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
<script src="ajaxabo.js" type="text/javascript"></script>
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

$readonly=" readonly='readonly'";
include("arriba.php");
$menu61=1;include("menusizda.php");
$ip = $_SERVER['HTTP_CLIENT_IP'];
if (!$ip) {$ip = $_SERVER['REMOTE_ADDR'];}
if (!$accion) {
	pantalla_ingreso();
?>
<?php 
}	// if (!$accion) 


if ($accion=='ProcesarArchivos') {
	extract($_POST);
	// phpinfo();
	// $fecha_nomina='2023-02-15';
	$fecha_nomina = $_POST['fechaaporte'];
	if (empty($fecha_nomina)) //  == '00')
		die('<h1>No se definio fecha de Nomina');


	$sql = "delete from t_his200 WHERE fecha = '".$fecha_nomina."'";
	$rsql=mysql_query($sql);

	/*
	$sql = "delete from sgcaf820 WHERE com_nrocom = '2024030400'";
	$rsql=mysql_query($sql);
	$sql = "delete from sgcaf830 WHERE enc_clave = '2024030400'";
	$rsql=mysql_query($sql);
	*/

	$fechaarchivo=explode('-',$fecha_nomina);
	$fechaarchivo=$fechaarchivo[0].$fechaarchivo[1].$fechaarchivo[2];
	$nombre_archivo = 'ayuda_solidaria/'.$fechaarchivo.'domiciliacion.txt';
	$contenido = $nombre;
	$gestor = fopen($nombre_archivo, 'w');

	procesar_txt($fecha_nomina, $gestor);

	fclose($gestor);

	echo '<table>';
		echo '<tr>';
			echo '<td>';
				echo "<h2><a target=\"_blank\" href=\"ayuda_solidaria.php?fechaaporte=$fecha_nomina\" onClick=\"info.html\', \'\',\'width=250, height=190\')\">Reporte Retenciones</a><br></h2>"; 
			echo '</td>';
			echo '<td>';
				echo '<form action="depositotxt.php" method="post" name="form1" enctype="multipart/form-data">';
				echo '<input type="hidden" name="archivo" value = "'.$nombre_archivo.'">';
				echo '<input type="submit" name="procesar" value="Descargar Archivo Ayuda Solidaria'.$nombre_archivo.'" />';
				echo '</form>';
			echo '</td>';
		echo '</tr>';
	echo '</table>';
}


function procesar_txt($fecha_nomina, $gestor)
{
	procesar_por_cobrar($fecha_nomina, $gestor);
	set_time_limit(30);
}


function procesar_por_cobrar($fecha_nomina, $gestor)
{
	$sqlano="select substr(now(),1,10) as hoy, DATE_FORMAT('".$fecha_nomina."','%d/%m/%Y') as fn";
	$sqlfano=mysql_query($sqlano);
	$sqlrano=mysql_fetch_assoc($sqlfano);

	$b = $sqlrano['hoy'];
	$fn = $sqlrano['fn'];
	$hoy = explode('-',$b);
	$b=$hoy[0].'-'.$hoy[1].'-'.$hoy[2];

	$sql = "select * from sgcaf100 limit 1";
	$resultado=mysql_query($sql);
	$rv = mysql_fetch_assoc($resultado);
	$monto = $rv['AyudaSolidaria'];
	$comisiongastos = round($monto * (5/100),1);
	$emonto = $ecomision = 0;

	$sql = "select * from sgcaf200 where AyudaSolidaria = 'Si' and (tipo_socio = 'P') and 
	((upper(statu_prof)='ACTIVO') or (upper(statu_prof)='JUBILA'))";
	$resultado=mysql_query($sql);
	while ($rsql = mysql_fetch_assoc($resultado)) {
		$codigo=($rsql['cod_prof']);
		$cedula=($rsql['ced_prof']);

		$sql2="insert into t_his200 (cod_prof, hab_prof, hab_ucla, hab_voluntario, fecha, ip, proceso, cedula, pertenece, comision) values ('".$codigo."', 0, 0, ".$monto.", '".$fecha_nomina."', '".$ip."', now(), '".$cedula."', 'AS', ".$comisiongastos.")";
		$emonto+=$monto;
		$ecomision+=$comisiongastos;
		// $rs2=(mysql_query($sql2)) or die ("<p />Estimado usuario $usuario contacte al administrador Código Vol-1- <br>".mysql_error()."<br>".$sql2);

		listadotxt($rsql,$rv['AyudaSolidaria'],$gestor);

	}	
	echo 'Información Agregada...<br />' ;
	$lafecha=explode("-",$b);
	$proceso='5';
	$asiento=$lafecha[0].$lafecha[1].$lafecha[2].'00'.$proceso;
	$explicacion='';

	echo "Realizando Registros contables del asiento <strong><a target=\"_blank\" href='editasi2.php?asiento=$asiento'>$asiento </a></strong><br>";

	$desc='Nomina Ayuda Solidaria del '.$fn;
	$explicacion=$desc;
	$sql = "INSERT INTO sgcaf830 (enc_clave, enc_fecha, enc_desco, enc_desc1, enc_debe, enc_haber, enc_item, enc_dif, enc_igual, enc_refer, enc_sw, enc_explic) VALUES ('$asiento', '$b', '$desc','',0,0,0,0,0,0,0,\"$explicacion\")"; 
	if (!mysql_query($sql)) die ("El usuario $usuario no tiene permiso para añadir Asientos.<br>".mysql_error()."<br>".$sql);

	$referencia = 'AS';
	
	hacer_asiento('RetxDisBco', $emonto, '-', $desc, $asiento, $b, $referencia);
	hacer_asiento('RetxCobBco', $emonto, '+', $desc, $asiento, $b, $referencia);

	$desc='Gastos Adm. Nomina x Cobrar Retencion del '.$fn;
	hacer_asiento('RetxDisBco', $ecomision, '-', $desc, $asiento, $b, $referencia);
	hacer_asiento('RetxCobBco', $ecomision, '+', $desc, $asiento, $b, $referencia);
	echo "Generado Asiento contable $asiento<br>";
}

function listadotxt($r200,$totalxsocio,$gestor)
{
	//0201082457570200015888V07333526        00000000000008937ABARCA DE G.TERESA G.                                                 00CAPPOUCL              *
	//0201082457510200129328V16770549        00000000000000010Xx  CARRASCO R. TONDIS MIGUEL                                         00CAPPOUCL              *
	if (substr($r200['ctan_prof'],0,4) == '0108')
	{
		$cadena='02'.$r200['ctan_prof'];
		$cadena.=substr($r200['ced_prof'],0,1).substr($r200['ced_prof'],2,8).replicate(' ',8);
		$monto=trim($totalxsocio*100);
		// quito el punto
		$sinpunto='';
		for ($i=0;$i<strlen($monto);$i++)
			if (substr($monto,$i,1)!= '.')
				$sinpunto.=substr($monto,$i,1);
		$monto=ceroizq($sinpunto,17);
		$cadena.=$monto;
		$nombre=trim($r200['ape_prof']).' ' .trim($r200['nombr_prof']);
		$nombre=substr(trim($nombre),0,40);
		$rellenar=replicate(' ',40-strlen($nombre));
		$cadena.=$nombre.$rellenar;
		$cadena.=replicate(' ',30).'00'.'CAPPOUCL'.replicate(' ',14).'*'.chr(13).chr(10);
		if (fwrite($gestor, $cadena) === FALSE) {
			echo "No se puede escribir al archivo ($nombre_archivo)";
			exit;
		}
	}

}

function replicate($caracterarepetir,$cantidaddeveces)
{
	$resultado='';
	for ($i=0;$i<$cantidaddeveces;$i++)
		$resultado.=$caracterarepetir;
	return $resultado;
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



function pantalla_ingreso()
{
	$sql = "select * from sgcaf100 limit 1";
	$rsql=mysql_query($sql);
	$r=mysql_fetch_assoc($rsql);
?>

	<div id='div1'>
		<form action='gnas.php?accion=ProcesarArchivos' name='form1' method='post' enctype='multipart/form-data' onsubmit='return realizar_abono(form1)'>
			<fieldset>
				<legend>Información Para Ahorro Voluntario para enviar al BANCO </legend>
				<table>
					<tr>
						<td><input type="hidden" name="aportespagos" value = "on"/>
						Fecha de la Retención 
						<input type="hidden" name="fechaaporte" id="fechaaporte" value="">
					</td>
					<td>
						<span style="background-color: #ff8; cursor: default;"
						onmouseover="this.style.backgroundColor='#ff0';"
						onmouseout="this.style.backgroundColor='#ff8';"
						id="show_d3" 
   						><?php  echo '00/00/0000'; ?></span>
						<script type="text/javascript">
						    Calendar.setup({
						        inputField     :    "fechaaporte",     // id of the input field
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
					<tr>
						<td>
							Monto Bs. Ayuda Solidaria
						</td>
						<td> 
							<?php echo number_format($r['AyudaSolidaria'],$_SESSION['deci'],'.',',') ?>
						</td>
					<td>

					<tr>
						<td>
							<input type="submit" name="Submit" value="Generar Archivo y Realizar Asientos Contables">
						</td>
					</tr>
				</table>
			</fieldset>
		</form>
	</div>
<?php
}

?>

</body></html>

