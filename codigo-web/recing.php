<?php  
include("head.php");
include("paginar.php");
if (!$link OR !$_SESSION['empresa']) {
	include("noempresa.php");
	exit;
}
$mostrarregresar=0;
?>
<script language="Javascript" src="selec_fecha.js" type='text/javascript'></script>
<script language="javascript">

function abrirVentana(elorden)
{
window.open("recingpdf.php?orden="+elorden,"parte1","top=0,left=395,status=no,toolbar=no,scrollbar=yes,location=no,type=fullWindow,fullscreen");	
}
</script>

<script src="ajxing.js" type="text/javascript"></script>
<?
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


if ($accion == "Renovar") {	// seleccionar el tipo de prestamo nuevo de renovacion
	$_SESSION['numeroarenovar']=$_GET['nropre'];
}
if ($accion == "Renovacion") {	// selecciono el tipo de prestamo
	$mostrarregresar=1;
	echo '<div id="div1">';
	$cedula = $_GET['cedula'];
	$elprestamo = $_GET['nropre'];
	$temp = "";
	echo "<form enctype='multipart/form-data' action='recing.php?accion=Solicitar' name='form1' id='form1' method='post' onsubmit='return valpre(form1)'";
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

//		$accion = 'Editar'; 

	echo '<div id="div1">';
	$sql='SELECT * FROM sgcaf200 WHERE ced_prof= "'.$cedula.'"';
	$result = mysql_query($sql) or die ('Error 200-1 <br>'.$sql.'<br>'.mysql_error());
	echo "<input type = 'hidden' value ='".$cedula."' name='cedula'>";
	$temp = "";
	echo "<form enctype='multipart/form-data' action='recing.php?accion=GenerarRecibos' name='form1' method='post' onsubmit='return siono(".'"Estan correctos los datos suministrados para la generacion del recibo"'.")'>";
	pantalla_recibo($result,$cedula);
	$elstatus=$_SESSION['elstatus'];
	echo '<fieldset><legend>Información de Prestamos Actuales </legend>';
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

		$estacedula=substr($cedula,0,4).'.'.substr($cedula,4,3).'.'.substr($cedula,7,3);
		$sql = "SELECT * FROM sgcaf310,sgcaf360 WHERE (cedsoc_sdp = '$estacedula' and stapre_sdp='A' and (! renovado)) and codpre_sdp=cod_pres ORDER BY codpre_sdp";
		$rs = mysql_query($sql);
		
		echo "<table class='basica 100 hover' width='750'><tr>";
		echo '<th colspan="1">Número</th><th>Tipo</th><th width="80">Saldo</th><th width="100">Habilitar</th><th width="100">Monto a Cancelar</th></tr>';

		while($r_310=mysql_fetch_assoc($rs)) {
			echo "<tr>";
			echo '<td>'.$r_310['nropre_sdp'].'</td>';
			echo '<td>'.$r_310['descr_pres'].'</td>';
			$lacuenta=trim($r_310['cuent_pres']).'-'.substr($r_310[codsoc_sdp],1,4);
//			echo '<td>'.$lacuenta.'</td>';
			$saldo=buscar_saldo_f810($lacuenta);
			echo '<td align="right">'.number_format($saldo,2,".",",").'</td>';
			$registros++;
			echo '<td class="centro azul"><input type="checkbox" id="cancelar'.$registros.'" name="cancelar'.$registros.'" value='.$r_310["nropre_sdp"] .' onClick="activar()" ';
			if ($saldo <= 0) echo ' disabled="true" ';  // checked 
			echo '></td>';
			echo '<input type="hidden" id="cancelarh'.$registros.'" name="cancelarh'.$registros.'" value='.$saldo .' >';
			echo '<td class="centro azul"><input type="textbox" maxlength="12" size="12" id="cancelart'.$registros.'" name="cancelart'.$registros.'" value=';
			if ($saldo <= 0) echo '0 disabled=true ';
			else echo '0 disabled=true ';
//			else echo $saldo .' disabled=true ';
			echo 'onBlur="revisarmonto('.$registros.')" ';
			echo ' </td>';
			echo '</tr>' ;
		}
		echo "<input type = 'hidden' value ='".$registros."' name='registros' id='registros'>";
		echo "<input type = 'hidden' value ='".$micedula."' name='micedula' id='micedula'>";
		echo "<input type = 'hidden' value ='".$cedula."' name='cedula' id='cedula'>";
		echo "<input type = 'hidden' value ='".$marcados."' name='marcados' id='marcados'>";
		echo '<tr>';
		
		echo '<td align="right" colspan="1"> Depositado en </td><td>';
		$sqlbanco='select * from sgcaf843 where recibirpago=1';
		$resultado=mysql_query($sqlbanco);
		echo '<select name="elbanco" size="1">';
		while ($fila2 = mysql_fetch_assoc($resultado)) {
			echo '<option value="'.$fila2['cod_banco'].'">'.$fila2['cue_banco'].' - '.$fila2['nombre_ban'].' - '.$fila2['nro_cta_ba'].'</option>'; }
		echo '</select> *'; 
		echo '</td>';

		echo '<td align="right" colspan="2">Forma de Pago</td><td>';
		$sqlbanco='select nombre from sgcaf000 where tipo="FormaPago"';
		$resultado=mysql_query($sqlbanco);
		echo '<select name="laforma" size="1">';
		while ($fila2 = mysql_fetch_assoc($resultado)) {
			echo '<option value="'.$fila2['nombre'].'">'.$fila2['nombre'].'</option>'; }
		echo '</select> *'; 
		echo '</td></tr>';

		echo '<tr>';
		echo '<td align="right" colspan="1">Número de Voucher </td><td >';
		echo '<input align="right" name="voucher" type="text" id="voucher" size="12" maxlength="12" value =" "></td>';
		echo '</td>';
		echo '<td align="right" colspan="2"> Monto de cancelación</td><td class="centro azul">';
		echo '<input align="right" name="montoprestamo" type="text" id="montoprestamo" size="12" maxlength="12" readonly="readonly" value ="'.number_format($montoprestamo,2,'.','').'"></td>';
		echo '</td></tr>';
		echo '<tr><td align="left" colspan="1">Fecha de Deposito: </td><td colspan="1">';
?>
		<script type="text/javascript">
		setActiveStyleSheet(document.getElementById("defaultTheme"), "green");
		</script>
		<input type="text" name="date3" id="sel3" size="12" readonly><input type="reset" value=" ... " onClick="return showCalendar('sel3', '%d/%m/%Y');">
<?php
		echo '</td>';
		echo '</tr>';
		echo '<tr><td align="center" colspan="5">';
		echo "<input id='continuar' name='continuar' type = 'submit' value = 'Generar recibo' disabled=true>"; 
		echo '</td></tr>';
		echo '</table>';
		echo '</fieldset>';
		echo '</div>';
		echo '</form>';




/*
		if (pagina($numasi, $conta, 20, "Prestamos Activos", $ord)) {$fin = 1;}
// 		bucle de listado
		while($row=mysql_fetch_assoc($rs)) {
			echo "<tr>";

		echo "<td class='centro'><a href='extractoctas3.php?cuenta=".trim($row['cuent_pres']).'-'.substr(trim($row['codsoc_sdp']),1,4)."&datos=no&'><img src='imagenes/page_wizard.gif' width='16' height='16' border='0' /></a></td>";
		echo "<td class='centro'><a href='recing.php?accion=Ver&cedula=".$cedula."&nropre=".$row['nropre_sdp']."'><img src='imagenes/page_user_dark.gif' width='16' height='16' border='0' /></a></td>";
		echo "<td class='centro'>";
		if ($row['renovacion']>1)
			if ($row['ultcan_sdp'] >= $row['renovacion']) {
				echo "<a href='recing.php?accion=Renovar&cedula=".$cedula."&nropre=".$row['nropre_sdp']."'>";
				echo "<img src='imagenes/action_refresh_blue.gif' width='16' height='16' border='0' />";
				echo "</a>";
			}
			else echo ' ';
		else if ($row['renovacion'] == 1){ 
				echo "<a href='recing.php?accion=ReAjustar&cedula=".$cedula."&nropre=".$row['nropre_sdp']."'>";
				echo "<img src='imagenes/icon_get_world.gif' width='16' height='16' border='0' />";
				echo "</a>";
			}
			else echo ' ';

		echo "</td><td>";
		
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
*/
	}
}	// fin de ($accion == 'Buscar') 
		
