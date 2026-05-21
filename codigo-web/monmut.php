<?php
include("head.php");
//include("popcalendario/escribe_formulario.php");
?>
<script src="ajxmut.js" type="text/javascript"></script>
<script language="Javascript" src="selec_fecha.js" type='text/javascript'></script>
<script language="javascript">
function abrir2Ventanas(fechadescuento)
{
// window.open("06_Inventario_actuallist.asp","prueba1", "width=385,height=180,top=0,left=0',status,toolbar =1,scrollbars,location");
// window.open("leftmenu.htm","prueba2","width=385,he ight=180,top=0,left=395,status,toolbar=1,scrollbar s,location");
window.open("monmutpdf1.php?fechadescuento="+fechadescuento,"parte1","top=0,left=395,status=no,toolbar=no,scrollbar=yes,location=no,type=fullWindow,fullscreen");	// los primeros 500 socios	width=385,height=180,
/*
window.open("monmutpdf2.php?fechadescuento="+fechadescuento,"parte2","top=0,left=395,status=no,toolbar=no,scrollbar=yes,location=no,type=fullWindow,fullscreen");
// "width=385,height=180,top=0,left=395,status,toolbar=1,scrollbar s,location");	// los demas
window.open("monmutpdf3.php?fechadescuento="+fechadescuento,"resumen","top=0,left=395,status=no,toolbar=no,scrollbar=yes,location=no,type=fullWindow,fullscreen");
//,"width=385,height=180,top=0,left=395,status,toolbar=1,scrollbar s,location");	// resumen de los montos
window.open("monmutpdf4.php?fechadescuento="+fechadescuento,"banco","top=0,left=395,status=no,toolbar=no,scrollbar=yes,location=no,type=fullWindow,fullscreen");
// "width=385,height=180,top=0,left=395,status,toolbar=1,scrollbar s,location");	// el listado a banco
window.open("monmutpdf5.php?fechadescuento="+fechadescuento,"amortiza","top=0,left=395,status=no,toolbar=no,scrollbar=yes,location=no,type=fullWindow,fullscreen");
// "width=385,height=180,top=0,left=395,status,toolbar=1,scrollbar s,location");	// amortizacion / capital
//window.open("monmutpdf6.php?fechadescuento="+fechadescuento,"descargar","top=0,left=395,status=no,toolbar=no,scrollbar=yes,location=no,type=fullWindow,fullscreen");
// "width=385,height=180,top=0,left=395,status,toolbar=1,scrollbar s,location");	// amortizacion / capital
*/
}
</script>
<?php
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
if (!$accion) {
	echo "<div id='div1'>";
	echo "<form action='monmut.php?accion=ListadoDeCuotas' name='form1' method='post'>";
	echo '<fieldset><legend>Información Para Descuentos de Prestamos</legend>';
	echo 'Fecha en que se realiza el descuento: ';
	$fechadelabono=date("d")."/".date('m')."/".date("Y"); 
//	escribe_formulario_fecha_vacio("fechadelpago","form1",$fechadelabono,5,''); 
	/*
		fecha del abono = fecha de la forma
		form1.fechadelabono	= ?
		'd/m/yyyy' =	formato de la fecha
		$fechadelabono 	= fecha por defecto
		$mesant			= rango anterior
		$hoy			= rango maximo
		'1'				= no habilita sabados ni domingos '0' muestra todo
		'3'				= cantidad de anos que se pueden visualizar
	*/
/*
	$hoy1 = mktime(0,0,0,date("m"),date("d"),date("Y")); 
	$h = date("d/m/Y",$hoy1);
	$futuro = $hoy1+(30*24*3600); // 30 dias
	$pasado = $hoy1-(3*24*3600); // 3 dias
	$futuro = date("d/m/Y",$futuro);
	$pasado = date("d/m/Y",$pasado);
	escribe_formulario(fechadelpago, form1.fechadelpago, 'd/m/yyyy',$fechadelabono, $pasado, $futuro, '1', '1');
*/
?>
<script type="text/javascript">
// setActiveStyleSheet(this, 'green');
setActiveStyleSheet(document.getElementById("defaultTheme"), "green");
</script>
	</b> <input type="text" name="date3" id="sel3" size="12" readonly
><input type="reset" value=" ... "
onclick="return showCalendar('sel3', '%d/%m/%Y');"><br />

<?php
//	echo '<br>Mutuo Auxilio<input type="checkbox" name="nominasnormales" value = "on" checked align="right"/><br />';
	echo '<br>Cedula del Socio: ';
?>
	<input type="text" size="20" tabindex='5' name='cuenta1' id="inputString" onKeyUp="lookup_socios(this.value);" onBlur="fill_socios();" value ="" autocomplete="off"/>
	<div class="suggestionsBox" id="suggestions" style="display: none;">
	<img src="upArrow.png" style="position: relative; top: -12px; left: 70px; " alt="upArrow" />
	<div class="suggestionList" id="autoSuggestionsList">
	</div>
	</div>

<?php
	echo 'Monto: ';	
	echo "<input type = 'text' size='11' maxlength='11' name='elmonto' value='0.00' tabindex='8'><br>";

	echo '<br>Mutuo Auxilio<input type="checkbox" id="nominasnormales" name="nominasnormales" value="on" checked="checked" onClick="activar()" />';
	echo '<input type="textbox" maxlength="30" size="30" id="inputString2" name="inputString2" value="" disabled=true ';
	echo "onKeyUp='lookup_beneficiario(this.value);' onBlur='fill_beneficiario();' ><br>";
//autocomplete='off' 
	echo '<div class="suggestionsBox" id="suggestions" style="display: none;">';
	echo '<img src="upArrow.png" style="position: relative; top: -12px; left: 70px; "  alt="upArrow" />';
	echo '<div class="suggestionList" id="autoSuggestionsList">';
	echo '</div>';
	echo '</div>';


	echo '<input type="submit" name="Submit" value="Enviar" />';
	echo '</legend>';
	echo '</form>';
	echo '</div>';
}	// !$accion
// recordar bloquear la base de datos durante este proceso y luego liberarla
if (($accion=='ListadoDeCuotas')) { // and ($nominasnormales == 'on')) {
	$fechadescuento=convertir_fecha($_POST['date3']);		// revisar que no hayan nominas con esa fecha
//	$sql="select count(fecha) as cuantos, ip from sgcanopr where fecha = '$fechadescuento' group by fecha";
//	echo $sql;
//	$resultado=mysql_query($sql);
	if (0 == 1) { // (mysql_num_rows($resultado)>0) {
		$registro=mysql_fetch_assoc($resultado);
		echo '<h2>No se puede procesar esta nomina existe una ya realizada con '.$registro['cuantos'].' registro generada desde la IP '.$registro['ip'].'</h2>';
		exit;
	}

	$fechaarchivo=explode('-',$fechadescuento);
	$fechaarchivo=$fechaarchivo[0].$fechaarchivo[1].$fechaarchivo[2];
	$nombre_archivo = 'nompre/'.$fechaarchivo.'domiciliacion'.($nominasnormales == 'on'?'MutuoAuxilio':'MontePio').'.txt';
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

			echo "<div id='div1'>";
			echo "<form action='monmut.php?accion=Abonar' name='form1' method='post' onsubmit='return realiza_asiento_montepio(form1)'>";
			echo '<input type="hidden" name="nombre_archivo" value = "'.$nombre_archivo.'"/>';
			echo '<input type="hidden" name="nominasnormales" value = "on"/>';
			echo '<input type="hidden" name="cedula" value="'.$cuenta1.'">';
			echo '<input type="hidden" name="beneficiario" value="'.$_POST['inputString2'].'">';
			$cedula=$cuenta1;
			$monto=$_POST['elmonto'];
//			$fechadescuento=$_POST['fechadelpago'];
			echo '<fieldset><legend>Recopilando información Para Descuentos de Prestamos al '.$fechadescuento.'</legend>';
			echo '<h2>Preparando información...</h2>';
//			$fechadescuento=convertir_fecha($fechadescuento);
/*
			$sql_360="select * from sgcaf360 where (dcto_sem) order by cod_pres";
			$a_360=mysql_query($sql_360);
*/
			$sql_200="select cod_prof, ced_prof, concat(trim(ape_prof), ' ', trim(nombr_prof)) as nombre, ctan_prof from sgcaf200 where (ucase(statu_prof) != 'RETIRA') and (tipo_socio='P') ";
			if ($nominasnormales == 'on') {
//				$sql_200.=" and (ced_prof = '".$cedula."') ";
				$otrosql="select concat(trim(ape_prof), ' ', trim(nombr_prof)) as nombre from sgcaf200 where ced_prof='$cedula'";
				$result=mysql_query($otrosql);
				$filas=mysql_fetch_assoc($result);
				$_SESSION['beneficiario']=$filas['nombre'];
			}
			else 
			{
				$sql_200.=" and (ced_prof <> '".$cedula."') ";
				$_SESSION['beneficiario']=$_POST['inputString2'];
			}

			$sql_200.=" order by ced_prof";
			$_SESSION['comandosql']=$sql_200;
			$_SESSION['monto']=$monto;
			$_SESSION['mutuo']=($nominasnormales == 'on'?1:0);
			$_SESSION['totalchq']=0;
			$_SESSION['cedulasocio']=$cedula;
			
//			echo $sql_200;
			$a_200=mysql_query($sql_200);
			while ($r200 = mysql_fetch_assoc($a_200))
			{
				$cedula=$r200['ced_prof'];
				$micedula=substr($cedula,0,4).'.'.substr($cedula,4,3).'.'.substr($cedula,7,4);
//				$sql_310="select * from sgcaf310 where (stapre_sdp='A') and (cedsoc_sdp='$micedula') and (f_1cuo_sdp <= '$fechadescuento') order by codpre_sdp";
//				$a_310=mysql_query($sql_310);
				revisar_prestamo($r200,$fechadescuento,$micedula,$ip,$gestor,$monto);
			} // ($r200 = mysql_fetch_assoc($a_200))
			echo '<input type="hidden" name="fechadescuento" value="'.$fechadescuento.'">';
			echo '<h2>Información Lista...</h2><br>';
			echo '<h2>Se ha generado el archivo '.$nombre_archivo.'<br> para su procesamiento a banco</h2>';

			echo '<input type="submit" name="Submit" value="Impresión de Listados" onClick="abrir2Ventanas(';
			echo "'";
			echo $fechadescuento;
			echo "'";
//			echo "'".'&downloadfile='.$nombre_archivo.'&';
			echo ');">  ';
//			echo '<input type="submit" name="Submit" value="Realizar Abono " />';
			echo '</legend>';
			echo '</form>';
			echo '</div>';	
		}
		fclose($gestor);
