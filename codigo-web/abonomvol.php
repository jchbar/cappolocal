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
<script src="ajaxabov.js" type="text/javascript"></script>
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
	$sql="select DATE_SUB(NOW(),interval 180 day) as viejos";
	$a_sql=mysql_query($sql);
	$viejo=mysql_fetch_assoc($a_sql);
	$viejo=$viejo['viejos'];
	$sql='SELECT fecha, sum(cuota) as monto, count(fecha) as cuantos FROM sgcaamorvol where proceso = 1 and semanal = 1 and (fecha > "'.$viejo.'") group by fecha desc';
	$a_sql=mysql_query($sql);
//	echo $sql; 
	echo "<form action='abonomvol.php?accion=Abonar' name='form1' method='post' enctype='multipart/form-data' onsubmit='return realizar_abono(form1)'>";
	echo '<fieldset><legend>Información Para Descuentos de Prestamos</legend>';

	echo 'Archivo de Devolucion del Viernes <input name="archivo[]" type="file" value="Examinar"><br>';
	echo 'Archivo de Devolucion del Sabado <input name="archivo[]" type="file" value="Examinar"><br>';
	echo 'Archivo de Devolucion del Domingo <input name="archivo[]" type="file" value="Examinar"><br>';
	echo 'Primera Fecha Proceso del Banco</td><td><input type="hidden" name="primerafecha" id="primerafecha" value="">';
	?>
			<span style="background-color: #ff8; cursor: default;"
		onmouseover="this.style.backgroundColor='#ff0';"
		onmouseout="this.style.backgroundColor='#ff8';"
		id="show_d3" 
			><?php  echo '00/00/0000'; ?></span>
		<script type="text/javascript">
		    Calendar.setup({
		        inputField     :    "primerafecha",     // id of the input field
		        // ifFormat       :    "%d/%m/%Y",     // format of the input field (even if hidden, this format will be honored)
		        ifFormat       :    "%Y-%m-%d",     // format of the input field (even if hidden, this format will be honored)
		        displayArea    :    "show_d3",       // ID of the span where the date is to be shown
		        daFormat       :    "%A, %B %d, %Y",// format of the displayed date
		        align          :    "Tl",           // alignment (defaults to "Bl")
		        singleClick    :    true,
				weekNumbers    :    false,  
				dateStatusFunc :    function (date) 
				{ 
					var today = new Date();
					// return (
					  // (date.getTime() > today.getTime()-(1*24*60*60*1000)) 
									  // ) ? true : false;  
				}
			});
		</script>

	<?php
//	echo '<input type="submit" name="Submit" value="Procesar" />';

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
	if (empty($primerafecha)) //  == '00')
		die('<h1>No se definio fecha');
	// phpinfo();
	$fecha = $fechanomina;
	$tipo = $tiponomina;
	echo '
	 <div class="ProgressBar">
      <div class="ProgressBarText"><span id="getprogress"></span>&nbsp;% completado</div>
      <div id="getProgressBarFill"></div>
    </div>';
	

