<?php
include("head.php");
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

if ($accion == 'Anadir2') {
    //echo '1'; 
	extract($_POST);
	$codigo = $_POST['codigo'];
	$com_fechamysql=convertir_fecha($fech_reg);
    //echo $nactivo; 
	if ($codigo) {
	//echo '2'; 
	    $sql = "select * from sgcaf845 where nro_ban= '$codigo' and  desde= '$desde' and nro_reg='$nregistro'";
		$result=mysql_query($sql);
		//echo $sql; 
		if (mysql_num_rows($result) > 0) die ('No se puede registrar esta Chequera ya existe');
        $sql="INSERT INTO sgcaf845(nro_ban, desde, hasta, fech_reg, ip, status) 
		VALUES ('$codigo', '$desde', '$hasta', '$com_fechamysql', '$ip', '$status')";
		//echo $sql;
		mysql_query($sql)or die ("<p />El usuario $usuario no tiene permisos para añadir Clientes o cuenta ya existente.<br>".mysql_error()."<br>".$sql);
		echo 'REGISTRO REALIZADO EXITOSAMENTE'; 
		$codigo="";
		$accion="";
		}
}

if ($accion=="Borrar_Cheque") {
   // echo '<div id="div1">';
	echo "<form action='chequeras.php?accion=Editar&a=Borrar_Cheque1&codigo=".$codigo."&nregistro=".$nregistro."' name='form1' method='post' onsubmit='return explicacion_cheque(form1)'>";
	$sql= "SELECT *FROM sgcaf843";
	$result=mysql_query($sql);
	echo 'hola'; 
	echo $nregistro; 
    pantalla_act_borrar($result,$accion,$codigo,$numero,$nombre,$nregistro);
	echo "<input type = 'submit' value = 'Eliminar'>";
	//echo '</div>';
}

if ($accion=="Borrar_ChequeTodos") {
   // echo '<div id="div1">';
	echo "<form action='chequeras.php?accion=Borrar_ChequeTodos1' name='form1' method='post' onsubmit='return explicacion_cheque(form1)'>";
	$sql= "SELECT *FROM sgcaf843";
	$result=mysql_query($sql);
	echo 'hola'; 
	echo $nregistro; 
    pantalla_act_borrar_todos($result,$accion,$codigo,$nombre,$nregistro);
	echo "<input type = 'submit' value = 'Eliminar'>";
	//echo '</div>';
}


if ($accion=="Reutilizar") {
   // echo '<div id="div1">';
	echo "<form action='chequeras.php?accion=Editar&a=Reutilizar1&codigo=".$codigo."&codigon=".$codigon."&nregistro=".$nregistro."' name='form1' method='post' onsubmit='return explicacion_cheque(form1)'>";
	$sql= "SELECT *FROM sgcaf843";
	$result=mysql_query($sql);
	$nro=$codigo; 
	echo 'hola'; 
	echo $nregistro; 
    pantalla_act_reutilizar($result,$accion,$codigo,$numero,$nombre,$nregistro);
	echo "<input type = 'submit' value = 'Reutilizar'>";
	//echo '</div>';
}

if ($accion=="ReutilizarTodos") {
   // echo '<div id="div1">';
	//$sql= "SELECT *FROM sgcaf843";
	//$result=mysql_query($sql);
	$nro=$codigo; 
	//echo 'hola'; 
	echo $n_registros; 
		$sql="SELECT count(banco) as cuantos, banco, registro, nombre_ban FROM sgcaf846, sgcaf843 where estatus='+' and registro<>'$nregistro' and banco=nro_cta_ba and estado='1' and emitircheque='1' group by registro";
//		echo $sql; 
		    $a='0'; 
			$resultado=mysql_query($sql);
			while ($fila2 = mysql_fetch_assoc($resultado)) {
			if ($fila2['cuantos'] >= $n_registros)
			{
			$a=$a +1; 
			}
			}
			echo $cuantos; 
		if ($a=='0')
		{
		echo '<h2> NO hay banco con esta cantidad de cheques disponibles </ h2>';
		$accion='Editar'; 
		$codigo=$codigo; 
		$nregistro= $nregistro; 
		}
		if ($a<>'0')
		{
		echo "<form action='chequeras.php?accion=ReutilizarTodos1' name='form1' method='post' onsubmit='return explicacion_cheque(form1)'>";
		pantalla_act_reutilizartodos($result,$accion,$numero,$codigo,$nregistro);
		echo "<input type = 'submit' value = 'Reutilizar'>";
		}	 
	
}

if ($accion == 'Desactivar1') {
	extract($_POST);
	$codigo= $_POST['codigo'];
	if ($status == '+')
	{
	$accion='';
	}
	else if ($status == '-') {  
	$sql="UPDATE sgcaf845 SET status='$status', explicacion='Completada' WHERE nro_ban='$codigo' and nro_reg='$nregistro'";
    	// echo $sql;
		mysql_query($sql) or die ("<p />El usuario $usuario no tiene permisos para modificar Clientes <br>".mysql_error()."<br>".$sql);
	}
	$accion='';
}


if ($accion == 'Desactivar4') {
	extract($_POST);
	$codigo= $_POST['codigo'];
	if ($status == '+')
	{
	$accion='';
	}
	else if ($status == '-') {
	$sql="UPDATE sgcaf845 SET status='$status', explicacion='$explicacion' WHERE nro_ban='$codigo' and nro_reg='$nregistro'";
    	// echo $sql;
		mysql_query($sql) or die ("<p />El usuario $usuario no tiene permisos para modificar Clientes <br>".mysql_error()."<br>".$sql);
		$accion='';
		$x='2';
}
if ($x=='2') 
{  
  $sql = "select * from sgcaf845 where nro_ban= '$codigo' and  status= '-' and nro_reg='$nregistro'";
		$result=mysql_query($sql);
		$filazzz = mysql_fetch_assoc($result);
		// echo $sql;
		$max=$filazzz['hasta'];
		$min= $filazzz['desde'];
	while ($min <= $max){
		$v= ceroizq ($min,8); 
		$min=$min+1;
		$sql="UPDATE sgcaf846 SET estatus='$status', descrip='Inhabilitados' WHERE banco='$codigo' and estatus='+'";
    	// echo $sql;
		mysql_query($sql) or die ("<p />El usuario $usuario no tiene permisos para modificar Clientes <br>".mysql_error()."<br>".$sql);
		}
		}
		$accion='';
}


///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

