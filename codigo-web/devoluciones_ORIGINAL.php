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
// $menu61=1;
include("menusizda.php");
$ip = $_SERVER['HTTP_CLIENT_IP'];
if (!$ip) {$ip = $_SERVER['REMOTE_ADDR'];}
// echo '<h1>Desarrollando.... pronto estara lista</h1>';
if ((! $_POST['procesar']) and (! $_FILES['archivo']['name'])) {
	echo '<form action="devoluciones.php" method="post" name="form1" enctype="multipart/form-data">';
	echo '<input type="checkbox" name="nominasemanal" value = "1" checked/> Nomina Semanal<br />';
	echo '<input name="archivo" type="file" value="Examinar">';
	echo '<input type="submit" name="Submit" value="Procesar" />';
	echo '</form>';
}
else 
if (! $_POST['procesar']) {
echo '<div id="div1">';
$copiado = 'SI';		// cambiar a no y resolver este problema
if(@$_FILES['archivo']['name']!=='') // {
	$salida='devoluciones/sica_'.$_FILES['archivo']['name'];
	$archivosalida=fopen ($salida, "w+");
	$nueva_ruta='devoluciones/';
	$ruta_total = $_SERVER['DOCUMENT_ROOT'].$nueva_ruta;
	$ruta_total = $_SERVER['DOCUMENT_ROOT']."/cajaweb/devoluciones/".$_FILES['archivo']['name'];
	$BASENAMES = basename( $_FILES['archivo']['name']);
	$nuevo_nombre=$BASENAMES;
	if (is_uploaded_file($HTTP_POST_FILES['archivo']['tmp_name'])) {
//	    copy($HTTP_POST_FILES['archivo']['tmp_name'], "/devoluciones");
//		echo 'archivo '.$HTTP_POST_FILES['archivo']['tmp_name'];
//		readfile($_FILES['archivo']['tmp_name']);
//		phpinfo();
//		$destino='http://cappobck/cajaweb/devoluciones/';
//		$destino='f:/devoluciones/';
		$destino='devoluciones/';
//		$destino='/devoluciones/'.$_FILES['archivo']['name'];
		$destino.=$_FILES['archivo']['name'];
//		echo $destino;
//	    if (copy($_FILES['archivo']['tmp_name'], $destino))
		if (move_uploaded_file($_FILES['archivo']['tmp_name'],$destino));
//			echo 'lo copie';
		else die ('fallo copia');
	} else {
	   	echo "Possible file upload attack. Filename: " . $HTTP_POST_FILES['archivo']['name'];
	}
//	echo 'http '. $HTTP_POST_FILES['archivo']['tmp_name'];
//	echo $ruta_total.'<br>'; 
//	echo 'resultado '.move_uploaded_file($HTTP_POST_FILES['archivo']['tmp_name'], $ruta_total);

//	echo 'Fecha de la Nomina: '.$fechaaporte;
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
			procesar($archivo_name,$fechaaporte,$ip,$archivosalida);
	//				}
		}
		else
			{ echo "el archivo supera los 60kb"; }
		}
	else
		{ echo "el formato de archivo no es valido, solo .txt => ".$original; }
	echo '</div>';
	set_time_limit(30);
}
else {
	$salida='devoluciones/sica_'.$_POST['archivosalida'];
	echo 'la salida'.$salida;
	$archivosalida=fopen ($salida, "w+");
	generar_comprobante($ip,$archivosalida);
	fclose($archivosalida);
}

