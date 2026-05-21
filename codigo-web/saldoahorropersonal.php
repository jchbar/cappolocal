<?php
include ('final.php');
include('conex.php');
// $menu61=1;
mysql_select_db('sica');
$upav1="update sgcaf810 set cue_codigo='3-01-01-01-02-01-2353' where cue_codigo='3-01-01-01-02-01-0002'";
$upav2="update sgcaf820 set com_cuenta='3-01-01-01-02-01-2353' where com_cuenta='3-01-01-01-02-01-0002'";
$upap1="update sgcaf810 set cue_codigo='3-01-01-01-02-01-1637' where cue_codigo='3-01-01-01-02-01-0001'";
$upap2="update sgcaf820 set com_cuenta='3-01-01-01-02-01-1637' where com_cuenta='3-01-01-01-02-01-0001'";
$upar1="update sgcaf810 set cue_codigo='3-01-01-01-02-01-0403' where cue_codigo='3-01-01-01-02-01-0004'";
$upar2="update sgcaf820 set com_cuenta='3-01-01-01-02-01-0403' where com_cuenta='3-01-01-01-02-01-0004'";
$upgs1="update sgcaf810 set cue_codigo='3-01-01-01-02-01-2095' where cue_codigo='3-01-01-01-02-01-0005'";
$upgs2="update sgcaf820 set com_cuenta='3-01-01-01-02-01-2095' where com_cuenta='3-01-01-01-02-01-0005'";
$upjh1="update sgcaf810 set cue_codigo='3-01-01-01-02-01-0914' where cue_codigo='3-01-01-01-02-01-0003'";
$upjh2="update sgcaf820 set com_cuenta='3-01-01-01-02-01-0914' where com_cuenta='3-01-01-01-02-01-0003'";
$uprg1="update sgcaf810 set cue_codigo='3-01-01-01-02-01-0285' where cue_codigo='3-01-01-01-02-01-0006'";
$uprg2="update sgcaf820 set com_cuenta='3-01-01-01-02-01-0285' where com_cuenta='3-01-01-01-02-01-0006'";
set_time_limit(60);
$r1=mysql_query($upav1) or die('Error en la F810-4 '.$sql.' '.mysql_error());
$r1=mysql_query($upav2) or die('Error en la F810-4 '.$sql.' '.mysql_error());
$r1=mysql_query($upap1) or die('Error en la F810-4 '.$sql.' '.mysql_error());
$r1=mysql_query($upap2) or die('Error en la F810-4 '.$sql.' '.mysql_error());
$r1=mysql_query($upar1) or die('Error en la F810-4 '.$sql.' '.mysql_error());
$r1=mysql_query($upar2) or die('Error en la F810-4 '.$sql.' '.mysql_error());
$r1=mysql_query($upgs1) or die('Error en la F810-4 '.$sql.' '.mysql_error());
$r1=mysql_query($upgs2) or die('Error en la F810-4 '.$sql.' '.mysql_error());
$r1=mysql_query($upjh1) or die('Error en la F810-4 '.$sql.' '.mysql_error());
$r1=mysql_query($upjh2) or die('Error en la F810-4 '.$sql.' '.mysql_error());
$r1=mysql_query($uprg1) or die('Error en la F810-4 '.$sql.' '.mysql_error());
$r1=mysql_query($uprg2) or die('Error en la F810-4 '.$sql.' '.mysql_error());

