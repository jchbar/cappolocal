<?php
include("head.php");
include("paginar.php");
if (!$link OR !$_SESSION['empresa']) {
	include("noempresa.php");
	exit;
}
?>
<script type="text/javascript" src="ajaxpres.js"></script>
<?
/*
if ($_GET['emp'] == 1) {$_GET['n'] = 1;}

*/
if ($accion == 'Anadir') 
	$onload="onload=\"foco('lacedula')\"";
else
	if ($accion =='EscogeRetiro')
		$onload="onload=\"foco('ret_socio')\"";
	else 
		if ($accion == 'Buscar')
			$onload="onload=\"foco('elretiro')\"";
		else $onload="onload=\"foco('cedula')\"";
//		echo $onload;
?>
<body <?php if (!$bloqueo) {echo $onload;}?>>

<?php
$readonly=" readonly='readonly'";
include("arriba.php");
$menu61=1;include("menusizda.php");
$cedula = $_GET['cedula'];
$ip = $_SERVER['HTTP_CLIENT_IP'];
if (!$ip) {$ip = $_SERVER['REMOTE_ADDR'];}

if ($accion == 'Buscar')  {
	extract($_POST);
	$elcodigo = trim($_POST['elcodigo']);
	$lacedula = trim($_POST['cedula']);
// 	echo $lacedula. ' - ' .$elcodigo . ' - '.$accion;
	if ($lacedula) { //  != ' ') {
		$sql="SELECT * FROM sgcaf200 where ced_prof = '$lacedula'";
		$result=mysql_query($sql);
		$row= mysql_fetch_assoc($result);
		echo "<input type = 'hidden' value ='".$row['ced_prof']."' name='cedula'>"; 
		$cedula=$row['ced_prof'];
		$accion = 'Editar'; 

		$conta = $_GET['conta'];
		if (!$_GET['conta']) 
			$conta = 1;
		
		$estacedula=substr($cedula,0,4).'.'.substr($cedula,4,3).'.'.substr($cedula,7,3);
		$sql = "SELECT * FROM sgcaf310,sgcaf360 WHERE (cedsoc_sdp = '$estacedula' and stapre_sdp='A' and (! renovado)) and codpre_sdp=cod_pres ORDER BY f_soli_sdp DESC"." LIMIT ".($conta-1).", 10";
		$rs = mysql_query($sql);
// 		echo $sql;
		echo "<table class='basica 100 hover' width='750'><tr>";
		echo '<th colspan="3"></th><th width="80">Fecha</th><th width="100">Nro.Prestamo</th><th width="280">Tipo</th><th width="100">Monto</th><th width="100">Saldo</th><th width="80">NC</th><th width="80">CC</th></tr>';
//		echo '[ <a href="solpre.php?accion=Anadir">           Nuevo Socio</a> ]</th></tr>';

		if (pagina($numasi, $conta, 20, "Prestamos Activos", $ord)) {$fin = 1;}
// 		bucle de listado
		while($row=mysql_fetch_assoc($rs)) {
			echo "<tr>";

		echo "<td class='centro'><a href='extractoctas3.php?cuenta=".trim($row['cuent_pres']).'-'.substr(trim($row['codsoc_sdp']),1,4)."&datos=no&'><img src='imagenes/page_wizard.gif' width='16' height='16' border='0'  title='Mayor Analítico' alt='Mayor Analítico'/></a></td>";
		echo "<td class='centro'><a href='solpre.php?accion=Ver&cedula=".$cedula."&nropre=".$row['nropre_sdp']."'><img src='imagenes/page_user_dark.gif' width='16' height='16' border='0' title='Consultar' alt='Consultar' /></a></td>";
		echo "<td class='centro'>";
		if ($row['renovacion']>1)
			if ($row['ultcan_sdp'] >= $row['renovacion']) {
				echo "<a href='solpre.php?accion=Renovar&cedula=".$cedula."&nropre=".$row['nropre_sdp']."'>";
				echo "<img src='imagenes/action_refresh_blue.gif' width='16' height='16' border='0' title='Renovar' alt='Renovar' />";
				echo "</a>";
			}
/*
			else echo "<img src='imagenes/12-em-cross.png' width='16' height='16' border='0' />";
		else echo "<img src='imagenes/12-em-cross.png' width='16' height='16' border='0' />";
*/
			else echo ' ';
		else if ($row['renovacion'] == 1){ 
				echo "<a href='solpre.php?accion=ReAjustar&cedula=".$cedula."&nropre=".$row['nropre_sdp']."'>";
				echo "<img src='imagenes/icon_get_world.gif' width='16' height='16' border='0' title='ReAjustar' alt='ReAjustar' />";
				echo "</a>";
			}
			else echo ' ';

		echo "</td><td>";
		

//			echo "<a href='solpre.php?accion=Ver&cedula=".$row['cedsoc']."'&nropre=".$row['nropre_sdp'].">";
//			echo convertir_fechadmy($row['f_soli_sdp'])."</a></td>";
			echo convertir_fechadmy($row['f_soli_sdp'])."</td>";
			echo "<td class='centro'>";
			echo $row['nropre_sdp'];
			echo "</td>";
			echo "<td class='centro'>".$row['descr_pres']."</td>";
			echo "<td align='right'>";
			echo number_format($row['monpre_sdp'],2,'.',',');
			echo "</td>";
			echo "<td align='right'>".number_format(($row['monpre_sdp']-$row['monpag_sdp']),2,'.',',')."</td>";
//			echo "<td class='right'>".number_format($row['monpre_sdp']-$row['monpag_sdp'],2,'.',',')."</td>";
			echo "<td class='centro'>".number_format($row['nrocuotas'],0,'.',',')."</td>";
			echo "<td class='centro'>".number_format($row['ultcan_sdp'],0,'.',',')."</td>";
			echo "</tr>";
		}

		echo "</table>";
	}
//	echo "</div>";
}
		
