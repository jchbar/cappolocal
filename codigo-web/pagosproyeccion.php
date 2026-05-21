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
if ($_GET['emp'] == 1) {$_GET['n'] = 1;}

extract($_GET);
extract($_POST);
// if ($_GET['n'] == 1) {
if (!$monto) {
	$onload="onload=\"foco('monto')\"";
} else {
}

?>

<body <?php if (!$bloqueo) {echo $onload;}?>>

<?php

include("arriba.php");
$menu11=1;include("menusizda.php");

if (! $_POST['monto']) {

	echo "<fieldset><legend>Informacion para la Proyeccion de Pagos</legend>";
	echo "<form enctype='multipart/form-data' name='form1' action='pagosproyeccion.php' method='post' onSubmit='return altaasim(form1)'>";

	echo "<table align='center' class='basica 100 hover' width='700'>";
	echo '<tr>';
	echo '<td>';
	echo "Monto";
	echo "<input type='text' name='monto' value='0' maxlength='11' size='11'> ";
	echo '</td>';

	echo '<td>';
	echo "Fecha";
//	$hoy = date("d/m/Y");
//	escribe_formulario(fecha, form1.fecha, 'd/m/yyyy', '', '', $hoy, '0', '10'); 
?>
<script type="text/javascript">
// setActiveStyleSheet(this, 'green');
setActiveStyleSheet(document.getElementById("defaultTheme"), "green");
</script>
	<input type="text" name="date3" id="sel3" size="12" readonly ><input type="reset" value=" ... " onClick="return showCalendar('sel3', '%d/%m/%Y');"><br />
	<?php 

	echo '</td>';
	echo '</tr>';

	echo '<tr>';
	echo '<td>';
	echo '% Interes ';
	echo "<input type='text' name='interes' value='0' maxlength='11' size='11'> ";
	echo '</td>';

	echo '<td>';
	echo 'Nro. Cuotas  ';
	echo "<input type='text' name='cuotas' value='24' maxlength='11' size='11'> ";
	echo '</td>';
	echo '</tr>';

	echo '<tr>';
	echo '<td>Los Intereses son</td>';
	echo '<td >';
	echo '<select id="concepto" name="concepto" size="1">';
	echo '<option value="Amortizada" selected>Amortizada</option>';
	echo '<option value="Descontada">Descontada</option>';
  	echo '</select> ';	
	echo '</td></tr>';

	echo '<tr><td align="center" colspan="2">';
	echo "<input type = 'submit' value = 'Calcular'>";
	echo '</td>';


	echo '</table>';

	echo '</fieldset><p style="clear:both">';
	echo '</form>';
}
else // calcular y mostrar
{
	$sql="CREATE TEMPORARY TABLE amortiza_temp (
		ncuota int(11) NOT NULL auto_increment,
		fecha date NOT NULL default '0000-00-00',
		monto decimal (12,2) NOT NULL default 0.00,
		amorcapi decimal (12,2) NOT NULL default 0.00,
		amoracum decimal (12,2) NOT NULL default 0.00,
		inte decimal (12,2) NOT NULL default 0.00,
		inteacum decimal (12,2) NOT NULL default 0.00,
		pagoacum decimal (12,2) NOT NULL default 0.00,
    PRIMARY KEY  (`ncuota`),
    UNIQUE KEY `id` (`ncuota`)
      ) ;";
	$rsql=mysql_query($sql);
//	$asql=mysql_fetch_assoc($rsql);
	  
	$lafecha=convertir_fecha($_POST['date3']);
	$ncuotas=$_POST['cuotas'];
	$monto=$_POST['monto'];
	$_elmonto=$c=$monto;
	$interes=$_POST['interes']; // / 100;
	$z=cal_int($interes,$ncuotas,$c,24,0,$i2); // calcular aqui luego la descontada
	if ($concepto == 'Amortizada')
		$z=cal_int($interes,$ncuotas,$c,24,0,$i2); // calcular aqui luego la descontada
	else 
		$z=$monto/$ncuotas;
//	$i2=0;
//	IF thisform.optiongroup3.Value=2
//	z=cal_int(m_interes,n,c)
//	else
/*
	$sql="select * from interes_temp";
	$rsql=mysql_query($sql);
	$asql=mysql_fetch_assoc($rsql);
	$i2=$asql['elinteres'];
	echo 'i2 bdd'.$asql['elinteres'] . ' / '.$i2;
*/
	$original=$_lacuota=$z;
	$k = 0;	         // k = contador
	$ia = 0;         // ia = interes acumulado
	$cu = 0;     // cu = cuota
	$ac = 0;     // ac = acumulado
	$tc = $z;     // tc = total cuota
	$ta = 0;     // ta = total acumulado
	$c1 = $c;     //  i1 = interes

	for ($m=0;$m<$ncuotas;$m++) 
	{
		$k = $k + 1;
		$i1 = $c1 * $i2;
		$cu = $z - $i1;
		$c1 = $c1 - $cu;
		$ia = $ia + $i1;
		$ac = $ac + $cu;
		$ta = $ta + $z;
	}


