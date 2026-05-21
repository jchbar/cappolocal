<?php
session_start();
extract($_GET);
extract($_POST);
extract($_SESSION);

include("conex.php");
include("funciones.php");

$suma=$reintegros=0;
// $suma='xx--'.$_GET['totalregistros'].'--xx';
$losregistros=$_GET['totalregistros'];
$lacedula=$_GET['micedula'];
$montoprestamo=$_GET['montoprestamo'];
for ($i=0;$i<$losregistros;$i++)
{
//	${'cancelar'.$i};
//	$variable=${'cancelar'.$i};
	$variable='cancelar'.($i+1);
//	echo 'la variable '.$$variable;
	if (substr($$variable,0,2)!='SC') {
		$sql="select cuent_pres,codsoc_sdp,cuent_int from sgcaf310,sgcaf360 where (nropre_sdp='".$$variable."') and stapre_sdp='A' and ! renovado and (cedsoc_sdp='$lacedula')  and (codpre_sdp=cod_pres)";
//		echo $sql;
		$a_310=mysql_query($sql);
		$r_310=mysql_fetch_assoc($a_310);
		$lacuenta=trim($r_310['cuent_pres']).'-'.substr($r_310[codsoc_sdp],1,4);
		$saldo=buscar_saldox_f810($lacuenta);
		if ($saldo > 0)
			$suma+=$saldo;
		else $suma-=$saldo;  // $r_310['saldo'];
		$lacuenta=trim($r_310['cuent_int']).'-'.substr($r_310[codsoc_sdp],1,4);
		$saldo=buscar_saldox_f810($lacuenta);
		if ($saldo < 0)
			$reintegros+=$saldo;
		else $reintegros-=$saldo;  // $r_310['saldo'];
	}
	else {
		$cuenta=substr($$variable,2,21);
		$sql="select cue_codigo from sgcaf810 where (cue_codigo='".$cuenta."')";
//		echo $sql;
		$a_310=mysql_query($sql);
		$r_310=mysql_fetch_assoc($a_310);
		$lacuenta=trim($r_310['cue_codigo']); // .'-'.substr($r_310[codsoc_sdp],1,4);
		$saldo=buscar_saldox_f810($lacuenta);
//		echo 'la cuenta '.$lacuenta.  ' / '.$saldo;
//		if ($saldo > 0)
			$suma+=$saldo;
//		else $suma-=$saldo;  // $r_310['saldo'];
/*
		$lacuenta=trim($r_310['cuent_int']).'-'.substr($r_310[codsoc_sdp],1,4);
		$saldo=buscar_saldox_f810($lacuenta);
		if ($saldo < 0)
			$reintegros+=$saldo;
		else $reintegros-=$saldo;  // $r_310['saldo'];
*/
	}
	
//	$variable="$".'cancelar'.$i;		// creo la variable
//	echo 'la variable '.$_GET[$${'cancelar'.$i}];
}
$descuentosadm=restaradministrativos($montoprestamo);
$elneto=$montoprestamo-($suma+$descuentosadm)+$reintegros;

/*
${'variable'.$indice};
esto haria que mi $variable fuera $variable1

for ($i=0; $i<count($_GET['cancelar']);$i++) {
	$suma+=$_GET['cancelar'][$i];
}
/*
$ids=$_POST['arreglo'];
foreach ( $ids as $id){
     echo $id."<br>"; 
	 $suma+=$id;
	 }
*/
// $suma.='(20)';
header("Content-Type: text/xml");
echo '<?xml version="1.0" encoding="utf-8"?>';
echo "<resultados>";
// echo utf8_encode("<cuota>$cuota</cuota>");		// sirve asi y como esta abajo tambien
echo "<cancelados>".number_format($suma,2,'.','')."</cancelados>";
echo "<neto>".number_format($elneto,2,'.','')."</neto>";
echo "<descuentosadm>".number_format($descuentosadm,2,'.','')."</descuentosadm>";
echo "<montoprestamo>".number_format($montoprestamo,2,'.','')."</montoprestamo>";
echo "<marcados>".number_format($losregistros,2,'.','')."</marcados>";
echo "<reintegros>".number_format($reintegros,2,'.','')."</reintegros>";
echo "</resultados>";

function buscar_saldox_f810($cuenta)
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

?>