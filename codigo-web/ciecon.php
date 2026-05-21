<?php

//Copyright (C) 2000-2006  Antonio Grandío Botella http://www.antoniograndio.com
//Copyright (C) 2000-2006  Inmaculada Echarri San Adrián http://www.inmaecharri.com

//This file is part of Catwin.

//CatWin is free software; you can redistribute it and/or modify
//it under the terms of the GNU General Public License as published by
//the Free Software Foundation; either version 2 of the License, or
//(at your option) any later version.

//CatWin is distributed in the hope that it will be useful,
//but WITHOUT ANY WARRANTY; without even the implied warranty of
//MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//GNU General Public License for more details:
//http://www.gnu.org/copyleft/gpl.html

//You should have received a copy of the GNU General Public License
//along with Catwin Net; if not, write to the Free Software
//Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

include("head.php");
if (!$link OR !$_SESSION['empresa']) {
	include("noempresa.php");
	exit;
}
if ($_GET['emp'] == 1) {$_GET['n'] = 1;}

extract($_GET);
extract($_POST);

// 		$sql="update definiti set final='1', cierre='$b' where idempresa='".$_SESSION['idempresa']."'";
//		$resultado=mysql_query($sql);

/*
if ($fila['final'] == 1) {		// listo para hacer cierre
	$onload="onload=\"foco('asiento')\"";
	//$result = mysql_query("SELECT max(asiento) FROM asientos");
	//$row = mysql_fetch_row($result);
	//$asiento = $row[0] + 1;
	$fila = mysql_fetch_array(mysql_query("SELECT con_compr FROM sgcaf8co where idempresa='".$_SESSION['idempresa']."'"));
	$asiento = $fila[0] + 1;
	mysql_query("UPDATE sgcaf8co SET con_compr = '$asiento' WHERE idempresa='".$_SESSION['idempresa']."'");
	// Cojo el valor de la fecha en que se hizo el último Asiento
	$result = mysql_query("SELECT date_format(con_ultfec,'%d/%m/%y') AS ultfechax FROM sgcaf8co where idempresa='".$_SESSION['idempresa']."'");
	$row = mysql_fetch_array($result);
	$fecha = $row[0];
} else {
	$onload="onload=\"foco('cuenta11')\"";
	$readonly=" readonly='readonly'";
	$asiento = $_POST['asiento'];
	$fecha = $_POST['fecha'];
	$fecha = $_POST['fecha'];
	$tipo =$_POST['tipo'];
	$debcre= $_POST['debcre'];
	$cuenta1= $_POST['cuenta1'];
	$referencia =$_POST['referencia'];
	$elmonto=$_POST['elmonto'];
}

?>
*/
?>
<body <?php if (!$bloqueo) {echo $onload;}?>>

<?php

include("arriba.php");
$menu24=1;include("menusizda.php");

/*
if ($elmonto) {
	include ("altaasim2.php");
//	$cuadre = totalapu($asiento);
}
*/

if (!$_POST['accion']) {
	echo '<fieldset><legend>Confirmar</legend>';
	echo "<form enctype='multipart/form-data' name='form1' action='ciecon.php' method='post'>";
	echo "<input type='hidden' name='accion' value='ListoCierre' />";
	echo "<input type='submit' name='boton' value=\"Cerrar Ejercicio\" tabindex='10'>"; 
	echo '</fieldset><p style="clear:both">';
	echo '</form>';
}
?>


