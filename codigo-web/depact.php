<?php
include("head.php");
?>
<script language="javascript">
function abrir2Ventanas(fecha)
{
// window.open("06_Inventario_actuallist.asp","prueba1", "width=385,height=180,top=0,left=0',status,toolbar =1,scrollbars,location");
// window.open("leftmenu.htm","prueba2","width=385,he ight=180,top=0,left=395,status,toolbar=1,scrollbar s,location");
window.open("depactpdf.php?fecha="+fecha,"parte1","width=385,height=180,top=0,left=395,status,toolbar=1,scrollbar s,location");	// listados de activos fijos depreciados
}
</script>
<?php
include("paginar.php");
if (!$link OR !$_SESSION['empresa']) {
	include("noempresa.php");
	exit;
}
if ($accion == 'Anadir') 
	$onload="onload=\"foco('cta')\""; 
else
	$onload="onload=\"foco('nactivo')\"";
?>

<body <?php if (!$bloqueo) {echo $onload;}?>>

 
  <?php
 
$readonly=" readonly='readonly'";
include("arriba.php");
$menu61=1;include("menusizda.php");
$cta = $_GET['cta'];
$_SESSION['nro']=$nro; 
$nactivo=$_GET['nactivo'];
$ip = $_SERVER['HTTP_CLIENT_IP'];
if (!$ip) {$ip = $_SERVER['REMOTE_ADDR'];}


?>
  <?php 
if (!$accion) {
//	echo "<div id='div1'>";
echo '<fieldset><legend>Depreciación de Activos Fijos</legend>';
		echo "<form action='depact.php?accion=Procesar' name='form1' method='post' onsubmit='return imprimir(form1)'>";
    echo 'Fecha de Última Depreciación' ;
$sql = "SELECT date_format(fechasdep,'%d/%m/%Y') AS fechadep FROM sgcaf600";
// echo $sql; 
$result=mysql_query($sql);
$fila = mysql_fetch_assoc($result);
$fechadep= $fila['fechadep']; 
$h= '35';
$d = suma_fechas($fechadep,$h); 
//echo $d;
$a=explode("/", $d);
$b = $a[0]."-".$a[1]."-".$a[2]; 
//echo $b; 
$a[0] = '01'; 
//echo $c;
$j= $a[0]."-".$a[1]."-".$a[2]; 
$hh = date($a[0]."-".$a[1]."-".$a[2]); 
//echo $hh; 
$hu= '1'; 
$fecha = restar_fechas($hh,$hu); 
$aa=explode("/", $fecha);
$bb = $aa[0]."".$aa[1]."".$aa[2];
	$cc = substr($aa[2], 1); 
	$codigo = $aa[0]."".$aa[1]."".$cc.$aa[0]."".$aa[1]; 
		$hoy = date("d/m/Y"); 
		$ho=explode("/", $hoy);
		$bbb = $ho[0]."-".$ho[1]."-".$ho[2]; 
$MiTimesTamp = mktime(0,0,0,$ho[1],$ho[0], $ho[2]);  
$MiTimesTamp1 = mktime(0,0,0,$aa[1],$aa[0],$aa[2]);  
//echo  $MiTimesTamp; 
//echo "<p />";
//echo $bbb; 
//echo "<p />";
//echo  $MiTimesTamp1; 
//echo "<p />";
//echo $bb; 
//echo $codigo; 
	echo '<input name="fechadep" type="text" id="fechadep" readonly = "readonly" value="'.$fechadep.'" size="10" maxlength="10" />';
	echo "<p />";
	echo 'Nueva Fecha para la Depreciación ';
	echo '<input name="newfechadep" type="text" id="newfechadep" readonly = "readonly" value="'.$fecha.'" size="10" maxlength="10" />';
	
	echo "<p />";
	echo '<input type="hidden" name="MiTimesTamp" value="'.$MiTimesTamp.'">';
	echo '<input type="hidden" name="MiTimesTamp1" value="'.$MiTimesTamp1.'">';
	echo '<input type="hidden" name="hoy" value="'.$hoy.'">';
	echo '<input type="hidden" name="fecha" value="'.$fecha.'">';
	echo '<input type="hidden" name="codigo" value="'.$codigo.'">';
	echo "<p />";
	//echo "<input type = 'submit' value = 'Procesar'>";
	echo "<p />";
	if ($MiTimesTamp1<=$MiTimesTamp){
	echo '<input type="submit" name="Submit" value="Impresión de Listados" onClick="abrir2Ventanas(';
			echo "'";
			echo $fecha;
			echo "'";
			echo ');">  ';
	}
	echo "<p />";
	echo '</form>';
//	echo "</div>";
}
?>
  <?php