$sql="select * from sgcaf200 where tipo_socio='E' order by cod_prof";
$res200=mysql_query($sql);
// echo $sql;
while($r200=mysql_fetch_assoc($res200)) {
	$codigo=substr($r200['cod_prof'],1,4);
	$socio=$codigo;
	$codigo='3-01-01-01-02-01-'.$codigo;
	$saldo=buscar_saldo_f810($codigo,'');
	$saldo=abs($saldo/2);
	echo $codigo.' ->'.$saldo.'<br>';
	$f200="update sgcaf200 set hab_f_prof=$saldo, hab_f_empr=$saldo where cod_prof='0$socio'";
	// echo $f200.'<br>';
	$res=mysql_query($f200)  or die('Error en la F810-4 '.$sql.' '.mysql_error());
}
$upav1="update sgcaf810 set cue_codigo='3-01-01-01-02-01-0002' where cue_codigo='3-01-01-01-02-01-2353'";
$upav2="update sgcaf820 set com_cuenta='3-01-01-01-02-01-0002' where com_cuenta='3-01-01-01-02-01-2353'";
$upap1="update sgcaf810 set cue_codigo='3-01-01-01-02-01-0001' where cue_codigo='3-01-01-01-02-01-1637'";
$upap2="update sgcaf820 set com_cuenta='3-01-01-01-02-01-0001' where com_cuenta='3-01-01-01-02-01-1637'";
$upar1="update sgcaf810 set cue_codigo='3-01-01-01-02-01-0004' where cue_codigo='3-01-01-01-02-01-0403'";
$upar2="update sgcaf820 set com_cuenta='3-01-01-01-02-01-0004' where com_cuenta='3-01-01-01-02-01-0403'";
$upgs1="update sgcaf810 set cue_codigo='3-01-01-01-02-01-0005' where cue_codigo='3-01-01-01-02-01-2095'";
$upgs2="update sgcaf820 set com_cuenta='3-01-01-01-02-01-0005' where com_cuenta='3-01-01-01-02-01-2095'";
$upjh1="update sgcaf810 set cue_codigo='3-01-01-01-02-01-0003' where cue_codigo='3-01-01-01-02-01-0914'";
$upjh2="update sgcaf820 set com_cuenta='3-01-01-01-02-01-0003' where com_cuenta='3-01-01-01-02-01-0914'";
$uprg1="update sgcaf810 set cue_codigo='3-01-01-01-02-01-0006' where cue_codigo='3-01-01-01-02-01-0285'";
$uprg2="update sgcaf820 set com_cuenta='3-01-01-01-02-01-0006' where com_cuenta='3-01-01-01-02-01-0285'";

$r1=mysql_query($upav1) or die('Error en la F810-4 '.$sql.' '.mysql_error());
$r1=mysql_query($upav2) or die('Error en la F810-4 '.$sql.' '.mysql_error());
$r1=mysql_query($upap1) or die('Error en la F810-4 '.$sql.' '.mysql_error());
$r1=mysql_query($upap2) or die('Error en la F810-4 '.$sql.' '.mysql_error());
$r1=mysql_query($upar1) or die('Error en la F810-4 '.$sql.' '.mysql_error());
$r1=mysql_query($upar2) or die('Error en la F810-4 '.$sql.' '.mysql_error());
$r1=mysql_query($upgs1) or die('Error en la F810-4 '.$sql.' '.mysql_error());
$r1=mysql_query($upgs2) or die('Error en la F810-4 '.$sql.' '.mysql_error());
$r1=mysql_query($upjh1) or die('Error en la F810-4 '.$sql.' '.mysql_error());
$r1=mysql_query($upjh2) or die('Error en la F810-4 '.$sql.' '.mysql_error());
$r1=mysql_query($uprg1) or die('Error en la F810-4 '.$sql.' '.mysql_error());
$r1=mysql_query($uprg2) or die('Error en la F810-4 '.$sql.' '.mysql_error());


function buscar_saldo_f810($cuenta, $asiento)
{
	$sql_f810="select cue_saldo from sgcaf810 where cue_codigo='$cuenta'";
	echo $sql_f810;
	$lacuentas=mysql_query($sql_f810); //  or die ("<p />El usuario $usuario no pudo conseguir el saldo contable<br>".mysql_error()."<br>".$sql);
	$lacuenta=mysql_fetch_assoc($lacuentas);
	$saldoinicial=$lacuenta['cue_saldo'];
//	echo $saldoinicial;
//	echo 'el asiento '.$asiento.'<br>';
	$sql_f820="select sum(com_monto1) as com_monto1, sum(com_monto2) as com_monto2 from sgcaf820 where com_cuenta='$cuenta' ";
	if ($asiento == '')
		$sql_f820.="";
	else
		$sql_f820.=" and (com_nrocom <> '$asiento') ";
	$sql_f820.=" group by com_cuenta order by com_fecha";
//	echo $sql_f820.'<br>';
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