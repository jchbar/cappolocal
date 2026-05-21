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
<script src="ajaxdev.js" type="text/javascript"></script>
<script language="Javascript" src="selec_fecha.js" type='text/javascript'></script>

<?php

$numerocuotas=1;
$readonly=" readonly='readonly'";
include("arriba.php");
// $menu61=1;
include("menusizda.php");
$ip = $_SERVER['HTTP_CLIENT_IP'];
if (!$ip) {$ip = $_SERVER['REMOTE_ADDR'];}
// echo '<h1>Desarrollando.... pronto estara lista</h1>';
if ((! $_POST['procesar']) and (! $_FILES['archivo']['name'])) {
	echo '<fieldset><legend>Informacion para procesar archivo para Ayuda Solidaria</legend>';
	echo '<form action="ayudasoli.php" method="post" name="form1" enctype="multipart/form-data">';
	echo 'Fecha de Descuento Prestamo Ayuda Solidaria ';
?>
<script type="text/javascript">
// setActiveStyleSheet(this, 'green');
setActiveStyleSheet(document.getElementById("defaultTheme"), "green");
</script>
	Desde:</b> <input type="text" name="date3" id="sel3" size="12" readonly
><input type="reset" value=" ... "
onclick="return showCalendar('sel3', '%d/%m/%Y');"><br />
<?php
//	echo '<input type="checkbox" name="nominasemanal" value = "1" checked/> Nomina Semanal<br />';
	echo 'Indique el archivo (CSV) que contiene la informacion de Ayuda Solidaria<input name="archivo" type="file" value="Examinar"><br>';
	echo 'Codigo del Socio quien recibe Ayuda Solidaria<input name="quien" type="text" maxlength="5" value="00000"><br>';
	echo '<input type="submit" name="Submit" value="Procesar" />';
	echo '</fieldset>';
	echo '</form>';
}
else 
if (! $_POST['procesar']) {
echo '<div id="div1">';
$copiado = 'SI';		// cambiar a no y resolver este problema
if(@$_FILES['archivo']['name']!=='') // {
	$salida='farmacia/sica_'.$_FILES['archivo']['name'];
	$archivosalida=fopen ($salida, "w+");
	$nueva_ruta='farmacia/';
	$ruta_total = $_SERVER['DOCUMENT_ROOT'].$nueva_ruta;
	$ruta_total = $_SERVER['DOCUMENT_ROOT']."/cajalocal/ayudasol/".$_FILES['archivo']['name'];
//	echo $ruta_total;
	
	$BASENAMES = basename( $_FILES['archivo']['name']);
	$nuevo_nombre=$BASENAMES;
	if (is_uploaded_file($HTTP_POST_FILES['archivo']['tmp_name'])) {
//	    copy($HTTP_POST_FILES['archivo']['tmp_name'], "/devoluciones");
//		echo 'archivo '.$HTTP_POST_FILES['archivo']['tmp_name'];
//		readfile($_FILES['archivo']['tmp_name']);
//		phpinfo();
//		$destino='http://cappobck/cajalocal/devoluciones/';
//		$destino='f:/devoluciones/';
		$destino='ayudasol/';
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
			procesar($archivo_name,$fechaaporte,$ip,$archivosalida,$numerocuotas);
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
/*
	$salida='farmacia/sica_'.$_POST['archivosalida'];
	$eltxt='sica_'.$_POST['archivosalida'];
	$archivosalida=fopen ($salida, "w+");
	generar_comprobante($ip,$archivosalida,$numerocuotas);
	fclose($archivosalida);
	echo '<h2><br>Proceso Finalizado</h2>';
	echo '<form action="devoluciontxt.php" method="post" name="form1" enctype="multipart/form-data">';
	echo '<input type="hidden" name="archivo" value = "'.$eltxt.'">';
	echo '<input type="submit" name="procesar" value="Descargar Archivo '.$eltxt.'" />';
	echo '</form>';
*/	
}

function procesar($archivo_name,$fechaaporte,$ip,$archivosalida, $numerocuotas)
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
$lines = file('ayudasol/'.$archivo_name);
$faltoalguno=0;
set_time_limit($lines);
$fechaoriginal=$_POST['date3'];
$b=$fechaoriginal;
$b=explode('/',$b);
$b=$b[2].'-'.$b[1].'-'.$b[0];
echo '<form action="ayudasoli.php" method="post" name="form1" enctype="multipart/form-data">';
echo "<input name='archivo' type='hidden' value='$archivo_name'>";
echo "<table class='basica 100 hover' width='100%'>";
$contadorgeneral=0;
$todobien=0;
$ip = $_SERVER['HTTP_CLIENT_IP'];
if (!$ip) {$ip = $_SERVER['REMOTE_ADDR'];}

$asiento=$b;
$asiento=explode('-',$asiento);
$asiento=$asiento[0].$asiento[1].$asiento[2];
$ultimo="select (con_compr+1) as nuevo from sgcaf8co limit 1";
$aultimo=mysql_query($ultimo);
$rultimo=mysql_fetch_assoc($aultimo);
$elultimo=$rultimo['nuevo'];
$elultimo=ceroizq($elultimo,3);
$ultimo="update sgcaf8co set con_compr ='$elultimo' limit 1";
$aultimo=mysql_query($ultimo);
	
$asiento.=$elultimo;
echo "Generando encabezado contable <strong><a target=\"_blank\" href='editasi2.php?asiento=$asiento'>$asiento </a></strong> <br>";
$quien=$_POST['quien'];
$sql2='select cod_prof, ape_prof, nombr_prof from sgcaf200 where cod_prof="'.$quien.'"';
$result=mysql_query($sql2) ; // or die ("<p />El usuario $usuario no tiene permisos para generar consulta<br>".mysql_error()."<br>".$sql);
$reg=mysql_fetch_assoc($result);
$quien=trim($reg['ape_prof']).' '.$reg['nombr_prof'];
$cuento='Cargo en Cuenta Prestamos Ayuda Solidaria de fecha '.$b.' para '.$quien;
$sql = "INSERT INTO sgcaf830 (enc_clave, enc_fecha, enc_desco, enc_desc1, enc_debe, enc_haber, enc_item, enc_dif, enc_igual, enc_refer, enc_sw, enc_explic) VALUES ('$asiento', '$b', '','',0,0,0,0,0,0,0,'$cuento')"; 
if (!mysql_query($sql)) die ("El usuario $usuario no tiene permiso para añadir Asientos.<br>".$sql);

$sql="select * from sgcaf360 where cod_pres='024'";
$result=mysql_query($sql); // or die ("<p />El usuario $usuario no pudo conseguir la cuenta x pagar<br>".mysql_error()."<br>".$sql);
$cuentas=mysql_fetch_assoc($result);
$cuentaxcobrar=trim($cuentas['cuent_pres']); // .'-'.substr($laparte,1,4);

$sql="select * from sgcaf000 where tipo='AbonoAyuda'";
$result=mysql_query($sql); // or die ("<p />El usuario $usuario no pudo conseguir la cuenta x pagar<br>".mysql_error()."<br>".$sql);
$cuentas=mysql_fetch_assoc($result);
$cuentafarmacia=trim($cuentas['nombre']); // .'-'.substr($laparte,1,4);

$sql="select * from sgcaf000 where tipo='Ingresofarmacia'";
$result=mysql_query($sql); // or die ("<p />El usuario $usuario no pudo conseguir la cuenta x pagar<br>".mysql_error()."<br>".$sql);
$cuentas=mysql_fetch_assoc($result);
$ingresofarmacia=trim($cuentas['nombre']); // .'-'.substr($laparte,1,4);


$totalesgral=$tingreso=0;
foreach ($lines as $line_num => $linea) {
	$datos = explode(",", $linea);
/*
	$cedula = substr($datos[0],0,1).'-';
	if (substr($datos[0],1,8) < 10000000)
	{
		$cedula.='0';
		$cedula.=substr($datos[0],1,7);
	}
	else 
	$cedula.=substr($datos[0],1,8);
*/
	$cedula = $datos[0];
	$tingreso+=($datos[2]);
	$sql2='select cod_prof, ape_prof, nombr_prof from sgcaf200 where ced_prof="'.$cedula.'" and (upper(statu_prof)="ACTIVO" OR upper(statu_prof)="JUBILA") ';
	$result=mysql_query($sql2) ; // or die ("<p />El usuario $usuario no tiene permisos para generar consulta<br>".mysql_error()."<br>".$sql);
	if (mysql_num_rows($result) < 1) {
		echo 'La cédula '.$cedula.' no esta registrada <br>';
//		echo mysql_num_rows($result).$sql2.'<br>';
		$todobien = 1; }
//		echo $datos[$i].'<br>';
/*
	else {
		$reg=mysql_fetch_assoc($result);
		echo $datos[0].'-'.$datos[2].'-'.$reg['cod_prof'].'<br>';
	}
*/
}
// die('para revisar');
if ($todobien != 0)
	echo '<h1><br>Algo fallo<br></h1>';
$ip = $_SERVER['HTTP_CLIENT_IP'];
if (!$ip) {$ip = $_SERVER['REMOTE_ADDR'];}
if ($todobien == 0)
{
	$ultimo="SELECT registro FROM sgcaf310 order by registro desc limit 1";
	$aultimo=mysql_query($ultimo);
	$rultimo=mysql_fetch_assoc($aultimo);
	$ultimo=$rultimo['registro'];
//	$lines = file('farmacia/'.$archivo_name);
	foreach ($lines as $line_num => $linea) {
		$datos = explode(",", $linea);
/*
		$cedula = substr($datos[0],0,1).'-';
		if (substr($datos[0],1,8) < 10000000)
		{
			$cedula.='0';
			$cedula.=substr($datos[0],1,7);
		}
		else 
			$cedula.=substr($datos[0],1,8);
		$monto=($datos[2]);
*/
		$cedula = $datos[0];
/*
		$tingreso+=($datos[2]*100)/100;
		$totalesgral+=($datos[2]*100)/100;
*/
		$sql2='select cod_prof, ape_prof, nombr_prof from sgcaf200 where ced_prof="'.$cedula.'" and (upper(statu_prof)="ACTIVO" OR upper(statu_prof)="JUBILA") ';
		$result=mysql_query($sql2) ; // or die ("<p />El usuario $usuario no tiene permisos para generar consulta<br>".mysql_error()."<br>".$sql);
		$r200=mysql_fetch_assoc($result);
		$micedula=substr($cedula,0,4).'.'.substr($cedula,4,3).'.'.substr($cedula,7,4);
		$sql310="select * from sgcaf310 where cedsoc_sdp='$micedula' and codpre_sdp='004' and stapre_sdp='A' and ! renovado ";
//		echo $sql310.'<br>';
		$r310=mysql_query($sql310);
		if (1==1) // (mysql_num_rows($r310) < 1)
		{
			// prestamo no existe 
			// determino nuevo numero de prestamo
			$laparte=$r200['cod_prof'];
			$elnumero=numero_prestamo($micedula, $laparte);
/*
			$sql_310="select count(nropre_sdp) as cantidad from sgcaf310 where (cedsoc_sdp='$micedula') group by cedsoc_sdp";
			$a_310=mysql_query($sql_310);
			$elnumero=mysql_fetch_assoc($a_310);
			$elnumero=$elnumero['cantidad'];
			$elnumero=$elnumero+1;
			$laparte=$r200['cod_prof'];
			$elnumero=$laparte.ceroizq($elnumero,3);
*/
			$cuota = $datos[2] ;
			$monto = $cuota;
			$nrocuota = 1;
/*
			if ($monto[2] < 2000)
				$cuota=30;
			else $cuota=50;
			$nrocuota=$monto / $cuota;
*/
			// fin de generar nuevo numero
			$sql_310="INSERT INTO sgcaf310 (codsoc_sdp, cedsoc_sdp, nropre_sdp, codpre_sdp, f_soli_sdp, f_1cuo_sdp, ultcan_sdp, monpre_sdp, stapre_sdp, cuota, nrocuotas, cuota_ucla, ip) VALUES ('".$r200['cod_prof']."', '".$micedula."', '$elnumero', '024', '$b', '$b', 0, $monto, 'A', $cuota, '$nrocuota', $cuota, '$ip')";
//			echo $sql_310.'<br>';
			$saldo=$monto;
		}
		else 
		{
/*
			// ya existe el prestamo
			$r310=mysql_fetch_assoc($r310);
			$saldo=($r310['monpre_sdp']-$r310['monpag_sdp'])+$monto;
			$registro = $r310['registro'];
			$sql_310="update sgcaf310 set monpre_sdp=monpre_sdp+'$monto' where registro = '$registro'";
*/
		}
		$r310=mysql_query($sql_310);
/*
		if ($saldo < 2000) 
			$cuota = 30;
		else $cuota = 50;
		if (($r310['cuota_ucla'] != 30) and ($r310['cuota_ucla'] != 50))
			$sql_310="update sgcaf310 set cuota='$cuota', cuota_ucla = '$cuota' where registro = '$registro'";
		$r310=mysql_query($sql_310);
*/	
		// crear registros contables
		$debe=$monto;
		$cuenta1=$cuentaxcobrar.'-'.substr($r200['cod_prof'],1,4);
		// chequear cuenta
		$sql810="select cue_codigo from sgcaf810 where cue_codigo='$cuenta1'";
		$r810=mysql_query($sql810);
		if (mysql_num_rows($r810) < 1)
		{
			// no esta la cuenta y se creara
			$sql810="insert into sgcaf810 (cue_codigo, cue_nombre, cue_nivel, cue_saldo) values ('$cuenta1', '".trim($r200['ape_prof']).' '.$r200['nombr_prof']."', '7', 0)";
//			echo $sql810;
			$r810=mysql_query($sql810);
		}
//		agregar_f820($asiento, $b, '+', $cuenta1, trim($r200['ape_prof']).' '.$r200['nombr_prof'], $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
		agregar_f820($asiento, $b, '+', $cuenta1, 'Ayuda Solidaria a '.$quien, $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
	}
	$debe=$tingreso;
//	$cuenta1=$ingresofarmacia;
//	agregar_f820($asiento, $b, '-', $cuenta1, "Relacion de Fecha Ayuda Solidaria ".$b, $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
	$debe=$tingreso;
	$cuenta1=$cuentaxcobrar.'-'.substr($reg['cod_prof'],1,4);
	agregar_f820($asiento, $b, '-', $cuenta1, "Relacion Ayuda Solidaria Fecha ".$b.' '.$quien, $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
}
echo '<h1><br>Proceso Finalizado<br></h1>';
	
//echo '</fieldset>';
/*
echo "<input type='hidden' name='registrostotales' id='registrostotales' value=".$contadorgeneral.">";
echo '<tr><td align="center" colspan="6">';
echo '<input type="hidden" name="archivosalida" value="'.$archivo_name.'" />';
echo '<input type="submit" name="procesar" value="Procesar" />';
echo '</td></tr></table>';
echo '</form>';
*/
}

function generar_comprobante($ip,$archivosalida,$numerocuotas)
{
//	phpinfo();
	$lascedulas=$_POST['cedulas'];
	$loscodigos=$_POST['codigos'];
	$cuantos=$_POST['cuantos'];
	$total=0;
	$fecha=$_POST['fecha'];
	$b=$fecha;
	$elasiento=substr($fecha,0,4).substr($fecha,5,2).substr($fecha,8,2).'100';
	echo "Generando encabezado contable <strong><a target=\"_blank\" href='editasi2.php?asiento=$elasiento'>$elasiento </a></strong> <br>";
	$sql = "INSERT INTO sgcaf830 (enc_clave, enc_fecha, enc_desco, enc_desc1, enc_debe, enc_haber, enc_item, enc_dif, enc_igual, enc_refer, enc_sw, enc_explic) VALUES ('$elasiento', '$b', 'RETENCIONES DEVUELTAS DE FECHA $fecha','',0,0,0,0,0,0,0,'')"; 
	escribir_archivo($archivosalida,'+830'.$elasiento.$b.'RETENCIONES DEVUELTAS DE FECHA '.$fecha,$numerocuotas);
	$result=mysql_query($sql);
	for ($registro=0; $registro < count($_POST['cedulas']); $registro++) {
//		echo '<br>'.$registro. ' ' .$lascedulas[$registro].' '.$loscodigos[$registro].' '.$cuantos[$registro].' = ';
		$acodigo=$loscodigos[$registro];
		$acodigo=''.$acodigo; // .'_'.$interno;
/*
		$cuantosson='N'.$acodigo; // .'_'.$interno;
		$cuantosson=$_POST['$cuantosson'];
		echo 'codigo a revisar '.$acodigo.'='.$cuantosson.'<br>';
*/
		$acodigo=$_POST[$acodigo];
//		echo $acodigo[1];
//		for ($prestamos=0; $prestamos< count($acodigo); $prestamos++) {
		for ($prestamos=0; $prestamos< $cuantos[$registro]; $prestamos++) {
//			$arevisar=''.$codigo.'_'.$interno;
//			echo '<br>'.$arevisar. count($acodigo);
//				echo 'arreglo';
//			ajustar_prestamo($acodigo[$prestamos], $lascedulas[$registro], $loscodigos[$registro], $total, $ip, $fecha, $elasiento,$archivosalida,$numerocuotas);
			$chequeo=$loscodigos[$registro].'_'.($prestamos+1);
/*
			echo '<br>cheque '.$chequeo . $_POST[$chequeo];
			echo ''.($_POST[$chequeo]?'lo tengo':'<br> NO LO TENGO ');
*/
			if ($_POST[$chequeo])
				ajustar_prestamo($_POST[$chequeo], $lascedulas[$registro], $loscodigos[$registro], $total, $ip, $fecha, $elasiento,$archivosalida,$numerocuotas);
		}
//		echo '<br>';
	}
//	$cuenta1='1-01-01-02-03-01-0002';
	$cuenta1='1-01-02-01-15-01-0001';
	$debe=$total;
	agregar_f820($elasiento, $b, '-', $cuenta1, 'RET.DEV.'.$fecha , $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
	escribir_archivo($archivosalida,'+820'.$elasiento.$b.$cuenta1.'-'.str_pad($debe,10,' ',STR_PAD_LEFT).'RET.DEV.'.$fecha,$numerocuotas);
}

function escribir_archivo($archivosalida,$cuento,$numerocuotas)
{
//	$cuento='"'.$cuento.'"';
//	echo 'el archvo salida'.$archivosalida.'<br>';
	fwrite($archivosalida,$cuento."\n");
//	$contenido.=$cuento;
}

function ajustar_prestamo($nroprestamo, $nrocedula, $codigo, &$total, $ip, $b, $elasiento,$archivosalida,$numerocuotas)
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
	$debe=$fila2['cuota_ucla']*$numerocuotas;
	$tipo=$fila2['codpre_sdp'];

	escribir_archivo($archivosalida,'=310'.$codigo.$nroprestamo.$tipo.str_pad($debe,10,' ',STR_PAD_LEFT),$numerocuotas);

	$sql="update sgcaf310 set monpag_sdp=monpag_sdp - $debe, ultcan_sdp=ultcan_sdp - ".$numerocuotas."  where (codsoc_sdp ='$codigo' and nropre_sdp='$nroprestamo' and ! renovado)";
	if (mysql_query($sql)) ;
	else die($sql);
	
	$cuenta1=$cargo;
	agregar_f820($elasiento, $b, '+', $cuenta1, 'RET.DEV.'.trim($fila1['ape_prof']).' ' .$fila1['nombr_prof'] , $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
	$total+=$debe;
	escribir_archivo($archivosalida,'+820'.$elasiento.$b.$cuenta1.'+'.str_pad($debe,10,' ',STR_PAD_LEFT).'RET.DEV.'.trim($fila1['ape_prof']).' ' .$fila1['nombr_prof'],$numerocuotas);
}

/*
INSERT INTO `sica`.`sgcaf000` (
`tipo` ,
`nombre` ,
`idregistro`
)
VALUES (
'Abonofarmacia', '2-01-01-04-02-01-0001', NULL
);
INSERT INTO `sica`.`sgcaf000` (
`tipo` ,
`nombre` ,
`idregistro`
)
VALUES (
'Ingresofarmacia', '4-02-02-05-01-01-0001', NULL
);


SELECT * FROM `sgcaf820` WHERE substr( com_refere, 1, 2 ) <> 'CF' and substr( com_cuenta, 1, 16 ) = '1-01-02-01-13-01' and (com_monto1 <>99 and com_monto1<>198);
update sgcaf820 set com_cuenta = concat('1-01-02-01-08-01-',substr( com_cuenta, 18, 4 )) where com_nrocom='20120831715' and substr( com_cuenta, 1, 16 ) = '1-01-02-01-13-01';
*/
?>

<?php include("pie.php");?>

</body></html>