if ($accion == 'Editar1') {
	extract($_POST);
	$codigo= $_POST['codigo'];
	$sql="UPDATE sgcaf845 SET status='$status' WHERE nro_ban='$codigo' and nro_reg='$nregistro'";
	mysql_query($sql) or die ("<p />El usuario $usuario no tiene permisos para modificar Clientes <br>".mysql_error()."<br>".$sql);
	$accion='';
	$x='1';
}
if ($x=='1') 
{  
	$sql = "select * from sgcaf845 where nro_ban= '$codigo' and  status= '+' and nro_reg='$nregistro'";
	$result=mysql_query($sql);
	$filaxxx = mysql_fetch_assoc($result);
	// echo $sql;
	$max=$filaxxx['hasta'];
	$min= $filaxxx['desde'];
	while ($min <= $max){
		$status= '+';
		$v= ceroizq ($min,8); 
		$min=$min+1;
		$sql="INSERT INTO sgcaf846(banco, nro_che, estatus, registro) 
		VALUES ('$codigo', '$v', '$status', '$nregistro')";
		// echo $sql;
		mysql_query($sql)or die ("<p />El usuario $usuario no tiene permisos para añadir Clientes o cuenta ya existente.<br>".mysql_error()."<br>".$sql);}	
}
?>
<?php
if ($accion == 'Borrar_ChequeTodos1')
 {   
    $hoy = date("d/m/Y");
	$fecha=convertir_fecha($hoy);
/*
	echo $codigo;
	echo $nregistro;
	echo $explicacion; 
*/
	$sql="SELECT * FROM sgcaf845, sgcaf846, sgcaf840, sgcaf843 where nro_ban='$codigo' and nro_cta_ba=nro_ban and nro_reg='$nregistro' and estatus='-' and banco=nro_ban and nro_che=mche_orden and mche_banco=cod_banco and mche_statu='L' and emitircheque='1' and estado='1'"; 
	$rs=mysql_query($sql);
	// echo $sql; 
	while($row=mysql_fetch_array($rs)) {
    
	$numero=$row['mche_orden'];  
/////////////////
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
	// echo $sql;  
    $sql="INSERT INTO sgcaf840E(mche_orden,mche_fecha,mche_nombr,mche_monto,mche_descr,mche_statu,mche_banco,mche_prest, ip, mche_observacion, fecha_borrar) 
	VALUES ('$numero','$mche_fecha','$mche_nombr','$mche_monto','$mche_descr','$mche_statu','$mche_banco','$mche_prest', '$ip', '$explicacion', '$fecha')";
	// echo $sql;
	mysql_query($sql)or die ("<p />El usuario $usuario no tiene permisos para añadir Clientes o cuenta ya existente.<br>".mysql_error()."<br>".$sql);
	mysql_query("DELETE FROM sgcaf840 WHERE mche_orden='$numero' and mche_banco='$mche_banco'") or die ("<p />El usuario $usuario no tiene permisos para borrar Cuentas");
	
	$sql="UPDATE sgcaf846 SET descrip='', ip='', fecha='', estatus='+'
		WHERE nro_che='$numero' and banco='$codigo'";
		// echo $sql;
		mysql_query($sql)or die ("<p />El usuario $usuario no tiene permisos para añadir Clientes o cuenta ya existente.<br>".mysql_error()."<br>".$sql);
		
	$sql="SELECT * FROM sgcaf841 WHERE mche_banco = '$mche_banco' and mche_orden= '$numero'";
	$resultado=mysql_query($sql);  
	// echo $sql; 
	while($row1=mysql_fetch_array($resultado)) 
	{
	$mche_cuent=$row1['mche_cuent']; 
	$mche_debcr=$row1['mche_debcr']; 
	$mche_descr=$row1['mche_descr']; 
	$mche_monto1=$row1['mche_monto1']; 
	$mche_monto2=$row1['mche_monto2']; 
	$mche_monto=$row1['mche_monto']; 
	$mche_banco=$row1['mche_banco']; 
	$nregistro_original=$row1['registro']; 
	// echo $sql; 
	$sql="INSERT INTO sgcaf841E(mche_orden,mche_cuent,mche_debcr,mche_descr,mche_monto1,mche_monto2,mche_monto,mche_banco, registro_original) 
	VALUES ('$numero','$mche_cuent','$mche_debcr','$mche_descr','$mche_monto1','$mche_monto2','$mche_monto','$mche_banco', '$nregistro_original')";
	mysql_query($sql)or die ("<p />El usuario $usuario no tiene permisos para añadir Clientes o cuenta ya existente.<br>".mysql_error()."<br>".$sql);
	}
	mysql_query("DELETE FROM sgcaf841 WHERE mche_orden='$numero' and mche_banco='$mche_banco'") or die ("<p />El usuario $usuario no tiene permisos para borrar Cuentas");
    $accion='';
	
	}
}
?> 
<?php
if ($accion == 'ReutilizarTodos1')
 {   
//  echo 'hola';
 $sql= "SELECT *FROM sgcaf846 where registro='$codigon'";
	// echo $sql; 
	$result=mysql_query($sql);
    $fila666 = mysql_fetch_assoc($result);
$codigov=$codigo; 
$codigo=$fila666['banco'];
// echo $nregistro; 
     $sql="SELECT * FROM sgcaf845, sgcaf846, sgcaf840, sgcaf843 where nro_ban='$codigov' and nro_cta_ba=nro_ban and nro_reg='$nregistro' and nro_reg=registro and estatus='-' and banco=nro_ban and nro_che=mche_orden and mche_banco=cod_banco and mche_statu='L' and emitircheque='1'"; 
 
// echo $sql; 
	$result2=mysql_query($sql);
	while($row1=mysql_fetch_array($result2)) 
///////// 
{
    $numero=$row1['mche_orden'];
    $hoy = date("d/m/Y");
	$fecha=convertir_fecha($hoy); 
	echo $codigov; 
	echo $numero; 
	$sql= "SELECT *FROM sgcaf843 where nro_cta_ba='$codigo' and emitircheque='1'";
	// echo $sql; 
	$result=mysql_query($sql);
    $fila666 = mysql_fetch_assoc($result);
	$cue_banco= $fila666['cue_banco']; 
	$cod_banco= $fila666['cod_banco'];
	$nombre= $fila666['nombre_ban'];
	
$sql= "SELECT * FROM sgcaf846 where banco='$codigo' and registro='$codigon' and estatus='+' and descrip='' order by nro_che";
	// echo $sql; 
	$result=mysql_query($sql);
    $fila99 = mysql_fetch_assoc($result);
	$nche=$fila99['nro_che']; 
	// echo '1'; 
	$sql="SELECT * FROM sgcaf840, sgcaf843 where mche_orden='$numero' and nro_cta_ba='$codigov' and cod_banco=mche_banco and emitircheque='1'"; 
	echo "<p />";
	$result=mysql_query($sql); 
	$b= mysql_fetch_assoc($result);
	$nombre1= $b['nombre_ban']; 
	$cue_bancov= $b['cue_banco']; 
	$mche_nombr = $b['mche_nombr']; 
	$mche_monto= $b['mche_monto']; 
	$mche_descr= $b['mche_descr'];
	$mche_statu= $b['mche_statu']; 
	$mche_banco= $b['mche_banco']; 
	$mche_prest= $b['mche_prest']; 
	$mche_fecha= $b['mche_fecha'];  
	$sustituto= $b['sustituto'];
/*
	echo $sql; 
	echo "<p />";
	echo'2'; 
	echo 'hello'; 
*/
	
	$sql="INSERT INTO sgcaf840(mche_orden,mche_fecha,mche_nombr,mche_monto,mche_descr,mche_statu,mche_banco,mche_prest) 
	VALUES ('$nche','$mche_fecha','$mche_nombr','$mche_monto','$mche_descr (EN SUSTITUCION DEL CHEQUE NRO $numero del Banco $nombre1 ) ','$mche_statu','$cod_banco','$mche_prest')";
/*
	echo $sql;
	echo "<p />";
*/
	mysql_query($sql)or die ("<p />El usuario $usuario no tiene permisos para añadir Clientes o cuenta ya existente.<br>".mysql_error()."<br>".$sql);
	
//	echo '3';
	$sql="UPDATE sgcaf840 SET mche_statu='A', sustituto='$nche'
		WHERE mche_orden='$numero' and mche_banco='$mche_banco'";
/*
		echo $sql;
		echo "<p />";
*/
		mysql_query($sql)or die ("<p />El usuario $usuario no tiene permisos para añadir Clientes o cuenta ya existente.<br>".mysql_error()."<br>".$sql);
	
	$sql="INSERT INTO sgcaf840E(mche_orden,mche_fecha,mche_nombr,mche_monto,mche_descr,mche_statu,mche_banco,mche_prest, ip, mche_observacion, fecha_borrar, sustituto) 
	VALUES ('$numero','$mche_fecha','$mche_nombr','$mche_monto','$mche_descr','$mche_statu','$mche_banco','$mche_prest', '$ip', '$explicacion', '$fecha', '$nche')";
/*
	echo $sql;
	echo "<p />";
*/
	mysql_query($sql)or die ("<p />El usuario $usuario no tiene permisos para añadir Clientes o cuenta ya existente.<br>".mysql_error()."<br>".$sql);
	
	
	$sql="UPDATE sgcaf846 SET descrip='CHEQUE ANULADO SUSTITUIDO POR EL CHEQUE NRO. $nche del Banco $nombre'
		WHERE nro_che='$numero' and banco='$codigov'";
		mysql_query($sql)or die ("<p />El usuario $usuario no tiene permisos para añadir Clientes o cuenta ya existente.<br>".mysql_error()."<br>".$sql);
		
	$sql="SELECT * FROM sgcaf841 WHERE mche_banco = '$mche_banco' and mche_orden= '$numero'";
	$resultado1=mysql_query($sql);  
	while($row1=mysql_fetch_array($resultado1)) 
	{
	$mche_cuent=$row1['mche_cuent']; 
	$mche_debcr=$row1['mche_debcr']; 
	$mche_descr=$row1['mche_descr']; 
	$mche_monto1=$row1['mche_monto1']; 
	$mche_monto2=$row1['mche_monto2']; 
	$mche_monto=$row1['mche_monto']; 
	$mche_banco=$row1['mche_banco']; 
	$nregistro_original=$row1['registro']; 
	$sql="INSERT INTO sgcaf841E(mche_orden,mche_cuent,mche_debcr,mche_descr,mche_monto1,mche_monto2,mche_monto,mche_banco, registro_original) 
	VALUES ('$numero','$mche_cuent','$mche_debcr','$mche_descr','$mche_monto1','$mche_monto2','$mche_monto','$mche_banco', '$nregistro_original')";
	mysql_query($sql)or die ("<p />El usuario $usuario no tiene permisos para añadir Clientes o cuenta ya existente.<br>".mysql_error()."<br>".$sql);
	}
	
	$sql="UPDATE sgcaf846 SET descrip='$mche_nombr', ip='$ip', fecha='$mche_fecha', estatus='-'
				WHERE nro_che='$nche' and banco='$codigo'";
				mysql_query($sql)or die ("<p />El usuario $usuario no tiene permisos para añadir Clientes o cuenta ya existente.<br>".mysql_error()."<br>".$sql);
	 
	$sql="SELECT * FROM sgcaf841 WHERE mche_banco = '$mche_banco' and mche_orden= '$numero'";
	$resultado1=mysql_query($sql);  
	while($row1=mysql_fetch_array($resultado1)) 
	{
	$mche_cuent=$row1['mche_cuent']; 
	$mche_debcr=$row1['mche_debcr']; 
	$mche_descr=$row1['mche_descr']; 
	$mche_monto1=$row1['mche_monto1']; 
	$mche_monto2=$row1['mche_monto2']; 
	$mche_monto=$row1['mche_monto']; 
	$mche_banco=$row1['mche_banco']; 
	if ($cue_bancov==$mche_cuent) 
	{ 
	echo $mche_cuent=$cue_banco;
	}
	else {
	echo $mche_cuent=$row1['mche_cuent']; 
	}
	$sql="INSERT INTO sgcaf841(mche_orden,mche_cuent,mche_debcr,mche_descr,mche_monto1,mche_monto2,mche_monto,mche_banco) 
	VALUES ('$nche','$mche_cuent','$mche_debcr','$mche_descr','$mche_monto1','$mche_monto2','$mche_monto','$cod_banco')";
	mysql_query($sql)or die ("<p />El usuario $usuario no tiene permisos para añadir Clientes o cuenta ya existente.<br>".mysql_error()."<br>".$sql);
	}

	}
		$accion='Editar';
	//echo '</div>';
	$codigo=$codigov; 
}
?> 
<?php
if (!$accion) {
//	echo "<div id='div1'>";
    echo "<table class='basica 100 hover' width=''><tr>";
	echo '<th>  </th><th><a href=?ord=nombre_ban>Nombres</a></th><th><a href=?ord=nro_cta_ba>Nro. de Cuenta</a><br>';
	echo '[ <a href="chequeras.php?accion=Anadir"> Nuevo Chequera</a> ]</a><br>';
	echo '<th>Desde</a></th><th>Hasta</a><th>Estatus</a><th>Total</a><th>Utilizados</a><br>';
	echo '</th></th>';
	$ord = $_GET['ord'];
	if (!$ord) $ord='cod_banco';
	$sql = "SELECT * FROM sgcaf843, sgcaf845 where nro_cta_ba = nro_ban and emitircheque='1' ORDER BY $ord";
	$rs = mysql_query($sql);

// bucle de listado

	while($row=mysql_fetch_array($rs)) {
		echo "<tr>";
		if ($row['explicacion']=='Completada') 
		{
		echo "<td class='centro'>";
		echo "</a></td>"; 
		}
		else {
		echo "<td><a href='chequeras.php?accion=Editar&codigo=".$row['nro_cta_ba']."&nregistro=".$row['nro_reg']."'><img src='imagenes/16-em-pencil.png' width='16' height='16' border='0' title='Editar' alt='Editar' /></a></td>"; 
		}
		echo "<td class='centro'>";
		echo $row['nombre_ban']."</a></td>";
		echo "<td class='centro'>";
		echo $row['nro_cta_ba']."</a></td>";
		echo "<td class='centro'>";
		echo $row['desde']."</a></td>";
		echo "<td class='centro'>";
		echo $row['hasta']."</a></td>";
		echo "<td class='centro'>";
		echo $row['status']."</a></td>";
		echo "<td class='centro'>";
		$total= $row['hasta']-$row['desde']+1;
		echo $total."</a></td>";
		$sql = "SELECT count(nro_che) AS cuantos FROM sgcaf846 where banco='".$row['nro_cta_ba']."' and registro='".$row['nro_reg']."' and estatus='-'"; 
		$result = mysql_query($sql);
	    $f = mysql_fetch_assoc($result);
	    echo "<td class='centro'>";
		echo $f['cuantos']."</a></td>";
	}
	echo "</table>";

//	echo "</div>";
}
?>
<?php 
if ($accion=='Anadir') {
//	echo "<div id='div1'>";
    echo '<div id="div1">';
	echo "<form action='chequeras.php?accion=Anadir1' name='form1' method='post' onsubmit='return valche(form1)'>";
	$sql= "SELECT *FROM sgcaf845 where nro_ban='$codigo' order by nro_reg DESC";
	//echo $nombre; 
	//echo $codigo;
	$result=mysql_query($sql);
    pantalla_act($result,$accion,$codigo,$nombre);
	echo "<input type = 'submit' value = 'Grabar Datos'>";
	echo '</div>';
// 	echo "</form>\n";
}
if ($accion=='Anadir1') {
//	echo "<div id='div1'>";
    echo '<div id="div1">';
	echo "<form action='chequeras.php?accion=Anadir2' name='form1' method='post' onsubmit='return valche(form1)'>";
	$sql= "SELECT *FROM sgcaf845 where nro_ban='$codigo' order by nro_reg DESC";
	echo '<input type="hidden" name="codigo" value="'.$codigo.'">';
	echo '<input type="hidden" name="nombre" value="'.$nombre.'">';
	//echo $nombre; 
	//echo $codigo;
	$result=mysql_query($sql);
    pantalla_act2($result,$accion,$codigo,$nombre);
	echo "<input type = 'submit' value = 'Grabar Datos'>";
	echo '</div>';
// 	echo "</form>\n";
}
//////////////////////


