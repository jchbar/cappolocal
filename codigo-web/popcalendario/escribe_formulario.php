<?php
function escribe_formulario($form, $form2, $formato, $valor, $fechai, $fechaf, $dom, $ano){
//echo $formato;
//echo $fechai; 
//echo $fechaf;
//echo $dom;
//echo $ano;
//echo $valor; 
if ($valor == '00/00/0000') {
$valor =''; }
	echo '
	<INPUT name="'.$form.'" size="10" value ="'.$valor.'"readonly="readonly" tabindex="'.$tab.'">
	<input type=button value="..." onclick="mostrarCalendar(\''. $form.'\',\''. $form2 .'\',\''.$formato.'\',\''.$valor.'\',\''.$fechai.'\',\''.$fechaf.'\',\''.$dom.'\',\''.$ano.'\')" '.$lectura.'>';	
}
?>