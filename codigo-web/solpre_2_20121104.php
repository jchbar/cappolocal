<?php
	$micedula=$_SESSION['micedula'];
	$laparte =$_SESSION['micodigo'];
//	echo 'micedula '.$micedula . ' / '.$_SESSION['micodigo'] . ' prestamo '.$r_360['cod_pres'].'<br>';
// zapatos
if (($r_360['cod_pres'] == '059')) {	// zapatos /
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

		$debe = $monpre_sdp; //  - $inicial - $intereses_diferidos - $d_obligatorias;
		$neto_cheque = $debe;
		if ($debe != 0) {
			if ($r_360['otractaab']=='')
			{
				$sql="select * from sgcaf000 where tipo='CtaSocxPag'";
				$result=mysql_query($sql); // or die ("<p />El usuario $usuario no pudo conseguir la cuenta x pagar<br>".mysql_error()."<br>".$sql);
				$cuentas=mysql_fetch_assoc($result);
//				echo 'cuenta buscada '.$cuentas['nombre'].'<br>';
				$cuenta1=trim($cuentas['nombre']).'-'.substr($laparte,1,4);
			}
			else 
			{
				$cuenta1=trim($r_360['otractaab']);
			}
//			echo 'cuenta mostrada '.$cuenta1.'<br>';
//			agregar_f820($elasiento, $b, '-', $cuenta1, $r_360['descr_pres'], $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
		}
		$_SESSION['queselleva']=' A continuacion se detallan la cantidad y modelos que retira ';
		$registros=$_POST['registros'];
		for ($i=0;$i<$registros;$i++)		
		{
			$cant='cnt'.($i);
			if ($$cant > 0)
			{
				$variable='modelo'.($i);
				$sql="update sgcazapa set existencia = existencia - ".$$cant." where modelo = '".$$variable."'";
				$result=mysql_query($sql); 
//				echo $sql.'<br>';
				$sql="select * from sgcazapa where modelo = '".$$variable."'";
//				echo $sql.'<br>';
				$result=mysql_query($sql); 
				$cuentas=mysql_fetch_assoc($result);
				$cuenta1=trim($cuentas['cinventario']);
				$debe=$$cant * $cuentas['costo'];
				agregar_f820($elasiento, $b, '-', $cuenta1, $$cant . ' Pares ', $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
				$_SESSION['queselleva'].= "".$$cant ." Pares del modelo ".$$variable." / ";
			}
		}
	}	
	// zapatos
	else 

// celulares 2
if (($r_360['cod_pres'] == '060')) {	// celulares 2
		$cargo=trim($r_360['cuent_pres']).'-'.substr($laparte,1,4);
		$listo=cuenta_810($cargo,trim($r_200['ape_prof']). ' '.$r_200['nombr_prof']);
		echo "Generando encabezado contable <strong><a target=\"_blank\" href='editasi2.php?asiento=$elasiento'>$elasiento </a></strong> <br>";
		$sql = "INSERT INTO sgcaf830 (enc_clave, enc_fecha, enc_desco, enc_desc1, enc_debe, enc_haber, enc_item, enc_dif, enc_igual, enc_refer, enc_sw, enc_explic) VALUES ('$elasiento', '$b', '','',0,0,0,0,0,0,0,'')"; 
		if (!mysql_query($sql)) die ("El usuario $usuario no tiene permiso para añadir Asientos.<br>".$sql);
		$haber = $debe = 0;
		$referencia=$elnumero;
		$d_obligatorias=0;

		$debe = $monpre_sdp; //  - $inicial - $intereses_diferidos - $d_obligatorias;
		$neto_cheque = $debe;
		if ($debe != 0) {
			if ($r_360['otractaab']=='')
			{
				$sql="select * from sgcaf000 where tipo='CtaSocxPag'";
				$result=mysql_query($sql); // or die ("<p />El usuario $usuario no pudo conseguir la cuenta x pagar<br>".mysql_error()."<br>".$sql);
				$cuentas=mysql_fetch_assoc($result);
//				echo 'cuenta buscada '.$cuentas['nombre'].'<br>';
				$cuenta1=trim($cuentas['nombre']).'-'.substr($laparte,1,4);
			}
			else 
			{
				$cuenta1=trim($r_360['otractaab']);
			}
//			echo 'cuenta mostrada '.$cuenta1.'<br>';
//			agregar_f820($elasiento, $b, '-', $cuenta1, $r_360['descr_pres'], $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
		}
		$_SESSION['queselleva']=' A continuacion se detallan la(s) cantidad(es) y modelo(s) que retira ';
		$registros=$_POST['registros'];
		$montocelulares=0;
		for ($i=0;$i<$registros;$i++)		
		{
			$cant='cnt'.($i);
			if ($$cant > 0)
			{
				$variable='modelo'.($i);
				$sql="update sgcazapa set existencia = existencia - ".$$cant." where modelo = '".$$variable."'";
				$result=mysql_query($sql); 
//				echo $sql.'<br>';
				$sql="select * from sgcazapa where modelo = '".$$variable."'";
//				echo $sql.'<br>';
				$result=mysql_query($sql); 
				$cuentas=mysql_fetch_assoc($result);
				$cuenta1=trim($cuentas['cinventario']);
				$debe=$$cant * $cuentas['costo'];
				$montocelulares+=$debe;
				agregar_f820($elasiento, $b, '-', $cuenta1, $$cant . ' equipo '.trim($r_200['ape_prof']). ' '.$r_200['nombr_prof'], $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
				$_SESSION['queselleva'].= "".$$cant ." equipo del modelo ".$$variable." / ";
			}
		}

		// cargo prestamo al socio
		$debe = $monpre_sdp;
		if ($r_360['int_dif'] == 1) {
			$cuenta_diferido=trim($r_360['cuent_int']).'-'.substr($laparte,1,4);
//			echo 'dfierod'.$cuenta_diferido;
			$listo=cuenta_810($cuenta_diferido,trim($r_200['ape_prof']). ' '.$r_200['nombr_prof']);
		}
		echo "Generando cargos del asiento <strong><a target=\"_blank\" href='editasi2.php?asiento=$elasiento'>$elasiento </a></strong> <br>";
		$debe=$montocelulares;// $monpre_sdp;
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


	}	
	// celulares
	else 

	if ($r_360['cod_pres'] == '055') {	// no hipotecario /
		$laparte=trim($r_200['cod_prof']);
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
		
		$ncuotas=$monto_giros / $cuota_giros;
		
		for ($losgiros=1;$losgiros<=$numero_giros;$losgiros++) {
			$numerogiro=$letra_giros.substr($laparte,0,5).ceroizq($losgiros,2);
			echo 'numero giro'.$numerogiro.'<br>';
			$primer_dcto=$fecha_giros;

			$sql="insert into sgcaf310 (
				codsoc_sdp, cedsoc_sdp, nropre_sdp, codpre_sdp, f_soli_sdp, 
				f_1cuo_sdp, monpre_sdp, monpag_sdp, nrofia_sdp, stapre_sdp, 
				tipo_fianz, cuota, nrocuotas, interes_sd, cuota_ucla, 
				netcheque, nro_acta, fecha_acta, ip, inicial, 
				intereses) 
				values (
				'$laparte', '$micedula', '$numerogiro', '034', '$hoy', 
				'$primer_dcto', '$monto_giros', 0, 0, 'A', 
				'', $cuota_giros, $ncuotas, 0, $cuota_giros, 
				0, '$nroacta', '$fechaacta', '$ip', $inicial, 
				0)";
//	echo $sql.'<br>';
			$resultado=mysql_query($sql);
			$elano=substr($fecha_giros,0,4)+1;
			$fecha_giros=$elano.substr($fecha_giros,4,6);
			$primer_dcto=$fecha_giros;
		}
	}	// fin no hipotecario
	else 
if (($r_360['cod_pres'] == '023') or ($r_360['cod_pres'] == '053') or ($r_360['cod_pres'] == '057') or ($r_360['cod_pres'] == '058')) {	// flash /

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

		$debe = $monpre_sdp; //  - $inicial - $intereses_diferidos - $d_obligatorias;
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
	else 
	// 055 
	if (($r_360['cod_pres'] != '055')) {
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
			if ($r_360['otractaab']=='')
			{
				$sql="select * from sgcaf000 where tipo='CtaSocxPag'";
				$result=mysql_query($sql); // or die ("<p />El usuario $usuario no pudo conseguir la cuenta x pagar<br>".mysql_error()."<br>".$sql);
				$cuentas=mysql_fetch_assoc($result);
//				echo 'cuenta buscada '.$cuentas['nombre'].'<br>';
				$cuenta1=trim($cuentas['nombre']).'-'.substr($laparte,1,4);
			}
			else 
			{
				$cuenta1=trim($r_360['otractaab']);
			}
//			echo 'cuenta mostrada '.$cuenta1.'<br>';
			agregar_f820($elasiento, $b, '-', $cuenta1, $r_360['descr_pres'], $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
		}
	}
	// 055 

?>
