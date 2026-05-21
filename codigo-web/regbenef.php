<?php

//Copyright (C) 2000-2006  Antonio Grandío Botella http://www.antoniograndio.com
//Copyright (C) 2000-2006  Inmaculada Echarri San Adrián http://www.inmaecharri.com

//This file is part of Catwin.

//CatWin is free software; you can redistribute it and/or modify
//it under the terms of the GNU General Public License as published by
//the Free Software Foundation; either version 2 of the License, or
//(at your option) any later version.

//CatWin is distributed in the hope that it will be useful,
//but WITHOUT ANY WARRANTY; without even the implied warranty of
//MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//GNU General Public License for more details:
//http://www.gnu.org/copyleft/gpl.html

//You should have received a copy of the GNU General Public License
//along with Catwin Net; if not, write to the Free Software
//Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

include("head.php");
?>
<script language="Javascript" src="selec_fecha_pasado.js" type='text/javascript'></script>
<?php 

if (!$link OR !$_SESSION['empresa']) {
	include("noempresa.php");
	exit;
}
?>

<body
<?php
if (!$bloqueo AND $cedula AND $accion AND ($accion == 'agrben' OR $accion == 'editben')) {echo " onload=\"foco('afafi')\"";}
?>
>

<?php
include("arriba.php");
$menu11=3;include("menusizda.php");

if (!$cedula) {

	echo "<form method='post' name='form1'>\n";
	echo "Cédula: <input type='text' name='cedula' size='10' maxlength='10'>\n";
	echo "<input type='submit' name = 'formu' value='Buscar Beneficiarios'>\n";
	echo "</form>\n";
	echo "</div></body></html>";
	exit;

}
if ($accion == "agrben1" ) { // ($debe != 0 OR $haber != 0)) {
	$agregar=1;
	$herafi=(($herafi==on)?1:0);
	include ("regbeag1.php");
 }

if ($accion == "editben1" ) { // ($debe != 0 OR $haber != 0)) {
// 	include ("editapu1.php");
	$herafi=(($herafi==on)?1:0);
	$agregar=2;
	include ("regbeag1.php");
}

if ($accion == "boapu") {
// 	include ("borrapu1.php");
	$row_id=$_GET['reg_afi'];
 	$sql = "DELETE FROM sgcaf220 WHERE reg_afi = '$row_id'";
	if (!mysql_query($sql)) die ("El usuario $usuario no tiene permiso para eliminar beneficiarios.");

}

/*
if ($accion == "boasi") {
 	$sql = "DELETE FROM sgcaf220 WHERE com_nrocom = '$cedula'";
	if (!mysql_query($sql)) die ("El usuario $usuario no tiene permiso para borrar Asientos.");
	$sql = "DELETE FROM sgcaf220 WHERE enc_clave = '$cedula'";
	if (!mysql_query($sql)) die ("El usuario $usuario no tiene permiso para borrar Asientos.");
//	mysql_query("UPDATE factrec SET asiento = '' WHERE asiento = '$asiento'");
	echo "Asiento<span class='b'> ".$cedula." </span>borrado.\n";
	echo "</div></body></html>";exit;
}
*/

if ($cedula) {
	$result = mysql_query("SELECT * FROM sgcaf220 WHERE ced_afi = '$cedula'");
	if (mysql_num_rows($result) == 0) {
		echo "<p />Cedula <span class='b'>$cedula</span> no tiene beneficiarios registrados</div></body></html>";
//		exit;
  
	}
	$fila = mysql_fetch_array($result);
}

// echo "<form enctype='multipart/form-data' name='justificante' action='editasi2.php?cedula=$cedula' method='post'>";
// echo "<label>Soporte</label> <input type='file' name='fich' size='19' maxlength='19'>";
// echo " (Si el asiento ya tiene un justificante será sustituído)";
// echo "<br /><label>Explicación</label> <textarea name='explicacion' rows='6' cols='90'>$fila[1]</textarea>";
// echo " <input type='submit' name='boton' value=\" >> \">";
// echo "</form>";

echo "<table class='basica 100 hover' width='850'>";
// width='100%'
cabben(2);
//totalbene($cedula);
beneficiarios($cedula,"1",$_SESSION['moneda'],$_SESSION['deci'],$_GET['bojust']);

echo "</table><p />";

if ($accion == 'editben') {
	include ("regbeed.php");
}

if ($accion == 'agrben') {
	include ("regbeag.php");
}
?>

</div></body></html>

