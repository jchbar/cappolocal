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
	$sql = "delete from sgcaretuclai WHERE fecha = '".$fecha_nomina."'";
	$rsql=mysql_query($sql);

	$sql = "delete from sgcaf820 WHERE com_nrocom = '20230323001'";
	$rsql=mysql_query($sql);
	$sql = "delete from sgcaf830 WHERE enc_clave = '20230323001'";
	$rsql=mysql_query($sql);

	// $rsql=mysql_fetch_assoc($rsql);

	colocar_inactivos($fecha_nomina);

	$fechaarchivo=explode('-',$fecha_nomina);
	$fechaarchivo=$fechaarchivo[0].$fechaarchivo[1].$fechaarchivo[2];
	$nombre_archivo = 'nompre/'.$fechaarchivo.'domiciliacion.txt';
	$contenido = $nombre;
	$gestor = fopen($nombre_archivo, 'w');

	procesar_txt($_FILES['asap'],'AP',1, $fecha_nomina, $gestor);
	procesar_txt($_FILES['asjp'],'JP',2, $fecha_nomina, $gestor);
	procesar_txt($_FILES['asao'],'AO',3, $fecha_nomina, $gestor);
	procesar_txt($_FILES['asjo'],'JO',4, $fecha_nomina, $gestor);


	fclose($gestor);

	echo '<table>';
		echo '<tr>';
			echo '<td>';
				echo "<h2><a target=\"_blank\" href=\"retencionimp2.php?fechaaporte=$fecha_nomina\" onClick=\"info.html\', \'\',\'width=250, height=190\')\">Reporte Retenciones</a><br></h2>"; 
			echo '</td>';
			echo '<td>';
				echo "<h2><a target=\"_blank\" href=\"retencionimpc.php?fechaaporte=$fecha_nomina\" onClick=\"info.html\', \'\',\'width=250, height=190\')\">Gastos Administrativos</a><br></h2>"; 
			echo '</td>';
			echo '<td>';
				echo '<form action="depositotxt.php" method="post" name="form1" enctype="multipart/form-data">';
				echo '<input type="hidden" name="archivo" value = "'.$nombre_archivo.'">';
				echo '<input type="submit" name="procesar" value="Descargar Archivo Gastos Administrativos'.$nombre_archivo.'" />';
				echo '</form>';

				// echo "<h2><a target=\"_blank\" href=\"retencionimpc.php?fechaaporte=$fecha_nomina\" onClick=\"info.html\', \'\',\'width=250, height=190\')\">Archivo Gastos </a><br></h2>"; 
			echo '</td>';
			echo '<td>';
				echo "<h2><a target=\"_blank\" href=\"retencionimppub.php?fechaaporte=$fecha_nomina\" onClick=\"info.html\', \'\',\'width=250, height=190\')\">Reporte Publicación</a><br></h2>"; 
			echo '</td>';
		echo '</tr>';
	echo '</table>';

	// listado_cambio_status($fecha_nomina);
}

function colocar_inactivos($fecha_nomina)
{
	echo 'Inactivando Socios<br>';
	$sql = "DELETE FROM sgcaf201 WHERE fecha = '$fecha_nomina'";
	$rsql=mysql_query($sql);
	$sql = "select cod_prof, ced_prof, upper(statu_prof) as statu_prof from sgcaf200 where (tipo_socio = 'P') and ((upper(statu_prof)='ACTIVO') or (upper(statu_prof)='JUBILA') or (upper(statu_prof)='SUSPEN')) order by cod_prof";
	$rsql=mysql_query($sql);
	while($r=mysql_fetch_assoc($rsql)) {
		$codigo = $r['cod_prof'];
		$cedula = $r['ced_prof'];
		$actual = $r['statu_prof'];
		$sql1="insert into sgcaf201 (codigo, cedula, st_actual, st_nuevo, fecha, procesado) values ('$codigo', '$cedula', '$actual', '', '$fecha_nomina', now())";
		$rsql1=mysql_query($sql1) or die ("<p />Estimado usuario $usuario contacte al administrador Código INac-1- <br>".mysql_error()."<br>".$sql1);
		$sql1 = "update sgcaf200 set statu_prof = 'RETIRA'  WHERE cod_prof ='$codigo'";
		$rsql1=mysql_query($sql1) or die ("<p />Estimado usuario $usuario contacte al administrador Código INac-2- <br>".mysql_error()."<br>".$sql1);
	}
}


