<?php

include("ajaxClass.php");

$objSem = new ajax;
$objSem->readURLParameters();
$objSem->staticExample();

?>

<html>
<script src="ajax.js" type="text/javascript"></script>
<body>
<br><br>
<p>AJAX Example:</p>
<form name="form1" action="" onSubmit="return ajax_call()">
	<input type="text" name="num1" id="num1"></input> *
	<input type="text" name="num2" id="num2"></input> = 
	<input type="text" name="result" id="result"></input>
	<br><br>
	<input type="submit" name="semajax" value="AJAX"></input>
</form>
<!-- -->
<br><br>
<p>Standard Example:</p>
<form name="form1" action="indexx.php">
	<input type="text" name="num1" id="num1" value=<? echo $objSem->num1 ?>></input> *
	<input type="text" name="num2" id="num2" value=<? echo $objSem->num2 ?>></input> = 
	<input type="text" name="result" id="result" value=<? echo $objSem->result ?>></input>
	<br><br>
	<input type="submit" name="semajax" value="Standard"></input>
</form>
</body>
</html>