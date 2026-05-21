<?php 
include("head.php");
include("paginar.php");
if (!$link OR !$_SESSION['empresa']) {
	include("noempresa.php");
	exit;
}
$mostrarregresar=0;
?>
<script language="javascript">

function abrirVentana(elorden)
{
window.open("propagpdf.php?orden="+elorden,"parte1","top=0,left=395,status=no,toolbar=no,scrollbar=yes,location=no,type=fullWindow,fullscreen");	
}
</script>

<script type="text/javascript" src="ajxproy.js"></script>
<script type="text/javascript" src="ajxproyb.js"></script>
<?php
/*
<script type="text/javascript" src="ajxmul.js"></script>
<script type="text/javascript">
var ajax = new Array();

function getprestamos(sel)
{
	var codigoprestamo = sel.options[sel.selectedIndex].value;
	document.getElementById('lascuotas').options.length = 0;	// Empty city select box
//	alert(document.getElementById('lascuotas').options.length);
//	alert(codigoprestamo);
	if(codigoprestamo.length>0){
		var index = ajax.length;
		ajax[index] = new sack();
		
		ajax[index].requestFile = 'saquecuotas.php?tipo='+codigoprestamo;	// Specifying which file to get
		ajax[index].onCompletion = function(){ createcuotas(index) };	// Specify function that will be executed after file has been found
		ajax[index].runAJAX();		// Execute AJAX function
	}
}

function createcuotas(index)
{
	var obj = document.getElementById('lascuotas');
	eval(ajax[index].response);	// Executing the response from Ajax as Javascript code	
}


function getCityList(sel)
{
	var countryCode = sel.options[sel.selectedIndex].value;
	document.getElementById('dhtmlgoodies_city').options.length = 0;	// Empty city select box
	if(countryCode.length>0){
		var index = ajax.length;
		ajax[index] = new sack();
		
		ajax[index].requestFile = 'getCities.php?countryCode='+countryCode;	// Specifying which file to get
		ajax[index].onCompletion = function(){ createCities(index) };	// Specify function that will be executed after file has been found
		ajax[index].runAJAX();		// Execute AJAX function
	}
}

function createCities(index)
{
	var obj = document.getElementById('dhtmlgoodies_city');
	eval(ajax[index].response);	// Executing the response from Ajax as Javascript code	
}


function getSubCategoryList(sel)
{
	var category = sel.options[sel.selectedIndex].value;
	document.getElementById('dhtmlgoodies_subcategory').options.length = 0;	// Empty city select box
	if(category.length>0){
		var index = ajax.length;
		ajax[index] = new sack();
		
		ajax[index].requestFile = 'getSubCategories.php?category='+category;	// Specifying which file to get
		ajax[index].onCompletion = function(){ createSubCategories(index) };	// Specify function that will be executed after file has been found
		ajax[index].runAJAX();		// Execute AJAX function
	}
}
function createSubCategories(index)
{
	var obj = document.getElementById('dhtmlgoodies_subcategory');
	eval(ajax[index].response);	// Executing the response from Ajax as Javascript code	
}		
</script>

*/
?>
<?php 
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
<body <?php if (!$bloqueo) {echo $onload;}?>>

<?php
$readonly=" readonly='readonly'";
include("arriba.php");
$menu61=1;include("menusizda.php");
$cedula = $_GET['cedula'];
$ip = $_SERVER['HTTP_CLIENT_IP'];
if (!$ip) {$ip = $_SERVER['REMOTE_ADDR'];}

if ($accion == "Renovacion") {	// selecciono el tipo de prestamo
	$mostrarregresar=1;
	echo '<div id="div1">';
	$cedula = $_GET['cedula'];
	$elprestamo = $_GET['nropre'];
	$temp = "";
	echo "<form enctype='multipart/form-data' action='propag.php?accion=Solicitar' name='form1' id='form1' method='post' onsubmit='return valpre(form1)'";
	echo "<input type = 'hidden' value ='".$cedula."' name='cedula'>";
	echo "<input type = 'hidden' value ='".$elprestamo."' name='elprestamo'>";
	$micedula=substr($cedula,0,4).'.'.substr($cedula,4,3).'.'.substr($cedula,7,4);
	$sql_360="select * from sgcaf360 where cod_pres='$elprestamo'";
	$a_360=mysql_query($sql_360);
	$r_360=mysql_fetch_assoc($a_360);
	$sql_310="select * from sgcaf310 where (cedsoc_sdp='$micedula') and (codpre_sdp='$elprestamo') and (stapre_sdp='A') and (! renovado)";
	$a_310=mysql_query($sql_310);
	if ((! $r_360['masdeuno']) and (mysql_num_rows($a_310) >= 1))	
			echo '<h2>No puede tener mas de un préstamo de este tipo</h2>';
	else {
		pantalla_completar_prestamo($cedula,$elprestamo);
	}
	echo '</form>';
	echo '</div>';
}	// fin de ($accion == "Renovacion")

//----------------------------
if ($accion == 'Buscar')  {
	extract($_POST);
	$elcodigo = trim($_POST['elcodigo']);
	$lacedula = trim($_POST['cedula']);
	if (! $cedula) {
		$lacedula = $_SESSION['cedulasesion']; 
		}
	else 
		$_SESSION['cedulasesion']=$_POST['cedula'];
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
		echo "<table class='basica 100 hover' width='750'><tr>";
		echo '<th colspan="1"></th><th width="80">Fecha</th><th width="100">Nro.Prestamo</th><th width="280">Tipo</th><th width="100">Monto</th><th width="100">Saldo</th><th width="80">NC</th><th width="80">CC</th></tr>';

		if (pagina($numasi, $conta, 20, "Prestamos Activos", $ord)) {$fin = 1;}
// 		bucle de listado
		while($row=mysql_fetch_assoc($rs)) {
			echo "<tr>";

//		echo "<td class='centro'><a href='extractoctas3.php?cuenta=".trim($row['cuent_pres']).'-'.substr(trim($row['codsoc_sdp']),1,4)."&datos=no&'><img src='imagenes/page_wizard.gif' width='16' height='16' border='0' /></a></td>";
		echo "<td class='centro'><a href='propag.php?accion=VerProyeccion&cedula=".$cedula."&nropre=".$row['nropre_sdp']."'><img src='imagenes/page_user_dark.gif' width='16' height='16' border='0' alt='Ver Proyeccion' longdesc='Presione aqui para visualizar la proyeccion de pagos' title='Consultar'/></a></td>";
		echo "<td class='centro'>";
/*
		if ($row['renovacion']>1)
			if ($row['ultcan_sdp'] >= $row['renovacion']) {
				echo "<a href='propag.php?accion=Renovar&cedula=".$cedula."&nropre=".$row['nropre_sdp']."'>";
				echo "<img src='imagenes/action_refresh_blue.gif' width='16' height='16' border='0' />";
				echo "</a>";
			}
			else echo ' ';
		else if ($row['renovacion'] == 1){ 
				echo "<a href='propag.php?accion=ReAjustar&cedula=".$cedula."&nropre=".$row['nropre_sdp']."'>";
				echo "<img src='imagenes/icon_get_world.gif' width='16' height='16' border='0' />";
				echo "</a>";
			}
			else echo ' ';

		echo "</td><td>";
*/		
			echo convertir_fechadmy($row['f_soli_sdp'])."</td>";
			echo "<td class='centro'>";
			echo $row['nropre_sdp'];
			echo "</td>";
			echo "<td class='centro'>".$row['descr_pres']."</td>";
			echo "<td align='right'>";
			echo number_format($row['monpre_sdp'],2,'.',',');
			echo "</td>";
			echo "<td align='right'>".number_format(($row['monpre_sdp']-$row['monpag_sdp']),2,'.',',')."</td>";
			echo "<td class='centro'>".number_format($row['nrocuotas'],0,'.',',')."</td>";
			echo "<td class='centro'>".number_format($row['ultcan_sdp'],0,'.',',')."</td>";
			echo "</tr>";
		}

		echo "</table>";
	}
}	// fin de ($accion == 'Buscar') 
		
