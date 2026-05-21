<?php
include("head.php");
include("paginar.php");
//echo $_SERVER['REMOTE_ADDR'].'<br>';

if (!$link OR !$_SESSION['empresa']) {
	include("noempresa.php");
	exit;
}
?>
<script language="Javascript" src="selec_fecha.js" type='text/javascript'></script>
<?
$readonly=" readonly='readonly'";
include("arriba.php");
$menu61=1;include("menusizda.php");
$codigo = $_GET['codigo'];
$ip = $_SERVER['HTTP_CLIENT_IP'];
if (!$ip) {$ip = $_SERVER['REMOTE_ADDR'];}


if ((!$_POST['inputString']) and (!$_POST['imagen'])) {	// seleccionar el tipo de prestamo nuevo de renovacion
	echo "<form action='voluntario.php' name='form1' method='post' enctype='multipart/form-data' >";
	echo '<fieldset><legend>Información Para Ahorro Voluntario </legend>';
	echo '<br>Cedula del Socio: ';
?>
	<input type="text" size="20" tabindex='5' name='inputString' id="inputString" onKeyUp="lookup_socios(this.value);" onBlur="fill_socios();" value ="" autocomplete="off"/>
	<div class="suggestionsBox" id="suggestions" style="display: none;">
	<img src="upArrow.png" style="position: relative; top: -12px; left: 70px; " alt="upArrow" />
	<div class="suggestionList" id="autoSuggestionsList">
	</div>
	</div>

<?php
    echo 'Monto del Voluntario ';
	echo '<input name="monto" type="text" id="monto" value="0.00"  size="10" maxlength="10" /><br>';
    echo 'Fecha ';
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
	<input type="hidden" name="fecha" id="fecha" value=" <?php  echo $fechanueva; ?>"/>
   <span style="background-color: #ff8; cursor: default;"
         onmouseover="this.style.backgroundColor='#ff0';"
         onmouseout="this.style.backgroundColor='#ff8';"
         id="show_ingcapu" 
   ><?php  echo ($hoy); ?></span> *
<script type="text/javascript">
    Calendar.setup({
//		showAt(220, 250)
//		position       : 	{100,300},
        inputField     :    "fecha",     // id of the input field
        ifFormat       :    "%d/%m/%Y",     // format of the input field (even if hidden, this format will be honored)
        displayArea    :    "show_ingcapu",       // ID of the span where the date is to be shown
//        daFormat       :    "%A, %B %d, %Y",// format of the displayed date
        daFormat       :    "%B %d, %Y",// format of the displayed date
        align          :    "Tl",           // alignment (defaults to "Bl")
        singleClick    :    true,
		weekNumbers    :    false, 
		range          :     <?php echo $rango; ?>,

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
//	echo '<input name="fecha" type="text" id="fecha" value=""  size="12" maxlength="12" /><br>';
	echo '<label for="imagen">Imagen de Soporte:</label>';
	echo '<input type="file" name="imagen" id="imagen" />';
	echo "<input type = 'submit' value = 'Procesar'>";
	echo '</fieldset>';
	echo '</form>';
}
else
{
	$inputString=$_POST['inputString'];
	$sql="select * from sgcaf200 where ced_prof='$inputString'";
	$result = mysql_query($sql) or die ('Error FH200-1 <br>'.$sql.'<br>'.mysql_error());
	$r1=mysql_fetch_assoc($result);
	$a=explode("/",$fecha); 
	$fecha=$a[2]."-".$a[1]."-".$a[0];

	
	$codigo=$r1['cod_prof'];
//	$fecha=$_POST['fecha'];
	$monto=$_POST['monto'];
	$imagen=$_POST['imagen'];
	$sql="INSERT INTO fhis200 (cod_prof, hab_prof, hab_ucla, fecha, descri, pago) VALUES ('$codigo', '$monto', 0, '$fecha', 'Ahorro Voluntario', '$fecha')";
//	echo $sql;
	$result = mysql_query($sql) or die ('Error FH200-1 <br>'.$sql.'<br>'.mysql_error());
	$sql="UPDATE sgcaf200 set hab_f_extr = hab_f_extr + $monto, ultap_extr='$fecha' where cod_prof='$codigo'";
//	echo $sql;
	$result = mysql_query($sql) or die ('Error F200-1 <br>'.$sql.'<br>'.mysql_error());

        $fileName = $_FILES['imagen']['name'];
        $tmpName  = $_FILES['imagen']['tmp_name'];
        $fileSize = $_FILES['imagen']['size'];
        $fileType = $_FILES['imagen']['type'];
        
        $fp = fopen($tmpName, 'r');
        $content = fread($fp, $fileSize);
        $content = addslashes($content);
        fclose($fp);
        
        if(!get_magic_quotes_gpc())
        {
            $fileName = addslashes($fileName);
        }
	
	$comando="INSERT INTO sgcafsop (referente, id, descripcion, imagen, fecha) VALUES ('Ahorro Voluntario', '$codigo', 'Ahorro','$content', '$fecha')";
	$result = mysql_query($comando);

	$b=$fecha;
	$asiento=$fecha;
	$asiento=explode('-',$asiento);
	$asiento=$asiento[0].$asiento[1].$asiento[2];
	$ultimo="select (con_compr+1) as nuevo from sgcaf8co limit 1";
	$aultimo=mysql_query($ultimo);
	$rultimo=mysql_fetch_assoc($aultimo);
	$elultimo=$rultimo['nuevo'];
	$elultimo=ceroizq($elultimo,3);
	$ultimo="update sgcaf8co set con_compr ='$elultimo' limit 1";
	$aultimo=mysql_query($ultimo);
	
	$asiento.=$elultimo;
	echo "Generando encabezado contable <strong><a target=\"_blank\" href='editasi2.php?asiento=$asiento'>$asiento </a></strong> <br>";
	$cuento='Ahorro Voluntario ';
	$sql = "INSERT INTO sgcaf830 (enc_clave, enc_fecha, enc_desco, enc_desc1, enc_debe, enc_haber, enc_item, enc_dif, enc_igual, enc_refer, enc_sw, enc_explic, enc_soporte) VALUES ('$asiento', '$b', '','',0,0,0,0,0,0,0,'$cuento', '$content')"; 
	if (!mysql_query($sql)) die ("El usuario $usuario no tiene permiso para añadir Asientos.<br>".$sql);

	$debe=$monto;
	$cuenta1='1-01-01-02-03-01-0002'; // banco
	agregar_f820($asiento, $b, '+', $cuenta1, "Ahorro Voluntario de Fecha ".$fecha, $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
	$cuenta1='3-01-04-01-01-01-'.substr($codigo,1,4); // socio
	agregar_f820($asiento, $b, '-', $cuenta1, "Ahorro Voluntario de Fecha ".$fecha, $debe, $haber, 0,$ip,0,$referencia,'','S',0); 

	echo '<H1>Procesado </h1>';
}