<?php
include("head.php");
include("paginar.php");
//include("popcalendario/escribe_formulario.php");
if (!$link OR !$_SESSION['empresa']) {
	include("noempresa.php");
	exit;
}
if ($accionIn == 'Anadir') 
	$onload="onload=\"foco('cta')\""; 
else
	$onload="onload=\"foco('nactivo')\"";
?>

<body <?php if (!$bloqueo) {echo $onload;}?>>

<script src="ajxconc.js" type="text/javascript"></script>
<?php
 
$readonly=" readonly='readonly'";
include("arriba.php");
$menu61=1;include("menusizda.php");
$cta = $_GET['cta'];
$nactivo=$_GET['nactivo'];
$ip = $_SERVER['HTTP_CLIENT_IP'];
if (!$ip) {$ip = $_SERVER['REMOTE_ADDR'];}
?>
<?php
if ($accionIn=="Verificar") 
{
   // echo '<div id="div1">';
   $sql= "SELECT nombre_ban,date_format(fecha_conc, '%d/%m/%Y') AS fecha, cod_banco, cue_banco FROM sgcaf843 where nro_cta_ba='$codigo' and emitircheque='1'"; 
//   echo $sql.'<br>';
	$result=mysql_query($sql);
	$b= mysql_fetch_assoc($result);
	$nombre=$b['nombre_ban']; 
	$fechadep= $b['fecha']; 
	$cod_banco= $b['cod_banco'];
	$cue_banco=$b['cue_banco'];  
	$h= '35';
	$d = suma_fechas($fechadep,$h); 
	$a=explode("/", $d);
	$b = $a[0]."-".$a[1]."-".$a[2]; 
	$a[0] = '01'; 
	$j= $a[0]."-".$a[1]."-".$a[2]; 
	$hh = date($a[0]."-".$a[1]."-".$a[2]); 
	$hu= '1'; 
	$fecha = restar_fechas($hh,$hu); 
	$aa=explode("/", $fecha);
	$bb = $aa[0]."".$aa[1]."".$aa[2];
		$hoy = date("d/m/Y"); 
		$ho=explode("/", $hoy);
		$bbb = $ho[0]."-".$ho[1]."-".$ho[2]; 
$MiTimesTamp = mktime(0,0,0,$ho[1],$ho[0], $ho[2]);  
$MiTimesTamp1 = mktime(0,0,0,$aa[1],$aa[0],$aa[2]);     
	if ($MiTimesTamp<$MiTimesTamp1) 
	{
	echo '<h2> NO SE PUEDE CONCILIAR ESTE BANCO. TODAVIA NO HA TERMINADO EL MES </h2>'; 
	$accionIn='';  
	}
	else if ($MiTimesTamp>=$MiTimesTamp1)
	{
	 echo "<form action='conciliacion.php?accionIn=Verificar1' name='form1' method='post' onsubmit='return conciliacion(form1)'>"; 
	$fecha1= convertir_fecha($fecha);
	$saldo=buscar_saldo_f810($cue_banco,$fecha1);
//    echo $cod_banco; 
	pantalla_verificar($result,$accionIn,$codigo,$nombre,$fecha,$cod_banco,$saldo,$fecha1,$cue_banco);
	echo "<input type = 'submit' value = 'Enviar'>";
	}
}