//////////////
if ($accion == "Editar") {
	echo '<div id="div1">';
	///////////////////////////////////
	if ($a == 'Borrar_Cheque1')
    {   
    $hoy = date("d/m/Y");
	$fecha=convertir_fecha($hoy);
	$sql="SELECT * FROM sgcaf840, sgcaf843 where mche_orden='$numero' and nro_cta_ba='$codigo' and cod_banco=mche_banco and emitircheque='1'"; 
	$result=mysql_query($sql); 
	$b= mysql_fetch_assoc($result);
	$mche_nombr= $b['mche_nombr']; 
	$mche_monto= $b['mche_monto']; 
	$mche_descr= $b['mche_descr'];
	$mche_statu= $b['mche_statu']; 
	$mche_banco= $b['mche_banco']; 
	$mche_prest= $b['mche_prest']; 
	$mche_fecha= $b['mche_fecha'];  
    $sql="INSERT INTO sgcaf840E(mche_orden,mche_fecha,mche_nombr,mche_monto,mche_descr,mche_statu,mche_banco,mche_prest, ip, mche_observacion, fecha_borrar) 
	VALUES ('$numero','$mche_fecha','$mche_nombr','$mche_monto','$mche_descr','$mche_statu','$mche_banco','$mche_prest', '$ip', '$explicacion', '$fecha')";
	mysql_query($sql)or die ("<p />El usuario $usuario no tiene permisos para añadir Clientes o cuenta ya existente.<br>".mysql_error()."<br>".$sql);
	mysql_query("DELETE FROM sgcaf840 WHERE mche_orden='$numero' and mche_banco='$mche_banco'") or die ("<p />El usuario $usuario no tiene permisos para borrar Cuentas");
	
	$sql="UPDATE sgcaf846 SET descrip='', ip='', fecha='', estatus='+'
		WHERE nro_che='$numero' and banco='$codigo'";
		mysql_query($sql)or die ("<p />El usuario $usuario no tiene permisos para añadir Clientes o cuenta ya existente.<br>".mysql_error()."<br>".$sql);
		
	$sql="SELECT * FROM sgcaf841 WHERE mche_banco = '$mche_banco' and mche_orden= '$numero'";
	$resultado=mysql_query($sql);  
	while($row1=mysql_fetch_array($resultado)) 
	{
	$mche_cuent=$row1['mche_cuent']; 
	$mche_debcr=$row1['mche_debcr']; 
	$mche_descr=$row1['mche_descr']; 
	$mche_monto1=$row1['mche_monto1']; 
	$mche_monto2=$row1['mche_monto2']; 
	$mche_monto=$row1['mche_monto']; 
	$mche_banco=$row1['mche_banco']; 
	$nregistro_original=$row1['registro']; 
	$sql="INSERT INTO sgcaf841E(mche_orden,mche_cuent,mche_debcr,mche_descr,mche_monto1,mche_monto2,mche_monto,mche_banco, registro_original) 
	VALUES ('$numero','$mche_cuent','$mche_debcr','$mche_descr','$mche_monto1','$mche_monto2','$mche_monto','$mche_banco', '$nregistro_original')";
	mysql_query($sql)or die ("<p />El usuario $usuario no tiene permisos para añadir Clientes o cuenta ya existente.<br>".mysql_error()."<br>".$sql);
	}
	mysql_query("DELETE FROM sgcaf841 WHERE mche_orden='$numero' and mche_banco='$mche_banco'") or die ("<p />El usuario $usuario no tiene permisos para borrar Cuentas");
    $accion='Editar';
	
}
/////////////////////////////777
if ($a == "Reutilizar1")
{

$sql= "SELECT *FROM sgcaf846 where registro='$codigon'";
	$result=mysql_query($sql);
    $fila666 = mysql_fetch_assoc($result);
$codigov=$codigo; 
$codigo=$fila666['banco'];
 $hoy = date("d/m/Y");
	$fecha=convertir_fecha($hoy); 
	echo $codigov; 
	echo $numero; 
	$sql= "SELECT *FROM sgcaf843 where nro_cta_ba='$codigo' and emitircheque='1'";
	$result=mysql_query($sql);
    $fila666 = mysql_fetch_assoc($result);
	$cue_banco= $fila666['cue_banco']; 
	$cod_banco= $fila666['cod_banco'];
	$nombre= $fila666['nombre_ban'];
	
$sql= "SELECT * FROM sgcaf846 where banco='$codigo' and estatus='+' and descrip='' order by nro_che";
	$result=mysql_query($sql);
    $fila99 = mysql_fetch_assoc($result);
	$nche=$fila99['nro_che']; 
	$sql="SELECT * FROM sgcaf840, sgcaf843 where mche_orden='$numero' and nro_cta_ba='$codigov' and cod_banco=mche_banco and emitircheque='1'"; 
	$result=mysql_query($sql); 
	$b= mysql_fetch_assoc($result);
	$nombre1= $b['nombre_ban']; 
	$cue_bancov= $b['cue_banco']; 
	$mche_nombr = $b['mche_nombr']; 
	$mche_monto= $b['mche_monto']; 
	$mche_descr= $b['mche_descr'];
	$mche_statu= $b['mche_statu']; 
	$mche_banco= $b['mche_banco']; 
	$mche_prest= $b['mche_prest']; 
	$mche_fecha= $b['mche_fecha'];  
	$sustituto= $b['sustituto'];
	
	$sql="INSERT INTO sgcaf840(mche_orden,mche_fecha,mche_nombr,mche_monto,mche_descr,mche_statu,mche_banco,mche_prest) 
	VALUES ('$nche','$mche_fecha','$mche_nombr','$mche_monto','$mche_descr (EN SUSTITUCION DEL CHEQUE NRO $numero del Banco $nombre1 ) ','$mche_statu','$cod_banco','$mche_prest')";
	echo "<p />";
	mysql_query($sql)or die ("<p />El usuario $usuario no tiene permisos para añadir Clientes o cuenta ya existente.<br>".mysql_error()."<br>".$sql);
	
	$sql="UPDATE sgcaf840 SET mche_statu='A', sustituto='$nche'
		WHERE mche_orden='$numero' and mche_banco='$mche_banco'";
		mysql_query($sql)or die ("<p />El usuario $usuario no tiene permisos para añadir Clientes o cuenta ya existente.<br>".mysql_error()."<br>".$sql);
	
	$sql="INSERT INTO sgcaf840E(mche_orden,mche_fecha,mche_nombr,mche_monto,mche_descr,mche_statu,mche_banco,mche_prest, ip, mche_observacion, fecha_borrar, sustituto) 
	VALUES ('$numero','$mche_fecha','$mche_nombr','$mche_monto','$mche_descr','$mche_statu','$mche_banco','$mche_prest', '$ip', '$explicacion', '$fecha', '$nche')";
	mysql_query($sql)or die ("<p />El usuario $usuario no tiene permisos para añadir Clientes o cuenta ya existente.<br>".mysql_error()."<br>".$sql);
	
	
	$sql="UPDATE sgcaf846 SET descrip='CHEQUE ANULADO SUSTITUIDO POR EL CHEQUE NRO. $nche del Banco $nombre'
		WHERE nro_che='$numero' and banco='$codigov'";
		mysql_query($sql)or die ("<p />El usuario $usuario no tiene permisos para añadir Clientes o cuenta ya existente.<br>".mysql_error()."<br>".$sql);
		
	$sql="SELECT * FROM sgcaf841 WHERE mche_banco = '$mche_banco' and mche_orden= '$numero'";
	$resultado1=mysql_query($sql);  
	while($row1=mysql_fetch_array($resultado1)) 
	{
	$mche_cuent=$row1['mche_cuent']; 
	$mche_debcr=$row1['mche_debcr']; 
	$mche_descr=$row1['mche_descr']; 
	$mche_monto1=$row1['mche_monto1']; 
	$mche_monto2=$row1['mche_monto2']; 
	$mche_monto=$row1['mche_monto']; 
	$mche_banco=$row1['mche_banco']; 
	$nregistro_original=$row1['registro']; 
	$sql="INSERT INTO sgcaf841E(mche_orden,mche_cuent,mche_debcr,mche_descr,mche_monto1,mche_monto2,mche_monto,mche_banco, registro_original) 
	VALUES ('$numero','$mche_cuent','$mche_debcr','$mche_descr','$mche_monto1','$mche_monto2','$mche_monto','$mche_banco', '$nregistro_original')";
	mysql_query($sql)or die ("<p />El usuario $usuario no tiene permisos para añadir Clientes o cuenta ya existente.<br>".mysql_error()."<br>".$sql);
	}
	
	$sql="UPDATE sgcaf846 SET descrip='$mche_nombr', ip='$ip', fecha='$mche_fecha', estatus='-'
				WHERE nro_che='$nche' and banco='$codigo'";
				echo $sql;
				mysql_query($sql)or die ("<p />El usuario $usuario no tiene permisos para añadir Clientes o cuenta ya existente.<br>".mysql_error()."<br>".$sql);
	
		$sql="SELECT * FROM sgcaf841 WHERE mche_banco = '$mche_banco' and mche_orden= '$numero'";
	$resultado1=mysql_query($sql);  
	while($row1=mysql_fetch_array($resultado1)) 
	{
	$mche_cuent=$row1['mche_cuent']; 
	$mche_debcr=$row1['mche_debcr']; 
	$mche_descr=$row1['mche_descr']; 
	$mche_monto1=$row1['mche_monto1']; 
	$mche_monto2=$row1['mche_monto2']; 
	$mche_monto=$row1['mche_monto']; 
	$mche_banco=$row1['mche_banco']; 
	echo '</ p>'; 
	echo $cue_bancov; 
	echo $mche_cuent;  
	echo '</ p>'; 
	if ($cue_bancov==$mche_cuent) 
	{ 
	echo $mche_cuent=$cue_banco;
	}
	else 
	{
	echo $mche_cuent=$row1['mche_cuent']; 
	}
	
	$sql="INSERT INTO sgcaf841(mche_orden,mche_cuent,mche_debcr,mche_descr,mche_monto1,mche_monto2,mche_monto,mche_banco) 
	VALUES ('$nche','$mche_cuent','$mche_debcr','$mche_descr','$mche_monto1','$mche_monto2','$mche_monto','$cod_banco')";
	mysql_query($sql)or die ("<p />El usuario $usuario no tiene permisos para añadir Clientes o cuenta ya existente.<br>".mysql_error()."<br>".$sql);
	}
	$accion='Editar';
	//echo '</div>';
	$codigo=$codigov; 
}

//////////////////////////////////

	$sql="SELECT * FROM sgcaf845, sgcaf843 where nro_ban= '$codigo' and nro_cta_ba= '$codigo' and nro_reg='$nregistro' and emitircheque='1'"; 
	$resultado = mysql_query($sql);
	$fila22= mysql_fetch_assoc($resultado);
	$sql="SELECT * FROM sgcaf845, sgcaf843 where nro_ban= '$codigo' and nro_cta_ba= '$codigo' and nro_reg='$nregistro' and emitircheque='1'"; 
	$result = mysql_query($sql);
	
	if ($fila22['status']=='+') 
	{ 
		/////////////////////////////////////////////////
	   $sql="SELECT * FROM sgcaf845, sgcaf846 where nro_ban='$codigo' and nro_reg='$nregistro' and nro_ban=banco and nro_che>=desde and nro_che<=hasta and estatus='-'"; 
	$result1=mysql_query($sql);
		if (mysql_num_rows($result1) == 0)
		{
//		echo 'A'; 
		echo "<form enctype='multipart/form-data' action='chequeras.php?accion=Desactivar4' name='form1' method='post''>";  // onsubmit='return valban(form1)'>";
		pantalla_act4($result,$accion,$codigo,$nregistro);
    	echo "<br><input type = 'submit' value = 'Confirmar cambios'></form>\n";
		}
		else {
		
		$sql="SELECT * FROM sgcaf845, sgcaf846, sgcaf840, sgcaf843 where nro_ban='$codigo' and nro_cta_ba=nro_ban and nro_reg='$nregistro' and nro_reg=registro and estatus='-' and banco=nro_ban and nro_che=mche_orden and mche_banco=cod_banco and mche_statu='L' and emitircheque='1'"; 
	    $result2=mysql_query($sql);
		$n_registros=mysql_num_rows($result2);
			//echo $sql; 
		if (mysql_num_rows($result2) == 0)
		{
	 	echo "<form enctype='multipart/form-data' action='chequeras.php?accion=Desactivar1' name='form1' method='post'>"; // onsubmit='return valban(form1)'>";
		pantalla_act3($result,$accion, $codigo, $nregistro);
    	echo "<br><input type = 'submit' value = 'Confirmar cambios'></form>\n";
		}
		else 
		{
		$sql="SELECT nombre_ban FROM sgcaf843 where nro_cta_ba='$codigo' and emitircheque='1'"; 
		$res = mysql_query($sql);
	    $fss= mysql_fetch_assoc($res);
		$nombre= $fss['nombre_ban']; 
		 if ($n_registros > 1) 
		 {
		echo "<a href='chequeras.php?accion=Borrar_ChequeTodos&codigo=".$codigo."&nombre=".$nombre."&nregistro=".$nregistro."'''>Borrar Cheques</a>";
        echo "&nbsp;&nbsp;&nbsp; - &nbsp;&nbsp;&nbsp;<a href='chequeras.php?accion=ReutilizarTodos&codigo=".$codigo."&nombre=".$nombre."&nregistro=".$nregistro."&n_registros=".$n_registros."'>Reutilizar Cheques</a><p />";
		}
		echo "<p />";
		echo '<H2> NO SE PUEDE DESACTIVAR ESTA CHEQUERA </H2>'; 
		echo '<H2> EXISTE '.$n_registros.' CHEQUE LISTO </H2>';
			
		pantalla_reutilizar($result2,$accion,$codigo,$nombre,$nro);
		}
		
		}
		
		
		
		
		
		
	/////////////////////////////////////////////////
	}
	
	if ($fila22['status']=='-') 
	{
	echo "<form enctype='multipart/form-data' action='chequeras.php?accion=Editar1' name='form1' method='post' >"; //onsubmit='return valban(form1)'>";
	pantalla_act3($result,$accion, $codigo, $nregistro);
    echo "<br><input type = 'submit' value = 'Confirmar cambios'></form>\n";
	}
	echo '</div>';
}
?>
<?php include("pie.php");?>
</body></html>
   </td>
    </tr>
