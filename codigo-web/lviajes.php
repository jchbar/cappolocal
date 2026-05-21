<?php
include("head.php");
?>
<script language="javascript">

function abrirVentana(elorden)
{
window.open("lviajespdf.php?orden="+elorden,"parte1","top=0,left=395,status=no,toolbar=no,scrollbar=yes,location=no,type=fullWindow,fullscreen");	
}

</script>
<script language="Javascript" src="selec_fecha.js" type='text/javascript'></script>
<?php
// include("paginar.php");

extract($_GET);
extract($_POST);
extract($_SESSION);


if (!$link OR !$_SESSION['empresa']) {
	include("noempresa.php");
	exit;
}
?>
<body onLoad="showFlatCalendar()">
 <?php // if (!$bloqueo) {echo $onload;}>>
?>

<?php

$readonly=" readonly='readonly'";
include("arriba.php");
// $menu61=1;
include("menusizda.php");
$ip = $_SERVER['HTTP_CLIENT_IP'];
if (!$ip) {$ip = $_SERVER['REMOTE_ADDR'];}
if (!$accion) {
	echo "<div id='div1'>";
	echo "<form action='lviajes.php?accion=Listado' name='form1' method='post'>";
	echo '<fieldset><legend>Información Para Listado de Viajes</legend>';

	$viajes="select fecviaje from sgcaviaj group by fecviaje order by fecviaje";
	$result=mysql_query($viajes);
	echo '<table>';
	echo '<tr><td>Fecha Seleccionadas</td></tr>';
	while ($fila = mysql_fetch_assoc($result))
	{
		$lafecha=$fila['fecviaje'];
		echo '<tr><td>'.$lafecha.'</td</tr>';
/*
		$viajes="select count(cedbene) as cuantos from sgcaviaj where edad >= 12 and fecviaje = '$lafecha' group by fecviaje";
		$result2=mysql_query($viajes);
		$fila2=mysql_fetch_assoc($result2);
		$ta+=$fila2['cuantos'];
		echo '<td align="right" >'.$fila2['cuantos'].'</td>';
		$viajes="select count(cedbene) as cuantos from sgcaviaj where (edad > 2 and edad < 12) and fecviaje = '$lafecha' group by fecviaje";
		$result2=mysql_query($viajes);
		$fila2=mysql_fetch_assoc($result2);
		$tm+=$fila2['cuantos'];
		echo '<td align="right" >'.$fila2['cuantos'].'</td>';
		$viajes="select count(cedbene) as cuantos from sgcaviaj where (edad < 3) and fecviaje = '$lafecha' group by fecviaje";
		$result2=mysql_query($viajes);
		$fila2=mysql_fetch_assoc($result2);
		$ti+=$fila2['cuantos'];
		echo '<td align="right">'.$fila2['cuantos'].'</td></tr>';
*/
	}
/*
	echo 'Listado ordenado por ';
	$orden='Codigo';
	echo '<select name="elorden" size="1">';
	$sql="select nombre from sgcaf000 where tipo='OrdLisSoc' order by nombre";
	$resultado=mysql_query($sql);
	while ($fila2 = mysql_fetch_assoc($resultado)) {
		echo '<option value="'.$fila2['nombre'].'" '.(($orden==$fila2['nombre'])?'selected':'').'>'.$fila2['nombre'].'</option>';}
 	echo '</select> '; 
*/
?>
<script type="text/javascript">
// setActiveStyleSheet(this, 'green');
setActiveStyleSheet(document.getElementById("defaultTheme"), "green");
</script>
	Desde:</b> <input type="text" name="date3" id="sel3" size="12" readonly
><input type="reset" value=" ... "
onclick="return showCalendar('sel3', '%d/%m/%Y');"><br />
	<?php 
/*
	Hasta:</b> <input type="text" name="date4" id="sel1" size="12" readonly="readonly"
><input type="reset" value=" ... "
onclick="return showCalendar('sel1', '%d/%m/%Y');">
*/
	echo '<input type="submit" name="Submit" value="Obtener Reporte" />';
	echo '</legend>';
	echo '</form>';
	echo '</div>';
}	// !$accion
if ($accion=='Listado') {
	echo '<div id="display" style="float: right; clear: both;"></div>';
	echo "<div id='div1'>";

	echo "<form action='lviajes.php?accion=Listo' name='form1' method='post'>"; 
	echo '<fieldset><legend>Recopilando información Para Listado </legend>'; 
	echo '<h2>Preparando información...</h2>';
	echo '<input type="submit" name="Submit" value="Impresión de Listados" onClick="abrirVentana(';
	echo "'";
	echo $elorden;
	echo "&desde=".convertir_fecha($date3);
	echo "'";
	echo ');">  ';
	echo '</legend>';
	echo '</form>';
	
/*
	$fecha=convertir_fecha($date3);
//	echo $fecha;
	$fechaarchivo=explode('/',$date3);
	$fechaarchivo=$fechaarchivo[0].$fechaarchivo[1].$fechaarchivo[2];
	$nombre_archivo = 'nomret/'.$fechaarchivo.'domiciliacion.txt';
	$contenido = $nombre;
	fopen($nombre_archivo, 'w');

	// Asegurarse primero de que el archivo existe y puede escribirse sobre el.
	if (is_writable($nombre_archivo)) {

		// En nuestro ejemplo estamos abriendo $nombre_archivo en modo de adicion.
		// El apuntador de archivo se encuentra al final del archivo, asi que
		// alli es donde ira $contenido cuando llamemos fwrite().
		if (!$gestor = fopen($nombre_archivo, 'a')) {
			echo "<h2>No se puede abrir el archivo ($nombre_archivo) revise permisologia</h2>";
			exit;
		}
		else {

			$sql_200="select ced_prof, concat(trim(ape_prof),' ',nombr_prof) as nombre, statu_prof, netcheque, ctan_prof from sgcaf200, sgcaf700 where (cod_prof=codsoc) and (fecha_acta =  '$fecha' and (estado = 'A'))";
			$a_200=mysql_query($sql_200);
//			echo $sql_200;
			while ($r200 = mysql_fetch_assoc($a_200))
			{
				$cedula=$r200['ced_prof'];
				$micedula=substr($cedula,0,4).'.'.substr($cedula,4,3).'.'.substr($cedula,7,4);
//				echo $micedula;
				revisar_retiro($r200,$fechadescuento,$micedula,$ip,$gestor);
			} // ($r200 = mysql_fetch_assoc($a_200))
			echo '<h2>Se ha generado el archivo '.$nombre_archivo.'<br> para su procesamiento a banco</h2>';

			echo '<form action="depositotxt.php" method="post" name="form1" enctype="multipart/form-data">';
			echo '<input type="hidden" name="archivo" value = "'.$nombre_archivo.'">';
			echo '<input type="submit" name="procesar" value="Descargar Archivo '.$nombre_archivo.'" />';
			echo '</form>';

			echo "<form action='lviajes.php?accion=AsientoContable' name='form1' method='post'>";
			echo '<input type="hidden" name="fechadescuento" value="'.$_POST['date3'].'">';
			echo '<input type="submit" name="deposito" value="Generar Asiento Contable " />';
			echo '</form>';
			
		}
		fclose($gestor);
	}
	else {
		echo "<h2>No se puede crear el archivo ($nombre_archivo) revise permisologia</h2>";
		exit;
	}

	echo '</div>';	
*/
}	// ($accion=='Listado')
if (($accion=='Listo')) { // and ($nominasnormales == 'on')) {
// if ($nominasnormales == 'on') {
//	$fechadescuento=$_POST['fechadescuento'];
//	$nombre_archivo=$_POST['nombre_archivo'];
//	echo '<input type="hidden" name="nombre_archivo" value = "'.$nombre_archivo.'"/>';
	echo "<div id='div1'>";
	
	echo '<h2>Listado Generado...</h2>';
	echo '</div>';
}	// ($accion=='Listo') 


