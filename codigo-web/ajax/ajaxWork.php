<?php

include("ajaxClass.php");

$objSem = new ajax;
$objSem->readURLParameters();
$objSem->staticExample();

echo $objSem->result;

?>