</table>
</fieldset>
<?php
function pantalla_act ($result,$accion){
$deci=$_SESSION['deci'];
$sep_decimal=$_SESSION['sep_decimal'];
$sep_miles=$_SESSION['sep_miles'];
//echo $sql; 
if ($accion == 'Anadir') { $lectura = 'readonly = "readonly"'; $activada="disabled" ; } else {$lectura=''; $activada='';}
?>
  	 <label><fieldset><legend>REGISTRO CHEQUERA </legend>
  	<table width="300" border="2">
    <td class= "blanco b" width="50">Banco<td colspan='3'>
	  		<?php
			$codigo=$fila['nro_cta_ba'];
			echo '<select name="codigo" size="1">';
			$sql="select * from sgcaf843 order by nombre_ban";
			$resultado=mysql_query($sql);
			while ($fila2 = mysql_fetch_assoc($resultado)) {
				echo '<option value="'.$fila2['nro_cta_ba'].'" '.(($banco==$fila2['nro_cta_ba'])?'selected':'').'>'.$fila2['nombre_ban'].''.$fila2['nro_cta_ba'].'</option>';}
	 	echo '</select> '; 
	    ?> 	*</td>	<tr>	
		
		<input type="hidden" name="nombre" value="<?php echo $fila2['nombre_ban'];?>">	
	&nbsp;</td></tr> 
</table>
<?php 
}
?>

