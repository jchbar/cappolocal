<?php
include("head.php");


if (!$link OR !$_SESSION['empresa']) {
	include("noempresa.php");
	exit;
}
/*
if ($_GET['emp'] == 1) {$_GET['n'] = 1;}

*/

/*
	//$result = mysql_query("SELECT max(asiento) FROM asientos");
	//$row = mysql_fetch_row($result);
	//$asiento = $row[0] + 1;
	$fila = mysql_fetch_array(mysql_query("SELECT con_compr FROM sgcaf8co"));
	$asiento = $fila[0] + 1;
	mysql_query("UPDATE sgcaf8co SET con_compr = '$asiento' WHERE 1");
	// Tomo el valor de la fecha en que se hizo el último Asiento
	$result = mysql_query("SELECT date_format(con_ultfec,'%d/%m/%y') AS ultfechax FROM sgcaf8co");
	$row = mysql_fetch_array($result);
	$fecha = $row[0];
} else {
	$onload="onload=\"foco('cuenta11')\"";
	$readonly=" readonly='readonly'";
	$asiento = $_POST['asiento'];
	$fecha = $_POST['fecha'];
	$tipo =$_POST['tipo'];
	$referencia =$_POST['referencia'];
}
*/
?>
<style type="text/css">
<!--
.Estilo1 {color: #FF0000}
-->
</style>
<body <?php if (!$bloqueo) {echo $onload;}?>>
<?php

$readonly=" readonly='readonly'";
include("arriba.php");
$menu6=5;include("menusizda.php");
$cedula = $_GET['cedula'];
$ip = $_SERVER['HTTP_CLIENT_IP'];
if (!$ip) {$ip = $_SERVER['REMOTE_ADDR'];}
?>

<form action="contacto.php" method="post">
  <div align="center">
    <table width="371" height="217" border="1" >
      <tr>
        <td width="87">
      <tr>
        <td><b>Nombre: </b></td>
        <td width="268"><input type="text" name="nombre" size="40" maxlength="30"></td>
      </tr>
      <tr>
        <td><p><b>Email:</b></p>        </td>
        <td><p>
          <input type="text" name="email" size="40" maxlength="100">
        </p>        </td>
      </tr>
      <tr>
        <td><b>Asunto: </b></td>
        <td><input type="text" name="comentario2" size="40" maxlength="200"></td>
      </tr>
      <tr>
        <td height="111"><b>Comentario:</b></td>
        <td><label>
          <textarea name="textarea" cols="40" rows="5"></textarea>
        </label></td>
      </tr>
    </table>
  </div>
  <p align="center"><b><span class="Estilo1">*</span>C&oacute;digo de confirmaci&oacute;n:</b> <img src="captcha.php" />
      <input type="text" name="codigo" size="25">
  </p>
  <p align="center">
    <input name="submit" type="submit" value="    Enviar    " />
     <input name="reset" type="reset" value="    Borrar    ">
    <br>
  </p>
  <p>&nbsp;</p>
</form>
<?php
$nombre=$_POST['Nombre'];
$asunto=$_POST['Asunto'];
$email=$_POST ['Email'];
$comentario=$_POST ['Comentario'];
?>
<form name="form1" method="post" action="">
  <label></label>
</form>