<?php
// ----------------------------------
function cabben($edborr) {
echo "<tr>";
if ($edborr) {echo "<th width='200' colspan=2></th>";}
echo '<th width="100">Cedula</th><th width="300">Nombre/Apellido Beneficiario</th><th width="100">Nacimiento</th><th width="80">Edad</th><th width="100">Parentesco</th><th width="100">Heredero</th><th width="100">%</th><th width="100">Recibe Seguro de Vida</th></tr>';
}

//--------------------------------
function beneficiarios($cedula, $edborr, $por, $deci, $bojust) {

// if ($bojust == $asiento) {mysql_query("UPDATE asientos SET fich = '', tipofich='' WHERE asiento = '$asiento'");}

$result = mysql_query("SELECT cod_prof, ced_prof, ape_prof, nombr_prof FROM sgcaf200 WHERE ced_prof = '$cedula'");
if ($result) {$fichero = mysql_fetch_assoc($result);}

$cols = 6;

if ($edborr) {$cols = $cols+2;}

// echo "<tr><td class='blanco b' colspan='$cols'>Titular: <a href='editasi2.php?cedula=$cedula'>".$cedula."</a> Nombre: ";
echo "<tr><td class='blanco b' colspan='$cols+3'>Titular: ".$cedula." Nombre: " .trim($fichero['ape_prof']) . ', '.trim($fichero['nombr_prof']);
// echo $a[2]."/".$a[1]."/".$a[0]; // substr($a[0],2,2);
echo "</b>";

echo "</td></tr>";

$result = mysql_query("SELECT * FROM sgcaf220 WHERE ced_afi = '$cedula' ORDER BY nom_afi, afi_afi");
$suma=0;
$segvid=0;
while ($fila = mysql_fetch_assoc($result)) {

	echo "<tr>";
	if ($edborr) {
		echo "<td><a href='regbenef.php?reg_afi=".$fila[reg_afi]."&cedula=$cedula&accion=editben' target='_self'> <img src='imagenes/16-em-pencil.png' width='16' height='16' border='0' title='Editar' alt='Editar' /></a></td>";
		echo "<td><a href='regbenef.php?reg_afi=".$fila[reg_afi]."&cedula=$cedula&accion=boapu' onclick='return borrar_benef()'><img src='imagenes/16-em-cross.png' width='16' height='16' border='0' title='Eliminar' alt='Eliminar' /></a></td>";
	}
//	echo "<td><a href=\"extractoctas3.php?cuenta=".$fila["com_cuenta"]."&datos='no'\">".$fila["com_cuenta"]."</a></td>";
	echo "<td>".$fila["afi_afi"]."</td>";
	echo "<td>".$fila["nom_afi"]."</td>";
	echo "<td>".convertir_fechadmy($fila["nac_afi"])."</td>";
	echo "<td>".cedad(convertir_fechadmy($fila["nac_afi"]))."</td>";
	echo "<td>".$fila["par_afi"]."</td>";
	echo "<td><img src='imagenes/".($fila["rec_afi"]==0?'here_down':'here_up').".png'  width='16' height='16' border='0' /></td>";
	echo "</td><td class='dcha'>";
	if (($fila["por_afi"] == 0) || ($fila['rec_afi'] == 0))
	{
//		echo "&nbsp;";
		echo number_format(0,$deci,'.',',');
	} else {
		echo number_format($fila["por_afi"],$deci,'.',',');
		$suma+=$fila['por_afi'];
	}
	echo "</td>";
	echo "<td><img src='imagenes/".($fila["sv_afi"]==0?'here_down':'here_up').".png'  width='16' height='16' border='0' /></td>";
	if ($fila["sv_afi"]==1)
		$segvid++;
	echo "</tr>";

}

//if ($asi['enc_debe']-$asi['enc_haber'] != 0) {
	echo "<tr><td class='rojo dcha b' colspan=".($cols);
//	echo "<span class='blanco b'> Diferencia de ".number_format(($asi['enc_debe']-$asi['enc_haber'])*$por,$deci,',','.')."</span>";
/*
}
else 
	echo "<tr><td class='blanco dcha b' colspan=".($cols-2).">";
*/
echo "  Total: </td><td class='blanco dcha b'>".number_format($suma,$deci,',','.')."</td>";
echo "</tr><tr><td colspan='$cols' class='verde'>&nbsp;</td></tr>
<p>";

echo "<a href='regbenef.php?cedula=$cedula&accion=agrben'>Añadir Beneficiarios</a><p />";
//echo 'vidas '.$segvid;
if (($suma == 100) or ($suma == 0))
// echo "<a href='editasi2.php?cedula=$cedula&accion=boasi' onclick='return borrar_asiento()'>Borrar Beneficiarios</a>";
// echo "&nbsp;&nbsp;&nbsp; - &nbsp;&nbsp;&nbsp;<a href='regbenef.php?cedula=$cedula&accion=agrben'>Añadir Beneficiarios</a><p />";
// echo "<a href='regbeim.php?cedula=$cedula'>Imprimir Planilla</a><p />";
{
	if ($segvid == 1)
		echo "<a target=\"_blank\" href=\"regbeim.php?cedula=$cedula\" onClick=\"info.html\', \'\',\'width=250, height=190\')\">Imprimir Planilla</a>"; 
	else echo '<fieldset><legend>Tiene mas de un beneficiario para el seguro de vida</legend>';

}
else 
	echo 'El total de beneficiarios debe ser igual a 100 o igual a cero para poder generar la planilla';

  
/*
echo "<td colspan='7' class='dcha'>";
echo "<input type = 'submit'name = 'formu' value = 'Añadir' tabindex='10' onclick='return comprueba_beneficiarios(form1)'>";
echo "</td>";
*/
}
  