function procesar($archivo_name,$fechaaporte,$ip,$archivosalida)
{
// 123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890
//          1         2         3         4         5         6         7         8         9        10        11        12
// ---------+---------+---------+---------+---------+---------+---------+---------+---------+---------+---------+---------+
// 6110J301781678CAPPOUCLA                          201106142011061701082445190100023187VEF795000000APREST201.RECCASCAPPOUCLA
// 6210J301781678V12019714       001010824575102001406310000000000075012010824501CGE0001 FONDOS INSUFICIENTES
//echo 'valor '.$_POST['nominasemanal'];
$essemanal=($_POST['nominasemanal']==1?1:0);
//echo 'semanal '.$essemanal;
//echo 'Verificación de archivo <br>';
$lines = file('devoluciones/'.$archivo_name);
$faltoalguno=0;
set_time_limit($lines);
echo '<form action="devoluciones.php" method="post" name="form1" enctype="multipart/form-data">';
echo "<input name='archivo' type='hidden' value='$archivo_name'>";
echo "<table class='basica 100 hover' width='100%'>";
foreach ($lines as $line_num => $linea) {
	$datos = explode("|", $linea);
	if (substr($datos[0],0,3)=='611') {
		$fecha=substr($datos[0],49,8);
		$fecha=substr($fecha,0,4).'-'.substr($fecha,4,2).'-'.substr($fecha,6,2);
		echo 'Fecha de Proceso '.$fecha.'<br>';
		echo "<input name='fecha' type='hidden' value='$fecha'>";
		}

	if ((substr($datos[0],0,3)=='621') and (substr($datos[0],78,10)!='AUTORIZADO')) {
		$cedula=ceroizq(trim(substr($datos[0],15,8)),8);
		$cedula = 'V-'.$cedula;
		$monto=substr($datos[0],53,15);
		$monto = $monto / 100;
		$sql2='select cod_prof, ape_prof, nombr_prof from sgcaf200 where ced_prof="'.$cedula.'"';
		$result=mysql_query($sql2) or die ("<p />El usuario $usuario no tiene permisos para generar consulta<br>".mysql_error()."<br>".$sql);
		if (mysql_num_rows($result) < 1) {
			echo 'La cédula '.$cedula.' no esta registrada <br>';
			$faltoalguno = 1; }
		else {
			$socio=mysql_fetch_assoc($result);
			$codigo=$socio['cod_prof'];
			$sql="select * from sgcaf310, sgcaf360 where (codsoc_sdp='$codigo' and ! renovado) and (codpre_sdp=cod_pres and dcto_sem='$essemanal')  order by f_1cuo_sdp desc";
			$resul2=mysql_query($sql);
			$filas=mysql_num_rows($resul2)+1;
			echo "<tr><td rowspan='$filas' class='azul'>";
			echo "<input name='cedulas[]' type='hidden' value='$cedula'>";
			echo "<input name='codigos[]' type='hidden' value='$codigo'>";
			echo "<strong>$codigo<br>$cedula</strong>";
			echo '<br>'.$socio['ape_prof'].' '.$socio['nombr_prof']."<br><strong>$monto</strong>".'</td></tr>';
			$ti=0;
			while ($row = mysql_fetch_assoc($resul2)) 
				$ti+=$row['cuota_ucla'];
			mysql_data_seek($resul2,0);
			while ($row = mysql_fetch_assoc($resul2)) {
				echo '<tr><td>';
				echo $row['nropre_sdp'].'</td><td>'.$row['descr_pres'].'</td><td align="right">'.$row['cuota_ucla'].'</td>';
				echo '<td>';
//			echo $ti. ' '.$monto.'- '.($ti == $monto).'<br>';
				echo '<input type="checkbox" name="';
				echo $codigo;
				echo '[]" value="'.$row["nropre_sdp"].'|'.$cedula.'' .'"';
				if ($ti == $monto) {
//					echo 'entre ';
					echo ' checked '; }
					else ' ';
				echo '> </td>';
				echo '</tr>';
			}
		}
	}

}
echo '<tr><td align="center" colspan="5">';
echo '<input type="hidden" name="archivosalida" value="'.$archivo_name.'" />';
echo '<input type="submit" name="procesar" value="Procesar" />';
echo '</td></tr></table>';
echo '</form>';
}

