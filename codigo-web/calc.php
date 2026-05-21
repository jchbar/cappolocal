<?php

if ($_POST['guarcerr']) {
	mysql_query("UPDATE usuarios SET calc = '".$_POST['listado']."' WHERE usuario = '".$_SESSION['usuario']."'");
}
$listado = mysql_query("SELECT calc FROM usuarios WHERE usuario = '".$_SESSION['usuario']."'");
if ($listado) {
	$listado = mysql_fetch_array($listado);
	$listado = $listado[0]."\n-----\n";
}

echo "<form name='calcu' method='post' style='display:inline'>";

echo "<span class='rojo b'>CALCULADORA EN CONSTRUCCIÓN</span>";

echo "<input type='text' id = 'vent' name='vent' size='30' maxlength='30' onkeyup=\"calcula('',calcu,event)\">";

echo "<input type='hidden' name='valor1'>";
echo "<input type='hidden' name='valor2'>";
echo "<input type='hidden' name='operador'>";


//echo "<div style='float:left;display:online'>";
echo "<br /><input type='button' value='&nbsp;C&nbsp;' class='cour' onclick=\"calcula('C',calcu,'')\">";
echo "<input type='button' value='&nbsp;/&nbsp;' class='cour' onclick=\"calcula('/',calcu,'')\">";
echo "<input type='button' value='&nbsp;*&nbsp;' class='cour' onclick=\"calcula('*',calcu,'')\">";
echo "<input type='button' value='&nbsp;-&nbsp;' class='cour' onclick=\"calcula('-',calcu,'')\">";

echo "<br />";
echo "<input type='button' value='&nbsp;7&nbsp;' class='cour' onclick=\"calcula('7',calcu,'')\">";
echo "<input type='button' value='&nbsp;8&nbsp;' class='cour' onclick=\"calcula('8',calcu,'')\">";
echo "<input type='button' value='&nbsp;9&nbsp;' class='cour' onclick=\"calcula('9',calcu,'')\">";
echo "<input type='button' value='&nbsp;+&nbsp;' class='cour' onclick=\"calcula('+',calcu,'')\">";

echo "<br />";
echo "<input type='button' value='&nbsp;4&nbsp;' class='cour' onclick=\"calcula('4',calcu,'')\">";
echo "<input type='button' value='&nbsp;5&nbsp;' class='cour' onclick=\"calcula('5',calcu,'')\">";
echo "<input type='button' value='&nbsp;6&nbsp;' class='cour' onclick=\"calcula('6',calcu,'')\">";
echo "<input type='button' value='&nbsp;%&nbsp;' class='cour' onclick=\"calcula('%',calcu,'')\">";

echo "<br />";
echo "<input type='button' value='&nbsp;1&nbsp;' class='cour' onclick=\"calcula('1',calcu,'')\">";
echo "<input type='button' value='&nbsp;2&nbsp;' class='cour' onclick=\"calcula('2',calcu,'')\">";
echo "<input type='button' value='&nbsp;3&nbsp;' class='cour' onclick=\"calcula('3',calcu,'')\">";
echo "<input type='button' value='&nbsp;=&nbsp;' class='cour' onclick=\"calcula('=',calcu,'')\">";

echo "<br />";
echo "<input type='button' value='&nbsp;0&nbsp;' class='cour' onclick=\"calcula('0',calcu,'')\">";
echo "<input type='button' value='&nbsp;.&nbsp;' class='cour' onclick=\"calcula('.',calcu,'')\">";
echo "<input type='submit' name='guarcerr' value='guardar y cerrar'>";
//echo "</div>";

//echo "<div style='float:right;display:inline'>";
echo "<textarea id='listado' name='listado' rows='6' cols='40'>$listado</textarea>";

echo "<br />";

//echo "</div>";

echo "</form>";
?>