function pantalla_beneficiarios($afiafi,$nomafi, $nacafi, $parafi, $herafi, $porafi, $svafi)
{
?>
<table class='basica'>
<tr><th width="50">Cedula</th><th width="100">Apellidos y Nombres </th><th width="120">Nacimiento</th><th width="80">Parentesco</th><th width="80">Heredero</th><th width="80">%</th><th>Recibe S.V</th></tr>
<tr><td>
<input type = 'text' maxlength='8' size='8' name='afiafi' value='<?php echo $afiafi;?>' tabindex='3' onChange='conMayusculas(this)'>
</td><td>
<input type = 'text' size='40' maxlength='40' name='nomafi' tabindex='4' onChange='conMayusculas(this)' value ='<?php echo $nomafi?>'>
</td><td>
	<input type="hidden" name="date3" id="date3" value=" <?php  echo convertir_fechadmy($nacafi); ?>"/>
   <span style="background-color: #ff8; cursor: default;"
         onmouseover="this.style.backgroundColor='#ff0';"
         onmouseout="this.style.backgroundColor='#ff8';"
         id="show_date3" 
   ><?php  echo convertir_fechadmy($nacafi); ?></span> *
<script type="text/javascript">
    Calendar.setup({
//		showAt(220, 250)
//		position       : 	{100,300},
        inputField     :    "date3",     // id of the input field
        ifFormat       :    "%d/%m/%Y",     // format of the input field (even if hidden, this format will be honored)
        displayArea    :    "show_date3",       // ID of the span where the date is to be shown
        daFormat       :    "%A, %B %d, %Y",// format of the displayed date
        align          :    "Tl",           // alignment (defaults to "Bl")
        singleClick    :    true,
		weekNumbers    :    false, 

// desactivacion de 18 años pa' tras


		dateStatusFunc :    function (date) { // disable weekend days (Saturdays == 6 and Subdays == 0)
						var today = new Date();
						return (
//							  (date.getTime() < today.getTime()-((365*18)*24*60*60*1000))
							  (date.getTime() > today.getTime()-(1*24*60*60*1000)) 
							  // || date.getTime() > today.getTime()+(10*24*60*60*1000))	date.getDay() == 0 || 
							  ) ? true : false;  }
    });
</script>

</td><td>
<?php
$elparentesco=$parafi;
echo '<select name="parafi" size="1" tabindex="6">';
$sql="select nombre from sgcaf000 where tipo='Parentesco' order by nombre";
$resultado=mysql_query($sql);
while ($fila2 = mysql_fetch_assoc($resultado)) {
	echo '<option value="'.$fila2['nombre'].'" '.(($elparentesco==$fila2['nombre'])?'selected':'').'>'.$fila2['nombre'].'</option>';}
echo '</select> '; 
echo '</td><td>';
$activar=' ';
if (($herafi == 1)) {$activar='checked="checked"'; } else { $activar = ' '; }
// value="<?php echo $herafi;>" 
?>
<input name="herafi" type="checkbox" id="herafi" tabindex='7' <?php echo $activar;?> /> 
Heredero
</td><td>
<input type='text' name='porafi' size='8' maxlengt='8' tabindex='8' value ="<?php echo $porafi;?>">
</td>
<?php
$activar=' ';
if (($svafi == 1)) {$activar='checked="checked"'; $svafi=1; } else { $activar = ' '; $svafi=0; }
echo '<td><input name="svafi" type="checkbox" value="1" id="svafi" tabindex="9" '.$activar.' /> </td>';
echo '</tr>';
}

?>