function generar_comprobante($ip,$archivosalida)
{
	$lascedulas=$_POST['cedulas'];
	$loscodigos=$_POST['codigos'];
	$total=0;
	$fecha=$_POST['fecha'];
	$b=$fecha;
	$elasiento=substr($fecha,0,4).substr($fecha,5,2).substr($fecha,8,2).'100';
	echo "Generando encabezado contable <strong><a target=\"_blank\" href='editasi2.php?asiento=$elasiento'>$elasiento </a></strong> <br>";
	$sql = "INSERT INTO sgcaf830 (enc_clave, enc_fecha, enc_desco, enc_desc1, enc_debe, enc_haber, enc_item, enc_dif, enc_igual, enc_refer, enc_sw, enc_explic) VALUES ('$elasiento', '$b', 'RETENCIONES DEVUELTAS DE FECHA $fecha','',0,0,0,0,0,0,0,'')"; 
	escribir_archivo($archivosalida,'+830'.$elasiento.$b.'RETENCIONES DEVUELTAS DE FECHA '.$fecha);
	$result=mysql_query($sql);
	for ($registro=0; $registro < count($_POST['cedulas']); $registro++) {
		echo $registro. ' ' .$lascedulas[$registro].' '.$loscodigos[$registro].' = ';
		$acodigo=$loscodigos[$registro];
		$acodigo=$_POST[$acodigo];
//		echo $acodigo[1];
		for ($prestamos=0; $prestamos< count($acodigo); $prestamos++) {
			ajustar_prestamo($acodigo[$prestamos], $lascedulas[$registro], $loscodigos[$registro], $total, $ip, $fecha, $elasiento,$archivosalida);
		}
//		echo '<br>';
	}
//	$cuenta1='1-01-01-02-03-01-0002';
	$cuenta1='1-01-02-01-15-01-0001';
	$debe=$total;
	agregar_f820($elasiento, $b, '-', $cuenta1, 'RET.DEV.'.$fecha , $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
	escribir_archivo($archivosalida,'+820'.$elasiento.$b.$cuenta1.'-'.str_pad($debe,10,' ',STR_PAD_LEFT).'RET.DEV.'.$fecha);
}

function escribir_archivo($archivosalida,$cuento)
{
//	$cuento='"'.$cuento.'"';
//	echo 'el archvo salida'.$archivosalida.'<br>';
	fwrite($archivosalida,$cuento."\n");
}

function ajustar_prestamo($nroprestamo, $nrocedula, $codigo, &$total, $ip, $b, $elasiento,$archivosalida)
{
	$sql1="select * from sgcaf200 where cod_prof ='$codigo'";
	$resul1=mysql_query($sql1);
	$fila1=mysql_fetch_assoc($resul1);
	$nroprestamo=explode('|',$nroprestamo);
	$nroprestamo=$nroprestamo[0];
	$sql2="select * from sgcaf310, sgcaf360 where (codsoc_sdp ='$codigo' and nropre_sdp='$nroprestamo' and ! renovado) and (codpre_sdp=cod_pres)";
//	echo $sql1.'<br>'.$sql2.'<br>';
	$resul2=mysql_query($sql2);
	$fila2=mysql_fetch_assoc($resul2);
	$referencia='';
	$cargo=trim($fila2['cuent_pres']).'-'.substr($codigo,1,4);
	$debe=$fila2['cuota_ucla'];

	$tipo=$fila2['codpre_sdp'];
	escribir_archivo($archivosalida,'=310'.$codigo.$nroprestamo.$tipo.str_pad($debe,10,' ',STR_PAD_LEFT));
	$sql="update sgcaf310 set monpag_sdp=monpag_sdp - $debe, ultcan_sdp=ultcan_sdp - 1  where (codsoc_sdp ='$codigo' and nropre_sdp='$nroprestamo' and ! renovado)";
	if (mysql_query($sql)) ;
	else die($sql);
	
	$cuenta1=$cargo;
	agregar_f820($elasiento, $b, '+', $cuenta1, 'RET.DEV.'.trim($fila1['ape_prof']).' ' .$fila1['nombr_prof'] , $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
	$total+=$debe;
	escribir_archivo($archivosalida,'+820'.$elasiento.$b.$cuenta1.'+'.str_pad($debe,10,' ',STR_PAD_LEFT).'RET.DEV.'.trim($fila1['ape_prof']).' ' .$fila1['nombr_prof']);
}

?>

<?php include("pie.php");?>

</body></html>