if (!$accion) {
	echo "<form action='propag.php?accion=Buscar' name='form1' method='post'>";
    echo '  C&eacute;dula ';
	echo '<input name="cedula" type="text" id="cedula" value=""  size="10" maxlength="10" />';
	echo "<input type = 'submit' value = 'Buscar'>";
	$_SESSION['numeroarenovar']='';
	$_SESSION['cedulasesion']=''; 
	echo '</form>';
}	// fin de (!$accion) 
if ($accion == 'VerProyeccion') {
	echo "<div align='center' id='div1'>";
	$mostrarregresar=1;
	$cedula=$_GET['cedula'];
	$nropre=$_GET['nropre'];
	mostrar_proyeccion($cedula,$nropre);
	echo "</div>";
}	// fin de ($accion == 'Ver')

if (($accion == "Editar")) {	// muestra datos para prestamo
	echo '<div id="div1">';
	$sql='SELECT * FROM sgcaf200 WHERE ced_prof= "'.$cedula.'"';
	$result = mysql_query($sql) or die ('Error 200-1 <br>'.$sql.'<br>'.mysql_error());
	echo "<input type = 'hidden' value ='".$cedula."' name='cedula'>";
	$temp = "";
	echo "<form enctype='multipart/form-data' action='propag.php?accion=Proyectar' name='form1' method='post' onsubmit='return valsoc(form1)'>";
	pantalla_prestamo($result,$cedula);
	$elstatus=$_SESSION['elstatus'];
	echo '<fieldset><legend>Información Para Prestamo </legend>';
	$sqlprestamos="";
	if (($elstatus == "ACTIVO") or ($elstatus == "JUBILA")) {
		$sqlprestamos.="select * from sgcaf360 where tipo_interes='Amortizada' and ";}
	else {
		echo '<h2>El socio NO tiene un estatus disponible para solicitar préstamos</h2>';
		echo '</fieldset>';
	}
	$sqlprestamos.="(tiempo < ".$_SESSION['tiempoactivo'];
	$sqlprestamos.=") order by cod_pres";

	echo "<table class='basica 100 hover' width='750'><tr>";
	echo '<td>Monto a Proyectar </td>';
	echo '<td><input align="right" name="montoprestamo" type="text" id="montoprestamo" size="12" maxlength="12" value ='.number_format($_SESSION['disponibilidadprestamo'],2,'.','').'></td>';
	echo '<input align="right" name="interes_sd" type="hidden" id="interes_sd" size="12" maxlength="12" value =0>';
	echo "<input type = 'hidden' value ='".$r_360['en_ajax']."' name='calculo' id='calculo'>";
	echo "<input type = 'hidden' value ='".$r_360['en_ajax']."' name='tipo_interes' id='tipo_interes'>";
	echo '<td>Seleccione Tipo</td>';
   	echo '<td class="rojo">';
//	echo '<select name="elprestamo" size="1" onChange="getprestamos(this)">';
	echo '<select name="elprestamo" size="1" onChange="ajax_call(this)">';
	$resultado=mysql_query($sqlprestamos);
	echo '<option value=" ">'." ".'</option>'; 
	while ($fila2 = mysql_fetch_assoc($resultado)) {
		echo '<option value="'.$fila2['cod_pres'].'">'.$fila2['cod_pres'].' - '.$fila2['descr_pres'].'</option>'; }
	echo '</select> *'; 
	echo '</td></tr><tr><td>Indique Numero de Cuotas</td><td>';
	echo '<select id="lascuotas" name="lascuotas" size="1" onChange="ajax2call(this)">';
//	for ($laposicion=$r_360['n_cuo_pres'];$laposicion >= 1;$laposicion--) {
//	for ($laposicion=10;$laposicion >= 1;$laposicion--) {
//		echo '<option value="'.$laposicion.($posicion==$r_360['n_cuo_pres']?" selected ":"").'" >'.$laposicion.' </option>'; }
	echo '</option>';
	echo '</td>';	
	echo '<td>Monto Cuota </td><td><input align="right" name="cuota" type="text" id="cuota" size="12" maxlength="12" value =0.00 readonly="readonly"></td></tr>';
	echo '<tr><td>Intereses </td><td><input align="right" name="interesdiferido" type="text" id="interesdiferido" size="12" maxlength="12" value =0.00 readonly="readonly"></td>';
	echo '<td>- Descuentos Administrativos </td><td><input align="right" name="descuentosadm" type="text" id="descuentosadm" size="12" maxlength="12" value =0.00 readonly="readonly"></td></tr>';
	echo '<tr><td>Neto a Recibir (Aproximado) </td><td><input align="right" name="netoarecibir" type="text" id="netoarecibir" size="12" maxlength="12" value =0.00 readonly="readonly"></td>';
	echo "<td colspan='2'> <input type = 'submit' value = 'Realizar Proyeccion'></td></tr>"; 
	echo '</table>';
	echo '</fieldset>';
	echo '</form>';
	echo '</div>';
} 	// fin de ($accion == "Editar")
if (($accion == "Proyectar")) {	// muestra datos para prestamo
	$mostrarregresar=1;
	echo '<div id="div1">';
	$sql='SELECT * FROM sgcaf200 WHERE ced_prof= "'.$cedula.'"';
	$result = mysql_query($sql) or die ('Error 200-1 <br>'.$sql.'<br>'.mysql_error());
	echo "<input type = 'hidden' value ='".$cedula."' name='cedula'>";
	$temp = "";
//	echo "<form enctype='multipart/form-data' action='propag.php?accion=Regresar' name='form1' method='post' onsubmit='return valsoc(form1)'>";
	$cedula=$_POST['cedula'];
	$sql_200="select * from sgcaf200 where ced_prof='$cedula'";
	$a_200=mysql_query($sql_200);
	$r_200=mysql_fetch_assoc($a_200);
	$micedula=substr($cedula,0,4).'.'.substr($cedula,4,3).'.'.substr($cedula,7,4);
	$codpre_sdp=$_POST['elprestamo'];
	$sql_310="select * from sgcaf360 where ('$codpre_sdp'=cod_pres)";
	$a_310=mysql_query($sql_310);
	$r_310=mysql_fetch_assoc($a_310);
	$interes=$_POST['interes_sd'];
	$cuotas=$_POST['lascuotas'];
	$hoy = date("Y-m-d");
	$primer_dcto=$hoy;
	$capital=$_POST['montoprestamo'];
//	echo $interes.' - '.$cuotas.' - '.$capital;
	visualice($capital, $primer_dcto, $interes, $cuotas, $r_310, $c1, $cu, $ia, $cu, $ac, $ta, $tc, $i1, $better_token);
	echo '<input type="submit" name="Submit" value="Imprimir Proyeccion de Pagos" onClick="abrirVentana(';
	echo "'";
	echo $better_token;
//	echo "&c1=".$c1."&cu=".$cu."&ia=".$ia."&ac=".$ac."&ta=".$ta."&tc=".$tc."&i1=".$i1;
	echo "&otro=".$better_token.$better_token;
	echo "&aa=$capital&bb=$cuotas&ia=$ia[$cuotas]&cc=$cu[1]&dd=$interes&socio=";
	echo trim($r_200['ape_prof']). ', '.trim($r_200['nombr_prof']);
	echo "&prestamo=".trim($r_310['descr_pres']);
	echo "'";
	echo ');">  ';
//	echo '</form>';
	echo '</div>';
}