if ($accionIn=="Verificar1") 
{
   //echo $fecha1; 
   //echo $cod_banco; 
   //echo $saldo_bancos;
   //echo $saldo_libros; 
   echo $diferencia; 
   echo $diferenciacon; 
   //echo $cheques; 
   //echo $depositos; 
  // echo $cue_banco;

 ///////////////////////MODIFICAR 840 PARA LOS CHEQUES COBRADOS//////////////
   for ($i=1;$i<$registros;$i++)
	{
		$variable='cancelar'.($i);
		if (!empty($$variable)) 
		{
	    $numero=$$variable; 
		$sql="UPDATE sgcaf840 SET cobrados='1', fecha_cobrados='$fecha1'
		WHERE mche_orden='$numero' and mche_banco='$cod_banco'";
	echo $sql;
	echo "<p />";
		mysql_query($sql)or die ("<p />El usuario $usuario no tiene permisos para añadir Clientes o cuenta ya existente.<br>".mysql_error()."<br>".$sql);
		}	
	} 
	////////////////////////MODIFICAR 840 PARA LOS CHEQUES EN TRANSITO//////////
    $sql= "SELECT *,date_format(mche_fecha, '%d/%m/%Y') AS fechax  FROM sgcaf840, sgcaf843 where nro_cta_ba='$codigo' and cod_banco=mche_banco and mche_fecha <= '$fecha1' and cobrados='0' and emitircheque='1' and mche_statu='I' order by mche_fecha"; 
	$result=mysql_query($sql);
	echo $sql; 
    while ($fila2 = mysql_fetch_assoc($result)) 
		{
	    $numero=$fila2['mche_orden']; 
		$cod_ban=$fila2['cod_banco']; 
		//echo $cod_banco; 
		$sql="UPDATE sgcaf840 SET  fecha_cobrados='$fecha1'
		WHERE mche_orden='$numero' and mche_banco='$cod_ban' ";
		echo $sql;
		echo "<p />";
		mysql_query($sql)or die ("<p />El usuario $usuario no tiene permisos para añadir Clientes o cuenta ya existente.<br>".mysql_error()."<br>".$sql);
		    }
//////////////////////////////
//////////////////////MODIFICAR 820 PARA LOS DEPOSITOS COBRADOS//////////////
   for ($i=1;$i<$j;$i++)
	{
		$variable='cancelard'.($i);
		if (!empty($$variable)) 
		{
	    $numero=$$variable; 
		$sql="UPDATE sgcaf820 SET cobrado='1', fecha_cobro='$fecha1'
		WHERE com_refere='$numero' and com_cuenta='$cue_banco'";
		echo $sql;
		echo "<p />";
		mysql_query($sql)or die ("<p />El usuario $usuario no tiene permisos para añadir Clientes o cuenta ya existente.<br>".mysql_error()."<br>".$sql);
		}	
	}
	//////////////////////////MODIFICAR 820 PARA LOS DEPOSITOS EN TRANSITO//////////
     $sql= "SELECT *,date_format(com_fecha, '%d/%m/%Y') AS fechax  FROM sgcaf820, sgcaf843 where nro_cta_ba='$codigo' and cue_banco=com_cuenta and cobrado='0' and com_fecha <= '$fecha1' and emitircheque='1' order by com_fecha"; 
	$result=mysql_query($sql);
	echo $sql; 
    while ($fila2 = mysql_fetch_assoc($result)) 
			{
		$numero=$fila2['com_refere']; 
		$cue_banco=$fila2['com_cuenta'];
		$sql="UPDATE sgcaf820 SET fecha_cobro='$fecha1'
		WHERE com_refere='$numero' and com_cuenta='$cue_banco'";
		echo $sql;
		echo "<p />";
		mysql_query($sql)or die ("<p />El usuario $usuario no tiene permisos para añadir Clientes o cuenta ya existente.<br>".mysql_error()."<br>".$sql);
		    }
	
	/////////////////////////////////////////////////INSERTAR EN LA 848 LA INFORMACION DE LA CONCILIACION////	
	$sql="INSERT INTO sgcaf848(cuent_banco,fecha_concil,saldo_bancos,saldo_libros,dif_con, monto_ch_t, monto_de_t, ip) 
	VALUES ('$codigo','$fecha1','$saldo_bancos','$saldo_libros','$diferenciacon', '$cheques', '$depositos','$ip')";
	echo $sql;
	echo "<p />";
	mysql_query($sql)or die ("<p />El usuario $usuario no tiene permisos para añadir Clientes o cuenta ya existente.<br>".mysql_error()."<br>".$sql);
	///////////////////////////////MODIFICAR 843 EN FECHA DE CONCILIACION///////////////////////////////
		$sql="UPDATE sgcaf843 SET fecha_conc='$fecha1'
		WHERE nro_cta_ba='$codigo'";
		echo $sql;
		echo "<p />";
		mysql_query($sql)or die ("<p />El usuario $usuario no tiene permisos para añadir Clientes o cuenta ya existente.<br>".mysql_error()."<br>".$sql);
 ?>
<html>
<head>
<title></title>

<script language="JavaScript">
var codigo='<? echo $codigo;?>'
var fecha='<? echo $fecha1;?>'
//checkDoubleConfirmation(); 
//alert ('hola'); 
  mi_ventana = window.open("conciliacion_pdf.php?codigo=" + codigo + "&fecha=" + fecha, "","width=1200,height=500,left=5,top=135,scrollbar=no,menubar=no,statusbar=no,status=no,resizable=YES,location=NO,toolbar=NO,personalbar=NO") 
</script>
</head> 
<body>
</body>
</html>  
   
<?php 

echo '<h2>SE HA REALIZADO EXITOSAMENTE LA CONCILIACION DE '.$fecha.' </h2>'; 
	$accionIn=''; 
}


