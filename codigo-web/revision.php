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
<body onLoad="showFlatCalendar()">
 <?php // if (!$bloqueo) {echo $onload;}>>
// <body <?php if (!$bloqueo) {echo $onload;}

$readonly=" readonly='readonly'";
include("arriba.php");
// $menu61=1;
include("menusizda.php");
$ip = $_SERVER['HTTP_CLIENT_IP'];
if (!$ip) {$ip = $_SERVER['REMOTE_ADDR'];}
$hoy = date("Y-m-d");
$sql="select * from sgcaf360 where retab_pres = 0";
$result2=mysql_query($sql);
//$sql="SELECT f_1cuo_sdp, codpre_sdp, count(f_1cuo_sdp) as cuantos, count(codpre_sdp) as tipos FROM sgcaf310 WHERE (codpre_sdp = '023' or codpre_sdp = '053' or codpre_sdp = '057' or codpre_sdp = '058' or codpre_sdp = '034' or codpre_sdp = '069') AND stapre_sdp = 'A' AND renovado =0 and f_1cuo_sdp < '$hoy' group by f_1cuo_sdp, codpre_sdp ORDER BY f_1cuo_sdp";
//$arreglo=array('023','053','057','058','034','069');	// 6
//$tamano=5;
//for ($prestamos=0;$prestamos<=$tamano;$prestamos++) 
while ($fila2 = mysql_fetch_assoc($result2)) 
{
	$codigo=$fila2['cod_pres']; // $arreglo[$prestamos];
	$sql="SELECT * FROM sgcaf310 WHERE (codpre_sdp = '$codigo') AND stapre_sdp = 'A' AND renovado =0 and f_1cuo_sdp < '$hoy' ORDER BY f_1cuo_sdp";
	echo '<table>';
	echo "<tr><td>$codigo ".$fila2['descr_pres']."</td>";
//	echo $sql;
	$derecha=0;
	$result=mysql_query($sql);
	while ($fila = mysql_fetch_assoc($result)) {
		echo '<td>';
		echo $fila['codsoc_sdp'].'<br>'.$fila['f_1cuo_sdp'];
		$lacedula=$fila['cedsoc_sdp'];
//		echo $lacedula;
		$lacedula=substr($lacedula,0,4).substr($lacedula,5,3).substr($lacedula,9,3);
		echo "<td><a target=\"_blank\" href='edocta.php?accion=Editar&cedula=".$lacedula."'><img src='imagenes/socioweb2.PNG' width='16' height='16' border='0' title='Estado de Cuenta' alt='Estado de Cuenta'  /></a></td>";
		echo '</td>';
		$derecha++;
		if ($derecha > 9)
		{
			$derecha=0;
			echo '</tr><tr><td></td>';
		}
	}
	echo '</tr>';
	echo '<table>';
}

