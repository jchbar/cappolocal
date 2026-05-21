<?php
include("head.php");
include("paginar.php");
////include("popcalendario/escribe_formulario.php");
extract($_GET);
extract($_POST);
extract($_SESSION);

if (!$link OR !$_SESSION['empresa']) {
	include("noempresa.php");
	exit;
}
?>
<body <?php if (!$bloqueo) {echo $onload;}?>>
<?php
$readonly=" readonly='readonly'";
include("arriba.php");
$menu61=1;include("menusizda.php");
$ip = $_SERVER['HTTP_CLIENT_IP'];
if (!$ip) {$ip = $_SERVER['REMOTE_ADDR'];}
// echo 'aretenciones' .$aretenciones[0];
$codigo=$_POST['codigo'];
// echo 'codigo '.$codigo;
if (!$codigo) {
	$correcto=0;
//	echo "<div id='div1'>";
	echo "<form action='hishab.php?accion=fecha' name='form1' method='post'>";
	 echo "<form enctype='multipart/form-data' method='post' name='form1'>Código: <input type='text' name='codigo' size='10' maxlength='5'> \n";
	echo '<input type="radio" name="aportespagos" value = "1"/> Antes de la Reconversión Monetaria';
	 echo '<input type="radio" name="aportespagos" value = "2" checked /> Después de la Reconversión Monetaria <br />';
	echo '<input type="submit" name="Submit" value="Enviar" />';
	echo '</form>';
}
if ($codigo) {
	// echo '1';
	$result = mysql_query("Select cod_prof from sgcaf200 where cod_prof= '$codigo'");
	if (mysql_num_rows($result) == 0) {
		echo "<p />Código <span class='b'>$codigo</span> no esta registrado</div></body></html>";
				//		exit;
			}
    else 
	if ($aportespagos == '1') {
    // echo '2';
		if ($accion == "fecha") {
	//	echo '3';
			echo "<form action='hishab.php?accion=fechaa' name='form1' method='post'>";
	?>
	<input type="hidden" name="fechai" id="fechai" value="01/01/1993"/>
   <span style="background-color: #ff8; cursor: default;"
         onmouseover="this.style.backgroundColor='#ff0';"
         onmouseout="this.style.backgroundColor='#ff8';"
         id="lafechai" 
   ><?php  echo '01/01/1993'; ?></span> *
<script type="text/javascript">
    Calendar.setup({
//		showAt(220, 250)
//		position       : 	{100,300},
        inputField     :    "fechai",     // id of the input field
        ifFormat       :    "%d/%m/%Y",     // format of the input field (even if hidden, this format will be honored)
        displayArea    :    "lafechai",       // ID of the span where the date is to be shown
//        daFormat       :    "%A, %B %d, %Y",// format of the displayed date
        daFormat       :    "%B %d, %Y",// format of the displayed date
        align          :    "Tl",           // alignment (defaults to "Bl")
        singleClick    :    true,
		weekNumbers    :    false, 
		range          :    [1993, 2007],

// desactivacion de 18 años pa' tras


/*
		dateStatusFunc :    function (date) { // disable weekend days (Saturdays == 6 and Subdays == 0)
						var today = new Date();
						return (
//							  (date.getTime() < today.getTime()-((365*18)*24*60*60*1000))
							  (date.getTime() > today.getTime()-(1*24*60*60*1000)) 
							  // || date.getTime() > today.getTime()+(10*24*60*60*1000))	date.getDay() == 0 || 
							  ) ? true : false;  }
*/
					    });
</script>
	</td>
  </tr>
  <tr>

	<input type="hidden" name="fechaf" id="fechaf" value="31/12/2007"/>
   <span style="background-color: #ff8; cursor: default;"
         onmouseover="this.style.backgroundColor='#ff0';"
         onmouseout="this.style.backgroundColor='#ff8';"
         id="lafechaf" 
   ><?php  echo 'esta'. '12/31/2007'; ?></span> *
<script type="text/javascript">
    Calendar.setup({
//		showAt(220, 250)
//		position       : 	{100,300},
        inputField     :    "fechaf",     // id of the input field
        ifFormat       :    "%d/%m/%Y",     // format of the input field (even if hidden, this format will be honored)
        displayArea    :    "lafechaf",       // ID of the span where the date is to be shown
//        daFormat       :    "%A, %B %d, %Y",// format of the displayed date
        daFormat       :    "%B %d, %Y",// format of the displayed date
        align          :    "Tl",           // alignment (defaults to "Bl")
        singleClick    :    true,
		weekNumbers    :    false, 
		range          :    [1993, 2007],
					    });
</script>
	</td>
  </tr>
  <tr>

	<?php 	


//			solicitar_fechasnew('31/12/1993','30/12/1993','31/12/2007','31/12/2007','30/12/1993','31/12/2007');
			echo '<input type="hidden" name="aportespagos" value="1">';
			echo '<input type="hidden" name="codigo" value="'.$codigo.'">';
			echo '<input type="submit" name="Submit" value="Buscar Históricos de Haberes" />';
			echo '</form>';
		}
		if ($accion == "fechaa") {
 	  		if ($fechai)  
			{
			   	$lfi=convertir_fecha($fechai);
		    	$lff=convertir_fecha($fechaf);
				$ord = $_GET['ord'];
				if (!$ord) $ord='fecha';
				$conta = $_GET['conta'];
				if (!$_GET['conta']) $conta = 1;
				$sql1="CREATE TEMPORARY TABLE la4 select cod_prof as codsoc, hab_prof, hab_ucla, 0 as ret_opsu, descri, fecha, date_format(fecha, '%d/%m/%Y') AS fechax  from fhis200 where cod_prof= '$codigo' and ((fecha >= '$lfi') AND (fecha<= '$lff') AND ('$lff'<= '2007-12-31'))";
				$sql2="CREATE TEMPORARY TABLE la5 select codsoc,   ret_capu as hab_prof, ret_ucla as hab_ucla, ret_opsu, 'Retiro de Haberes' as descri, fechareti as fecha, date_format(fechareti, '%d/%m/%Y') AS fechax  from sgcaf700 where codsoc='$codigo' and ((fechareti >= '$lfi') AND (fechareti<= '$lff') AND ('$lff'<= '2007-12-31'))";
				$f4=mysql_query($sql1) or die(mysql_error());
				$f5=mysql_query($sql2) or die(mysql_error());
				$sql3="CREATE TEMPORARY TABLE la6 select * from la4 union select * from la5 ";
				$f3=mysql_query($sql3) or die(mysql_error());

				$sql="select * from la6 order by fecha "; //." LIMIT ".($conta-1).", 20";
				$result=mysql_query($sql);
				$sql = "SELECT COUNT(codsoc) AS cuantos from la6 ";
				$rs = mysql_query($sql);
				$row= mysql_fetch_array($rs);
				$numasi = $row[cuantos]; 

				if ($numasi == 0) 
				{
					echo "<p /><br /><p />Error historiales Desde: $fechai  Hasta:  $fechaf </span class='b'>.";
					echo "<p /><br /><p />Por favor introducir nueva fecha</span class='b'>.";
					echo '<div style="clear:both"></div>';
					echo '<p /><div class="noimpri" style="clear:both;text-align:center">';
					echo '<a href="hishab.php?accion=fecha&aportespagos=1&codigo=$codigo"><input type="button" name="boton" value="regresar" tabindex="3">';
					exit;
				}
				echo "<a target=\"_blank\" href=\"hishabpdf.php?codigo=$codigo&aportespagos=1&lfi=$lfi&lff=$lff&accion=fechaa\" onClick=\"info.html\', \'\',\'width=250, height=190\')\">Imprimir Historiales de Haberes</a>";
			}		// fechai 
		$sql="SELECT ape_prof, nombr_prof, ced_prof FROM sgcaf200 WHERE cod_prof= '$codigo'";
		$resultado=mysql_query($sql);
		$fila2 = mysql_fetch_assoc($resultado);
		mostrar($fila2, $result);
}
}
/********************************************************************************************************************************/
	else if ($aportespagos == '2'){
		if ($accion == "fecha") {
			echo "<form action='hishab.php?accion=fechad' name='form1' method='post'>";
			$hoy = date("d/m/Y");
			$sqlano='select substr(now(),1,4) as ano';
			$sqlfano=mysql_query($sqlano);
			$sqlrano=mysql_fetch_assoc($sqlfano);
			$rango='2008';
			$rango.=', '.$sqlrano['ano'];
//			echo $rango;

?>
	<input type="hidden" name="fechai" id="fechai" value="01/01/2008"/>
   <span style="background-color: #ff8; cursor: default;"
         onmouseover="this.style.backgroundColor='#ff0';"
         onmouseout="this.style.backgroundColor='#ff8';"
         id="lafechai" 
   ><?php  echo '01/01/2008'; ?></span> *
<script type="text/javascript">
    Calendar.setup({
//		showAt(220, 250)
//		position       : 	{100,300},
        inputField     :    "fechai",     // id of the input field
        ifFormat       :    "%d/%m/%Y",     // format of the input field (even if hidden, this format will be honored)
        displayArea    :    "lafechai",       // ID of the span where the date is to be shown
//        daFormat       :    "%A, %B %d, %Y",// format of the displayed date
        daFormat       :    "%B %d, %Y",// format of the displayed date
        align          :    "Tl",           // alignment (defaults to "Bl")
        singleClick    :    true,
		weekNumbers    :    false, 
		range          :    [<?php echo $rango; ?>],
					    });
</script>
	</td>
  </tr>
  <tr>

	<input type="hidden" name="fechaf" id="fechaf" value="<?php echo $hoy; ?>"/>
   <span style="background-color: #ff8; cursor: default;"
         onmouseover="this.style.backgroundColor='#ff0';"
         onmouseout="this.style.backgroundColor='#ff8';"
         id="lafechaf" 
   ><?php  echo $hoy; ?></span> *
<script type="text/javascript">
    Calendar.setup({
//		showAt(220, 250)
//		position       : 	{100,300},
        inputField     :    "fechaf",     // id of the input field
        ifFormat       :    "%d/%m/%Y",     // format of the input field (even if hidden, this format will be honored)
        displayArea    :    "lafechaf",       // ID of the span where the date is to be shown
//        daFormat       :    "%A, %B %d, %Y",// format of the displayed date
        daFormat       :    "%B %d, %Y",// format of the displayed date
        align          :    "Tl",           // alignment (defaults to "Bl")
        singleClick    :    true,
		weekNumbers    :    false, 
		range          :     [<?php echo $rango; ?>],

					    });
</script>
	</td>
  </tr>
  <tr>

	<?php 	


//			solicitar_fechasnew('1/1/2008','31/12/2007',$hoy,$hoy,'31/12/2007',$hoy);
			echo '<input type="hidden" name="aportespagos" value="2">';
			echo '<input type="hidden" name="codigo" value="'.$codigo.'">';
			echo '<input type="submit" name="Submit" value="Buscar Históricos de Haberes" />';
			echo '</form>';
	   }
	   if ($accion == "fechad")  {
	   		if ($fechai) 	{ 
			$lfi=convertir_fecha($fechai);
	    	$lff=convertir_fecha($fechaf);
			$ord = $_GET['ord'];
			if (!$ord) $ord='fecha';
			$conta = $_GET['conta'];
			if (!$_GET['conta']) $conta = 1;
			$sql1="CREATE TEMPORARY TABLE la4 select cod_prof as codsoc, hab_prof, hab_ucla, 0 as ret_opsu, descri, fecha, date_format(fecha, '%d/%m/%Y') AS fechax  from fhis200 where cod_prof= '$codigo' and ((fecha >= '$lfi') AND (fecha<= '$lff') AND ('$lfi'> '2007-12-31'))";
			$sql2="CREATE TEMPORARY TABLE la5 select codsoc,   ret_capu as hab_prof, ret_ucla as hab_ucla, ret_opsu, 'Retiro de Haberes' as descri, fechareti as fecha, date_format(fechareti, '%d/%m/%Y') AS fechax  from sgcaf700 where codsoc='$codigo' and ((fechareti >= '$lfi') AND (fechareti<= '$lff') AND ('$lfi'> '2007-12-31'))";
			$f4=mysql_query($sql1) or die(mysql_error());
			$f5=mysql_query($sql2) or die(mysql_error());
			$sql3="CREATE TEMPORARY TABLE la6 select * from la4 union select * from la5 ";
			$f3=mysql_query($sql3) or die(mysql_error());

			$sql="select * from la6 order by fecha "; //." LIMIT ".($conta-1).", 20";
			$result=mysql_query($sql);
			$sql = "SELECT COUNT(codsoc) AS cuantos from la6 ";

			$rs = mysql_query($sql);
			$row= mysql_fetch_array($rs);
			$numasi = $row[cuantos]; 	

			}
		echo "<a target=\"_blank\" href=\"hishabpdf.php?codigo=$codigo&aportespagos=2&lfi=$lfi&lff=$lff&accion=fechad\" onClick=\"info.html\', \'\',\'width=250, height=190\')\">Imprimir Historiales de Haberes</a>";
		$sql="SELECT ape_prof, nombr_prof, ced_prof FROM sgcaf200 WHERE cod_prof= '$codigo'";
		$resultado=mysql_query($sql);
		$fila2 = mysql_fetch_assoc($resultado);
		mostrar($fila2, $result);
		echo "</table>";   
}
/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
		if ($accion=="fechaxxx") {	
      //  		echo '6';
				$ord = $_GET['ord'];
				if (!$ord) $ord='fecha';
				$conta = $_GET['conta'];
				if (!$_GET['conta']) $conta = 1;
		//		echo '7';
 				$sql = "SELECT COUNT(cod_prof) AS cuantos from fhis200 where cod_prof= '$codigo' and (fecha >= '2008-01-01')";
				$rs = mysql_query($sql);
				$row= mysql_fetch_array($rs);
				$numasi = $row[cuantos]; 	

	$sql="Select cod_prof, hab_prof, hab_ucla, descri, date_format(fecha, '%d/%m/%Y') AS fechax from fhis200 where cod_prof= '$codigo' and (fecha >= '2008-01-01') ORDER by $ord "." LIMIT ".($conta-1).", 20";
	echo $sql; 
	$result=mysql_query($sql);
	 echo "<a target=\"_blank\" href=\"hishabpdf.php?codigo=$codigo&aportespagos=2&accion=fechaxxx\" onClick=\"info.html\', \'\',\'width=250, height=190\')\">Imprimir Historiales de Haberes</a>";
	 
	$sql="SELECT ape_prof, nombr_prof, ced_prof FROM sgcaf200 WHERE cod_prof= '$codigo'";
		$resultado=mysql_query($sql);
		$fila2 = mysql_fetch_assoc($resultado);
		echo $sql;
		if (pagina3($numasi, $conta, 20, "Registros", $ord, $codigo)) {$fin = 1;}
		echo "<table align='center' class='basica'>";
		echo '<tr><th width="200">Nombre del Asociado: </th><td class="blanco b" width="270">'.$fila2['ape_prof'].' '.$fila2['nombr_prof'].'</th><th width="100">Cédula</th><td class="blanco b" width="150">'.$fila2['ced_prof'].'</th></tr>';
		echo "<table align='center' class='basica'>";
		echo '<tr><th align="center" width="95">Fecha</th><th align="center" width="100">Haberes Socio</th><th align="center" width="100">Haberes Patrono</th><th align="center" width="220">Descripción</th><th align="center" width="95">Monto de Retiro</th><th align="center" width="95">Fecha de Retiro</th></tr>';
		
		while($row=mysql_fetch_assoc($result)) {
			echo "<tr>";
			echo "<td class='centro'>";
			echo $row['fechax']."</a></td>";
			echo "<td class='dcha'>";
		    echo number_format($row['hab_prof'],2,'.',',')."</td>";
			echo "<td class='dcha'>";
			echo number_format($row['hab_ucla'],2,'.',',')."</td>";
			echo "<td class='dcha'>"; 
			echo $row['descri']."</a></td>";
		}
		$sql="select montoreti,date_format(fechareti, '%d/%m/%Y') AS fechaz FROM sgcaf700 WHERE codsoc='$codigo' ORDER BY fechareti";
		$resultado=mysql_query($sql);
		//echo $sql." <br> \n";
while($row1=mysql_fetch_assoc($resultado)) {
        echo "<tr>";
		echo "<td class='centro'>";
		echo "</td>";
		echo "<td class='dcha'>";
	    echo "</td>";
		echo "<td class='dcha'>";
		echo "</td>";
		echo "<td class='dcha'>"; 
		echo "</td>";
		echo "<td class='dcha'>";
		echo number_format($row1['montoreti'],2,'.',',')."</a></td>";
		echo "<td class='dcha'>";
		echo $row1['fechaz']."</a></td>"; 
		}
		echo "</table>";   
		pagina3($numasi, $conta, 20, "Registros", $ord, $codigo ); 
       }
	 
  }
}

