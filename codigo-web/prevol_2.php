<?php
	$micedula=$_SESSION['micedula'];
	$laparte =$_SESSION['micodigo'];

	$cargo=trim($r_360['cuent_pres']).'-'.substr($laparte,1,4);
	$listo=cuenta_810($cargo,trim($r_200['ape_prof']). ' '.$r_200['nombr_prof']);
	echo "Generando encabezado contable <strong><a target=\"_blank\" href='editasi2.php?asiento=$elasiento'>$elasiento </a></strong> <br>";
	$desc='Prestamo Otorgado al socio '.$r_200['ape_prof']. ' '.$r_200['nombr_prof'];
	$sql = "INSERT INTO sgcaf830 (enc_clave, enc_fecha, enc_desco, enc_desc1, enc_debe, enc_haber, enc_item, enc_dif, enc_igual, enc_refer, enc_sw, enc_explic) VALUES ('$elasiento', '$b', '$desc','',0,0,0,0,0,0,0,'$desc')"; 
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
		else $monto_deduccion=($monpre_sdp)*($r_deduccion['porcentaje']/100);
		// $monto_deduccion=($r_310['monpre_sdp']-$r_310['inicial'])*($r_deduccion['porcentaje']/100);
		$d_obligatorias+=$monto_deduccion;
		$debe=$monto_deduccion;
		$albanco-=$debe;
		$cuenta1=trim($r_deduccion['cuenta']);
		agregar_f820($elasiento, $b, '-', $cuenta1, $r_deduccion['cuento']. ' '.trim($socio['ape_prof']). ' '.trim($socio['nombr_prof']), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
		$sql_312="insert into sgcaf312 (tipo, cuento, cuenta, monto, numero, cedula) VALUES ('-','".$r_deduccion['cuento']."', '$cuenta1', $monto_deduccion, '$elnumero','$micedula')";
//			echo $sql_312;
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
?>
