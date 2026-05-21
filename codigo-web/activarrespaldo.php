<?php
// include("final.php");
$Usuario="jhernandez";
$Password="nene14";
$Servidor="192.168.1.40";
$link = @mysql_connect($Servidor,$Usuario, $Password,'',65536) or die ("<p /><br /><p /><div style='text-align:center'>Disculpe... En estos momentos no hay conexión con el servidor, estamos realizando modificaciones.... inténtalo más tarde. Gracias....</div>");
mysql_select_db('sica', $link);
$comando  = "select * FROM sgcaf8co";
$resultado = mysql_query($comando);
$archivo = mysql_fetch_assoc($resultado);
$registro = $archivo["respaldo"];
if ($registro == 0)
	$comando = "update sgcaf8co set respaldo = 1";
else 
	$comando = "update sgcaf8co set respaldo = 0";
echo $comando;
$resultado=mysql_query($comando);
?>