<?php
$_SESSION['idempresa']='CAPPOUCLA';
/*
$comando="select * from definiti where idempresa='".$_SESSION['idempresa']."'";
$resultado=mysql_query($comando);
$fila=mysql_fetch_assoc($resultado);
*/
$ip = $_SERVER['HTTP_CLIENT_IP'];
if (!$ip) {$ip = $_SERVER['REMOTE_ADDR'];}
if ($_POST['accion']=='ListoCierre')
	if (1==1) { // ($fila['final'] == 1) {		// listo para hacer cierre
		$sql='select fech_ejerc as cierre from sgcaf100';
		$acuentas=mysql_query($sql);
		$fila=mysql_fetch_assoc($acuentas);
		
		echo 'Ejecutando Cierre Contable<br>';

		echo 'Cerrando Cuentas / Pasando historico <br>';
		$ccuentas="select * from sgcaf810 order by cue_codigo ";
		$acuentas=mysql_query($ccuentas);
		$registros=mysql_num_rows($acuentas);
		set_time_limit($registros);

		while($lacuenta=mysql_fetch_assoc($acuentas)) {
			// copio a la historica 
			$rhis="insert into histf810 (cue_codigo, cue_nombre, cue_nivel, cue_saldo, ";
			for ($i=1;$i<13;$i++) {
				$cuento='cue_deb'.($i<10?'0':'').$i;
				$rhis.=$cuento.', ';
				$cuento='cue_cre'.($i<10?'0':'').$i;
				$rhis.=$cuento.', ';
				}
			$rhis.=' cue_codig2, cue_ip) values (';
			$emp=$lacuenta['idempresa'];
			$codigo=$lacuenta['cue_codigo'];
			$codigo2=substr($fila['cierre'],2,2).$codigo;
			$nombre=$lacuenta['cue_nombre'];
			$nombre=str_replace("'","",$nombre);
			$nombre=str_replace('"',"",$nombre);
//			$nombre=eregi_replace('["]',$nombre);
//			$nombre=eregi_replace("[']",$nombre);
			$nivel=$lacuenta['cue_nivel'];
			$saldo=$lacuenta['cue_saldo'];
			$rhis.="'$codigo', '$nombre', '$nivel', $saldo, ";
			for ($i=1;$i<13;$i++) {
				$cuento='cue_deb'.($i<10?'0':'').$i;
				$rhis.=$lacuenta[$cuento].', ';
				$cuento='cue_cre'.($i<10?'0':'').$i;
				$rhis.=$lacuenta[$cuento].', ';
			}
			$rhis.=" '$codigo2', '$ip')";
//			echo $rhis.'<br><br>';
			$grabar=mysql_query($rhis) or die(mysql_error());
			//	calcular saldo final y grabarlo
			$debe=buscar_saldo_f810($codigo);
//			$rhis="update sgcaf810 set cue_saldo=$debe where cue_codigo = '$codigo' and idempresa='".$_SESSION['idempresa']."'";
			$rhis="update sgcaf810 set cue_saldo=$debe where cue_codigo = '$codigo' ";
//			echo $rhis.'<br><br>';
			$grabar=mysql_query($rhis) or die(mysql_error());
			// colocar en 0 los debitos y creditos
			$rhis="update sgcaf810 set ";
			for ($i=1;$i<13;$i++) {
				$cuento='cue_deb'.($i<10?'0':'').$i;
				$rhis.=$cuento . " = 0, ";
				$cuento='cue_cre'.($i<10?'0':'').$i;
				$rhis.=$cuento . " = 0, ";
			}
//			$rhis.=" cue_cre12 = 0  where cue_codigo = '$codigo' and idempresa='".$_SESSION['idempresa']."'";
			$rhis.=" cue_cre12 = 0  where cue_codigo = '$codigo' ";
			$grabar=mysql_query($rhis) or die(mysql_error());
			
//			echo $rhis.'<br><br>';
			}

		echo '<br>Cerrando Asientos / Pasando historico <br>';
//		$ccuentas="select * from sgcaf820 where idempresa='".$_SESSION['idempresa']."' order by com_nrocom ";
		$ccuentas="select * from sgcaf820, sgcaf100 where substr(com_fecha,1,4)=substr(fech_ejerc,1,4) order by com_fecha";
		$acuentas=mysql_query($ccuentas);
		$registros=mysql_num_rows($acuentas);
		set_time_limit($registros);
		while($lacuenta=mysql_fetch_assoc($acuentas)) {
//			$emp=$lacuenta['idempresa'];
			$nro=$lacuenta['com_nrocom'];
			$ite=$lacuenta['com_nroite'];
			$fec=$lacuenta['com_fecha'];
			$cue=$lacuenta['com_cuenta'];
			$deb=$lacuenta['com_debcre'];
			$tip=$lacuenta['com_tipmov'];
			$ref=$lacuenta['com_refere'];
			$des=$lacuenta['com_descri'];
			$des=str_replace('"',"",$des);
			$des=str_replace("'","",$des);
//			$des=eregi_replace('["]',$des);
//			$des=eregi_replace("[']",$des);
			$mo1=$lacuenta['com_monto1'];
			$mo2=$lacuenta['com_monto2'];
			$mon=$lacuenta['com_monto'];
			$reg=$lacuenta['nro_registro'];
			$ipv=$lacuenta['com_ip'];
//			echo substr($fec,0,4).'----'.substr($fila['cierre'],0,4);
			if ( substr($fec,0,4)==substr($fila['cierre'],0,4)) {
//				$rhis="insert into histf820 (com_nrocom, com_nroite, com_fecha, com_cuenta, com_debcre, com_tipmov, com_refere, com_descri, com_monto1, com_monto2, com_monto, nro_registro, com_ip, his_ip) values (";
				$rhis.="'$nro', '$ite', '$fec', '$cue', '$deb', '$tip', '$ref', ".'"'.$des.'"'.", $mo1, $mo2, $mon, '$reg', '$ipv', '$ip')";
				$rhis="insert into histf820 (com_nrocom, com_nroite, com_fecha, com_cuenta, com_debcre, com_tipmov, com_refere, com_descri, com_monto1, com_monto2, com_monto, nro_registro, com_ip, his_ip) values (";
				$rhis.="'$nro', '$ite', '$fec', '$cue', '$deb', '$tip', '$ref', '$des', $mo1, $mo2, $mon, '$reg', '$ipv', '$ip')";
				$grabar=mysql_query($rhis) or die(mysql_error());
			
//				echo $rhis.'<br><br>';
//				$rhis="delete from sgcaf820 where nro_registro = $reg and idempresa='".$_SESSION['idempresa']."'";
				$rhis="delete from sgcaf820 where nro_registro = $reg ";
				$grabar=mysql_query($rhis) or die(mysql_error());
//				echo $rhis.'<br><br>';
			} 
		}

		echo '<br>Cerrando Encabezados / Pasando historico <br>';
//		$ccuentas="select * from sgcaf830 where idempresa='".$_SESSION['idempresa']."' order by enc_clave ";
		$ccuentas="select * from sgcaf830, sgcaf100 where  substr(enc_fecha,1,4)=substr(fech_ejerc,1,4) order by enc_clave ";
		$acuentas=mysql_query($ccuentas);
		$registros=mysql_num_rows($acuentas);
		set_time_limit($registros);
		while($lacuenta=mysql_fetch_assoc($acuentas)) {
//			$emp=$lacuenta['idempresa'];
			$nro=$lacuenta['enc_clave'];
			$fec=$lacuenta['enc_fecha'];
			$ref=$lacuenta['enc_refer'];
			$de1=$lacuenta['enc_desco'];
			$de1=str_replace('"',"",$de1);
			$de1=str_replace("'","",$de1);
			$de2=$lacuenta['enc_desc1'];
			$de2=str_replace('"',"",$de2);
			$de2=str_replace("'","",$de2);
			$deb=$lacuenta['enc_debe'];
			$hab=$lacuenta['enc_haber'];
			$ite=$lacuenta['enc_item'];
			$sw=$lacuenta['enc_sw'];
			$exp=$lacuenta['enc_explic'];
			$exp=str_replace('"',"",$exp);
			$exp=str_replace("'","",$exp);
			$sop=$lacuenta['enc_soporte'];
			if ( substr($fec,0,4)==substr($fila['cierre'],0,4)) {
				$rhis="insert into histf830 (enc_clave, enc_fecha, enc_refer, enc_desco, enc_desc1, enc_debe, enc_haber, enc_item, enc_sw, enc_explic, enc_soporte) values (";
				$rhis.="'$nro', '$fec', '$ref', '$de1', '$de2', '$deb', $hab, '$ite', '$sw', '".$exp."', '".addslashes($sop)."')";
				$grabar=mysql_query($rhis) or die(mysql_error().'<br>'.$rhis.'<br>');
			
//				echo $rhis.'<br><br>';
//				$rhis="delete from sgcaf830 where enc_clave = $nro and idempresa='".$_SESSION['idempresa']."'";
				$rhis="delete from sgcaf830 where enc_clave = $nro ";
				$grabar=mysql_query($rhis) or die(mysql_error());
//				echo $rhis.'<br><br>';
			} 
		}
		$ano=substr($fila['cierre'],0,4);
//		$sql="update definiti set final='0' where idempresa='".$_SESSION['idempresa']."'";;
		$sql="update definiti set final='0' ";;
		$resultado=mysql_query($sql);
//		$sql="insert into histf835 (idempresa, ano) values ('".$_SESSION['idempresa']."', '$ano')";
		$sql="insert into histf835 (ano) values ('$ano')";
		$resultado=mysql_query($sql);

		$result = mysql_query("UPDATE sgcaf100 set fech_ejerc=date_add(fech_ejerc,INTERVAL 365 DAY)");


		echo "<h2>Cierre Contable del ".substr($fila['cierre'],0,4)." finalizado </h1>";		
		}
else 
echo "<h1>No ha realizado el precierre contable</h1>";		
		
?>

<?php
function buscar_saldo_f810($cuenta)
{
//	$sql_f810="select cue_saldo from sgcaf810 where cue_codigo='$cuenta' and idempresa='".$_SESSION['idempresa']."'";
	$sql_f810="select cue_saldo from sgcaf810 where cue_codigo='$cuenta' ";
//	echo $sql_f810;
	$lacuentas=mysql_query($sql_f810); //  or die ("<p />El usuario $usuario no pudo conseguir el saldo contable<br>".mysql_error()."<br>".$sql);
	$lacuenta=mysql_fetch_assoc($lacuentas);
	$saldoinicial=$lacuenta['cue_saldo'];
	
	$sql_f820="select com_monto1, com_monto2 from sgcaf820, sgcaf100 where com_cuenta='$cuenta' and substr(com_fecha,1,4)=substr(fech_ejerc,1,4) order by com_fecha";
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

</div></body></html>

