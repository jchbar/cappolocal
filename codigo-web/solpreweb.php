<?php
/*
http://localhost/cajalocal/solpreweb.php?valor=Y2VkdWxhPVYtMDkzNzczODgmbW9udG89NTAwLjAwJnRpcG89MDIzJmlwPTE5Mi4xNjguMS45
*/
include("head.php");
include("paginar.php");
//echo $_SERVER['REMOTE_ADDR'].'<br>';

if (!$link OR !$_SESSION['empresa']) {
	include("noempresa.php");
	exit;
}
$mostrarregresar=0;
// <script src="ajaxpr2.js" type="text/javascript"><script>

if ($accion == 'Anadir') 
	$onload="onload=\"foco('lacedula')\"";
else
	if ($accion =='EscogeRetiro')
		$onload="onload=\"foco('ret_socio')\"";
	else 
		if ($accion == 'Buscar') 
			$onload="onload=\"foco('elretiro')\""; 
		else $onload="onload=\"foco('cedula')\"";
?>
<script language="javascript">
function abrirVentana(asiento)
{
window.open("impcompdf.php?asiento="+asiento,"parte1","top=0,left=395,status=no,toolbar=no,scrollbar=yes,location=no,type=fullWindow,fullscreen");
}
</script>
<body <?php if (!$bloqueo) {echo $onload;}?>>

<?php
$readonly=" readonly='readonly'";
include("arriba.php");
$menu61=1;include("menusizda.php");
$cedula = $_GET['cedula'];
$ip = $_SERVER['HTTP_CLIENT_IP'];
if (!$ip) {$ip = $_SERVER['REMOTE_ADDR'];}

extract($_GET);
extract($_POST);
$valor=base64_decode($valor);
echo $valor;
$varios=explode('&',$valor);
$cedula	=$varios[0];
$cedula =explode('=',$cedula);
$cedula =$cedula[1];

$monto	=$varios[1];
$monto =explode('=',$monto);
$monto =$monto[1];

$tipo	=$varios[2];
$tipo =explode('=',$tipo);
$tipo =$tipo[1];

$ip_origen=$varios[3];
$ip_origen =explode('=',$ip_origen);
$ip_origen =$ip_origen[1];
// $cedula=str_replace('\\',$cedula);

/*
echo '<br>cedula '.$cedula.'<br>';
echo 'monto '.$monto.'<br>';
echo 'tipo '.$tipo.'<br>';
echo 'ip'.$ip_origen.'<br>';
*/
// $cedula='V-09377388';
// $tipo='023';

