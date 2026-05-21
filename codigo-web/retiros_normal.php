<?php
include("head.php");
include("paginar.php");

if (!$link OR !$_SESSION['empresa']) {
	include("noempresa.php");
	exit;
}
/*
if ($_GET['emp'] == 1) {$_GET['n'] = 1;}

*/
if ($accion == 'Anadir') 
	$onload="onload=\"foco('lacedula')\"";
else
	if ($accion =='EscogeRetiro')
		$onload="onload=\"foco('ret_socio')\"";
	else 
		if ($accion == 'r')
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
		$sql = "SELECT *,date_format(fechareti,'%Y-%m-%d') AS fecha_reti  FROM sgcaf700 WHERE cedsoc = '$estacedula' ORDER BY fechareti DESC"." LIMIT ".($conta-1).", 5";
		$rs = mysql_query($sql);
// 		echo $sql;
		echo "<table class='basica 100 hover' width='650'><tr>";
		echo '<th>Fecha</th><th>Dias<br>Transcurridos</th><th>Monto</a></th><th>Observacion</th></tr>';
//		echo '[ <a href="retiros.php?accion=Anadir">           Nuevo Socio</a> ]</th></tr>';

		if (pagina($numasi, $conta, 20, "Retiros Realizados", $ord)) {$fin = 1;}
		$lagestion='X';
		$pasadas=0;

// 		bucle de listado

		while($row=mysql_fetch_assoc($rs)) {
			echo "<tr>";
			echo "<td class='centro'>";
			echo "<a href='retiros.php?accion=Ver&cedula=".$row['cedsoc']."'&fecha=".$row['fechareti'].">";
			echo convertir_fechadmy($row['fechareti'])."</a></td>";
			$hoy=date("Y-m-d", time());
			echo '<td align="center" class=" ';
			$losdias=dias_pasados($row['fecha_reti'],$hoy);
			if ($losdias <= 365)
				echo 'rojo';
			else if ($losdias > 365 and $losdias < 730)
				echo 'azul';
			echo '">'.dias_pasados($row['fecha_reti'],$hoy).'</td>' ;

			echo "<td align='right'>";
			echo number_format($row['montoreti'],2,'.',',');
			echo "</td>";
			echo "<td class='centro'>";
			echo $row['observa1'];
			$ultimo=$row['tiporeti'];
			echo "</td>";
			$pasadas++;
			if ($pasadas == 1) {
				$lagestion=$row['estado']; 
				$_SESSION['lagestion']=$lagestion; 
				}
			echo "</tr>";
		}

		echo "</table>";
		if ($_SESSION['lagestion']=='S') $accion = 'Liquidar';
		}
//	echo "</div>";
}
		
if (!$accion) {
//	echo "<div id='div1'>";
	echo "<form action='retiros.php?accion=Buscar' name='form1' method='post'>";
    echo '  C&eacute;dula ';
	echo '<input name="cedula" type="text" id="cedula" value=""  size="10" maxlength="10" />';
	echo "<input type = 'submit' value = 'Buscar'>";
	echo '</form>';
}