<?php
function pantalla_act2 ($result,$accion,$codigo,$nombre){
$deci=$_SESSION['deci'];
$sep_decimal=$_SESSION['sep_decimal'];
$sep_miles=$_SESSION['sep_miles'];
$fila666 = mysql_fetch_assoc($result);
//echo $sql; 
if ($accion == 'Anadir1') { $lectura = 'readonly = "readonly"'; $activada="disabled" ; } else {$lectura=''; $activada='';}
?>
  	 <label><fieldset><legend>REGISTRO CHEQUERA del Banco <?php 
	$sql= "SELECT *FROM sgcaf843 where nro_cta_ba='$codigo'";
	$result=mysql_query($sql);
	 $fila5 = mysql_fetch_assoc($result);
	 
	 echo $fila5 ['nombre_ban'] ?></legend>
  	<table width="300" border="2">
    <td class= "blanco b" width="50">Banco<td colspan='3'>
	<input name="codigo" type="text" id="codigo" value="<?php echo $codigo ?>" <?php echo $lectura; ?>size="20" maxlength="20" />*</td>
	  		<?php
			$fech_reg = date("d/m/Y", time());
		?> </td>	<tr>				
	<td class= "blanco b" width="50" >Desde 
	<td width="50"><input name="desde" type="text" id="desde" value="<?php $d= $fila666['hasta'] +1; 
	echo ceroizq($d,'8');?>"size="8" maxlength="8" />*</td>
	<td class= "blanco b" width="50">Hasta 
	<td width="50"><input name="hasta" type="text" id="hasta" value="<?php $h= $d +24; echo ceroizq($h,'8');	
	?>"size="8" maxlength="8" />*</td>
   <?php  echo "<input type='hidden' name='fech_reg' value='.$fech_reg.'>"; ?>
   <?php  echo "<input type='hidden' name='status' value='-'>"; ?>
	&nbsp;</td></> 
</table>
<?php 
}
?>