die('termine');
if (!$accion) {
	echo "<div id='div1'>";
	echo "<form action='depositobanco2.php?accion=ListadoDeCuotas' name='form1' method='post'>";
	echo '<fieldset><legend>Información Para Deposito de Prestamos</legend>';
	echo 'Fecha en que se realiza el Descuento: ';
/*
<script type="text/javascript">
// setActiveStyleSheet(this, 'green');
setActiveStyleSheet(document.getElementById("defaultTheme"), "green");
</script>
	</b> <input type="text" name="fechadelpago" id="sel3" size="12" readonly
><input type="reset" value=" ... "
onclick="return showCalendar('sel3', '%d/%m/%Y');"><br />
*/
	$hoy = date("d/m/Y");
    $fechanueva=explode('/',$hoy);
	$fechanueva=$fechanueva[0].'/'.$fechanueva[1].'/'.$fechanueva[2];
//	$fechanueva=$fechanueva[0].'/'.$fechanueva[1].'/'.$fechanueva[2];
	$fechanueva=trim($fechanueva);
	$sqlano="SELECT substr(f_1cuo_sdp,1,4) as ano, count(f_1cuo_sdp) as cuantos FROM sgcaf310 WHERE (codpre_sdp = '023' or codpre_sdp = '053' or codpre_sdp = '057' or codpre_sdp = '058') AND stapre_sdp = 'A' AND renovado =0 group by f_1cuo_sdp ORDER BY f_1cuo_sdp limit 1";
	$sqlfano=mysql_query($sqlano);
	$sqlrano=mysql_fetch_assoc($sqlfano);
	$rango=$sqlrano['ano'];
	$sqlano='select substr(now(),1,4)';
	$sqlfano=mysql_query($sqlano);
	$sqlrano=mysql_fetch_assoc($sqlfano);
	if ($sqlrano['ano'] > $rango)
		$rango.=', '.$sqlrano['ano'];
		//
	?>
	<input type="hidden" name="fechadelpago" id="fechadelpago" value="<?php echo $fechanueva; ?>"/>
   <span style="background-color: #ff8; cursor: default;"
         onmouseover="this.style.backgroundColor='#ff0';"
         onmouseout="this.style.backgroundColor='#ff8';"
         id="show_ingcapu" 
   ><?php  echo '  /   /'; ?></span> *
<script type="text/javascript">
    Calendar.setup({
//		showAt(220, 250)
//		position       : 	{100,300},
        inputField     :    "fechadelpago",     // id of the input field
        ifFormat       :    "%d/%m/%Y",     // format of the input field (even if hidden, this format will be honored)
        displayArea    :    "show_ingcapu",       // ID of the span where the date is to be shown
//        daFormat       :    "%A, %B %d, %Y",// format of the displayed date
        daFormat       :    "%B %d, %Y",// format of the displayed date
        align          :    "Tl",           // alignment (defaults to "Bl")
        singleClick    :    true,
		weekNumbers    :    false, 
		range          :     [<?php echo $rango; ?>],

// desactivacion de 18 años pa' tras


/*
		dateStatusFunc :    function (date) { // disable weekend days (Saturdays == 6 and Subdays == 0)
						var today = new Date();
						return (
//							  (date.getTime() < today.getTime()-((365*18)*24*60*60*1000))
							  (date.getTime() > today.getTime()-(1*24*60*60*1000)) 
							  // || date.getTime() > today.getTime()+(10*24*60*60*1000))	date.getDay() == 0 || 
							  ) ? true : false;  }
*/
					    });
</script>
	<?php 
	echo '</tr><br>';

	echo '<td>Seleccione Tipo</td>';
   	echo '<td class="rojo">';
	echo '<select name="elprestamo" size="1">';
	$sqlprestamos="select * from sgcaf360 where dcto_sem = 0 order by cod_pres";
	$resultado=mysql_query($sqlprestamos);
	echo '<option value="032">032 - LIBRERIA SEMANAL</option>'; 
	echo '<option value="055">055 - NO HIPOTECARIO</option>'; 
	echo '<option value="064">064 - NO HIPOTECARIO</option>'; 
	echo '<option value="066">066 - NO HIPOTECARIO</option>'; 
	while ($fila2 = mysql_fetch_assoc($resultado)) {
		echo '<option value="'.$fila2['cod_pres'].'">'.$fila2['cod_pres'].' - '.$fila2['descr_pres'].'</option>'; }
	echo '</select> *'; 
	echo '</td>';
	echo '<br>Prestamos Estatutarios <input type="checkbox" name="estatutarios"    value = "on" align="right"/><br />';
//	echo "<input type = 'submit' value = 'Nuevo Prestamo'></form>\n"; 
	echo '</fieldset>';
	echo '</div>';

	echo '<fieldset><legend>Atentos con esta información</legend>';
	echo '<br><h1>Para Descontar<input type="checkbox" name="descontar" value = "on"  align="right"/><br /></h1>';
	echo '% de Descuento ';
	echo '<input name="porcentaje" type="text" id="porcentaje" value="100" size="7" maxlength="7" />';
	echo '</fieldset>';
	echo '<input type="submit" name="Submit" value="Enviar" />';
	echo '</legend>';
	echo '</form>';
	echo '</div>';
}	// !$accion
// echo 'accion = '.$accion;
// echo 'nominas '.$_POST['nominasnormales'];
// recordar bloquear la base de datos durante este proceso y luego liberarla
if (($accion=='ListadoDeCuotas') ) {	// and ($nominasnormales == 'on')
//	$fechadescuento=convertir_fecha($_POST['fechadelpago']);		// revisar que no hayan nominas con esa fecha
	$fechadescuento=convertir_fecha($_POST['fechadelpago']);		// revisar que no hayan nominas con esa fecha
	$sql="delete from sgcanopr where fecha = '$fechadescuento' ";
	$resultado=mysql_query($sql);
//	echo $sql;
	$sql="select count(fecha) as cuantos, ip from sgcanopr where fecha = '$fechadescuento' group by fecha";
//	echo $sql;
	$resultado=mysql_query($sql);
	if (mysql_num_rows($resultado)>0) {
		$registro=mysql_fetch_assoc($resultado);
		echo '<h2>No se puede procesar esta nomina existe una ya realizada con '.$registro['cuantos'].' registro generada desde la IP '.$registro['ip'].'</h2>';
		exit;
	}

	$fechaarchivo=explode('-',$fechadescuento);
	$fechaarchivo=$fechaarchivo[0].$fechaarchivo[1].$fechaarchivo[2];
	$nombre_archivo = 'nompre/'.$fechaarchivo.'domiciliacion.txt';
	$contenido = $nombre;
	fopen($nombre_archivo, 'w');

	// Asegurarse primero de que el archivo existe y puede escribirse sobre el.
	if (is_writable($nombre_archivo)) {

		// En nuestro ejemplo estamos abriendo $nombre_archivo en modo de adicion.
		// El apuntador de archivo se encuentra al final del archivo, asi que
		// alli es donde ira $contenido cuando llamemos fwrite().
		if (!$gestor = fopen($nombre_archivo, 'a')) {
			echo "<h2>No se puede abrir el archivo ($nombre_archivo) revise permisologia</h2>";
			exit;
		}
		else {

			echo "<div id='div1'>";
			echo "<form action='depositobanco2.php?accion=Abonar' name='form1' method='post' onsubmit='return realizo_abono_banco(form1)'>";
			echo '<input type="hidden" name="nombre_archivo" value = "'.$nombre_archivo.'"/>';
			echo '<input type="hidden" name="nominasnormales" value = "on"/>';
			$fechadescuento=$_POST['fechadelpago'];
			echo '<fieldset><legend>Recopilando información Para Descuentos de Prestamos al '.$fechadescuento.'</legend>';
			echo '<h2>Preparando información...</h2>';
			$fechadescuento=convertir_fecha($fechadescuento);
/*
			if ($flash == 'on')
			{
				$sql_360="select * from sgcaf360 where (cod_pres='023' or cod_pres='053' or cod_pres='057' or cod_pres='058') order by cod_pres";
				$_SESSION['tipo']='Flash ';
			}
			else 
				if ($libreria == 'on')
				{
					$sql_360="select * from sgcaf360 where (cod_pres='032' or cod_pres='040' or cod_pres='041' or cod_pres='049') order by cod_pres";
					$_SESSION['tipo']='Libreria ';
				}
			else 
				if ($cupon == 'on')
				{
					$sql_360="select * from sgcaf360 where (cod_pres='CDA') order by cod_pres";
					echo $sql_360;
					$_SESSION['tipo']='Cupon de Alimentacion ';
				}
			else 
				{
					$sql_360="select * from sgcaf360 where (cod_pres!='032' and cod_pres!='040' and cod_pres!='041' and cod_pres!='049') and (cod_pres!='023' and cod_pres!='053' and cod_pres!='057' and cod_pres!='058') order by cod_pres";
					$_SESSION['tipo']='(Estatutarios / Comerciales ) ';

				}
*/
//			echo $sql_360;
			if ($estatutarios == 'on')
			{
				$sql_360="select * from sgcaf360 where (dcto_sem = 1) order by cod_pres";
				$sql_360="select * from sgcaf360 where (dcto_sem = 1) and (cod_pres <> '055' and cod_pres <> '064') order by cod_pres";
				$_SESSION['tipo']='Prestamos Estatutarios ';
			}
			else {
				$sql_360="select * from sgcaf360 where (cod_pres='".$elprestamo."')";
				$a_360=mysql_query($sql_360);
				$r360=mysql_fetch_assoc($a_360);
				$_SESSION['tipo']=$r360['descr_pres'];
			}
//			echo $sql_360;
			$_SESSION['albanco']=$sql_360;
			$a_360=mysql_query($sql_360);
			$sql_200="select cod_prof, ced_prof, concat(ape_prof, ' ', nombr_prof) as nombre, ctan_prof from sgcaf200 where (ucase(statu_prof) != 'RETIRA') and (tipo_socio='P') order by ced_prof";
			$a_200=mysql_query($sql_200);
			set_time_limit(mysql_num_rows($a_200));
			
			$_SESSION['descontar']=($descontar=='on'?'S':'N');
			$_SESSION['porcentaje']=($_POST['porcentaje']/100);
//			echo $sql_200;

			echo '
			 <div class="ProgressBar">
    		  <div class="ProgressBarText"><span id="getprogress"></span>&nbsp;% completado</div>
		      <div id="getProgressBarFill"></div>
		    </div>';
			$ValorTotal=mysql_num_rows($a_200);
			$cuantos=0;

			while ($r200 = mysql_fetch_assoc($a_200))
			{
				$cedula=$r200['ced_prof'];
				$micedula=substr($cedula,0,4).'.'.substr($cedula,4,3).'.'.substr($cedula,7,4);
				$sql_310="select * from sgcaf310 where (stapre_sdp='A') and (cedsoc_sdp='$micedula') and (f_1cuo_sdp = '$fechadescuento') order by codpre_sdp";
				$a_310=mysql_query($sql_310);
//				if ((mysql_num_rows($a_310)) > 0) echo $sql_310;

				$cuantos++;
				$porcenta = $cuantos * 100 / $ValorTotal; //saco mi valor en porcentaje
				echo "<script>callprogress(".round($porcenta).")</script>"; //llamo a la función JS(JavaScript) para actualizar el progreso
				flush(); //con esta funcion hago que se muestre el resultado de inmediato y no espere a terminar todo el bucle con los 25 registros para recien mostrar el resultado
				ob_flush();


				revisar_prestamo($r200,$a_310,$a_360,$fechadescuento,$micedula,$ip,$gestor);
			} // ($r200 = mysql_fetch_assoc($a_200))
			echo '<input type="hidden" name="fechadescuento" value="'.$fechadescuento.'">';
			echo '<h2>Información Lista...</h2><br>';
			echo '<h2>Se ha generado el archivo '.$nombre_archivo.'<br> para su procesamiento a banco</h2>';
			echo '<input type="submit" name="Submit" value="Impresión de Listados" onClick="abrir2Ventanas(';
			echo "'";
			echo $fechadescuento;
			echo "'";
//			echo "'".'&downloadfile='.$nombre_archivo.'&';
			echo ');">  ';
//			echo '<input type="submit" name="Submit" value="Realizar Abono " />';
			echo '</legend>';
			echo '</form>';
			echo '</div>';	
		}
		fclose($gestor);
/*
		$downloadfile=nombre_archivo;
		echo 'header ("Content-Disposition: attachment; filename=\".$downloadfile.\"" )';
//		header ("Content-Disposition: attachment; filename=\"exportar.txt\"" );
		echo 'header("Content-Type: application/force-download")';
		echo 'header("Content-Transfer-Encoding: binary")';
		echo 'header("Content-Length: ".strlen($downloadfile))';
// 		header("Content-Length: ".strlen($filecontent));
		echo 'header("Pragma: no-cache")';
		echo 'header("Expires: 0")' ;
		echo $downloadfile;
*/
	}
	else {
		echo "<h2>No se puede crear el archivo ($nombre_archivo) revise permisologia</h2>";
		exit;
	}
}	// ($accion=='ListadoDeCuotas')
if (($accion=='Abonar')) { // and ($nominasnormales == 'on')) {
// if ($nominasnormales == 'on') {
	$fechadescuento=$_POST['fechadescuento'];
	$nombre_archivo=$_POST['nombre_archivo'];
//	echo '<input type="hidden" name="nombre_archivo" value = "'.$nombre_archivo.'"/>';
	echo "<div id='div1'>";


	$cuento='emision de listado descuento a banco '.$fechadescuento;
	$ip = $_SERVER['HTTP_CLIENT_IP'];
	if (!$ip) {$ip = $_SERVER['REMOTE_ADDR'];}
	$usuario=$_SERVER['REMOTE_ADDR'];
	$sql_bita="insert into sgcabita (cuento, ip, quien) values ('$cuento', '$ip', '$usuario')";
	$a_bita=mysql_query($sql_bita);

	
	echo '<h2>Puede proceder luego de la impresion de los listados a <br>realizar el abono a prestamos y el asiento contable y';
	echo '<br>recuerde obtener descargar el archivo </h2><h1>'.$nombre_archivo.'</h1><h2> para enviar al banco</h2>';

	echo '<h2><br>Proceso Finalizado</h2>';
	echo '<form action="depositotxt.php" method="post" name="form1" enctype="multipart/form-data">';
	echo '<input type="hidden" name="archivo" value = "'.$nombre_archivo.'">';
	echo '<input type="submit" name="procesar" value="Descargar Archivo '.$nombre_archivo.'" />';
	echo '</form>';

	echo "<form action='depositobanco2.php?accion=AsientoContable' name='form1' method='post'>";
	echo '<input type="hidden" name="fechadescuento" value="'.$fechadescuento.'">';
	echo '<input type="submit" name="deposito" value="Generar Asiento Contable " />';
	echo '</form>';


//	echo "<form action='bajpre.php' name='form1' method='post'>";
//	echo '<input type="submit" name="Submit" value="Descargar Archivo TXT"';
//	echo '</form>';
	/// hacer el asiento ver asiento original con intereses y demas
/*	echo '<script language="JavaScript" src="depositobancopdf1.php?fechadescuento=$fechadescuento"></script>';  */
//	echo '<a href="" onClick="abrir2Ventanas();">KK</a>';
//	echo "<a target=\"_blank\" href=\"depositobancopdf1.php?fechadescuento=$fechadescuento\" onClick=\"info.html\', \'\',\'width=250, height=190\')\">Imprimir Listados de Descuentos</a>"; 	


	echo '</div>';
}	// ($accion=='ImpresionListados') 


