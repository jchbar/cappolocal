<?php
include("head.php");
include("paginar.php");
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
if ($accionIn=="Anular_Cheque") {  	
   // echo '<div id="div1">';
	echo "<form action='cheact.php?accionIn=Anular_Cheque1' name='form1' method='post' onsubmit='return explicacion_cheque(form1)'>";
	$sql= "SELECT *FROM sgcaf843";
	$result=mysql_query($sql);
    pantalla_act_anular($result,$accionIn,$codigo,$numero,$nombre);
	echo "<input type = 'submit' value = 'Anular'>";
	//echo '</div>';
}

if ($accionIn=="Borrar_Cheque") {
   // echo '<div id="div1">';
	echo "<form action='cheact.php?accionIn=Borrar_Cheque1' name='form1' method='post' onsubmit='return explicacion_cheque(form1)'>";
	$sql= "SELECT *FROM sgcaf843";
	$result=mysql_query($sql);
    pantalla_act_borrar($result,$accionIn,$codigo,$numero,$nombre);
	echo "<input type = 'submit' value = 'Eliminar'>";
	//echo '</div>';
}

if ($accionIn == "EditarMo") { 
	$com_fechamysql=convertir_fecha($fecha);
	$sql="UPDATE sgcaf840 SET mche_nombr='$beneficiario', mche_fecha='$com_fechamysql', mche_descr='$observacion' WHERE mche_orden='$numero' and mche_banco='$cod_banco'";
//    echo $sql;
	mysql_query($sql) or die ("<p />El usuario $usuario no tiene permisos para modificar Clientes <br>".mysql_error()."<br>".$sql);
	
	$sql="UPDATE sgcaf846 SET descrip='$observacion', ip='$ip', fecha='$com_fechamysql'
				WHERE nro_che='$numero' and banco='$codigo'";
//				echo $sql;
	mysql_query($sql)or die ("<p />El usuario $usuario no tiene permisos para añadir Clientes o cuenta ya existente.<br>".mysql_error()."<br>".$sql);	
	$cuenta="select cue_banco from sgcaf843 where cod_banco = '$cod_banco'";
	$rcuenta=mysql_query($cuenta);
	$fcuenta=mysql_fetch_assoc($rcuenta);
	$cuenta=$fcuenta['cue_banco'];
	$sql="update sgcaf841 set mche_descr = '$beneficiario' WHERE mche_orden ='$numero' and mche_cuent = '$cuenta'";
	$rcuenta=mysql_query($sql);
//	echo $sql;
	
	$accionIn='';
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if ($accionIn == 'Anular_Cheque1')
 {   //echo '<div id="div1">';
    $hoy = date("d/m/Y");
	$fecha=convertir_fecha($hoy); 
	echo '1'; 
	$sql="SELECT * FROM sgcaf840, sgcaf843 where mche_orden='$numero' and nro_cta_ba='$codigo' and cod_banco=mche_banco and mche_statu='I' and estado='1' and emitircheque='1' "; 
	echo "<p />";
	$result=mysql_query($sql); 
	$b= mysql_fetch_assoc($result);
	$nombre= $b['nombre_ban']; 
	$mche_nombr = $b['mche_nombr']; 
	$mche_monto= $b['mche_monto']; 
	$mche_descr= $b['mche_descr'];
	$mche_statu= $b['mche_statu']; 
	$mche_banco= $b['mche_banco']; 
	$mche_prest= $b['mche_prest']; 
	$mche_fecha= $b['mche_fecha'];
	$verificado= $b['verificado']; 
	$fecha_verific= $b['fecha_verific'];
	$ip_ver= $b['ip_ver']; 
	echo $sql; 
	echo'2'; 
    $sql="INSERT INTO sgcaf840E(mche_orden,mche_fecha,mche_nombr,mche_monto,mche_descr,mche_statu,mche_banco,mche_prest, ip, mche_observacion, fecha_borrar, verificado, fecha_verific, ip_ver) 
	VALUES ('$numero','$mche_fecha','$mche_nombr','$mche_monto','$mche_descr','$mche_statu','$mche_banco','$mche_prest', '$ip', '$explicacion', '$fecha', '$verificado', '$fecha_verific', '$ip_ver')";
	echo $sql;
	echo "<p />";
	mysql_query($sql)or die ("<p />El usuario $usuario no tiene permisos para añadir Clientes o cuenta ya existente.<br>".mysql_error()."<br>".$sql);
	echo '3';
	$sql="UPDATE sgcaf840 SET mche_statu='A'
		WHERE mche_orden='$numero' and mche_banco='$mche_banco'";
		echo $sql;
		echo "<p />";
		mysql_query($sql)or die ("<p />El usuario $usuario no tiene permisos para añadir Clientes o cuenta ya existente.<br>".mysql_error()."<br>".$sql);
	echo '4';
	$sql="UPDATE sgcaf846 SET descrip='CHEQUE ANULADO'
		WHERE nro_che='$numero' and banco='$codigo'";
		echo $sql;
		echo "<p />";
		mysql_query($sql)or die ("<p />El usuario $usuario no tiene permisos para añadir Clientes o cuenta ya existente.<br>".mysql_error()."<br>".$sql);
		
     echo '5'; 
	$sql="SELECT * FROM sgcaf841 WHERE mche_banco = '$mche_banco' and mche_orden= '$numero'";
	$resultado1=mysql_query($sql);  
	echo $sql; 
	echo "<p />";
	while($row1=mysql_fetch_array($resultado1)) 
	{
	$mche_cuent=$row1['mche_cuent']; 
	$mche_debcr=$row1['mche_debcr']; 
	$mche_descr=$row1['mche_descr']; 
	$mche_monto1=$row1['mche_monto1']; 
	$mche_monto2=$row1['mche_monto2']; 
	$mche_monto=$row1['mche_monto']; 
	$mche_banco=$row1['mche_banco']; 
	$registro_original=$row1['registro']; 
	echo $sql; 
	echo '6'; 
	$sql="INSERT INTO sgcaf841E(mche_orden,mche_cuent,mche_debcr,mche_descr,mche_monto1,mche_monto2,mche_monto,mche_banco, registro_original) 
	VALUES ('$numero','$mche_cuent','$mche_debcr','$mche_nombr','$mche_monto1','$mche_monto2','$mche_monto','$mche_banco', '$registro_original')";
	echo $sql;
	echo "<p />";
	mysql_query($sql)or die ("<p />El usuario $usuario no tiene permisos para añadir Clientes o cuenta ya existente.<br>".mysql_error()."<br>".$sql);
	}
	$accionIn='';
	//echo '</div>';
}


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if ($accionIn == 'Borrar_Cheque1')
 {   //echo '<div id="div1">';
    $hoy = date("d/m/Y");
	$fecha=convertir_fecha($hoy);
	$sql="SELECT * FROM sgcaf840, sgcaf843 where mche_orden='$numero' and nro_cta_ba='$codigo' and cod_banco=mche_banco and emitircheque='1' and estado='1'"; 
	$result=mysql_query($sql); 
	$b= mysql_fetch_assoc($result);
	$mche_nombr= $b['mche_nombr']; 
	$mche_monto= $b['mche_monto']; 
	$mche_descr= $b['mche_descr'];
	$mche_statu= $b['mche_statu']; 
	$mche_banco= $b['mche_banco']; 
	$mche_prest= $b['mche_prest']; 
	$mche_fecha= $b['mche_fecha'];  
	$verificado= $b['verificado']; 
	$fecha_verific= $b['fecha_verific'];
	$ip_ver= $b['ip_ver']; 
	echo $sql;  
    $sql="INSERT INTO sgcaf840E(mche_orden,mche_fecha,mche_nombr,mche_monto,mche_descr,mche_statu,mche_banco,mche_prest, ip, mche_observacion, fecha_borrar, verificado, fecha_verific, ip_ver) 
	VALUES ('$numero','$mche_fecha','$mche_nombr','$mche_monto','$mche_descr','$mche_statu','$mche_banco','$mche_prest', '$ip', '$explicacion', '$fecha', '$verificado', '$fecha_verific', '$ip_ver')";
	echo $sql;
	mysql_query($sql)or die ("<p />El usuario $usuario no tiene permisos para añadir Clientes o cuenta ya existente.<br>".mysql_error()."<br>".$sql);
	mysql_query("DELETE FROM sgcaf840 WHERE mche_orden='$numero' and mche_banco='$mche_banco'") or die ("<p />El usuario $usuario no tiene permisos para borrar Cuentas");
	
	$sql="UPDATE sgcaf846 SET descrip='', ip='', fecha='', estatus='+'
		WHERE nro_che='$numero' and banco='$codigo'";
		echo $sql;
		mysql_query($sql)or die ("<p />El usuario $usuario no tiene permisos para añadir Clientes o cuenta ya existente.<br>".mysql_error()."<br>".$sql);
		
	$sql="SELECT * FROM sgcaf841 WHERE mche_banco = '$mche_banco' and mche_orden= '$numero'";
	$resultado=mysql_query($sql);  
	echo $sql; 
	while($row1=mysql_fetch_array($resultado)) 
	{
	$mche_cuent=$row1['mche_cuent']; 
	$mche_debcr=$row1['mche_debcr']; 
	$mche_descr=$row1['mche_descr']; 
	$mche_monto1=$row1['mche_monto1']; 
	$mche_monto2=$row1['mche_monto2']; 
	$mche_monto=$row1['mche_monto']; 
	$mche_banco=$row1['mche_banco']; 
	$registro_original=$row1['registro']; 
	echo $sql; 
	$sql="INSERT INTO sgcaf841E(mche_orden,mche_cuent,mche_debcr,mche_descr,mche_monto1,mche_monto2,mche_monto,mche_banco, registro_original) 
	VALUES ('$numero','$mche_cuent','$mche_debcr','$mche_descr','$mche_monto1','$mche_monto2','$mche_monto','$mche_banco', '$registro_original')";
	mysql_query($sql)or die ("<p />El usuario $usuario no tiene permisos para añadir Clientes o cuenta ya existente.<br>".mysql_error()."<br>".$sql);
	}
	mysql_query("DELETE FROM sgcaf841 WHERE mche_orden='$numero' and mche_banco='$mche_banco'") or die ("<p />El usuario $usuario no tiene permisos para borrar Cuentas");
    $accionIn='';
	//echo '</div>';
}


if ($accionIn == 'Buscar_In')  {
	extract($_POST);
	$codigo = trim($_POST['codigo']);
	$numero = trim($_POST['numero']);
	echo $numero. ' - ' .$codigo . ' - '.$status . ' - '.$accionIn;
	if ($numero and $codigo) { //  != ' ') {
  $sql="SELECT mche_orden, date_format(mche_fecha, '%d/%m/%Y') AS fecha, mche_nombr, mche_descr, mche_statu, mche_banco, mche_prest, desc_prest, nombre_ban, cue_banco, mche_monto, cod_banco FROM sgcaf840, sgcaf842, sgcaf843 where mche_orden='$numero' and codi_prest=mche_prest and mche_banco=cod_banco and nro_cta_ba ='$codigo' and emitircheque='1' and estado='1'";
//  echo $sql; 
		$result=mysql_query($sql);
		$row= mysql_fetch_assoc($result);
		  
		if (mysql_num_rows($result) > 0) {
		echo "<input type = 'hidden' value ='".$row['mche_orden']."' name='$numero'>"; 
		echo "<input type = 'hidden' value ='".$row['nro_cta_ba']."' name='$codigo'>"; 
		echo "<input type = 'hidden' value ='".$row['mche_statu']."' name='$status'>";
		$accionIn = 'Consultar'; }
		else {
		echo "<p /><h2>NO SE ENCUENTRA EL CHEQUE</ h2></div></body></html>";
		 echo '<div style="clear:both"></div>';
   		 echo '<p /><div class="noimpri" style="clear:both;text-align:center">';
   		 echo '<a href="cheact.php"><input type="button" name="boton" value="regresar" tabindex="3">'; 
		echo "<p /><br /><p /><td>"; 
		}
		}
} 
?>
<?php

if ($accionIn=="Consultar") {
   // echo '<div id="div1">';

     $sql="SELECT mche_statu, nombre_ban FROM sgcaf840, sgcaf843 where mche_orden='$numero' and mche_banco=cod_banco and nro_cta_ba ='$codigo' and emitircheque='1' and estado='1'"; 
	$rs2 = mysql_query($sql);
//	echo $sql; 
	$registros23= mysql_fetch_assoc($rs2);
		$status=$registros23['mche_statu']; 
		$nombre=$registros23['nombre_ban']; 
		//echo $cobrados; 
	if ($status=='L') 
	{ 
	echo "<a href='cheact.php?accionIn=VerificarMo0&codigo=".$codigo."&numero=".$numero."&nombre=".$nombre."'>MODIFICAR</a>";
	echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='cheact.php?accionIn=Borrar_Cheque&codigo=".$codigo."&numero=".$numero."&nombre=".$nombre."'>ELIMINAR</a>";
	}
	if ($status=='I')
	{ 
	if ($cobrados=='1')
	{
	}
	else if ($cobrados=='0') 
	{
	echo "<a href='cheact.php?accionIn=Anular_Cheque&codigo=".$codigo."&numero=".$numero."&nombre=".$nombre."'>ANULAR</a>";
    }
	}
   	else if ($status=='A') 
	{ 
    }
	$sql= "SELECT mche_orden, date_format(mche_fecha, '%d/%m/%Y') AS fecha, mche_nombr, mche_descr, mche_statu, mche_banco, mche_prest, desc_prest, nombre_ban, cue_banco, mche_monto, cod_banco FROM sgcaf840, sgcaf842, sgcaf843 where mche_orden='$numero' and codi_prest=mche_prest and mche_banco=cod_banco and nro_cta_ba ='$codigo' and emitircheque='1' and estado='1'";
	//echo $sql; 
	$result=mysql_query($sql);
	$sql= "SELECT cue_banco, cod_banco FROM sgcaf840, sgcaf843 where mche_orden='$numero' and mche_banco=cod_banco and nro_cta_ba ='$codigo' and emitircheque='1' and estado='1' ";
	
	$rs1 = mysql_query($sql);
//	echo $sql; 
	$registros22= mysql_fetch_assoc($rs1);
	$cod_cont= $registros22['cue_banco'];
	$nro= $registros22['cod_banco']; 
	pantalla_act_consultar($result,$accionIn,$codigo,$numero,$nombre);
	$sql = "SELECT * FROM sgcaf841 where mche_orden='$numero' and mche_banco='$nro' and mche_cuent<>'$cod_cont' order by mche_debcr";
	//echo $sql; 
	$rs = mysql_query($sql);
	$registros=mysql_num_rows($rs);
    if ($registros > 0) {
	echo "<p />";
//	echo $sql;
	echo "<table class='basica 100 hover' width='100%'><tr>";
	echo '<th colspan="4">ASIENTOS CONTABLES PARA ESTE CHEQUE</th><tr>'; 
	echo '<th>Cuentas</a></th><th>Descripción</a><br>';
	echo '<th>Debe</a></th><th>Haber</a><br>';
	echo '</th></th>';
	
$td='0';
$th='0'; 
	while($row=mysql_fetch_array($rs)) {
		echo "<tr>";
  		echo "<td class='centro'>";
		echo $row['mche_cuent']."</a></td>";
		echo "<td class='izq'>";
		echo $row['mche_descr']."</a></td>";
		echo "<td class='dcha'>";
		
		echo number_format($row['mche_monto1'],2,'.',',')."</a></td>";
		$td=$td+$row['mche_monto1']; 
		echo "<td class='dcha'>";
		echo number_format($row['mche_monto2'],2,'.',',')."</a></td>";
		$th=$th+$row['mche_monto2']; 
 }
	echo "<tr>";
	    $sql = "SELECT * FROM sgcaf841 where mche_orden='$numero' and mche_banco='$nro' and mche_cuent='$cod_cont' order by mche_debcr";
//		echo $sql;
	$rs1 = mysql_query($sql);
	$registros22= mysql_fetch_assoc($rs1);
	$thh=$registros22['mche_monto2'];
    	echo "<td align='center' class='b''>";
		echo $registros22['mche_cuent']."</a></td>";
		echo "<td align='left' class='b'>";
		echo $registros22['mche_descr']."</a></td>";
		echo "<td align='right' class='b'>";
		echo number_format($registros22['mche_monto1'],2,'.',',')."</a></td>";
		echo "<td align='right' class='b'>";
		echo number_format($registros22['mche_monto2'],2,'.',',')."</a></td>";
		echo "<tr>";
		if ($td == $th+$thh)
		{
		echo "<td align='right' class='b' colspan ='2'>";
		echo "Totales:</td>";
		echo "<td align='right' class='blanco b'>"; 
		echo number_format($td,2,'.',',')."</a></td>";
		echo "<td align='right' class='blanco b'>";
		echo number_format($th+$thh,2,'.',',')."</a></td>";
		}
		else if ($td <> $th+$thh)
		{
		$diferencia=number_format($td-$th+$thh,2,'.',','); 
		echo "<td align='right' class='rojo b' colspan ='2'>";
		echo "Diferencia de $diferencia  Totales: </td>";
		echo "<td align='right' class='rojo b'>"; 
		echo number_format($td,2,'.',',')."</a></td>";
		echo "<td align='right' class='rojo b'>";
		echo number_format($th+$thh,2,'.',',')."</a></td>";
		}
		
		
		echo "<tr>";
	echo "</table>";
	echo "<p />";
	//echo '</div>';
	}
	if ($verificacion=='1')
	{
	$hoy = date("Y-m-d");
	$sql="UPDATE sgcaf840 SET verificado='1', ip_ver='$ip', fecha_verific='$hoy'
				WHERE mche_orden='$numero' and mche_banco='$cod'";
//				echo $sql;
				mysql_query($sql)or die ("<p />El usuario $usuario no tiene permisos para añadir Clientes o cuenta ya existente.<br>".mysql_error()."<br>".$sql);
	echo '<div style="clear:both"></div>';
    echo '<p /><div class="noimpri" style="clear:both;text-align:center">';
    echo '<a href="che_verif.php?accionIn=Verificar&codigo='.$cod.'&nombre='.$nombre.'"><input type="button" name="boton" value="regresar" tabindex="3">';
		
	}
	if ($verificacion=='')
	{
    echo '<div style="clear:both"></div>';
    echo '<p /><div class="noimpri" style="clear:both;text-align:center">';
    echo '<a href="cheact.php"><input type="button" name="boton" value="regresar" tabindex="3">';
	}
	
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
?>

<?php 
if (!$accionIn) {
	echo "<form action='cheact.php?accionIn=Buscar_In' name='form1' method='post'>\n";
    echo 'Banco';
	echo '<select name="codigo" size="1">';
	$sql="SELECT * FROM sgcaf840, sgcaf843 where mche_banco=cod_banco group by mche_banco";
	$resultado=mysql_query($sql);
	while ($fila2 = mysql_fetch_assoc($resultado)) {
		 echo '<option value="'.$fila2['nro_cta_ba'].'" '.(($banco==$fila2['nro_cta_ba'])?'selected':'').'>'.$fila2['nombre_ban'].''.$fila2['nro_cta_ba'].'</option>';
	 }
	echo '</select> '; 	
		 
	echo 'Número del Cheque';
	echo '<input name="numero" type="text" id="numero" value=""  size="8" maxlength="8" />';
	echo "<input type = 'submit' value = 'Buscar'>";
	echo '</form>';
	echo "<table class='basica 100 hover' width='50%'><tr>";
	echo '<th colspan="3"><th>Banco</th><th>Nro.de Cheque'; // <a href=?ord=mche_banco,mche_fecha>
	echo '<th>Descripción  '; 
	echo '[ <a href="cheact.php?accionIn=Incluir"> Nuevo Cheque</a> ]</a>';
	echo '</th><th><a href=?ord=mche_fecha>Fecha</th></th>';
    echo "<tr>";
	$ord = $_GET['ord'];
	if (!$ord) $ord='mche_banco, mche_fecha';
	$conta = $_GET['conta'];
	if (!$_GET['conta']) {
		$conta = 1;
	}
	$sql = "SELECT COUNT(mche_orden) AS cuantos FROM sgcaf840";
	$rs = mysql_query($sql);
	$row= mysql_fetch_array($rs);
	$numasi = $row[cuantos]; 
	
//	$sql="SELECT *, date_format(mche_fecha, '%d/%m/%Y') AS fecha FROM sgcaf840, sgcaf843 where mche_banco= cod_banco and emitircheque='1' and estado='1' order by $ord DESC "." LIMIT ".($conta-1).", 20";
	$sql="SELECT *, date_format(mche_fecha, '%d/%m/%Y') AS fecha FROM sgcaf840, sgcaf843 where mche_banco= cod_banco and emitircheque='1' and estado='1' order by mche_fecha DESC "." LIMIT ".($conta-1).", 20";
	 $resultado=mysql_query($sql); 
// echo $sql;

	if (pagina($numasi, $conta, 20, "Cheques", $ord)) {$fin = 1;}

	while($row=mysql_fetch_array($resultado)) 
	{  
		$hoy  = date("d/m/Y"); 
		echo "<tr>"; 
		if ($row['mche_statu'] == 'L')
		{
			echo "<td><a href='cheact.php?accionIn=VerificarMo0&codigo=".$row['nro_cta_ba']."&numero=".$row['mche_orden']."&nombre=".$row['nombre_ban']."'><img src='imagenes/16-em-pencil.png' width='16' height='16' border='0' title='Editar' alt='Editar'/></a></td>";
			echo "<td><a href='cheact.php?accionIn=Borrar_Cheque&codigo=".$row['nro_cta_ba']."&numero=".$row['mche_orden']."&nombre=".$row['nombre_ban']."'><img src='imagenes/16-em-cross.png' width='16' height='16' border='0' title='Eliminar' alt='Eliminar' ()' /></a></td>";
			echo "<td><a href='cheact.php?accionIn=Consultar&codigo=".$row['nro_cta_ba']."&numero=".$row['mche_orden']."&nombre=".$row['nombre_ban']."&status=".$row['mche_statu']."&cobrados=".$row['cobrados']."'><img src='imagenes/page_user_dark.gif' width='16' height='16' border='0' title='Consultar'  alt='Consultar'/></a></td>";
//			echo "<td></td>";
		}
		if ($row['mche_statu'] == 'I')
		{
			echo "<td colspan='2'></td>";
	    	echo "<td><a href='cheact.php?accionIn=Consultar&codigo=".$row['nro_cta_ba']."&numero=".$row['mche_orden']."&nombre=".$row['nombre_ban']."&status=".$row['mche_statu']."&cobrados=".$row['cobrados']."'><img src='imagenes/page_user_dark.gif' width='16' height='16' border='0' title='Consultar' alt='Consultar'/></a></td>";
			if ($row['cobrados'] == '0')
			{
				echo "<td><a href='cheact.php?accionIn=Anular_Cheque&codigo=".$row['nro_cta_ba']."&numero=".$row['mche_orden']."&nombre=".$row['nombre_ban']."'><img src='imagenes/page_cross.gif' width='16' height='16' border='0' title='Anular' alt='Anular'/></a></td>";
			}
			else if ($row['cobrados'] == '1')
			{
			    echo "<td></td>";
			}
		}
		if ($row['mche_statu'] == 'A')
		{
	   		echo "<td colspan='2'></td>";
			echo "<td><a href='cheact.php?accionIn=Consultar&codigo=".$row['nro_cta_ba']."&numero=".$row['mche_orden']."&nombre=".$row['nombre_ban']."&status=".$row['mche_statu']."&cobrados=".$row['cobrados']."'><img src='imagenes/page_user_dark.gif' width='16' height='16' border='0' title='Consultar' alt='Consultar' /></a></td>";
//				echo "<td></td>";
		}
		echo "<td class='centro'>";
					echo $row['nombre_ban']."</a></td>";
					echo "<td class='centro'>";
					echo $row['mche_orden']."</a></td>";
					echo "<td class='centro'>";
					echo $row['mche_nombr']."</a></td>";
					echo "<td class='centro'>";
					echo $row['fecha']."</a></td>";
				   	echo "</tr>";
				}
					echo "</table>";

	pagina($numasi, $conta, 20, "Cheques", $ord);
}
?>

<?php
if ($accionIn=="Incluir") {
    //echo '<div id="div1">';
	echo "<form action=	'cheact.php?accionIn=VerificarIn' name='form1' method='post' onsubmit='return val(form1)'>";
	$sql= "SELECT * FROM sgcaf843, sgcaf845, sgcaf846 where nro_cta_ba = nro_ban and descrip ='' and estado='1' and emitircheque='1'";
//	echo $sql; 
	$result=mysql_query($sql);
	if (mysql_num_rows($result) == 0)
	{
	echo '<h2> No hay cheques disponibles </ h2>'; 
	   echo '<div style="clear:both"></div>';
    echo '<p /><div class="noimpri" style="clear:both;text-align:center">';
    echo '<a href="cheact.php"><input type="button" name="boton" value="regresar" tabindex="3">';
	}
	else {
    pantalla_act_in($result,$accionIn);
	echo "<input type = 'submit' value = 'Enviar'>";
	}
	//echo '</div>';
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if ($accionIn=="VerificarIn") {
    //echo '<div id="div1">';
	echo "<form action='cheact.php?accionIn=Verificar1In' name='form1' method='post' onsubmit='return valben(form1)'>";
	$sql= "SELECT * FROM sgcaf846 where registro='$codi' and estatus='+'";
	$result=mysql_query($sql);
	
	
	echo '<input type="hidden" name="codi" value="'.$codi.'">';
    echo '<input type="hidden" name="nombre" value="'.$nombre.'">';
/*
	echo $codi; 
	echo $nombre; 
*/
    pantalla_act_in1($result,$accionIn,$codi);
	echo "<input type = 'submit' value = 'Enviar'>";
	//echo '</div>';
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if ($accionIn == "Verificar1In") {
	echo '<div id="div1">';
	echo "<form action='cheact.php?accionIn=Verificar2In' name='form1' method='post' onsubmit='return valobs(form1)'>";
	$sql= "SELECT *FROM sgcaf842 where codi_prest='$concepto'";
		$result = mysql_query($sql) or die ('Error 200-1 <br>'.$sql.'<br>'.mysql_error());
		$result=mysql_query($sql);
	    $fila999 = mysql_fetch_assoc($result);
		$observacion= $fila999['cuen_prest']; 
		$concepto1 = $fila999['desc_prest'];
			echo '<input type="hidden" name="observacion" value="'.$observacion.'">';
			echo '<input type="hidden" name="concepto1" value="'.$concepto1.'">';
			echo '<input type="hidden" name="codigo" value="'.$codigo.'">';
			echo '<input type="hidden" name="numero" value="'.$numero.'">';
			echo '<input type="hidden" name="nombre" value="'.$nombre.'">';
			echo '<input type="hidden" name="beneficiario" value="'.$beneficiario.'">';
			echo '<input type="hidden" name="estatus" value="'.$estatus.'">';
			echo '<input type="hidden" name="concepto" value="'.$concepto.'">';
			echo '<input type="hidden" name="fecha" value="'.$fecha.'">';
			echo '<input type="hidden" name="cod_cont" value="'.$cod_cont.'">';
			echo '<input type="hidden" name="montoche" value="'.$montoche.'">';
		$sql='SELECT * FROM sgcaf843 WHERE nro_cta_ba= "'.$codigo.'" and estado="1" and emitircheque="1"';
		$result = mysql_query($sql) or die ('Error 200-1 <br>'.$sql.'<br>'.mysql_error());
    pantalla_act_in2($result,$accionIn,$codigo,$nombre,$numero,$beneficiario,$estatus,$concepto,$concepto1,$fecha,$observacion,$cod_cont, $montoche);
    		echo "<input type = 'submit' value = 'Enviar'>";
	//echo '</div>';
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if ($accionIn == "Verificar2In") {
	//echo '<div id="div1">';
	$com_fechamysql=convertir_fecha($fecha);
	$sql="INSERT INTO sgcaf840(mche_orden,mche_fecha,mche_nombr,mche_monto,mche_descr,mche_statu,mche_banco,mche_prest, cobrados, verificado) 
	VALUES ('$numero','$com_fechamysql', '$beneficiario', '0','$observacion','L', '$nro', '$concepto','0','0')";
//		echo $sql;
	mysql_query($sql)or die ("<p />El usuario $usuario no tiene permisos para añadir Clientes o cuenta ya existente.<br>".mysql_error()."<br>".$sql);
		
	$sql="UPDATE sgcaf846 SET descrip='$beneficiario', ip='$ip', fecha='$com_fechamysql', estatus='-' WHERE nro_che='$numero' and banco='$codigo'";
	mysql_query($sql)or die ("<p />El usuario $usuario no tiene permisos para añadir Clientes o cuenta ya existente.<br>".mysql_error()."<br>".$sql);
				
   	$sql= "SELECT * FROM sgcaf843 where nro_cta_ba='$codigo' and emitircheque='1' and estado='1'";
	$result = mysql_query($sql) or die ('Error 200-1 <br>'.$sql.'<br>'.mysql_error());
	$result=mysql_query($sql);
   	$fila123 = mysql_fetch_assoc($result);
	$cod_cont = $fila123['cue_banco'];
	$sql="INSERT INTO sgcaf841(mche_orden,mche_cuent,mche_debcr,mche_descr,mche_monto1,mche_monto2,mche_monto,mche_banco) 
	VALUES ('$numero', '$cod_cont', '-', '$beneficiario','0', '0', '0', '$nro')";
	mysql_query($sql)or die ("<p />El usuario $usuario no tiene permisos para añadir Clientes o cuenta ya existente.<br>".mysql_error()."<br>".$sql);
	$sql= "SELECT * FROM sgcaf841 where mche_orden='$numero' and mche_cuent='$cod_cont'";
//   	echo 'la841'.$sql; 
	$result = mysql_query($sql) or die ('Error 200-1 <br>'.$sql.'<br>'.mysql_error());
	$result=mysql_query($sql);
    $fila321= mysql_fetch_assoc($result);
	$montoche=$fila321['mche_monto']; 
	$registro=$fila321['registro']; 	
//	echo $montoche; 
	echo "<form action='cheact.php?accionIn=Anadir' name='form1' method='post' onsubmit='return valcar(form1)'>";
	echo '<input type="hidden" name="observacion" value="'.$observacion.'">';
	echo '<input type="hidden" name="concepto1" value="'.$concepto1.'">';
	echo '<input type="hidden" name="codigo" value="'.$codigo.'">';
	echo '<input type="hidden" name="numero" value="'.$numero.'">';
	echo '<input type="hidden" name="nombre" value="'.$nombre.'">';
	echo '<input type="hidden" name="beneficiario" value="'.$beneficiario.'">';
	echo '<input type="hidden" name="estatus" value="'.$estatus.'">';
	echo '<input type="hidden" name="concepto" value="'.$concepto.'">';
	echo '<input type="hidden" name="fecha" value="'.$fecha.'">';
	echo '<input type="hidden" name="cod_cont" value="'.$cod_cont.'">';
	echo '<input type="hidden" name="montoche" value="'.$montoche.'">';
	echo '<input type="hidden" name="registro" value="'.$registro.'">';
   	pantalla_act_in3($result,$accionIn,$codigo,$nombre,$numero,$beneficiario,$estatus,$concepto,$concepto1,$fecha,$observacion,$cod_cont, $montoche,$elcargo,$descripcion,$cuenta1,$elmonto,$cod,$registro);
   	echo "<input type = 'submit' value = 'Guardar Asiento'>";
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if ($accionIn == "Verificar3In") {
	//echo '<div id="div1">';
	echo "<form action='cheact.php?accionIn=Anadir' name='form1' method='post' onsubmit='return valcar(form1)'>";
	echo '<input type="hidden" name="codigo" value="'.$codigo.'">';
	echo '<input type="hidden" name="numero" value="'.$numero.'">';
	echo '<input type="hidden" name="nombre" value="'.$nombre.'">';
	echo '<input type="hidden" name="beneficiario" value="'.$beneficiario.'">';
	echo '<input type="hidden" name="estatus" value="'.$estatus.'">';
	echo '<input type="hidden" name="concepto" value="'.$concepto.'">';
	echo '<input type="hidden" name="fecha" value="'.$fecha.'">';
	echo '<input type="hidden" name="cod_cont" value="'.$cod_cont.'">';
	if ($cod_banco<>'')
	{$nro=$cod_banco; }
	echo '<input type="hidden" name="nro" value="'.$nro.'">';
	$sql= "SELECT * FROM sgcaf841 where mche_orden='$numero' and mche_cuent='$cod_cont'";
//    echo $sql; 
	$result = mysql_query($sql) or die ('Error 200-1 <br>'.$sql.'<br>'.mysql_error());
	$result=mysql_query($sql);
    $fila321= mysql_fetch_assoc($result);
	$montoche=$fila321['mche_monto']; 
	echo "<p />";	
	echo $montoche; 
	echo "<p />";
	echo '<input type="hidden" name="montoche" value="'.$montoche.'">';
    $sql= "SELECT *FROM sgcaf842 where codi_prest='$concepto'";
//    echo $sql; 
	echo "<p />";
	$result = mysql_query($sql) or die ('Error 200-1 <br>'.$sql.'<br>'.mysql_error());
	$result=mysql_query($sql);
    $fila999 = mysql_fetch_assoc($result);
	//$observacion= $fila999['cuen_prest']; 
	$concepto1 = $fila999['desc_prest'];
    //echo '<input type="hidden" name="observacion" value="'.$observacion.'">';
	echo '<input type="hidden" name="concepto1" value="'.$concepto1.'">';

	$result = mysql_query($sql) or die ('Error 200-1 <br>'.$sql.'<br>'.mysql_error());
   pantalla_act_in3($result,$accionIn,$codigo,$nombre,$numero,$beneficiario,$estatus,$concepto,$concepto1,$fecha,$observacion,$cod_cont, $montoche,$elcargo,$descripcion,$cuenta1,$elmonto,$cod,$registro);
	echo "<p />";
    echo "<input type = 'submit' value = 'Guardar Asiento'>";
	echo "<h3><a href= cheact.php>Salir</a></h3>";
	$sql = "SELECT * FROM sgcaf841 where mche_orden='$numero' and mche_banco='$nro' and mche_cuent<>'$cod_cont' order by mche_debcr";
	$rs = mysql_query($sql);
	$registros=mysql_num_rows($rs);
    if ($registros > 0) {
	echo "<p />";
//	echo $sql;
	echo "<table class='basica 100 hover' width='650'><tr>";
	echo '<th colspan="6">ASIENTOS CONTABLES PARA ESTE CHEQUE</th><tr>';
	echo '<th>  </th><th>  </th><th>Cuentas</a></th><th>Descripción</a><br>';
	echo '<th>Debe</a></th><th>Haber</a><br>';
	echo '</th></th>';
	
$td='0';
$th='0'; 
	while($row=mysql_fetch_array($rs)) 
	{
		echo "<tr>";
  	 	echo "<td><a href='cheact.php?accionIn=EditaIn&numero=$numero&cuenta1=".$row['mche_cuent']."&codigo=$codigo&registro=".$row['registro']."&cod_banco=".$row['mche_banco']."'><img src='imagenes/16-em-pencil.png' width='16' height='16' border='0' title='Editar' alt='Editar' /></a></td>";
		echo "<td><a href='cheact.php?accionIn=Anadir&a=B&numero=$numero&cuenta1=".$row['mche_cuent']."&codigo=$codigo&cod_cont=$cod_cont&registro=".$row['registro']."&cod_banco=".$row['mche_banco']."'><img src='imagenes/16-em-cross.png' width='16' height='16' border='0' title='Eliminar' alt='Eliminar' /></a></td>";
		echo "<td class='centro'>";
		echo $row['mche_cuent']."</a></td>";
		echo "<td class='izq'>";
		echo $row['mche_descr']."</a></td>";
		echo "<td class='dcha'>";
		echo number_format($row['mche_monto1'],2,'.',',')."</a></td>";
		$td=$td+$row['mche_monto1']; 
		echo "<td class='dcha'>";
		echo number_format($row['mche_monto2'],2,'.',',')."</a></td>";
		$th=$th+$row['mche_monto2']; 
	}
	echo "<tr>";
	$sql = "SELECT * FROM sgcaf841 where mche_orden='$numero' and mche_banco='$nro' and mche_cuent='$cod_cont' order by mche_debcr";
//		echo $sql;
	$rs1 = mysql_query($sql);
	$registros22= mysql_fetch_assoc($rs1);
	$thh=$registros22['mche_monto2'];
    echo "<td></td>";
	echo "<td></td>";
	echo "<td align='center' class='b''>";
	echo $registros22['mche_cuent']."</a></td>";
	echo "<td align='left' class='b'>";
	echo $registros22['mche_descr']."</a></td>";
	echo "<td align='right' class='b'>";
	echo number_format($registros22['mche_monto1'],2,'.',',')."</a></td>";
	echo "<td align='right' class='b'>";
	echo number_format($registros22['mche_monto2'],2,'.',',')."</a></td>";
	echo "<tr>";
	if ($td == $th+$thh)
	{
		echo "<td align='right' class='b' colspan ='4'>";
		echo "Totales:</td>";
		echo "<td align='right' class='blanco b'>"; 
		echo number_format($td,2,'.',',')."</a></td>";
		echo "<td align='right' class='blanco b'>";
		echo number_format($th+$thh,2,'.',',')."</a></td>";
		}
		else if ($td <> $th+$thh)
		{
		$diferencia=number_format($td-$th+$thh,2,'.',','); 
		echo "<td align='right' class='rojo b' colspan ='4'>";
		echo "Diferencia de $diferencia  Totales: </td>";
		echo "<td align='right' class='rojo b'>"; 
		echo number_format($td,2,'.',',')."</a></td>";
		echo "<td align='right' class='rojo b'>";
		echo number_format($th+$thh,2,'.',',')."</a></td>";
	}
	echo "<tr>";
	echo "</table>";
	echo "<p />";
	//echo '</div>';
	}
    echo "<p />";
	//echo '</div>';
}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
?>

<?php
if ($accionIn=='Anadir') {
    //echo '<div id="div1">';
	echo $cod; 
	if ($a<>'B') {
  if ($cod <> '1') {
	echo '1'; 
	if ($elcargo=='on') {$elcargo='+'; }
	else if ($elcargo<>'on') {$elcargo='';}
	if ($elcargo=='+')
				{	// echo '1-2'; 
				$sql="INSERT INTO sgcaf841(mche_orden,mche_cuent,mche_debcr,mche_descr,mche_monto1,mche_monto2,mche_monto,mche_banco) 
		VALUES ('$numero', '$cuenta1', '$elcargo', '$descripcion','$elmonto', '0', '$elmonto', '$nro')";
//				echo $sql;
				mysql_query($sql)or die ("<p />El usuario $usuario no tiene permisos para añadir Clientes o cuenta ya existente.<br>".mysql_error()."<br>".$sql);
				$v='1';
				}
	else if ($elcargo=='')
				{	// echo '1-3';
				$sql="INSERT INTO sgcaf841(mche_orden,mche_cuent,mche_debcr,mche_descr,mche_monto1,mche_monto2,mche_monto,mche_banco) 
		VALUES ('$numero','$cuenta1','-', '$descripcion','0','$elmonto', '$elmonto', '$nro')";
//				echo $sql;
				mysql_query($sql)or die ("<p />El usuario $usuario no tiene permisos para añadir Clientes o cuenta ya existente.<br>".mysql_error()."<br>".$sql);
				$v='1';
				}
    }
	else if ($cod=='1')
	{	 echo '2'; 
	if ($elcargo=='on') {$elcargo='+'; }
	else if ($elcargo<>'on') {$elcargo='';}
	if ($elcargo=='+')
				{	// echo '2-1'; 
			    $sql="UPDATE sgcaf841 SET mche_cuent='$cuenta1', mche_debcr='$elcargo', mche_descr='$descripcion', mche_monto1='$elmonto', mche_monto2='0', mche_monto='$elmonto'
				WHERE mche_cuent='$cuenta1' and mche_orden='$numero' and registro='$registro'";
//				echo $sql;
				mysql_query($sql)or die ("<p />El usuario $usuario no tiene permisos para añadir Clientes o cuenta ya existente.<br>".mysql_error()."<br>".$sql);
				$v='1';
				}
	else if ($elcargo=='')
				{	// echo '2-3'; 
//				echo $cod; 
				$sql="UPDATE sgcaf841 SET mche_cuent='$cuenta1', mche_debcr='-', mche_descr='$descripcion', mche_monto1='0', mche_monto2='$elmonto', mche_monto='$elmonto'
				WHERE mche_cuent='$cuenta1' and mche_orden='$numero' and registro='$registro'";
//				echo $sql;
				mysql_query($sql)or die ("<p />El usuario $usuario no tiene permisos para añadir Clientes o cuenta ya existente.<br>".mysql_error()."<br>".$sql);
				$v='1';
				}
	}	
	}		
	if ($a == 'B') {
	echo 'Hello'; 
	extract($_POST);
	echo $codigo;
	echo $cuenta1; 
	echo $numero; 
	echo $cod_cont; 
	echo $cod_banco; 
	mysql_query("DELETE FROM sgcaf841 WHERE mche_orden='$numero' and mche_cuent='$cuenta1' and registro='$registro'") or die ("<p />El usuario $usuario no tiene permisos para borrar Cuentas");
	$v = '1';
//	echo $sql; 
	$sql="SELECT *, date_format(mche_fecha, '%d/%m/%Y') AS fecha FROM sgcaf840, sgcaf843, sgcaf842 where mche_orden='$numero' and mche_banco='$cod_banco' and cod_banco= mche_banco and codi_prest= mche_prest and mche_statu='L' and emitircheque='1' and estado='1' and cobrados='0'"; 
	$result=mysql_query($sql);
    $editar1= mysql_fetch_assoc($result);
//	echo $sql; 
	$nombre=$editar1['nombre_ban']; 
	$beneficiario=$editar1['mche_nombr'];
	$estatus=$editar1['mche_statu'];
	$concepto= $editar1['mche_prest'];
	$fecha= $editar1['fecha'];
	$cod_cont= $editar1['cue_banco'];
	$elcargo= $editar['mche_debcr'];
    $descripcion= $editar['mche_descr'];
	$cod_banco= $editar1['cod_banco'];
	$nro= $editar1['cod_banco'];
	$elmonto= number_format($editar['mche_monto'],0,'','');
	echo '<input type="hidden" name="codigo" value="'.$codigo.'">';
	echo '<input type="hidden" name="numero" value="'.$numero.'">';
	echo '<input type="hidden" name="nombre" value="'.$nombre.'">';
	echo '<input type="hidden" name="beneficiario" value="'.$beneficiario.'">';
	echo '<input type="hidden" name="estatus" value="'.$estatus.'">';
	echo '<input type="hidden" name="concepto" value="'.$concepto.'">';
	echo '<input type="hidden" name="fecha" value="'.$fecha.'">';
	echo '<input type="hidden" name="cod_cont" value="'.$cod_cont.'">';
	echo '<input type="hidden" name="cod_banco" value="'.$cod_banco.'">';
	echo '<input type="hidden" name="nro" value="'.$nro.'">';
	echo '<input type="hidden" name="elcargo" value="'.$elcargo.'">';
	echo '<input type="hidden" name="descripcion" value="'.$descripcion.'">';
	echo '<input type="hidden" name="elmonto" value="'.$elmonto.'">';
	echo '<input type="hidden" name="cuenta1" value="'.$cuenta1.'">';
    }
				$tdebe = 0; 
                $thaber = 0;
                $sql="SELECT * FROM sgcaf841 WHERE mche_orden = '$numero' and mche_cuent<>'$cod_cont' and mche_banco='$nro'";
		        $resultado=mysql_query($sql);
//				echo 'la841'.$sql; 
				while ($row3 = mysql_fetch_array($resultado)) 
				{
				$tdebe = $tdebe + $row3['mche_monto1']; 
				$thaber = $thaber + $row3['mche_monto2']; 
				}
				$sql="SELECT * FROM sgcaf841 WHERE mche_orden = '$numero' and mche_cuent = '$cod_cont'";
		        $resultadoxx=mysql_query($sql);
//				echo $sql; 
				$p = mysql_fetch_assoc($resultadoxx);
				$mcheq=$p['mche_monto2']; 
				$cod_banco=$p['mche_banco']; 
/*
				echo $tdebe; 
				echo $thaber; 
*/
				$monto= $tdebe-$thaber;
				if ($monto<0) { $monto ='0';} 
				else if ($monto>0){ $monto= $tdebe-$thaber;}; 
//				echo $monto; 			
				$sql="UPDATE sgcaf840 SET mche_monto='$monto'
				WHERE mche_orden='$numero' and mche_banco='$cod_banco'";
//    			echo $sql;
				mysql_query($sql) or die ("<p />El usuario $usuario no tiene permisos para modificar Clientes <br>".mysql_error()."<br>".$sql);
				$sql="UPDATE sgcaf841 SET mche_monto2='$monto', mche_monto='$monto'
				WHERE mche_cuent='$cod_cont' and mche_orden='$numero'";
//    			echo $sql;
				mysql_query($sql) or die ("<p />El usuario $usuario no tiene permisos para modificar Clientes <br>".mysql_error()."<br>".$sql);		
	if ($v == '1') {
//	echo 'Hola';
	//echo '<div id="div1">';
	echo "<form action='cheact.php?accionIn=Verificar3In' name='form1' method='post' onsubmit='return valcar(form1)'>";
	$sql= "SELECT * FROM sgcaf841 where mche_cuent='$cod_cont' and mche_banco='$cod_banco' and mche_orden='$numero'";
//    echo $sql; 
	$result = mysql_query($sql) or die ('Error 200-1 <br>'.$sql.'<br>'.mysql_error());
	$result=mysql_query($sql);
    $fila321= mysql_fetch_assoc($result);
	$montoche=$fila321['mche_monto']; 	
	echo '<input type="hidden" name="montoche" value="'.$montoche.'">';
	echo '<input type="hidden" name="codigo" value="'.$codigo.'">';
	echo '<input type="hidden" name="numero" value="'.$numero.'">';
	echo '<input type="hidden" name="nombre" value="'.$nombre.'">';
	echo '<input type="hidden" name="beneficiario" value="'.$beneficiario.'">';
	echo '<input type="hidden" name="estatus" value="'.$estatus.'">';
	echo '<input type="hidden" name="concepto" value="'.$concepto.'">';
	echo '<input type="hidden" name="fecha" value="'.$fecha.'">';
	echo '<input type="hidden" name="cod_cont" value="'.$cod_cont.'">';
	echo '<input type="hidden" name="montoche" value="'.$montoche.'">';
	$sql= "SELECT *FROM sgcaf842 where codi_prest='$concepto'";
//	echo $sql; 
	$result = mysql_query($sql) or die ('Error 200-1 <br>'.$sql.'<br>'.mysql_error());
	$result=mysql_query($sql);
    $fila999 = mysql_fetch_assoc($result);
	//$observacion= $fila999['cuen_prest']; 
	$concepto1 = $fila999['desc_prest'];
   // echo '<input type="hidden" name="observacion" value="'.$observacion.'">';
	echo '<input type="hidden" name="concepto1" value="'.$concepto1.'">';
    
	$result = mysql_query($sql) or die ('Error 200-1 <br>'.$sql.'<br>'.mysql_error());
	
     pantalla_act_in4($result,$accionIn,$codigo,$nombre,$numero,$beneficiario,$estatus,$concepto,$concepto1,$fecha,$observacion,$cod_cont,$montoche);
	echo "<p />";
    echo "<input type = 'submit' value = 'Agregar Asiento'>";
    echo "<h3><a href= cheact.php>Salir</a></h3>";
	$sql = "SELECT * FROM sgcaf841 where mche_orden='$numero' and mche_banco='$nro' and mche_cuent<>'$cod_cont' order by mche_debcr";
//	echo $sql; 
	$rs = mysql_query($sql);
	$registros=mysql_num_rows($rs);
//	echo $numero; 
//	echo 'hola'; 
    if ($registros > 0) {
//	echo $sql;
	echo "<table class='basica 100 hover' width='650'><tr>";
	echo '<th colspan="6">ASIENTOS CONTABLES PARA ESTE CHEQUE</th><tr>';
	echo '<th>  </th><th>  </th><th>Cuentas</a></th><th>Descripción</a><br>';
	echo '<th>Debe</a></th><th>Haber</a><br>';
	echo '</th></th>';

$td='0';
$th='0'; 
	while($row=mysql_fetch_array($rs)) {
		echo "<tr>";
		echo "<td><a href='cheact.php?accionIn=EditaIn&numero=$numero&cuenta1=".$row['mche_cuent']."&codigo=$codigo&registro=".$row['registro']."&cod_banco=".$row['mche_banco']."'><img src='imagenes/16-em-pencil.png' width='16' height='16' border='0' title='Editar' alt='Editar'  /></a></td>";
		echo "<td><a href='cheact.php?accionIn=Anadir&a=B&numero=$numero&cuenta1=".$row['mche_cuent']."&codigo=$codigo&cod_cont=$cod_cont&registro=".$row['registro']."&cod_banco=".$row['mche_banco']."'><img src='imagenes/16-em-cross.png' width='16' height='16' border='0' title='Eliminar' alt='Eliminar'/></a></td>";
		echo "<td class='centro'>";
		echo $row['mche_cuent']."</a></td>";
	    echo "<td class='izq'>";
		echo $row['mche_descr']."</a></td>";
		echo "<td class='dcha'>";
		echo number_format($row['mche_monto1'],2,'.',',')."</a></td>";
		$td=$td+$row['mche_monto1']; 
		echo "<td class='dcha'>";
		echo number_format($row['mche_monto2'],2,'.',',')."</a></td>";
		$th=$th+$row['mche_monto2']; 
 }
	echo "<tr>";
	    $sql = "SELECT * FROM sgcaf841 where mche_orden='$numero' and mche_banco='$nro' and mche_cuent='$cod_cont' order by mche_debcr";
//		echo $sql;
	$rs1 = mysql_query($sql);
	$registros22= mysql_fetch_assoc($rs1);
	$thh=$registros22['mche_monto2'];
    	echo "<td></td>";
		echo "<td></td>";
		echo "<td align='center' class='b''>";
		echo $registros22['mche_cuent']."</a></td>";
		echo "<td align='left' class='b'>";
		echo $registros22['mche_descr']."</a></td>";
		echo "<td align='right' class='b'>";
		echo number_format($registros22['mche_monto1'],2,'.',',')."</a></td>";
		echo "<td align='right' class='b'>";
		echo number_format($registros22['mche_monto2'],2,'.',',')."</a></td>";
			echo "<tr>";
			if ($td == $th+$thh)
		{
		echo "<td align='right' class='b' colspan ='4'>";
		echo "Totales:</td>";
		echo "<td align='right' class='blanco b'>"; 
		echo number_format($td,2,'.',',')."</a></td>";
		echo "<td align='right' class='blanco b'>";
		echo number_format($th+$thh,2,'.',',')."</a></td>";
		}
		else if ($td <> $th+$thh)
		{
		$diferencia=number_format($td-$th+$thh,2,'.',','); 
		echo "<td align='right' class='rojo b' colspan ='4'>";
		echo "Diferencia de $diferencia  Totales: </td>";
		echo "<td align='right' class='rojo b'>"; 
		echo number_format($td,2,'.',',')."</a></td>";
		echo "<td align='right' class='rojo b'>";
		echo number_format($th+$thh,2,'.',',')."</a></td>";
		}
		echo "<tr>";
	echo "</table>";
	echo "<p />";
	//echo '</div>';
	}
	}

}
?>

<?php

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if ($accionIn == "EditaIn") { 
	//echo '<div id="div1">';
	echo $codigo; 
	echo $cuenta1; 
	echo $numero; 
    echo "<form action='cheact.php?accionIn=Anadir' name='form1' method='post' onsubmit='return valcar(form1)'>";
    $sql= "SELECT * FROM sgcaf841 where mche_orden='$numero' and mche_cuent='$cuenta1' and registro='$registro'";
	$result=mysql_query($sql);
    $editar= mysql_fetch_assoc($result);
	$cod_banco= $editar['mche_banco'];
//	echo $sql; 
	$sql="SELECT *, date_format(mche_fecha, '%d/%m/%Y') AS fecha FROM sgcaf840, sgcaf843, sgcaf842 where mche_orden='$numero' and mche_banco='$cod_banco' and cod_banco= mche_banco  and codi_prest= mche_prest and mche_statu='L' and estado='1' and emitircheque='1' and cobrados='0'"; 
	$result=mysql_query($sql);
    $editar1= mysql_fetch_assoc($result);
//	echo $sql; 
	$nombre=$editar1['nombre_ban']; 
	$beneficiario=$editar1['mche_nombr'];
	$estatus=$editar1['mche_statu'];
	$concepto= $editar1['mche_prest'];
	$fecha= $editar1['fecha'];
	$cod_cont= $editar1['cue_banco'];
	$elcargo= $editar['mche_debcr'];
    $descripcion= $editar['mche_descr'];
	$elmonto= number_format($editar['mche_monto'],0,'','');
	echo '<input type="hidden" name="codigo" value="'.$codigo.'">';
	echo '<input type="hidden" name="numero" value="'.$numero.'">';
	echo '<input type="hidden" name="nombre" value="'.$nombre.'">';
	echo '<input type="hidden" name="beneficiario" value="'.$beneficiario.'">';
	echo '<input type="hidden" name="estatus" value="'.$estatus.'">';
	echo '<input type="hidden" name="concepto" value="'.$concepto.'">';
	echo '<input type="hidden" name="fecha" value="'.$fecha.'">';
	echo '<input type="hidden" name="cod_cont" value="'.$cod_cont.'">';
	echo '<input type="hidden" name="elcargo" value="'.$elcargo.'">';
	echo '<input type="hidden" name="descripcion" value="'.$descripcion.'">';
	echo '<input type="hidden" name="elmonto" value="'.$elmonto.'">';
	echo '<input type="hidden" name="cuenta1" value="'.$cuenta1.'">';
	echo '<input type="hidden" name="registro" value="'.$registro.'">';
	$sql= "SELECT * FROM sgcaf841 where mche_orden='$numero' and mche_cuent='$cod_cont'";
//    echo $sql; 
	$result = mysql_query($sql) or die ('Error 200-1 <br>'.$sql.'<br>'.mysql_error());
	$result=mysql_query($sql);
    $fila321= mysql_fetch_assoc($result);
	$montoche=$fila321['mche_monto']; 	
	echo $montoche; 
	echo '<input type="hidden" name="montoche" value="'.$montoche.'">';
    $sql= "SELECT *FROM sgcaf842 where codi_prest='$concepto'";
//    echo $sql; 
	$result = mysql_query($sql) or die ('Error 200-1 <br>'.$sql.'<br>'.mysql_error());
	$result=mysql_query($sql);
    $fila999 = mysql_fetch_assoc($result);
	$observacion= $fila999['cuen_prest']; 
	$concepto1 = $fila999['desc_prest'];
	$cod='1'; 
	 echo '<input type="hidden" name="cod" value="'.$cod.'">';
    echo '<input type="hidden" name="observacion" value="'.$observacion.'">';
	echo '<input type="hidden" name="concepto1" value="'.$concepto1.'">';

	$result = mysql_query($sql) or die ('Error 200-1 <br>'.$sql.'<br>'.mysql_error());
    pantalla_act_in3($result,$accionIn,$codigo,$nombre,$numero,$beneficiario,$estatus,$concepto,$concepto1,$fecha,$observacion,$cod_cont,$montoche,$elcargo,$descripcion,$cuenta1,$elmonto,$cod,$registro);
	echo "<p />";
	echo $cod; 
	echo 'HOLA'; 
    echo "<input type = 'submit' value = 'Confirmar Cambios'>";
	echo "<h3><a href= cheact.php>Salir</a></h3>";
	$sql = "SELECT * FROM sgcaf841 where mche_orden='$numero' and mche_banco='$cod_banco' and mche_cuent<>'$cod_cont' order by mche_debcr";
	$rs = mysql_query($sql);
	$registros=mysql_num_rows($rs);
    if ($registros > 0) {
//	echo $sql;
	echo "<table class='basica 100 hover' width='650'><tr>";
	echo '<th colspan="6">ASIENTOS CONTABLES PARA ESTE CHEQUE</th><tr>';
	echo '<th>  </th><th>  </th><th>Cuentas</a></th><th>Descripción</a><br>';
	echo '<th>Debe</a></th><th>Haber</a><br>';
	echo '</th></th>';
	echo $numero; 
// bucle de listado
$td='0';
$th='0'; 
	while($row=mysql_fetch_array($rs)) {
		echo "<tr>";
	  	echo "<td><a href='cheact.php?accionIn=EditaIn&numero=$numero&cuenta1=".$row['mche_cuent']."&codigo=$codigo&registro=".$row['registro']."&cod_banco=".$row['mche_banco']."'><img src='imagenes/16-em-pencil.png' width='16' height='16' border='0' title='Editar' alt='Editar'  /></a></td>";
		echo "<td><a href='cheact.php?accionIn=Anadir&a=B&numero=$numero&cuenta1=".$row['mche_cuent']."&codigo=$codigo&cod_cont=$cod_cont&registro=".$row['registro']."&cod_banco=".$row['mche_banco']."'><img src='imagenes/16-em-cross.png' width='16' height='16' border='0' title='Eliminar' alt='Eliminar' /></a></td>";
		echo "<td class='centro'>";
		echo $row['mche_cuent']."</a></td>";
		echo "<td class='izq'>";
		echo $row['mche_descr']."</a></td>";
		echo "<td class='dcha'>";
		echo number_format($row['mche_monto1'],2,'.',',')."</a></td>";
		$td=$td+$row['mche_monto1']; 
		echo "<td class='dcha'>";
		echo number_format($row['mche_monto2'],2,'.',',')."</a></td>";
		$th=$th+$row['mche_monto2']; 
 }
	echo "<tr>";
	    $sql = "SELECT * FROM sgcaf841 where mche_orden='$numero' and mche_banco='$cod_banco' and mche_cuent='$cod_cont' order by mche_debcr";
//		echo $sql;
	$rs1 = mysql_query($sql);
	$registros22= mysql_fetch_assoc($rs1);
		$thh=$registros22['mche_monto2'];
    	echo "<td></td>";
		echo "<td></td>";
		echo "<td align='center' class='b''>";
		echo $registros22['mche_cuent']."</a></td>";
		echo "<td align='left' class='b'>";
		echo $registros22['mche_descr']."</a></td>";
		echo "<td align='right' class='b'>";
		echo number_format($registros22['mche_monto1'],2,'.',',')."</a></td>";
		echo "<td align='right' class='b'>";
		echo number_format($registros22['mche_monto2'],2,'.',',')."</a></td>";
			echo "<tr>";
			if ($td == $th+$thh)
		{
		echo "<td align='right' class='b' colspan ='4'>";
		echo "Totales:</td>";
		echo "<td align='right' class='blanco b'>"; 
		echo number_format($td,2,'.',',')."</a></td>";
		echo "<td align='right' class='blanco b'>";
		echo number_format($th+$thh,2,'.',',')."</a></td>";
		}
		else if ($td <> $th+$thh)
		{
		$diferencia=number_format($td-$th+$thh,2,'.',','); 
		echo "<td align='right' class='rojo b' colspan ='4'>";
		echo "Diferencia de $diferencia  Totales: </td>";
		echo "<td align='right' class='rojo b'>"; 
		echo number_format($td,2,'.',',')."</a></td>";
		echo "<td align='right' class='rojo b'>";
		echo number_format($th+$thh,2,'.',',')."</a></td>";
		}
		echo "<tr>";
	echo "</table>";
	echo "<p />";
	//echo '</div>';
	}
    echo "<p />";
	//echo '</div>';
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
?>
</body></html>
   </td>
    </tr>
</table>
</fieldset>
<?php
function pantalla_act_in ($result,$accionIn){
$deci=$_SESSION['deci'];
$sep_decimal=$_SESSION['sep_decimal'];
$sep_miles=$_SESSION['sep_miles'];
//echo $sql; 
if ($accionIn == 'VerificarIn') { $lectura = 'readonly = "readonly"'; $activada="disabled" ; } else {$lectura=''; $activada='';}
?>
  	 <label><fieldset><legend>ACTUALIZACIÓN DE CHEQUES/ Inclusión </legend>
  	<table width="270" border="3">
     <td class= "blanco b" width="50" bgcolor='#FFFFCC'>Nro. de Cuenta<td class="rojo" width="90">
	 	<?php
			$codi=$fila['nro_reg'];
			echo '<select name="codi" size="1">';
			$sql="SELECT * FROM sgcaf846, sgcaf845, sgcaf843 where registro=nro_reg and estatus='+' and nro_ban=nro_cta_ba and estado='1' and emitircheque='1' group by registro";
			$resultado=mysql_query($sql);
			while ($fila2 = mysql_fetch_assoc($resultado)) 
			 {
				echo '<option value="'.$fila2['nro_reg'].'" '.(($banco==$fila2['nro_reg'])?'selected':'').'>'.$fila2['nombre_ban'].''.$fila2['nro_cta_ba'].'</option>';
			 }
			echo '</select> '; 
	    ?> *</td><tr>
</table>
 	&nbsp;</td></tr> 

<?php 
}
?>

<?php 
function pantalla_act_in1($result,$accionIn,$codi)
{
$deci=$_SESSION['deci'];
$sep_decimal=$_SESSION['sep_decimal'];
$sep_miles=$_SESSION['sep_miles'];
//echo $sql; 
if ($accionIn == 'VerificarIn') { $lectura = 'readonly = "readonly"'; $activada="disabled" ; } else {$lectura=''; $activada='';}
 	echo '<fieldset><legend>ACTUALIZACIÓN DE CHEQUES del Banco';
	$sql= "SELECT * FROM sgcaf845, sgcaf843 where nro_reg='$codi' and nro_ban=nro_cta_ba and estado='1' and emitircheque='1'";
	$result=mysql_query($sql);
	$fila5 = mysql_fetch_assoc($result);
	echo $fila5 ['nombre_ban'];  
	$codigo=$fila5 ['nro_ban'];  
	echo '/ Inclusión</legend>';
	
  	echo '<table width="440" border="3">';
	echo '<td class= "blanco b" colspan="4" width="50" style="text-align:center" bgcolor="#FFFFCC" >Encabezado del Cheque<tr>';
	echo '<td class= "blanco b" width="50" bgcolor="#FFFFCC">Nro. de Cuenta<td class="rojo">';
	echo '<input name="codigo" type="text" id="codigo" value="'.$codigo.'" '.$lectura.' size="20" maxlength="20" />*</td>';
	echo '</td>';
	echo '<td class= "blanco b" width="50" bgcolor="#FFFFCC">Nro. de Cheque<td class="rojo">';
	echo '<input name="numero" type="text" id="numero" value="';
	$sql= "SELECT *FROM sgcaf846 where banco='$codigo' and registro='$codi' and estatus='+' and descrip='' order by nro_che";
	$result=mysql_query($sql);
	$fila99 = mysql_fetch_assoc($result);
    echo $fila99['nro_che'] .'" '.$lectura.' size="8" maxlength="8" />*</td><tr>';
	echo '<input type="hidden" name="nombre" value="'.$fila5['nombre_ban'].'">'; 
	echo '</td><tr>';
	echo '<td class= "blanco b" width="50" bgcolor="#FFFFCC">Beneficiario<td  colspan="3" class="rojo">';
	echo '<input name="beneficiario" type="text" id="beneficiario" value="';
	echo '" onChange="conMayusculas(this)" ';
	echo ' size="65" maxlength="65" />*</td><tr>';
	echo '<td class= "blanco b" width="50" bgcolor="#FFFFCC">Estatus<td>';
	echo '<input type="radio" name="estatus" value="L" checked/> Listo</label> ';
	echo '<td class= "blanco b" width="50" bgcolor="#FFFFCC">Fecha<td class="rojo">';

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
//		range          :     <?php echo $rango; ?>,


		dateStatusFunc :    function (date) { // disable weekend days (Saturdays == 6 and Subdays == 0)
						var today = new Date();
						return (
							  (date.getTime() < today.getTime()-((5)*24*60*60*1000)
//							  (date.getTime() > today.getTime()-(10*24*60*60*1000)) 
							  || date.getTime() > today.getTime()+(10*24*60*60*1000))	
//							  date.getDay() == 0 || 
							  ) ? true : false;  }

					    });
</script>
	</td><tr>
	 
	 <td class= "blanco b" width="50" bgcolor="#FFFFCC">Concepto<td td  colspan='3' class="rojo">
  	<?php
	$sql= "SELECT *FROM sgcaf842";
	//echo $sql; 
	$result=mysql_query($sql);
	$fila = mysql_fetch_assoc($result);
			$concepto=$fila['codi_prest'];
			echo '<select name="concepto" size="1">';
			$sql="select * from sgcaf842";
			$resultado=mysql_query($sql);
			while ($fila = mysql_fetch_assoc($resultado)) {
				echo '<option value="'.$fila['codi_prest'].'" '.(($elcdpto==$fila['codi_prest'])?'selected':'').'>'.$fila['desc_prest'].'</option>';}
	
	 	echo '</select> '; 
		?>*
   </tr>
</table>
	
		&nbsp;</td></tr> 
<?php 
}
?>

<?php 
function pantalla_act_in2($result,$accionIn,$codigo,$nombre,$numero,$beneficiario,$estatus,$concepto,$concepto1,$fecha,$observacion,$cod_cont, $montoche){
$deci=$_SESSION['deci'];
$sep_decimal=$_SESSION['sep_decimal'];
$sep_miles=$_SESSION['sep_miles'];
$filaxxx = mysql_fetch_assoc($result);
if ($accionIn == 'Verificar1In') { $lectura = 'readonly = "readonly"'; $activada="disabled" ; } else {$lectura=''; $activada='';}
echo '<fieldset><legend>ACTUALIZACIÓN DE CHEQUES del Banco '.$nombre.'</legend>';
echo '<table width="440" border="3">';
echo '<tr><td class= "blanco b" colspan="5" width="50" style="text-align:center" bgcolor="#FFFFCC">Encabezado del Cheque </td></tr>';
echo '<tr><td class= "blanco b" width="50" bgcolor="#FFFFCC">Nro. de Cuenta</td>';
echo '<td><input name="codigo" type="text" id="codigo" value="'.$codigo.'" \'$lectura\' size="20" maxlength="20" /></td>';
echo '<td class= "blanco b" width="50" bgcolor="#FFFFCC">Nro. de Cheque</td>';
echo '<td><input name="numero" type="text" id="numero" value="'.$numero.'" '.$lectura.' size="8" maxlength="8" /></td>';
echo '<td rowspan="5"><textarea name="observacion" type="text" id="observacion" onChange="conMayusculas(this)" cols="35" rows="04" maxlength="95" value="'.$observacion.'"/> '.$observacion.'</textarea></td></tr>';
?>		 
		 </td><tr>
	 
	  <td class= "blanco b" width="50" bgcolor="#FFFFCC">Beneficiario<td  colspan='3'>
	 <input name="beneficiario" type="text" id="beneficiario" value="<?php echo $beneficiario?>" <?php echo $lectura; ?>size="65" maxlength="65" /></td><tr>
	 
		 <td class= "blanco b" width="50" bgcolor="#FFFFCC">Estatus<td>
	<input type="radio" name="estatus" value="L" checked/> Listo</label> 
</td>
	  <td class= "blanco b" width="50" bgcolor="#FFFFCC">Fecha<td>
	 <input name="fecha" type="text" id="fecha" value="<?php echo $fecha ?>" <?php echo $lectura; ?>size="10" maxlength="20" /></td><tr>
	 
	 <td class= "blanco b" width="50" bgcolor="#FFFFCC">Concepto<td>
	 <input name="concepto1" type="text" id="concepto1" value="<?php
	 echo $concepto1;?>" <?php echo $lectura;  ?>size="46" maxlength="20" /></td>  
	 <td class= "blanco b" width="50" bgcolor="#FFFFCC">Monto<td>
	 <input name="montoche" type="text" id="montoche" style="text-align:right" value="<?php echo number_format($montoche,2,'.',',') ?>" <?php echo $lectura; ?>size="10" maxlength="20" /></td><tr>  
	 <input type="hidden" name="nro" value="<?php echo $filaxxx['cod_banco'];?>">
   </tr>
</table>
	
		&nbsp;</td></tr> 

<?php 
}
?>
<?php 
function pantalla_act_in3($result,$accionIn,$codigo,$nombre,$numero,$beneficiario,$estatus,$concepto,$concepto1,$fecha,$observacion,$cod_cont, $montoche,$elcargo,$descripcion,$cuenta1,$elmonto, $cod, $registro){
$deci=$_SESSION['deci'];
$sep_decimal=$_SESSION['sep_decimal'];
$sep_miles=$_SESSION['sep_miles'];
$fila999 = mysql_fetch_assoc($result);
if ($accionIn == 'Verificar2In' or $accionIn=='Verificar3In' or $accionIn=='EditaIn') { $lectura = 'readonly = "readonly"'; $activada="disabled" ; } else {$lectura=''; $activada='';}
?>
 <fieldset><legend>ACTUALIZACIÓN DE CHEQUES del Banco <?php echo $nombre?></legend>
    <table width="600" border="3">
	<td class= "blanco b" colspan='5' width="50" style="text-align:center" bgcolor="#FFFFCC">Encabezado del Cheque <tr>
		
	  <td class= "blanco b" width="50" bgcolor="#FFFFCC">Nro. de Cuenta<td>
	<input name="codigo" type="text" id="codigo" value="<?php echo $codigo ?>" <?php echo $lectura; ?>size="20" maxlength="20" /></td>
		
	  <td class= "blanco b" width="50" bgcolor="#FFFFCC">Nro. de Cheque<td>
	 <input name="numero" type="text" id="numero" value="<?php echo $numero?>" <?php  echo $lectura; ?>size="8" maxlength="8" /></td>
	 	 <td rowspan='5'><textarea name="observacion" type="text" id="observacion" onChange="conMayusculas(this)" <?php  echo $lectura; ?> cols="35" rows="04" maxlength="125" value="<?php echo $observacion;?>"/> <?php echo $observacion;?></textarea></td><tr>
		 
		 </td><tr>
	 
	  <td class= "blanco b" width="50" bgcolor="#FFFFCC">Beneficiario<td  colspan='3'>
	 <input name="beneficiario" type="text" id="beneficiario" value="<?php echo $beneficiario?>" <?php echo $lectura; ?>size="65" maxlength="65" /></td><tr>
	 
		 <td class= "blanco b" width="50" bgcolor="#FFFFCC">Estatus<td>
	<input type="radio" name="estatus" value="L" checked/> Listo</label> 
</td>
	  <td class= "blanco b" width="50" bgcolor="#FFFFCC">Fecha<td>
	 <input name="fecha" type="text" id="fecha" value="<?php echo $fecha ?>" <?php echo $lectura; ?>size="10" maxlength="20" /></td><tr>
	 
	 <td class= "blanco b" width="50" bgcolor="#FFFFCC">Concepto<td>
	 <input name="concepto1" type="text" id="concepto1" value="<?php
	 echo $concepto1;?>" <?php echo $lectura;  ?>size="46" maxlength="20" /></td> 
	  <td class= "blanco b" width="50" bgcolor="#FFFFCC">Monto<td>
	 <input name="montoche" type="text" id="montoche" style="text-align:right" value="<?php echo number_format($montoche,2,'.',',') ?>" <?php echo $lectura; ?>size="10" maxlength="20" /></td><tr>   </tr>
   </div></body></html>
<td>
</table>
<table class='basica 100' width='655'>
<tr><th width="45"colspan='4'> ASIENTOS CONTABLES PARA ESTE CHEQUE</th>
<tr><th width="45"> </th><th width="100">Cuenta</th><th width="200">Descripción</th><th width="80">Monto</th></tr>
<td>

<?php 
// echo 'pantalla_asiento '.$fechax;
$activar=' ';
if (($elcargo == '+')) {$activar='checked="checked"'; } else { $activar = ' '; }
//  || ($elcargo = 1)
// value="<?php echo $elcargo;>" 
?>
<input name="elcargo" type="checkbox" tabindex='4' <?php echo $activar;?> /> 
Cargo
</td> <td>
<input type="text" size="25" tabindex='5' name='cuenta1' id="inputString" onKeyUp="lookup(this.value);" onBlur="fill();" value ="<?php echo $cuenta1;?>" autocomplete="off"/>
			<div class="suggestionsBox" id="suggestions" style="display: none;">
				<img src="upArrow.png" style="position: relative; top: -12px; left: 70px; "  alt="upArrow" />
				<div class="suggestionList" id="autoSuggestionsList">
				</div>
			</div>
		</div>

</td><td>
<input type = 'text' size='45' maxlength='60' name='descripcion' tabindex='6' onChange="conMayusculas(this)" value ="<?php echo $descripcion; ?>">
</td><td>
<input type = 'text' size='11' maxlength='11' name='elmonto' value='<?php echo 
$elmonto ;?>' tabindex='8'>
<?php if ($accionIn=='Verificar2In'){ $time='2'; }
else if ($accionIn=='Verificar3In') { $time='3'; }?>
<input type="hidden" name="nro" value="<?php 
$sql = "select cod_banco from sgcaf843 where nro_cta_ba= '$codigo' and estado='1'  and emitircheque='1'";
$result=mysql_query($sql);
$sss = mysql_fetch_assoc($result);
echo $sss['cod_banco'];?>">
<input type="hidden" name="time" value="<?php echo $time;?>">
<input type="hidden" name="cod_cont" value="<?php echo $cod_cont;?>">
<input type="hidden" name="cod" value="<?php echo $cod;?>">
<input type="hidden" name="registro" value="<?php echo $registro;?>">
</td>
</tr>
<tr>
</table>
	&nbsp;</td></tr> 
<?php
}
?>
<?php 
function pantalla_act_in4($result,$accionIn,$codigo,$nombre,$numero,$beneficiario,$estatus,$concepto,$concepto1,$fecha,$observacion,$cod_cont, $montoche){
$deci=$_SESSION['deci'];
$sep_decimal=$_SESSION['sep_decimal'];
$sep_miles=$_SESSION['sep_miles'];
$fila999 = mysql_fetch_assoc($result);
//echo $sql; 
if ($accionIn == 'Anadir') { $lectura = 'readonly = "readonly"'; $activada="disabled" ; } else {$lectura=''; $activada='';}
?>
 <label><fieldset><legend>ACTUALIZACIÓN DE CHEQUES del Banco <?php echo $nombre?></legend>
  	<table width="440" border="3">
	<td class= "blanco b" colspan='5' width="50" style="text-align:center" bgcolor="#FFFFCC">Encabezado del Cheque <tr>
		
	  <td class= "blanco b" width="50" bgcolor="#FFFFCC">Nro. de Cuenta<td>
	<input name="codigo" type="text" id="codigo" value="<?php echo $codigo ?>" <?php echo $lectura; ?>size="20" maxlength="20" /></td>
		
	  <td class= "blanco b" width="50" bgcolor="#FFFFCC">Nro. de Cheque<td>
	 <input name="numero" type="text" id="numero" value="<?php echo $numero; ?>" <?php  echo $lectura; ?>size="8" maxlength="8" /></td>
	 	 <td rowspan='5'><textarea name="observacion" type="text" id="observacion" onChange="conMayusculas(this)" <?php  echo $lectura; ?> cols="35" rows="04" maxlength="125"/> <?php echo $observacion;?></textarea></td><tr>
		 
		 </td><tr>
	 
	  <td class= "blanco b" width="50" bgcolor="#FFFFCC">Beneficiario<td  colspan='3'>
	 <input name="beneficiario" type="text" id="beneficiario" value="<?php echo $beneficiario?>" <?php echo $lectura; ?>size="65" maxlength="65" /></td><tr>
	 
		 <td class= "blanco b" width="50" bgcolor="#FFFFCC">Estatus<td>
	<input type="radio" name="estatus" value="L" checked/> Listo</label>
	  <td class= "blanco b" width="50" bgcolor="#FFFFCC">Fecha<td>
	 <input name="fecha" type="text" id="fecha" value="<?php echo $fecha ?>" <?php echo $lectura; ?>size="10" maxlength="20" /></td><tr>
	 <input type="hidden" name="cod_cont" value="<?php echo $cod_cont;?>">
	 <td class= "blanco b" width="50" bgcolor="#FFFFCC">Concepto<td>
	 <input name="concepto1" type="text" id="concepto1" value="<?php echo $concepto1;?>" <?php echo $lectura;  ?>size="46" maxlength="20" /></td>
	  <td class= "blanco b" width="50" bgcolor="#FFFFCC">Monto<td>
	 <input name="montoche" type="text" id="montoche"  style="text-align:right" value="<?php echo number_format($montoche,2,'.',',') ?>" <?php echo $lectura; ?>size="10" maxlength="20" /></td><tr>  </tr>
   </div></body></html>
<td>
</table>
	&nbsp;</td></tr> 
<?php
}
?>


<?php 
function pantalla_act_borrar($result,$accionIn,$codigo,$numero,$nombre){
$deci=$_SESSION['deci'];
$sep_decimal=$_SESSION['sep_decimal'];
$sep_miles=$_SESSION['sep_miles'];
$filaxxx = mysql_fetch_assoc($result);
//echo $sql; 
if ($accionIn == 'Borrar_Cheque') { $lectura = 'readonly = "readonly"'; $activada="disabled" ; } else {$lectura=''; $activada='';}
?>
<label><fieldset><legend>ACTUALIZACIÓN DE CHEQUES del Banco <?php echo $nombre?>/ Eliminación</legend>
  	<table width="380" border="3">
	<td class= "blanco b" colspan='5' width="50" style="text-align:center" bgcolor="#FFFFCC">Encabezado del Cheque <tr>
		
	  <td class= "blanco b" width="50" bgcolor="#FFFFCC">Nro. de Cuenta<td>
	<input name="codigo" type="text" id="codigo" value="<?php echo $codigo ?>" <?php echo $lectura; ?>size="20" maxlength="20" /></td>
	 <td class= "blanco b" width="50" bgcolor="#FFFFCC">Nro. de Cheque<td>
	 <input name="numero" type="text" id="numero" value="<?php echo $numero?>" <?php  echo $lectura; ?>size="8" maxlength="8" /></td> <tr>
	 
	 <td class= "blanco b" width="50" bgcolor="#FFFFCC">Explicación
	 <td  colspan='3' class="rojo b">	<?php
			echo '<select name="explicacion" size="1">';
			$sql="select * from sgcaf000 where tipo='Eliminar'";
			$resultado=mysql_query($sql);
			while ($fila = mysql_fetch_assoc($resultado)) {
				echo '<option value="'.$fila['nombre'].'" '.(($explicacion==$fila['nombre'])?'selected':'').'>'.$fila['nombre'].'</option>';
				}
	
	 	echo '</select> '; 
		?>*</td><tr>
		 
		 </td><tr>
	 </tr>
</table>
	
		&nbsp;</td></tr> 

<?php 
}
?>

<?php 
function pantalla_act_anular($result,$accionIn,$codigo,$numero,$nombre){
$deci=$_SESSION['deci'];
$sep_decimal=$_SESSION['sep_decimal'];
$sep_miles=$_SESSION['sep_miles'];
$filaxxx = mysql_fetch_assoc($result);
//echo $sql; 
if ($accionIn == 'Anular_Cheque') { $lectura = 'readonly = "readonly"'; $activada="disabled" ; } else {$lectura=''; $activada='';}
?>
<label><fieldset><legend>ACTUALIZACIÓN DE CHEQUES del Banco <?php echo $nombre?>/ Anulación</legend>
  	<table width="360" border="3">
	<td class= "blanco b" colspan='5' width="50" style="text-align:center" bgcolor="#FFFFCC">Encabezado del Cheque <tr>
		
	  <td class= "blanco b" width="50" bgcolor="#FFFFCC">Nro. de Cuenta<td>
	<input name="codigo" type="text" id="codigo" value="<?php echo $codigo ?>" <?php echo $lectura; ?>size="20" maxlength="20" /></td>
	 <td class= "blanco b" width="50" bgcolor="#FFFFCC">Nro. de Cheque<td>
	 <input name="numero" type="text" id="numero" value="<?php echo $numero?>" <?php  echo $lectura; ?>size="8" maxlength="8" /></td> <tr>
	 
 <td class= "blanco b" width="50" bgcolor="#FFFFCC">Explicación<td td  colspan='3' class="rojo">
  	<?php
			echo '<select name="explicacion" size="1">';
			$sql="select * from sgcaf000 where tipo='Anulado'";
			$resultado=mysql_query($sql);
			while ($fila = mysql_fetch_assoc($resultado)) {
				echo '<option value="'.$fila['nombre'].'" '.(($explicacion==$fila['nombre'])?'selected':'').'>'.$fila['nombre'].'</option>';}
	
	 	echo '</select> '; 
		?>*</td><tr>
		 
		 </td><tr>
	 </tr>
</table>
	
		&nbsp;</td></tr> 

<?php 
}
?>

<?php 
function pantalla_act_consultar($result,$accionIn,$codigo,$numero,$nombre){
$deci=$_SESSION['deci'];
$sep_decimal=$_SESSION['sep_decimal'];
$sep_miles=$_SESSION['sep_miles'];
$filaxxx = mysql_fetch_assoc($result);
//echo $sql; 
if ($accionIn == 'Consultar') { $lectura = 'readonly = "readonly"'; $activada="disabled" ; } else {$lectura=''; $activada='';}
echo '<fieldset><legend> CHEQUE  Nro.'.$numero.' del Banco '.$nombre.'</legend>';
echo '<table width="100%" border="2">';
echo '<tr>';
echo "<td class= 'blanco b' colspan='5' width='50' style='text-align:center' bgcolor='#FFFFCC'>Encabezado del Cheque </td><tr>";
echo '<tr>';
echo "<td class= 'blanco b' bgcolor='#FFFFCC'>Nro. de Cuenta<td class= 'blanco b' >".$codigo."</td>";
echo "<td class= 'blanco b' bgcolor='#FFFFCC'>Nro. de Cheque<td class= 'blanco b'>".$numero."</td>";
echo '</tr>';
echo '<tr>';
echo "<td colspan='4' rowspan='5'><textarea name='observacion' type='text' id='observacion' onChange='conMayusculas(this)' ".$lectura."  cols='35' rows='04' maxlength='05'/>";
echo $filaxxx['mche_descr'].'</textarea></td></tr>';
echo '<tr>';
echo '<td class= "blanco b" width="50" bgcolor="#FFFFCC">Beneficiario<td  colspan="3" class= "blanco b" width="100">'.$filaxxx['mche_nombr'].'</td></tr>';
echo '<tr>';
echo '<td class= "blanco b" width="50" bgcolor="#FFFFCC">Estatus<td class= "blanco b" width="120%">';
if ($filaxxx['mche_statu']=='L') 
	echo '<input type="radio" name="estatus" value="L" checked/> Listo</label>'; 
if ($filaxxx['mche_statu']=='I') 
	echo '<input type="radio" name="estatus" value="I" checked/> Impreso</label>'; 
if ($filaxxx['mche_statu']=='A') 
	echo '<input type="radio" name="estatus" value="A" checked/> Anulado</label>'; 
echo '</td>';
echo '<td class= "blanco b" width="50" bgcolor="#FFFFCC">Fecha<td class= "blanco b">'.$filaxxx['fecha'].'</td></tr><tr>';
echo '<td class= "blanco b" width="50" bgcolor="#FFFFCC">Concepto<td class= "blanco b" width="120%">'.$filaxxx['desc_prest'].'</td>';
echo '<td class= "blanco b" width="50" bgcolor="#FFFFCC">Monto<td class= "blanco b" style="text-align:right">'.number_format ($filaxxx['mche_monto'],2,'.',',').'</td></tr>';
//echo '</div></body></html>';
echo '</table>';
//	&nbsp;</td></tr> 
}
?>

<?php
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if ($accionIn == "VerificarMo0") {
	echo '<div id="div1">';
	echo "<form action='cheact.php?accionIn=VerificarMo1' name='form1' method='post' onsubmit='return valobs(form1)'>";
	$sql= "SELECT cod_banco, nro_cta_ba, mche_orden, mche_nombr FROM sgcaf840, sgcaf843 where mche_orden='$numero' and nro_cta_ba ='$codigo' and cod_banco = mche_banco and mche_statu='L' and estado='1' and emitircheque='1' and cobrados='0'"; 
//	echo $sql; 
	$result=mysql_query($sql);
	if (mysql_num_rows($result) <> 0) {
    pantalla_act_mo00($result,$accionIn,$codigo,$nombre,$numero);
    echo "<input type = 'submit' value = 'Enviar'>";
	echo '</div>';   }  
else 
	if (mysql_num_rows($result) == 0) {
		echo "<p />Nro. de Cheque <span class='b'>$numero</span> no esta registrado</div></body></html>";
		echo '<div style="clear:both"></div>';
		echo '<p /><div class="noimpri" style="clear:both;text-align:center">';
		echo '<a href="cheact.php?accionIn=VerificarMo&codigo=$codigo"><input type="button" name="boton" value="regresar" tabindex="3">';
		exit; 
	}
	echo $accionIn; 
	echo '</div>';
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if ($accionIn == "VerificarMo1") {
	echo '<div id="div1">';
// aqui	
	if ($modificar=='1') {
		echo "<form action='cheact.php?accionIn=EditarMo' name='form1' method='post' onsubmit='return valobs(form1)'>";
		
		$sql="SELECT mche_orden, date_format(mche_fecha, '%d/%m/%Y') AS fecha, mche_nombr, mche_descr, mche_statu, mche_banco, mche_prest, desc_prest, nombre_ban, cue_banco, mche_monto, cod_banco FROM sgcaf840, sgcaf842, sgcaf843 where mche_orden='$numero' and codi_prest=mche_prest and mche_banco=cod_banco and nro_cta_ba ='$codigo' and mche_statu='L' and estado='1' and emitircheque='1' and cobrados='0'";
		$result=mysql_query($sql);
		$editar1= mysql_fetch_assoc($result);
//		echo $sql; 
		$cod_banco=$editar1['cod_banco'];
//		echo $cod_banco; 
//		echo $codigo;
		$nombre=$editar1['nombre_ban']; 
		$beneficiario=$editar1['mche_nombr'];
		$estatus=$editar1['mche_statu'];
		$concepto1= $editar1['desc_prest'];
		$concepto= $editar1['mche_prest'];
		$fecha= $editar1['fecha'];
		$cod_cont= $editar1['cue_banco'];
		$elcargo= $editar['mche_debcr'];
	    $observacion= $editar1['mche_descr'];
		$montoche= number_format($editar1['mche_monto'],0,'','');
	    pantalla_act_mo1($result,$accionIn,$modificar,$codigo,$nombre,$numero,$beneficiario,$estatus,$concepto,$concepto1,$fecha,$observacion,$cod_cont,$montoche, $cod_banco);
   		echo "<input type = 'submit' value = 'Enviar'>";
//		echo $accionIn; 
	echo '</div>';
	}
	if ($modificar=='2') {
	echo "<form action='cheact.php?accionIn=Verificar3In' name='form1' method='post' onsubmit='return valcar(form1)'>";
		
		$sql="SELECT mche_orden, date_format(mche_fecha, '%d/%m/%Y') AS fecha, mche_nombr, mche_descr, mche_statu, mche_banco, mche_prest, desc_prest, nombre_ban, cue_banco, mche_monto, cod_banco FROM sgcaf840, sgcaf842, sgcaf843 where mche_orden='$numero' and codi_prest=mche_prest and mche_banco=cod_banco and nro_cta_ba ='$codigo' and estado='1' and emitircheque='1' ";
		$result=mysql_query($sql);
		$editar1= mysql_fetch_assoc($result);
//		echo $sql; 
		$cod_banco=$editar1['cod_banco'];
		$nombre=$editar1['nombre_ban']; 
		$beneficiario=$editar1['mche_nombr'];
		$estatus=$editar1['mche_statu'];
		$concepto1= $editar1['desc_prest'];
		$concepto= $editar1['mche_prest'];
		$fecha= $editar1['fecha'];
		$cod_cont= $editar1['cue_banco'];
		$elcargo= $editar['mche_debcr'];
	    $observacion= $editar1['mche_descr'];
		$montoche= number_format($editar1['mche_monto'],0,'','');
	 	pantalla_act_mo4($result,$accionIn,$codigo,$nombre,$numero,$beneficiario,$estatus,$concepto,$concepto1,$fecha,$observacion,$cod_cont,$montoche,$cod_banco);
	echo "<p />";
    echo "<input type = 'submit' value = 'Agregar Asiento'>";
    echo "<h3><a href= cheact.php>Salir</a></h3>";
	$sql = "SELECT * FROM sgcaf841 where mche_orden='$numero' and mche_banco='$cod_banco' and mche_cuent<>'$cod_cont' order by mche_debcr";
//	echo $sql; 
	$rs = mysql_query($sql);
	$registros=mysql_num_rows($rs);
//	echo $numero; 
//	echo 'hola'; 
    if ($registros > 0) {
//	echo $sql;
	echo "<table class='basica 100 hover' width='200%'><tr>";
	echo '<th colspan="6">ASIENTOS CONTABLES PARA ESTE CHEQUE</th><tr>';
	echo '<th>  </th><th>  </th><th>Cuentas</a></th><th>Descripción</a><br>';
	echo '<th>Debe</a></th><th>Haber</a><br>';
	echo '</th></th>';

$td='0';
$th='0'; 
	while($row=mysql_fetch_array($rs)) {
		echo "<tr>";
			echo "<td><a href='cheact.php?accionIn=EditaIn&numero=$numero&cuenta1=".$row['mche_cuent']."&codigo=$codigo&registro=".$row['registro']."&cod_banco=".$row['mche_banco']."'><img src='imagenes/16-em-pencil.png' width='16' height='16' border='0' title='Editar' alt='Editar' /></a></td>";
		echo "<td><a href='cheact.php?accionIn=Anadir&a=B&numero=$numero&cuenta1=".$row['mche_cuent']."&codigo=$codigo&cod_cont=$cod_cont&registro=".$row['registro']."&cod_banco=".$row['mche_banco']."'><img src='imagenes/16-em-cross.png' width='16' height='16' border='0' title='Eliminar' alt='Eliminar' /></a></td>";
		echo "<td class='centro'>";
		echo $row['mche_cuent']."</a></td>";
		echo "<td class='izq'>";
		echo $row['mche_descr']."</a></td>";
		echo "<td class='dcha'>";
		echo number_format($row['mche_monto1'],2,'.',',')."</a></td>";
		$td=$td+$row['mche_monto1']; 
		echo "<td class='dcha'>";
		echo number_format($row['mche_monto2'],2,'.',',')."</a></td>";
		$th=$th+$row['mche_monto2']; 
 }
	echo "<tr>";
	   $sql = "SELECT * FROM sgcaf841 where mche_orden='$numero' and mche_banco='$cod_banco' and mche_cuent='$cod_cont' order by mche_debcr";
		echo $sql;
	$rs1 = mysql_query($sql);
	$registros22= mysql_fetch_assoc($rs1);
		$thh=$registros22['mche_monto2'];
    	echo "<td></td>";
		echo "<td></td>";
		echo "<td align='center' class='b''>";
		echo $registros22['mche_cuent']."</a></td>";
		echo "<td align='left' class='b'>";
		echo $registros22['mche_descr']."</a></td>";
		echo "<td align='right' class='b'>";
		echo number_format($registros22['mche_monto1'],2,'.',',')."</a></td>";
		echo "<td align='right' class='b'>";
		echo number_format($registros22['mche_monto2'],2,'.',',')."</a></td>";
			echo "<tr>";
			if ($td == $th+$thh)
		{
		echo "<td align='right' class='b' colspan ='4'>";
		echo "Totales:</td>";
		echo "<td align='right' class='blanco b'>"; 
		echo number_format($td,2,'.',',')."</a></td>";
		echo "<td align='right' class='blanco b'>";
		echo number_format($th+$thh,2,'.',',')."</a></td>";
		}
		else if ($td <> $th+$thh)
		{
		$diferencia=number_format($td-$th+$thh,2,'.',','); 
		echo "<td align='right' class='rojo b' colspan ='4'>";
		echo "Diferencia de $diferencia  Totales: </td>";
		echo "<td align='right' class='rojo b'>"; 
		echo number_format($td,2,'.',',')."</a></td>";
		echo "<td align='right' class='rojo b'>";
		echo number_format($th+$thh,2,'.',',')."</a></td>";
		}
		echo "<tr>";
	echo "</table>";
	echo "<p />";
	echo '</div>';
	}
	}
   
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
?>
</body></html>
   </td>
    </tr>
</table>
</fieldset>
<?php
function pantalla_act_mo ($result,$accionIn){
$deci=$_SESSION['deci'];
$sep_decimal=$_SESSION['sep_decimal'];
$sep_miles=$_SESSION['sep_miles'];
//echo $sql; 
if ($accionIn == 'VerificarMo') { $lectura = 'readonly = "readonly"'; $activada="disabled" ; } else {$lectura=''; $activada='';}
?>
  	 <label><fieldset><legend>ACTUALIZACIÓN DE CHEQUES/ Modificación </legend>
  	<table width="260" border="3">
     <td class= "blanco b" width="50" bgcolor="#FFFFCC">Nro. de Cuenta<td>
	 	<?php
			$codigo=$fila['nro_cta_ba'];
			echo '<select name="codigo" size="1">';
			$sql="select * from sgcaf843 where estado='1' and emitircheque='1' order by nombre_ban";
			$resultado=mysql_query($sql);
			while ($fila2 = mysql_fetch_assoc($resultado)) {
				echo '<option value="'.$fila2['nro_cta_ba'].'" '.(($banco==$fila2['nro_cta_ba'])?'selected':'').'>'.$fila2['nombre_ban'].''.$fila2['nro_cta_ba'].'</option>';}
	 	echo '</select> '; 
	    ?> 
		<input type="hidden" name="nombre" value="<?php echo $fila2['nombre_ban'];?>">	
	 *</td><tr>
</table>
 	&nbsp;</td></tr> 

<?php 
}
?>

<?php 
function pantalla_act_mo0($result,$accionIn,$codigo,$nombre)
{
$deci=$_SESSION['deci'];
$sep_decimal=$_SESSION['sep_decimal'];
$sep_miles=$_SESSION['sep_miles'];
//echo $sql; 
if ($accionIn == 'VerificarMo') { $lectura = 'readonly = "readonly"'; $activada="disabled" ; } else {$lectura=''; $activada='';}
?>
  	 <label><fieldset><legend>ACTUALIZACIÓN DE CHEQUES del Banco <?php 
	$sql= "SELECT *FROM sgcaf843 where nro_cta_ba='$codigo' and estado='1' and emitircheque='1'";
	$result=mysql_query($sql);
	$fila5 = mysql_fetch_assoc($result);
	echo $fila5 ['nombre_ban'] ?> / Modificación</legend>
	
  	<table width="440" border="3">
	<td class= "blanco b" colspan='4' width="50" style="text-align:center" bgcolor="#FFFFCC">Encabezado del Cheque<tr>
		
	 <td class= "blanco b" width="50" bgcolor="#FFFFCC">Nro. de Cuenta<td>
	<input name="codigo" type="text" id="codigo" value="<?php echo $codigo ?>" <?php echo $lectura; ?>size="20" maxlength="20" />*</td>
	 </td>
	  <td class= "blanco b" width="50" bgcolor="#FFFFCC">Nro de Cheque<td>
	 <input name="numero" type="text" id="numero" value="<?php  ?>" size="8" maxlength="8" />*</td><tr>
</table>
	&nbsp;</td></tr> 
<?php 
}
?>

<?php 
function pantalla_act_mo00($result,$accionIn,$codigo,$nombre,$numero)
{
$deci=$_SESSION['deci'];
$sep_decimal=$_SESSION['sep_decimal'];
$sep_miles=$_SESSION['sep_miles'];
//echo $sql; 
if ($accionIn == 'VerificarMo0') { $lectura = 'readonly = "readonly"'; $activada="disabled" ; } else {$lectura=''; $activada='';}
?>
  	 <label><fieldset><legend>ACTUALIZACIÓN DE CHEQUES del Banco <?php echo $nombre ?> / Modificación</legend>
	
  	<table width="440" border="3">
	<td class= "blanco b" colspan='4' width="50" style="text-align:center" bgcolor="#FFFFCC">Encabezado del Cheque<tr>
		
	 <td class= "blanco b" width="50" bgcolor="#FFFFCC">Nro. de Cuenta<td>
	<input name="codigo" type="text" id="codigo" value="<?php echo $codigo ?>" <?php echo $lectura; ?>size="20" maxlength="20" />*</td>
	 </td>
	  <td class= "blanco b" width="50" bgcolor="#FFFFCC">Nro. de Cheque<td>
	 <input name="numero" type="text" id="numero" value="<?php echo $numero; ?>" <?php echo $lectura; ?> size="8" maxlength="8" />*</td><tr>
	 
      <td class= "blanco b" width="50" bgcolor="#FFFFCC">Modificar<td colspan='3' class= "blanco b">
	 <input type="radio" name="modificar" value = "1"/> Encabezado del Cheque
	<input type="radio" name="modificar" value = "2" checked />Asientos Contables del Cheque</td><tr>
	 
</table>
	&nbsp;</td></tr> 
<?php 
}
?>

<?php 
function  pantalla_act_mo1($result,$accionIn,$modificar,$codigo,$nombre,$numero,$beneficiario,$estatus,$concepto,$concepto1,$fecha,$observacion,$cod_cont, $montoche, $cod_banco)
{
$deci=$_SESSION['deci'];
$sep_decimal=$_SESSION['sep_decimal'];
$sep_miles=$_SESSION['sep_miles'];
//echo $sql; 
if ($accionIn == 'VerificarMo1') { $lectura = 'readonly = "readonly"'; $activada="disabled" ; } else {$lectura=''; $activada='';}
?>
  	 <label><fieldset><legend>ACTUALIZACIÓN DE CHEQUES del Banco <?php echo $nombre;  ?> / Modificación</legend>
	
  	<table width="600" border="3">
	<td class= "blanco b" colspan='5' width="50" style="text-align:center" bgcolor="#FFFFCC" >Encabezado del Cheque<tr>
		
	 <td class= "blanco b" width="50" bgcolor="#FFFFCC">Nro. de Cuenta<td>
	<input name="codigo" type="text" id="codigo" value="<?php echo $codigo ?>" <?php echo $lectura; ?>size="20" maxlength="20" />*</td>
	 </td>
	  <td class= "blanco b" width="50" bgcolor="#FFFFCC">Nro. de Cheque<td width="30">
	 <input name="numero" type="text" id="numero" value="<?php  echo  $numero; ?>" <?php echo $lectura; ?> size="8" maxlength="8" />*
	 	 <td rowspan='5'><textarea name="observacion" type="text" id="observacion" onChange="conMayusculas(this)" cols="35" rows="05" maxlength="125" value="<?php echo $observacion;?>"/> <?php echo $observacion;?></textarea></td><tr>
		 
	 
	  <td class= "blanco b" width="50" bgcolor="#FFFFCC">Beneficiario<td  colspan='3'>
	 <input name="beneficiario" type="text" id="beneficiario" value="<?php echo $beneficiario; ?>" size="65" maxlength="65" /></td><tr>
	 
		 <td class= "blanco b" width="50" bgcolor="#FFFFCC">Estatus<td>
	<input type="radio" name="estatus" value="L" checked/> Listo</label> 
</td>
	   <td class= "blanco b" width="50" bgcolor="#FFFFCC">Fecha<td width="25">
<?php
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
//		range          :     <?php echo $rango; ?>,


		dateStatusFunc :    function (date) { // disable weekend days (Saturdays == 6 and Subdays == 0)
						var today = new Date();
						return (
							  (date.getTime() < today.getTime()-((5)*24*60*60*1000)
//							  (date.getTime() > today.getTime()-(10*24*60*60*1000)) 
							  || date.getTime() > today.getTime()+(10*24*60*60*1000))	
//							  date.getDay() == 0 || 
							  ) ? true : false;  }

					    });
</script>
</td><tr>
	 
	 <td class= "blanco b" width="50" bgcolor="#FFFFCC">Concepto<td>
	 <input name="concepto1" type="text" id="concepto1" value="<?php
	 echo $concepto1;?>" <?php echo $lectura;  ?>size="46" maxlength="20" /></td>  
	 <td class= "blanco b" width="50" bgcolor="#FFFFCC">Monto<td width="30">
	 <input name="montoche" type="text" id="montoche" style="text-align:right" value="<?php echo number_format($montoche,2,'.',',') ?>" <?php echo $lectura; ?>size="16" maxlength="20" /></td><tr>   </tr>
	 <input type="hidden" name="cod_banco" value="<?php echo $cod_banco;?>">

   </tr>
</table>
	
		&nbsp;</td></tr> 
<?php 
}
?>

<?php 
function pantalla_act_mo2($result,$accionIn,$modificar,$codigo,$nombre,$numero,$beneficiario,$estatus,$concepto,$concepto1,$fecha,$observacion,$cod_cont, $montoche){
$deci=$_SESSION['deci'];
$sep_decimal=$_SESSION['sep_decimal'];
$sep_miles=$_SESSION['sep_miles'];
//echo $sql; 
if ($accionIn == 'VerificarMo1') { $lectura = 'readonly = "readonly"'; $activada="disabled" ; } else {$lectura=''; $activada='';}
?>
<label><fieldset><legend>ACTUALIZACIÓN DE CHEQUES del Banco <?php echo $nombre?></legend>
  	<table width="500" border="3">
	<td class= "blanco b" colspan='5' width="50" style="text-align:center" bgcolor="#FFFFCC" >Encabezado del Cheque <tr>
		
	  <td class= "blanco b" width="50" bgcolor="#FFFFCC">Nro. de Cuenta<td>
	<input name="codigo" type="text" id="codigo" value="<?php echo $codigo ?>" <?php echo $lectura; ?>size="20" maxlength="20" /></td>
		
	  <td class= "blanco b" width="50" bgcolor="#FFFFCC">Nro. de Cheque<td>
	 <input name="numero" type="text" id="numero" value="<?php echo $numero?>" <?php  echo $lectura; ?>size="8" maxlength="8" /></td>
	 <td rowspan='5'><textarea name="observacion" type="text" id="observacion" onChange="conMayusculas(this)" cols="35" rows="04" maxlength="125" value="<?php echo $observacion;?>"/> <?php echo $observacion;?></textarea></td><tr>
		 
		 </td><tr>
	 
	  <td class= "blanco b" width="50" bgcolor="#FFFFCC">Beneficiario<td  colspan='3'>
	 <input name="beneficiario" type="text" id="beneficiario" value="<?php echo $beneficiario;?>" size="65" maxlength="65" /></td><tr>
	 
		 <td class= "blanco b" width="50" bgcolor="#FFFFCC">Estatus<td>
	<input type="radio" name="estatus" value="L" checked/> Listo</label> 
</td>
	  <td class= "blanco b" width="50" bgcolor="#FFFFCC">Fecha<td>
	 <input name="fecha" type="text" id="fecha" value="<?php echo $fecha; ?>" size="10" maxlength="20" /></td><tr>
	 
	 <td class= "blanco b" width="50" bgcolor="#FFFFCC">Concepto<td>
	 <input name="concepto1" type="text" id="concepto1" value="<?php
	 echo $concepto1;?>" size="46" maxlength="20" /></td> 
	  <td class= "blanco b" width="50" bgcolor="#FFFFCC">Monto<td>
	 <input name="montoche" type="text" id="montoche" style="text-align:right" value="<?php echo number_format($montoche,2,'.',',') ?>" <?php echo $lectura; ?>size="10" maxlength="20" /></td><tr>  
	 
   </tr>
</table>
	
		&nbsp;</td></tr> 

<?php 
}
?>
<?php 
function pantalla_act_mo4($result,$accionIn,$codigo,$nombre,$numero,$beneficiario,$estatus,$concepto,$concepto1,$fecha,$observacion,$cod_cont, $montoche,$cod_banco){
$deci=$_SESSION['deci'];
$sep_decimal=$_SESSION['sep_decimal'];
$sep_miles=$_SESSION['sep_miles'];
$fila999 = mysql_fetch_assoc($result);
//echo $sql; 
if ($accionIn == 'VerificarMo1') { $lectura = 'readonly = "readonly"'; $activada="disabled" ; } else {$lectura=''; $activada='';}
?>
 <label><fieldset><legend>ACTUALIZACIÓN DE CHEQUES del Banco <?php echo $nombre?></legend>
  	<table width="500" border="3">
	<td class= "blanco b" colspan='5' width="50" style="text-align:center" bgcolor="#FFFFCC">Encabezado del Cheque <tr>
		
	  <td class= "blanco b" width="50" bgcolor="#FFFFCC">Nro. de Cuenta<td>
	<input name="codigo" type="text" id="codigo" value="<?php echo $codigo ?>" <?php echo $lectura; ?>size="20" maxlength="20" /></td>
		
	  <td class= "blanco b" width="50" bgcolor="#FFFFCC">Nro. de Cheque<td>
	 <input name="numero" type="text" id="numero" value="<?php echo $numero; ?>" <?php  echo $lectura; ?>size="8" maxlength="8" /></td>
	 	 <td rowspan='5'><textarea name="observacion" type="text" id="observacion" onChange="conMayusculas(this)" <?php  echo $lectura; ?> cols="35" rows="04" maxlength="125"/> <?php echo $observacion;?></textarea></td><tr>
		 
		 </td><tr>
	 
	  <td class= "blanco b" width="50" bgcolor="#FFFFCC">Beneficiario<td  colspan='3'>
	 <input name="beneficiario" type="text" id="beneficiario" value="<?php echo $beneficiario?>" <?php echo $lectura; ?>size="65" maxlength="65" /></td><tr>
	 
		 <td class= "blanco b" width="50" bgcolor="#FFFFCC">Estatus<td>
	<input type="radio" name="estatus" value="L" checked/> Listo</label>
	  <td class= "blanco b" width="50" bgcolor="#FFFFCC">Fecha<td>
	 <input name="fecha" type="text" id="fecha" value="<?php echo $fecha ?>" <?php echo $lectura; ?>size="10" maxlength="20" /></td><tr>
	 <input type="hidden" name="cod_cont" value="<?php echo $cod_cont;?>">
	 <td class= "blanco b" width="50" bgcolor="#FFFFCC">Concepto<td>
	 <input name="concepto1" type="text" id="concepto1" value="<?php echo $concepto1;?>" <?php echo $lectura;  ?>size="46" maxlength="20" /></td>
	  <td class= "blanco b" width="50" bgcolor="#FFFFCC">Monto<td>
	 <input name="montoche" type="text" id="montoche"  style="text-align:right" value="<?php echo number_format($montoche,2,'.',',') ?>" <?php echo $lectura; ?>size="10" maxlength="20" /></td><tr>  </tr>
	 <input type="hidden" name="concepto" value="<?php echo $concepto;?>">
	 <input type="hidden" name="cod_banco" value="<?php echo $cod_banco;?>">
   </div></body></html>
<td>
</table>
	&nbsp;</td></tr> 
<?php
}
?>