if ($accion == "EscogePrestamo")  {	// selecciono el tipo de prestamo
	$mostrarregresar=1;
	echo '<div id="div1">';
	$cedula = $_POST['cedula'];
	$elprestamo = $_POST['elprestamo'];
	$temp = "";
	echo "<form enctype='multipart/form-data' action='propag.php?accion=Solicitar' name='form1' id='form1' method='post' onsubmit='return valpre(form1)'";
	echo "<input type = 'hidden' value ='".$cedula."' name='cedula'>";
	echo "<input type = 'hidden' value ='".$elprestamo."' name='elprestamo'>";
	$micedula=substr($cedula,0,4).'.'.substr($cedula,4,3).'.'.substr($cedula,7,4);
	$sql_360="select * from sgcaf360 where cod_pres='$elprestamo'";
	$a_360=mysql_query($sql_360);
	$r_360=mysql_fetch_assoc($a_360);
	$sql_310="select * from sgcaf310 where (cedsoc_sdp='$micedula') and (codpre_sdp='$elprestamo') and (stapre_sdp='A') and (! renovado)";
	$a_310=mysql_query($sql_310);
	if ((! $r_360['masdeuno']) and (mysql_num_rows($a_310) >= 1))	
			echo '<h2>No puede tener mas de un préstamo de este tipo</h2>';
	else {
		pantalla_completar_prestamo($cedula,$elprestamo);
	}
	echo '</form>';
	echo '</div>';
}	// fin de ($accion == "EscogePrestamo")
if ($accion == "Solicitar") {	// aprobar
	$estatus='S';
	$sql_360="select * from sgcaf360 where cod_pres='$elprestamo'";
	$a_360=mysql_query($sql_360);
	$r_360=mysql_fetch_assoc($a_360);
	if ($r_360['aprobar'] == 1) $estatus= 'A';
	$cedula = $_POST['cedula'];
	$elprestamo = $_POST['elprestamo'];
	$elnumero = $_POST['elnumero'];
	$primerdcto = $_POST['primerdcto'];
	$monpre_sdp = $_POST['monpre_sdp'];
	$inicial = $_POST['inicial'];
	$_SESSION['cedula']=$cedula;
	$_SESSION['elnumero']=$elnumero;
	$_SESSION['elprestamo']=$elprestamo;
	$cuota = $_POST['cuota'];
	$interes_sd = $_POST['interes_sd'];
	$lascuotas = $_POST['lascuotas'];
	$micedula=substr($cedula,0,4).'.'.substr($cedula,4,3).'.'.substr($cedula,7,4);
	$sql_200="select * from sgcaf200 where ced_prof='$cedula'";
	$a_200=mysql_query($sql_200);
	$r_200=mysql_fetch_assoc($a_200);
	$laparte=$r_200['cod_prof'];
	$codigo=$laparte;
	$sql_acta="select * from sgcafact order by fecha desc limit 1";
	$las_actas=mysql_query($sql_acta);
	$el_acta=mysql_fetch_assoc($las_actas);
	$nroacta=$el_acta['acta'];
	$fechaacta=$el_acta['fecha'];
	$hoy = date("Y-m-d");
	$b = $hoy;
	$elasiento = date("ymd").$codigo;
	$ip = $_SERVER['HTTP_CLIENT_IP'];
	if (!$ip) {$ip = $_SERVER['REMOTE_ADDR'];}
	$intereses_diferidos=$_POST['interes_diferido'];
	$_SESSION['montoprestamo']=$monpre_sdp;

	/////////////////
	$primerdcto='0000-00-00';
	echo "Creando préstamo nuevo numero <strong>$elnumero</strong><br>";
	$sql="insert into sgcaf310 (codsoc_sdp, cedsoc_sdp, nropre_sdp, codpre_sdp, f_soli_sdp, f_1cuo_sdp, monpre_sdp, monpag_sdp, nrofia_sdp, stapre_sdp, tipo_fianz, cuota, nrocuotas, interes_sd, cuota_ucla, netcheque, nro_acta, fecha_acta, ip, inicial, intereses) values ('$laparte', '$micedula', '$elnumero','$elprestamo','$hoy', '$primerdcto', $monpre_sdp, 0, 0, '$estatus', '',$cuota, $lascuotas, $interes_sd, $cuota, $monpre_sdp, '$nroacta', '$fechaacta', '$ip', $inicial, $intereses_diferidos)";
//	echo $sql;
	$resultado=mysql_query($sql);	
	
	if ($r_360['restar_otros'] == 1) $accion='Restar';
	else 
	if ($r_360['genera_com'] == 1){
		// coloco las deducciones obligatorias activas
		$sql_deduccion="select * from sgcaf311 where activar = 1";
		$a_deduccion=mysql_query($sql_deduccion);
		$cargo=trim($r_360['cuent_pres']).'-'.substr($laparte,1,4);
		echo "Generando encabezado contable <strong>$elasiento </strong> <br>";
		$sql = "INSERT INTO sgcaf830 (enc_clave, enc_fecha, enc_desco, enc_desc1, enc_debe, enc_haber, enc_item, enc_dif, enc_igual, enc_refer, enc_sw, enc_explic) VALUES ('$elasiento', '$b', '','',0,0,0,0,0,0,0,'')"; 
		if (!mysql_query($sql)) die ("El usuario $usuario no tiene permiso para añadir Asientos.<br>".$sql);
		$haber = $debe = 0;
		$referencia=$elnumero;
		// cargo prestamo al socio
		$debe = $monpre_sdp;
		if ($r_360['int_dif'] == 1) {
			$cuenta_diferido=trim($r_360['cuent_int']).'-'.substr($laparte,1,4);
		}
		echo "Generando cargos del asiento <strong>$elasiento </strong>  <br>";
		$debe=$monpre_sdp;
		if ($debe != 0) {
			$cuenta1=$cargo;
			agregar_f820($elasiento, $b, '+', $cuenta1, $r_360['descr_pres'], $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
		}
		echo "Generando abonos del asiento <strong>$elasiento </strong>  <br>";
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
		$sql="update sgcaf310 set netcheque = $neto_cheque where cedsoc_sdp = '$micedula' and nropre_sdp='$referencia'";
//		echo $sql;
		$resultado=mysql_query($sql);
		$_SESSION['elasiento']=$elasiento;		
		actualizar_acta($nroacta,$debe);
		if ($r_360['genera_pl'] == 1) {
			echo 'Preparando para la impresion<br>';
			echo "<a target=\"_blank\" href=\"solprepdf.php?cedula=$cedula\" onClick=\"info.html\', \'\',\'width=250, height=190\')\">Imprimir Planilla de Préstamo </a>"; 	
		}
		else echo '<h2>Este tipo de préstamo esta configurado para no realizar impresión de planilla</h2>';
	}
	/// *****imprimri en otro momento, faltan los fiadores*****
} // fin de ($accion == "Solicitar")

if ($accion == "ReAjustar") {	//  para aquellos que solo aumentan el monto y varian la cuota
	$mostrarregresar=1;
	echo '<div id="div1">';
	echo "<form enctype='multipart/form-data' action='propag.php?accion=Reajuste' name='form1' id='form1' method='post' onsubmit='return valajuste(form1)'";
	$cedula = $_GET['cedula'];
	$elnumero = $_GET['nropre'];
	$micedula=substr($cedula,0,4).'.'.substr($cedula,4,3).'.'.substr($cedula,7,4);
	$deci=$_SESSION['deci'];
	$sep_decimal=$_SESSION['sep_decimal'];
	$sep_miles=$_SESSION['sep_miles'];
	$sql_200="select * from sgcaf200 where ced_prof='$cedula'";
	$a_200=mysql_query($sql_200);
	$r_200=mysql_fetch_assoc($a_200);
	// busco el prestamo
	$sql_310="select * from sgcaf310, sgcaf360 where (cedsoc_sdp='$micedula') and (stapre_sdp='A' and ! renovado) and (codpre_sdp=cod_pres) and (nropre_sdp = '$elnumero') limit 1";
	//  order by nropre_sdp";
	$a_310=mysql_query($sql_310);
	$r_310=mysql_fetch_assoc($a_310);
	echo '<fieldset><legend>'.trim($r_200['ape_prof']). ', '.trim($r_200['nombr_prof']).' / ';
	echo $r_200['ced_prof'].' / '.$r_200['cod_prof'].' / '.$elnumero.'</legend>';
	echo '<table align="center" class="basica 100 hover" width="500" border="1">';
//	echo $sql_310;
	echo '<tr><td>Monto Actual del Prestamo </td>';
	echo '<td>'.number_format($r_310['monpre_sdp']-$r_310['monpag_sdp'],$deci,$sep_decimal,$sep_miles).'</td></tr>';
	echo '<tr><td>Indique monto a adicionar </td>';
	echo '<input name="nropre" type="hidden" id="nropre" value ="'.$elnumero.'">';
	echo '<input name="cedula" type="hidden" id="cedula" value ="'.$cedula.'">';
	echo '<td><input align="right" name="montoprestamo" type="text" id="montoprestamo" size="12" maxlength="12" value =0.00></td></tr>';
	echo '<tr><td>Indique Nueva Cuota (Opcional)</td>';
	echo '<td><input align="right" name="cuota" type="text" id="cuota" size="12" maxlength="12" value ="'.number_format($r_310['cuota_ucla'],2,'.','').'"></td>';
	echo '<tr>';
	echo '<td align="center" colspan="4"> '; 
	echo "<input type = 'submit' value = 'Ajustar'>"; 
	echo '</td></tr>';
	echo '</table>';
	echo '</fieldset>';
	echo '</form>';
	echo '</div>';
	$lafoto='fotos/'.substr($cedula,2,8).'.jpg';
	echo "<br><br><img src='".$lafoto."' width='156' height='156' border='0' />";
//**********************
	echo '</div>';
}	// fin de ($accion == "ReAjustar")
if ($accion == "Reajuste") {	//  para aquellos que solo aumentan el monto y varian la cuota
	$mostrarregresar=1;
	echo '<div id="div1">';
	$cedula = $_POST['cedula'];
	$elnumero = $_POST['nropre'];
	$micedula=substr($cedula,0,4).'.'.substr($cedula,4,3).'.'.substr($cedula,7,4);
	$deci=$_SESSION['deci'];
	$sep_decimal=$_SESSION['sep_decimal'];
	$sep_miles=$_SESSION['sep_miles'];
	$sql_200="select * from sgcaf200 where ced_prof='$cedula'";
	$a_200=mysql_query($sql_200);
	$r_200=mysql_fetch_assoc($a_200);
	// actualizo 
	$sql_310="update sgcaf310 set monpre_sdp = monpre_sdp + ".$_POST['montoprestamo'].", cuota_ucla = ".$_POST['cuota']." where (nropre_sdp = '$elnumero') and (cedsoc_sdp = '$micedula')";
//	echo $sql_310;
	$a_310=mysql_query($sql_310);
	// busco el prestamo y lo muestro actualizado
	$sql_310="select * from sgcaf310, sgcaf360 where (cedsoc_sdp='$micedula') and (stapre_sdp='A' and ! renovado) and (codpre_sdp=cod_pres) and (nropre_sdp = '$elnumero') limit 1";
	//  order by nropre_sdp";
	$a_310=mysql_query($sql_310);
	$r_310=mysql_fetch_assoc($a_310);
	echo '<fieldset><legend>'.trim($r_200['ape_prof']). ', '.trim($r_200['nombr_prof']).' / ';
	echo $r_200['ced_prof'].' / '.$r_200['cod_prof'].' / '.$elnumero.'</legend>';
	echo '<table align="center" class="basica 100 hover" width="500" border="1">';
//	echo $sql_310;
	echo '<tr><td>Monto Actualizado del Prestamo </td>';
	echo '<td>'.number_format($r_310['monpre_sdp']-$r_310['monpag_sdp'],$deci,$sep_decimal,$sep_miles).'</td></tr>';
	echo '</table>';
	echo '</fieldset>';
	echo '</form>';
	echo '</div>';
	$lafoto='fotos/'.substr($cedula,2,8).'.jpg';
	echo "<br><br><img src='".$lafoto."' width='156' height='156' border='0' />";
	echo '</div>';
}	// fin de ($accion == "Reajuste")
if ($accion == "Restar") {	// restar prestamos cuando va a cancelarlos
	echo '<div id="div1">';
	echo "<form enctype='multipart/form-data' action='propag.php?accion=Concretar' name='form1' id='form1' method='post' onsubmit='return valpreres(form1)'";
	$cedula = $_SESSION['cedula'];
	$elnumero = $_SESSION['elnumero'];
	$elprestamo = $_SESSION['elprestamo'];
	$montoprestamo = $_SESSION['montoprestamo'];
	$micedula=substr($cedula,0,4).'.'.substr($cedula,4,3).'.'.substr($cedula,7,4);
//**********************
	$deci=$_SESSION['deci'];
	$sep_decimal=$_SESSION['sep_decimal'];
	$sep_miles=$_SESSION['sep_miles'];
	$sql_200="select * from sgcaf200 where ced_prof='$cedula'";
	$a_200=mysql_query($sql_200);
	$r_200=mysql_fetch_assoc($a_200);
	// determino los prestamos que tiene y puede cancelar
	$sql_310="select * from sgcaf310, sgcaf360 where (cedsoc_sdp='$micedula') and (incluir_otros=1) and (codpre_sdp != '$elprestamo') and (stapre_sdp='A' and ! renovado) and (codpre_sdp=cod_pres) order by nropre_sdp";
//	echo $sql_310;
	$a_310=mysql_query($sql_310);
	echo '<fieldset><legend>'.trim($r_200['ape_prof']). ', '.trim($r_200['nombr_prof']).' / ';
	echo $r_200['ced_prof'].' / '.$r_200['cod_prof'].' / '.$elnumero;
	$saldo = $reintegro = $suma = 0;
	if ($_SESSION['numeroarenovar']) {
		echo '<br>(Renovacion)';
		$sql="select cuent_pres,codsoc_sdp,cuent_int from sgcaf310,sgcaf360 where (nropre_sdp='".$_SESSION['numeroarenovar']."') and stapre_sdp='A' and ! renovado and (cedsoc_sdp='$micedula')  and (codpre_sdp=cod_pres)";
		$a_31=mysql_query($sql);
		$r_31=mysql_fetch_assoc($a_31);
		$lacuenta=trim($r_31['cuent_pres']).'-'.substr($r_31[codsoc_sdp],1,4);
		$saldo=buscar_saldo_f810($lacuenta);
		if ($saldo > 0)
			$suma+=$saldo;
		else $suma-=$saldo;  // $r_310['saldo'];
		$lacuenta=trim($r_31['cuent_int']).'-'.substr($r_31[codsoc_sdp],1,4);
		$saldo=buscar_saldo_f810($lacuenta);
		if ($saldo < 0)
			$reintegro+=$saldo;
		else $reintegro-=$saldo;  // $r_310['saldo'];	
	}
	else $suma = $reintegro = 0;
	echo '</legend>';
	echo '<table align="center" class="basica 100 hover" width="500" border="1">';
	$cancelar=array();
	$registros=0;
	while($r_310=mysql_fetch_assoc($a_310)) {
		echo '<tr>';
		echo '<td>'.$r_310['nropre_sdp'].'</td>';
		echo '<td>'.$r_310['descr_pres'].'</td>';
		$lacuenta=trim($r_310['cuent_pres']).'-'.substr($r_200[cod_prof],1,4);
		$saldo=buscar_saldo_f810($lacuenta);
//		echo $lacuenta.'<br>';
		echo '<td align="right">'.number_format($saldo,2,".",",").'</td>';
//		echo '<td align="right">'.number_format(($r_310['monpre_sdp']-$r_310['monpag_sdp']),2,".",",").'</td>';
		$registros++;
		echo '<td class="centro azul"><input type="checkbox" id="cancelar'.$registros.'" name="cancelar'.$registros.'" value='.$r_310["nropre_sdp"] .' onClick="calccanc()" ';
		if ($_SESSION['numeroarenovar']==$r_310['nropre_sdp']) echo ' checked disabled="true" ';
		echo '></td></tr>' ;
	}
	echo "<input type = 'hidden' value ='".$registros."' name='registros' id='registros'>";
	echo "<input type = 'hidden' value ='".$micedula."' name='micedula' id='micedula'>";
	echo "<input type = 'hidden' value ='".$cedula."' name='cedula' id='cedula'>";
	echo "<input type = 'hidden' value ='".$marcados."' name='marcados' id='marcados'>";
//	echo "<input type = 'hidden' value ='".$montoprestamo."' name='montoprestamo' id='montoprestamo'>";
	echo '<tr>';
	echo '<td> Monto del Prestamo</td><td>';
	echo '<input align="right" name="montoprestamo" type="text" id="montoprestamo" size="12" maxlength="12" readonly="readonly" value ="'.number_format($montoprestamo,2,'.','').'"></td>';
	echo '<td>Descuentos Administrativos</td><td>';
	$descuentos=restaradministrativos($montoprestamo);
	echo '<input align="right" name="descuentosadm" type="text" id="descuentosadm" size="12" maxlength="12" readonly="readonly" value ='.number_format($descuentos,2,'.','').'></td>';
	echo '</tr>';

	echo '<tr>';
	echo '<td> Reintegros</td><td>';
	echo '<input align="right" name="reintegros" type="text" id="reintegros" size="12" maxlength="12" readonly="readonly" value ='.number_format($reintegro,2,'.','').'></td>';
	echo '<td></td><td>';
	echo '</td>';
	echo '</tr>';

	echo '<tr>';
	echo '<td> Total a Cancelar </td><td>';
	$neto=$montoprestamo-($suma+$descuentos)+$reintegro;
	echo '<input align="right" name="cancelados" type="text" id="cancelados" size="12" maxlength="12" readonly="readonly" value ='.number_format($suma,2,'.','').'></td>';
	echo '<td>Neto a Recibir</td><td>';
	echo '<input align="right" name="netoarecibir" type="text" id="netoarecibir" size="12" maxlength="12" readonly="readonly" value ='.number_format($neto,2,'.','').'></td>';
	echo '<tr>';
	echo '<td align="center" colspan="4"> '; 
	echo "<input type = 'submit' value = 'Continuar/Imprimir'>"; 
	echo '</td></tr>';
	echo '</table>';
	echo '</fieldset>';
	echo '</form>';
//	echo '</div>';
	$lafoto='fotos/'.substr($cedula,2,8).'.jpg';
	echo "<br><br><img src='".$lafoto."' width='156' height='156' border='0' />";

//**********************
	echo '</div>';
} 	// ($accion == "Restar")

if ($accion == "Concretar") {	// hacer los asientos y actualizar el prestamo, faltarian los fiadores
	$mostrarregresar=1;
	extract($_POST);
	$cedula = $_POST['cedula'];
	$sql_200="select * from sgcaf200 where ced_prof='$cedula'";
	$a_200=mysql_query($sql_200);
	$r_200=mysql_fetch_assoc($a_200);
	$elnumero = $_SESSION['elnumero'];
	$elprestamo = $_SESSION['elprestamo'];
	$referencia=$elnumero;
	$montoprestamo = $_SESSION['montoprestamo'];
	$micedula=substr($cedula,0,4).'.'.substr($cedula,4,3).'.'.substr($cedula,7,4);
	$sql_310="select * from sgcaf310, sgcaf360 where (cedsoc_sdp='$micedula') and (nropre_sdp='$elnumero') and (codpre_sdp = '$elprestamo') and (stapre_sdp='A' and ! renovado) and (codpre_sdp=cod_pres)";
//	echo $sql_310;
	$a_310=mysql_query($sql_310);
	$r_310=mysql_fetch_assoc($a_310);
	$hoy = date("Y-m-d");
	$b = $hoy;
	$albanco=$r_310['monpre_sdp'];
	$elasiento = date("ymd").$r_310['codsoc_sdp'];
	$ip = $_SERVER['HTTP_CLIENT_IP'];
	if (!$ip) {$ip = $_SERVER['REMOTE_ADDR'];}
	$registros=$_POST['registros'];
	if ($r_310['genera_com'] == 1){
		echo 'la830';
		echo "Generando encabezado contable <strong>$elasiento </strong> <br>";
		$sql = "INSERT INTO sgcaf830 (enc_clave, enc_fecha, enc_desco, enc_desc1, enc_debe, enc_haber, enc_item, enc_dif, enc_igual, enc_refer, enc_sw, enc_explic) VALUES ('$elasiento', '$b', '','',0,0,0,0,0,0,0,'')"; 
		if (!mysql_query($sql)) die ("El usuario $usuario no tiene permiso para añadir Asientos.<br>".$sql);

		// cargo prestamo al socio
		$laparte=$r_310['codsoc_sdp'];
		$cargo=trim($r_310['cuent_pres']).'-'.substr($laparte,1,4);
		if ($r_310['int_dif'] == 1) {
			$cuenta_diferido=trim($r_310['cuent_int']).'-'.substr($laparte,1,4);
			
		}
		echo "Generando cargos del asiento <strong>$elasiento </strong>  <br>";
		$debe=$r_310['monpre_sdp'];
		if ($debe != 0) {
			$cuenta1=$cargo;
			agregar_f820($elasiento, $b, '+', $cuenta1, $r_310['descr_pres'], $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
		}
		echo "Generando abonos del asiento <strong>$elasiento </strong>  <br>";
		$debe=$r_310['inicial'];
		$albanco-=$debe;
		if ($debe != 0) {
			$cuenta1=$cargo;
			agregar_f820($elasiento, $b, '-', $cuenta1, 'Inicial '.$r_310['descr_pres'], $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
		}


		for ($i=0;$i<$registros;$i++)		// no es necesarios revisar el check si aparece es porq estan seleccionados para hacer el asiento 
		{
			$variable='cancelar'.($i+1);
//			echo '<br><br>'.' variable '.$variable.' contenido = '.$$variable;
//			echo $_POST['cancelar1'];
//			echo '<br><br>';
			if (!empty($$variable)) 
			{
				echo "Cancelando prestamos / Generando Registros contables del asiento <strong>$elasiento </strong> del prestamo numero <strong>".$$variable."</strong><br>";
				$s310="select cuent_pres, codsoc_sdp, descr_pres, cuent_int from sgcaf310, sgcaf360 where (cedsoc_sdp='$micedula') and (nropre_sdp = '".$$variable."') and (stapre_sdp='A' and ! renovado) and (codpre_sdp=cod_pres)";
//				echo $s310;
				$a310=mysql_query($s310);
				$r310=mysql_fetch_assoc($a310);
				// saldo pendiente del prestamo
				$cuenta1=trim($r310['cuent_pres']).'-'.substr($r310[codsoc_sdp],1,4);
				$debe=buscar_saldo_f810($cuenta1);
				$cargar=(($debe>0)?'-':'+');
				$debe=abs($debe);
				agregar_f820($elasiento, $b, $cargar, $cuenta1, 'Canc.'.$r310['descr_pres'], $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
				if ($debe > 0)
					$albanco-=$debe;
				else $albanco+=$debe;
// 				echo $albanco.'<br>';
				$sql_312="insert into sgcaf312 (tipo, cuento, cuenta, monto, numero, cedula) VALUES ('$cargar','Canc.".$r310['descr_pres']."', '$cuenta1', $debe, '$elnumero','$micedula')";
				echo $sql_312.'<br>';
				$resultado=mysql_query($sql_312);

				// intereses
				$cuenta1=trim($r310['cuent_int']).'-'.substr($r310[codsoc_sdp],1,4);
				$debe=buscar_saldo_f810($cuenta1);
				$cargar=(($debe<0)?'+':'-');
				if ($debe < 0)
					$albanco-=$debe;
				else $albanco+=$debe;
//				echo $albanco.'<br>';

				$debe=abs($debe);
				agregar_f820($elasiento, $b, $cargar, $cuenta1, 'Int.'.$r310['descr_pres'], $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
				$sql_312="insert into sgcaf312 (tipo, cuento, cuenta, monto, numero, cedula) VALUES ('$cargar','Int.".$r310['descr_pres']."', '$cuenta1', $debe, '$elnumero','$micedula')";
				echo $sql_312.'<br>';
				$resultado=mysql_query($sql_312);
				// cancelar el prestamo
			}
		}
		// coloco las deducciones obligatorias activas
		$sql_deduccion="select * from sgcaf311 where activar = 1";
		$a_deduccion=mysql_query($sql_deduccion);
		$cargo=trim($r_360['cuent_pres']).'-'.substr($laparte,1,4);
		// cargo prestamo al socio
		$debe = $monpre_sdp;
		if ($r_360['int_dif'] == 1) {
			$cuenta_diferido=trim($r_360['cuent_int']).'-'.substr($laparte,1,4);
		}
		echo "Generando cargos del asiento <strong>$elasiento </strong>  <br>";
		$debe=$r_310['intereses']; // $intereses_diferidos;
		$intereses_diferidos=$debe;
		$albanco-=$debe;
		if ($debe != 0) {
			$cuenta1=$cuenta_diferido; // .'-'.substr($laparte,1,4);
			agregar_f820($elasiento, $b, '-', $cuenta1, $r_310['descr_pres'], $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
		}
		$d_obligatorias=0;
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
//			echo $sql_312;
			$resultado=mysql_query($sql_312);
		}
		$debe = $albanco; //  - ($intereses_diferidos + $d_obligatorias); 
		$neto_cheque = $debe;
		if ($debe != 0) {
			$sql="select * from sgcaf000 where tipo='CtaSocxPag'";
			$result=mysql_query($sql); // or die ("<p />El usuario $usuario no pudo conseguir la cuenta x pagar<br>".mysql_error()."<br>".$sql);
			$cuentas=mysql_fetch_assoc($result);
//			echo 'cuenta buscada '.$cuentas['nombre'].'<br>';
			$cuenta1=trim($cuentas['nombre']).'-'.substr($laparte,1,4);
//			echo 'cuenta mostrada '.$cuenta1.'<br>';
			agregar_f820($elasiento, $b, '-', $cuenta1, $r_310['descr_pres'], $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
		}
		$sql="update sgcaf310 set netcheque = $neto_cheque where cedsoc_sdp = '$micedula' and nropre_sdp='$referencia'";
//		echo $sql;
		$resultado=mysql_query($sql);
		$_SESSION['elasiento']=$elasiento;		
		actualizar_acta($nroacta,$debe);
		if ($r_310['garantia']==2) 
			solicitar_fiadores($elnumero,$cedula);
		else 
			if ($r_310['genera_pl'] == 1) {
				echo 'Preparando para la impresion<br>';
				echo "<a target=\"_blank\" href=\"solprepdf.php?cedula=$cedula\" onClick=\"info.html\', \'\',\'width=250, height=190\')\">Imprimir Planilla de Préstamo </a>"; 
			}
		else echo '<h2>Este tipo de préstamo esta configurado para no realizar impresión de planilla</h2>';
	}
	/// *****imprimri en otro momento, faltan los fiadores*****	
}	// ($accion == "Concretar")
if ($accion == "Eliminar") {	// eliminar el fiador
	$elnumero=$_GET['nropre'];
	$lacedula=$_GET['cedula'];
	$elregistro=$_GET['registro'];
//	echo $elnumero. ' - '.$elregistro;
	$sql_320="delete from sgcaf320 where registro='$elregistro'";
	$a_320=mysql_query($sql_320);
//	echo $sql_320;
	solicitar_fiadores($elnumero,$lacedula);
} // fin d e($accion == "Eliminar")

//-----------------------------------
if ($accion == "Fiadores") {	// los fiadores
	$elnumero=$_SESSION['elnumero'];
	$lacedula=$_SESSION['lacedula'];
	$cedulafiador=$_POST['_unacedula'];
//	echo 'guardo el fiador y vuelvo a solicitar '.$elnumero. ' - ' .$lacedula;
	$sql_200="select cod_prof from sgcaf200 where ced_prof = '$cedulafiador'";
//	echo $sql_200;
	$a_200=mysql_query($sql_200);
	$r_200=mysql_fetch_assoc($a_200);
	$sql_310="select cod_prof from sgcaf200,sgcaf310 where (nropre_sdp='$elnumero') and (cod_prof = codsoc_sdp)";
//	echo $sql_310;
	$a_310=mysql_query($sql_310);
	$r_310=mysql_fetch_assoc($a_310);
	if (mysql_num_rows($a_200) > 0) // existe el fiador
	{
		$elfiador=$r_200['cod_prof'];
		$afianzado=$r_310['cod_prof'];
		$monto=$_POST['monto_fianza'];
		$ip = $_SERVER['HTTP_CLIENT_IP'];
		if (!$ip) {$ip = $_SERVER['REMOTE_ADDR'];}
		$sql_320="insert into sgcaf320 (codsoc_fia, nropre_fia, codfia_fia, monlib_fia, tipmov_fia, monto_fia, ip) values 
		('$afianzado', '$elnumero','$elfiador',0,'F',$monto,'$ip')";
//		echo $sql_320;
		$a_320=mysql_query($sql_320);

	}
	else echo '<h2>Informacion del fiador no existe!!!!</h2>';
	solicitar_fiadores($elnumero,$lacedula);
}	// fin de ($accion == "Fiadores")

if ($mostrarregresar==1) { // ($accion == "Buscar") or ($accion == "Ver") or ($accion="EscogePrestamo")) {
	echo '<form enctype="multipart/form-data" name="formdepie" method="post" action="propag.php?accion=Buscar">';
	echo '<input type = "hidden" value ="'.$_SESSION['cedulasesion'].'" name="cedula" id="cedula">';
// 	echo 'la cedula '.$_SESSION['cedulasesion'];
	echo '<div style="clear:both"></div>';
	echo '<p /><div class="noimpri" style="clear:both;text-align:center">';
	echo '<input type="submit" name="boton" value="regresar" tabindex="3">';
	echo '</div>';
	echo '</form>';
}
else 
	include("pie.php");
?>
</body></html>


<?php

function solicitar_fiadores($elnumero,$lacedula)
{
	echo '<div id="div1">';
	$_SESSION['elnumero']=$elnumero;
	$_SESSION['cedula']=$lacedula;
	echo "<form enctype='multipart/form-data' action='propag.php?accion=Fiadores' name='form1' id='form1' method='POST' onsubmit='return valfiadores(form1)'>";
	// &elnumero=$elnumero&cedula=$cedula&
	echo "<form action='propag.php?accion=Buscar' name='form1' method='post'>";
	$micedula=substr($lacedula,0,4).'.'.substr($lacedula,4,3).'.'.substr($lacedula,7,4);
	$sql_310="select * from sgcaf310, sgcaf360, sgcaf200 where (cedsoc_sdp='$micedula') and (nropre_sdp='$elnumero') and (stapre_sdp='A' and ! renovado) and (codpre_sdp=cod_pres) and (ced_prof='$lacedula') limit 1";
	// and (codpre_sdp = '$elprestamo') 
//	echo $sql_310;
	$a_310=mysql_query($sql_310);
	$r_310=mysql_fetch_assoc($a_310);
	echo '<fieldset><legend> Actualizacion de Fianzas: '.trim($r_310['descr_pres']). ' / '.trim($r_310['ape_prof']). ', '.trim($r_310['nombr_prof']).' / '.$r_310['ced_prof'].' / '.$r_310['cod_prof'].' / '.$elnumero.'</legend>';
	$elcodigo=$r_310['cod_prof'];
//	$sql_320="select * from sgcaf320, sgcaf200 where (nropre_fia = '$elnumero') and (codsoc_fia='$elcodigo') and (ced_prof='$cedula')";
	$sql_320="select * from sgcaf320, sgcaf200 where (nropre_fia = '$elnumero') and (codfia_fia=cod_prof)";
//	echo $sql_320;
	$a_320=mysql_query($sql_320);
	echo "<table class='basica 100 hover' width='750'><tr>";
	echo '<th colspan="1"></th><th width="80">Código</th><th width="80">Cédula</th><th width="200">Nombre</th><th width="280">Monto Fianza</th></tr>';
	$registros=$total=0;
	while($r_320=mysql_fetch_assoc($a_320)) {
////////////////////////////
		$registros++;
//			echo '<td class="centro azul"><input type="checkbox" id="eliminar'.$registros.'" name="eliminar'.$registros.'" value='.$r_320["registro"] .' onClick="eliminafianza()"> </td>' ;
		echo '<td class="centro azul">';
		echo "<a href='propag.php?accion=Eliminar&cedula=".$lacedula."&nropre=".$elnumero."&registro=".$r_320['registro']."& '  onClick='return conf_elim_fiadores()'>";
		echo "<img src='imagenes/16-em-cross.png' width='16' height='16' border='0' title='Eliminar' alt='Eliminar'/>";
		echo "</a></td>";
		echo '<td>'.$r_320['codfia_fia'].'</td>';
		echo '<td>'.$r_320['ced_prof'].'</td>';
		echo '<td>'.trim($r_320['ape_prof']). ', '.trim($r_320['nombr_prof']).'</td>';
		echo '<td align="right">'.number_format($r_320['monto_fia'],2,'.',',').'</td></tr>';
		$total+=$r_320['monto_fia'];
	}
	$unacedula='';
	echo '<tr><td align="right" colspan="4">Total Fianzas</td><td align="right">'.number_format($total,2,'.',',').'</td></tr>';
	echo '</table>';
	//-----------------
	echo "<table class='basica 100' width='500'>";
	echo '<tr><th width="400">Cedula o Nombre del Fiador</th><th width="100">Monto de la Fianza</th></tr>';
	echo '<tr><td width="400"> ';
	echo "<input type='text' size='20' tabindex='1' name='_unacedula' id='inputString5' onKeyUp='lookup5(this.value);' onBlur='fill5();' value ='$_unacedula' autocomplete='off'/>";
	echo '<div class="suggestionsBox5" id="suggestions5" style="display: none;">';
	echo '<img src="upArrow.png" style="position: relative; top: -12px; left: 70px; "  alt="upArrow" />';
	echo '<div class="suggestionList5" id="autoSuggestionsList5">';
	echo '</div>';
	echo '</div>';
	echo '</td><td width="100">';
	echo "<input type = 'text' size='12' maxlength='12' name='monto_fianza' tabindex='2' value ='0.00'>";
	echo '</td></tr><tr><td colspan="2">';
//	echo $registros. ' '.$r_310['fiadores'];
	if ($registros < $r_310['n_fia_pres'])
		echo "<input type='submit' name='boton' value=\"Guardar\" tabindex='3'>";
	else echo 'Tiene la cantidad maxima de fiadores';
	//-----------------
	echo '</td></tr></table></form>';
	echo '</fieldset>';
	echo '</div>';	
	if ($r_310['genera_pl'] == 1) {
//		echo 'Preparando para la impresion<br>';
		echo "<a target=\"_blank\" href=\"solprepdf.php?cedula=$lacedula\" onClick=\"info.html\', \'\',\'width=250, height=190\')\">Imprimir Planilla de Préstamo </a>"; 
	}
}

//---------------------
//---------------------
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
		$factor = 24;
	else 
		if ($r_200['tipo_socio']== 'E')
			$factor = 24;
		else 
			$factor = 12;
	echo "<input type = 'hidden' value ='".$factor."' name='factor_division' id='factor_division'>";
	// determino nuevo numero de prestamo
	$sql_310="select nropre_sdp from sgcaf310 where (cedsoc_sdp='$micedula') and (substr(nropre_sdp,1,5)='$laparte') order by nropre_sdp desc limit 1";
//	echo $sql_310;
	$a_310=mysql_query($sql_310);
	$elnumero=mysql_fetch_assoc($a_310);
	$elnumero=substr($elnumero['nropre_sdp'],5,3);
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
	$sql_acta="select * from sgcafact order by fecha desc limit 1";
	$las_actas=mysql_query($sql_acta);
	$el_acta=mysql_fetch_assoc($las_actas);
	echo '<td>Fecha de solicitud </td><td>'.$hoy.'</td>';
    echo '<td>Monto Pagado </td><td  align="right">'.number_format(0,$deci,$sep_decimal,$sep_miles).'</td></tr>';
	echo '<tr>';
	echo '<td>1er Descuento </td><td>';
	if ($r_360['dcto_sem']==1) {
		echo convertir_fechadmy($el_acta['f_dcto']);
		echo "<input type = 'hidden' value ='".$primerdcto."' name='primerdcto' id='primerdcto'>";
		}
	else echo "<input type = 'text' value ='".$primerdcto."' name='primerdcto' id='primerdcto'>";
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
	echo '<input align="right" name="cuota" type="text" id="cuota" size="12" maxlength="12" readonly="readonly" value ="0.00"';
	echo '</td></tr>';
	echo '<tr>';
	$nroacta=$el_acta['acta'];
	$fechaacta=$el_acta['fecha'];
	$elasiento = date("ymd").$codigo;
	echo '<tr><td>Intereses: </td><td align="right">';
	echo '<input align="right" name="interes_diferido" type="text" id="interes_diferido" size="12" maxlength="12" readonly="readonly" value ="0.00"></td>';
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
			$_SESSION['disponibilidadprestamo']=1234; // $disponible; 
//			$_SESSION['disponibilidadprestamo']=$disponible; 
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
function mostrar_proyeccion($cedula,$nropre)
{
	// no prefix
	// works only in PHP 5 and later versions
//	$token = md5(uniqid());

	// better, difficult to guess
//	$better_token = md5(uniqid(mt_rand(), true));
	
//	echo 'token '.$token . ' len '.strlen($token);
//	echo '<br>better '.$better_token. ' len '.strlen($better_token);

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
	echo '<fieldset><legend>Proyeccion de Pagos de '.trim($r_310['descr_pres']). ' para <br>'.trim($r_200['ape_prof']). ', '.trim($r_200['nombr_prof']).'</legend>';
	$interes=$r_310['interes_sd'];
	$cuotas=$r_310['nrocuotas'];
	$primer_dcto=$r_310['f_soli_sdp'];
//	echo 'rpimer '.$primer_dcto;
	$capital=$r_310['monpre_sdp'];
	visualice($capital, $primer_dcto, $interes, $cuotas, $r_310, $c1, $cu, $ia, $cu, $ac, $ta, $tc, $i1, $better_token);
	echo '<input type="submit" name="Submit" value="Imprimir Proyeccion de Pagos" onClick="abrirVentana(';
	echo "'";
	echo $better_token;
//	echo "&c1=".$c1."&cu=".$cu."&ia=".$ia."&ac=".$ac."&ta=".$ta."&tc=".$tc."&i1=".$i1;
	echo "&otro=".$better_token.$better_token;
	echo "&aa=$capital&bb=$cuotas&ia=$ia[$cuotas]&cc=$cu[1]&dd=$interes&socio=";
	echo trim($r_200['ape_prof']). ', '.trim($r_200['nombr_prof']);
	echo "&prestamo=".trim($r_310['descr_pres']);
	echo "'";
	echo ');">  ';

	echo '</fieldset>';
	$lafoto='fotos/'.substr($cedula,2,8).'.jpg';
//	echo "<img src='".$lafoto."' width='156' height='156' border='0' />";
//	for ($k=1;$k<=$cuotas;$k++) {
//		echo $i1[$k] . ' - '.$cu[$k] . ' - '.$c1[$k]. ' - '.$ac[$k]. ' - '.$ta[$k].' <br>';
//	}
	echo '<div id="display" style="float: right; clear: both;"></div>';
	echo "<div id='div1'>";

/*
	echo "<form target=\"_blank\" action='propagpdf.php?accion=Buscar' name='form1' method='post'>"; 
	echo '<fieldset><legend>Recopilando información Para Listado </legend>'; 
	echo '<h2>Preparando información...</h2>';
	echo '<input name="c1" type="hidden" value="$c1">';
	echo '<input id="i1" name="i1" type="hidden" value="'.$i1.'">';
	echo '<input type="submit" name="Submit" value="Imprimir Proyeccion de Pagos" >';
*/

/*
echo "<a target=\"_blank\" href=\"regbeim.php?cedula=$cedula\" onClick=\"info.html\', \'\',\'width=250, height=190\')\">Imprimir Planilla</a>"; 
*/
	echo '</div>';
	echo '</legend>';
	echo '</form>';
//$c1, $cu, $ia, $cu, $ac, $ta, $tc, $i1
}	


function visualice($capital, $fecha, $interes, $cuotas, $r_310, &$c1, &$cu, &$ia, &$cu, &$ac, &$ta, &$tc, &$i1, &$better_token) {
//	echo $fecha;
	if ($r310['int_dif']==1)	// tipo de interes
		$z=$capital / $cuotas ;
	else	
		$z=cal_int($interes, $cuotas, $capital,$factor_divisible = 24,$z=0,&$i2);
	$better_token = md5(uniqid(mt_rand(), true));	
	$elmonto = $capital;
	$hoy = date("Y-m-d");
	$ip = $_SERVER['HTTP_CLIENT_IP'];
	if (!$ip) {$ip = $_SERVER['REMOTE_ADDR'];}
	$lacuota= $z;
	$c1 = $cu = $ia = $cu = $ac = $ta = $tc = array();
	$c1[0] = $capital;
	$k = 0;
	/*
		k = contador
		ia = interes acumulado
		cu = cuota
		ac = acumulado
		tc = total cuota
		ta = total acumulado
		i1 = interes
	*/
	for ($k=1;$k<=$cuotas;$k++) {
		$i1[$k] = $c1[$k-1] * $i2;
		$cu[$k] = $lacuota - $i1[$k];
		$c1[$k] = $c1[$k-1] - $cu[$k];
		$ia[$k] = $ia[$k-1] + $i1[$k];
		$ac[$k] = $ac[$k-1] + $cu[$k];
		$ta[$k] = $ta[$k-1] + $z;
		
		if ($k==1)	
			$comando="insert into sicaf901 (id, fecha) values ('$better_token','$fecha')";
		else 
			$comando="update sicaf901 set fecha=adddate(fecha,7) where id='$better_token'";
//		echo $comando.'<br>';
		$result = mysql_query($comando) or die ('Error 200-1 <br>'.$comando.'<br>'.mysql_error());
		$comando="select fecha from sicaf901 where id='$better_token'";
		$result = mysql_query($comando) or die ('Error 200-1 <br>'.$comando.'<br>'.mysql_error());
		
/*
			$ultimafecha=explode('-',$ultimafecha);
			$ultimafecha = mktime(0,0,0,$ultimafecha[1],$ultimafecha[2]+7,$ultimafecha[2]); 
//$fecha2 = mktime(0,0,0,date("m"),date("d")+8,date("Y")); 
//$fecha3 = mktime(0,0,0,date("m")+2,date("d")-5,date("Y")); 

//Luego, para acuparla:

echo date("d/m/Y", $ultimafecha); //devuelve la fecha actual
// echo date("d/m/Y", $fecha2); //le suma 8 días a la fecha actual
// echo date("d/m/Y", $fecha3); //le resta 5 días y la suma 2 meses a la fecha actual.
		}
/*
//		$fecha=$fecha+7;
//		SELECT date_format(con_ultfec+14,'%d/%m/%y') AS ultfechax FROM sgcaf8co
*/
		$registro=mysql_fetch_assoc($result);
		$ultimafecha=$registro['fecha'];
		$comando="insert into sicaf900 (id, nro_cta, capital, fechaa, interes, interesa, amor_ac, acumulado, fecha, ip, amor_cap) values ('$better_token', '$k', '$c1[$k]','$ultimafecha', '$i1[$k]', '$ia[$k]', '$ac[$k]','$ta[$k]','$hoy','$ip','$cu[$k]')";
		$result = mysql_query($comando) or die ('Error 200-1 <br>'.$comando.'<br>'.mysql_error());
//		echo $i1[$k] . ' - '.$cu[$k] . ' - '.$c1[$k]. ' - '.$ac[$k]. ' - '.$ta[$k].' <br>';
	}
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


*/
?>