<?php
function pantalla_act3 ($result,$accion, $codigo, $nregistro){
$deci=$_SESSION['deci'];
$sep_decimal=$_SESSION['sep_decimal'];
$sep_miles=$_SESSION['sep_miles'];
$s= mysql_fetch_assoc($result);
//echo $sql; 
if ($accion == 'Editar') { $lectura = 'readonly = "readonly"'; $activada="disabled" ; } else {$lectura=''; $activada='';}
?>
  	 <label><fieldset><legend>Cambiar Estatus de Chequera del Banco <?php echo $s['nombre_ban'] ?> </legend>
  	<table width="260" border="3">
	<?php $status=$s['status']; ?>
    <input type="radio" name="status" value="+" <?php if ($status == '+') echo " checked"?>/> Activo</label> 
	 <input type="radio" name="status" value="-" <?php if ($status == '-') echo " checked"?>/> Inactivo</label></td><tr>
	 <?php  echo "<input type='hidden' name='codigo' value='$codigo'>"; ?>
	 <?php  echo "<input type='hidden' name='nregistro' value='$nregistro'>"; ?>
</table>
 	&nbsp;</td></tr> 

<?php 
}
?>


<?php
function pantalla_act4 ($result,$accion,$codigo, $nregistro){
$deci=$_SESSION['deci'];
$sep_decimal=$_SESSION['sep_decimal'];
$sep_miles=$_SESSION['sep_miles'];
$ss= mysql_fetch_assoc($result);
//echo $sql; 
if ($accion == 'Editar') { $lectura = 'readonly = "readonly"'; $activada="disabled" ; } else {$lectura=''; $activada='';}
	echo "<fieldset><legend>Cambiar Estatus de Chequera del Banco ".$ss['nombre_ban']."</legend>";
  	echo '<table width="260" border="3">';
	echo '<tr><td>';
	$status=$ss['status'];
	echo '<input type="radio" name="status" value="+" '.($status == '+'?" checked":'').'/> Activo';
	echo '<input type="radio" name="status" value="-" '.($status == '-'?" checked":'').'/> Inactivo';
	echo '</td>';
	echo '</tr>';
	echo '<tr>';

	echo '<td class= "blanco b" width="50">Explicación';
	echo '<select name="explicacion" size="1">';
	$sql="select * from sgcaf000 where tipo='Anu_Chequeras'";
	$resultado=mysql_query($sql);
	while ($fila = mysql_fetch_assoc($resultado)) {
		echo '<option value="'.$fila['nombre'].'" '.(($explicacion==$fila['nombre'])?'selected':'').'>'.$fila['nombre'].'</option>';
	}
 	echo '</select> '; 
	echo '*</td></tr>';
		 
	echo "<input type='hidden' name='codigo' value='$codigo'>"; 
	echo "<input type='hidden' name='registro' value='$nregistro'>"; 
	echo '</table>';

}
?>