if (!$accionIn) 
{
	echo '<div id="div1">';
 	echo "<form action='conciliacion.php?accionIn=Verificar' name='form1' method='post' onsubmit='return explicacion_cheque(form1)'>";
    $sql="SELECT * FROM sgcaf845, sgcaf843 where nro_ban=nro_cta_ba group by nro_cta_ba";
	$result=mysql_query($sql);
//	echo $sql; 
    pantalla_act_banco($result,$accionIn);
	echo "<input type = 'submit' value = 'Enviar'>";
	echo '</form>';
	echo '</div>';
	echo '<div id="div1">';
	echo "<table class='basica 100 hover' width='100%'><tr>";
	echo '<th><a href=?ord=cuent_banco, fecha_concil>Banco</th><th>Nro.de Cuenta';
	echo '</th><th><a href=?ord=fecha_concil>Fecha</th><th></th>';
    echo "<tr>";
		$ord = $_GET['ord'];
	if (!$ord) $ord='cuent_banco, fecha_concil';
	
		$conta = $_GET['conta'];
	if (!$_GET['conta']) {
		$conta = 1;
	}
	$sql = "SELECT COUNT(fecha_concil) AS cuantos FROM sgcaf848";
	$rs = mysql_query($sql);
	$row= mysql_fetch_array($rs);
	$numasi = $row[cuantos]; 
	
	
	 $sql="SELECT *, date_format(fecha_concil, '%d/%m/%Y') AS fecha FROM sgcaf848, sgcaf843 where cuent_banco= nro_cta_ba and emitircheque='1' and estado='1' order by $ord DESC"." LIMIT ".($conta-1).", 20";
	 $resultado=mysql_query($sql); 

	if (pagina($numasi, $conta, 20, "Conciliaciones", $ord)) {$fin = 1;}

// bucle de listado
//			 echo $sql;  
			 while($row=mysql_fetch_array($resultado)) 
	         		{  
					echo "<td class='centro'>";
					echo $row['nombre_ban']."</a></td>";
					echo "<td class='centro'>";
					echo $row['cuent_banco']."</a></td>";
					echo "<td class='centro'>";
					echo $row['fecha']."</a></td>";
					echo "<td><a target=\"_blank\" href='conciliacion_pdf.php?codigo=".$row['cuent_banco']."&fecha=".$row['fecha_concil']."'><img src='imagenes/animadas/printingjob_md_wht.gif' width='16' height='16' border='0' title='Imprimir'  alt='Imprimir' /></a></td>";
				   	echo "</tr>";
					}
					echo "</table>";

	pagina($numasi, $conta, 20, "Conciliaciones", $ord);
	echo '</div>';
}   


?>

