<?php
$mostrarerrores=1;
error_reporting(E_ALL);
ini_set('display_errors',$mostrarerrores);

include("final.php");
try {
    $link = new PDO("mysql:host=$Servidor;dbname=$bdd", $Usuario, $Password) or die(mysql_error());
}
catch(PDOException $e)
{
   echo $e->getMessage();
 	   die('fallo comando cone'.mysql_error());
}

?>
