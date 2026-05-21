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
	foreach($_FILES["archivo"]['tmp_name'] as $key => $tmp_name)
	{
		//condicional si el fuchero existe
		if($_FILES["archivo"]["name"][$key]) {
			// Nombres de archivos de temporales
			$archivonombre = $_FILES["archivo"]["name"][$key]; 
			$fuente = $_FILES["archivo"]["tmp_name"][$key]; 
			
			$carpeta = 'devoluciones_prueba/'; //Declaramos el nombre de la carpeta que guardara los archivos
			
			if(!file_exists($carpeta)){
				mkdir($carpeta, 0777) or die("Hubo un error al crear el directorio de almacenamiento");	
			}
			
			$dir=opendir($carpeta);
			$target_path = $carpeta.'/'.$archivonombre; //indicamos la ruta de destino de los archivos
			
	
			if(move_uploaded_file($fuente, $target_path)) {	
				// echo $fuente;
				echo "Archivo $archivonombre se han cargado de forma correcta.<br>";
				procesar_por_cobrar($target_path);
				} else {	
				echo "Se ha producido un error, por favor revise los archivos e intentelo de nuevo.<br>";
			}
			closedir($dir); //Cerramos la conexion con la carpeta destino
		}
	}
}


function procesar_por_cobrar($archivo)
{
	$lines = file($archivo);
	$faltoalguno=0;
	$arreglo = array(); 
	// set_time_limit($lines);
	// $contadorgeneral=0;
	// $hoy = date("Y-m-d");

	echo '<table class="responsve">';
	$emonto = 0;
	foreach ($lines as $line_num => $linea) {
		// $datos = explode("|", $linea);
		$datos = $linea;
		if (substr($linea,0,3) == '611')
		{
			procesar_encabezado($linea);
		}
		if (substr($linea,0,3) == '621')
		{
			procesar_detalle($linea, $arreglo);
		}
		if (substr($linea,0,3) == '691')
		{
			$lineafinal = $linea;
		}
/*
		else
		{
			$cuenta = substr($datos,2,20);
			$cedula = substr($datos,23,8);
			$nacionalidad = substr($datos,22,1).'-';
			$cedula = $nacionalidad.$cedula; // substr($cedula,0,2).'.'.substr($cedula,2,3).'.'.substr($cedula,5,3);
			$monto = substr($datos,39,17);
			$monto = $monto / 100;
		}
*/

	}	
	// var_dump($arreglo);
	echo '<tr><td colspan="5">Resumen</td></tr>';
	echo '<tr><td>Descripción</td><td>Cantidad</td><td>Monto</td></tr>';
	foreach ($arreglo as $key => $value) {
		echo '<tr><td>'.$value['resultado'] . '</td><td>'.$value['cantidad'] . ' </td><td>'.($value['sumatoria']/100).'</td></tr>';
	}
	procesar_final($lineafinal);
	echo '</table>';
}

function procesar_encabezado($linea)
{
	$enviado = substr($linea,98,7);
	$fecha_subida = substr($linea,49,8);
	$fecha_proceso = substr($linea,57,8);
	echo '<tr><td>Archivo enviado </td><td>'.  $enviado.'</td></tr>';
	echo '<tr><td>Fecha de Envio </td><td>'.  $fecha_subida.'</td></tr>';
	echo '<tr><td>Fecha de Proceso </td><td>'.  $fecha_proceso.'</td></tr>';
}

function procesar_detalle($linea, &$arreglo)
{
// 6210J301781678V01277938       001010824575202000031540000000000018700000000100AUTORIZADO                                                
	$resultado = substr($linea,78,28);
	$monto = substr($linea,54,14);
	$monto = ($monto / 100) * 100;
	// echo '<br>'.$monto. ' '. $resultado;
	$encontre = false;
	for ($i=0; $i < count($arreglo) ; $i++) { 
		if ($arreglo[$i]['resultado'] == $resultado)
		{
			$arreglo[$i]['sumatoria'] += $monto;
			$arreglo[$i]['cantidad'] += 1;
			$encontre = true;
		}
	}
	if (!$encontre)
	{
		// echo ' el monto entre 100 '.$monto/100;
		array_push($arreglo, array('resultado'=> $resultado, 'sumatoria'=>$monto, 'cantidad'=>1));
	}
	// foreach ($arreglo as $key => $value) {
	// 	if ($value->resultado )
	// }

	/*
	echo '<br>Archivo enviado '.  $enviado;
	echo '<br>Fecha de Envio '.  $fecha_subida;
	echo '<br>Fecha de Proceso '.  $fecha_proceso;
	*/
}



function procesar_final($linea)
{
	$registros = substr($linea,39,8);
	$procesados = substr($linea,29,8);
	$monto_procesado = substr($linea,14,15);
	echo '<tr><td>Registros Procesados ??????</td><td>'.  $registros.'</td></tr>';
	echo '<tr><td>Registros Procesados </td><td>'.  (($procesados/100)*100).'</td></tr>';
	echo '<tr><td>Monto de Proceso </td><td>'.  ($monto_procesado/100).'</td></tr>';
}


function pantalla_ingreso()
{
?>
	<div id='div1'>
		<form action='rndb.php?accion=ProcesarArchivos' name='form1' method='post' enctype='multipart/form-data' onsubmit='return realizar_revision(form1)'>
			<fieldset>
				<legend>Información de archivos recibidos del BANCO </legend>
				<table>
					<tr>
						<td>Indique al menos un Archivo</td><td>
							<input class="form-control" name="archivo[]" id="archivo[] "type="file" multiple="">
						</td>
					</tr>
					<tr>
						<td>
							<input type="submit" name="Submit" value="Realizar Revision">
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

