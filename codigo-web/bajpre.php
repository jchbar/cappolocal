<?php
$downloadfile=$_POST['nombre_archivo'];
header ('Content-Disposition: attachment; filename="nompre/20090818domicialiacion.txt"' );
header("Content-Type: application/force-download");
header("Content-Transfer-Encoding: binary");
header("Content-Length: ".strlen($downloadfile));
header("Pragma: no-cache");
header("Expires: 0");
echo $downloadfile;
?>
