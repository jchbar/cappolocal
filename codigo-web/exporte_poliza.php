<?php
header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=NOMBRE.xls");

include_once('final.php');
$link = @mysql_connect($Servidor,$Usuario, $Password,'',65536) or die ("<p /><br /><p /><div style='text-align:center'>Disculpe... En estos momentos no hay conexión con el servidor, estamos realizando modificaciones.... inténtalo más tarde. Gracias....</div>");
mysql_select_db('sica', $link);


$sql="select *, round((DATEDIFF(now(), fnaci_prof) / 365),0) as edad from sgcaf200 where (UPPER(statu_prof)='JUBILA' or UPPER(statu_prof)='ACTIVO') order by cod_prof ";
echo '<table>';
$resultado=mysql_query($sql);
while ($fila200 = mysql_fetch_assoc($resultado))
{
	echo '<tr><td>'.$fila200['ced_prof']. '</td><td>'.$fila200['nombr_prof']. '</td><td>' .$fila200['ape_prof']. '</td><td>' .$fila200['edad']. '</td><td>'.($fila200['edad']<$limite?$montoa:$montoj).'</td></tr>';
}
echo '</table>';
?>
