<?php
$f = fopen("datos.csv","w");
$sep = ";";

mysql_data_seek($result,0);
	while($reg = mysql_fetch_array($result) ) {
		$linea = $reg['cue_codigo'] . $sep . $reg['cue_nombre'] . $sep . $reg['cue_saldo']. $sep . $reg['danterior']. $sep . $reg['hanterior']. $sep . $reg['debe']. $sep . $reg['haber']; //pones cada campo separado con $sep.
	fwrite($f,$linea);
	}
fclose($f); 
$fichero = "./datos.csv";
header("Content-Description: File Transfer");
header( "Content-Disposition: filename=".basename($fichero) );
header("Content-Length: ".filesize($fichero));
header("Content-Type: application/force-download");
@readfile($fichero);
?>