/*
		$downloadfile=nombre_archivo;
		echo 'header ("Content-Disposition: attachment; filename=\".$downloadfile.\"" )';
//		header ("Content-Disposition: attachment; filename=\"exportar.txt\"" );
		echo 'header("Content-Type: application/force-download")';
		echo 'header("Content-Transfer-Encoding: binary")';
		echo 'header("Content-Length: ".strlen($downloadfile))';
// 		header("Content-Length: ".strlen($filecontent));
		echo 'header("Pragma: no-cache")';
		echo 'header("Expires: 0")' ;
		echo $downloadfile;
*/
	}
	else {
		echo "<h2>No se puede crear el archivo ($nombre_archivo) revise permisologia</h2>";
		exit;
	}
}	// ($accion=='ListadoDeCuotas')
if (($accion=='Abonar')) { // and ($nominasnormales == 'on')) {
// if ($nominasnormales == 'on') {
	$fechadescuento=$_POST['fechadescuento'];
	$nombre_archivo=$_POST['nombre_archivo'];
	$beneficiario=$_POST['beneficiario'];
//	echo '<input type="hidden" name="nombre_archivo" value = "'.$nombre_archivo.'"/>';
//	echo "<div id='div1'>";
	
/*
	echo '<h2>Puede proceder luego de la impresion de los listados a <br>realizar el abono a prestamos y el asiento contable y';
	echo '<br>recuerde obtener descargar el archivo </h2><h1>'.$nombre_archivo.'</h1><h2> para enviar al banco</h2>';
*/
	echo '<form action="depositotxt.php" method="post" name="form1" enctype="multipart/form-data">';
	echo '<input type="hidden" name="archivo" value = "'.$nombre_archivo.'">';
	echo '<input type="submit" name="procesar" value="Descargar Archivo (para Descuento)'.$nombre_archivo.'" />';
	echo '</form>';

//	echo 'la sesion'.$_SESSION['mutuo']  ;
	if ($_SESSION['mutuo'] == 0) 
	{
		echo '<div id="div1">';
		$cedula = $_POST['cedula'];
		$_SESSION['lagestion']='S';
		echo "<form enctype='multipart/form-data' action='monmut.php?accion=HC' name='form1' method='post'>";
		$sql2="select * from sgcaf843 order by nombre_ban";
		$resultado=mysql_query($sql2);
		echo "<input type = 'hidden' value ='".$cedula."' name='cedula'>";
		$fila2 = mysql_fetch_assoc($resultado);
		echo '<fieldset><legend>Datos para el cheque </legend>';
		echo '<td>Seleccione Plaza</td>';
		echo '<td class="rojo">';
		echo '<select name="elcheque" size="1">';
		while ($fila2 = mysql_fetch_assoc($resultado)) {
			echo '<option value="'.$fila2['cod_banco'].'" selected >'.$fila2['nombre_ban'].' / '.$fila2['cue_banco'].'</option>';}
		echo '</select> *'; 
		echo '</td>';
		echo '</fieldset>';
		echo "<br><input type = 'submit' value = 'Continuar'></form>\n";
	}
	else 
	{
		$cedula = $_POST['cedula'];
		$sql="select *, concat(ape_prof, ' ', nombr_prof) as nombre from sgcaf200 where ced_prof='$cedula'";
		$result=mysql_query($sql); 	// busco el registro	
		$retiro=mysql_fetch_assoc($result);
		$beneficiario=trim($retiro['ape_prof']). ' '.trim($retiro['nom_prof']);
		$monto=$_SESSION['totalchq']; // $retiro['netcheque'];
		$laparte=substr($retiro['cod_prof'],0,5);

		$ultimo="select (con_compr+1) as nuevo from sgcaf8co limit 1";
		$aultimo=mysql_query($ultimo);
		$rultimo=mysql_fetch_assoc($aultimo);
		$elultimo=$rultimo['nuevo'];
		$elultimo=ceroizq($elultimo,3);
		$ultimo="update sgcaf8co set con_compr ='$elultimo' limit 1";
		$aultimo=mysql_query($ultimo);
		$b=$_POST['fechadescuento'];
		$c=explode('-',$b);
		$asiento=$c[0].$c[1].$c[2].$elultimo;
		echo "Generando encabezado contable <strong><a target=\"_blank\" href='editasi2.php?asiento=$asiento'>$asiento </a></strong> <br>";
		$cuento='Mutuo Auxilio Beneficiario '.$beneficiario;
		$sql = "INSERT INTO sgcaf830 (enc_clave, enc_fecha, enc_desco, enc_desc1, enc_debe, enc_haber, enc_item, enc_dif, enc_igual, enc_refer, enc_sw, enc_explic) VALUES ('$asiento', '$b', '','',0,0,0,0,0,0,0,'$cuento')"; 
		if (!mysql_query($sql)) die ("El usuario $usuario no tiene permiso para añadir Asientos.<br>".$sql);

		$monto=$_SESSION['totalchq']; // $retiro['netcheque'];
		$codigosocio=substr($retiro['cod_prof'],1,4);
		$sql="select * from sgcaf000 where tipo='MutuoAuxilio'";
		$sqling="select * from sgcaf000 where tipo='MutuoAuxilioIng'";
		echo "Creando Abono(s) <br>";
		$result=mysql_query($sql); // or die ("<p />El usuario $usuario no pudo conseguir la cuenta x pagar<br>".mysql_error()."<br>".$sql);
		$cuentas=mysql_fetch_assoc($result);
		$cuenta1=$cuentas['nombre'].'-'.$codigosocio;
		$debe=$monto;
		$referencia='';
		agregar_f820($asiento, $b, '+', $cuenta1, $beneficiario, $debe, $haber, 0,$ip,0,$referencia,'','S',0); 

		$result=mysql_query($sqling); // or die ("<p />El usuario $usuario no pudo conseguir la cuenta x pagar<br>".mysql_error()."<br>".$sql);
		$cuentas=mysql_fetch_assoc($result);
		$cuenta1=$cuentas['nombre'].$codigo;
		$ingreso=$monto * 0.1;
		$comision=$monto * 0.05;
		$debe=$ingreso;
		agregar_f820($asiento, $b, '-', $cuenta1, $beneficiario, $debe, $haber, 0,$ip,0,$referencia,'','S',0); 

		$sqling="select * from sgcaf000 where tipo='ComisionDom'";
		$result=mysql_query($sqling); // or die ("<p />El usuario $usuario no pudo conseguir la cuenta x pagar<br>".mysql_error()."<br>".$sql);
		$cuentas=mysql_fetch_assoc($result);
		$cuenta1=$cuentas['nombre'].$codigo;
		$debe=$comision;
		agregar_f820($asiento, $b, '-', $cuenta1, $beneficiario, $debe, $haber, 0,$ip,0,$referencia,'','S',0); 

		$sql="select * from sgcaf000 where tipo='IngresoBanco'"; //   CtaSocxPag'";
		$result=mysql_query($sql); // or die ("<p />El usuario $usuario no pudo conseguir la cuenta x pagar<br>".mysql_error()."<br>".$sql);
		echo "Creando Cargo(s) <br>";
		$cuentas=mysql_fetch_assoc($result);
		$cuenta1=trim($cuentas['nombre']); // .'-'.substr($laparte,1,4);
		$neto = $monto - ($ingreso + $comision);
		$debe=$neto;
		agregar_f820($asiento, $b, '-', $cuenta1, $beneficiario, $debe, $haber, 0,$ip,0,$referencia,'','S',0); 

// generar archivo
		$fechaarchivo=explode('-',$fechadescuento);
		$fechaarchivo=$fechaarchivo[0].$fechaarchivo[1].$fechaarchivo[2];
		$nombre_archivo = 'nompre/'.$fechaarchivo.'mutuoauxilio.txt';
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
				$cadena='02'.$retiro['ctan_prof'];
				$cadena.=substr($retiro['ced_prof'],0,1).substr($retiro['ced_prof'],2,8).replicate(' ',8);
				$monto=trim($monto*100);
				// quito el punto
				$sinpunto='';
				for ($i=0;$i<strlen($monto);$i++)
					if (substr($monto,$i,1)!= '.')
					$sinpunto.=substr($monto,$i,1);
					$monto=ceroizq($sinpunto,17);
				$cadena.=$monto;
				$nombre=$retiro['nombre'];
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
		fclose($gestor);
		echo '<form action="depositotxt.php" method="post" name="form1" enctype="multipart/form-data">';
		echo '<input type="hidden" name="archivo" value = "'.$nombre_archivo.'">';
		echo '<input type="submit" name="procesar" value="Descargar Archivo (para DEPOSITO)'.$nombre_archivo.'" />';
		echo '</form>';

				

// fin generar archivo

	}

//	echo "<form action='bajpre.php' name='form1' method='post'>";
//	echo '<input type="submit" name="Submit" value="Descargar Archivo TXT"';
//	echo '</form>';
	/// hacer el asiento ver asiento original con intereses y demas
/*	echo '<script language="JavaScript" src="monmutpdf1.php?fechadescuento=$fechadescuento"></script>';  */
//	echo '<a href="" onClick="abrir2Ventanas();">KK</a>';
//	echo "<a target=\"_blank\" href=\"monmutpdf1.php?fechadescuento=$fechadescuento\" onClick=\"info.html\', \'\',\'width=250, height=190\')\">Imprimir Listados de Descuentos</a>"; 	


	echo '</div>';
}	// ($accion=='ImpresionListados') 