if (!$accion) {
	echo "<form action='recing.php?accion=Buscar' name='form1' method='post'>";
    echo '  C&eacute;dula ';
	echo '<input name="cedula" type="text" id="cedula" value=""  size="10" maxlength="10" />';
	echo "<input type = 'submit' value = 'Buscar'>";
	$_SESSION['numeroarenovar']='';
	$_SESSION['cedulasesion']=''; 
	echo '</form>';
}	// fin de (!$accion) 
if ($accion == 'Ver') {
	echo "<div align='center' id='div1'>";
	$mostrarregresar=1;
	$cedula=$_GET['cedula'];
	$nropre=$_GET['nropre'];
	mostrar_prestamo($cedula,$nropre);
	echo "</div>";
}	// fin de ($accion == 'Ver')

/*
if (($accion == "Editar") or ($accion=="Renovar")) {	// muestra datos para prestamo
	echo '<div id="div1">';
	$sql='SELECT * FROM sgcaf200 WHERE ced_prof= "'.$cedula.'"';
	$result = mysql_query($sql) or die ('Error 200-1 <br>'.$sql.'<br>'.mysql_error());
	echo "<input type = 'hidden' value ='".$cedula."' name='cedula'>";
	$temp = "";
	echo "<form enctype='multipart/form-data' action='recing.php?accion=GenerarRecibos' name='form1' method='post' onsubmit='return valsoc(form1)'>";
	pantalla_recibo($result,$cedula);
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
	if (!$_SESSION['numeroarenovar']) echo "<input type = 'submit' value = 'Nuevo Prestamo'></form>\n"; 
	else echo "<input type = 'submit' value = 'Renovar por'></form>\n"; 
	echo '</fieldset>';
	echo '</div>';
} 	// fin de ($accion == "Editar")
*/