extract($_POST);
// phpinfo();
for ($veces=0;$veces<3;$veces++)	// recorrer los 3 archivos y procesar
{
		// primero reviso el archivo de indomiciliados  archivo del domingo
	$copiado = 'SI';		// cambiar a no y resolver este problema
	$elarchivo=$nom_arc[$veces];
//	echo 'el archivo '.$elarchivo;
	if(@$_FILES['archivo']['name'][$veces]!=='') 
	{
		$salida='devoluciones/sica_'.$_FILES['archivo']['name'][$veces];
		$archivosalida=fopen ($salida, "w+");
		$nueva_ruta='devoluciones/';
		$ruta_total = $_SERVER['DOCUMENT_ROOT'].$nueva_ruta;
		$ruta_total = $_SERVER['DOCUMENT_ROOT']."/cajaweb/devoluciones/".$_FILES['archivo']['name'][$veces];
		$BASENAMES = basename( $_FILES['archivo']['name'][$veces]);
		$nuevo_nombre=$BASENAMES;
//		echo 'el archivo '.$elarchivo. ' / '.'$$elarchivo' .'<<<<<';
		if (is_uploaded_file($HTTP_POST_FILES['archivo']['tmp_name'][$veces])) {
			$destino='devoluciones/';
			$destino.=$_FILES['archivo']['name'][$veces];
//			echo 'destino '.$destino.'<br>';
			if (move_uploaded_file($_FILES['archivo']['tmp_name'][$veces],$destino));
				else die ('fallo copia');
		} 
		else {
		   	die ("Possible file upload attack. Filename: " . $HTTP_POST_FILES['archivo']['name'][$veces]);
		}
			$archivo_name = $nuevo_nombre; 
			$original = $archivo_name;
			$extension = explode(".",$archivo_name);
			$num = count($extension)-1;
			if (1 == 1) { // (strtoupper($extension[$num]) == "TXT") {
				if($copiado = 'SI') { // $archivo_size < 60000) {
					// separar el archivo con los datos
					procesar($archivo_name,$primerafecha,$ip,$archivosalida,$numerocuotas,$veces);
				}
			else
				{ echo "el archivo supera los 60kb"; }
			}
		else
			{ echo "el formato de archivo no es valido, solo .txt => ".$original; }
		set_time_limit(30);

	
	
	for ($i=0;$i<$registros;$i++)	// no es necesarios revisar el check si aparece es porq estan seleccionados para hacer el asiento 
	{
		$variable='cancelar'.($i+1);
		if (!empty($$variable)) 
		{


			$fecha=explode('-',$$variable);
			$b=$fecha[0].'-'.$fecha[1].'-'.$fecha[2];
			$fecha=$b;

			$nuevafecha="select date_add('$primerafecha',INTERVAL ".$veces." DAY) as fecha";
			$rsqln=mysql_query($nuevafecha);
			$asqln=mysql_fetch_assoc($rsqln);
			$otrafecha=($asqln['fecha']);

			$sql="select nropre from sgcaamorvol where fecha ='$fecha' and proceso = 1 and (semanal = 1) order by codsoc"; // limit 10";
			// echo $sql.'<br>'.$otrafecha;
			$a_amor=mysql_query($sql);
			$tiempoestimado=mysql_num_rows($a_amor);
			$ValorTotal=$tiempoestimado;
			$cuantos=0;
			if ($tiempoestimado > 0) 
			{
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

				$contarA = $testatutario = $thipotecario = $tcomercial = $testatutarioA =  0;
				$estnocobrado = $intestnocobrado = $comnocobrado = $intcomnocobrado = $hipnocobrado = $inthipnocobrado = 0;
	
				$referencia='';
				$ofecha=explode('-',$otrafecha);
				$b=$ofecha[0].'-'.$ofecha[1].'-'.$ofecha[2];
				$elasiento1=$ofecha[0].$ofecha[1].$ofecha[2].'401';
				$elasiento2=$ofecha[0].$ofecha[1].$ofecha[2].'402';
				$elasiento3=$ofecha[0].$ofecha[1].$ofecha[2].'403';
				$elasiento4=$ofecha[0].$ofecha[1].$ofecha[2].'404';
				$elasiento5=$ofecha[0].$ofecha[1].$ofecha[2].'405';
				$elasiento6=$ofecha[0].$ofecha[1].$ofecha[2].'406';
				$elasientof=$ofecha[0].$ofecha[1].$ofecha[2].'407';
				$elasiento8=$ofecha[0].$ofecha[1].$ofecha[2].'408';
				$ofecha=$b;
				/*
				crear_encabezado($elasiento1,$b,'por prestamos hipotecarios');
				crear_encabezado($elasiento2,$b,'para intereses hipotecarios');
				*/
				crear_encabezado($elasiento3,$b,'por prestamos voluntarios');
				crear_encabezado($elasiento6,$b,'para intereses diferidos');
				crear_encabezado($elasiento4,$b,'para control interno');
				/*
				crear_encabezado($elasiento5,$b,'por convenios comerciales');
				*/
				$tinteresest=0;

				// for ($j=1;$j<2;$j++)
				$j=1;
				if (1 == 1) 
				{

					if ($j==1) $aprocesar='VOLUNTARIO';
					$sql="select * from sgcaamorvol where ((fecha ='$fecha') and (proceso = 1) and (semanal = 1) and (tipo ='$aprocesar')) order by codsoc"; // limit 10";
					// echo $sql.'<br>';
					echo "<h2>Procesando $aprocesar</h2><br>";
					$a_amor=mysql_query($sql);
					$tiempoestimado=mysql_num_rows($a_amor);
					if ($tiempoestimado < 30)
						$tiempoestimado=30;
					set_time_limit($tiempoestimado);
					while ($r_amor = mysql_fetch_assoc($a_amor)) 
					{

						$cuantos++;
						$porcentaje = $cuantos * 100 / $ValorTotal; //saco mi valor en porcentaje
						echo "<script>callprogress(".round($porcentaje).")</script>"; //llamo a la función JS(JavaScript) para actualizar el progreso
						flush(); //con esta funcion hago que se muestre el resultado de inmediato y no espere a terminar todo el bucle con los 25 registros para recien mostrar el resultado
						ob_flush();

						// revisar si esta indomiciliado
						$mostrar=0;
						$estado=indomiciliado($r_amor['cedula'], $ofecha,$motivo,$mostrar);
						// echo 'chequear '.$r_amor['cedula'].' ' .$r_amor['nropre'].' ' . $ofecha.' ' .$motivo.'<br>';
	
						$cuentaprestamo=$r_amor['cuent_p'];
						$cuentadiferido=$r_amor['cuent_i'];
						$cuentainteres =$r_amor['cuent_d'];
						$referencia = $r_amor['nropre'];
						for ($k=1;count($codigos);$k++)
							if ($r_amor['codpre']==$codigos[$k]) {
								$posicion=$k;
								break; }
						if ($estado == 0) 
						{
							$capital[$posicion]+=$r_amor['capital'];
							$interes[$posicion]+=$r_amor['interes'];
						}
						////////  estatutario 
						if ($r_amor['tipo']=='VOLUNTARIO') 
						{
							$asientoa=$elasiento3;
							$asientob=$elasiento6;
							$debe=$r_amor['capital']; // -$r_amor['interes'];
							$abonoc=$debe;
							agregar_f820($asientoa, $b, '-', $cuentaprestamo, 'Ret. Prest.Vol. del '.convertir_fechadmy($r_amor['fecha']), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
							if ($estado == 1)
								agregar_f820($asientoa, $b, '+', $cuentaprestamo, 'Ret. Dev. Prest.Vol. del '.convertir_fechadmy($r_amor['fecha']).$motivo, $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
							$debe=$r_amor['interes'];
							$abonoi=$debe;
							agregar_f820($asientob, $b, '+', $cuentadiferido, 'Int.Dif.Prest.Vol. del '.convertir_fechadmy($r_amor['fecha']), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
							if ($estado == 1)
								agregar_f820($asientob, $b, '-', $cuentadiferido, 'Int.Dif.Dev.Prest.Vol. del '.convertir_fechadmy($r_amor['fecha']).$motivo, $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
							if ($estado == 0)
							{
								$testatutario+=$r_amor['capital'];
								$tinteresest+=$debe;
							}
							else 
							{
								$estnocobrado+=$r_amor['capital'];
								$intestnocobrado+=$debe;
							}
						}

						// actualizo prestamo y amortizacion como procesada
						if ($estado == 0)
						{
							$upd_310="update sgcaf310 set monpag_sdp=monpag_sdp+$abonoc, monint=monint + $abonoi, ultcan_sdp=ultcan_sdp+1 where registro=".$r_amor['pos310'];
							if (! mysql_query($upd_310))
								echo $upd_310.'<br>';
							// revisar fiadores
							revisar_fiadores($r_amor['pos310']);
							// fin revisar fiadores
							$upd_amor="update sgcaamorvol set proceso = 2, abonado = now(), ip_abono = '$ip' where registro = ".$r_amor['registro'];
							if (! mysql_query($upd_amor))
								echo $upd_amor.'<br>';	
						}
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

					$sql="select * from sgcaf000 where tipo='IngPreBco'";
					$sql="select * from sgcaf000 where tipo='CtaDepTransito'";
					$result=mysql_query($sql); 
					$cuentas=mysql_fetch_assoc($result);
					$cuentaingbanco=trim($cuentas['nombre']);			

					$referencia='';
					if ($j==1) {
						$debe=$testatutario; // -$tinteresest; //-$tinteresest;
						agregar_f820($elasiento3, $b, '+', $cuenta_amortizacion, 'Total Retenciones del '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 	
						agregar_f820($elasiento4, $b, '-', $cuentabanco, 'Amort. Prest. p/cobrar banco al '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
						agregar_f820($elasiento4, $b, '+', $cuentaingbanco, 'Prest. p/cobrar banco al '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 		


						$debe= $tinteresest;
						//							agregar_f820($elasiento3, $b, '+', $cuenta_amortizacion, 'Total Retenciones del '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 	
						/*
							agregar_f820($elasiento4, $b, '-', $cuentabanco, 'Amort. Prest. p/cobrar banco al '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
							agregar_f820($elasiento4, $b, '+', $cuentaingbanco, 'Prest. p/cobrar banco al '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 		
						*/
						for ($k=1;$k<=count($codigos);$k++) 
							if ($tipoi[$k]=='Estatutario')
								if ($interesg[$k] != 'NO TIENE') {
								$cuenta1=$interesg[$k];
								$debe=$interes[$k];
								if ($debe > 0)
								agregar_f820($elasiento6, $b, '-', $cuenta1, 'Interes del '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
							}
					}
					/*
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
							$debe=$tcomercial;
							agregar_f820($elasiento5, $b, '+', $cuenta_amortizacion, 'Total Retenciones del '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
							agregar_f820($elasiento4, $b, '-', $cuentabanco, 'Amort. Prest. p/cobrar banco al '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
							agregar_f820($elasiento4, $b, '+', $cuentaingbanco, 'Prest. p/cobrar banco al '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 

							$debe=$intlocal;
							agregar_f820($elasiento5, $b, '+', $cuenta_amortizacion, 'Total Retenciones del '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
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
						$debe=$thipotecario; //+$tinteresh;
						agregar_f820($elasiento1, $b, '+', $cuenta_amortizacion, 'Total Retenciones del '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
						agregar_f820($elasiento2, $b, '-', $cuentabanco, 'Amort. Prest. p/cobrar banco al '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
						agregar_f820($elasiento2, $b, '+', $cuentaingbanco, 'Prest. p/cobrar banco al '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 


						$debe=$tinteresh;
						agregar_f820($elasiento1, $b, '+', $cuenta_amortizacion, 'Total Retenciones del '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
						agregar_f820($elasiento2, $b, '-', $cuentabanco, 'Amort. Prest. p/cobrar banco al '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
						agregar_f820($elasiento2, $b, '+', $cuentaingbanco, 'Prest. p/cobrar banco al '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
					}
					// para los Estatutarios A
					if ($j==4) {
						$tinteresh=0;
						for ($k=0;$k<count($codigos);$k++)
							if ($tipoi[$k]=='EstatutarioA') 
							if ($interesg[$k] != 'NO TIENE'){
								$cuenta1=$interesg[$k];
								$debe=$interes[$k];
								$tinteresh+=$debe;
								if ($debe > 0)
									agregar_f820($elasiento3, $b, '-', $cuenta1, 'Interes del '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
							}
						$debe=$testatutarioA; //+$tinteresh;
						agregar_f820($elasiento3, $b, '+', $cuenta_amortizacion, 'Total Retenciones del '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
						agregar_f820($elasiento4, $b, '-', $cuentabanco, 'Amort. Prest. p/cobrar banco al '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
						agregar_f820($elasiento4, $b, '+', $cuentaingbanco, 'Prest. p/cobrar banco al '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 


						$debe=$tinteresh;
						agregar_f820($elasiento3, $b, '+', $cuenta_amortizacion, 'Total Retenciones del '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
						agregar_f820($elasiento4, $b, '-', $cuentabanco, 'Amort. Prest. p/cobrar banco al '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
						agregar_f820($elasiento4, $b, '+', $cuentaingbanco, 'Prest. p/cobrar banco al '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
					}
					*/
					// falta actualizar el prstamo y el amortizacion
				}	// while 
				$sql="update sgcaf310 set stapre_sdp='C', renovado=1 where (codpre_sdp='VO1' and ultcan_sdp=nrocuotas)";
				if (! mysql_query($sql))
					echo $sql.'<br>';
			} // el registro esta marcado


	if ($veces>1) {
		crear_encabezado($elasientof,$b,'cierre de nominas fallidas');
		crear_encabezado($elasiento8,$b,'p/r cierre deposito en transito');
		$debe = $estnocobrado ;
		agregar_f820($elasientof, $b, '-', $cuentabanco, 'Amort. estnocobrado Prest. p/cobrar banco al '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
		agregar_f820($elasientof, $b, '+', $cuenta_amortizacion, 'Prest. estnocobrado p/cobrar banco al '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
		
/*
		$debe = $intestnocobrado ;
		agregar_f820($elasientof, $b, '-', $cuentabanco, 'Amort. intestnocobrado Prest. p/cobrar banco al '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
		agregar_f820($elasientof, $b, '+', $cuenta_amortizacion, 'Prest.intestnocobrado p/cobrar banco al '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
*/		
		$debe = $comnocobrado;
		agregar_f820($elasientof, $b, '-', $cuentabanco, 'Amort. comnocobrado Prest. p/cobrar banco al '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
		agregar_f820($elasientof, $b, '+', $cuenta_amortizacion, 'Prest.comnocobrado p/cobrar banco al '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
		
/*
		$debe = $intcomnocobrado ;
		agregar_f820($elasientof, $b, '-', $cuentabanco, 'Amort. intcomnocobrado Prest. p/cobrar banco al '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
		agregar_f820($elasientof, $b, '+', $cuenta_amortizacion, 'Prest. intcomnocobrado p/cobrar banco al '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
*/	

		$debe = $hipnocobrado ;
		agregar_f820($elasientof, $b, '-', $cuentabanco, 'Amort. hipnocobrado Prest. p/cobrar banco al '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
		agregar_f820($elasientof, $b, '+', $cuenta_amortizacion, 'Prest. hipnocobrado p/cobrar banco al '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
		
/*
		$debe = $inthipnocobrado ;
		agregar_f820($elasientof, $b, '-', $cuentabanco, 'Amort. inthipnocobrado Prest. p/cobrar banco al '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
		agregar_f820($elasientof, $b, '+', $cuenta_amortizacion, 'Prest. inthipnocobrado p/cobrar banco al '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
*/
		$sql="select * from sgcaf000 where tipo='CtaPrexCobBco'";
		$result=mysql_query($sql); 
		$cuentas=mysql_fetch_assoc($result);
		$deposito=$cuentabanco;
		$cuentabanco=trim($cuentas['nombre']);

		$sql="select * from sgcaf000 where tipo='ComisionDom'";
		$result=mysql_query($sql); 
		$cuentas=mysql_fetch_assoc($result);
		$comision=trim($cuentas['nombre']);
		$debe=0.05;
		agregar_f820($elasiento8, $b, '+', $cuentabanco, 'Prest. p/cobrar banco al '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
		agregar_f820($elasiento8, $b, '+', $deposito, 'Prest. p/cobrar banco al '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
		agregar_f820($elasiento8, $b, '-', $cuentabanco, 'Comision Prest. p/cobrar banco al '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
		agregar_f820($elasiento8, $b, '-', $cuentabanco, 'Comision Prest. p/cobrar banco al '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 

		// suspender los que no pasaron
		$fecha = $_POST['cancelar1'];
		$sql="select *, date_add('$fecha',INTERVAL 90 DAY) as suspension from sgcaamorvol where fecha ='$fecha' and proceso = 1 and (semanal = 1) order by codsoc"; 
		$indomiciliado=mysql_query($sql) or  die ("Ind-4<br>".mysql_error());
		while ($rindo = mysql_fetch_assoc($indomiciliado)){
			$nombre = $rindo['nombre'];
			$suspension = $rindo['suspension'];
			$lacedula = $rindo['cedula'];
			$codigo = $rindo['codigo'];
			$monto = $rindo['cuota'];
			$prestamo = 'PRESTAMO # '.$rindo['nropre'];

			$sql = "insert into suspende (codigo, nombre, prestamo, monto, cedula, reporto, fallo, activo, ingresado, suspendido, motivo_in) values ('$codigo', '$nombre', '$prestamo', $monto, '$lacedula', 'Sistema Banco','Indomiciliado Prestamo',1,'$fecha', '$suspension', '')";
			$registrar=mysql_query($sql) or  die ("Ind-5<br>'$sql".mysql_error());
		}
	}
/*
	// cerrar ingreso de intereses
	$sql="SELECT cuent_int, otro_int FROM sgcaf360 GROUP BY cuent_int ";
	$result=mysql_query($sql);
	while ($r360 = mysql_fetch_assoc($result)){
		$lacuenta=trim($r360['cuent_int']);
		$reverso=trim($r360['otro_int']);
		$tamano=strlen($lacuenta);
		if (($tamano > 4) and ($tamano < 19))
		{
			$sql810="select cue_codigo from sgcaf810 where substr(cue_codigo,1,".$tamano.") = '$lacuenta' order by cue_codigo";
			$res810=mysql_query($sql810);
			while ($r810 = mysql_fetch_assoc($res810)){
				$cuenta=$r810['cue_codigo'];
				if (substr($cuenta,-4)!='0001')
				{
					$debe=buscar_saldo_f810($cuenta);
					if ($debe > 0) // hacer reverso
					{
						agregar_f820($elasiento9, $b, '-', $cuenta, 'Cierre de Interes '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
						agregar_f820($elasiento9, $b, '+', $reverso, 'Cierre de Interes '.convertir_fechadmy($b), $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
					}
				}
				
			}
		}
	}
*/
	$comando = "update sgcaf8co set fechanominalunes= now()";
	$resultado=mysql_query($comando);
	}

	else '<h2>Ya fue procesada anteriormente </h2>';
	}	// ciclo de registros marcados 
	set_time_limit(30);	
	}
}	// if (!$accion=='Abonar')
}

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
	$sql = "INSERT INTO sgcaf830 (enc_clave, enc_fecha, enc_desco, enc_desc1, enc_debe, enc_haber, enc_item, enc_dif, enc_igual, enc_refer, enc_sw, enc_explic) VALUES ('$asiento', '$fecha', '$cuento','',0,0,0,0,0,0,0,'$cuento')"; 
	if (!mysql_query($sql)) die ("El usuario $usuario no tiene permiso para añadir Asientos.<br>".$sql);
}

function indomiciliado($cedula, $fecha, &$motivo, $mostrar)
{
	$sqlin="select * from sgcaresb where cedula = '$cedula' and fechagen = '$fecha' and substr(cadena,1,4)='6210'";
	$res_in=mysql_query($sqlin);
	$r_in=mysql_fetch_assoc($res_in);
	/*
	echo $sqlin;
	echo $r_in['estatus'].'<br>';
	*/
	$motivo='';
/*
6210J301781678V02541709       001010824575802000045090000000000146822010824501CGE0001 FONDOS INSUFICIENTES
12345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890
---------+---------+---------+---------+---------+---------+---------+---------+---------+---------+---------+
*/
	if ($r_in['estatus'] == 'AUTORIZADO')
		return 0;
	else {
		if ($mostrar == 1) {
			echo $r_in['cadena'].'<br>';
			echo $sqlin.'<br>';
		}
		$motivo=substr($r_in['cadena'],86,10);
		return 1;
	}
}

function procesar($archivo_name,$fechaaporte,$ip,$archivosalida, $numerocuotas, $dias)
{
// 123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890
//          1         2         3         4         5         6         7         8         9        10        11        12
// ---------+---------+---------+---------+---------+---------+---------+---------+---------+---------+---------+---------+
// 6110J301781678CAPPOUCLA                          201106142011061701082445190100023187VEF795000000APREST201.RECCASCAPPOUCLA
// 6210J301781678V12019714       001010824575102001406310000000000075012010824501CGE0001 FONDOS INSUFICIENTES
//echo 'valor '.$_POST['nominasemanal'];
$essemanal=($_POST['nominasemanal']==1?1:0);
//echo 'semanal '.$essemanal;
//echo 'Verificación de archivo <br>';
$lines = file('devoluciones/'.$archivo_name);
$faltoalguno=0;
set_time_limit($lines);
/*
echo '<form action="abonomvol.php" method="post" name="form1" enctype="multipart/form-data">';
echo "<input name='archivo' type='hidden' value='$archivo_name'>";
echo "<table class='basica 100 hover' width='100%'>";
*/
$contadorgeneral=0;
$hoy = date("Y-m-d");

extract($_POST);
$registros=$_POST['registros'];
for ($i=0;$i<$registros;$i++)	// no es necesarios revisar el check si aparece es porq estan seleccionados para hacer el asiento 
{
	$variable='cancelar'.($i+1);
	if (!empty($$variable)) 
	{
		$fecha=explode('-',$$variable);
		$b=$fecha[0].'-'.$fecha[1].'-'.$fecha[2];

//		echo 'fecha de nomina '.$b;
//		exit;
	}
}
foreach ($lines as $line_num => $linea) {
	$datos = explode("|", $linea);
	if (substr($datos[0],0,3)=='611') {
		$fecha=substr($datos[0],49+8,8);
		$fecha=substr($fecha,0,4).'-'.substr($fecha,4,2).'-'.substr($fecha,6,2);
//		echo "<input name='fecha' type='hidden' value='$fecha'>";
		$nuevafecha="select date_add('$fecha',INTERVAL ".$dias." DAY) as fecha";
		$rsqln=mysql_query($nuevafecha);
		$asqln=mysql_fetch_assoc($rsqln);
		$fecha=($asqln['fecha']);
//		echo 'Fecha de Proceso '.$fecha.'<br>';

//		echo '<br><input type="text" name="totalgeneral" id="totalgeneral" value=0 readonly="readonly">';
	}

	$cadena=$datos[0];
	$cedula=ceroizq(trim(substr($datos[0],15,8)),8);
	if (($cedula == '02') and (substr($datos[0],0,3)=='621'))
	{
		$cuenta=substr($datos[0],33,20);
		$sql_c="select ced_prof from sgcaf200 where ctan_prof='".$cuenta."'";
		// echo $sql_c.'<br>';
		$laced=mysql_query($sql_c);
		$laced=mysql_fetch_assoc($laced);
		$cedula=($laced['ced_prof']);
		$cedula=substr($cedula,2,8);

	}
	$cedula = 'V-'.substr($cedula,0,2).'.'.substr($cedula,2,3).'.'.substr($cedula,5,3);
	$monto=substr($datos[0],53,15);
	$monto = $monto / 100;
	$estatus = substr($datos[0],78,10);
		
	$sqlresbanco="insert into sgcaresb (cadena, fechagen, cedula, estatus, ip, fechaproc, fechanom, monto, abierta) values ('$cadena', '$fecha', '$cedula', '$estatus', '$ip', now(), '$b', '$monto', 1)";
	$ressql=mysql_query($sqlresbanco);
	// echo $sqlresbanco;

}
}

?>

<?php // include("pie.php");?>

</body></html>