//	ENDIF 
	echo "<table align='center' class='basica 100 hover' width='700'>";
		echo '<tr>';
			echo '<th>Cuota </th>';
			echo '<td>'.number_format($z,2,".",",").'</td>';
			echo '<th>Nro. Cuota </th>';
			echo '<td>'.number_format($ncuotas,0,".",",").'</td>';
			echo '<th>Monto Solicitado </th>';
			echo '<td>'.number_format($monto,2,".",",").'</td>';
			echo '<th>Interes: '.number_format($interes,2,'.',',').'% </th>';
			echo '<td align="right">'.number_format($ia,2,".",",").'</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<th colspan="2" align="right">Neto a Recibir </th>';
			if ($concepto == 'Amortizada')
				echo '<td colspan="2" align="left">'.number_format($monto,2,".",",").'</td>';
			else 
				echo '<td colspan="2" align="left">'.number_format($monto-$ia,2,".",",").'</td>';
			echo '<td colspan="4" align="center">';
			echo "<a target=\"_blank\" href=\"proyeccionpdf.php?prestamo=NO&monto=$monto&interes=$interes&concepto=$concepto&fecha=$lafecha&ncuotas=$ncuotas\" onClick=\"info.html\', \'\',\'width=250, height=190\')\">Imprimir Proyeccion ($concepto) </a>"; 	
			echo '</td>';

		echo '</tr>';
	
	$z= $original; // $monto/$ncuotas;
	$_lacuota=$z;
	$k = 0;	         // k = contador
	$ia = 0;         // ia = interes acumulado
	$cu = 0;     // cu = cuota
	$ac = 0;     // ac = acumulado
	$tc = $z;     // tc = total cuota
	$ta = 0;     // ta = total acumulado
	$c1 = $c;     //  i1 = interes

		echo '<tr>';
			echo '<td>';
			echo '#Cuota';
			echo '</td>';
			echo '<td>';
			echo 'Fecha';
			echo '</td>';
			echo '<td>';
			echo 'Saldo';
			echo '</td>';
			echo '<td>';
			echo 'Amortizacion<br>Cuota';
			echo '</td>';
			echo '<td>';
			echo 'Amortizacion <br>Acumulada';
			echo '</td>';
			echo '<td>';
			echo 'Interes';
			echo '</td>';
			echo '<td>';
			echo 'Interes <br>Acumulado';
			echo '</td>';
			echo '<td>';
			echo 'Pagos <br>Acumulados';
			echo '</td>';
/*
			echo '<td>';
			echo number_format($ta,2,".",",");
			echo '</td>';
*/
		echo '</tr>';

	for ($m=0;$m<$ncuotas;$m++) 
	{

		$k = $k + 1;
		$i1 = $c1 * $i2;
//	echo 'i1 '.$i1;
		$cu = $z - $i1;
		$c1 = $c1 - $cu;
		$ia = $ia + $i1;
		$ac = $ac + $cu;
		$ta = $ta + $z;
		
/*
		$sql="insert into amortiza_temp (fecha, monto) VALUES ('$fecha', $c1)";
		$rsql=mysql_query($sql);
*/		
/*
		$sql="update amortiza_temp set amorcapi = 
		amoracum decimal (12,2) NOT NULL default 0.00,
		inte decimal (12,2) NOT NULL default 0.00,
		inteacum decimal (12,2) NOT NULL default 0.00,
		pagoacum decimal (12,2) NOT NULL default 0.00,
*/

		echo '<tr>';
			echo '<td align="right">';
			echo $k;
			echo '</td>';
			echo '<td align="center">';
			echo convertir_fechadmy($lafecha); 
			echo '</td>';
			echo '<td align="right">';
			echo number_format($c1,2,".",",");
			echo '</td>';
			echo '<td align="right">';
			echo number_format($cu,2,".",",").'<br>';
			echo '</td>';
			echo '<td align="right">';
			echo number_format($ac,2,".",",").'<br>';
			echo '</td>';
			echo '<td align="right">';
			echo number_format($i1,2,".",",").'<br>';
			echo '</td>';
			echo '<td align="right">';
			echo number_format($ia,2,".",",");
			echo '</td>';
			echo '<td align="right">';
			echo number_format($ta,2,".",",");
			echo '</td>';
		echo '</tr>';
		$sql="select date_add('$lafecha',INTERVAL 7 DAY) as fecha";
		$rsql=mysql_query($sql);
		$asql=mysql_fetch_assoc($rsql);
		$lafecha=($asql['fecha']);
	}
	echo '</table>';
}
echo '</div></body></html>';

?>