function revisar_retiro($r200,$fechadescuento,$micedula,$ip,$gestor)
{
	$totalxsocio=$r200['netcheque'];
	listadotxt($r200,$totalxsocio,$gestor); // meterlo en el listado a banco
}

function listadotxt($r200,$totalxsocio,$gestor)
{
//0201082457570200015888V07333526        00000000000008937ABARCA DE G.TERESA G.                                                 00CAPPOUCL              *
//0201082457510200129328V16770549        00000000000000010Xx  CARRASCO R. TONDIS MIGUEL                                         00CAPPOUCL              *
	$cadena='02'.$r200['ctan_prof'];
	$cadena.=substr($r200['ced_prof'],0,1).substr($r200['ced_prof'],2,8).replicate(' ',8);
	$monto=(($totalxsocio*100));
//	$monto = str_replace('.','|',$monto);

//	echo $monto.'<br>';
	// quito el punto
	$sinpunto='';
	for ($i=0;$i<strlen($monto);$i++)
		if (substr($monto,$i,1)!= '.')
			$sinpunto.=substr($monto,$i,1);
	$monto=ceroizq($sinpunto,17);
	$cadena.=$monto;
	$nombre=$r200['nombre'];
	$nombre=substr(trim($nombre),0,40);
	$rellenar=replicate(' ',40-strlen($nombre));
	$cadena.=$nombre.$rellenar;
	$cadena.=replicate(' ',30).'00'.'CAPPOUCL'.replicate(' ',14).'*'.chr(13).chr(10);
//	echo $cadena;
	if (fwrite($gestor, $cadena) === FALSE) {
		echo "No se puede escribir al archivo ($nombre_archivo)";
		exit;
	}

}

function replicate($caracterarepetir,$cantidaddeveces)
{
	$resultado='';
	for ($i=0;$i<$cantidaddeveces;$i++)
		$resultado.=$caracterarepetir;
	return $resultado;
}