if ($accion == "Procesar") {
    echo "<form action='depact.php?accion=Editar' name='form1' method='post'>";
	echo '<input type="radio" name="S" value = "1"/> SI';
    echo '<input type="radio" name="S" value = "2" checked /> NO <br />';
	echo '<input type="hidden" name="fecha" value="'.$fecha.'">';
	echo '<input type="hidden" name="codigo" value="'.$codigo.'">';
	echo '<input type="submit" name="Submit" value="Guardar cambios" />';
	}
	if ($S == '1') {
	  if ($accion == "Editar") {
	echo '<div id="div1">';
	echo "<form action='depact.php?accion=Verificar1' name='form1' method='post' onsubmit='return valsoc(form1)'>";
	$fechamysql=convertir_fecha($fecha);
	$sql = "INSERT INTO sgcaf830 (enc_clave, enc_fecha, enc_desco, enc_desc1, enc_debe, enc_haber, enc_item, enc_dif, enc_igual, enc_refer, enc_sw, enc_explic) VALUES ('$codigo', '$fechamysql', '','',0,0,0,0,0,0,0,'')"; 
	echo $sql.'<br>';
	if (!mysql_query($sql)) die ("El usuario $usuario no tiene permiso para añadir Asientos.<br>".$sql);
$ttcosto = 0; 
$ttdepacum = 0; 
$ttdep = 0;
$ttvalor = 0;
      $sql="SELECT * , substr( cta_contab, 1, 17 ) AS cuenta, date_format(fechaad, '%d/%m/%Y') AS fechax FROM sgcaf610, sgcaf620 WHERE (substr(cta_contab,1,17) = codigoact) and motivodes='' ORDER BY cta_contab";
		$resultado=mysql_query($sql);
		echo $sql; 
while ($row1 = mysql_fetch_array($resultado))

{	//echo $jj; 
  if ($jj <> $row1['descripact']){ 
    
	//	$sql="SELECT *, date_format(fechaad, '%d/%m/%Y') AS fechax FROM sgcaf610 where motivodes='' order by cta_contab";
	//	$result=mysql_query($sql);
	//echo "<table class='basica 100 hover' width='100%'><tr>";
	//echo '<th colspan= "11" >'.$row1['descripact'].'</th><tr>'; 
	//echo '<th>Identificación</th><th>Cta. Contable </th><th>Descripción</th><th>Fecha de Adquisición';
	//echo '<th>Costo</th><th>Meses</th><th>%</th><th>Depreciación Acumulada</th><th>Depreciación</th><th>Valor según Libros</th><th>Fecha Depreciación</th>';
	
	$sql = "SELECT *, date_format(fechaad, '%d/%m/%Y') AS fechax FROM sgcaf610 where motivodes='' order by cta_contab";
	$rs = mysql_query($sql);
  //  echo $sql;
	$tcosto = 0; 
	$tdepacum = 0; 
	$tdep = 0;
	$tvalor = 0;
 
// bucle de listado

	while($row=mysql_fetch_array($rs)) {
	  $hhh = substr($row ['cta_contab'],0,17); 
	  if ($hhh == $row1['codigoact']) {
	  //	echo "<tr>";
		//echo "<td class='centro'>";
		//echo $row['nidentif']."</a></td>";
		//echo "<td class='centro'>";
		//echo $hhh."</a></td>";
		//echo "<td class='centro'>";
		//echo $row['descrip']."</a></td>";
		//echo "<td class='centro'>";
		//echo $row['fechax']."</a></td>";
		//echo "<td class='dcha'>";
		$tcosto = $tcosto + $row['costo']; 
		//echo number_format ($row['costo'],2,'.',',')."</a></td>";
		//echo "<td class='centro'>";
		//echo '1'."</a></td>";
		//echo "<td class='dcha'>";
		//echo number_format ($vida,2,'.',',')."</a></td>";
		//echo "<td class='dcha'>";
		if ($row['depacfecha'] >= $row['costo']){
$dep= $row['depacfecha'] + '0'; 
$tdepacum = $tdepacum + $dep; }
else {		 
$dep= $row['depacfecha']+$row['depmensual'];
$tdepacum = $tdepacum + $dep; 
}
		//echo number_format ($dep,2,'.',',')."</a></td>";
		//echo "<td class='dcha'>";
		if ($row['costo'] == $dep){
		$tdep= $tdep + '0,00';
		//echo '0,00'."</a></td>";
	    //echo "<td class='dcha'>";
		$tvalor= $tvalor + '0,00';
		//echo '0,00'."</td>";
		//echo "<td class='dcha'>";
		//echo $fecha."</td>";
		//cho "</tr>";
		}
		else if ($row['costo'] <> $dep) {
		$tdep= $tdep + $row['depmensual'];
		//echo number_format ($row['depmensual'],2,'.',',')."</a></td>";
	    //echo "<td class='dcha'>";
		$valor = $row['costo'] - $dep; 
		$tvalor= $tvalor + $valor;
		//echo number_format($valor,2,'.',',')."</td>";
		//echo "<td class='dcha'>";
		//echo $fecha."</td>";
		//echo "</tr>";	
		}
			$sql="UPDATE sgcaf610 SET valoract ='$valor', depacfecha ='$dep', ultima_dep='$fechamysql' WHERE nidentif = '".$row['nidentif']."'";
    //echo $sql;
	mysql_query($sql) or die ("<p />El usuario $usuario no tiene permisos para modificar Clientes <br>".mysql_error()."<br>".$sql);
		}
		
}
 //echo '<th colspan= "4" class = "b dcha" >Subtotal para '.$row1['codigoact'].'</th><th class="dcha">'.number_format ($tcosto,2,'.',',').'</th><th></th><th></th><th class="dcha">'.number_format ($tdepacum,2,'.',',').'</th><th class="dcha">'.number_format ($tdep,2,'.',',').'</th><th class="dcha">'.number_format ($tvalor,2,'.',',').'</th><th></th><tr>';
 //echo "</table>"; 
$cuenta1= $row1 ['cta_egreso']; 
$cuenta2 = $row1 ['cta_deprec']; 
$debe = $tdep;
$haber = 0; 
 agregar_f820($codigo, $fechamysql, '+', $cuenta1, 'DEPRECIACIÓN DE ACTIVOS FIJOS A LA FECHA'  .$fecha, $debe, $haber, 0,$ip,0,'','','S',0);
 echo 'hola'; 
  agregar_f820($codigo, $fechamysql, '-', $cuenta2, 'DEPRECIACIÓN DE ACTIVOS FIJOS A LA FECHA'  .$fecha, $debe, $haber, 0,$ip,0,'','','S',0);
$ttcosto = $ttcosto + $tcosto; 
$ttdepacum = $ttdepacum + $tdepacum; 
$ttdep = $ttdep + $tdep;
$ttvalor = $ttvalor + $tvalor;
$jj= $row1 ['descripact']; 
}
}
$sql="UPDATE sgcaf600 SET fechasdep  ='$fechamysql' WHERE clasesdep = 'A'";
    //echo $sql;
	mysql_query($sql) or die ("<p />El usuario $usuario no tiene permisos para modificar Clientes <br>".mysql_error()."<br>".$sql);
// echo '<th colspan= "4" class = "b dcha" >TOTAL</th><th class="dcha">'.number_format ($ttcosto,2,'.',',').'</th><th></th><th></th><th class="dcha">'.number_format ($ttdepacum,2,'.',',').'</th><th class="dcha">'.number_format ($ttdep,2,'.',',').'</th><th class="dcha">'.number_format ($ttvalor,2,'.',',').'</th><th></th><tr>';		
 }
 echo '<h2>SE HA MODIFICADO SATISFACTORIAMENTE LA BASE DE DATOS</h2>';
	}
	else if ($S == '2'){echo '<h2>NO SE HA MODIFICADO LA BASE DE DATOS</h2>';}
?>
</p>