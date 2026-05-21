<?php
include("funciones.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<link rel="stylesheet" type="text/css" href="DatePicker.css" media="screen" />
<script language="Javascript" src="DatePicker.js" type='text/javascript'></script>
</head>

<body>
<p>
  <?php // pantalla_socio(); ?>
</p>
<p>&nbsp;</p>
<form enctype='multipart/form-data' name="form1" method="post" action="">
  <label>
  <input name="radiobutton" type="radio" value="radiobutton">
  </label> 
  masculino 
  <label>
  <input name="radiobutton" type="radio" value="radiobutton" checked>
  femenino</label>
  	Fecha Inicio: <input type='text' name='fechai' size='10' maxlength='10' value="<?php echo $fechai ?>">
	<input type="button" name="selfechai" value="..."  onclick='displayDatePicker("fechai","","dmy")' />

</form>
<p>&nbsp;</p>
</body>
</html>
