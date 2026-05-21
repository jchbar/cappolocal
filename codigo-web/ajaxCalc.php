<?php
session_start();
extract($_GET);
extract($_POST);
extract($_SESSION);

// include("conex.php");


$suma=0;
$suma='xx'.count(urldecode($_POST['cancelar'])).'xx';
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
$suma.='(20)';
header("Content-Type: text/xml");
echo '<?xml version="1.0" encoding="utf-8"?>';
echo "<resultados>";
// echo utf8_encode("<cuota>$cuota</cuota>");		// sirve asi y como esta abajo tambien
echo "<cancelados>".$suma."</cancelados>";
// echo "<montoneto>".$neto."</montoneto>";
// echo "<gastosadministrativos>".$gtoadm."</gastosadministrativos>";
echo "</resultados>";


?>