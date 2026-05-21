<?php
include("head.php");
include("pagina.php");
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
if (!$codigo) {
	$correcto=0;
	echo "<form action='cueaso.php?accion=fecha' name='form1' method='post'>";
	echo "<form enctype='multipart/form-data' method='post' name='form1'>";
	echo "Código: <input type='text' name='codigo' size='10' maxlength='5' id='inputString5' onKeyUp='lookup7(this.value);' onBlur='fill7();' autocomplete='off' > \n";
	
//	echo "<input type='text' size='20' tabindex='1' name='_unacedula' id='inputString5' onKeyUp='lookup5(this.value);' onBlur='fill5();' value ='$_unacedula' autocomplete='off'/>";
	echo '<div class="suggestionsBox5" id="suggestions5" style="display: none;">';
	echo '<img src="upArrow.png" style="position: relative; top: -12px; left: 70px; "  alt="upArrow" />';
	echo '<div class="suggestionList5" id="autoSuggestionsList5">';
	echo '</div>';
	echo '</div>';

	echo '<input type="submit" name="Submit" value="Buscar" />';
	echo '</form>';
	}
if ($codigo) {
	$codigo  = ceroizq($codigo,5);
	$sql="Select cod_prof, ape_prof, nombr_prof, ced_prof from sgcaf200 where cod_prof= '$codigo'";
	$result = mysql_query($sql);
	if (mysql_num_rows($result) == 0) {
		echo "<p />Código <span class='b'>$codigo</span> no esta registrado</div></body></html>";
				//		exit;
	}
    else 
	{
		$codigo  = substr($codigo,(4*-1));
		$sql="select cue_codigo, cue_saldo from sgcaf810 where right(cue_codigo,4)='$codigo' order by cue_codigo";
		$a810=mysql_query($sql);
//		echo $sql;
		echo "<table align='center' class='basica'>";
		echo '<tr><th align="center" width="95">Código</th><th align="left" width="250">Cuenta</th><th align="center" width="100">Saldo Inicio</th><th align="center" width="100">Saldo Actual</th></tr>';
		
		while($r810=mysql_fetch_assoc($a810)) {
			$lacuenta=$r810['cue_codigo'];
			$tamano=strlen(trim($r810['cue_codigo']))-5;
			$limitada=substr($lacuenta,0,$tamano);
			$sql="select cue_nombre from sgcaf810 where cue_codigo='$limitada'";
			$a810_2=mysql_query($sql);
			$r810_2=mysql_fetch_assoc($a810_2);
			$saldo_actual=buscar_saldo_f810($lacuenta);
//			echo $saldo_actual.'<br>';
			if (($r810['cue_saldo'] != 0) or ($saldo_actual != 0)) {
				echo "<tr>";
				echo "<td class='centro'><a href='extractoctas3.php?cuenta=".$lacuenta."'>";
				echo $limitada."</a></td>";
				echo "<td class='izda'>";
				echo $r810_2['cue_nombre'].'</td>';
				echo "<td class='dcha'>";
			    echo number_format($r810['cue_saldo'],2,'.',',')."</td>";
				echo "<td class='dcha'>";
				echo number_format($saldo_actual,2,'.',',')."</td>";
				echo "</td></tr>";
			}
		}
		echo "</table>";   
	}
}

function buscar_saldo_f810($cuenta)
{
	$sql_f810="select cue_saldo from sgcaf810 where cue_codigo='$cuenta'";
//	echo $sql_f810;
	$lacuentas=mysql_query($sql_f810); //  or die ("<p />El usuario $usuario no pudo conseguir el saldo contable<br>".mysql_error()."<br>".$sql);
	$lacuenta=mysql_fetch_assoc($lacuentas);
	$saldoinicial=$lacuenta['cue_saldo'];
//	echo $saldoinicial;
	if (mysql_num_rows($lacuentas) > 0) {
		$sql_f820="select com_monto1, com_monto2 from sgcaf820 where com_cuenta='$cuenta' order by com_fecha";
//	echo $sql_f820;
		$lacuentas=mysql_query($sql_f820); //  or die ("<p />El usuario $usuario no pudo conseguir los movimientos contables<br>".mysql_error()."<br>".$sql);
		while($lascuenta=mysql_fetch_assoc($lacuentas)) {
			$saldoinicial+=$lascuenta['com_monto1'];
//		echo $saldoinicial.'<br>';
			$saldoinicial-=$lascuenta['com_monto2'];
//		echo $saldoinicial.'<br>';
		}
//	echo $saldoinicial.'<br>';
	}
return round($saldoinicial,2);
}

?>