if (($accion=='AsientoContable')) { // and ($nominasnormales == 'on')) {
	$sql="select * from sgcaf000 where tipo='CtaSocxPag'";
	$result=mysql_query($sql); // or die ("<p />El usuario $usuario no pudo conseguir la cuenta x pagar<br>".mysql_error()."<br>".$sql);
	$cuentas=mysql_fetch_assoc($result);
	$cuentaxpagar=trim($cuentas['nombre']); // .'-'.substr($laparte,1,4);
	$sql_360=$_SESSION['albanco'];
	$a_360=mysql_query($sql_360);
	$rpl=300; 	// registros por listado
	$crl=0;		// contador de registros por listado
	$col_listado=0;
/*
	$condicion_sql='select codigo, cedula, nombre, nrocta, ';
	$col_listado=0;
	$max_cols=mysql_num_rows($a_360);
	while ($r360 = mysql_fetch_assoc($a_360))
	{
		$col_listado++;
//		$header[$col_listado] =trim($r360['descr_pres']).'('.$r360['cod_pres'].')' ;
		$header[$col_listado] =''.$r360['cod_pres'].'' ;
//		echo $header[$col_listado];
		$totales[$col_listado]=0;
		$campo='colpre'.$col_listado;
		$condicion_sql.=' colpre'.$col_listado;
		if ($col_listado != $max_cols) {
			$arrtitulo.=', ';
			$condicion_sql.=', ';
		}
	}
*/
	$asiento=$_POST['fechadescuento'];
	$b=$asiento;
	$b=explode('/',$b);
	$asiento=explode('/',$asiento);
	$asiento=$asiento[0].$asiento[1].$asiento[2];
//	echo 'el asiento '.$asiento;
	$b=$b[2].'-'.$b[1].'-'.$b[0]; // $fechadescuento;
	$ultimo="select (con_compr+1) as nuevo from sgcaf8co limit 1";
	$aultimo=mysql_query($ultimo);
	$rultimo=mysql_fetch_assoc($aultimo);
	$elultimo=$rultimo['nuevo'];
	$elultimo=ceroizq($elultimo,3);
	$ultimo="update sgcaf8co set con_compr ='$elultimo' limit 1";
	$aultimo=mysql_query($ultimo);
	
	$asiento.=$elultimo;
//	echo 'el asiento '.$asiento;
	echo "Generando encabezado contable <strong><a target=\"_blank\" href='editasi2.php?asiento=$asiento'>$asiento </a></strong> <br>";
	$cuento='Deposito a Banco de Prestamos '.$_SESSION['tipo'];
	$sql = "INSERT INTO sgcaf830 (enc_clave, enc_fecha, enc_desco, enc_desc1, enc_debe, enc_haber, enc_item, enc_dif, enc_igual, enc_refer, enc_sw, enc_explic) VALUES ('$asiento', '$b', '','',0,0,0,0,0,0,0,'$cuento')"; 
	if (!mysql_query($sql)) die ("El usuario $usuario no tiene permiso para añadir Asientos.<br>".$sql);

	$sql_nopr=$condicion_sql." from sgcanopr where ('$fechadescuento' = fecha) order by cedula "; //  limit 20";
//	 echo $sql_nopr;
	$sql_nopr="select cod_prof, ced_prof, concat(trim(ape_prof),' ',nombr_prof) as nombre, statu_prof, netcheque, ctan_prof from sgcaf200, sgcaf700 where (cod_prof=codsoc) and (fecha_acta =  '$b' and (estado = 'A'))";
echo $sql_200;
	$a_nopr=mysql_query($sql_nopr);
	$registros=mysql_num_rows($a_nopr);
	if ($registro < 30)
		$registro=30;
	set_time_limit($registros);
//	$lascolumnas=mysql_num_fields($a_nopr)-4;
	$totalesgral=0;
	while ($r_nopr = mysql_fetch_assoc($a_nopr))
	{
/*
		$t1=0;
		for ($prestamos=1;$prestamos<=$lascolumnas;$prestamos++) {		// sumatoria de los prestamos
			$item='colpre'.$prestamos;
			$t1+=$r_nopr[$item];
			$totales[$prestamos]+=$r_nopr[$item];
		}
		if ($t1 > 0) {
			$cont++;
			for ($prestamos=1;$prestamos<=$lascolumnas;$prestamos++) {
				$posicion++;
				$item='colpre'.$prestamos;
				$t1+=$r_nopr[$item];
				if ($r_nopr[$item] > 0)
				{	
*/
		$debe=$r_nopr['netcheque'];
		$cuenta1=$cuentaxpagar.'-'.substr($r_nopr["cod_prof"],1,4);
		agregar_f820($asiento, $b, '+', $cuenta1, 'Retiro de Haberes '.$fechadescuento, $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
		$totalesgral+=$debe;
	}
/*
		$totales[$prestamos]+=$r_nopr[$item];
			}
		}
	}
*/
	$debe=$totalesgral;
	$cuenta1='1-01-01-02-03-01-0002'; // $cuentaxpagar.='-'.substr($r_nopr["codigo"],1,4);
	agregar_f820($asiento, $b, '-', $cuenta1, 'Retiro Haberes Socios de Fecha '.$fechadescuento, $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
	echo '<h1>Asiento Contable Generado</h1>';
}

?>

<?php include("pie.php");?>

</body></html>

