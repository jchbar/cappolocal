<?php
include("head.php");
include("paginar.php");

extract($_GET);
extract($_POST);
extract($_SESSION);


if (!$link OR !$_SESSION['empresa']) {
	include("noempresa.php");
	exit;
}
?>
<script src="ajaxabo.js" type="text/javascript"></script>
<script language="javascript">
//Creo una función que imprimira en la hoja el valor del porcentanje asi como el relleno de la barra de progreso
function callprogress(vValor){
 document.getElementById("getprogress").innerHTML = vValor;
 document.getElementById("getProgressBarFill").innerHTML = '<div class="ProgressBarFill" style="width: '+vValor+'%;"></div>';
}
</script>
<style type="text/css">
/* Ahora creo el estilo que hara que aparesca el porcentanje y relleno del mismoo*/
      .ProgressBar     { width: 16em; border: 1px solid black; background: #eef; height: 1.25em; display: block; }
      .ProgressBarText { position: absolute; font-size: 1em; width: 16em; text-align: center; font-weight: normal; }
      .ProgressBarFill { height: 100%; background: #aae; display: block; overflow: visible; }
    </style>
</script>

<body <?php if (!$bloqueo) {echo $onload;}?>>

<?php

$readonly=" readonly='readonly'";
include("arriba.php");
$menu61=1;include("menusizda.php");
$ip = $_SERVER['HTTP_CLIENT_IP'];
if (!$ip) {$ip = $_SERVER['REMOTE_ADDR'];}
if (!$accion) {
	echo "<div id='div1'>";
	$sql='SELECT fecha, sum(cuota) as monto, count(fecha) as cuantos FROM sgcaamor where proceso = 1 group by fecha';
	$a_sql=mysql_query($sql);
//	echo $sql; 
	echo "<form action='aboxnom.php?accion=Abonar' name='form1' method='post'  onsubmit='return realizar_abono(form1)'>";
	echo '<fieldset><legend>Información Para Descuentos de Prestamos</legend>';
	echo '<table align="center" class="basica 100 hover" width="500" border="1">';
	echo '<tr><th width="50">Fecha</th><th width="80">Monto</th><th width="80">Cantidad</th><th width="40">Procesar</th>';

	$registros=0;
	while($r=mysql_fetch_assoc($a_sql)) {
		echo '<tr>';
		echo '<td>'.convertir_fechadmy($r['fecha']).'</td>';
		echo '<td align="right">'.number_format($r['monto'],2,".",",").'</td>';
		echo '<td align="right">'.number_format($r['cuantos'],0,".",",").'</td>';
//		echo '<td align="right">'.number_format(($r_310['monpre_sdp']-$r_310['monpag_sdp']),2,".",",").'</td>';
		$registros++;
		echo '<td class="centro azul"><input type="checkbox" id="cancelar'.$registros.'" name="cancelar'.$registros.'" value='.$r["fecha"] .' onClick="amor_cap()" ';
		echo '></td></tr>' ;
	}
	echo "<input type = 'hidden' value ='".$registros."' name='registros' id='registros'>";
	echo '</table>';
	echo '</legend>';
	echo '</div>';

//echo '<div title="sss" dir="ltr"  style="margin-right:auto">';
// echo "<div style='width:50%;float:left'>";
//echo 'listausu();';
//echo "</div>";


//echo "<div style='float:left;display:inline'>";
//echo 'divi3';
//echo "</div>";

	echo '<fieldset><legend>Resumen Para Descuentos de Prestamos</legend>';
	echo '<table align="center" class="basica 100 hover" width="300" border="1">';
	echo '<tr><td>Total Nominas </td><td>';
	echo '<input type="text" name="totalnominas" id="totalnominas" size="8" maxlengt="8"  value=0.00 readonly="readonly"></td></tr>';
	echo '<tr><td>Total Registros</td><td>';
	echo '<input type="text" name="totalregistros" id="totalregistros" size="5" maxlengt="5"  value=0  readonly="readonly"></td></tr>';
	echo '</table>';
	echo '</legend>';
	echo '<input type="submit" name="Submit" value="Realizar Abono a Prestamos (Asientos Contables)" />';
	echo '</form>';
}	// if (!$accion) 
if ($accion=='Abonar') {
	$registros=$_POST['registros'];
	echo '
	 <div class="ProgressBar">
      <div class="ProgressBarText"><span id="getprogress"></span>&nbsp;% completado</div>
      <div id="getProgressBarFill"></div>
    </div>';

	for ($i=0;$i<$registros;$i++)	// no es necesarios revisar el check si aparece es porq estan seleccionados para hacer el asiento 
	{
		$variable='cancelar'.($i+1);
		if (!empty($$variable)) 
		{


			$fecha=explode('-',$$variable);
			$b=$fecha[0].'-'.$fecha[1].'-'.$fecha[2];
			$fecha=$b;
			$sql="select nropre from sgcaamor where fecha ='$fecha' and proceso = 1 order by codsoc"; // limit 10";
			$a_amor=mysql_query($sql);
			$tiempoestimado=mysql_num_rows($a_amor);
			$ValorTotal=$tiempoestimado;
			$cuantos=0;
			if ($tiempoestimado > 0) {
				$sql="select * from sgcaf360 order by cod_pres";
				$a360=mysql_query($sql);
				$posicion=0;
				while ($r360 = mysql_fetch_assoc($a360)){
					$posicion++;
					$capital[$posicion]=0;
					$interes[$posicion]=0;
					$tipoi[$posicion]=$r360['tipo'];
					$codigos[$posicion]=$r360['cod_pres'];
					$interesg[$posicion]=trim($r360['otro_int']);
				}

				$testatutario = $thipotecario = $tcomercial = 0;
				$referencia='';
				$fecha=explode('-',$$variable);
				$b=$fecha[0].'-'.$fecha[1].'-'.$fecha[2];
				$elasiento1=$fecha[0].$fecha[1].$fecha[2].'001';
				$elasiento2=$fecha[0].$fecha[1].$fecha[2].'002';
				$elasiento3=$fecha[0].$fecha[1].$fecha[2].'003';
				$elasiento4=$fecha[0].$fecha[1].$fecha[2].'004';
				$elasiento5=$fecha[0].$fecha[1].$fecha[2].'005';
				$elasiento6=$fecha[0].$fecha[1].$fecha[2].'006';
				crear_encabezado($elasiento1,$b,'por prestamos hipotecarios');
				$fecha=$b;
				crear_encabezado($elasiento2,$b,'para intereses hipotecarios');
				crear_encabezado($elasiento3,$b,'por prestamos estatutarios');
				crear_encabezado($elasiento4,$b,'para control interno');
				crear_encabezado($elasiento5,$b,'por convenios comerciales');
				crear_encabezado($elasiento6,$b,'para intereses diferidos');
				$tinteresest=0;

				for ($j=1;$j<4;$j++) {

					if ($j==1) $aprocesar='Estatutario';
					else if ($j==2) $aprocesar='Comercial';
					else $aprocesar='Hipotecario';
					$sql="select * from sgcaamor where fecha ='$fecha' and proceso = 1 and (tipo ='$aprocesar') order by codsoc"; // limit 10";
					echo "<h2>Procesando $aprocesar</h2><br>";
					$a_amor=mysql_query($sql);
					$tiempoestimado=mysql_num_rows($a_amor);
					if ($tiempoestimado < 30)
						$tiempoestimado=30;
					set_time_limit($tiempoestimado);
					while ($r_amor = mysql_fetch_assoc($a_amor)) {

		$cuantos++;
		$porcentaje = $cuantos * 100 / $ValorTotal; //saco mi valor en porcentaje
		echo "<script>callprogress(".round($porcentaje).")</script>"; //llamo a la función JS(JavaScript) para actualizar el progreso
		flush(); //con esta funcion hago que se muestre el resultado de inmediato y no espere a terminar todo el bucle con los 25 registros para recien mostrar el resultado
		ob_flush();

						$cuentaprestamo=$r_amor['cuent_p'];
						$cuentadiferido=$r_amor['cuent_i'];
						$cuentainteres =$r_amor['cuent_d'];
						for ($k=1;count($codigos);$k++)
							if ($r_amor['codpre']==$codigos[$k]) {
								$posicion=$k;
								break; }
						$capital[$posicion]+=$r_amor['capital'];
						$interes[$posicion]+=$r_amor['interes'];
						if ($r_amor['tipo']=='Estatutario') {
							$asientoa=$elasiento3;
							$asientob=$elasiento6;
							$debe=$r_amor['capital']; // -$r_amor['interes'];
							$abonoc=$debe;
							agregar_f820($asientoa, $b, '-', $cuentaprestamo, 'Ret. Prest.Est. del '.convertir_fechadmy($r_amor['fecha']), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
							$debe=$r_amor['interes'];
							$abonoi=$debe;
							agregar_f820($asientob, $b, '+', $cuentadiferido, 'Int.Dif.Prest.Est. del '.convertir_fechadmy($r_amor['fecha']), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
							$testatutario+=$r_amor['capital'];
							$tinteresest+=$debe;
						}
						else 
						if ($r_amor['tipo']=='Comercial') {
							$asientoa=$elasiento5;
							$asientob=$elasiento6;
							$debe=$r_amor['capital']; // -$r_amor['interes'];
							$abonoc=$debe;
							agregar_f820($asientoa, $b, '-', $cuentaprestamo, 'Ret.Conv. Comer. del '.convertir_fechadmy($r_amor['fecha']), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
							$tcomercial+=$r_amor['capital'];
							$debe=$r_amor['interes'];
							$abonoi=0;
						}
						else { // hipotecario
							$asientoa=$elasiento1;
							$debe=$r_amor['capital']-$r_amor['interes'];
							agregar_f820($asientoa, $b, '-', $cuentaprestamo, 'Ret.Prest.Hipot. del '.convertir_fechadmy($r_amor['fecha']), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
							$thipotecario+=$debe; // $r_amor['capital'];
							$abonoc=$debe;
							$abonoi=$r_amor['interes'];
						}
						// actualizo prestamo y amortizacion como procesada
						$upd_310="update sgcaf310 set monpag_sdp=monpag_sdp+$abonoc, monint=monint + $abonoi, ultcan_sdp=ultcan_sdp+1 where registro=".$r_amor['pos310'];
						if (! mysql_query($upd_310))
							echo $upd_310.'<br>';
						// revisar fiadores
						revisar_fiadores($r_amor['pos310']);
						// fin revisar fiadores
						$upd_amor="update sgcaamor set proceso = 2, abonado = now(), ip_abono = '$ip' where registro = ".$r_amor['registro'];
						if (! mysql_query($upd_amor))
							echo $upd_amor.'<br>';						
						} 	// next
				
						// cierro asiento estatutarios
						$sql="select * from sgcaf000 where tipo='CtaPrexCobAmo'";
						$result=mysql_query($sql); // or die ("<p />El usuario $usuario no pudo conseguir la cuenta x pagar<br>".mysql_error()."<br>".$sql);
						$cuentas=mysql_fetch_assoc($result);
						$cuenta_amortizacion=trim($cuentas['nombre']);
						$sql="select * from sgcaf000 where tipo='CtaPrexCobBco'";
						$result=mysql_query($sql); 
						$cuentas=mysql_fetch_assoc($result);
						$cuentabanco=trim($cuentas['nombre']);			
						if ($j==1) {
							$debe=$testatutario; // -$tinteresest;
							agregar_f820($elasiento3, $b, '+', $cuenta_amortizacion, 'Total Retenciones del '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 	
							agregar_f820($elasiento4, $b, '-', $cuenta_amortizacion, 'Amort. Prest. p/cobrar banco al '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
							agregar_f820($elasiento4, $b, '+', $cuentabanco, 'Prest. p/cobrar banco al '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 		
							for ($k=1;$k<=count($codigos);$k++) 
								if ($tipoi[$k]=='Estatutario')
									if ($interesg[$k] != 'NO TIENE') {
									$cuenta1=$interesg[$k];
									$debe=$interes[$k];
									if ($debe > 0)
									agregar_f820($elasiento6, $b, '-', $cuenta1, 'Interes del '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
								}
						}
						// para los comerciales 
						if ($j==2) {
							$intlocal=0;
							for ($k=1;$k<=count($codigos);$k++)
								if ($tipoi[$k]=='Comercial') 
								if ($interesg[$k] != 'NO TIENE'){
									$cuenta1=$interesg[$k];
									$debe=$interes[$k];
									$intlocal+=$debe;
								if ($debe > 0)
									agregar_f820($elasiento5, $b, '-', $cuenta1, 'Interes del '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
								}
								$debe=$tcomercial+$intlocal;
								agregar_f820($elasiento5, $b, '+', $cuenta_amortizacion, 'Total Retenciones del '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
								agregar_f820($elasiento4, $b, '-', $cuenta_amortizacion, 'Amort. Prest. p/cobrar banco al '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
								agregar_f820($elasiento4, $b, '+', $cuentabanco, 'Prest. p/cobrar banco al '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
							}	
				
						// para los hipotecarios
						if ($j==3) {
							$tinteresh=0;
							for ($k=0;$k<count($codigos);$k++)
								if ($tipoi[$k]=='Hipotecario') 
								if ($interesg[$k] != 'NO TIENE'){
									$cuenta1=$interesg[$k];
									$debe=$interes[$k];
									$tinteresh+=$debe;
									if ($debe > 0)
										agregar_f820($elasiento1, $b, '-', $cuenta1, 'Interes del '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
								}
							$debe=$thipotecario+$tinteresh;
							agregar_f820($elasiento1, $b, '+', $cuenta_amortizacion, 'Total Retenciones del '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
							agregar_f820($elasiento2, $b, '-', $cuenta_amortizacion, 'Amort. Prest. p/cobrar banco al '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
							agregar_f820($elasiento2, $b, '+', $cuentabanco, 'Prest. p/cobrar banco al '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
						}
					// falta actualizar el prstamo y el amortizacion
					}	// while 
					$sql="update sgcaf310 set stapre_sdp='C', renovado=1 where codpre_sdp='046' and ultcan_sdp=1";
					if (! mysql_query($sql))
							echo $sql.'<br>';
			} // el registro esta marcado
		}
	else '<h2>Ya fue procesada anteriormente </h2>';
	}	// ciclo de registros marcados 
	set_time_limit(30);	
	
	
/*
		FOR vueltas=1 TO 2 
			STORE 0 TO MMontoPar, i1, Mabono, ca, ti
			IF vueltas = 1
*				condicion="((Codpre<='003') .or. ((Codpre>='006') .and. (Codpre<='009')) .or. ((Codpre>='026') .and. (Codpre<='030'))) or (codpre='032')"
				condicion="((Codpre<='003') .or. ((Codpre>='006') .and. (Codpre<='009')) .or. ((Codpre>='026') .and. (Codpre<='030'))) .or. (codpre>'035')"
*				condicion="((Codpre<='003') .or. ((Codpre>='006') .and. (Codpre<='009')))"
			ELSE
				condicion="((Codpre>'003') .and. ((Codpre<'006') .or. (Codpre>'009' and codpre<'026'))) or (codpre='032') or (codpre='031') or (codpre='035')"
			ENDIF 
			SELECT temporal
			GO top
			todos=0
			DO while ! eof()
				IF &condicion
					conteos=conteos+1
					thisform.olecontrol1.value=conteos
					Mcodpre_sdp = codpre
					MCodSoc     = CodSoc
					Mnropre     = alltrim(nropre)  && mcodpre_sdp+nropre
					mpre=Mnropre
					MCuenta1   = ALLTRIM(Cuent_P)
					MCuenta2   = ALLTRIM(Cuent_I)
					MCuenta3   = ALLTRIM(Cuent_D)
					SELECT temporal
					Minteres=interes
					interesmes=Minteres
					i1=Minteres
					cuotames=cuota && capital
					todos=todos+capital
					Mabono=cuotames
					ca=ca+cuotames
					ti=ti+i1
					IF vueltas = 1
						DO RegAsiento    WITH nrocomp1, fechacompr, MCuenta1, 'Retención Prest.Estat. '+DTOC(THISFORM.mfecha.VALUE), cuotames, '-'
					ELSE 
						DO RegAsiento    WITH nrocomp3, fechacompr, MCuenta1, 'Retención Conv.Comercial '+DTOC(THISFORM.mfecha.VALUE), cuotames, '-'
					ENDIF 
		
					IF vueltas=1
						DO RegAsiento    WITH nrocomp4, fechacompr, MCuenta3, 'Intereses Dif.'+DTOC(THISFORM.mfecha.VALUE), interesmes, '+'
						xposicion=ASCAN(arreglo,MCuenta2)
*!*							FOR XX=1 TO 32
*!*								WAIT WINDOW  ARREGLO[XX]
*!*							NEXT 
						valores[xposicion]=valores[xposicion]+interesmes
					ENDIF 
					IF vueltas=2
						IF codpre='032'
							valores[posiciondel032]=valores[posiciondel032]+interesmes
						ENDIF
					ENDIF 
		
					thisform.p400mostrar(temporal->cuota)
					thisform.p4000revisar(MCodSoc,temporal->cuota)
		
					SELECT SGCAF310
					SET order to sgcai311
					SEEK mpre
					DO WHILE ! EOF()
						IF mpre=nropre_sdp
							IF temporal.cedula=cedsoc_sdp
								IF temporal.codsoc=codsoc_sdp
									IF temporal.codpre=codpre_sdp
										IF temporal.fecha=f_1cuo_sdp
											SCATTER MEMVAR
											m.MonPag_sdp=m.MonPag_sdp+Mabono
											m.UltCan_Sdp=m.UltCan_Sdp + 1
											m.monint = m.monint + interesmes
											IF ((MonPre_Sdp-monpag_sdp) <= 0) && .or. (ultcan_sdp=nrocuotas)
												m.StaPre_Sdp ='C'
											ENDIF
											GATHER MEMVAR
											EXIT
										ENDIF 
									ENDIF
								ENDIF
							ENDIF
						ELSE
							EXIT
						ENDIF
						SKIP
					ENDDO
				ENDIF 
				SELECT temporal
				SKIP 
			ENDDO
			IF vueltas = 1
*!*					FOR _i=1 TO ALEN(arreglo)
*!*						IF valores[_i] # 0
*!*							DO RegAsiento WITH nrocomp1, fechacompr, arreglo[_i], 'Intereses por '+titulos[_i], valores[_i], '-'
*!*						ENDIF
*!*					NEXT
				mcuenta='1-02-01-01-03-03-0003' && '1-2-1-03-03-0003'
				SELECT SGCAF820
				DO RegAsiento    WITH nrocomp1, fechacompr, mcuenta, 'TOTAL  RETENCIONES AL MES '+nombrefecha, ca, '+'
*!*					DO RegAsiento    WITH nrocomp2, fechacompr, '1-1-2-19-20-0001', 'PRESTAMO P/COB. BCO.'+nombrefecha, todos, '+'
*!*					DO RegAsiento    WITH nrocomp2, fechacompr, '1-2-1-03-03-0003', 'AMORT. PRESTAMO P/COB. BCO.'+nombrefecha, todos, '-'
				DO RegAsiento    WITH nrocomp2, fechacompr, '1-01-02-01-15-01-0001', 'PRESTAMO P/COB. BCO.'+nombrefecha, todos, '+'
				DO RegAsiento    WITH nrocomp2, fechacompr, mcuenta, 'AMORT. PRESTAMO P/COB. BCO.'+nombrefecha, todos, '-'
			ELSE 
				FOR _i=1 TO ALEN(arreglo)
					IF 	_i#posiciondel032
						IF valores[_i] # 0
							DO RegAsiento WITH nrocomp4, fechacompr, arreglo[_i], 'Intereses por '+titulos[_i], valores[_i], '-'
						ENDIF
					ENDIF 
				NEXT
				mcuenta='1-02-01-01-03-03-0003' && '1-2-1-03-03-0003'
				SELECT SGCAF820
				DO RegAsiento 	 WITH nrocomp3, fechacompr, arreglo[posiciondel032], 'Intereses por '+titulos[posiciondel032], valores[posiciondel032], '-'
				DO RegAsiento    WITH nrocomp3, fechacompr, mcuenta, 'TOTAL  RETENCIONES AL MES '+nombrefecha, ca+ti, '+'
*!*					DO RegAsiento    WITH nrocomp2, fechacompr, '1-1-2-19-20-0001', 'PRESTAMO P/COB. BCO.'+nombrefecha, todos, '+'
*!*					DO RegAsiento    WITH nrocomp2, fechacompr, '1-2-1-03-03-0003', 'AMORT. PRESTAMO P/COB. BCO.'+nombrefecha, todos, '-'
				DO RegAsiento    WITH nrocomp2, fechacompr, '1-01-02-01-15-01-0001', 'PRESTAMO P/COB. BCO.'+nombrefecha, todos, '+'
				DO RegAsiento    WITH nrocomp2, fechacompr, mcuenta, 'AMORT. PRESTAMO P/COB. BCO.'+nombrefecha, todos, '-'
			ENDIF 
		NEXT
*/

}	// if (!$accion=='Abonar')

function revisar_fiadores($registro)
{
	$sqlp="select nropre_sdp, cuota_ucla, monpre_sdp from sgcaf310 where registro = '$registro'";
	$resp=mysql_query($sqlp);
//	if (!mysql_query($sqlp)) die ("El usuario $usuario no tiene permiso para consultar prestamos.<br>".$sqlp);
	$apre=mysql_fetch_assoc($resp);
	$numero=$apre['nropre_sdp'];
	$sqlf="select sum(monto_fia) as totalfianza from sgcaf320 where nropre_fia='$numero' and tipmov_fia='F' group by nropre_fia";
	$resf=mysql_query($sqlf);
	$rfia = mysql_fetch_assoc($resf);
	$totalfianza=$rfia['totalfianza'];
	if ($rfia['totalfianza'] > 0) {
		$sqlf="select * from sgcaf320 where nropre_fia='$numero' and tipmov_fia='F' ";
		$resf=mysql_query($sqlf);
		while ($rfia = mysql_fetch_assoc($resf)){
			$regfia=$rfia['registro'];
			$proporcion=$rfia['monto_fia'];
			$proporcion = (($proporcion * 100) / $apre['monpre_sdp'] ) ;
			$abonofianza = ($apre['cuota_ucla'] * ($proporcion / 100));
			$ufia="update sgcaf320 set monlib_fia = monlib_fia + '$abonofianza'";
			if (($rfia['monlib_fia'] + $abonofianza) >= $rfia['monto_fia'])
				$ufia.=", tipmov_fia = 'L'";
			$ufia.=" where registro = '$regfia'";
			$resfia=mysql_query($ufia);
		}
	}
}

function crear_encabezado($asiento,$fecha,$cuento)
{
	echo "Realizando Abonos / Registros contables del asiento <strong><a target=\"_blank\" href='editasi2.php?asiento=$asiento'>$asiento </a></strong> $cuento <br>";
	$sql = "INSERT INTO sgcaf830 (enc_clave, enc_fecha, enc_desco, enc_desc1, enc_debe, enc_haber, enc_item, enc_dif, enc_igual, enc_refer, enc_sw, enc_explic) VALUES ('$asiento', '$fecha', '','',0,0,0,0,0,0,0,'')"; 
	if (!mysql_query($sql)) die ("El usuario $usuario no tiene permiso para añadir Asientos.<br>".$sql);
}

?>

<?php // include("pie.php");?>

</body></html>

<?php
/*
	delete from sgcanopr;
	delete from sgcaamor;
	delete from sgcaf820 where com_nrocom='20090804001' or com_nrocom='20090804002' or com_nrocom='20090804003' or com_nrocom='20090804004' or com_nrocom='20090804005' or com_nrocom='20090804006' ;
	delete from sgcaf830 where enc_clave='20090804001' or enc_clave='20090804002' or enc_clave='20090804003' or enc_clave='20090804004' or enc_clave='20090804005' or enc_clave='20090804006' ;
SELECT * FROM `sgcaamor` WHERE tipo = 'Estatutario' order by codsoc LIMIT 30	
*/
?>