if ($accion == "GenerarRecibos")  {	// selecciono el tipo de prestamo
	$mostrarregresar=0;
	extract($_POST);
	echo '<div id="div1">';
	$sql_200="select * from sgcaf200 where ced_prof='$cedula'";
	$a_200=mysql_query($sql_200);
	$r_200=mysql_fetch_assoc($a_200);
	$codigo=$r_200['cod_prof'];
	$nombre=$r_200['ape_prof'] . ' '. $r_200['nombr_prof'];
	$micedula=substr($cedula,0,4).'.'.substr($cedula,4,3).'.'.substr($cedula,7,4);
	$sqlnumero='select con_compr from sgcaf8co limit 1';
	$a_numero=mysql_query($sqlnumero);
	$r_numero=mysql_fetch_assoc($a_numero);
	$elnumero=$r_numero['con_compr']+1;
	$actualiza="update sgcaf8co set con_compr = '$elnumero' limit 1";
	$a_numero=mysql_query($actualiza);
	$elnumero=$elnumero-1; // $r_numero['con_compr'];
	$elnumero=ceroizq($elnumero,8);
//	echo $elnumero;
	$elnumero=substr($elnumero,5*-1);	// simular la funcion rigth
//	echo $elnumero;
	$elnumero=ceroizq($elnumero,5);
//	echo $elnumero;
	$elasiento = date("ymd").$elnumero;
	$_SESSION['elasiento']=$elasiento;
	$albanco=0;
	$deposito=ceroizq($voucher,20);
	$deposito=substr($deposito,10*-1);
	$referencia="select nro_rec from sgcaf370 order by nro_rec desc limit 1";
	$a_370=mysql_query($referencia);
	$r_370=mysql_fetch_assoc($a_370);
	$referencia=$r_370['nro_rec']+1;
	$referencia=ceroizq($referencia,6);
	$hoy = date("Y-m-d H:i:s" );
	$b = date("Y-m-d" );
	$proceso = convertir_fecha($_POST['date3']);
	$sql_370="insert into sgcaf370 (nro_rec, cod_prof, nombre, monto, fecha, ip, proceso) values ('$referencia', '$codigo', '$nombre',0,'$hoy','$ip', '$proceso')";
	$a_370=mysql_query($sql_370) or die(mysql_error());
	$ip = $_SERVER['HTTP_CLIENT_IP'];
	if (!$ip) {$ip = $_SERVER['REMOTE_ADDR'];}
	$des='P/R Abono Prest.';
	$sql = "INSERT INTO sgcaf830 (enc_clave, enc_fecha, enc_desco, enc_desc1, enc_debe, enc_haber, enc_item, enc_dif, enc_igual, enc_refer, enc_sw, enc_explic) VALUES ('$elasiento', '$proceso', '','',0,0,0,0,0,0,0,'')"; 
		if (!mysql_query($sql)) die ("El usuario $usuario no tiene permiso para añadir Asientos.<br>".$sql);

	echo "Generando Registros contables del asiento <strong>";
	echo "<a target=\"_blank\" href='editasi2.php?asiento=$elasiento'>$elasiento </a>";
	echo "</strong> para el recibo de ingreso numero <strong>".$$referencia."</strong><br>";
	for ($i=0;$i<$registros;$i++)
	{
		$variable='cancelar'.($i+1);
//		echo $variable; 
//			echo '<br><br>'.' variable '.$variable.' contenido = '.$$variable;
//			echo $_POST['cancelar1'];
//			echo '<br><br>';
		if (!empty($$variable)) 
		{
			$des.=$$variable.' ';
			
			$s310="select cuent_pres, codsoc_sdp, descr_pres, cuent_int, monpre_sdp, cuota_ucla, registro from sgcaf310, sgcaf360 where (cedsoc_sdp='$micedula') and (nropre_sdp = '".$$variable."') and (stapre_sdp='A' and ! renovado) and (codpre_sdp=cod_pres)";
//				echo $s310;
//             echo $s310; 
			$a310=mysql_query($s310);
			$r310=mysql_fetch_assoc($a310);
			// saldo pendiente del prestamo
			$cuenta1=trim($r310['cuent_pres']).'-'.substr($r310[codsoc_sdp],1,4);
//			$debe=buscar_saldo_f810($cuenta1);
			$cargar='-'; 
			$anterior='cancelarh'.($i+1);
			$anterior=abs($$anterior);
			$debe='cancelart'.($i+1);
			$debe=abs($$debe);
			$np=$$variable;
			agregar_f820($elasiento, $proceso, $cargar, $cuenta1, 'Abono '.$r310['descr_pres'], $debe, $haber, 0,$ip,0,$deposito,'','S',0); 
			$albanco+=$debe;
			$sql_375="insert into sgcaf375 (nro_rec, nro_che, fecha, banco, monto, forma, ip, nro_pre) VALUES ('$referencia','$deposito','$hoy','$elbanco',$debe, '$laforma','$ip','$np')";
//			echo $sql_375.'<br>';
			$resultado=mysql_query($sql_375);
			// actualizar el 310
			$registro=$r310['registro'];
			$saldo=$r310['monpre_sdp']-($anterior - $debe);
//			echo $r310['monpre_sdp'].' - '.$saldo .' - '.$anterior.' - '. $debe.'<br>';
			$cuotaspagadas=$debe / $r310['cuota_ucla'];
			$sql310="update sgcaf310 set monpag_sdp = monpag_sdp + '$debe' ";
			if ($saldo >= $r310['monpre_sdp'])
				$sql310.=", stapre_sdp = 'C', renovado = 1 ";
			else $sql310.=", ultcan_sdp = ultcan_sdp + '$cuotaspagadas' ";
			$sql310.=" where registro = '$registro'";
//			echo $sql310;
			$act310=mysql_query($sql310);
			// actualizar el 320
			actualizar_fiador($r310['codsoc_sdp'],$debe,$np);
		}
	}
	$des.=' segun '.$laforma.' Nro. '.$deposito;
	$debe = $albanco; //  - ($intereses_diferidos + $d_obligatorias); 
	$sql="update sgcaf370 set descri1 = '$des', monto ='$albanco' where nro_rec='$referencia'";
	$a=mysql_query($sql);
	$sql="update sgcaf830 set enc_desco = '$des', enc_explic='$des' where enc_clave ='$elasiento'";
	$a=mysql_query($sql);
	$cargar='+';
	$sqlbanco="select * from sgcaf843 where recibirpago=1 and cod_banco='$elbanco'";
	$resultado=mysql_query($sqlbanco);
	$fila2 = mysql_fetch_assoc($resultado);
	$cuenta1=$fila2['cue_banco'];
	agregar_f820($elasiento, $proceso, $cargar, $cuenta1, 'Abono '.$r310['descr_pres'], $debe, $haber, 0,$ip,0,$deposito,'','S',0); 

	echo '<input type="submit" name="Submit" value="Imprimir Recibo" onClick="abrirVentana(';
	echo "'";
	echo $referencia;
//	echo "&c1=".$c1."&cu=".$cu."&ia=".$ia."&ac=".$ac."&ta=".$ta."&tc=".$tc."&i1=".$i1;
//	echo "&otro=".$better_token.$better_token;
//	echo "&aa=$capital&bb=$cuotas&ia=$ia[$cuotas]&cc=$cu[1]&dd=$interes&socio=";
//	echo trim($r_200['ape_prof']). ', '.trim($r_200['nombr_prof']);
//	echo "&prestamo=".trim($r_310['descr_pres']);
	echo "'";
	echo ');">  ';


/*
	$cedula = $_POST['cedula'];
	$elprestamo = $_POST['elprestamo'];
	$temp = "";
	echo "<form enctype='multipart/form-data' action='recing.php?accion=Solicitar' name='form1' id='form1' method='post' onsubmit='return valpre(form1)'";
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
*/
	echo '</div>';
}	// fin de ($accion == "GenerarRecibos")

if ($mostrarregresar==1) { // ($accion == "Buscar") or ($accion == "Ver") or ($accion="GenerarRecibos")) {
	echo '<form enctype="multipart/form-data" name="formdepie" method="post" action="recing.php?accion=Buscar">';
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
function pantalla_recibo($result,$cedula)
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
  <fieldset><legend>Información Personal </legend>
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
?>