function mostrar($fila2, $result)
{
	echo "<table align='center' class='basica'>";
	echo '<tr><th width="200">Nombre del Asociado: </th><td class="blanco b" width="270">'.$fila2['ape_prof'].' '.$fila2['nombr_prof'].'</th><th width="100">Cédula</th><td class="blanco b" width="150">'.$fila2['ced_prof'].'</th></tr>';
	echo "<table align='center' class='basica'>";
	echo '<tr><th align="center" width="95">Fecha</th><th align="center" width="100">Haberes Socio</th><th align="center" width="100">Haberes Patrono</th><th align="center" width="220">Descripción</th><th align="center" width="95">Monto de Retiro</th></tr>'; // <th align="center" width="95">Fecha de Retiro</th>
		
		while($row=mysql_fetch_assoc($result)) {
			echo "<tr>";
			echo "<td class='centro'>";
			echo $row['fechax']."</a></td>";
			if ($row['descri']=='Retiro de Haberes')
			{
				echo "<td class='dcha'>";
			    echo number_format(0,2,'.',',')."</td>";
				echo "<td class='dcha'>";
				echo number_format(0,2,'.',',')."</td>";
				echo "<td class='dcha'>"; 
				echo $row['descri']."</a></td>";
				echo "<td class='dcha'>";
			    echo number_format(($row['hab_prof']+$row['hab_ucla']+$row['ret_opsu']),2,'.',',')."</td>";
			}
			else 
			{
				echo "<td class='dcha'>";
			    echo number_format($row['hab_prof'],2,'.',',')."</td>";
				echo "<td class='dcha'>";
				echo number_format($row['hab_ucla'],2,'.',',')."</td>";
				echo "<td class='dcha'>"; 
				echo $row['descri']."</a></td>";
			}
		}
		echo "</table>";   

}
/*
function solicitar_fechasnew($valori, $fechai1, $fechai2, $valorf, $fechaf1, $fechaf2)
{
	$fechai="01/01/".date("Y");
	$fechaf=date("d")."/".date('n')."/".date("Y"); 
	echo 'Fecha Inicio: ';
escribe_formulario('fechai', 'form1.fechai', 'd/m/yyyy',$valori,$fechai1,$fechai2, '0', '20'); 	
	
	echo 'Fecha Final: ';
	
	escribe_formulario('fechaf', 'form1.fechaf', 'd/m/yyyy',$valorf,$fechaf1,$fechaf2, '0', '20'); 
}
*/
?>