<?php 
function pantalla_reutilizar($result2,$accionIn,$codigo,$nombre,$nro){
$deci=$_SESSION['deci'];
$sep_decimal=$_SESSION['sep_decimal'];
$sep_miles=$_SESSION['sep_miles'];
//echo $sql; 
 
if ($accion == 'Anular_Cheque') { $lectura = 'readonly = "readonly"'; $activada="disabled" ; } else {$lectura=''; $activada='';}
?>
<label><fieldset><legend>REUTILIZACIÓN DE CHEQUES del Banco <?php echo $nombre;?></legend>
  	<table width="350" border="3">
	<?php 
	echo "<table class='basica 100 hover' width='100%'>"; 
	while($row1=mysql_fetch_array($result2)) 
	{
	
	echo "<td><a href='chequeras.php?accion=Borrar_Cheque&codigo=".$row1['nro_cta_ba']."&numero=".$row1['mche_orden']."&nombre=".$row1['nombre_ban']."&nregistro=".$row1['nro_reg']."'><img src='imagenes/16-em-cross.png' width='16' height='16' border='0' title='Eliminar' alt='Eliminar'()' /></a></td>";
		
	echo "<td><a href='chequeras.php?accion=Reutilizar&codigo=".$row1['nro_cta_ba']."&numero=".$row1['mche_orden']."&nombre=".$row1['nombre_ban']."&nregistro=".$row1['nro_reg']."'><img src='imagenes/action_refresh_blue.gif' width='16' height='16' border='0' title='Reutilizar' alt='Reutilizar'/></a></td>";
	
    echo '</td>' .'<td  class="centro negro b" width="50">'.$row1['mche_orden'].' </td> 
	</td>' .'<td  class="izq negro b" width="190">'.$row1['mche_nombr'].' </td><td  class="negro b dcha" width="30"> '.number_format($row1['mche_monto'],2,'.',',').'</td></tr>';
	}
	?>
	<input type="hidden" name="nro" value="<?php echo $nro;?>">
	<input type="hidden" name="codigo" value="<?php echo $codigo;?>">
	<input type="hidden" name="nombre" value="<?php echo $nombre;?>">
</table>
	
		&nbsp;</td></tr> 

<?php 
}
?>