function listado_cambio_status($fecha_nomina)
{
	$sql = "SELECT codigo, cedula, concat(ape_prof,' ',nombr_prof) as nombre, st_actual, st_nuevo from sgcaf201, sgcaf200 where codigo = cod_prof and sgcaf201.fecha = '$fecha_nomina' and st_actual <> st_nuevo";
	$rsql=mysql_query($sql);
	while($r=mysql_fetch_assoc($rsql)) {
		echo $r['codigo']. $r['cedula']. $r['nombre']. $r['st_actual']. $r['st_nuevo'].'<br>'; 
	}
}


function procesar_txt($archivo,$tipo, $proceso, $fecha_nomina, $gestor)
{
	// die('archivo'.$_FILES['asap']);
	// extract($_POST);
	// var_dump($archivo);
	// echo 'name '.$archivo['name'];
	$copiado = 'SI';		// cambiar a no y resolver este problema

	// $salida='retenciones_ucla/'.$_FILES['archivo'];
	// $archivosalida=fopen ($salida, "w+");
	// $nueva_ruta='devoluciones/';
	// $ruta_total = $_SERVER['DOCUMENT_ROOT'].$nueva_ruta;
	$ruta_total = $_SERVER['DOCUMENT_ROOT']."/cajaweb/retenciones_ucla/".$archivo['name'];
	// echo $ruta_total;
	$BASENAMES = basename( $archivo['name']);
	$nuevo_nombre=$BASENAMES;
//		echo 'el archivo '.$elarchivo. ' / '.'$$elarchivo' .'<<<<<';
	// if (is_uploaded_file($HTTP_POST_FILES['archivo']['tmp_name'])) {
	if (is_uploaded_file($archivo['tmp_name'])) {
		$destino='retenciones_ucla/';
		$destino.=$archivo['name'];
		// echo 'destino '.$destino.'<br>';
		// $destino = $ruta_total;
		if (move_uploaded_file($archivo['tmp_name'],$destino))
			procesar_por_cobrar($destino,$tipo, $proceso, $fecha_nomina, $gestor);
		else die ('fallo copia');
	} 
	else {
	   	// die ("Possible file upload attack. Filename: " . $HTTP_POST_FILES['archivo']['name']);
	   	die ("Possible file upload attack. Filename: " . $archivo['name']);
	}
		// $archivo_name = $nuevo_nombre; 
		// $original = $archivo_name;
		// $extension = explode(".",$archivo_name);
		// $num = count($extension)-1;
		// if (1 == 1) { // (strtoupper($extension[$num]) == "TXT") {
		// 	if($copiado = 'SI') { // $archivo_size < 60000) {
		// 		// separar el archivo con los datos
		// 		echo 'copiado';
		// 		// procesar($archivo_name,$fechaaporte,$ip,$archivosalida,$numerocuotas,$veces);
		// 	}
		// else
		// 	{ echo "el archivo supera los 60kb"; }
		// }
	// else
	// 	{ echo "el formato de archivo no es valido, solo .txt => ".$original; }
	set_time_limit(30);
}