if ($accion == "HC") {	
	echo '<div id="div1">';
	$cedula = $_POST['cedula'];
	$elcheque= $_POST['elcheque'];
	echo "Asignando cheque<br>";
	$cheques_sql="SELECT * FROM sgcaf846, sgcaf845, sgcaf843 where registro=nro_reg and estatus='+' and nro_ban=nro_cta_ba and estado='1' and emitircheque='1' group by registro";

//	$cheques_sql="select * from sgcaf844, sgcaf843 where ((ban_che='$elcheque') and (sta_che='L')) and (cod_banco ='$elcheque') limit 1";
//	die($cheques_sql);
	$cheques=mysql_query($cheques_sql);	// busco el primer cheque disponible de ese banco 
	if (mysql_num_rows($cheques) > 0) {
		$cheque=mysql_fetch_assoc($cheques);
		$elnumero=$cheque['nro_che'];
		$laplaza=trim($cheque['nombre_ban']) . ' / '.$cheque['nro_cta_ba'];
		echo "El número de cheque asignado es $elnumero<br>";

		$sql="select * from sgcaf200 where ced_prof='$cedula'";
		$result=mysql_query($sql); 	// busco el registro	
		$retiro=mysql_fetch_assoc($result);
		$benef=trim($retiro['ape_prof']). ' '.trim($retiro['nom_prof']);
		$monto=$_SESSION['totalchq']; // $retiro['netcheque'];
		$codigosocio=substr($retiro['cod_prof'],1,4);
//		$codigo=$retiro['codsoc'];
		$hoy= date("Y-m-d");
		echo "Creando encabezado de cheque<br>";
		$titulo=($_SESSION['mutuo']==1?'Mutuo Auxilio':'Montepio');
		if ($_SESSION['mutuo']==1) {
			$concepto='P/Registrar Mutuo Auxilio '.$beneficiario;
			$anombre=$beneficiario;
			$sql="select * from sgcaf000 where tipo='MutuoAuxilio'";
			$sqling="select * from sgcaf000 where tipo='MutuoAuxilioIng'";

			$registro="insert into sgcaf840 (mche_orden, mche_fecha, mche_nombr, mche_monto, mche_descr, mche_statu, mche_banco, mche_prest, cobrados) ";
			$registro.="VALUES ('$elnumero','$hoy','$anombre',$monto,'$concepto','L','$elcheque','XXXX', 0)";
		}
		else 
		{
			$sql="select * from sgcaf843 where cod_banco='$elcheque'";
			$resultado=mysql_query($sql)or die ("<p />El usuario $usuario no tiene permisos para añadir Clientes o cuenta ya existente.<br>".mysql_error()."<br>".$sql);
			$lacuenta=mysql_fetch_assoc($resultado);
			$lacuenta=$lacuenta['nro_cta_ba'];
			$sql="UPDATE sgcaf846 SET descrip='".$_SESSION['beneficiario']."', ip='$ip', fecha='$hoy', estatus='-' WHERE nro_che='$elnumero' and banco='$lacuenta'";
//			echo $sql;
			mysql_query($sql)or die ("<p />El usuario $usuario no tiene permisos para añadir Clientes o cuenta ya existente.<br>".mysql_error()."<br>".$sql);

			$concepto='P/Registrar Monte Pio '.$beneficiario . ' Beneficiario '.$benef;
			$anombre=$_SESSION['beneficiario'];
			$sql="select * from sgcaf000 where tipo='MontePio'";
			$sqling="select * from sgcaf000 where tipo='MontePioIng'";
			$registro="insert into sgcaf840 (mche_orden, mche_fecha, mche_nombr, mche_monto, mche_descr, mche_statu, mche_banco, mche_prest, cobrados) ";
			$registro.="VALUES ('$elnumero','$hoy','$anombre',$monto,'$concepto','L','$elcheque','XXXX', 0)";
		}
//	echo $registro;
		if (mysql_query($registro)){
			$registrocreado=mysql_insert_id();
			echo "Creando cargo en el cheque<br>";
			$result=mysql_query($sql); // or die ("<p />El usuario $usuario no pudo conseguir la cuenta x pagar<br>".mysql_error()."<br>".$sql);
			$cuentas=mysql_fetch_assoc($result);
			$cuenta1=$cuentas['nombre'].'-'.$codigosocio;
			$registro="insert into sgcaf841 (mche_orden, mche_cuent, mche_debcr, mche_descr, mche_monto1, mche_monto2,mche_monto,mche_banco";
			$registro.=") VALUES ('$elnumero','$cuenta1','+',' ".$titulo.' '.$beneficiario."', ";
			$registro.="$monto, 0, 0, '$elcheque')";
//			echo $elcheque;
			if (mysql_query($registro)){}
			echo "Creando abono en el cheque<br>";
//			$_SESSION['elcheque']=$elnumero; 
//			$_SESSION['laplaza']=$laplaza; 

			$result=mysql_query($sqling); // or die ("<p />El usuario $usuario no pudo conseguir la cuenta x pagar<br>".mysql_error()."<br>".$sql);
			$cuentas=mysql_fetch_assoc($result);
			$cuenta1=$cuentas['nombre'].$codigo;
			$ingreso=$monto * 0.1;
			$comision=$monto * 0.05;
//			echo $elcheque;
			$registro="insert into sgcaf841 (mche_orden, mche_cuent, mche_debcr, mche_descr, mche_monto1, mche_monto2,mche_monto,mche_banco";
			$registro.=") VALUES ('$elnumero','$cuenta1','-',' ".$titulo.' '.$beneficiario."', ";
			$registro.="0, $ingreso, 0, '$elcheque')";
			if (mysql_query($registro)){}


			$sqling="select * from sgcaf000 where tipo='ComisionDom'";
			$result=mysql_query($sqling); // or die ("<p />El usuario $usuario no pudo conseguir la cuenta x pagar<br>".mysql_error()."<br>".$sql);
			$cuentas=mysql_fetch_assoc($result);
			$cuenta1=$cuentas['nombre'].$codigo;
			$registro="insert into sgcaf841 (mche_orden, mche_cuent, mche_debcr, mche_descr, mche_monto1, mche_monto2,mche_monto,mche_banco";
			$registro.=") VALUES ('$elnumero','$cuenta1','-',' ".$titulo.' '.$beneficiario."', ";
			$registro.="0, $comision, 0, '$elcheque')";

			$neto = $monto - $ingreso - $comision;
			if (mysql_query($registro)){
//				$sql2="select * from sgcaf843 where cod_banco = '$elcheque'";
//				echo $sql2;
//				$cheque=mysql_query($sql2);
//				$cheque=mysql_fetch_assoc($cheque);
				$cuenta1=$cheque['cue_banco'];
				$registro="insert into sgcaf841 (mche_orden, mche_cuent, mche_debcr, mche_descr, mche_monto1, mche_monto2,mche_monto,mche_banco";
				$registro.=") VALUES ('$elnumero','$cuenta1','-','".$beneficiario."', ";
				$registro.="0,$neto, 0, '$elcheque')";
	//			echo $registro;
				if (mysql_query($registro)){}
				else echo '<h2>Error al generar el abono del cheque</h2>';
				$sql="update sgcaf840 set mche_monto = '$neto' where (".$registrocreado." = idregistro)";
				if (mysql_query($sql)){}
			}
			else echo '<h2>Error al generar el cargo del cheque</h2>';
		}
		else echo '<h2>Error al generar encabezado del cheque</h2>';
	}
	else echo "<h2>No se puede aprobar el retiro porque no hay cheques disponibles. Debe realizar el proceso de cargar cheques</h2>";
	echo '</div>';
}