if (! $accion) {
	$elprestamo=$tipo;
	$sql_200="select * from sgcaf200 where ced_prof='$cedula'";
	$a_200=mysql_query($sql_200);
	$r_200=mysql_fetch_assoc($a_200);
	$laparte=$r_200['cod_prof'];
	$codigo=$laparte;
	$elasiento = date("ymd").$codigo;
	$micedula=substr($cedula,0,4).'.'.substr($cedula,4,3).'.'.substr($cedula,7,4);
	$elnumero=numero_prestamo($micedula, $laparte);
	$b = $hoy = date("Y-m-d");
	$monpre_sdp=$monto;
	$estatus='A';
	$inicial = $intereses_diferidos = 0;

	$sql_360="select * from sgcaf360 where cod_pres='$tipo'";
	$a_360=mysql_query($sql_360);
	$r_360=mysql_fetch_assoc($a_360);

	$cuota=$monpre_sdp * (1 + ($r_360['i_max_pres'] / 100));
	$lascuotas=1;
	$interes_sd=$r_360['i_max_pres'];

	$sql_acta="select * from sgcafact where especial = 1 order by fecha desc limit 3";
	$las_actas=mysql_query($sql_acta);
	while ($filaa = mysql_fetch_assoc($las_actas)) 
	{
		$primerdcto=$filaa['f_dcto'];
		$nroacta=$filaa['acta'];
	}
	echo '<fieldset><legend>'.trim($r_360['descr_pres']). ' / '.trim($r_200['ape_prof']). ', '.trim($r_200['nombr_prof']).' / ';
	echo $r_200['ced_prof'].' / '.$r_200['cod_prof'].' / '.$elnumero;
	echo '</legend>';

	echo "Creando préstamo nuevo numero <strong>$elnumero</strong><br>";
	$sql="insert into sgcaf310 (codsoc_sdp, cedsoc_sdp, nropre_sdp, codpre_sdp, f_soli_sdp, f_1cuo_sdp, monpre_sdp, monpag_sdp, nrofia_sdp, stapre_sdp, tipo_fianz, cuota, nrocuotas, interes_sd, cuota_ucla, netcheque, nro_acta, fecha_acta, ip, inicial, intereses, quien) values ('$laparte', '$micedula', '$elnumero','$elprestamo','$hoy', '$primerdcto', $monpre_sdp, 0, 0, '$estatus', '',$cuota, $lascuotas, $interes_sd, $cuota, $monpre_sdp, '$nroacta', '$fechaacta', '$ip - $ip_origen', $inicial, $intereses_diferidos, '".$_SERVER['REMOTE_ADDR']."')";
	$resultado=mysql_query($sql);	

	echo "Actualizando acta <strong>$nroacta</strong><br>";
	actualizar_acta($nroacta,$monpre_sdp,$primerdcto);
	
	if ($r_360['genera_com'] == 1){
		if ($tipo == '023') {	// flash /

			$cargo=trim($r_360['cuent_pres']).'-'.substr($laparte,1,4);
			$listo=cuenta_810($cargo,trim($r_200['ape_prof']). ' '.$r_200['nombr_prof']);
			echo "Generando encabezado contable <strong><a target=\"_blank\" href='editasi2.php?asiento=$elasiento'>$elasiento </a></strong> <br>";
			$sql = "INSERT INTO sgcaf830 (enc_clave, enc_fecha, enc_desco, enc_desc1, enc_debe, enc_haber, enc_item, enc_dif, enc_igual, enc_refer, enc_sw, enc_explic) VALUES ('$elasiento', '$b', '','',0,0,0,0,0,0,0,'')"; 
			if (!mysql_query($sql)) die ("El usuario $usuario no tiene permiso para añadir Asientos.<br>".$sql);
			$haber = $debe = 0;
			$referencia=$elnumero;
		// cargo prestamo al socio
			$debe = $monpre_sdp;
			if ($r_360['int_dif'] == 1) {
				$cuenta_diferido=trim($r_360['cuent_int']).'-'.substr($laparte,1,4);
				$listo=cuenta_810($cuenta_diferido,trim($r_200['ape_prof']). ' '.$r_200['nombr_prof']);
			}
			echo "Generando cargos del asiento <strong><a target=\"_blank\" href='editasi2.php?asiento=$elasiento'>$elasiento </a></strong> <br>";
			$debe=$monpre_sdp;
			if ($debe != 0) {
				$cuenta1=$cargo;
				agregar_f820($elasiento, $b, '+', $cuenta1, $r_360['descr_pres'], $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
			}
			echo "Generando abonos del asiento <strong><a target=\"_blank\" href='editasi2.php?asiento=$elasiento'>$elasiento </a></strong> <br>";
			$debe=$inicial;
			if ($debe != 0) {
				$cuenta1=$cargo;
				agregar_f820($elasiento, $b, '-', $cuenta1, 'Inicial '.$r_360['descr_pres'], $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
			}
			$debe=$intereses_diferidos;
			if ($debe != 0) {
				$cuenta1=$cuenta_diferido; // .'-'.substr($laparte,1,4);
				agregar_f820($elasiento, $b, '-', $cuenta1, $r_360['descr_pres'], $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
			}
			$d_obligatorias=0;
			// coloco las deducciones obligatorias activas
			$sql_deduccion="select * from sgcaf311 where activar = 1";
			$a_deduccion=mysql_query($sql_deduccion);
			$cargo=trim($r_360['cuent_pres']).'-'.substr($laparte,1,4);
			existe_cuenta($cargo);
			$listo=cuenta_810($cargo,trim($r_200['ape_prof']). ' '.$r_200['nombr_prof']);
			while($r_deduccion=mysql_fetch_assoc($a_deduccion)) {
				if ($r_deduccion['porcentaje'] == 0)
					$monto_deduccion=$r_deduccion['monto'];
				else $monto_deduccion=($r_310['monpre_sdp']-$r_310['inicial'])*($r_deduccion['porcentaje']/100);
				$d_obligatorias+=$monto_deduccion;
				$debe=$monto_deduccion;
				$albanco-=$debe;
				$cuenta1=trim($r_deduccion['cuenta']);
				agregar_f820($elasiento, $b, '-', $cuenta1, $r_deduccion['cuento']. ' '.trim($socio['ape_prof']). ' '.trim($socio['nombr_prof']), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
				$sql_312="insert into sgcaf312 (tipo, cuento, cuenta, monto, numero, cedula) VALUES ('-','".$r_deduccion['cuento']."', '$cuenta1', $monto_deduccion, '$elnumero','$micedula')";
				$resultado=mysql_query($sql_312);
			}

			$debe = $monpre_sdp- $d_obligatorias; //  - $inicial - $intereses_diferidos ;
			$neto_cheque = $debe;
			if ($debe != 0) {
				$sql="select * from sgcaf000 where tipo='CtaSocxPag'";
				$result=mysql_query($sql); // or die ("<p />El usuario $usuario no pudo conseguir la cuenta x pagar<br>".mysql_error()."<br>".$sql);
				$cuentas=mysql_fetch_assoc($result);
//			echo 'cuenta buscada '.$cuentas['nombre'].'<br>';
				$cuenta1=trim($cuentas['nombre']).'-'.substr($laparte,1,4);
//			echo 'cuenta mostrada '.$cuenta1.'<br>';
				agregar_f820($elasiento, $b, '-', $cuenta1, $r_360['descr_pres'], $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
			}
	
		}	// flash
	}

	$sql="update sgcaf310 set netcheque = $neto_cheque where cedsoc_sdp = '$micedula' and nropre_sdp='$referencia'";
	$resultado=mysql_query($sql);

	if ($r_360['albanco']==0)
	{
		$sql="update sgcaf310 set netcheque = 0 where cedsoc_sdp = '$micedula' and nropre_sdp='$referencia'";
		$resultado=mysql_query($sql);
	}

	$asiento=$elasiento;
	echo "<form method='post' name='form1' action='solpreweb.php?accion=Listo'>\n";
	echo '<fieldset><legend>Indique Tipo de Impresion para el asiento '.$asiento.'</legend>';
	echo "Asiento: <input type='text' name='asiento' value='$asiento'>\n";

	echo '<input type="submit" name="Submit" value="Imprimir Asiento" onClick="abrirVentana(';
	echo "'";
	echo $asiento;
	echo "&hoja=1";
	echo "&agrupar=0";
	echo "'";
	echo ');">  ';
	echo '</fieldset>';

	echo "</form>\n";
}
else 
if ($accion == 'Listo')
{
	echo "<form enctype='multipart/form-data' action='solpreweb.php?accion=Cerrar' name='form1' id='form1' method='post' ";
	echo '<tr><td colspan="2" align="center"><input type = "submit" value = "Cerrar Ventana"></td></tr>'; 
	echo '</form>';
}
else 
if ($accion == 'Cerrar')
{
		echo "<script languaje='javascript' type='text/javascript'>window.close();</script>";
}


	echo "</div></body></html>";


// die('espero');
//---------------------
//---------------------
function buscar_saldo_f810($cuenta, $asiento)
{
	$sql_f810="select cue_saldo from sgcaf810 where cue_codigo='$cuenta'";
//	echo $sql_f810;
	$lacuentas=mysql_query($sql_f810); //  or die ("<p />El usuario $usuario no pudo conseguir el saldo contable<br>".mysql_error()."<br>".$sql);
	$lacuenta=mysql_fetch_assoc($lacuentas);
	$saldoinicial=$lacuenta['cue_saldo'];
//	echo 'el asiento '.$asiento.'<br>';
	$sql_f820="select com_monto1, com_monto2 from sgcaf820 where com_cuenta='$cuenta' ";
	if ($asiento == '')
		$sql_f820.="";
	else
		$sql_f820.=" and (com_nrocom <> '$asiento') ";
	$sql_f820.=" order by com_fecha";
//	echo $sql_f820.'<br>';
	$lacuentas=mysql_query($sql_f820); //  or die ("<p />El usuario $usuario no pudo conseguir los movimientos contables<br>".mysql_error()."<br>".$sql);
	while($lascuenta=mysql_fetch_assoc($lacuentas)) {
		$saldoinicial+=$lascuenta['com_monto1'];
//		echo $saldoinicial.'<br>';
		$saldoinicial-=$lascuenta['com_monto2'];
//		echo $saldoinicial.'<br>';
	}
return round($saldoinicial,2);
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
	// determino factor de anualidad
	if ($r_200['tipo_socio']== 'P')
		$factor = 52;
	else 
		if ($r_200['tipo_socio']== 'E')
			$factor = 24;
		else 
			$factor = 12;
	echo "<input type = 'hidden' value ='".$factor."' name='factor_division' id='factor_division'>";
	$elnumero=numero_prestamo($micedula, $laparte);

	$sql_360="select * from sgcaf360 where cod_pres='$tipo'";
	$a_360=mysql_query($sql_360);
	$r_360=mysql_fetch_assoc($a_360);
	$sql_310="select * from sgcaf310, sgcaf360 where cedsoc_sdp='$micedula' and nropre_sdp='$nropre'";
	$a_310=mysql_query($sql_310);
	$r_310=mysql_fetch_assoc($a_310);
		echo '<fieldset><legend>'.trim($r_360['descr_pres']). ' / '.trim($r_200['ape_prof']). ', '.trim($r_200['nombr_prof']).' / ';
	echo $r_200['ced_prof'].' / '.$r_200['cod_prof'].' / '.$elnumero;
	if 	($_SESSION['numeroarenovar']) echo ' <br>(Renovacion) ';
	echo '</legend>';
	echo '<table align="center" class="basica 100 hover" width="500" border="1">';
	echo '<tr>';
    echo '<td width="100">Tasa de Interes </td><td width="100" align="right">'.number_format($r_360['i_max_pres'],$deci,$sep_decimal,$sep_miles).'%</td>';
	echo "<input type = 'hidden' value ='".$r_360['i_max_pres']."' name='interes_sd' id='interes_sd'>";
	echo "<input type = 'hidden' value ='".$r_360['tipo_interes']."' name='tipo_interes' id='tipo_interes'>";
	echo "<input type = 'hidden' value ='".$r_360['en_ajax']."' name='calculo' id='calculo'>";
	echo "<input type = 'hidden' value ='".$elnumero."' name='elnumero' id='elnumero'>";
	echo "<input type = 'hidden' value ='".$r_200['ced_prof']."' name='cedula' id='cedula'>";
    echo '<td width="150">Monto Solicitado </td><td width="100" align="right">';
	// -----------
	$s_100="select ut from sgcaf100 limit 1";
	$a_100=mysql_query($s_100);
	$r_100=mysql_fetch_assoc($a_100);
	$montounidadtributaria=$r_100['ut'];
	$maximodisponible=$_SESSION['disponibilidadprestamo'];
	$texto='';
	if ($_SESSION['disponibilidadprestamo'] <= 0)
		if ($r_360['tope_ut'] == 0)
			{ 
			$maximodisponible=$r_360['tope_monto']-($r_310['monpre_sdp']-$r_310['monpag_sdp']);
			if ($maximodisponible <= 0) $texto='1';
			}
		else {
			$maximodisponible=($r_360['factor_ut']*$montounidadtributaria); // +$_SESSION['disponibilidadprestamo'];
			if ($r_360['e_items'] == 1)
			{
				$s_items="select sum(monpre_sdp-monpag_sdp) as saldo from sgcaf310 where cedsoc_sdp='$micedula' and codpre_sdp='$tipo' and stapre_sdp='A' and (! renovado) group by cedsoc_sdp";
				$a_items=mysql_query($s_items);
				$r_items=mysql_fetch_assoc($a_items);
				$maximodisponible-=$r_items['saldo'];
				if ($maximodisponible < 0)
					$maximodisponible=0;
				else $texto='1';
			}
		}
	if ($r_360['montofijo'] != 0)
		$_SESSION['disponibilidadprestamo']=$r_360['montofijo']; // $disponible; 
	if ($texto =='')
			echo '<input align="right" name="monpre_sdp" type="text" id="monpre_sdp" size="12" maxlength="12" value="';
	echo ($texto==''?number_format($maximodisponible,2,'.',''):'Sin Disponibilidad'); 
	if ($texto =='')
		echo '"/>';
	echo "<input type = 'hidden' value ='".$maximodisponible."' name='elmaximo' id='elmaximo'>";
//	---------------
	echo '</td></tr>';
	echo '<tr>';
	$hoy=date("d/m/Y", time());
	$sql_acta="select * from sgcafact where especial = 0 order by fecha desc limit 1";
	$las_actas=mysql_query($sql_acta);
	$el_acta=mysql_fetch_assoc($las_actas);
	echo '<td>Fecha de solicitud </td><td>'.$hoy.'</td>';
    echo '<td>Monto Pagado </td><td  align="right">'.number_format(0,$deci,$sep_decimal,$sep_miles).'</td></tr>';
	echo '<tr>';
	echo '<td>1er Descuento </td><td>';
	echo convertir_fechadmy($el_acta['f_dcto']);
	$primerdcto=convertir_fechadmy($el_acta['f_dcto']);
	$primerdcto=($el_acta['f_dcto']);
//	$primer_dcto=convertir_fechadmy($el_acta['f_dcto']);
	if ($r_360['dcto_sem']==1) 
	{
		echo "<input type = 'hidden' value ='".$primerdcto."' name='primerdcto' id='primerdcto'>";
	}
	else 
	{
//		echo "<input type = 'text' value ='".$primerdcto."' name='primerdcto' id='primerdcto'>";
		$sql_acta="select * from sgcafact where especial = 1 order by fecha desc limit 3";
		$las_actas=mysql_query($sql_acta);
//		echo '111';
		echo '<select id="primerdcto" name="primerdcto" size="1">';
		while ($filaa = mysql_fetch_assoc($las_actas)) 
		{
			echo '<option value="'.$filaa['f_dcto'].'" '.'selected>'.$filaa['f_dcto'].'</option>';
		}
	  	echo '</select> ';	

	}
	echo '</td>';
    echo '<td>Saldo </td><td  align="right">'.number_format(0,$deci,$sep_decimal,$sep_miles).'</td></tr>';
	echo '<tr>';
	echo '<td>CC/NC</td><td>'.'0'.' de ';
	echo '<select id="lascuotas" name="lascuotas" size="1">';
	for ($laposicion=$r_360['n_cuo_pres'];$laposicion >= 1;$laposicion--) {
		echo '<option value="'.$laposicion.($posicion==$r_360['n_cuo_pres']?" selected ":"").'" >'.$laposicion.' </option>'; }
		// 
	echo '</select>'; 
	echo '</td>';
    echo '<td>Cuota Original </td><td  align="right">';
	// .number_format($r_310['cuota'],$deci,$sep_decimal,$sep_miles).;
	echo '<input align="right" name="cuota" type="text" id="cuota" size="12" maxlength="12" readonly="readonly" value ="0.00">';
	echo '<input align="right" name="descontar_interes" type="hidden" id="descontar_interes" size="12" maxlength="12" readonly="readonly" value ='.$r_360['int_dif'].'>';
	echo '<input align="right" name="monto_futuro" type="hidden" id="monto_futuro" size="12" maxlength="12" readonly="readonly" value ='.$r_360['montofuturo'].'>';
	echo '</td></tr>';
	echo '<tr>';
	
	$nroacta=$el_acta['acta'];
	$fechaacta=$el_acta['fecha'];
	$elasiento = date("ymd").$codigo;
	echo '<input align="right" name="nroacta" type="hidden" id="nroacta" size="12" maxlength="12" readonly="readonly" value ="'.$nroacta.'">';
	echo '<input align="right" name="fechaacta" type="hidden" id="fechaacta" size="12" maxlength="12" readonly="readonly" value ="'.$fechaacta.'">';
	echo '<tr><td>Intereses: </td><td align="right">';
	echo '<input align="right" name="interes_diferido" type="hidden" id="interes_diferido" size="12" maxlength="12" readonly="readonly" value ="0.00"></td>';
	echo '<td>Cuota Modificada </td><td align="right">'.number_format($r_310['cuota_ucla'],$deci,$sep_decimal,$sep_miles).'</td></tr>';
	echo '<tr><td>Gastos Administrativos: </td><td align="right">';
	echo '<input align="right" name="gastosadministrativos" type="text" id="gastosadministrativos" size="12" maxlength="12" readonly="readonly" value ="0.00"';
	echo '</td><td>Inicial</td><td align="right">';
	echo '<input align="right" name="inicial" type="text" id="inicial" size="12" maxlength="12" value ="0.00"';
	if ($r_360['inicial'] == 0)
		echo 'readonly="readonly" ';
	echo '>';
	echo '</td></tr><tr>';
	echo '<td>Acta / Fecha </td><td>'.$nroacta.' del '.convertir_fechadmy($fechaacta).'</td>';
	echo '<td>Neto a Depositar<br><em>No incluye otros prestamos</em></td><td align="right">';
	echo '<input align="right" name="montoneto" type="text" id="montoneto" size="12" maxlength="12" readonly="readonly" value ="0.00"';
	echo '</td></tr><tr>';
//	echo '<tr><td align="center" colspan="4">';

//	echo '</td></tr>';
//	echo '<td><div id="contenedor">Valor</div></td>';

//	<input type="button" name="calculo" value="Calcular a" onClick="Cargarcontenido('mostrarpr.php','c=3', 'form1', 'contenido2')">	
	if ($texto =='') {
	echo '<td align="center" colspan="2"> '; 
	echo '<input type="button" name="calculo" value="Calcular Cuota" onClick="ajax_call()">	';
	echo '</td><td align="center" colspan="2"> ';
	echo "<input type = 'submit' value = 'Crear Préstamo'>"; 

	// <a title="Calcular" href="javascript:Cargarcontenido('mostrarpr.php', 'c=3', 'form1', 'contenido2')">Calcular</a>
	echo '</td>';} 
	echo '</table>';
	echo '</fieldset>';
//	echo 'numero a renovar '.$_SESSION['numeroarenovar'];
//	echo '</div>';
	$lafoto='fotos/'.substr($cedula,2,8).'.jpg';
	echo "<br><br><img src='".$lafoto."' width='156' height='156' border='0' />";
//	echo '<div id="contenido2"></div>';
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
		<td colspan="1" width="100" >C&oacute;digo <?php echo '<strong>'.$elcodigo.'</strong>'; ?></td>
 		<td colspan="1" width="130">Cédula <?php echo '<strong>'.$fila['ced_prof'].'</strong>';?></td>
		<td colspan="3" width="127">Socio <?php echo '<strong>'.$fila['ape_prof'].' '.$fila['nombr_prof'] .'</strong>'?></td>
	</tr>
	<tr>
		<td colspan="1" width="127" scope="col">Fecha de Ingreso 
		<strong><?php echo convertir_fechadmy($fila['f_ing_capu']) ?></strong></td>
		<td colspan="1" width="127" scope="col">Fecha Ing. UCLA 
		<strong><?php echo convertir_fechadmy($fila['f_ing_ucla']) ?> </strong></td>
		<td colspan="1" width="127" scope="col">Tiempo UCLA
		<strong><?php echo cedad(convertir_fechadmy($fila['f_ing_ucla'])) ?> </strong></td>
		<td>Estatus
		<strong><?php echo $fila['statu_prof'] ?></strong></td>
	    <td align="center" colspan="1" class="<?php echo ($disponible<=0)?'rojo':'azul' ?>" >Disponibilidad Neta
		<?php 
			$ahorros=ahorros($cedula);
			$afectan=afectan($cedula);
			$noafectan=noafectan($cedula);
			$sql='select * from sgcaf200 where ced_prof="'.$cedula.'"';
			$result=mysql_query($sql);
			$fila = mysql_fetch_assoc($result);
			$fianzas=fianzas($fila['cod_prof']);
//			$disponible=($totalahorros-$reserva)-($afectan+$fianzas);
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
//			$_SESSION['disponibilidadprestamo']=1234; // $disponible; 
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
	$sql_310="select * from sgcaf310, sgcaf360 where cedsoc_sdp='$micedula' and nropre_sdp='$nropre' and (codpre_sdp=cod_pres)";
	$a_310=mysql_query($sql_310);
	$r_310=mysql_fetch_assoc($a_310);
	echo '<fieldset><legend>'.trim($r_310['descr_pres']). ' / '.trim($r_200['ape_prof']). ', '.trim($r_200['nombr_prof']).' / ';
	echo $r_310['cedsoc_sdp'].' / '.$r_310['codsoc_sdp'].'</legend>';
	echo '<table class="basica 100 hover" width="400" border="1">';
	echo '<tr>';
    echo '<td width="250">Tasa de Interes </td><td width="200" align="right">'.number_format($r_310['interes_sd'],$deci,$sep_decimal,$sep_miles).'%</td>';
    echo '<td width="250">Monto Solicitado </td><td width="200" align="right">'.number_format($r_310['monpre_sdp'],$deci,$sep_decimal,$sep_miles).'</td></tr>';
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
	echo '</tr>';
	echo '</table>';
	echo '</fieldset>';
	$lafoto='fotos/'.substr($cedula,2,8).'.jpg';
	echo "<img src='".$lafoto."' width='156' height='156' border='0' />";
}	

function actualizar_acta($nroacta, $monto, $primerdcto) {
	$sql="update sgcafact set eje_pre=eje_pre + $monto, otorgado=otorgado+1 where ((acta ='$nroacta') and (f_dcto = '$primerdcto'))";
	$resultado=mysql_query($sql);
}

function generar_comprobantes($sql_360)
{
/*
	$a_360=mysql_query($sql_360);
	$r_360=mysql_fetch_assoc($a_360);
	echo 'cod_pres='.$r_360['cod_pres'];
	if ($r_360['cod_pres'] != '055') {
		// coloco las deducciones obligatorias activas
		$sql_deduccion="select * from sgcaf311 where activar = 1";
		$a_deduccion=mysql_query($sql_deduccion);
		$cargo=trim($r_360['cuent_pres']).'-'.substr($laparte,1,4);
		$listo=cuenta_810($cargo,trim($r_200['ape_prof']). ' '.$r_200['nombr_prof']);
		echo "Generando encabezado contable <strong><a target=\"_blank\" href='editasi2.php?asiento=$elasiento'>$elasiento </a></strong> <br>";
		$sql = "INSERT INTO sgcaf830 (enc_clave, enc_fecha, enc_desco, enc_desc1, enc_debe, enc_haber, enc_item, enc_dif, enc_igual, enc_refer, enc_sw, enc_explic) VALUES ('$elasiento', '$b', '','',0,0,0,0,0,0,0,'')"; 
		if (!mysql_query($sql)) die ("El usuario $usuario no tiene permiso para añadir Asientos.<br>".$sql);
		$haber = $debe = 0;
		$referencia=$elnumero;
		// cargo prestamo al socio
		$debe = $monpre_sdp;
		if ($r_360['int_dif'] == 1) {
			$cuenta_diferido=trim($r_360['cuent_int']).'-'.substr($laparte,1,4);
//			echo 'dfierod'.$cuenta_diferido;
			$listo=cuenta_810($cuenta_diferido,trim($r_200['ape_prof']). ' '.$r_200['nombr_prof']);
		}
		echo "Generando cargos del asiento <strong><a target=\"_blank\" href='editasi2.php?asiento=$elasiento'>$elasiento </a></strong> <br>";
		$debe=$monpre_sdp;
		if ($debe != 0) {
			$cuenta1=$cargo;
			agregar_f820($elasiento, $b, '+', $cuenta1, $r_360['descr_pres'], $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
		}
		echo "Generando abonos del asiento <strong><a target=\"_blank\" href='editasi2.php?asiento=$elasiento'>$elasiento </a></strong> <br>";
		$debe=$inicial;
		if ($debe != 0) {
			$cuenta1=$cargo;
			agregar_f820($elasiento, $b, '-', $cuenta1, 'Inicial '.$r_360['descr_pres'], $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
		}
		$debe=$intereses_diferidos;
		if ($debe != 0) {
			$cuenta1=$cuenta_diferido; // .'-'.substr($laparte,1,4);
			agregar_f820($elasiento, $b, '-', $cuenta1, $r_360['descr_pres'], $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
		}
		$d_obligatorias=0;
		while($r_deduccion=mysql_fetch_assoc($a_deduccion)) {
			if ($r_deduccion['porcentaje'] == 0)
				$monto_deduccion=$r_deduccion['monto'];
			else $monto_deduccion=($monpre_sdp-$inicial)*($r_deduccion['porcentaje']/100);
			$d_obligatorias+=$monto_deduccion;
			$debe=$monto_deduccion;
			$cuenta1=trim($r_deduccion['cuenta']);
			agregar_f820($elasiento, $b, '-', $cuenta1, $r_deduccion['cuento']. ' '.trim($socio['ape_prof']). ' '.trim($socio['nombr_prof']), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
			$sql_312="insert into sgcaf312 (tipo, cuento, cuenta, monto, numero, cedula) VALUES ('-','".$r_deduccion['cuento']."', '$cuenta1', $monto_deduccion, '$elnumero','$micedula')";
//			echo $sql_312;
			$resultado=mysql_query($sql_312);
		}
		
		$debe = $monpre_sdp - $inicial - $intereses_diferidos - $d_obligatorias;
		$neto_cheque = $debe;
		if ($debe != 0) {
			$sql="select * from sgcaf000 where tipo='CtaSocxPag'";
			$result=mysql_query($sql); // or die ("<p />El usuario $usuario no pudo conseguir la cuenta x pagar<br>".mysql_error()."<br>".$sql);
			$cuentas=mysql_fetch_assoc($result);
//			echo 'cuenta buscada '.$cuentas['nombre'].'<br>';
			$cuenta1=trim($cuentas['nombre']).'-'.substr($laparte,1,4);
//			echo 'cuenta mostrada '.$cuenta1.'<br>';
			agregar_f820($elasiento, $b, '-', $cuenta1, $r_360['descr_pres'], $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
		}
		}
	if ($r_360['cod_pres'] == '055') {	// no hipotecario /
		$sqlgiros="select * from sgcaf000 where tipo='NumeroGiros'";
		$a_giros=mysql_query($sqlgiros);
		$r_giros=mysql_fetch_assoc($a_giros);
		$numero_giros=$r_giros['nombre'];
		
		$sqlgiros="select * from sgcaf000 where tipo='MontoGiros'";
		$a_giros=mysql_query($sqlgiros);
		$r_giros=mysql_fetch_assoc($a_giros);
		$monto_giros=$r_giros['nombre'];
		
		$sqlgiros="select * from sgcaf000 where tipo='FechaGiro'";
		$a_giros=mysql_query($sqlgiros);
		$r_giros=mysql_fetch_assoc($a_giros);
		$fecha_giros=$r_giros['nombre'];
		
		$sqlgiros="select * from sgcaf000 where tipo='LetraGiros'";
		$a_giros=mysql_query($sqlgiros);
		$r_giros=mysql_fetch_assoc($a_giros);
		$letra_giros=$r_giros['nombre'];
		
		$sqlgiros="select * from sgcaf000 where tipo='CuotaGiros'";
		$a_giros=mysql_query($sqlgiros);
		$r_giros=mysql_fetch_assoc($a_giros);
		$cuota_giros=$r_giros['nombre'];
		
		for ($losgiros=0;$i<$numero_giros;$losgiros++) {
			$numerogiro=letra_giros+substr($laparte,1,4)+ceroizq($losgiros,2);
			$primer_dcto=$fecha_giros;

			$sql="insert into sgcaf310 (codsoc_sdp, cedsoc_sdp, nropre_sdp, codpre_sdp, f_soli_sdp, f_1cuo_sdp, monpre_sdp, monpag_sdp, nrofia_sdp, stapre_sdp, tipo_fianz, cuota, nrocuotas, interes_sd, cuota_ucla, netcheque, nro_acta, fecha_acta, ip, inicial, intereses) values ('$laparte', '$micedula', '$elnumero','$numerogiro','$hoy', '$primer_dcto', $numero_giros, 0, 0, '$estatus', '',$cuota_giros, $lascuotas, $interes_sd, $cuota_giros, $monpre_sdp, '$nroacta', '$fechaacta', '$ip', $inicial, $intereses_diferidos)";
	echo $sql.'<br>';
			$resultado=mysql_query($sql);
			$elano=substr($fecha_giros,1,4);
			$fecha_giros=$elano.substr($fecha_giros,5,5);
			$primer_dcto=$fecha_giros;
		}
		
	
	}	// fin no hipotecario
	$sql="update sgcaf310 set netcheque = $neto_cheque where cedsoc_sdp = '$micedula' and nropre_sdp='$referencia'";
//		echo $sql;
	$resultado=mysql_query($sql);
	$_SESSION['elasiento']=$elasiento;		
	actualizar_acta($nroacta,$debe);
*/
}
/*
ALTER TABLE `sgcaf310` ADD `ip` VARCHAR( 30 ) NOT NULL ;
ALTER TABLE `sgcaf360` ADD `genera_com` BOOL NOT NULL ;
ALTER TABLE `sgcaf360` ADD `restar_otros` BOOL NOT NULL ,
ADD `incluir_otros` BOOL NOT NULL ;
ALTER TABLE `sgcaf360` ADD `inicial` BOOL NOT NULL ;
ALTER TABLE `sgcaf360` ADD `fiadores` BOOL NOT NULL ;
// restar otros para que al monto del prestamo se le puedan restar otros prestamos
// incluir otros servira para indicar si ese tipo de prestamo se puede incluir para que sea restado o cancelado
ALTER TABLE `sgcaf310` ADD `inicial` DECIMAL( 12, 2 ) NOT NULL ;
ALTER TABLE `sgcaf310` ADD `intereses` DECIMAL( 12, 2 ) NOT NULL ;
ALTER TABLE `sgcaf310` ADD `registro` BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY ;

CREATE TABLE `sgcaf311` (
`cuento` VARCHAR( 50 ) NOT NULL ,
`activar` BOOL NOT NULL ,
`porcentaje` DECIMAL( 12, 2 ) NOT NULL ,
`cuenta` VARCHAR( 30 ) NOT NULL
) ENGINE = MYISAM ;
ALTER TABLE `sgcaf311` ADD `monto` DECIMAL( 12, 2 ) NOT NULL ;
// para saber que cosas les puedo descontar en forma automatica 
INSERT INTO `sica`.`sgcaf311` (`cuento` ,`activar` ,`porcentaje` ,`cuenta`,`monto`)
VALUES ('Debito Bancario', '1', '.5', '4-01-01-01-01-0001',0), ('I.T.F', '0', '1.5', '4-01-01-01-01-0002',0);
INSERT INTO `sica`.`sgcaf311` (`cuento` ,`activar` ,`porcentaje` ,`cuenta`,`monto`)
VALUES ('Gastos Administrativos', '1', '1', '4-01-01-01-01-0003',0);
INSERT INTO `sica`.`sgcaf311` (`cuento` ,`activar` ,`porcentaje` ,`cuenta` ,`monto`)
VALUES ('otra deduccion', '1', '0', '4-01-01-01-01-0004', '20'); 

// guardar las deducciones y los reintegros
CREATE TABLE `sgcaf312` (
`tipo` VARCHAR( 1 ) NOT NULL ,
`cuento` VARCHAR( 40 ) NOT NULL ,
`cuenta` VARCHAR( 30 ) NOT NULL ,
`monto` DECIMAL( 12, 2 ) NOT NULL ,
`numero` VARCHAR( 8 ) NOT NULL ,
`cedula` VARCHAR( 12 ) NOT NULL
) ENGINE = MYISAM ;

modificar la estructura de sgcaf320 en el campo nropre_fia a 8 caracteres de manera que haya integridad con la sgcaf310
y colocar el cero delante del numero de prestamo

ALTER TABLE `sgcaf320` CHANGE `nropre_fia` `nropre_fia` VARCHAR( 8 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL  
ALTER TABLE `sgcaf320` ADD `ip` VARCHAR( 40 ) NOT NULL ,
ADD `registro` BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY ;

ALTER TABLE `sgcaf312` ADD `registro` BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY ;

delete from sgcaf310 where nropre_sdp='00882013';
update sgcaf310 set renovado = 0, renova_por ='', paga_hasta = '' where nropre_sdp='0882052';
delete from sgcaf312 where numero='00882013';
delete from sgcaf820 where com_nrocom='12101000882';
delete from sgcaf830 where enc_clave='12101000882';
select * from sgcaf310 where cedsoc_sdp='V-12.851.330' and codpre_sdp='001';

delete from sgcaf310 where nropre_sdp='02006002';
update sgcaf310 set renovado = 0, renova_por ='', paga_hasta = '' where nropre_sdp='0782006';
delete from sgcaf312 where numero='02006002';
delete from sgcaf820 where com_nrocom='12101102006';
delete from sgcaf830 where enc_clave='12101102006';# 
select * from sgcaf310 where cedsoc_sdp='V-07.310.959' ;

delete from sgcaf310 where nropre_sdp='00871002';
update sgcaf310 set renovado = 0, renova_por ='', paga_hasta = '' where nropre_sdp='0780871';
delete from sgcaf312 where numero='00871002';
delete from sgcaf820 where com_nrocom='12101100871';
delete from sgcaf830 where enc_clave='12101100871';# 
select * from sgcaf310 where cedsoc_sdp='V-04.379.154' ;

*/
?>