if ($accion == "Editar") {	// muestra datos para retiro
	echo '<div id="div1">';
	$sql='SELECT * FROM sgcaf200 WHERE ced_prof= "'.$cedula.'"';
	$result = mysql_query($sql) or die ('Error 200-1 <br>'.$sql.'<br>'.mysql_error());
//	$fila = mysql_fetch_assoc($result);
// 	echo "<input type = 'hidden' value ='".$fila['ced_prof']."' name='cedula'>";
	echo "<input type = 'hidden' value ='".$cedula."' name='cedula'>";
	$temp = "";
//	echo "<form enctype='multipart/form-data' action='retiros.php?accion=EscogeRetiro' name='form1' method='post' onsubmit='return valret1(form1)'>";
	echo "<form enctype='multipart/form-data' action='retiros.php?accion=EscogeRetiro' name='form1' method='post'>";
	pantalla_socio($result,$accion,'D');
	echo '<fieldset><legend>Información Para Retiro ('.($lagestion=='S'?'Aprobación':'Solicitud').') </legend>';
	if ($_SESSION['disponibleretiro'] > 0) {
	    echo '<td>Seleccione Tipo</td>';
    	echo '<td class="rojo">';
		$elstatus=$fila['statu_prof'];
		echo '<select name="elretiro" size="1">';
		$sql="select * from sgcaf710 order by descri";
		$elultimo=$ultimo;
		$resultado=mysql_query($sql);
		while ($fila2 = mysql_fetch_assoc($resultado)) {
			echo '<option value="'.$fila2['tipo'].'" '.(($elultimo==$fila2['tipo'])?'selected':'').'>'.$fila2['descri'].'</option>';}
 		echo '</select> '; 
		echo '*</td>';
		echo '<td>Monto para Retiro';
		echo "<input type='text' value ='".number_format($_SESSION['disponibleretiro'],2,'.','')."' name='mretirar' id='mretirar' size='12' maxlength='12'".' '."/>*";
		echo "<input type = 'hidden' value ='".number_format($_SESSION['disponibleretiro'],2,'.','')."' name='moretiro' id='moretiro'>";
		echo "<input type = 'submit' value = 'Continuar'></form>\n";
		echo '</fieldset>';
	}
	else {
		echo '<h2>El socio posee disponibilidad negativa. NO se puede procesar retiros</h2>';
		echo '</fieldset>';
	}
	echo '</div>';
}
if ($accion == "EscogeRetiro") {	// selecciono el tipo de retiro
	echo '<div id="div1">';
	$cedula = $_POST['cedula'];
	$elretiro= $_POST['elretiro'];
	$_SESSION['elretiro']=$elretiro;
	$lagestion=$_SESSION['lagestion']; 
//	echo 'la gest='.$lagestion;
//	echo 'la cedula '.$cedula;
	$sql='SELECT * FROM sgcaf200 WHERE ced_prof= "'.$cedula.'"';
	$result = mysql_query($sql) or die ('Error 200-1 <br>'.$sql.'<br>'.mysql_error());
	$temp = "";
	echo "<form enctype='multipart/form-data' action='retiros.php?accion=".($lagestion=='S'?'Liquidar':'PreLiquidar')."' name='form1' method='post' onsubmit='return valret(form1)'>";
	$sql2="select * from sgcaf710 where tipo='$elretiro' order by descri";
	$resultado=mysql_query($sql2);
	pantalla_socio($result,$accion,'R');
//	echo 'la gestion'.$lagestion;
	echo "<input type = 'hidden' value ='".$cedula."' name='cedula'>";
	echo "<input type = 'hidden' value ='".$elretiro."' name='elretiro'>";
	$fila2 = mysql_fetch_assoc($resultado);
	echo '<fieldset><legend>Información Para '.$fila2['descri']. ' ('.($lagestion=='S'?'Aprobación/Liquidar':'Solicitud/Preliquidar').') </legend>';
	pantalla_retiro($sql2,$sql);
	echo '</fieldset>';
	echo "<br><input type = 'submit' value = 'Continuar'></form>\n";
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
	$rsocio=$_POST['ohab_prof']; // $_POST['ret_socio'];
	$raporte=$_POST['ohab_empr']; // $_POST['ret_empr'];
	$rvoluntario=$_POST['ohab_extr'];  //$_POST['ret_volu'];
	$rdividendo=$_POST['ohab_capi'];  //$_POST['ret_opsu'];
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

	if ($tiporetiro['porcentaje']==100) 
	{
		$sql="select date_sub('$fechaacta',INTERVAL 1 DAY) as fecha";
		$rsql=mysql_query($sql);
		$asql=mysql_fetch_assoc($rsql);
		$fechaacta=($asql['fecha']);
	}

	$elasiento = date("ymd").$codigo;
	//echo $elasiento; 
	echo 'Guardando datos del retiro<br>';
	$ip = $_SERVER['HTTP_CLIENT_IP'];
	if (!$ip) {$ip = $_SERVER['REMOTE_ADDR'];}
	$sql="insert into sgcaf700 (codsoc, cedsoc, tiporeti, fechareti, montoreti, motivo, observa1, observa2, observa3, ret_ucla, ret_capu, ret_volu, ret_opsu, estado, asiento, ip, nro_acta, fecha_acta) values 
	('$codigo','$estacedula','$elretiro','$fechahoy', $monto, '$motivo', '$observa1', '$observa2', '$observa3', $rsocio, $raporte, $rvoluntario, $rdividendo, '$elestado', '$elasiento', '$ip', '$nroacta', '$fechaacta')";
	// actualizo los ahorros
//	echo $sql.'<br>';
	$retirarlo='';
	$b = date("Y-m-d");
	if ($tiporetiro['porcentaje']==100)
		$retirarlo=", f_ret_capu='$b' ";
	echo 'Actualizando ahorros del afiliado<br>';
	mysql_query($sql) or die ("<p />El usuario $usuario no tiene permisos incluir el retiro <br>".mysql_error()."<br>".$sql);
	$sql="update sgcaf200 set hab_f_prof=hab_f_prof-$rsocio, hab_f_empr=hab_f_empr-$raporte, hab_f_extr=hab_f_extr-$rvoluntario, hab_opsu=hab_opsu-$rdividendo ".$retirarlo."where ced_prof='$cedula'";
	mysql_query($sql) or die ("<p />El usuario $usuario no tiene permisos modificar los ahorros <br>".mysql_error()."<br>".$sql);
//	echo $sql.'<br>';
	// los asientos contables
	echo "Generando encabezado contable <strong><a target=\"_blank\" href='editasi2.php?asiento=$elasiento'>$elasiento </a></strong> <br>";
	
	$sql = "INSERT INTO sgcaf830 (enc_clave, enc_fecha, enc_desco, enc_desc1, enc_debe, enc_haber, enc_item, enc_dif, enc_igual, enc_refer, enc_sw, enc_explic) VALUES ('$elasiento', '$b', '','',0,0,0,0,0,0,0,'')"; 
//	echo $sql.'<br>';
	if (!mysql_query($sql)) die ("El usuario $usuario no tiene permiso para añadir Asientos.<br>".$sql);
	$haber = $debe = 0;
	$referencia='';
	// cargo retiro de socio
	$debe = $rsocio;
	echo "Generando cargos del asiento <strong><a target=\"_blank\" href='editasi2.php?asiento=$elasiento'>$elasiento </a></strong> <br>";
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
	echo "Generando abonos del asiento <strong><a target=\"_blank\" href='editasi2.php?asiento=$elasiento'>$elasiento </a></strong> <br>";
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
/*
		$sql="select * from sgcaf310, sgcaf360 where (cedsoc_sdp='$estacedula' and stapre_sdp='A' and (! renovado)) and (codpre_sdp=cod_pres)";
//	echo $sql.'<br>';
//	die('espero');
		$prestamos=mysql_query($sql); //  or die ("<p />El usuario $usuario no pudo conseguir los retiros de Dividendos <br>".mysql_error()."<br>".$sql);
*/
		$sql="select cue_codigo from sgcaf810 where right(cue_codigo,4)='".substr($codigo,1,4)."' order by cue_codigo";
//	echo $sql.'<br>';
//	die('espero');
		$prestamos=mysql_query($sql); //  or die ("<p />El usuario $usuario no pudo conseguir los retiros de Dividendos <br>".mysql_error()."<br>".$sql);
		echo '<strong>Cancelando prestamos... <br></strong>';
		while($prestamo=mysql_fetch_assoc($prestamos)) {
			$cuenta=trim($prestamo['cue_codigo']); // .'-'.substr($prestamo['codsoc_sdp'],1,4);
			$cuent2=substr($cuenta,0,16);
			$sql810="select cue_nombre from sgcaf810 where cue_codigo='$cuent2'" ;
			$r810=mysql_query($sql810);
			$f810=mysql_fetch_assoc($r810);
			$saldo=buscar_saldo_f810($cuenta); 	//			$prestamo['monpre_sdp']-$prestamo['monpag_sdp'];
			if ($saldo != 0)
			{
			if ($saldo < 0) {
				$reintegros += abs($saldo);
				$debe = abs($saldo);
				agregar_f820($elasiento, $b, '+', $cuenta, 'Canc. x Retiro de Haberes '.$afiliado, $debe, $haber, 0,$ip,0,$referencia,'','S',0);
				$sql701="INSERT INTO sgcaf701 (cedula, fecha, concepto, tipo, monto, cuenta) VALUES ('$cedula', '$b', '".$f810['cue_nombre']."', '+', '$debe', '$cuent2')";
				$r701=mysql_query($sql701);
//				echo $sql701;
			}
			else {
				$restando+=abs($saldo);
				$debe = abs($saldo);
				agregar_f820($elasiento, $b, '-', $cuenta, 'Canc. x Retiro de Haberes '.$afiliado, $debe, $haber, 0,$ip,0,$referencia,'','S',0);
				$sql701="INSERT INTO sgcaf701 (cedula, fecha, concepto, tipo, monto, cuenta) VALUES ('$cedula', '$b', '".$f810['cue_nombre']."', '-', '$debe', '$cuent2')";
//				echo $sql701;
				$r701=mysql_query($sql701);
			}
			}
/*
			if ($prestamo['int_dif']==1)
			{
				$cuenta=trim($prestamo['cuent_int']).'-'.substr($codigo,1,4);
				$saldo=buscar_saldo_f810($cuenta); 	//			$prestamo['monpre_sdp']-$prestamo['monpag_sdp'];
				$debe = abs($saldo);
				if ($debe > 0)
				{
					agregar_f820($elasiento, $b, '+', $cuenta, 'Canc. x Retiro de Haberes '.$afiliado, $debe, $haber, 0,$ip,0,$referencia,'','S',0);
					$restando-=abs($saldo);
				}
			}
*/
		}
		$sql310="UPDATE sgcaf310 set stapre_sdp='C', renovado = 1, paga_hasta = '$b', renova_por = 'RETIRO' WHERE (cedsoc_sdp='$estacedula' and stapre_sdp='A' and ! renovado)";
		$r310=mysql_query($sql310);
	}
	$debe=$_POST['mreintegro'];
	if ($debe > 0)
	{
		agregar_f820($elasiento, $b, '+', $_POST['creintegro'], $_POST['conreintegro'].$afiliado, $debe, $haber, 0,$ip,0,$referencia,'','S',0);
		$reintegros=abs($debe);
	}

	$debe=$_POST['mdeducciones'];
	if ($debe > 0)
	{
		agregar_f820($elasiento, $b, '-', $_POST['cdeducciones'], $_POST['condeducciones'].$afiliado, $debe, $haber, 0,$ip,0,$referencia,'','S',0);
		$restando+=abs($debe);
	}
	
	// cargo retiro de dividendos
	$debe = $monto-$restando+$reintegros;
	$neto_cheque = $debe;
	if ($debe != 0) {
		$sql="select * from sgcaf000 where tipo='CtaSocxPag'";
//	echo $sql.'<br>';
		$result=mysql_query($sql); // or die ("<p />El usuario $usuario no pudo conseguir la cuenta x pagar<br>".mysql_error()."<br>".$sql);
		$cuentas=mysql_fetch_assoc($result);
		$cuenta1=trim($cuentas['nombre']).'-'.substr($codigo,1,4);
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
	$elretiro= $_POST['elretiro'];
	$_SESSION['lagestion']='S';
	echo "<form enctype='multipart/form-data' action='retiros.php?accion=HC' name='form1' method='post'>";
	$sql2="select * from sgcaf843 order by nombre_ban";
	$sql2= "SELECT * FROM sgcaf843, sgcaf845, sgcaf846 where nro_cta_ba = nro_ban and descrip ='' and estado='1' and emitircheque='1'";
	$sql2="SELECT * FROM sgcaf846, sgcaf845, sgcaf843 where registro=nro_reg and estatus='+' and nro_ban=nro_cta_ba and estado='1' and emitircheque='1' group by registro";
	$resultado=mysql_query($sql2);
	echo "<input type = 'hidden' value ='".$cedula."' name='cedula'>";
	echo "<input type = 'hidden' value ='".$elretiro."' name='elretiro'>";
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
	$elretiro= $_POST['elretiro'];
	$elcheque= $_POST['elcheque'];
	$_SESSION['lagestion']='S';
	echo "Asignando cheque<br>";
	$codigobanco=$elcheque;
/*
	$cheques_sql="select * from sgcaf844,sgcaf843 where ((ban_che='$elcheque') and (sta_che='L')) and (cod_banco ='$elcheque') limit 1";
//	echo $cheques_sql;
	$cheques=mysql_query($cheques_sql);	// busco el primer cheque disponible de ese banco 
	$cheque=mysql_fetch_assoc($cheques);
*/

	$cheques_sql= "SELECT * FROM sgcaf846 where banco='$elcheque' and registro='$codi' and estatus='+' and descrip='' order by nro_che";
	$cheques_sql="SELECT nro_cta_ba FROM sgcaf843 where cod_banco='$elcheque'";
	$result=mysql_query($cheques_sql);
	$fila99 = mysql_fetch_assoc($result);
	$codigocuenta=$fila99['nro_cta_ba'];
	$cheques_sql="SELECT * FROM sgcaf846 where banco='$codigocuenta' and estatus='+'";
	$result=mysql_query($cheques_sql);
	$fila99 = mysql_fetch_assoc($result);
//	echo $cheques_sql;
//    echo $fila99['nro_che'] .'" '.$lectura.' size="8" maxlength="8" />*</td><tr>';

	$elnumero=$fila99['nro_che'];
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
	$registro="insert into sgcaf840 (mche_orden, mche_fecha, mche_nombr, mche_monto, mche_descr, mche_statu, mche_banco, mche_prest, cobrados, verificado ) ";
	$registro.="VALUES ('$elnumero','$hoy','$beneficiario',$monto,'$concepto','L','$elcheque','007', 0, 0)";
//	echo $registro;
	if (mysql_query($registro)){
		echo "Creando cargo en el cheque<br>";
		$sql="select * from sgcaf000 where tipo='CtaSocxPag'";
		$result=mysql_query($sql); // or die ("<p />El usuario $usuario no pudo conseguir la cuenta x pagar<br>".mysql_error()."<br>".$sql);
		$cuentas=mysql_fetch_assoc($result);
		$cuenta1=trim($cuentas['nombre']).'-'.substr($codigo,1,4);
		$registro="insert into sgcaf841 (mche_orden, mche_cuent, mche_debcr, mche_descr, mche_monto1, mche_monto2,mche_monto,mche_banco";
		$registro.=") VALUES ('$elnumero','$cuenta1','+','Cancel. Ret. Hab. del ".$retiro['solicitado']."', ";
		$registro.="$monto, 0, 0, '$codigobanco')";
		echo "Creando abono en el cheque<br>";
		$_SESSION['elcheque']=$elnumero; 
		$_SESSION['laplaza']=$laplaza; 
//		echo $registro;
		if (mysql_query($registro)){
			$sql2="select * from sgcaf843 where cod_banco = '$codigobanco'";
//			echo $sql2;
			$cheque=mysql_query($sql2);
			$cheque=mysql_fetch_assoc($cheque);
			$cuenta1=$cheque['cue_banco'];
			$registro="insert into sgcaf841 (mche_orden, mche_cuent, mche_debcr, mche_descr, mche_monto1, mche_monto2,mche_monto,mche_banco";
			$registro.=") VALUES ('$elnumero','$cuenta1','-','".$beneficiario."', ";
			$registro.="0,$monto, 0, '$codigobanco')";
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
	$sql="update sgcaf700 set estado = 'A', banco = '$codigobanco', nro_cheque='$elnumero' where registro= $numeroderegistro ";
//	echo $sql;
	mysql_query($sql);
	echo "<a target=\"_blank\" href=\"retirapdf.php?cedula=$cedula\" onClick=\"info.html\', \'\',\'width=250, height=190\')\">Imprimir Aprobación de Retiro </a>"; 
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
function pantalla_retiro($result, $resultado)
{ 
?>
<table width="639" border="1">
  <tr>
	<?php 
//		echo $resultado;
		$deci=$_SESSION['deci'];
		$sep_decimal=$_SESSION['sep_decimal'];
		$sep_miles=$_SESSION['sep_miles'];
		$resultado = mysql_query($resultado) or die ('Error 200-1 <br>'.$sql.'<br>'.mysql_error());
		$lectura = 'readonly = "readonly"'; $activada="disabled" ; 
		$result = mysql_query($result) or die ('Error 200-1 <br>'.$sql.'<br>'.mysql_error());
		$cedula = $_POST['cedula'];
		$micedula=substr($cedula,0,4).'.'.substr($cedula,4,3).'.'.substr($cedula,7,4);
		$elretiro= $_POST['elretiro'];
		$sql3="select *, date_format(fechareti,'%Y-%m-%d') AS fecha_reti from sgcaf700 where cedsoc='$micedula' and tiporeti='$elretiro' order by fecha_reti desc";
//		echo $sql3;
		$result3 = mysql_query($sql3) or die ('Error 700-1 <br>'.$sql.'<br>'.mysql_error());
		
		$fila2=mysql_fetch_assoc($result); 
		$fila=mysql_fetch_assoc($resultado); 
		$ahorros=$_POST['ahorros'];
		$afectan=$_POST['afectan'];
		$noafectan=$_POST['noafectan'];
		$fianzas=$_POST['fianzas'];
		$codigo=$fila['cod_prof'];
		$totalahorros=$_POST['hab_prof']+$_POST['hab_empr']+$_POST['hab_extr']+$_POST['hab_opsu'];
		$t2=$_POST['mretirar'];
		$psocio	=$_POST['hab_prof']*100/$totalahorros;
		$pempresa=$_POST['hab_empr']*100/$totalahorros;
		$extra	=$_POST['hab_extr']*100/$totalahorros;
		$pcapi	=$_POST['hab_opsu']*100/$totalahorros;
		$hab_prof=round($t2*($psocio/100),2);
		$hab_empr=round($t2*($pempresa/100),2);
		$hab_extr=round($t2*($pextra/100),2);
		$hab_opsu=round($t2*($pcapi/100),2);
/*
		$hab_prof=round($_SESSION['disponibleretiro']*($psocio/100),2);
		$hab_empr=round($_SESSION['disponibleretiro']*($pempresa/100),2);
		$hab_extr=round($_SESSION['disponibleretiro']*($pextra/100),2);
		$hab_opsu=round($_SESSION['disponibleretiro']*($pcapi/100),2);
*/
    echo '<td width="127" colspan="1" scope="col">% de Retiro </td><td><strong>'.number_format($fila2["porcentaje"],$deci,".",",") .'</strong></td>';
    echo '<td colspan="2" >Nro. de Días Requerido p/Retiro  </td><td><strong>'.number_format($fila2["condicion"],0,".",",") .'</strong></td>';
    echo '<td colspan="2" >Tiempo Transcurrido (Días)  </td><td><strong>';
	$hoy=date("d-m-Y", time());
	$fila3=mysql_fetch_assoc($result3); 
	if ((mysql_num_rows($result3) > 0))
		echo dias_pasados($fila3['fecha_reti'],$hoy) ;
	else
		echo '<strong>NO APLICA</strong>'; 
	echo '</strong> </td>';
	echo '</tr>';
		if ($fila2['porcentaje']!=100){
		    echo '<tr><td width="127" scope="col">Max.Socio </td>';
			echo '<td width="127" align="right" scope="col">';
			echo "<input name='ohab_prof' type='text' id='ohab_prof' size='12' maxlength='12' value ='".$hab_prof."' ".$lectura." />";
		    echo '</td><td width="127" scope="col">Max.UCLA </td>';
			echo '<td width="127" align="right" scope="col">';
			echo "<input type='text' value ='".$hab_empr."' name='ohab_empr' id='ohab_empr' size='12' maxlength='12'".' '.$lectura ."/>";
		    echo '</td><td width="127" scope="col">Max.Volunt.</td>';
			echo '<td width="127" align="right" scope="col">';
			echo "<input type='text' value ='".$hab_extr."' name='ohab_extr' id='ohab_extr' size='12' maxlength='12'".' '.$lectura ."/>";
		    echo '</td><td width="127" scope="col">Max.OPSU</td>';
			echo '<td width="127" align="right" scope="col">';
			echo "<input type='text' value ='".$hab_opsu."' name='ohab_capi' id='ohab_capi' size='12' maxlength='12'".' '.$lectura ."/>";
			echo '</td></tr>';
		}
		else {
		    echo '<tr><td width="127" scope="col">Max.Socio </td>';
			echo '<td width="127" align="right" scope="col">';
			echo "<input name='ohab_prof' type='text' id='ohab_prof' size='12' maxlength='12' value ='".$fila['hab_f_prof']."' ".$lectura." />";
		    echo '</td><td width="127" scope="col">Max.UCLA </td>';
			echo '<td width="127" align="right" scope="col">';
			echo "<input type='text' value ='".$fila['hab_f_empr']."' name='ohab_empr' id='ohab_empr' size='12' maxlength='12'".' '.$lectura ."/>";
		    echo '</td><td width="127" scope="col">Max.Volunt.</td>';
			echo '<td width="127" align="right" scope="col">';
			echo "<input type='text' value ='".$fila['hab_f_extr']."' name='ohab_extr' id='ohab_extr' size='12' maxlength='12'".' '.$lectura ."/>";
		    echo '</td><td width="127" scope="col">Max.OPSU</td>';
			echo '<td width="127" align="right" scope="col">';
			echo "<input type='text' value ='".$fila['hab_opsu']."' name='ohab_capi' id='ohab_capi' size='12' maxlength='12'".' '.$lectura ."/>";
			echo '</td></tr>';
		}
		?>
	<tr><td colspan="5">
	Motivo: <input name="motivo" type="text" id="motivo" size="65" maxlength="65" value="" />
	</td>
		<td colspan="3">Monto del Retiro: 
        <?php
			if ($fila2['porcentaje']!=100){
				echo number_format($_POST['mretiro'],2,'.',','); 
			}
			else echo number_format($totalahorros,2,'.',','); 
			?> 
    </td>
	</tr>
	<tr><td colspan="8">
	Observacion(es): <input name="observa1" type="text" id="observa1" size="50" maxlength="50" value="" />
	<input name="observa2" type="text" id="observa2" size="50" maxlength="50" value="" /><br>
	<textarea name="observa3" id="observa3" maxlength="254" value=""  cols="120" rows="2"> </textarea>
	</td></tr>
	<?php
	if ($fila2['porcentaje']==100)
	{
		// busco los prestamos para cancelarlos
		$estacedula=$micedula;
		$sql="select * from sgcaf310, sgcaf360 where (cedsoc_sdp='$estacedula' and stapre_sdp='A' and (! renovado)) and (codpre_sdp=cod_pres)"; // se cambia el procedimiento solo por saldo contable s/ventura
		$sql="select cue_codigo from sgcaf810 where right(cue_codigo,4)='".substr($codigo,1,4)."' order by cue_codigo";
//	echo $sql.'<br>';
//	die('espero');
		$prestamos=mysql_query($sql); //  or die ("<p />El usuario $usuario no pudo conseguir los retiros de Dividendos <br>".mysql_error()."<br>".$sql);
		echo '<table><tr><td><strong>Cuentas que se autoliquidaran</strong></td></tr>';
		while($prestamo=mysql_fetch_assoc($prestamos)) {
//			$cuenta=trim($prestamo['cuent_pres']).'-'.substr($prestamo['codsoc_sdp'],1,4);
			$cuenta=trim($prestamo['cue_codigo']); // .'-'.substr($prestamo['codsoc_sdp'],1,4);
			$cuent2=substr($cuenta,0,16);
			$sql810="select cue_nombre from sgcaf810 where cue_codigo='$cuent2'" ;
			$r810=mysql_query($sql810);
			$f810=mysql_fetch_assoc($r810);
			$saldo=buscar_saldo_f810($cuenta); 	//			$prestamo['monpre_sdp']-$prestamo['monpag_sdp'];
//			echo "<tr><td>".$cuenta."</td><td>".$prestamo['descr_pres'].'</td><td align="right">'.number_format($saldo,2,'.',',').'</td></tr>';
			if  ($saldo!=0) {
				echo "<tr><td>".$cuenta."</td><td>".$f810['cue_nombre'].'</td><td align="right">'.number_format($saldo,2,'.',',').'</td></tr>';
			}
		}
		echo '</table>';
	}

/*
		echo '<tr><td colspan="5" width="127" scope="col">Total Retiro</td>';
		echo '<td width="127" align="right" scope="col">';
		$disponible=$_SESSION['disponibleretiro']; 
//		echo number_format($ahorros-($afectan+$noafectan+$fianzas),$deci,".",",");
		echo number_format($disponible),$deci,".",",");
		echo '</td></tr>';
*/
	?>
</table>
<?php
	echo '<fieldset><legend>Otros Reintegros/Otras Deducciones</legend>';
	echo '<table>';
	echo '<tr><td><strong>Otros Reintegros</strong></td></tr><br>';
	echo '<tr><td>Cuenta Contable</td>';
	echo '<td><input name="creintegro" type="text" id="creintegro" size="20" maxlength="20" value="" /></td>';
	echo '<td>Concepto</td>';
	echo '<td><input name="conreintegro" type="text" id="conreintegro" size="40" maxlength="40" value="" /></td>';
	echo '<td>Monto</td>';
	echo '<td><input name="mreintegro" type="text" id="mreintegro" size="15" maxlength="15" value="0" /></td>';
	echo '</tr>';

	echo '<tr><td><strong>Otras Deducciones</strong></td></tr><br>';
	echo '<tr><td>Cuenta Contable</td>';
	echo '<td><input name="cdeducciones" type="text" id="cdeducciones" size="20" maxlength="20" value="4-02-99-01-99-01-0001" /></td>';
	echo '<td>Concepto</td>';
	echo '<td><input name="condeducciones" type="text" id="condeducciones" size="40" maxlength="40" value="Retencion S/Oficio B.C.V " /></td>';
	echo '<td>Monto</td>';
	echo '<td><input name="mdeducciones" type="text" id="mdeducciones" size="15" maxlength="15" value="1" /></td>';
	echo '</tr>';
	echo '</table>';
	echo '</fieldset>';
	


}

//----------------------------------------------
function pantalla_socio($result,$accion,$opcion)
{
	$deci=$_SESSION['deci'];
	$sep_decimal=$_SESSION['sep_decimal'];
	$sep_miles=$_SESSION['sep_miles'];
	$fila = mysql_fetch_assoc($result);
//	echo 'qui '.$fila['ced_prof'];
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
		<td colspan="2" width="127">Socio <?php echo '<strong>'.$fila['ape_prof'].' '.$fila['nombr_prof'] .'</strong>'?></td>
	</tr>
	<tr>
		<td colspan="2" width="127" scope="col">Fecha de Ingreso 
		<strong><?php echo convertir_fechadmy($fila['f_ing_capu']) ?></strong></td>
		<td>Estatus
		<strong><?php echo $fila['statu_prof'] ?></strong></td>
		<td>Retiro
		<strong><?php echo convertir_fechadmy($fila['f_ret_capu']) ?></strong></td>
		<td>Jubilación
		<strong><?php echo convertir_fechadmy($fila['jubilado']) ?> </strong></td>
	</tr>
</table>
</fieldset> 
<?php 
if ($accion!='Anadir')
	pida_financiera(trim($fila['ced_prof']),$opcion);
}

function pida_financiera($cedula,$opcion)
{
?>
<fieldset><legend>Información Financiera </legend>
<?php 
	$deci=$_SESSION['deci'];
	$sep_decimal=$_SESSION['sep_decimal'];
	$sep_miles=$_SESSION['sep_miles'];
	$ahorros=ahorros($cedula);
	$afectan=afectan($cedula);
	$noafectan=noafectan($cedula);
	$sql='select * from sgcaf200 where ced_prof="'.$cedula.'"';
	$result=mysql_query($sql);
	$fila = mysql_fetch_assoc($result);
	$fianzas=fianzas($fila['cod_prof']);
	$disponible=disponibilidad($ahorros,$afectan,$noafectan,$fianzas); 
?>
<table width="639" border="1">
  <tr>
    <th colspan="2" width="186" scope="col">Ahorros</th> <th colspan="2" width="350" scope="col">Préstamos / Fianzas </th> 
  </tr>
  <?php 
	echo "<input type = 'hidden' value ='".$afectan."' name='afectan'>";
	echo "<input type = 'hidden' value ='".$noafectan."' name='noafectan'>";
	echo "<input type = 'hidden' value ='".$fianzas."' name='fianzas'>";
	echo "<input type = 'hidden' value ='".$ahorros."' name='ahorros'>";
	echo "<input type = 'hidden' value ='".$fila["hab_f_prof"]."' name='hab_prof'>";
	echo "<input type = 'hidden' value ='".$fila["hab_f_empr"]."' name='hab_empr'>";
	echo "<input type = 'hidden' value ='".$fila["hab_f_extr"]."' name='hab_extr'>";
	echo "<input type = 'hidden' value ='".$fila["hab_opsu"]."' name='hab_opsu'>";
  	if ($opcion == 'D') {
		echo '<tr><th width="186" scope="col">Descripci&oacute;n</th> <th width="80" scope="col">Monto</th> <th width="250" scope="col">Descripción</th><th width="80" scope="col">Saldo</th></tr>';
  		echo '<tr><th scope="row">Aporte Socio</th> <td align="right"> '; 
		echo number_format($fila["hab_f_prof"],$deci,".",",").'</td>';
		echo '<th scope="row">Préstamos que Afectan Disponibilidad</th> <td align="right"> ';
		echo number_format($afectan,$deci,".",",").'</td></tr>';
  		echo '<tr><th scope="row">Aporte Patronal</th> <td align="right">';
		echo number_format($fila["hab_f_empr"],$deci,".",",").'</td>';
    	echo '<th scope="row">Préstamos que No Afectan Disponibilidad</th> <td align="right">';
		echo number_format($noafectan,$deci,".",",").'</td></tr></tr>';
  		echo '<tr><th scope="row">Aporte Voluntario</th>';
		echo '<td align="right">'.number_format($fila["hab_f_extr"],$deci,".",",").'</td>';
		echo '<th scope="row">Fianzas Otorgadas</th> <td align="right">'.number_format($fianzas,$deci,".",",").'</tr>';
		echo '<tr><th scope="row">Ahorros OPSU</th> <td align="right">';
		echo number_format($fila["hab_opsu"],$deci,".",",").'</td></tr><tr>';
	}
  ?>
    <th scope="row">Total Ahorros</th>
    <td align="right"><?php echo number_format($fila['hab_f_prof']+$fila['hab_f_empr']+$fila['hab_f_extr']+$fila['hab_opsu'],$deci,$sep_decimal,$sep_miles) ?></td>
    <th scope="row">Total Obligaciones</th> <td align="right"> <?php echo number_format($afectan+$noafectan+$fianzas,$deci,".",",");?>  </tr>
    <th colspan="2" align="right">Disponibilidad Neta</th>
    <td align="center" colspan="2" class="<?php echo ($disponible<=0)?'rojo':'azul' ?>" >
     <? // <input name="disponible" align="right" type="text" id="disponible" size="14" maxlength="14" <?php echo $lectura;  />
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
		   $_SESSION['disponibleretiro']=$disponible; 
		   ?>
    </td>
    </tr>
</table>
</fieldset>
<?php
}

/*
update `sgcaf200` set hab_f_prof = 4814.27, hab_f_empr=4739.52, hab_opsu=24.18 WHERE ced_prof='V-07337778'
*/?>