function revisar_prestamo($r200,$fechadescuento,$micedula,$ip,$gestor,$monto)
{
	$primeravez=0;
	$totalxsocio=0;
/*
	while ($r310 = mysql_fetch_assoc($a_310))
	{
		if (! $r310['renovado'])
			if ($r310['stapre_sdp'] == 'A')
				acumular($r200,$r310,$a_360,$fechadescuento,$micedula,$primeravez,$ip,$totalxsocio) ;
			else ;
		else 
			if ($r310['stapre_sdp'] == 'A')
				if ($r310['paga_hasta'] >= $fechadescuento)
					acumular($r200,$r310,$a_360,$fechadescuento,$micedula,$primeravez,$ip,$totalxsocio);
	}
*/
	$totalxsocio=$monto;
	$_SESSION['totalchq']+=$monto;
	if ($totalxsocio > 0){	// meterlo en el listado a banco
		listadotxt($r200,$totalxsocio,$gestor);
	}
}

function listadotxt($r200,$totalxsocio,$gestor)
{
//0201082457570200015888V07333526        00000000000008937ABARCA DE G.TERESA G.                                                 00CAPPOUCL              *
//0201082457510200129328V16770549        00000000000000010Xx  CARRASCO R. TONDIS MIGUEL                                         00CAPPOUCL              *
	$cadena='02'.$r200['ctan_prof'];
	$cadena.=substr($r200['ced_prof'],0,1).substr($r200['ced_prof'],2,8).replicate(' ',8);
	$monto=trim($totalxsocio*100);
	// quito el punto
	$sinpunto='';
//	echo $monto.' - '; 
	for ($i=0;$i<strlen($monto);$i++)
		if (substr($monto,$i,1)!= '.')
			$sinpunto.=substr($monto,$i,1);

//	echo $sinpunto.' - '; 
	$monto=ceroizq($sinpunto,17);
//	echo $monto.'<br>';
	$cadena.=$monto;
	$nombre=$r200['nombre'];
	$nombre=substr(trim($nombre),0,40);
	$rellenar=replicate(' ',40-strlen($nombre));
	$cadena.=$nombre.$rellenar;
	$cadena.=replicate(' ',30).'00'.'CAPPOUCL'.replicate(' ',14).'*'.chr(13).chr(10);
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


function acumular($r200,$r310,$a_360,$fechadescuento,$micedula,&$primeravez,$ip,&$totalxsocio)
{
	if ($r310['cuota_ucla'] == 0) {
		$actualiza="update sgcaf310 set cuota_ucla=".$r310['cuota']." where registro =".$r310['registro'];
		$ractualiza=mysql_query($actualiza);
		$lacuota = $r310['cuota'];
	}
	else $lacuota = $r310['cuota_ucla'];
	mysql_data_seek ($a_360, 0);		// volver al principio de la busqueda
	$nombre=$r200['nombre'];
	$codigo=$r310['codsoc_sdp'];
	$posicion=0;
	while ($r360 = mysql_fetch_assoc($a_360))
	{
		$posicion++;
		if ($r310['codpre_sdp']==$r360['cod_pres']) {
//			echo $r310['nropre_sdp'].' - ' .$r310['codpre_sdp'].' '; // . ' - '.$r360['cod_pres'];
			$lacolumnapres='colpre'.$posicion;
			$lacolumnanro='colnro'.$posicion;
			$elnumero=$r310['nropre_sdp'];
			if ($primeravez == 0) {
				$nrocuenta=$r200['ctan_prof'];
				$sql_pre="insert into sgcanopr (fecha, cedula, codigo, nombre, ".$lacolumnapres.", ".$lacolumnanro.", proceso, ip, nrocta) values ('$fechadescuento','$micedula','$codigo','$nombre','$lacuota','$elnumero',1, '$ip', '$nrocuenta')";
					$primeravez = 1;
			}
			else $sql_pre="update sgcanopr set ".$lacolumnapres." = '$lacuota', ".lacolumnanro." = '$elnumero' where (cedula=$micedula)" ;
			if (mysql_query($sql_pre)) {
				$saldo=$r310['monpre_sdp']-$r310['monpag_sdp'];
				$tipo=$r310['codpre_sdp'];
				$capital=$lacuota;
				$totalxsocio+=$capital;
				$i2=0;
//				echo $r360['i_max_pres']. ' - ' .$r310['nrocuotas'].' - '.$saldo.' - '.'<br>';
				$interes=cal_int($r360['i_max_pres'],$r310['nrocuotas'],$saldo,$factor_divisible = 52,$z=0,$i2);
//				echo $i2;
				$interes=round(($saldo*$i2),2);
				$cu=round($lacuota,2) - $interes;
				$la_cuota=$interes+$cu;
				if ($saldo <= 0) {
					$interes=0;
					$capital=$cuota;
				}
				$cuent_p=trim($r360['cuent_pres']).'-'.substr($codigo,1,4);
				$cuent_i=trim($r360['cuent_int']).'-'.substr($codigo,1,4);
				$cuent_d=trim($r360['otro_int']);
				$nrocuota=$r310['ultcan_sdp']+1;
				$tipoprestamo=$r360['tipo'];
				$pos310=$r310['registro'];
				$sql_amor="insert into sgcaamor (fecha, codsoc, nropre, cedula, nombre, saldo, capital, interes, cuota, codpre, cuent_p, cuent_i, cuent_d, ip, nrocuota, proceso, tipo, pos310) values ('$fechadescuento', '$codigo', '$elnumero', '$micedula', '$nombre', '$saldo', '$capital', '$interes', '$la_cuota', '$tipo', '$cuent_p', '$cuent_i', '$cuent_d', '$ip','$nrocuota', 1, '$tipoprestamo', '$pos310')";
				$result2=mysql_query($sql_amor);
//				echo $sql_amor.'<br>';
			}
//			echo $sql_pre.'----'.$posicion.'<br>';
		}	// ($r360 = mysql_fetch_assoc($a_360))
	} // ($r310 = mysql_fetch_assoc($a_310))
}


?>

<?php include("pie.php");?>

</body></html>