function procesar_por_cobrar($archivo, $tipo, $proceso, $fecha_nomina, $gestor)
{
	$lines = file($archivo);
	$faltoalguno=0;
	set_time_limit($lines);
	$contadorgeneral=0;
	// $hoy = date("Y-m-d");

	$sqlano="select substr(now(),1,10) as hoy, DATE_FORMAT('".$fecha_nomina."','%d/%m/%Y') as fn";
	$sqlfano=mysql_query($sqlano);
	$sqlrano=mysql_fetch_assoc($sqlfano);

	$b = $sqlrano['hoy'];
	$fn = $sqlrano['fn'];
	$hoy = explode('-',$b);
	$b=$hoy[0].'-'.$hoy[1].'-'.$hoy[2];

	$comisiongastos = $_POST['comisiongastos'];

	$emonto = $ecomision = 0;
	foreach ($lines as $line_num => $linea) {
		// $datos = explode("|", $linea);
		$datos = $linea;

		$cuenta = substr($datos,2,20);
		$cedula = substr($datos,23,8);
		$nacionalidad = substr($datos,22,1).'-';
		$cedula = $nacionalidad.$cedula; // substr($cedula,0,2).'.'.substr($cedula,2,3).'.'.substr($cedula,5,3);
		$monto = substr($datos,39,17);
		$monto = $monto / 100;
		$ecomision = $ecomision + $comisiongastos;

		$sql = "SELECT * FROM sgcaf200 WHERE ced_prof = '".$cedula."' LIMIT 1";
		$rsql=mysql_query($sql);
		$rsql=mysql_fetch_assoc($rsql);
		$r200=$rsql;
		$codigo=($rsql['cod_prof']);

		$sql2="insert into t_his200 (cod_prof, hab_prof, hab_ucla, fecha, ip, proceso, cedula, pertenece, comision) values ('".$codigo."', '".$monto."', '0', '".$fecha_nomina."', '".$ip."', now(), '".$cedula."', '".$tipo."', ".$comisiongastos.")";
		$emonto+=$monto;
		$rs2=(mysql_query($sql2)) or die ("<p />Estimado usuario $usuario contacte al administrador Código Nomi-1- <br>".mysql_error()."<br>".$sql2);

		$sql2="insert into sgcaretuclai (codigo, cedula, monto, fecha, procesado) values ('$codigo', '$cedula', '$monto', '".$fecha_nomina."', now())";
		$rs2=(mysql_query($sql2)) or die ("<p />Estimado usuario $usuario contacte al administrador Código Indi-1- <br>".mysql_error()."<br>".$sql2);

		$nuevo_status = (substr($tipo,0,1)=="A"?'ACTIVO':'JUBILA');
		$sql = "UPDATE sgcaf200 SET statu_prof = '$nuevo_status', ctan_prof='$cuenta' WHERE ced_prof = '$cedula'";
		$rsql=mysql_query($sql)  or die ("<p />Estimado usuario $usuario contacte al administrador Código Act-1- <br>".mysql_error()."<br>".$sql);
		$sql = "UPDATE sgcaf201 SET st_nuevo = '$nuevo_status' WHERE codigo = '$codigo' AND fecha = '$fecha_nomina'";
		// echo $sql.'<br>';
		$rsql=mysql_query($sql)  or die ("<p />Estimado usuario $usuario contacte al administrador Código Act-2- <br>".mysql_error()."<br>".$sql);
		// UPDATE  `sgcaf200` SET STATU_PROF='ACTIVO' WHERE STATU_PROF='RETIRA'

		listadotxt($r200,$comisiongastos,$gestor);

	}	
	echo 'Información Agregada...<br />' ;
	$lafecha=explode("-",$b);
	if ($proceso == 1)
		$asiento=$lafecha[0].$lafecha[1].$lafecha[2].'00'.$proceso;
	else 
		$asiento=$lafecha[0].$lafecha[1].$lafecha[2].'001';
	// $b=explode("/",$fecha_nomina); // $rs[fecha]; 12/1/2011
	// $b=$b[2].'-'.$b[1].'-'.$b[0];
	$explicacion='';

	echo "Realizando Registros contables del asiento <strong><a target=\"_blank\" href='editasi2.php?asiento=$asiento'>$asiento </a></strong><br>";

	$desc='Nomina x Cobrar Retencion del '.$fn;
	$explicacion=$desc;
	$sql = "INSERT INTO sgcaf830 (enc_clave, enc_fecha, enc_desco, enc_desc1, enc_debe, enc_haber, enc_item, enc_dif, enc_igual, enc_refer, enc_sw, enc_explic) VALUES ('$asiento', '$b', '$desc','',0,0,0,0,0,0,0,\"$explicacion\")"; 
	if (!mysql_query($sql)) die ("El usuario $usuario no tiene permiso para añadir Asientos.<br>".mysql_error()."<br>".$sql);

	$referencia = $tipo;
	
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
?>
	<div id='div1'>
		<form action='cnru.php?accion=ProcesarArchivos' name='form1' method='post' enctype='multipart/form-data' onsubmit='return realizar_abono(form1)'>
			<fieldset>
				<legend>Información Para Retenciones UCLA por enviar al BANCO </legend>
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
							Monto Bs. Gastos Administrativos (Retención)
						</td>
						<td> 
							<input type="text" name="comisiongastos" id="comisiongastos" value="1.50">
						</td>
					<td>


					<tr>
						<td>Archivo Socios Activos Provincial</td><td><input name="asap" type="file" value="Examinar"></td>
					</tr>
					<tr>
						<td>Archivo Socios Jubilados Provincial </td><td><input name="asjp" type="file" value="Examinar"></td>
					</tr>
					<tr>
						<td>Archivo Socios Activos Otros Bancos </td><td><input name="asao" type="file" value="Examinar"></td>
					</tr>
					<tr>
						<td>Archivo Socios Jubilados Otros Bancos </td><td><input name="asjo" type="file" value="Examinar"></td>
					</tr>
					<tr>
						<td>
							<input type="submit" name="Submit" value="Cargar Archivos y Realizar Asientos Contables">
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

