<?php
$Usuariol="root";
$Passwordl="temp0ral";
$Servidorl="localhost";
$bddl="sica";
$Usuarior="cappoucl_datos";
$Passwordr="t3wp0r@1";
$Servidorr="65.110.52.32";
$Servidorr="66.118.151.44";
$bddr="cappoucl_sica";
$linkl = @mysql_connect($Servidorl,$Usuariol, $Passwordl,'',65536) or die ("<p /><br /><p /><div style='text-align:center'>Disculpe... En estos momentos no hay conexión con el servidor local, estamos realizando modificaciones.... inténtalo más tarde. Gracias....</div>");
$linkr = @mysql_connect($Servidorr,$Usuarior, $Passwordr,'',65536) or die ("<p /><br /><p /><div style='text-align:center'>Disculpe... En estos momentos no hay conexión con el servidor remoto, estamos realizando modificaciones.... inténtalo más tarde. Gracias....</div>");
$resultl=mysql_select_db($bddl,$linkl) or die('fallo cambio local');
$resultr=mysql_select_db($bddr,$linkr) or die('fallo cambio remoto');
$sqll="select * from sgcaf810 order by cue_codigo "; //limit 10";
$resultl=mysql_query($sqll,$linkl) or die('fallo '.$sqll);

$sqlr="delete from sgcaf810";
$resultr=mysql_query($sqlr,$linkr) or die('fallo eliminacion remota');
//echo 'Eliminando';
$tiempo=(mysql_num_rows($resultl));
set_time_limit($tiempo);
echo 'Registros a actualizar '.$tiempo;
while($datosl=mysql_fetch_array($resultl)) {
	$campos = mysql_num_fields($resultl);
	$comando='insert into sgcaf810 values (';
	for ($i=0; $i<$campos; $i++) {
	//	$valor="datosl['".mysql_field_name($resultl,$i)."']";
		$valor="'".$datosl[$i]."'";
		$comando.=$valor;
		$comando.=(($i+1)<$campos?', ':'');
	}
	$comando.=')';
	$resultr=mysql_query($comando,$linkr);
//	echo $comando.'<br>';
}
echo 'Finalizado';
?>