if (!$accion) {
//	echo "<div id='div1'>";
	echo "<form action='solpre.php?accion=Buscar' name='form1' method='post'>";
    echo '  C&eacute;dula ';
	echo '<input name="cedula" type="text" id="cedula" value=""  size="10" maxlength="10" />';
//	echo '<input type="radio" name="qhacer" value="1" >Nuevo ' ;
//	echo '<input type="radio" name="qhacer" value="2" checked >Renovacion   ';
	echo "<input type = 'submit' value = 'Buscar'>";
	echo '</form>';
}
if ($accion == 'Ver') {
	echo "<div align='center' id='div1'>";
	$cedula=$_GET['cedula'];
	$nropre=$_GET['nropre'];
	mostrar_prestamo($cedula,$nropre);
	echo "</div>";
}

if ($accion == "Editar") {	// muestra datos para prestamo
	echo '<div id="div1">';
	$sql='SELECT * FROM sgcaf200 WHERE ced_prof= "'.$cedula.'"';
	$result = mysql_query($sql) or die ('Error 200-1 <br>'.$sql.'<br>'.mysql_error());
	echo "<input type = 'hidden' value ='".$cedula."' name='cedula'>";
	$temp = "";
	echo "<form enctype='multipart/form-data' action='solpre.php?accion=EscogePrestamo' name='form1' method='post' onsubmit='return valsoc(form1)'>";
	pantalla_prestamo($result,$cedula);
	$elstatus=$_SESSION['elstatus'];
	echo '<fieldset><legend>Información Para Prestamo </legend>';
	$sqlprestamos="";
	if ($_SESSION['disponibilidadprestamo'] > 0) {
		if (($elstatus == "ACTIVO") or ($elstatus == "JUBILA")) {
			$sqlprestamos.="select * from sgcaf360 where ";}
		else {
			echo '<h2>El socio NO tiene un estatus disponible para solicitar préstamos</h2>';
			echo '</fieldset>';
		}
	}
	else {
		$sqlprestamos="select * from sgcaf360 where (retab_pres = 0) and ";
		echo '<h2>El socio NO tiene disponibilidad para solicitar préstamos<br>Sin embargo puede solicitar aquellos que <em>no afectan </em>disponibilidad</h2>';
	}
	$sqlprestamos.="(tiempo < ".$_SESSION['tiempoactivo'];
	$sqlprestamos.=") order by cod_pres";

	echo '<td>Seleccione Tipo</td>';
   	echo '<td class="rojo">';
	echo '<select name="elprestamo" size="1">';
	echo $sqlprestamos;
	$resultado=mysql_query($sqlprestamos);
	while ($fila2 = mysql_fetch_assoc($resultado)) {
		echo '<option value="'.$fila2['cod_pres'].'">'.$fila2['cod_pres'].' - '.$fila2['descr_pres'].'</option>'; }
	echo '</select> *'; 
	echo '</td>';
	echo "<input type = 'submit' value = 'Nuevo Prestamo'></form>\n"; 
	echo '</fieldset>';
	echo '</div>';
}
if ($accion == "EscogePrestamo") {	// selecciono el tipo de prestamo
	echo '<div id="div1">';
	$cedula = $_POST['cedula'];
	$elprestamo = $_POST['elprestamo'];
/*
	$sql='SELECT * FROM sgcaf200 WHERE ced_prof= "'.$cedula.'"';
	$result = mysql_query($sql) or die ('Error 200-1 <br>'.$sql.'<br>'.mysql_error());
*/
	$temp = "";
	echo "<form enctype='multipart/form-data' action='solpre.php?accion=Solicitar name='form1' id='form1' method='post''>";
	//  onsubmit='return valpre(form1)
	$micedula=substr($cedula,0,4).'.'.substr($cedula,4,3).'.'.substr($cedula,7,4);
	$sql_360="select * from sgcaf360 where cod_pres='$elprestamo'";
	$a_360=mysql_query($sql_360);
	$r_360=mysql_fetch_assoc($a_360);
	$sql_310="select * from sgcaf310 where (cedsoc_sdp='$micedula') and (codpre_sdp='$elprestamo')";
	echo $sql_310;
	$a_310=mysql_query($sql_310);
	if ((! $r_360['masdeuno']) and (mysql_num_rows($a_310) >= 1))	
			echo '<h2>No puede tener mas de un préstamo de este tipo</h2>';
	else {
		pantalla_completar_prestamo($cedula,$elprestamo);
	}
	echo '</form>';
	echo '</div>';
}
if ($accion == "PreLiquidar") {	// selecciono el tipo de retiro
	echo '<div id="div1">';
	$cedula = $_POST['cedula'];
	$elretiro= $_POST['elretiro'];
	$_SESSION['lagestion']='S';
	echo 'Ubicando datos del afiliado<br>';
	// grabo en la 700, resto de la 200, genero el asiento (830, 820 y 810)
	$sql="select * from sgcaf710 where tipo='$elretiro'";
	$result=mysql_query($sql);
	$tiporetiro=mysql_fetch_assoc($result);
	$sql="select cod_prof, ape_prof, nombr_prof from sgcaf200 where ced_prof='$cedula'";
	$result=mysql_query($sql);
	$socio=mysql_fetch_assoc($result);
	$codigo=$socio['cod_prof'];
	$afiliado=trim($socio['ape_prof']). ' '.$socio['nombr_prof'];
	$fechahoy= date("Y-m-d h:i:s");
	$estacedula=substr($cedula,0,4).'.'.substr($cedula,4,3).'.'.substr($cedula,7,3);
	$rsocio=$_POST['ret_socio'];
	$raporte=$_POST['ret_empr'];
	$rvoluntario=$_POST['ret_volu'];
	$rdividendo=$_POST['ret_capi'];
	$motivo=$_POST['motivo'];
	$observa1=$_POST['observa1'];
	$observa2=$_POST['observa2'];
	$observa3=$_POST['observa3'];
	$monto=$rsocio+$raporte+$rvoluntario+$rdividendo;
	$elestado=($tiporetiro['porcentaje']==100?'S':'A');
	// grabo el retiro 
	$sql_acta="select * from sgcafact order by fecha desc limit 1";
	$las_actas=mysql_query($sql_acta);
	$el_acta=mysql_fetch_assoc($las_actas);
	$nroacta=$el_acta['acta'];
	$fechaacta=$el_acta['fecha'];
	$elasiento = date("ymd").$codigo;
	echo 'Guardando datos del retiro<br>';
	$ip = $_SERVER['HTTP_CLIENT_IP'];
	if (!$ip) {$ip = $_SERVER['REMOTE_ADDR'];}
	$sql="insert into sgcaf700 (codsoc, cedsoc, tiporeti, fechareti, montoreti, motivo, observa1, observa2, observa3, ret_ucla, ret_capu, ret_volu, ret_divi, estado, asiento, ip, nro_acta, fecha_acta) values 
	('$codigo','$estacedula','$elretiro','$fechahoy', $monto, '$motivo', '$observa1', '$observa2', '$observa3', $rsocio, $raporte, $rvoluntario, $rdividendo, '$elestado', '$elasiento', '$ip', '$nroacta', '$fechaacta')";
	// actualizo los ahorros
//	echo $sql.'<br>';
	$retirarlo='';
	$b = date("Y-m-d");
	if ($tiporetiro['porcentaje']==100)
		$retirarlo=", f_ret_capu='$b' ";
	echo 'Actualizando ahorros del afiliado<br>';
	mysql_query($sql) or die ("<p />El usuario $usuario no tiene permisos incluir el retiro <br>".mysql_error()."<br>".$sql);
	$sql="update sgcaf200 set hab_f_prof=hab_f_prof-$rsocio, hab_f_empr=hab_f_empr-$raporte, hab_f_extr=hab_f_extr-$rvoluntario, hab_f_capi=hab_f_capi-$rdividendo ".$retirarlo."where ced_prof='$cedula'";
	mysql_query($sql) or die ("<p />El usuario $usuario no tiene permisos modificar los ahorros <br>".mysql_error()."<br>".$sql);
//	echo $sql.'<br>';
	// los asientos contables
	echo "Generando encabezado contable <strong>$elasiento </strong> <br>";
	$sql = "INSERT INTO sgcaf830 (enc_clave, enc_fecha, enc_desco, enc_desc1, enc_debe, enc_haber, enc_item, enc_dif, enc_igual, enc_refer, enc_sw, enc_explic) VALUES ('$elasiento', '$b', '','',0,0,0,0,0,0,0,'')"; 
//	echo $sql.'<br>';
	if (!mysql_query($sql)) die ("El usuario $usuario no tiene permiso para añadir Asientos.<br>".$sql);
	$haber = $debe = 0;
	$referencia='';
	// cargo retiro de socio
	$debe = $rsocio;
	echo "Generando cargos del asiento <strong>$elasiento </strong>  <br>";
	if ($debe != 0) {
		$sql="select * from sgcaf000 where tipo='RetSoc'";
//	echo $sql.'<br>';
		$result=mysql_query($sql); // or die ("<p />El usuario $usuario no pudo conseguir los retiros de ahorros <br>".mysql_error()."<br>".$sql);
		$cuentas=mysql_fetch_assoc($result);
		$cuenta1=$cuentas['nombre'];
		agregar_f820($elasiento, $b, '+', $cuenta1, 'Retiro de Haberes '.$afiliado, $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
	}
	// cargo retiro de aporte
	$debe = $raporte;
	if ($debe != 0) {
		$sql="select * from sgcaf000 where tipo='RetApo'";
//	echo $sql.'<br>';
		$result=mysql_query($sql); //  or die ("<p />El usuario $usuario no pudo conseguir los retiros de aportes <br>".mysql_error()."<br>".$sql);
		$cuentas=mysql_fetch_assoc($result);
		$cuenta1=$cuentas['nombre'];
		agregar_f820($elasiento, $b, '+', $cuenta1, 'Retiro de Haberes '.$afiliado, $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
	}
	// cargo retiro de voluntario
	$debe = $rvoluntario;
	if ($debe != 0) {
		$sql="select * from sgcaf000 where tipo='RetVol'";
//	echo $sql.'<br>';
		$result=mysql_query($sql); //  or die ("<p />El usuario $usuario no pudo conseguir los retiros de voluntarios <br>".mysql_error()."<br>".$sql);
		$cuentas=mysql_fetch_assoc($result);
		$cuenta1=$cuentas['nombre'];
		agregar_f820($elasiento, $b, '+', $cuenta1, 'Retiro de Haberes '.$afiliado, $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
	}
	// cargo retiro de dividendos
	$debe = $rdividendo;
	echo "Generando abonos del asiento <strong>$elasiento </strong> <br>";
	if ($debe != 0) {
		$sql="select * from sgcaf000 where tipo='RetDiv'";
//	echo $sql.'<br>';
		$result=mysql_query($sql); //  or die ("<p />El usuario $usuario no pudo conseguir los retiros de Dividendos <br>".mysql_error()."<br>".$sql);
		$cuentas=mysql_fetch_assoc($result);
		$cuenta1=$cuentas['nombre'];
		agregar_f820($elasiento, $b, '+', $cuenta1, 'Retiro de Haberes '.$afiliado, $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
	}
	// debo verificar si es parcial o total 
	// abonar los prestamos (y fianzas) si es total y restar del monto total
	// y llevarlo a cuenta por pagar
	$restando=0;
	if ($tiporetiro['porcentaje']==100)
	{
		// busco los prestamos para cancelarlos
		$sql="select * from sgcaf310,sgcaf360 where (cedsoc_sdp='$estacedula' and stapre_sdp='A' and (! renovado)) and (codpre_sdp=cod_pres)";
//	echo $sql.'<br>';
		$prestamos=mysql_query($sql); //  or die ("<p />El usuario $usuario no pudo conseguir los retiros de Dividendos <br>".mysql_error()."<br>".$sql);
		while($prestamo=mysql_fetch_assoc($prestamos)) {
			$cuenta=trim($prestamo['cuent_pres']).'-'.substr($codigo,1,4);
			$saldo=buscar_saldo_f810($cuenta); 	//			$prestamo['monpre_sdp']-$prestamo['monpag_sdp'];
			if ($saldo < 0) {
				$reintegros += $saldo;
				$debe = $saldo;
				agregar_f820($elasiento, $b, '+', $cuenta, 'Canc. x Retiro de Haberes '.$afiliado, $debe, $haber, 0,$ip,0,$referencia,'','S',0);
			}
			else {
				$restando+=$saldo;
				$debe = $saldo;
				agregar_f820($elasiento, $b, '-', $cuenta, 'Canc. x Retiro de Haberes '.$afiliado, $debe, $haber, 0,$ip,0,$referencia,'','S',0);
			}
			if ($prestamo['int_dif']==1)
			{
				$cuenta=trim($prestamo['cuent_int']).'-'.substr($codigo,1,4);
				$saldo=buscar_saldo_f810($cuenta); 	//			$prestamo['monpre_sdp']-$prestamo['monpag_sdp'];
				$debe = $saldo;
				agregar_f820($elasiento, $b, '+', $cuenta, 'Canc. x Retiro de Haberes '.$afiliado, $debe, $haber, 0,$ip,0,$referencia,'','S',0);
				$restando-=$saldo;
			}
		}		
	}

	// cargo retiro de dividendos
	$debe = $monto-$restando+$reintegros;
	$neto_cheque = $debe;
	if ($debe != 0) {
		$sql="select * from sgcaf000 where tipo='CtaSocxPag'";
//	echo $sql.'<br>';
		$result=mysql_query($sql); // or die ("<p />El usuario $usuario no pudo conseguir la cuenta x pagar<br>".mysql_error()."<br>".$sql);
		$cuentas=mysql_fetch_assoc($result);
		$cuenta1=$cuentas['nombre'].$codigo;
		agregar_f820($elasiento, $b, '-', $cuenta1, 'Retiro de Haberes '.$afiliado, $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
	}
	$sql="update sgcaf700 set netcheque = $debe where cedsoc = '$estacedula' and montoreti=$monto";
//	echo $sql;
	$resultado=mysql_query($sql);
	$_SESSION['elasiento']=$elasiento;
//	echo $elasiento;
	$sql="update sgcafact set eje_ret=eje_ret + $monto where acta ='$nroacta'";
	$resultado=mysql_query($sql);
	echo 'Preparando para la impresion<br>';
	echo "<a target=\"_blank\" href=\"retiropdf.php?cedula=$cedula\" onClick=\"info.html\', \'\',\'width=250, height=190\')\">Imprimir Retiro </a>"; 
//	echo 'la cedula '.$cedula;
// 	realizar la impresion de planilla y los asietnos contables a la cuenta por pagar
	echo '</div>';
}

if ($accion == "Liquidar") {	// selecciono el tipo de retiro
	echo '<div id="div1">';
	$cedula = $_POST['cedula'];
	$_SESSION['lagestion']='S';
	echo "<form enctype='multipart/form-data' action='solpre.php?accion=HC' name='form1' method='post'>";
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
if ($accion == "HC") {	// selecciono el tipo de retiro
	echo '<div id="div1">';
	$cedula = $_POST['cedula'];
	$elcheque= $_POST['elcheque'];
	$_SESSION['lagestion']='S';
	echo "Asignando cheque<br>";
	$cheques_sql="select * from sgcaf844,sgcaf843 where ((ban_che='$elcheque') and (sta_che='L')) and (cod_banco ='$elcheque') limit 1";
//	echo $cheques_sql;
	$cheques=mysql_query($cheques_sql);	// busco el primer cheque disponible de ese banco 
	if (mysql_num_rows($cheques) > 0) {
		$cheque=mysql_fetch_assoc($cheques);
		$elnumero=$cheque['nro_che'];
		$laplaza=trim($cheque['nombre_ban']) . ' / '.$cheque['nro_cta_ba'];
		echo "El número de cheque asignado es $elnumero<br>";
		$estacedula=substr($cedula,0,4).'.'.substr($cedula,4,3).'.'.substr($cedula,7,3);
		$sql="select *, date_format(fechareti,'%d/%m/%y') AS solicitado, date_format(fecha_acta,'%d/%m/%y') AS fechaacta from sgcaf700,sgcaf200 where (cedsoc='$estacedula' and estado='S') and ('$cedula'=ced_prof)";
	//	echo $sql;
		$result=mysql_query($sql); 	// busco el registro	
		$retiro=mysql_fetch_assoc($result);
		$numeroderegistro=$retiro['registro'];
		$beneficiario=trim($retiro['ape_prof']). ' '.trim($retiro['nombr_prof']);
		$monto=$retiro['netcheque'];
		$codigo=$retiro['codsoc'];
		$hoy= date("Y-m-d");
		echo "Creando encabezado de cheque<br>";
		$concepto='P/Registrar cancelación de haberes a: '.$beneficiario.' por retiro solicitado el '.$retiro['solicitado'].' S/acta Nro. ';
		$concepto.=$retiro['nro_acta'].' realizada en fecha '.$retiro['fechaacta'];
	//	echo $concepto.'<br>';
		$registro="insert into sgcaf840 (mche_orden, mche_fecha, mche_nombr, mche_monto, mche_descr, mche_statu, mche_banco, mche_prest) ";
		$registro.="VALUES ('$elnumero','$hoy','$beneficiario',$monto,'$concepto','L','$elcheque','XXXX')";
	//	echo $registro;
		if (mysql_query($registro)){
			echo "Creando cargo en el cheque<br>";
			$sql="select * from sgcaf000 where tipo='CtaSocxPag'";
			$result=mysql_query($sql); // or die ("<p />El usuario $usuario no pudo conseguir la cuenta x pagar<br>".mysql_error()."<br>".$sql);
			$cuentas=mysql_fetch_assoc($result);
			$cuenta1=$cuentas['nombre'].$codigo;
			$registro="insert into sgcaf841 (mche_orden, mche_cuent, mche_debcr, mche_descr, mche_monto1, mche_monto2,mche_monto,mche_banco";
			$registro.=") VALUES ('$elnumero','$cuenta1','+','Cancel. Ret. Hab. del ".$retiro['solicitado']."', ";
			$registro.="$monto, 0, 0, '$elcheque')";
			echo "Creando abono en el cheque<br>";
				$_SESSION['elcheque']=$elnumero; 
			$_SESSION['laplaza']=$laplaza; 
	//		echo $registro;
			if (mysql_query($registro)){
				$sql2="select * from sgcaf843 where cod_banco = '$elcheque'";
				$cheque=mysql_query($sql2);
				$cheque=mysql_fetch_assoc($cheque);
				$cuenta1=$cheque['cue_banco'];
				$registro="insert into sgcaf841 (mche_orden, mche_cuent, mche_debcr, mche_descr, mche_monto1, mche_monto2,mche_monto,mche_banco";
				$registro.=") VALUES ('$elnumero','$cuenta1','-','".$beneficiario."', ";
				$registro.="0,$monto, 0, '$elcheque')";
	//			echo $registro;
				if (mysql_query($registro)){}
				else echo '<h2>Error al generar el abono del cheque</h2>';
			}
			else echo '<h2>Error al generar el cargo del cheque</h2>';
		}
		else echo '<h2>Error al generar encabezado del cheque</h2>';
		$_SESSION['elasiento']=$retiro['asiento'];
		$sql="update sgcaf844 set sta_che = 'G' where nro_che = '$elnumero' and ban_che = '$elcheque'";
		mysql_query($sql);
		$sql="update sgcaf700 set estado = 'A', banco = '$elcheque', nro_cheque='$elnumero' where registro= $numeroderegistro ";
	//	echo $sql;
		mysql_query($sql);
		echo "<a target=\"_blank\" href=\"retirapdf.php?cedula=$cedula\" onClick=\"info.html\', \'\',\'width=250, height=190\')\">Imprimir Aprobación de Retiro </a>"; 
	}
	else echo "<h2>No se puede aprobar el retiro porque no hay cheques disponibles. Debe realizar el proceso de cargar cheques</h2>";
	echo '</div>';
}
?>

<?php include("pie.php");?>
</body></html>


<?php

function buscar_saldo_f810($cuenta)
{
	$sql_f810="select cue_saldo from sgcaf810 where cue_codigo='$cuenta'";
//	echo $sql_f810;
	$lacuentas=mysql_query($sql_f810); //  or die ("<p />El usuario $usuario no pudo conseguir el saldo contable<br>".mysql_error()."<br>".$sql);
	$lacuenta=mysql_fetch_assoc($lacuentas);
	$saldoinicial=$lacuenta['cue_saldo'];
	
	$sql_f820="select com_monto1, com_monto2 from sgcaf820 where com_cuenta='$cuenta' order by com_fecha";
//	echo $sql_f820;
	$lacuentas=mysql_query($sql_f820); //  or die ("<p />El usuario $usuario no pudo conseguir los movimientos contables<br>".mysql_error()."<br>".$sql);
	while($lascuenta=mysql_fetch_assoc($lacuentas)) {
		$saldoinicial+=$lascuenta['com_monto1'];
//		echo $saldoinicial.'<br>';
		$saldoinicial-=$lascuenta['com_monto2'];
//		echo $saldoinicial.'<br>';
	}
return $saldoinicial;
}

//--------------------------------------------
function pantalla_completar_prestamo($cedula,$tipo)
{ 
	$deci=$_SESSION['deci'];
	$sep_decimal=$_SESSION['sep_decimal'];
	$sep_miles=$_SESSION['sep_miles'];
	$sql_200="select * from sgcaf200 where ced_prof='$cedula'";
	$a_200=mysql_query($sql_200);
	$r_200=mysql_fetch_assoc($a_200);
	$laparte=$r_200['cod_prof'];
	$micedula=substr($cedula,0,4).'.'.substr($cedula,4,3).'.'.substr($cedula,7,4);
	// determino nuevo numero de prestamo
	$sql_310="select nropre_sdp from sgcaf310 where (cedsoc_sdp='$micedula') and (substr(nropre_sdp,1,5)='$laparte') order by nropre_sdp desc limit 1";
	$a_310=mysql_query($sql_310);
	$elnumero=mysql_fetch_assoc($a_310);
	$elnumero=substr($elnumero['nropre_sdp'],6,3);
	$elnumero=$elnumero+1;
	$elnumero=$laparte.ceroizq($elnumero,3);
	// fin de generar nuevo numero
	$sql_360="select * from sgcaf360 where cod_pres='$tipo'";
	$a_360=mysql_query($sql_360);
	$r_360=mysql_fetch_assoc($a_360);
	$sql_310="select * from sgcaf310, sgcaf360 where cedsoc_sdp='$micedula' and nropre_sdp='$nropre'";
	$a_310=mysql_query($sql_310);
	$r_310=mysql_fetch_assoc($a_310);
	echo '<fieldset><legend>'.trim($r_360['descr_pres']). ' / '.trim($r_200['ape_prof']). ', '.trim($r_200['nombr_prof']).' / ';
	echo $r_200['ced_prof'].' / '.$r_200['cod_prof'].'</legend>';
	echo '<table class="basica 100 hover" width="400" border="1">';
	echo '<tr>';
    echo '<td width="250">Tasa de Interes </td><td width="100" align="right">'.number_format($r_360['interes_sd'],$deci,$sep_decimal,$sep_miles).'%</td>';
    echo '<td width="250">Monto Solicitado </td><td width="100" align="right">';
	// -----------
	echo '<input align="right" name="monpre_sdp" type="text" id="monpre_sdp" size="12" maxlength="12" value="';
	echo number_format($_SESSION['disponibilidadprestamo'],2,'.',''); 
	echo '"/>';
//	---------------
	echo '</td></tr>';
	echo '<tr>';
	$hoy=date("d/m/Y", time());
	echo '<td>Fecha de solicitud </td><td>'.$hoy.'</td>';
    echo '<td>Monto Pagado </td><td  align="right">'.number_format(0,$deci,$sep_decimal,$sep_miles).'</td></tr>';
	echo '<tr>';
	echo '<td>1er Descuento </td><td>'.convertir_fechadmy($r_310['f_1cuo_sdp']).'</td>';
    echo '<td>Saldo </td><td  align="right">'.number_format(0,$deci,$sep_decimal,$sep_miles).'</td></tr>';
	echo '<tr>';
	echo '<td>CC/NC</td><td>'.'0'.' de ';
	echo '<select name="lascuotas" size="1">';
	for ($laposicion=$r_360['n_cuo_pres'];$laposicion >= 1;$laposicion--) {
		echo '<option value="'.$laposicion.($posicion==$r_360['n_cuo_pres']?" selected ":"").'" >'.$laposicion.' </option>'; }
		// 
	echo '</select>'; 
	echo '</td>';
    echo '<td>Cuota Original </td><td  align="right">'.number_format($r_310['cuota'],$deci,$sep_decimal,$sep_miles).'</td></tr>';
	echo '<tr>';
	$sql_acta="select * from sgcafact order by fecha desc limit 1";
	$las_actas=mysql_query($sql_acta);
	$el_acta=mysql_fetch_assoc($las_actas);
	$nroacta=$el_acta['acta'];
	$fechaacta=$el_acta['fecha'];
	$elasiento = date("ymd").$codigo;
	echo '<td>Acta / Fecha </td><td>'.$nroacta.' del '.convertir_fechadmy($fechaacta).'</td>';
	echo '<td>Cuota Modificada </td><td align="right">'.number_format($r_310['cuota_ucla'],$deci,$sep_decimal,$sep_miles).'</td></tr>';
	echo '<tr><td align="center" colspan="4">';
	echo "<img src='imagenes/page_wizard.gif' width='116' height='116' border='0' />";
	echo '</td></tr>';
//	echo '<td><div id="contenedor">Valor</div></td>';

	echo '<td> '; ?>
	<input type="button" name="calculo" value="Calcular" onClick="Cargarcontenido('mostrarpr.php','c=3', 'form1', 'contenido2')">	
	<?php 
	echo '</td> ';

	// <a title="Calcular" href="javascript:Cargarcontenido('mostrarpr.php', 'c=3', 'form1', 'contenido2')">Calcular</a>
	echo '</td>';
	echo '</table>';
	echo '</fieldset>';
	echo '</div>';
	echo '</form>';
	echo '<div id="contenido2"></div>';
}

//----------------------------------------------
function pantalla_prestamo($result,$cedula)
{
	$deci=$_SESSION['deci'];
	$sep_decimal=$_SESSION['sep_decimal'];
	$sep_miles=$_SESSION['sep_miles'];
	$fila = mysql_fetch_assoc($result);
	echo "<input type = 'hidden' value ='".$fila['ced_prof']."' name='cedula'>";
	if ($accion == 'Editar') { $lectura = 'readonly = "readonly"'; $activada="disabled" ; } else {$lectura=''; $activada='';}
	if ($accion == 'Anadir') {
		$elcodigo=nuevo_codigo(); 
		$ingreso=date("d/m/Y", time());
		}
	else  $elcodigo=$fila['cod_prof'];
	$lectura = 'readonly = "readonly"'; $activada="disabled" ; 
//	<form id="form1" name="form1" method="post" action="">
?>
  <label><fieldset><legend>Información Personal </legend>
  <table width="639" border="1">
    <tr>
		<td colspan="2" width="100" >C&oacute;digo <?php echo '<strong>'.$elcodigo.'</strong>'; ?></td>
 		<td colspan="1" width="130">Cédula <?php echo '<strong>'.$fila['ced_prof'].'</strong>';?></td>
		<td colspan="3" width="127">Socio <?php echo '<strong>'.$fila['ape_prof'].' '.$fila['nombr_prof'] .'</strong>'?></td>
	</tr>
	<tr>
		<td colspan="2" width="127" scope="col">Fecha de Ingreso 
		<strong><?php echo convertir_fechadmy($fila['f_ing_capu']) ?></strong></td>
		<td>Estatus
		<strong><?php echo $fila['statu_prof'] ?></strong></td>
	    <td align="center" colspan="2" class="<?php echo ($disponible<=0)?'rojo':'azul' ?>" >Disponibilidad Neta
		<?php 
			$ahorros=ahorros($cedula);
			$afectan=afectan($cedula);
			$noafectan=noafectan($cedula);
			$sql='select * from sgcaf200 where ced_prof="'.$cedula.'"';
			$result=mysql_query($sql);
			$fila = mysql_fetch_assoc($result);
			$fianzas=fianzas($fila['cod_prof']);
			$disponible=disponibilidad($ahorros,$afectan,$noafectan,$fianzas); ?>
			<strong><?php 
	  		if ($disponible<=0)
				{
					$imagen='24-em-cross.png';
					$cuento_mostrar='Disponibilidad Negativa';
					$cuento_interno='disp_neg';
				}
			else {
					$imagen='24-em-check.png';
					$cuento_mostrar='Disponibilidad Positiva';
					$cuento_interno='disp_pos';
			}
			echo '<img src="imagenes/'.$imagen.'" width="22" height="19" alt="'.$cuento_mostrar.'" longdesc="'.$cuento_interno.'" />';
			echo number_format($disponible,$deci,$sep_decimal,$sep_miles); 
			echo '<img src="imagenes/'.$imagen.'" width="22" height="19" alt="'.$cuento_mostrar.'" longdesc="'.$cuento_interno.'" />';
			$_SESSION['disponibilidadprestamo']=$disponible; 
			$_SESSION['elstatus']=strtoupper($fila['statu_prof']);
			$hoy=date("Y-m-d", time());
			$pasados=(dias_pasados($fila['f_ing_capu'],$hoy)/30) ;
		   $_SESSION['tiempoactivo']=intval($pasados);
		 ?></strong></td>
	</tr>
</table>
</fieldset> 

<?php
}
function mostrar_prestamo($cedula,$nropre)
{
	$deci=$_SESSION['deci'];
	$sep_decimal=$_SESSION['sep_decimal'];
	$sep_miles=$_SESSION['sep_miles'];
	$sql_200="select * from sgcaf200 where ced_prof='$cedula'";
	$a_200=mysql_query($sql_200);
	$r_200=mysql_fetch_assoc($a_200);
	$micedula=substr($cedula,0,4).'.'.substr($cedula,4,3).'.'.substr($cedula,7,4);
	$sql_310="select * from sgcaf310, sgcaf360 where cedsoc_sdp='$micedula' and nropre_sdp='$nropre'";
	$a_310=mysql_query($sql_310);
	$r_310=mysql_fetch_assoc($a_310);
	echo '<fieldset><legend>'.trim($r_310['descr_pres']). ' / '.trim($r_200['ape_prof']). ', '.trim($r_200['nombr_prof']).' / ';
	echo $r_310['cedsoc_sdp'].' / '.$r_310['codsoc_sdp'].'</legend>';
	echo '<table class="basica 100 hover" width="400" border="1">';
	echo '<tr>';
    echo '<td width="250">Tasa de Interes </td><td width="100" align="right">'.number_format($r_310['interes_sd'],$deci,$sep_decimal,$sep_miles).'%</td>';
    echo '<td width="250">Monto Solicitado </td><td width="100" align="right">'.number_format($r_310['monpre_sdp'],$deci,$sep_decimal,$sep_miles).'</td></tr>';
	echo '<tr>';
	echo '<td>Fecha de solicitud </td><td>'.convertir_fechadmy($r_310['f_soli_sdp']).'</td>';
    echo '<td>Monto Pagado </td><td  align="right">'.number_format($r_310['monpag_sdp'],$deci,$sep_decimal,$sep_miles).'</td></tr>';
	echo '<tr>';
	echo '<td>1er Descuento </td><td>'.convertir_fechadmy($r_310['f_1cuo_sdp']).'</td>';
    echo '<td>Saldo </td><td  align="right">'.number_format($r_310['monpre_sdp']-$r_310['monpag_sdp'],$deci,$sep_decimal,$sep_miles).'</td></tr>';
	echo '<tr>';
	echo '<td>CC/NC</td><td>'.$r_310['ultcan_sdp'].' de '.$r_310['nrocuotas'].'</td>';
    echo '<td>Cuota Original </td><td  align="right">'.number_format($r_310['cuota'],$deci,$sep_decimal,$sep_miles).'</td></tr>';
	echo '<tr>';
	echo '<td>Acta / Fecha </td><td>'.$r_310['nro_acta'].' del '.$r_310['fecha_acta'].'</td>';
	echo '<td>Cuota Modificada </td><td align="right">'.number_format($r_310['cuota_ucla'],$deci,$sep_decimal,$sep_miles).'</td></tr>';
	echo '<tr><td align="center" colspan="4">';
	echo "<img src='imagenes/page_wizard.gif' width='116' height='116' border='0'  title='Mayor Analítico' alt='Mayor Analítico'/>";
	echo '</td></tr>';
	echo '</table>';
	echo '</fieldset>';
}	

?>