<?php
function pantalla_act_banco($result,$accionIn) {
$deci=$_SESSION['deci'];
$sep_decimal=$_SESSION['sep_decimal'];
$sep_miles=$_SESSION['sep_miles'];
//echo $sql; 
if ($accionIn == '!$accionIn') { $lectura = 'readonly = "readonly"'; $activada="disabled" ; } else {$lectura=''; $activada='';}
?>
  	 <fieldset><legend>DATOS PARA LA CONCILIACION BANCARIA</legend>
  	<table width="270" border="3">
     <td class= "blanco b" width="50" bgcolor='#FFFFCC'>Banco<td class='rojo'>
	 	<?php
			$codigo=$fila['nro_cta_ba'];
			echo '<select name="codigo" size="1">';
			while ($fila2 = mysql_fetch_assoc($result)) 
			{
			echo '<option value="'.$fila2['nro_cta_ba'].'" '.(($banco==$fila2['nro_cta_ba'])?'selected':'').'>'.$fila2['nombre_ban'].''.$fila2['nro_cta_ba'].'</option>';
		    }
			
	 	echo '</select> '; 
	    ?> *</td><tr>
</table>
 	&nbsp;</td></tr> 

<?php 
}
?>

<?php
function pantalla_verificar($result,$accionIn,$codigo,$nombre,$fecha,$cod_banco,$saldo,$fecha1,$cue_banco) {
$deci=$_SESSION['deci'];
$sep_decimal=$_SESSION['sep_decimal'];
$sep_miles=$_SESSION['sep_miles'];
//echo $sql; 
if ($accionIn == 'Verificar') { $lectura = 'readonly = "readonly"'; $activada="disabled" ; } else {$lectura=''; $activada='';}
?>
  	 <fieldset><legend>DATOS PARA LA CONCILIACION BANCARIA del Banco <?php echo $nombre ?></legend>
  	<table width="430" border="3">
    <td class= "blanco b" width="85" bgcolor="#FFFFCC">Nro. de Cuenta<td class='rojo' colspan='3' width="80" >
	<input name="codigo" type="text" id="codigo" value="<?php echo $codigo ?>" <?php echo $lectura; ?>size="20" maxlength="20" />*</td>
	   <td class= "blanco b" width="86" bgcolor="#FFFFCC">Fecha<td class='rojo' width="100" >
	<input name="fecha" type="text" id="fecha" value="<?php echo $fecha ?>" <?php echo $lectura; ?>size="10" maxlength="10" />*</td><tr>
	    <td class= "blanco b" width="85" bgcolor="#FFFFCC">Saldo s/ Libros<td class='rojo' colspan='3' width="80" >
	<input name="saldo_libros" type="text" id="saldo_libros" value="<?php echo $saldo; ?>" <?php echo $lectura; ?>size="20" maxlength="20" />*</td>
	   <td class= "blanco b" width="86" bgcolor="#FFFFCC">Saldo s/ Bancos<td class='rojo' width="100" >
    <?php 
		echo '<input type="hidden" id="saldo_libros" name="saldo_libros" value='.$saldo .' >';
		echo '<input type="textbox" maxlength="16" size="16" id="saldo_bancos" name="saldo_bancos" style="text-align:right" value=';
			echo '0.00 ';
			echo 'onBlur="calcular()" ';
			echo ' >*</td>';
			echo '</tr>' ;
		////////////////////////////////////////
			 $sql= "SELECT SUM(mche_monto) as total FROM sgcaf840, sgcaf843 where nro_cta_ba='$codigo' and cod_banco=mche_banco and mche_fecha<='$fecha1' and mche_fecha <= '$fecha1' and cobrados='0' and emitircheque='1' and mche_statu='I' order by mche_orden"; 
	$result=mysql_query($sql);
	$row= mysql_fetch_assoc($result);
	$totalche=$row['total']; 
	//////////////////////////////////////////////
	 $sql= "SELECT SUM(com_monto1) as total FROM sgcaf820, sgcaf843 where nro_cta_ba='$codigo' and cue_banco=com_cuenta and cobrado='0' and com_fecha <= '$fecha1' and emitircheque='1'"; 
	$result=mysql_query($sql);
	$row1= mysql_fetch_assoc($result);
	$totaldep=$row1['total']; 
	///////////////////////////////////////
	echo "<table class='basica 100 hover' width='100%'>"; 
	echo '<th width="230">Diferencia </th>';
	$diferencia=$saldo; 
	echo '<td class="dcha negro b" width=""><input name="diferencia" type="text" id="diferencia" readonly="readonly"  value=" '.number_format($diferencia,2,".","").'" style="text-align:right" size="20" maxlength="20" /></td><tr>'; 
	echo '<th colspan="2">Explicación de la Diferencia </th><tr>';
	echo '<td class="izq negro b" width="60" >Cheques en Tránsito   
	</td>' .'<td class="dcha negro b" width=""><input name="cheques" type="text" id="cheques" readonly="readonly"  value=" '.number_format($totalche,2,".","").'" style="text-align:right" size="20" maxlength="20" /></td><tr>';
	echo '<td class="izq negro b" width="60" >Depósitos en Tránsito   
	</td>' .'<td class="dcha negro b" width=""><input name="depositos" type="text" id="depositos" readonly="readonly" value=" '.number_format($totaldep,2,".","").'"  style="text-align:right" size="20" maxlength="20" /></td><tr>';
	echo '<th width="230">Diferencia Conciliada </th>';
	$diferenciacon=$totalche+$totaldep;
	echo '<td class="dcha negro b" width=""><input name="diferenciacon" type="text" id="diferenciacon" readonly="readonly"  value=" '.number_format($diferenciacon,2,".","").'" style="text-align:right" size="20" maxlength="20" /></td><tr>'; 	
		
	//////////////////////////////LISTADOS DE CHEQUES EMITIDOS///////////////////////
	 $sql= "SELECT *,date_format(mche_fecha, '%d/%m/%Y') AS fechax  FROM sgcaf840, sgcaf843 where nro_cta_ba='$codigo' and cod_banco=mche_banco and mche_fecha <= '$fecha1' and cobrados='0' and emitircheque='1' and mche_statu='I' order by mche_fecha, mche_orden"; 
	$result=mysql_query($sql);
//	echo $sql; 
	if (mysql_num_rows($result) <> 0) {
	echo "<table class='basica 100 hover' width='100%'>"; 
	echo '<th colspan="5">Lista de Cheques Emitidos</th><tr>';
	echo '<th>Fecha</th><th>Número</th><th>Beneficiario</th><th>Monto</th><th>Cobrado</th></tr>';
	$registros=1;
	while($row1=mysql_fetch_array($result)) 
	{
/*
	echo '<td class="centro negro b" width="10" >'.$row1['fechax'].' 
	</td>' .'<td  class="centro negro b" width="50">'.$row1['mche_orden'].' </td>
	</td>' .'<td  class="izq negro b" width="190">'.$row1['mche_nombr'].' </td><td  class="negro b dcha" width="30"> '.number_format($row1['mche_monto'],2,'.',',').'</td>'; 
*/
		echo '<td class="centro negro" width="10" >'.$row1['fechax'];
		echo '</td>' .'<td  class="centro negro" width="50">'.$row1['mche_orden'];
		echo ' </td></td>' .'<td  class="izq negro" width="290">';
		echo $row1['mche_nombr'];
		echo ' </td><td  class="negro dcha" width="30"> ';
		echo number_format($row1['mche_monto'],2,'.',',').'</td>'; 
	
		echo '<td class="centro azul"><input type="checkbox" id="cancelar'.$registros.'" name="cancelar'.$registros.'" value='.$row1["mche_orden"] .' onClick="activar('.$totalche.')" ';
			if ($saldo <= 0) echo '0 disabled="true" ';  // checked 
			else echo '1 >';
			echo '</td>';
//			echo '<input type="hidden" id="cancelarh'.$registros.'" name="cancelarh'.$registros.'" value="'.number_format($row1['mche_monto'],2,".","").'">';
			echo '<input type="hidden" id="cancelarh'.$registros.'" name="cancelarh'.$registros.'" value="'.round($row1['mche_monto'],2).'">';
			echo '></td></tr>';	
		$registros++; 		
}
/*
*/
		echo '<td class="centro negro" width="10" >'.$row1['fechax'];
		echo '</td>' .'<td  class="centro negro" width="50">'.'ninguno';
		echo ' </td></td>' .'<td  class="izq negro" width="290">';
		echo $row1['mche_nombr'];
		echo ' </td><td  class="negro dcha" width="30"> ';
		echo number_format(0,2,'.',',').'</td>'; 
	
		echo '<td class="centro azul"><input type="checkbox" id="cancelar'.$registros.'" name="cancelar'.$registros.'" value=ninguno onClick="activar('.$totalche.')" ';
			if ($saldo <= 0) echo '0 disabled="true" ';  // checked 
			else echo '1 >';
			echo '</td>';
//			echo '<input type="hidden" id="cancelarh'.$registros.'" name="cancelarh'.$registros.'" value="'.number_format($row1['mche_monto'],2,".","").'">';

			echo '<input type="hidden" id="cancelarh'.$registros.'" name="cancelarh'.$registros.'" value="'.round($row1['mche_monto'],2).'">';
			echo '></td></tr>';	
		$registros++; 		

echo "<input type = 'hidden' value ='".$registros."' name='registros' id='registros'>";
	}

 //////////////////////////////LISTADOS DE DEPOSITOS///////////////////////
	 $sql= "SELECT *,date_format(com_fecha, '%d/%m/%Y') AS fechax  FROM sgcaf820, sgcaf843 where nro_cta_ba='$codigo' and cue_banco=com_cuenta and cobrado='0' and com_fecha <= '$fecha1' and emitircheque='1' order by com_fecha"; 
	$result=mysql_query($sql);
//	echo $sql; 
	if (mysql_num_rows($result) <> 0) {
	echo "<table class='basica 100 hover' width='100%'>"; 
	echo '<th colspan="5">Lista de Depósitos</th><tr>';
	echo '<th>Fecha</th><th>Número</th><th>Nombre</th><th>Monto</th><th></th></tr>';
	$j=1;
	while($row1=mysql_fetch_array($result)) 
	{
	echo '<td class="centro negro b" width="10" >'.$row1['fechax'].' 
	</td>' .'<td  class="centro negro b" width="50">'.$row1['com_refere'].' </td>
	</td>' .'<td  class="izq negro b" width="190">'.$row1['com_descri'].' </td><td  class="negro b dcha" width="30"> '.number_format($row1['com_monto1'],2,'.',',').'</td>'; 
	
	echo '<td class="centro azul"><input type="checkbox" id="cancelard'.$j.'" name="cancelard'.$j.'" value='.$row1["com_refere"] .' onClick="activard('.$totaldep.')" ';
			if ($saldo <= 0) echo '0.00 disabled="true" ';  // checked 
			echo '></td>';
			echo '<input type="hidden" id="cancelarhd'.$j.'" name="cancelarhd'.$j.'" value="'.number_format($row1['com_monto1'],2,".","").'">';
			echo '></td></tr>';	
	$j++; 	
	}
	echo "<input type = 'hidden' value ='".$j."' name='j' id='j'>";	
}

/////////////////////////////////////////////////////
	?>
	
	<input type="hidden" name="cue_banco" value="<?php echo $cue_banco;?>">
	<input type="hidden" name="cod_banco" value="<?php echo $cod_banco;?>">
	<input type="hidden" name="fecha1" value="<?php echo $fecha1;?>">
</table>
 	&nbsp;</td></tr> 

<?php 
}
?>
<?php
function buscar_saldo_f810($cuenta, $fecha1)
{
	$sql_f810="select cue_saldo from sgcaf810 where cue_codigo='$cuenta'";
//	echo $sql_f810;
	$lacuentas=mysql_query($sql_f810); //  or die ("<p />El usuario $usuario no pudo conseguir el saldo contable<br>".mysql_error()."<br>".$sql);
	$lacuenta=mysql_fetch_assoc($lacuentas);
	$saldoinicial=$lacuenta['cue_saldo'];
	
	$sql_f820="select com_monto1, com_monto2 from sgcaf820 where (com_cuenta='$cuenta') and (com_fecha <= '$fecha1') order by com_fecha";
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
?>