<?php 
function pantalla_act_borrar($result,$accion,$codigo,$numero,$nombre,$nregistro){
$deci=$_SESSION['deci'];
$sep_decimal=$_SESSION['sep_decimal'];
$sep_miles=$_SESSION['sep_miles'];
$filaxxx = mysql_fetch_assoc($result);
//echo $sql; 
if ($accion == 'Borrar_Cheque') { $lectura = 'readonly = "readonly"'; $activada="disabled" ; } else {$lectura=''; $activada='';}
?>
<label><fieldset><legend>ELIMINACIÓN DE CHEQUE del Banco <?php echo $nombre?></legend>
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
		 
		 <input type="hidden" name="registro" value="<?php echo $nregistro;?>">
		 </td><tr>
	 </tr>
</table>
	
		&nbsp;</td></tr> 

<?php 
}
?>

<?php 
function pantalla_act_reutilizar($result,$accion,$codigo,$numero,$nombre,$nregistro){
$deci=$_SESSION['deci'];
$sep_decimal=$_SESSION['sep_decimal'];
$sep_miles=$_SESSION['sep_miles'];
$filaxxx = mysql_fetch_assoc($result);
//echo $sql; 
if ($accion == 'Reutilizar') {$lectura = 'readonly = "readonly"'; $activada="disabled" ; } else {$lectura=''; $activada='';}
?>
<label><fieldset><legend>REUTILIZACIÓN DE CHEQUE del Banco <?php echo $nombre?></legend>
  	<table width="360" border="3">
	<td class= "blanco b" colspan='5' width="50" style="text-align:center" bgcolor="#FFFFCC">Encabezado del Cheque <tr>
		
	  <td class= "blanco b" width="50" bgcolor="#FFFFCC">Nro. de Cuenta<td>
	<input name="codigo" type="text" id="codigo" value="<?php echo $codigo ?>" <?php echo $lectura; ?>size="20" maxlength="20" /></td>
	 <td class= "blanco b" width="50" bgcolor="#FFFFCC">Nro. de Cheque<td>
	 <input name="numero" type="text" id="numero" value="<?php echo $numero?>" <?php  echo $lectura; ?>size="8" maxlength="8" /></td> <tr>
	 
 <td class= "blanco b" width="50" bgcolor="#FFFFCC">Bancos con Cheques Disponibles<td td  colspan='3' class="rojo">
  	<?php
		$sql="SELECT count(banco) as cuantos, banco, registro, nombre_ban FROM sgcaf846, sgcaf843 where estatus='+' and registro<>'$nregistro' and banco=nro_cta_ba and estado='1' and emitircheque='1' group by registro";
		//echo $sql; 
		    $a='0'; 
			$resultado=mysql_query($sql);
			echo '<select name="codigon" size="1">';
			while ($fila3 = mysql_fetch_assoc($resultado)) {
			if ($fila3['cuantos'] >= $n_registros)
			{
			echo '<option value="'.$fila3['registro'].'" '.(($banco==$fila3['registro'])?'selected':'').'>'.$fila3['nombre_ban'].''.$fila3['banco'].'</option>';
	 		 }
			 }
			 echo '</select> '; 
	    ?> 
	  <input type="hidden" name="codigo" value="<?php echo $codigo;?>">
	  <input type="hidden" name="registro" value="<?php echo $nregistro;?>">
		 </td><tr>
</table>
	
		&nbsp;</td></tr> 

<?php 
}
?>

<?php 
function pantalla_act_borrar_todos($result,$accion,$codigo,$nombre,$nregistro){
$deci=$_SESSION['deci'];
$sep_decimal=$_SESSION['sep_decimal'];
$sep_miles=$_SESSION['sep_miles'];
$filaxxx = mysql_fetch_assoc($result);
//echo $sql; 
if ($accion == 'Borrar_ChequeTodos') { $lectura = 'readonly = "readonly"'; $activada="disabled" ; } else {$lectura=''; $activada='';}
?>
<label><fieldset><legend>ELIMINACIÓN DE CHEQUES del Banco <?php echo $nombre?></legend>
  	<table width="380" border="3">
	<td class= "blanco b" colspan='5' width="50" style="text-align:center" bgcolor="#FFFFCC">Encabezado del Cheque <tr>
		
	  <td class= "blanco b" width="50" bgcolor="#FFFFCC">Nro. de Cuenta<td>
	<input name="codigo" type="text" id="codigo" value="<?php echo $codigo ?>" <?php echo $lectura; ?>size="20" maxlength="20" /></td><tr>
		 <input type="hidden" name="registro" value="<?php echo $nregistro;?>">
 
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
function pantalla_act_reutilizartodos($result,$accion,$numero,$codigo,$nregistro) {
$deci=$_SESSION['deci'];
$sep_decimal=$_SESSION['sep_decimal'];
$sep_miles=$_SESSION['sep_miles'];
//echo $sql; 
if ($accion == 'VerificarIn') { $lectura = 'readonly = "readonly"'; $activada="disabled" ; } else {$lectura=''; $activada='';}
?>
  	 <label><fieldset><legend>BANCOS CON CHEQUES DISPONIBLES</legend>
  	<table width="260" border="3">
     <td class= "blanco b" width="50">Nro. de Cuenta<td>
	  	<?php
		$sql="SELECT count(banco) as cuantos, banco, registro, nombre_ban FROM sgcaf846, sgcaf843 where estatus='+' and registro<>'$nregistro' and banco=nro_cta_ba and estado='1' and emitircheque='1' group by registro";
		//echo $sql; 
		    $a='0'; 
			$resultado=mysql_query($sql);
			echo '<select name="codigon" size="1">';
			while ($fila3 = mysql_fetch_assoc($resultado)) {
			if ($fila3['cuantos'] >= $n_registros)
			{
			echo '<option value="'.$fila3['registro'].'" '.(($banco==$fila3['registro'])?'selected':'').'>'.$fila3['nombre_ban'].''.$fila3['banco'].'</option>';
	 		 }
			 }
			 echo '</select> '; 
	    ?> 
	  <input type="hidden" name="codigo" value="<?php echo $codigo;?>">
	  <input type="hidden" name="registro" value="<?php echo $nregistro;?>">
	   <input type="hidden" name="cod" value="<?php echo 'h';?>">
	  
	 *</td><tr>
</table>
 	&nbsp;</td></tr> 

<?php 
}
?>