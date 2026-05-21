<?php
$mostrarerrores=1;
error_reporting(E_ALL);
ini_set('display_errors',$mostrarerrores);
// $db_host = "localhost";
// $db_name = "sica";
// $db_user = "jhernandez";
// $db_pass = "nene14";
$db_host = "mysql-db";
$db_name = "cajaweb_db";
$db_user = "jhernandez";
$db_pass = "abc123**";
$_SESSION['institucion'] = "";

	define('cuentas',   $_SESSION["institucion"]."sgcaf810"); 
	define('enc_contable',   $_SESSION["institucion"]."sgcaf830"); 
	define('detalle_contable',  $_SESSION["institucion"]."sgcaf820"); 
	define('niveles',  $_SESSION["institucion"]."sgcafniv"); 
	define('confcont',  $_SESSION["institucion"]."sgcaf100"); 
	define('decimales',   2); 
define('socios',   $_SESSION["institucion"]."sgcaf200"); 
define('prestamos',   $_SESSION["institucion"]."sgcaf310"); 
define('fianzas',   $_SESSION["institucion"]."sgcaf320"); 
define('activos',   $_SESSION["institucion"]."sgcaf610"); 
//require("../final.php");
	try{
/*
		$link = @mysql_connect($Servidor,$Usuario, $Password,'',65536) or die ("<p /><br /><p /><div style='text-align:center'>Disculpe... En estos momentos no hay conexión con el servidor, estamos realizando modificaciones.... inténtalo más tarde. Gracias....</div>");
	mysql_select_db('sica', $link);
*/
		$db_con = new PDO("mysql:host={$db_host};dbname={$db_name};charset=utf8",$db_user,$db_pass);
		$db_con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		// $db_con->execute("set names utf8");
		// $db_con->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
	}
	catch(PDOException $e){
		echo $e->getMessage();
		// echo 'Fallo la conexion';
	}

?>
