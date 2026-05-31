<?php
include("head.php");

if (!$link OR !$_SESSION['empresa']) {
	include("noempresa.php");
	exit; 
}

if ($_GET['emp'] == 1) {$_GET['n'] = 1;}

if ($_GET['n'] == 1) {
	$onload="onload=\"foco('asiento')\"";
} else {
}

?>

<body <?php if (!$bloqueo) {echo $onload;}?>>

<?php

include("arriba.php");
$menu11=1;include("menusizda.php");

if (!$cedula) {

	echo "<form method='post' name='form1'>\n";
	echo "Cédula: <input type='text' name='cedula' size='10' maxlength='10'>\n";
	echo "<input type='submit' name = 'formu' value='Buscar Estado de Cuenta'>\n";
	echo "</form>\n";
	echo "</div></body></html>";
	exit;

}

if ($cedula) {
	$result = mysql_query("SELECT * FROM sgcaf200 WHERE ced_prof = '$cedula' ");
	if (mysql_num_rows($result) == 0) {
		echo "<p />Cédula <span class='b'>$cedula</span> no esta registrada</div></body></html>";
				//		exit;
	}
	else 
	{
		$fila = mysql_fetch_array($result);
		if ($fila['polizaactiva'] == 1)
			echo '<h3>Poliza de Vida: Activa</h3><br>';
		else 
			echo '<h1>Poliza de Vida: NO ACTIVA</h1><br>';

		if ($fila['AyudaSolidaria'] != 'Si')
			echo '<h3>Se encuentra retirado de APORTE AYUDA SOLIDARIA ('.$fila['FechaRetiroAyuda'].')</h3><br>';
		if ($fila['AhorroVoluntario'] != 'Si')
			echo '<h3>Se encuentra retirado de AHORRO VOLUNTARIO</h3><br>';


		$cededo=$cedula;
		$cnt="select * from sgcaf000 where tipo='CntEdoCta'";
		$cnt=mysql_query($cnt);
		$cnt=mysql_fetch_assoc($cnt);
		$cnt=$cnt['nombre'];
		if (cantidad_estado_cuenta($cedula) < $cnt)
			echo "<a target=\"_blank\" href=\"edoctapdf.php?cedula=$cedula\" onClick=\"info.html\', \'\',\'width=250, height=190\')\">Imprimir Estado de Cuenta</a>";
		   echo "<p/ >";

		//echo '<fieldset><legend>'.$fila['apel_prof'].' '.$fila['nombr_prof'].'</legend>';
		echo "<table align='center' class='basica'>";
		echo '<tr><th width="175">Nombre del Asociado: </th><td class="blanco b" width="230">'.$fila['ape_prof'].' '.$fila['nombr_prof'].'</td><th width="75">Cédula</th><td class="blanco b" width="80">'.$fila['ced_prof'].'</td><th width="75">Código</th><td class="blanco b" width="80">'.$fila['cod_prof'].'</td></tr>';
		echo '<tr><th width="175">Fecha Ingreso Caja: </th><td class="blanco b" width="230">'.convertir_fechadmy($fila['f_ing_capu']).'</td><th width="75">Decanato</th><td class="blanco b" width="175">';
		$elcescuela=$fila['escuela'];
		$sql="select codigo, nombre from escuelas where codigo = '$elcescuela'";
		$resultado=mysql_query($sql);
		$fila2 = mysql_fetch_assoc($resultado);
		echo $fila2['nombre'];
		echo '<th width="75">Departamento </th><td class="blanco b" width="175">';
		$elcdpto=$fila['dept_prof'];
		$sql="select escdpto, escuela from sgcafeyd where escdpto = '$elcdpto'";
		$resultado=mysql_query($sql);
		$fila2 = mysql_fetch_assoc($resultado);
		echo $fila2['escuela'];
		echo '</td></tr>';
		echo '<tr><th width="175">Fecha Ingreso UCLA: </th><td class="blanco b" width="230">'.convertir_fechadmy($fila['f_ing_ucla']).'</td><th width="175">Cargo: </th><td class="blanco b" width="230">'.$fila['cargo'].'</td><th width="175">Estatus: </th><td  class="blanco b" width="230">'.$fila['statu_prof'].'</td></tr>';
		echo '</table>';

		echo "<table align='center' class='basica'>";
		$cedula=substr($cedula,0,4).'.'.substr($cedula,4,3).'.'.substr($cedula,7,3);
		$sql="select * from unido where cedula= '$cedula' and ano='2004' order by ano";

		echo '<tr><th align="center" colspan="6" width="800">Descripción</th><th colspan="1" width="100">Ahorros Bs</th><th colspan="2" width="75">Total Bs</th></tr>';
		$sql_aporte="SELECT * FROM aportep WHERE tipo = 'R' ORDER BY fecha DESC LIMIT 1 ";
		$resul_aporte=mysql_query($sql_aporte);
		$faporte=mysql_fetch_assoc($resul_aporte);
		// echo '<tr><td align="left" colspan="6" width="175" class="blanco" >Ahorro Socio al: '.convertir_fechadmy($faporte['fecha']).'</td><td colspan="1" align="right" width="100"class="blanco" >'.number_format($fila['hab_f_prof'],2,'.',',').'</td></tr>';
		echo '<tr><td align="left" colspan="6" width="175" class="blanco" >Ahorro Socio al: '.convertir_fechadmy($fila['ultap_prof']).'</td><td colspan="1" align="right" width="100"class="blanco" >'.number_format($fila['hab_f_prof'],2,'.',',').'</td></tr>';
		$sql_aporte="SELECT * FROM aportep WHERE tipo = 'A' ORDER BY fecha DESC LIMIT 1 ";
		$resul_aporte=mysql_query($sql_aporte);
		$faporte=mysql_fetch_assoc($resul_aporte);
		echo '<tr><td align="left" colspan="6" width="175" class="blanco" >Ahorro UCLA al: '.convertir_fechadmy($faporte['fecha']).'</td><td colspan="1" align="right" width="100" class="blanco" >'.number_format($fila['hab_f_empr'],2,'.',',').'</td></tr>';
		// echo '<tr><td align="left" colspan="6" width="175" class="blanco" >VeBono 2009</td><td colspan="1" align="right" width="100" class="blanco" >'.number_format($fila['hab_opsu'],2,'.',',').'</td></tr>';
		// echo '<tr><td align="left" colspan="6" width="175" class="blanco" >Ahorro Voluntarios </td><td colspan="1" align="right" width="100" class="blanco" >'.number_format($fila['hab_f_extr'],2,'.',',').'</td></tr>';
		// echo '<tr><td align="left" colspan="6" width="175" class="blanco" >Ahorro Capitalizables </td><td colspan="1" align="right" width="100" class="blanco" >'.number_format($fila['hab_f_capi'],2,'.',',').'</td></tr>';

		$totalahorros=$fila['hab_f_prof']+$fila['hab_f_extr']+$fila['hab_f_capi']+$fila['hab_f_empr']+$fila['hab_opsu'];
		echo '<tr><td align="left" colspan="7" width="175" class="blanco b" >SALDO AHORROS </td><td colspan="1" align="right" width="100"class="blanco b" >'.number_format(($totalahorros),2,'.',',').'</td></tr>';


		$sql="select * from sgcaf310, sgcaf360 where (cedsoc_sdp='$cedula' and codpre_sdp=cod_pres and stapre_sdp='A' and ! renovado) order by f_1cuo_sdp";
		$result=mysql_query($sql);
		$registros12=mysql_num_rows($result);
		if ($registros12 > 0) {
		echo '<tr><th align="center" colspan="8" width="800">SALDO DE PRESTAMOS AL </th></tr>';
		echo '<tr><th align="center" width="50"># Prest.</th><th align="center" width="250">Tipo de Préstamo</th><th align="center" width="100">Monto</th><th align="center" width="100"># NC</th><th align="center" width="100">CC</th><th align="center" width="100">Cuota Bs</th><th align="center" width="100">1er Dcto.</th><th align="center" width="100">Saldo</th></tr>';
		//echo $sql.'<br>';
	// -- $result=mysql_query($sql);
	$sql = "SELECT count(*) as numero, SUM(monpre_sdp-monpag_sdp) as deuda from sgcaf310 where codpre_sdp='046' and cedsoc_sdp='$cedula' and stapre_sdp = 'A' and (!renovado) group by cedsoc_sdp ";
	// $sql = "SELECT count(*) as numero from sgcaf310 where codpre_sdp='046' and cedsoc_sdp='$cedula' and stapre_sdp = 'A' and (!renovado) group by cedsoc_sdp ";
	// echo $sql;
	$result_p=mysql_query($sql);
	$registrosp=mysql_num_rows($result_p);
	if ($registrosp > 0) {
		$row=mysql_fetch_assoc($result_p);
		// echo $row['numero'].'   '.$row['deuda'];

		$cuento = "Este socio tiene ".$row['numero']." prestamos con cuotas indomiciliadas por un monto de ". $row['deuda'];
		// echo 'cuento '.$cuento;

		echo '<script languaje="javascript">alert("'.$cuento.'")</script>';
		// .$row['deuda'].'
		// var_dump($row['numero']);
	}

	$fianzas = $afectan = $noafectan = $semanal = 0;
	while($row=mysql_fetch_assoc($result)) {
		echo '<tr>
			<td align="center" width="50" class="blanco">'.$row['nropre_sdp'].'</td>
			<td align="center" width="250" class="blanco">'.$row['descr_pres'].'</td>
			<td align="center" width="100" class="blanco">'.number_format($row['monpre_sdp'],2,'.',',').' '.($row['enUSD']=='1'?'(USD)':'').'</td>
			<td align="center" width="100" class="blanco">'.number_format($row['nrocuotas'],0,',','.').'</td>
			<td align="center" width="100" class="blanco">'.number_format($row['ultcan_sdp'],0,',','.').'</td>
			<td align="center" width="100" class="blanco">'.number_format($row['cuota_ucla'],2,'.',',').' '.($row['enUSD']=='1'?'(USD)':'').'</td>
			<td align="center" width="100" class="blanco">'.convertir_fechadmy ($row['f_1cuo_sdp']).'</td>
			<td align="right" width="100" class="blanco">'.number_format(($row['monpre_sdp']-$row['monpag_sdp']),2,'.',',').' '.($row['enUSD']=='1'?'(USD)':'').'</td>
			</tr>';
		if ($row['retab_pres']==1)
			$afectan +=($row['monpre_sdp']-$row['monpag_sdp']);
		else {
			$noafectan += ($row['monpre_sdp']-$row['monpag_sdp']);
	//		echo ($row['monpre_sdp']-$row['monpag_sdp']).'<br>';
		}
		if ($row['dcto_sem']==1)
			$semanal += $row['cuota_ucla'];
	   }
	}
	$fiado=0;
	//$sql="select *, ape_prof, nombr_prof, (monto_fia-monlib_fia) as saldo_fia from sgcaf320, sgcaf200 where (codsoc_fia=cod_prof) and (codfia_fia='".$fila['cod_prof']."') and ((monto_fia-monlib_fia) > 0) order by codsoc_fia";
	$sql="select *, ape_prof, nombr_prof, (monto_fia-monlib_fia) as saldo_fia from sgcaf320, sgcaf200 where (codsoc_fia='".$fila['cod_prof']."') and (codfia_fia=cod_prof) and (tipmov_fia='F') and ((monto_fia-monlib_fia) > 0) order by codsoc_fia";
	// $sql="select *, (monto_fia-monlib_fia) as saldo_fia from sgcaf320 where (codsoc_fia='".$fila['cod_prof']."') and (tipmov_fia='F') and ((monto_fia-monlib_fia) > 0) order by codsoc_fia";
	//echo $sql.'<br>';
	$afianzadores=mysql_query($sql);
	$registros1=mysql_num_rows($afianzadores);
	if ($registros1 > 0) {
		echo '<tr><th align="left" colspan="8" width="175">FIANZAS RECIBIDAS</th></tr>';
		echo '<tr><th align="center" width="50"># Prest.</th><th align="center" width="250">Fiado</th><th align="center" width="100" colspan="2">Monto Otorgado</th><th align="center" width="100" colspan="2">Monto Liberado</th><th align="center" width="100" colspan="2">Monto por Liberar</th></tr>';
	while($afianzado=mysql_fetch_assoc($afianzadores)) {
		echo '<tr><td align="center" width="50" class="blanco">'.$afianzado['nropre_fia'].'/'.$afianzado['codfia_fia'].'</td><td align="center" width="250" class="blanco">'.$afianzado['ape_prof'].' '.$afianzado['nombr_prof'].'</td><td align="center" width="100" class="blanco" colspan="2">'.number_format($afianzado['monto_fia'],2,'.',',').'</td><td align="center" width="100" class="blanco" colspan="2">'.number_format($afianzado['monlib_fia'],2,'.',',').'</td><td align="center" width="100" class="blanco" colspan="2">'.number_format($afianzado['saldo_fia'],2,'.',',').'</td></tr>';
//		$fiado+= ($afianzado['saldo_fia']);
	   }
	}

	$sql="select *, ape_prof, nombr_prof, (monto_fia-monlib_fia) as saldo_fia from sgcaf320, sgcaf200 where (codsoc_fia=cod_prof) and (codfia_fia='".$fila['cod_prof']."') and ((monto_fia-monlib_fia) > 0) and (tipmov_fia='F') order by codsoc_fia";
	// $sql="select *, (monto_fia-monlib_fia) as saldo_fia from sgcaf320 where (codsoc_fia='".$fila['cod_prof']."') and (tipmov_fia='F') and ((monto_fia-monlib_fia) > 0) order by codsoc_fia";
	//echo $sql.'<br>';

	$fiadores=mysql_query($sql);
	$registros=mysql_num_rows($fiadores);
	if ($registros > 0) {
		echo '<tr><th align="left" colspan="8" width="175">FIANZAS OTORGADAS</th></tr>';
		echo '<tr><th align="center" width="50"># Prest.</th><th align="center" width="250">Fiador</th><th align="center" width="95" colspan="2">Monto Otorgado</th><th align="center" width="100" colspan="2">Monto Liberado</th><th align="center" width="100" colspan="2">Saldo Actual</th></tr>';
		while($fiador=mysql_fetch_assoc($fiadores)) {
			echo '<tr><td align="center" width="50" class="blanco">'.$fiador['nropre_fia'].'/'.$fiador['codfia_fia'].'</td><td align="center" width="250" class="blanco">'.$fiador['ape_prof'].' '.$fiador['nombr_prof'].'</td><td align="center" width="100" class="blanco" colspan="2">'.number_format($fiador['monto_fia'],2,'.',',').'</td><td align="center" width="100" class="blanco" colspan="2">'.number_format($fiador['monlib_fia'],2,'.',',').'</td><td align="center" width="100" class="blanco" colspan="2">'.number_format($fiador['saldo_fia'],2,'.',',').'</td></tr>';
		$fianzas+=($fiador['saldo_fia']);
		}
	}
	echo "<table align='center' class='basica'>";
	echo '<tr><th align="right" colspan="8" width="175"></th></tr>';
	echo '<tr><td align="right" width="300" class="blanco" colspan="2">Total Saldos Fianzas Recibidas</td><td align="right" width="100" class="blanco" colspan="1">'.number_format($fiado,2,'.',',').'</td><td align="right" width="360" class="blanco" colspan="4">Total Saldos Fianzas Otorgadas</td><td align="right" width="100" class="b" colspan="1">'.number_format($fianzas,2,'.',',').'</td></tr>';
	echo '<tr><td align="right" width="300" class="blanco" colspan="2">Total Saldos que NO Afectan Disponibilidad</td><td align="right" width="100" class="blanco" colspan="1">'.number_format($noafectan,2,'.',',').'</td><td align="right" width="360" class="blanco" colspan="4">Total Saldos que Afectan Disponibilidad</td><td align="right" width="100" class="b" colspan="1">'.number_format($afectan,2,'.',',').'</td>';

	echo '<tr><td align="right" width="300" class="blanco" colspan="2">Total Cuota a Banco Quincenal</td><td class="blanco" align="right" width="100"class="blanco" colspan="1">'.number_format($semanal,2,'.',',').'</td>';
	$sql="select por_dispon from sgcaf100 limit 1";
	$result=mysql_query($sql);
	$row=mysql_fetch_assoc($result);
	$reserva=$totalahorros*($row['por_dispon']/100);

	echo '<td align="right" width="360" class="blanco" colspan="4">Monto por Reserva Legal ('.number_format($row['por_dispon'],2,'.','.').'%)</td><td align="right" width="100" class="b" colspan="1">'.number_format($reserva,2,'.',',').'</td>';
	// nuevo 2012-10-20
	$sql="select * from sgcaf000 where tipo ='Reserva_P_Esp02'";
	$a_sql=mysql_query($sql);
	$r_sql=mysql_fetch_assoc($a_sql);
	$porc_esp=($r_sql['nombre']/100);
	// echo 'porcentake '.$porc_esp;
	$reserva2=0;
	$reserva2=$noafectan * $porc_esp;
	$sql="select * from sgcaf000 where tipo ='Reserva_P_Esp01'";
	$a_sql=mysql_query($sql);
	$r_sql=mysql_fetch_assoc($a_sql);
	$nombre_reserva=$r_sql['nombre'];
	echo '<tr><td align="right" class="b" colspan="7">'.$nombre_reserva.'</td><td class="b" align="right">'.number_format($reserva2,2,'.',',').'</td>';
	$disponibilidad=($totalahorros-$reserva-$reserva2)-($afectan+$fianzas);
	//$disponibilidad=($totalahorros-$reserva)-($afectan+$fianzas);
	// fin nuevo 2012-10-20
	if ($disponibilidad >= 0)
		echo '<tr><td align="right" class="b" colspan="7">Disponibilidad Neta </td><td class="b" align="right">'.number_format($disponibilidad,2,'.',',').'</td>';
	else
		echo '<tr><td align="right" class="rojo b" colspan="7">Disponibilidad Neta </td><td class="rojo b" align="right">'.number_format($disponibilidad,2,'.',',').'</td>';
	//
		$sql2="select sum(hab_prof) as socio, sum(hab_ucla) as ucla from t_his200 where cod_prof = '".$fila['cod_prof']."' group by cod_prof";
	//	echo $sql2;
		$result2=mysql_query($sql2);
		$row2=mysql_fetch_assoc($result2);
		echo '<tr><td align="right" class="rojo b" colspan="7">Monto Adeudado por la UCLA </td><td class="rojo b" align="right">'.number_format($row2[socio]+$row2[ucla],2,'.',',').'</td>';

	//	$sql2="select * from sgcaf700 where codsoc = '".$fila['cod_prof']."' order by fechareti desc limit 1";
		$cedula=$fila['ced_prof'];
		$estacedula=substr($cedula,0,4).'.'.substr($cedula,4,3).'.'.substr($cedula,7,3);
		$sql2="select * from sgcaf700 where cedsoc = '".$estacedula."' order by fechareti desc limit 1";
	//	echo $sql2;
		$result2=mysql_query($sql2);
		$row2=mysql_fetch_assoc($result2);
		echo '<tr><td align="right" class="rojo b" colspan="7">Ultimo retiro realizado el '.convertir_fechadmy($row2['fechareti']).' por concepto de '.$row2['motivo'].' </td><td class="rojo b" align="right">'.number_format($row2[montoreti],2,'.',',').'</td>';
			
	//
		echo '</table>';
		echo "<p/ >";
		if (cantidad_estado_cuenta($cedula) < $cnt)
			echo "<a target=\"_blank\" href=\"edoctapdf.php?cedula=$cededo\" onClick=\"info.html\', \'\',\'width=250, height=190\')\">Imprimir Estado de Cuenta</a>";
	   echo "<p/ >";
		if (strlen($fila['mail_prof']) > 3)
			echo 'Enviar por email';

		/* revisar si esta suspendido */
		$micedula=substr($cedula,0,4).'.'.substr($cedula,4,3).'.'.substr($cedula,7,4);
		// $sqls="select *, date_format(suspendido,'%d/%m/%y') as fs, date_format(ingresado,'%d/%m/%y') as ingresadof from suspende where ((cedula = '$micedula') and  (activo = 1) and (now() < suspendido))";
		// V-07.349.428
		// V-07349428
		$sqls= "
			select *, 
				if(tipoIndomiciliacion='Ahorro',date_format(suspendido,'%d/%m/%y'),date_format(date_add(now(),INTERVAL 365 DAY),'%d/%m/%y')) as fs,
				date_format(ingresado,'%d/%m/%y') as ingresadof,
				date_add(fecha_pago,INTERVAL 90 DAY) as fp, 
				date_format(date_add(fecha_pago,INTERVAL 90 DAY),'%d/%m/%y') as fpi, 
				date_format(now(),'%y-%m-%d') as hoy
			from suspende where (
				(cedula = '$micedula') and  
				(activo = 1) and 
				(
					(tipoIndomiciliacion='Ahorro' and now() < suspendido) or (tipoIndomiciliacion='Prestamo')
				)
				)
		";
		$resuls=mysql_query($sqls);
		$vacio=(mysql_num_rows($resuls) > 0?true:false);

		while ($fila2 = mysql_fetch_assoc($resuls)) {
			var_dump(($fila2));
			echo '<br><br><br>';
			$imprimir = false;
			echo "$fila2fp " .$fila2['fp'];
			if (($fila2['tipoIndomiciliacion'] == 'Ahorro') and ($fila2['suspendido'] < $fila2['suspendido']))
			{
				$imprimir = true;
				$cuento = '1<h2>No se pudo descontar prestamo '.$fila2['prestamo']. '('.$fila2['ID'].') enviado para '.$fila2['fallo'].' por un monto de '.number_format($fila2['monto'],2,'.',',').' suspendido hasta '.$fila2['fs']. ' reportado por '.$fila2['reporto'].' en fecha '.$fila2['ingresadof'].'</h2>';
			}
			else 
				if (($fila2['fp'] < $fila2['hoy']) and (!IS_NULL($fila2['fecha_pago'])))
				{
					$imprimir = true;
					$cuento = '2<h2>No se pudo descontar prestamo '.$fila2['prestamo']. '('.$fila2['ID'].') enviado para '.$fila2['fallo'].' por un monto de '.number_format($fila2['monto'],2,'.',',').' suspendido hasta '.$fila2['fpi']. ' reportado por '.$fila2['reporto'].' en fecha '.$fila2['ingresadof'].'</h2>';

				} 
				else // if ($fila2['fp'] < $fila2['hoy'])
				{
					$imprimir = true;
					$cuento = '3<h2>No se pudo descontar prestamo '.$fila2['prestamo']. '('.$fila2['ID'].') enviado para '.$fila2['fallo'].' por un monto de '.number_format($fila2['monto'],2,'.',',').' suspendido hasta '.$fila2['fs']. ' reportado por '.$fila2['reporto'].' en fecha '.$fila2['ingresadof'].'</h2>';
				}

			if ($imprimir)
				echo $cuento;
			// echo '<h2>No se pudo descontar prestamo '.$fila2['prestamo']. '('.$fila2['ID'].') enviado para '.$fila2['fallo'].' por un monto de '.number_format($fila2['monto'],2,'.',',').' suspendido hasta '.$fila2['fs']. ' reportado por '.$fila2['reporto'].' en fecha '.$fila2['ingresadof'].'</h2>';
	//		$loquedebe.='No se pudo descontar prestamo '.$fila2['prestamo']. ' enviado para '.$fila2['fallo'].' por un monto de '.number_format($fila2['monto'],2,'.',',').' suspendido hasta '.$fila2['suspendido']. ' reportado por '.$fila2['reporto'].' / ';
		}
		
	//	echo $sqls;
	// 	$resuls=mysql_query($sqls);
	// 	$vacio=(mysql_num_rows($resuls) > 0?true:false);
	// 	$loquedebe='';
	// 	while ($fila2 = mysql_fetch_assoc($resuls)) {
	// 		echo '<h2>No se pudo descontar prestamo '.$fila2['prestamo']. '('.$fila2['ID'].') enviado para '.$fila2['fallo'].' por un monto de '.number_format($fila2['monto'],2,'.',',').' suspendido hasta '.$fila2['fs']. ' reportado por '.$fila2['reporto'].' en fecha '.$fila2['ingresadof'].'</h2>';
	// //		$loquedebe.='No se pudo descontar prestamo '.$fila2['prestamo']. ' enviado para '.$fila2['fallo'].' por un monto de '.number_format($fila2['monto'],2,'.',',').' suspendido hasta '.$fila2['suspendido']. ' reportado por '.$fila2['reporto'].' / ';
	// 	}
		if ($vacio == true) // esta suspendido
			die('<h1>No puede solicitar prestamos</h1>');

	/* fin revisar si esta suspendido */
   


   if ($accion=='') {
   echo '<div style="clear:both"></div>';
   echo '<p /><div class="noimpri" style="clear:both;text-align:center">';
   echo '<a href="edocta.php"><input type="button" name="boton" value="regresar" tabindex="3">';
   }
   else if ($accion=='Editar') {
   echo '<div style="clear:both"></div>';
   echo '<p /><div class="noimpri" style="clear:both;text-align:center">';
   echo '<a href="habsoc.php"><input type="button" name="boton" value="regresar" tabindex="3">';
   }

   
include("pie.php");
}

}
function cantidad_estado_cuenta($cedula)
{
	$hoy = date("Y-m-d H:i:s");
	$buscar="select * from hedocta where trim(cedula)='$cedula' and substr(fecha,1,7)='".substr($hoy,0,7)."' ";
//	echo $buscar;
	$aresb=mysql_query($buscar);
	$cuantos=mysql_num_rows($aresb);
	echo 'Impreso el - Desde IP<br>';
	while($rresb=mysql_fetch_assoc($aresb)) {
		echo $rresb['fecha'] . ' - '. $rresb['ip'].'<br>';
	}
	return $cuantos;
}
?>