if (($accion=='AsientoContable')) { // and ($nominasnormales == 'on')) {
	if ($_SESSION['descontar']=='S')
		QuienesDesconte($fechadescuento);
//		AsientoDescuento($fechadescuento);
	else 
	{ 
		AsientoAbono($fechadescuento);
		echo '<h1>Asiento Contable Generado</h1>';
	}
}
if (($accion=='AsientoDescuento')) {
	AsientoDescuento($fechadescuento);
	echo '<h1>Asiento Contable Generado</h1>';
}


function AsientoDescuento($fechadescuento)
{
	extract($_POST);
	$sql_360=$_SESSION['albanco'];
	$a_360=mysql_query($sql_360);
	$rpl=300; 	// registros por listado
	$crl=0;		// contador de registros por listado
	$col_listado=0;
	$condicion_sql='select codigo, cedula, nombre, nrocta, ';
	$col_listado=0;
	$max_cols=mysql_num_rows($a_360);
	$songiros=false;
	$fechaasiento=convertir_fecha($_POST['fechaasiento']);
	
	while ($r360 = mysql_fetch_assoc($a_360))
	{
		$col_listado++;
//		$header[$col_listado] =trim($r360['descr_pres']).'('.$r360['cod_pres'].')' ;
		$header[$col_listado] =''.$r360['cod_pres'].'' ;
		$cpc[$col_listado] =trim($r360['cuent_pres']);
		if (($header[$col_listado] == '034') or ($header[$col_listado] == '069'))
		{
			$cingreso='4-02-01-03-01-01-0004';
			$songiros=true;
		}
		$interes[$col_listado] =$r360['i_max_pres'];
		$cinteres[$col_listado] =$r360['otro_int'];
		$minteres[$col_listado] =0;
//		echo $interes[$col_listado].$cinteres[$col_listado];
		$totales[$col_listado]=0;
		$campo='colpre'.$col_listado;
		$condicion_sql.=' colpre'.$col_listado.', '.'colnro'.$col_listado;
		if ($col_listado != $max_cols) {
			$arrtitulo.=', ';
			$condicion_sql.=', ';
		}
	}
	$asiento=$fechaasiento; // $fechadescuento; // $_POST['$fechadescuento'];
	$asiento=explode('-',$asiento);
	$asiento=$asiento[0].$asiento[1].$asiento[2];
//	echo 'el asiento '.$asiento;
	$b=$fechaasiento; // $fechadescuento;
	$ultimo="select (con_compr+1) as nuevo from sgcaf8co limit 1";
	$aultimo=mysql_query($ultimo);
	$rultimo=mysql_fetch_assoc($aultimo);
	$elultimo=$rultimo['nuevo'];
	if ($elultimo >= 999)
		$elultimo=1;
	$elultimo=ceroizq($elultimo,3);
	$ultimo="update sgcaf8co set con_compr ='$elultimo' limit 1";
	$aultimo=mysql_query($ultimo);
	
	$asiento.=$elultimo;

	$cuento='generacion de asiento '.$asiento .' de fecha '.$fechadescuento. ' para descuento a socios ';
	$ip = $_SERVER['HTTP_CLIENT_IP'];
	if (!$ip) {$ip = $_SERVER['REMOTE_ADDR'];}
	$usuario=$_SERVER['REMOTE_ADDR'];
	$sql_bita="insert into sgcabita (cuento, ip, quien) values ('$cuento', '$ip', '$usuario')";
	$a_bita=mysql_query($sql_bita);


//	echo 'el asiento '.$asiento;
	echo "Generando encabezado contable <strong><a target=\"_blank\" href='editasi2.php?asiento=$asiento'>$asiento </a></strong> <br>";
	$cuento='Descuento de Prestamos '.$_SESSION['tipo'];
	$sql = "INSERT INTO sgcaf830 (enc_clave, enc_fecha, enc_desco, enc_desc1, enc_debe, enc_haber, enc_item, enc_dif, enc_igual, enc_refer, enc_sw, enc_explic) VALUES ('$asiento', '$b', '','',0,0,0,0,0,0,0,'$cuento')"; 
	if (!mysql_query($sql)) die ("El usuario $usuario no tiene permiso para añadir Asientos.<br>".$sql);

	$sql_nopr=$condicion_sql." from sgcanopr where ('$fechadescuento' = fecha) order by cedula "; //  limit 20";
//	die($sql_nopr);
	$a_nopr=mysql_query($sql_nopr);
	$registros=mysql_num_rows($a_nopr);
	if ($registro < 30)
		$registro=30;
	set_time_limit($registros);

			echo '
			 <div class="ProgressBar">
    		  <div class="ProgressBarText"><span id="getprogress"></span>&nbsp;% completado</div>
		      <div id="getProgressBarFill"></div>
		    </div>';
			$ValorTotal=$registros;
			$cuantos=0;

	$lascolumnas=mysql_num_fields($a_nopr)-4;
	$totalesgral=0;
	while ($r_nopr = mysql_fetch_assoc($a_nopr)){

				$cuantos++;
				$porcenta = $cuantos * 100 / $ValorTotal; //saco mi valor en porcentaje
				echo "<script>callprogress(".round($porcenta).")</script>"; //llamo a la función JS(JavaScript) para actualizar el progreso
				flush(); //con esta funcion hago que se muestre el resultado de inmediato y no espere a terminar todo el bucle con los 25 registros para recien mostrar el resultado
				ob_flush();



		$t1=0;
		for ($prestamos=1;$prestamos<=$lascolumnas;$prestamos++) {		// sumatoria de los prestamos
			$item='colpre'.$prestamos;
			$t1+=$r_nopr[$item];
			$totales[$prestamos]+=$r_nopr[$item];
		}
		if ($t1 > 0) {
			$cont++;
			for ($prestamos=1;$prestamos<=$lascolumnas;$prestamos++) {
				$posicion++;
				$item='colpre'.$prestamos;
				$itemn='colnro'.$prestamos;
				$t1+=$r_nopr[$item];
				// sacar el interes de ese prestamo
				$sql_p="select interes_sd from sgcaf310 where nropre_sdp='".$r_nopr[$itemn]."'";
				$res_p=mysql_query($sql_p);
				$res_r=mysql_fetch_assoc($res_p);
				$esteinteres=$res_r['interes_sd'];
				// fin 
				if ($r_nopr[$item] > 0)
				{	
//					$debe=($r_nopr[$item] / (1 + ($interes[$prestamos]/100)));
					$debe=($r_nopr[$item] / (1 + ($esteinteres/100)));
					$elinteres=$r_nopr[$item]-$debe;
					if (($header[$prestamos] == '045') or ($header[$prestamos] == '044'))
					{
						$debe=$r_nopr[$item];
						$elinteres=0;
					}
					$minteres[$prestamos]+=$elinteres;
//					echo $minteres[$prestamos]. '- '.$elinteres.'<br>';
					$cuentaxpagar=$cpc[$prestamos];
					$cuenta1=$cuentaxpagar.'-'.substr($r_nopr["codigo"],1,4);
					if ((substr(trim($r_nopr[$itemn]), -1) < 2)  and $songiros == true)
						$cuenta1=$cingreso;
					agregar_f820($asiento, $b, '-', $cuenta1, 'Retencion Prest.#'.$r_nopr[$itemn] .'('.$header[$prestamos].')', $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
					$totalesgral+=($debe+$elinteres);
					$rechazado=false;


					for ($i=0;$i<$_POST['totalmarcados'];$i++)
					{
						$variable='cancelar'.($i+1);
						if (!empty($$variable)) 
							if (($$variable == $r_nopr[$itemn])) // no se le desconto 
							{
								agregar_f820($asiento, $b, '+', $cuenta1, 'Rechazo Retencion Prest.#'.$r_nopr[$itemn] .'('.$header[$prestamos].')', $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
								$rechazado=true;
								$totalesgral-=($debe+$elinteres);
								$minteres[$prestamos]-=$elinteres;
							}
					}

					if ($rechazado == false) 
					{
//						echo 'descontando... ignorado temporalmnente<br>';

						$sql310="update sgcaf310 set monpag_sdp=monpag_sdp+'$debe' where nropre_sdp='".$r_nopr[$itemn]."'";
						$res310=mysql_query($sql310);
						$sql310="select monpre_sdp-monpag_sdp as saldo from sgcaf310 where nropre_sdp='".$r_nopr[$itemn]."'";
						$res310=mysql_query($sql310);
						$r310=mysql_fetch_assoc($res310);
						if ($r310['saldo'] <= 0) // esta pago
							$sql310="update sgcaf310 set stapre_sdp='C', renovado=1, ultcan_sdp=ultcan_sdp+1, renova_por='VIA BANCO' where nropre_sdp='".$r_nopr[$itemn]."'";
						else
							$sql310="update sgcaf310 set ultcan_sdp=ultcan_sdp+1 where nropre_sdp='".$r_nopr[$itemn]."'";
						$res310=mysql_query($sql310);

					}
				}
				$totales[$prestamos]+=$r_nopr[$item];
			}
		}
	}
	$debe=$minteres[1];
	$cuenta1=$cinteres[1]; // $cuentaxpagar.='-'.substr($r_nopr["codigo"],1,4);
	agregar_f820($asiento, $b, '-', $cuenta1, 'Pagos de Socios de Fecha '.$fechadescuento, $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
	$debe=$totalesgral;
//	$cuenta1='1-01-01-02-03-01-0002'; // $cuentaxpagar.='-'.substr($r_nopr["codigo"],1,4);

	$sql="select * from sgcaf000 where tipo='IngresoBanco'";
	$result=mysql_query($sql); // or die ("<p />El usuario $usuario no pudo conseguir la cuenta x pagar<br>".mysql_error()."<br>".$sql);
	$cuentas=mysql_fetch_assoc($result);
	$cuenta1=trim($cuentas['nombre']); // .'-'.substr($laparte,1,4);

	agregar_f820($asiento, $b, '+', $cuenta1, 'Pagos de Socios de Fecha '.$fechadescuento, $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
	
	// registrar la comision 
	$sql="select * from sgcaf000 where tipo='IngresoBanco'";
	$result=mysql_query($sql); // or die ("<p />El usuario $usuario no pudo conseguir la cuenta x pagar<br>".mysql_error()."<br>".$sql);
	$cuentas=mysql_fetch_assoc($result);
	$cuenta1=trim($cuentas['nombre']); // .'-'.substr($laparte,1,4);
	$debe=($debe*0.01);
	agregar_f820($asiento, $b, '-', $cuenta1, 'Pagos de Socios de Fecha '.$fechadescuento, $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
	$sql="select * from sgcaf000 where tipo='ComisionBanco'";
	$result=mysql_query($sql); // or die ("<p />El usuario $usuario no pudo conseguir la cuenta x pagar<br>".mysql_error()."<br>".$sql);
	$cuentas=mysql_fetch_assoc($result);
	$cuenta1=trim($cuentas['nombre']); // .'-'.substr($laparte,1,4);
	agregar_f820($asiento, $b, '+', $cuenta1, 'Pagos de Socios de Fecha '.$fechadescuento, $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
	// fin registrar la comision
}


function AsientoAbono($fechadescuento)
{
	$sql="select * from sgcaf000 where tipo='CtaSocxPag'";
	$result=mysql_query($sql); // or die ("<p />El usuario $usuario no pudo conseguir la cuenta x pagar<br>".mysql_error()."<br>".$sql);
	$cuentas=mysql_fetch_assoc($result);
	$cuentaxpagar=trim($cuentas['nombre']); // .'-'.substr($laparte,1,4);
	$sql_360=$_SESSION['albanco'];
	$a_360=mysql_query($sql_360);
	$rpl=300; 	// registros por listado
	$crl=0;		// contador de registros por listado
	$col_listado=0;
	$condicion_sql='select codigo, cedula, nombre, nrocta, ';
	$col_listado=0;
	$max_cols=mysql_num_rows($a_360);
	
	
	while ($r360 = mysql_fetch_assoc($a_360))
	{
		$col_listado++;
//		$header[$col_listado] =trim($r360['descr_pres']).'('.$r360['cod_pres'].')' ;
		$header[$col_listado] =''.$r360['cod_pres'].'' ;
//		echo $header[$col_listado];
		$totales[$col_listado]=0;
		$campo='colpre'.$col_listado;
		$condicion_sql.=' colpre'.$col_listado;
		$condicion_sql.=', colnro'.$col_listado;
		if ($col_listado != $max_cols) {
			$arrtitulo.=', ';
			$condicion_sql.=', ';
		}
	}
	$asiento=$fechadescuento; // $_POST['$fechadescuento'];
	$asiento=explode('-',$asiento);
	$asiento=$asiento[0].$asiento[1].$asiento[2];
//	echo 'el asiento '.$asiento;
	$b=$fechadescuento;
	$ultimo="select (con_compr+1) as nuevo from sgcaf8co limit 1";
	$aultimo=mysql_query($ultimo);
	$rultimo=mysql_fetch_assoc($aultimo);
	$elultimo=$rultimo['nuevo'];
	$elultimo=ceroizq($elultimo,3);
	$ultimo="update sgcaf8co set con_compr ='$elultimo' limit 1";
	$aultimo=mysql_query($ultimo);
	
	$asiento.=$elultimo;

	$cuento='generacion de asiento '.$asiento .' de fecha '.$fechadescuento. ' para depositar a socios ';
	$ip = $_SERVER['HTTP_CLIENT_IP'];
	if (!$ip) {$ip = $_SERVER['REMOTE_ADDR'];}
	$usuario=$_SERVER['REMOTE_ADDR'];
	$sql_bita="insert into sgcabita (cuento, ip, quien) values ('$cuento', '$ip', '$usuario')";
	$a_bita=mysql_query($sql_bita);

//	echo 'el asiento '.$asiento;
	echo "Generando encabezado contable <strong><a target=\"_blank\" href='editasi2.php?asiento=$asiento'>$asiento </a></strong> <br>";
	$cuento='Deposito a Banco de Prestamos '.$_SESSION['tipo'];
	$sql = "INSERT INTO sgcaf830 (enc_clave, enc_fecha, enc_desco, enc_desc1, enc_debe, enc_haber, enc_item, enc_dif, enc_igual, enc_refer, enc_sw, enc_explic) VALUES ('$asiento', '$b', '','',0,0,0,0,0,0,0,'$cuento')"; 
	if (!mysql_query($sql)) die ("El usuario $usuario no tiene permiso para añadir Asientos.<br>".$sql);

	$sql_nopr=$condicion_sql." from sgcanopr where ('$fechadescuento' = fecha) order by cedula "; //  limit 20";
//	 echo $sql_nopr;
	$a_nopr=mysql_query($sql_nopr);
	$registros=mysql_num_rows($a_nopr);
	if ($registro < 30)
		$registro=30;
	set_time_limit($registros);

			echo '
			 <div class="ProgressBar">
    		  <div class="ProgressBarText"><span id="getprogress"></span>&nbsp;% completado</div>
		      <div id="getProgressBarFill"></div>
		    </div>';
			$ValorTotal=$registros;
			$cuantos=0;

	$lascolumnas=mysql_num_fields($a_nopr)-4;
	$totalesgral=0;
	while ($r_nopr = mysql_fetch_assoc($a_nopr)){

				$cuantos++;
				$porcenta = $cuantos * 100 / $ValorTotal; //saco mi valor en porcentaje
				echo "<script>callprogress(".round($porcenta).")</script>"; //llamo a la función JS(JavaScript) para actualizar el progreso
				flush(); //con esta funcion hago que se muestre el resultado de inmediato y no espere a terminar todo el bucle con los 25 registros para recien mostrar el resultado
				ob_flush();



		$t1=0;
		for ($prestamos=1;$prestamos<=$lascolumnas;$prestamos++) {		// sumatoria de los prestamos
			$item='colpre'.$prestamos;
			$t1+=$r_nopr[$item];
			$totales[$prestamos]+=$r_nopr[$item];
		}
		if ($t1 > 0) {
			$cont++;
			for ($prestamos=1;$prestamos<=$lascolumnas;$prestamos++) {
				$posicion++;
				$item='colpre'.$prestamos;
				$item2='colnro'.$prestamos;
				$t1+=$r_nopr[$item];
				if ($r_nopr[$item] > 0)
				{	
					$debe=$r_nopr[$item];
					$cuenta1=$cuentaxpagar.'-'.substr($r_nopr["codigo"],1,4);
					$referencia=$r_nopr[$item2];
					echo $item2;
					agregar_f820($asiento, $b, '+', $cuenta1, $r_nopr["nombre"].'('.$header[$prestamos].')', $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
					$totalesgral+=$debe;
				}
				$totales[$prestamos]+=$r_nopr[$item];
			}
		}
	}
	$debe=$totalesgral;
	$cuenta1='1-01-01-02-03-01-0002'; // $cuentaxpagar.='-'.substr($r_nopr["codigo"],1,4);
	agregar_f820($asiento, $b, '-', $cuenta1, 'Pagos a Socios de Fecha '.$fechadescuento, $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
}

function revisar_prestamo($r200,$a_310,$a_360,$fechadescuento,$micedula,$ip,$gestor)
{
	$primeravez=0;
	$totalxsocio=0;
	while ($r310 = mysql_fetch_assoc($a_310))
	{
		if (! $r310['renovado'])
			if ($r310['stapre_sdp'] == 'A')
				acumular($r200,$r310,$a_360,$fechadescuento,$micedula,$primeravez,$ip,$totalxsocio) ;
			else ;
		else 
			if ($r310['stapre_sdp'] == 'A')
				if ($r310['paga_hasta'] >= $fechadescuento)
					acumular($r200,$r310,$a_360,$fechadescuento,$micedula,$primeravez,$ip,$totalxsocio);
	}
	if ($totalxsocio > 0){	// meterlo en el listado a banco
		listadotxt($r200,$totalxsocio,$gestor);
	}
}

function listadotxt($r200,$totalxsocio,$gestor)
{
//0201082457570200015888V07333526        00000000000008937ABARCA DE G.TERESA G.                                                 00CAPPOUCL              *
//0201082457510200129328V16770549        00000000000000010Xx  CARRASCO R. TONDIS MIGUEL                                         00CAPPOUCL              *
	$cadena='02'.$r200['ctan_prof'];
	$cadena.=substr($r200['ced_prof'],0,1).substr($r200['ced_prof'],2,8).replicate(' ',8);
	$monto=trim($totalxsocio*100);
	// quito el punto
	$sinpunto='';
	for ($i=0;$i<strlen($monto);$i++)
		if (substr($monto,$i,1)!= '.')
			$sinpunto.=substr($monto,$i,1);
	$monto=ceroizq($sinpunto,17);
	$cadena.=$monto;
	$nombre=$r200['nombre'];
	$nombre=substr(trim($nombre),0,40);
	$rellenar=replicate(' ',40-strlen($nombre));
	$cadena.=$nombre.$rellenar;
	$cadena.=replicate(' ',30).'00'.'CAPPOUCL'.replicate(' ',14).'*'.chr(13).chr(10);
	if (fwrite($gestor, $cadena) === FALSE) {
		echo "No se puede escribir al archivo ($nombre_archivo)";
		exit;
	}

}

function replicate($caracterarepetir,$cantidaddeveces)
{
	$resultado='';
	for ($i=0;$i<$cantidaddeveces;$i++)
		$resultado.=$caracterarepetir;
	return $resultado;
}


function acumular($r200,$r310,$a_360,$fechadescuento,$micedula,&$primeravez,$ip,&$totalxsocio)
{
	if ($_SESSION['descontar']== 'S')
	{
		if ($r310['cuota_ucla'] == 0) {
			$actualiza="update sgcaf310 set cuota_ucla=".$r310['netcheque']*(1+($r310['interes_sd']/100))." where registro =".$r310['registro'];
			$ractualiza=mysql_query($actualiza);
			$lacuota = $r310['cuota'];
		}
		else $lacuota = $r310['cuota_ucla']; // $r310['cuota_ucla'];
//		echo '<br>'.$lacuota. ' - ' .$_SESSION['porcentaje']. '  ';
		$lacuota=$lacuota*$_SESSION['porcentaje'];
//		echo $lacuota.'<br>';
	}
	else {
		if ($r310['cuota_ucla'] == 0) {
			$actualiza="update sgcaf310 set cuota_ucla=".$r310['cuota']." where registro =".$r310['registro'];
			$ractualiza=mysql_query($actualiza);
			$lacuota = $r310['cuota'];
		}
		else $lacuota = $r310['netcheque']; // $r310['cuota_ucla'];
	}
	mysql_data_seek ($a_360, 0);		// volver al principio de la busqueda
	$nombre=$r200['nombre'];
	$codigo=$r310['codsoc_sdp'];
	$posicion=0;
	while ($r360 = mysql_fetch_assoc($a_360))
	{
		$posicion++;
//			echo $r310['nropre_sdp'].' - ' .$r310['codpre_sdp'].' '.'<br>'; // . ' - '.$r360['cod_pres'];
		if ($r310['codpre_sdp']==$r360['cod_pres']) {
//			echo '<br>';
			$lacolumnapres='colpre'.$posicion;
			$lacolumnanro='colnro'.$posicion;
			$elnumero=$r310['nropre_sdp'];
			if ($primeravez == 0) {
				$nrocuenta=$r200['ctan_prof'];
				$sql_pre="insert into sgcanopr (fecha, cedula, codigo, nombre, ".$lacolumnapres.", ".$lacolumnanro.", proceso, ip, nrocta) values ('$fechadescuento','$micedula','$codigo','$nombre','$lacuota','$elnumero',1, '$ip', '$nrocuenta')";
//					$primeravez = 1;
			}
//			else $sql_pre="update sgcanopr set ".$lacolumnapres." = '$lacuota', ".$lacolumnanro." = '$elnumero' where (cedula='$micedula')" ;
			else $sql_pre="update sgcanopr set ".$lacolumnapres." = ".$lacolumnapres."+'$lacuota', ".$lacolumnanro." = '$elnumero' where (cedula='$micedula')" ;
//			echo $sql_pre.'<br>';
			if (mysql_query($sql_pre)) {
				$saldo=$r310['monpre_sdp']-$r310['monpag_sdp'];
				$tipo=$r310['codpre_sdp'];
				$capital=$lacuota;
				$totalxsocio+=$capital;
				$i2=0;
//				echo $r360['i_max_pres']. ' - ' .$r310['nrocuotas'].' - '.$saldo.' - '.'<br>';
				$interes=cal_int($r360['i_max_pres'],$r310['nrocuotas'],$saldo,$factor_divisible = 52,$z=0,$i2);
//				echo $i2;
				$interes=round(($saldo*$i2),2);
				$cu=round($lacuota,2) - $interes;
				$la_cuota=$interes+$cu;
				if ($saldo <= 0) {
					$interes=0;
					$capital=$cuota;
				}
				if ($r360['tipo_interes']=='DirectoFuturo')
				{
					$interes=0;
					$capital=$cuota;
				}
				$cuent_p=trim($r360['cuent_pres']).'-'.substr($codigo,1,4);
				$cuent_i=trim($r360['cuent_int']).'-'.substr($codigo,1,4);
				$cuent_d=trim($r360['otro_int']);
				$nrocuota=$r310['ultcan_sdp']+1;
				$tipoprestamo=$r360['tipo'];
				$pos310=$r310['registro'];
				$sql_amor="insert into sgcaamor (fecha, codsoc, nropre, cedula, nombre, saldo, capital, interes, cuota, codpre, cuent_p, cuent_i, cuent_d, ip, nrocuota, proceso, tipo, pos310) values ('$fechadescuento', '$codigo', '$elnumero', '$micedula', '$nombre', '$saldo', '$capital', '$interes', '$la_cuota', '$tipo', '$cuent_p', '$cuent_i', '$cuent_d', '$ip','$nrocuota', 1, '$tipoprestamo', '$pos310')";
				$result2=mysql_query($sql_amor);
//				echo $sql_amor.'<br>';
			}
//			echo $sql_pre.'----'.$posicion.'<br>';
		}	// ($r360 = mysql_fetch_assoc($a_360))
	} // ($r310 = mysql_fetch_assoc($a_310))
}


function QuienesDesconte($fechadescuento)
{
	echo "<form enctype='multipart/form-data' action='depositobanco2.php?accion=AsientoDescuento' name='form1' id='form1' method='POST' >";
	$hoy = date("d/m/Y");
    $fechanueva=explode('/',$hoy);
	$fechanueva=$fechanueva[1].'/'.$fechanueva[0].'/'.$fechanueva[2];
	$sqlano='select substr(fech_ejerc,1,4) as ano from sgcaf100';
	$sqlfano=mysql_query($sqlano);
	$sqlrano=mysql_fetch_assoc($sqlfano);
	$rango=$sqlrano['ano'];
	$sqlano='select substr(now(),1,4)';
	$sqlfano=mysql_query($sqlano);
	$sqlrano=mysql_fetch_assoc($sqlfano);
	if ($sqlrano['ano'] > $rango)
		$rango.=', '.$sqlrano['ano'];
	?>
	<input type="hidden" name="fechaasiento" id="fechaasiento" value=" <?php  echo $fechanueva; ?>"/>
   <span style="background-color: #ff8; cursor: default;"
         onmouseover="this.style.backgroundColor='#ff0';"
         onmouseout="this.style.backgroundColor='#ff8';"
         id="show_ingcapu" 
   ><?php  echo 'Fecha del Comprobante '.($hoy); ?></span> *
<script type="text/javascript">
    Calendar.setup({
//		showAt(220, 250)
//		position       : 	{100,300},
        inputField     :    "fechaasiento",     // id of the input field
        ifFormat       :    "%d/%m/%Y",     // format of the input field (even if hidden, this format will be honored)
        displayArea    :    "show_ingcapu",       // ID of the span where the date is to be shown
//        daFormat       :    "%A, %B %d, %Y",// format of the displayed date
        daFormat       :    "%B %d, %Y",// format of the displayed date
        align          :    "Tl",           // alignment (defaults to "Bl")
        singleClick    :    true,
		weekNumbers    :    false, 
		range          :     [<?php echo $rango; ?>],

// desactivacion de 18 años pa' tras


/*
		dateStatusFunc :    function (date) { // disable weekend days (Saturdays == 6 and Subdays == 0)
						var today = new Date();
						return (
//							  (date.getTime() < today.getTime()-((365*18)*24*60*60*1000))
							  (date.getTime() > today.getTime()-(1*24*60*60*1000)) 
							  // || date.getTime() > today.getTime()+(10*24*60*60*1000))	date.getDay() == 0 || 
							  ) ? true : false;  }
*/
					    });
</script>
	<?php 
	echo '<input type="hidden" name="fechadescuento" value="'.$fechadescuento.'">';
	$sql_360=$_SESSION['albanco'];
	$a_360=mysql_query($sql_360);
	$rpl=300; 	// registros por listado
	$crl=0;		// contador de registros por listado
	$col_listado=0;
	$condicion_sql='select codigo, cedula, nombre, nrocta, ';
	$col_listado=0;
	$max_cols=mysql_num_rows($a_360);
	$songiros=false;
	
	
	while ($r360 = mysql_fetch_assoc($a_360))
	{
		$col_listado++;
//		$header[$col_listado] =trim($r360['descr_pres']).'('.$r360['cod_pres'].')' ;
		$header[$col_listado] =''.$r360['cod_pres'].'' ;
		$cpc[$col_listado] =trim($r360['cuent_pres']);
		if ($header[$col_listado] == '034') 
		{
			$cingreso='4-02-01-03-01-01-0004';
			$songiros=true;
		}
		$interes[$col_listado] =$r360['i_max_pres'];
		$cinteres[$col_listado] =$r360['otro_int'];
		$minteres[$col_listado] =0;
//		echo $interes[$col_listado].$cinteres[$col_listado];
		$totales[$col_listado]=0;
		$campo='colpre'.$col_listado;
		$condicion_sql.=' colpre'.$col_listado.', '.'colnro'.$col_listado;
		if ($col_listado != $max_cols) {
			$arrtitulo.=', ';
			$condicion_sql.=', ';
		}
	}
	$asiento=$fechadescuento; // $_POST['$fechadescuento'];
	$asiento=explode('-',$asiento);
	$asiento=$asiento[0].$asiento[1].$asiento[2];
	$sql_nopr=$condicion_sql." from sgcanopr where ('$fechadescuento' = fecha) order by cedula "; //  limit 20";
//	echo $sql_nopr;
	$a_nopr=mysql_query($sql_nopr);
	$registros=mysql_num_rows($a_nopr);
	if ($registro < 30)
		$registro=30;
	set_time_limit($registros);
	$registros=0;

/*
			echo '
			 <div class="ProgressBar">
    		  <div class="ProgressBarText"><span id="getprogress"></span>&nbsp;% completado</div>
		      <div id="getProgressBarFill"></div>
		    </div>';
			$ValorTotal=$registros;
			$cuantos=0;
*/

	$lascolumnas=mysql_num_fields($a_nopr)-4;
	$totalesgral=0;
	echo '<table align="center" class="basica 100 hover" width="500" border="1">';
	echo '<td colspan="5" align="center"><strong>Marcar los prestamos que no pudieron ser descontados</strong> </td>';
	while ($r_nopr = mysql_fetch_assoc($a_nopr)){

/*
				$cuantos++;
				$porcenta = $cuantos * 100 / $ValorTotal; //saco mi valor en porcentaje
				echo "<script>callprogress(".round($porcenta).")</script>"; //llamo a la función JS(JavaScript) para actualizar el progreso
				flush(); //con esta funcion hago que se muestre el resultado de inmediato y no espere a terminar todo el bucle con los 25 registros para recien mostrar el resultado
				ob_flush();

*/

		$t1=0;
		for ($prestamos=1;$prestamos<=$lascolumnas;$prestamos++) {		// sumatoria de los prestamos
			$item='colpre'.$prestamos;
			$t1+=$r_nopr[$item];
			$totales[$prestamos]+=$r_nopr[$item];
		}
		if ($t1 > 0) {
			$cont++;
			for ($prestamos=1;$prestamos<=$lascolumnas;$prestamos++) {
				$posicion++;
				$item='colpre'.$prestamos;
				$itemn='colnro'.$prestamos;
				$t1+=$r_nopr[$item];
				if ($r_nopr[$item] > 0)
				{	
					$debe=($r_nopr[$item] / (1 + ($interes[$prestamos]/100)));
					$elinteres=$r_nopr[$item]-$debe;
					if (($header[$prestamos] == '045') or ($header[$prestamos] == '044'))
					{
						$debe=$r_nopr[$item];
						$elinteres=0;
					}
					$minteres[$prestamos]+=$elinteres;
//					echo $minteres[$prestamos]. '- '.$elinteres.'<br>';
					$cuentaxpagar=$cpc[$prestamos];
					$cuenta1=$cuentaxpagar.'-'.substr($r_nopr["codigo"],1,4);
					if ((substr(trim($r_nopr[$itemn]), -1) < 2)  and $songiros == true)
						$cuenta1=$cingreso;

					echo '<tr>';
					echo '<td>'.$r_nopr['colnro1'].'</td>';
					echo '<td>'.$r_nopr['codigo'].'</td>';
					echo '<td>'.$r_nopr['nombre'].'</td>';
					echo '<td align="right">'.number_format($r_nopr['colpre1'],2,'.',',').'</td>';
	
					$registros++;
					echo '<td class="centro azul"><input type="checkbox" id="cancelar'.$registros.'" name="cancelar'.$registros.'" value="'.$r_nopr["colnro1"] .'" onClick="calccanc()" ';
					echo '</tr>';
/*
					agregar_f820($asiento, $b, '-', $cuenta1, 'Retencion Prest.#'.$r_nopr[$itemn] .'('.$header[$prestamos].')', $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
					$totalesgral+=($debe+$elinteres);
					$sql310="update sgcaf310 set monpag_sdp=monpag_sdp+'$debe' where nropre_sdp='".$r_nopr[$itemn]."'";
					$res310=mysql_query($sql310);
					$sql310="select monpre_sdp-monpag_sdp as saldo from sgcaf310 where nropre_sdp='".$r_nopr[$itemn]."'";
					$res310=mysql_query($sql310);
					$r310=mysql_fetch_assoc($res310);
					if ($r310['saldo'] <= 0) // esta pago
						$sql310="update sgcaf310 set stapre_sdp='C', renovado=1, ultcan_sdp=ultcan_sdp+1, renova_por='VIA BANCO' where nropre_sdp='".$r_nopr[$itemn]."'";
					else
						$sql310="update sgcaf310 set ultcan_sdp=ultcan_sdp+1 where nropre_sdp='".$r_nopr[$itemn]."'";
					$res310=mysql_query($sql310);
*/
				}
				$totales[$prestamos]+=$r_nopr[$item];
			}
		}
	}
	echo '</table>';
	echo '<input type="hidden" name="totalmarcados" value="'.$registros.'">';
	echo '<input type="submit" name="Submit" value="Enviar" />';
	echo '</form>';
}
?>

<?php include("pie.php");?>

</body></html>

