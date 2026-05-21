<?php
include("head.php");
include("paginar.php");
if (!$link OR !$_SESSION['empresa']) {
	include("noempresa.php");
	exit;
}
if ($accion == 'Anadir') 
	$onload="onload=\"foco('eltipo')\"";
else
	if ($accion == 'Buscar') 
		$onload="onload=\"foco('elretiro')\""; 
	else $onload="onload=\"foco('cedula')\"";
?>
<body <?php if (!$bloqueo) {echo $onload;}?>>

<?php
$readonly=" readonly='readonly'";
include("arriba.php");
$menu61=1;include("menusizda.php");
$cedula = $_GET['cedula'];
$ip = $_SERVER['HTTP_CLIENT_IP'];
if (!$ip) {$ip = $_SERVER['REMOTE_ADDR'];}

if (($accion == "Anada")) {	// muestra datos para prestamo
	$garantia=substr($garantia,0,1);
	$retab_pres=isset($_POST['retab_pres']);
	$albanco=isset($_POST['albanco']);
	$dcto_sem=isset($_POST['dcto_sem']);
	$int_dif=isset($_POST['int_dif']);
	$masdeuno=isset($_POST['masdeuno']);
	$tope_ut=isset($_POST['tope_ut']);
	$genera_pl=isset($_POST['genera_pl']);
	$aprobar=isset($_POST['aprobar']);
	$canc_pres=isset($_POST['canc_pres']);
	$genera_com=isset($_POST['genera_com']);
	$restar_otros=isset($_POST['restar_otros']);
	$incluir_otros=isset($_POST['incluir_otros']);
	$inicial=isset($_POST['inicial']);
	$sql="insert into sgcaf360 (
	cod_pres, descr_pres, n_cuo_pres, i_max_pres, n_fia_pres, retab_pres, cuent_pres, cuent_int, otro_int, 
	garantia, renovacion, tiempo, albanco, aprobar, dcto_sem, int_dif, masdeuno, tope_monto, tope_ut, 
	factor_ut, tipo_interes, en_ajax, en_proyec, genera_pl, canc_pres, genera_com, restar_otros, incluir_otros, 
	inicial, desc_cor, tipo, copias, ip) values (
	'$codigo', '$descr_pres', $n_cuo_pres, $i_max_pres, $n_fia_pres, '$retab_pres', '$cuent_pres', '$cuent_int', '$otro_int',
	'$garantia', '$renovacion', '$tiempo', '$albanco', '$aprobar', '$dcto_sem', '$int_dif', '$masdeuno', '$tope_monto', '$tope_ut', 
	'$factor_ut', '$tipo_interes', '$en_ajax', '$en_proyect', '$genera_pl', '$canc_pres', '$genera_com', '$restar_otros', '$incluir_otros',
	'$inicial', '$desc_cor', '$tipo', '$copias', '$ip')";
	echo '<div id="div1">';
//	echo $sql;
	$result = mysql_query($sql) or die ('Error 360-2 <br>'.$sql.'<br>'.mysql_error());
	echo '</div>';
	$accion='';
} 	// fin de ($accion == "Anada")

if (($accion == "Modifica")) {	// muestra datos para prestamo
	$garantia=substr($garantia,0,1);
	$retab_pres=isset($_POST['retab_pres']);
	$albanco=isset($_POST['albanco']);
	$dcto_sem=isset($_POST['dcto_sem']);
	$int_dif=isset($_POST['int_dif']);
	$masdeuno=isset($_POST['masdeuno']);
	$aprobar=isset($_POST['aprobar']);
	$tope_ut=isset($_POST['tope_ut']);
	$genera_pl=isset($_POST['genera_pl']);
	$canc_pres=isset($_POST['canc_pres']);
	$genera_com=isset($_POST['genera_com']);
	$restar_otros=isset($_POST['restar_otros']);
	$incluir_otros=isset($_POST['incluir_otros']);
	$inicial=isset($_POST['inicial']);
	$sql="update sgcaf360 set descr_pres='$descr_pres', n_cuo_pres='$n_cuo_pres',
	i_max_pres='$i_max_pres', n_fia_pres='$n_fia_pres', retab_pres='$retab_pres',
	cuent_pres='$cuent_pres', cuent_int='$cuent_int', otro_int='$otro_int', 
	garantia='$garantia', renovacion='$renovacion', tiempo='$tiempo', albanco='$albanco', 
	aprobar='$aprobar', dcto_sem='$dcto_sem', int_dif='$int_dif', masdeuno='$masdeuno', 
	tope_monto='$tope_monto', tope_ut='$tope_ut', factor_ut='$factor_ut', 
	tipo_interes='$tipo_interes', en_ajax='$en_ajax', en_proyec='$en_proyec', genera_pl='$genera_pl',
	canc_pres='$canc_pres', genera_com='$genera_com', restar_otros='$restar_otros', 
	incluir_otros='$incluir_otros', inicial='$inicial', desc_cor='$desc_cor', tipo='$tipo', 
	copias='$copias', ip='$ip' where (cod_pres ='$codigo')";
	echo '<div id="div1">';
//	echo $sql;
	$result = mysql_query($sql) or die ('Error 360-2 <br>'.$sql.'<br>'.mysql_error());
	echo '</div>';
	$accion='';
} 	// fin de ($accion == "Anada")


//----------------------------
if (!$accion)  {
	$ord = $_GET['ord'];
	if (!$ord) $ord='cod_pres';
	$conta = $_GET['conta'];
	if (!$_GET['conta']) {
		$conta = 1;
	}
	$sql = "SELECT COUNT(cod_pres) AS cuantos FROM sgcaf360";
	$rs = mysql_query($sql);
	$row= mysql_fetch_array($rs);
	$numasi = $row[cuantos]; 

	$sql="SELECT * FROM sgcaf360 ORDER BY $ord "." LIMIT ".($conta-1).", 15";
	$rs = mysql_query($sql);
	echo "<table class='basica 100 hover' width='750'><tr>";
	echo '<th colspan="2"></th><th width="80">Código</th><th width="200">Descripción<br>';
	echo '[ <a href="tippre.php?accion=Anadir">Nuevo Tipo de Prestamo   </a> ]';
	echo '</th><th width="40">Plazo</th><th width="40">Interes</th><th width="100">Afecta Disp.</th><th width="80">Renovable</th><th width="80">Desc.Esp.</th></tr>';

	if (pagina($numasi, $conta, 15, "Tipos de Prestamos", $ord)) {$fin = 1;}
// 		bucle de listado
	while($row=mysql_fetch_assoc($rs)) {
		echo "<tr>";

//		echo "<td class='centro'><a href='extractoctas3.php?cuenta=".trim($row['cuent_pres']).'-'.substr(trim($row['codsoc_sdp']),1,4)."&datos=no&'><img src='imagenes/page_wizard.gif' width='16' height='16' border='0' /></a></td>";
		echo "<td class='centro'><a href='tippre.php?accion=Ver&elcodigo=".trim($row['cod_pres'])."'><img src='imagenes/page_user_dark.gif' width='16' height='16' border='0' title='Consultar' alt='Consultar'/></a></td>";
		echo "<td class='centro'><a href='tippre.php?accion=Modificar&elcodigo=".trim($row['cod_pres'])."'><img src='imagenes/16-em-pencil.png' width='16' height='16' border='0' title='Editar' alt='Editar'/></a></td>";
		echo "<td class='centro'>";
		echo $row['cod_pres']."</td>";
		echo "<td class='centro'>".$row['descr_pres']."</td>";
		echo "<td align='right'>".number_format($row['n_cuo_pres'],0,'.',',')."</td>";
		echo "<td align='right'>".number_format($row['i_max_pres'],2,'.',',')."</td>";
		echo "<td class='centro'><input type='checkbox' ".($row['retab_pres']?'checked ':'')." disabled='true' ></td>";
		echo "<td class='centro'><input type='checkbox' ".(($row['renovacion']>3)?'checked ':'')." disabled='true' ></td>";
		echo "<td class='centro'><input type='checkbox' ".(!$row['dcto_sem']?'checked ':'')." disabled='true' ></td>";
			echo "</tr>";
		}

		echo "</table>";
		pagina($numasi, $conta, 15, "Tipos de Prestamos", $ord);
}	// fin de (!$accion ) 

if ($accion == 'Ver') {
	echo "<div align='center' id='div1'>";
	$mostrarregresar=1;
	$codigo=$_GET['elcodigo'];
	mostrar_tipo_prestamo($codigo,$accion);
	echo "</div>";
}	// fin de ($accion == 'Ver')
if (($accion == "Anadir")) {	// muestra datos para prestamo
	echo '<div id="div1">';
	echo "<form enctype='multipart/form-data' action='tippre.php?accion=Anada' name='form1' method='post' onsubmit='return valtipre(form1)'>";
	$codigo=nuevo_codigo();
	mostrar_tipo_prestamo($codigo,$accion);
//	echo "<input type = 'submit' value = 'Grabar Datos'></form>\n"; 
	echo '</div>';
} 	// fin de ($accion == "Anadir")

if (($accion == "Modificar")) {	// muestra datos para prestamo
	echo '<div id="div1">';
	echo "<form enctype='multipart/form-data' action='tippre.php?accion=Modifica' name='form1' method='post' onsubmit='return valtipre(form1)'>";
	$codigo=$_GET['elcodigo'];
	mostrar_tipo_prestamo($codigo,$accion);
	echo '</div>';
} 	// fin de ($accion == "Modifcar")


		
if ($mostrarregresar==1) { // ($accion == "Buscar") or ($accion == "Ver") or ($accion="EscogePrestamo")) {
	echo '<form enctype="multipart/form-data" name="formdepie" method="post" action="tippre.php?accion=">';
	echo '<div style="clear:both"></div>';
	echo '<p /><div class="noimpri" style="clear:both;text-align:center">';
	echo '<input type="submit" name="boton" value="regresar" tabindex="3">';
	echo '</div>';
	echo '</form>';
}
else 
	include("pie.php");
?>
</body></html>


<?php


function mostrar_tipo_prestamo($codigo,$accion)
{
	$deci=$_SESSION['deci'];
	$sep_decimal=$_SESSION['sep_decimal'];
	$sep_miles=$_SESSION['sep_miles'];
	$sql_360="select * from sgcaf360 where cod_pres='$codigo'";
//	echo $sql_360;
	$a_360=mysql_query($sql_360);
	$r_360=mysql_fetch_assoc($a_360);
	echo "<input type = 'hidden' value ='".$codigo."' name='codigo'>";
	if ($accion == 'Ver') {
		echo '<fieldset><legend>Tipo '.trim($r_360['descr_pres']). ' / Codigo: '.$r_360['cod_pres'].'</legend>';
		echo '<table class="basica 100 hover" width="700" border="1">';
	}
	else { 
		echo '<fieldset><legend>Nuevo Tipo de Préstamo / Codigo: '.$codigo.'</legend>';
		echo '<table class="basica 100 hover" width="900px" border="1">';
		echo '<tr>';
		echo '<td width="200px">Descripción </td><td colspan="1" width="180px" align="left"><input type="text" name="descr_pres" id="descr_pres" value="'.$r_360['descr_pres'].'" maxlength="25" size="25">*';
	}
    echo '<td width="200px">Nro.Maximo de Cuotas</td><td width="100px" align="left">';
	if ($accion=='Ver')
		echo number_format($r_360['n_cuo_pres'],0,$sep_decimal,$sep_miles).'</td>';
	else {
	echo '<select id="n_cuo_pres" name="n_cuo_pres" size="1">';
	$maximo=52*6;
	$elmaximo=$maximo;
	if ($accion!='Anadir') $elmaximo=$r_360['n_cuo_pres'];
	for ($laposicion=1;$laposicion <= $maximo;$laposicion++) {
		echo '<option value="'.$laposicion.'" '.($laposicion==$elmaximo?" selected ":"").'>'.$laposicion.' </option>'; }
		// 
	echo '</select>'; 
//	echo "<input type = 'text' size='12' maxlength='12' name='n_cuo_pres' tabindex='2' value ='".$r_360['n_cuo_pres']."'>";
	}
    echo '<td width="200px">Tasa de Interes </td><td width="200px" align="right">';
	if ($accion=='Ver')
		echo number_format($r_360['i_max_pres'],$deci,$sep_decimal,$sep_miles).'%</td>';
	else echo "<input type = 'text' size='12' maxlength='12' name='i_max_pres' tabindex='3' value ='".number_format($r_360['i_max_pres'],2,'.','')."'>";
	echo '</tr>';

    echo '<tr><td>Nro.Maximo de Fiadores</td><td align="left">';
	if ($accion=='Ver')
		echo number_format($r_360['n_fia_pres'],0,$sep_decimal,$sep_miles).'</td>';
	else {
		echo '<select id="n_fia_pres" name="n_fia_pres" size="1">';
		$maximo=3;
		if ($accion!='Anadir') $elmaximo=$r_360['n_fia_pres'];
		for ($laposicion=0;$laposicion <= $maximo;$laposicion++) {
			echo '<option value="'.$laposicion.'" '.($laposicion==$elmaximo?" selected ":"").'>'.$laposicion.' </option>'; }
			// 
		echo '</select>'; 
//		echo "<input type = 'text' size='12' maxlength='12' name='n_fia_pres' tabindex='4' value ='".$r_360['n_fia_pres']."'>";
		}
    echo '<td>Afecta Disponibilidad</td><td align="left">';
	if ($accion=='Ver')
		echo "<input type='checkbox' ".($r_360['retab_pres']?'checked ':'')." disabled='true' ></td>";
	else // echo "<input type = 'checkbox' name='retab_pres' tabindex='5' value ='".($accion=='Anadir'?"1   'checked'":$r_360['retab_pres'])."' ".(($r_360['retab_pres'])?'checked ':'')." >";
	echo "<input type = 'checkbox' name='retab_pres' tabindex='5' value =".$r_360['retab_pres']." ".(($r_360['retab_pres'])?'checked ':'')." >";

    echo '<td width="200px">Nro.de Cuotas p/renovar</td><td align="left">';
	if ($accion=='Ver')
		echo number_format($r_360['renovacion'],0,$sep_decimal,$sep_miles).'</td>';
	else {
		if ($accion!='Anadir') $elmaximo=$r_360['renovacion'];
		echo '<select id="renovacion" name="renovacion" size="1">';
		$maximo=52*6;
		for ($laposicion=0;$laposicion <= $maximo;$laposicion++) {
			echo '<option value="'.$laposicion.'" '.($laposicion==$elmaximo?" selected ":"").'>'.$laposicion.' </option>'; }
		echo '</select>'; 
		}
//	else echo "<input type = 'text' size='12' maxlength='12' name='renovacion' tabindex='6' value ='".$r_360['renovacion']."'>";
	echo '</tr><tr>';
    echo '<td>Deposito al Banco</td><td align="left">';
	if ($accion=='Ver')
		echo "<input type='checkbox' ".($r_360['albanco']?'checked ':'')." disabled='true' ></td>";
	else echo "<input type = 'checkbox' name='albanco' tabindex='5' value ='".($accion=='Anadir'?"1   'checked'":$r_360['albanco'])."' ".(($r_360['albanco']==1)?'checked ':'')." >";

    echo '<td>Nro.de Meses en la Caja</td><td align="left">';
	if ($accion=='Ver')
		echo number_format($r_360['tiempo'],0,$sep_decimal,$sep_miles).'</td>';
	else {
		if ($accion!='Anadir') $elmaximo=$r_360['tiempo'];
		echo '<select id="tiempo" name="tiempo" size="1">';
		$maximo=36;
		for ($laposicion=1;$laposicion <= $maximo;$laposicion++) {
			echo '<option value="'.$laposicion.'"'.($laposicion==$elmaximo?" selected ":"").' >'.$laposicion.' </option>'; }
		echo '</select>'; 
		}
//	else echo "<input type = 'text' size='12' maxlength='12' name='tiempo' tabindex='8' value ='".$r_360['tiempo']."'>";
    echo '<td >Aprobacion Directa</td><td align="left">';
	if ($accion=='Ver')
		echo "<input type='checkbox' ".($row['aprobar']?'checked ':'')." disabled='true' ></td>";
	else echo "<input type = 'checkbox' name='aprobar' tabindex='5' value ='".($accion=='Anadir'?"1   'checked'":$r_360['aprobar'])."' ".(($r_360['aprobar'])?'checked ':'')." >";
	echo '</tr><tr>';

    echo '<td>Descuentos Consecutivos</td><td align="left">';
	if ($accion=='Ver')
		echo "<input type='checkbox' ".($r_360['dcto_sem']?'checked ':'')." disabled='true' ></td>";
	else echo "<input type = 'checkbox' name='dcto_sem' tabindex='10' value ='".($accion=='Anadir'?"1   'checked'":$r_360['dcto_sem'])."' ".(($r_360['dcto_sem'])?'checked ':'')." >";
    echo '<td >Int.Desc.Anticipado</td><td align="left">';
	if ($accion=='Ver')
		echo "<input type='checkbox' ".($r_360['int_dif']?'checked ':'')." disabled='true' ></td>";
	else echo "<input type = 'checkbox' name='int_dif' tabindex='11' value ='".($accion=='Anadir'?"1   'checked'":$r_360['int_dif'])."' ".(($r_360['int_dif'])?'checked ':'')." >";

    echo '<td>Varios Vigentes</td><td align="left">';
	if ($accion=='Ver')
		echo "<input type='checkbox' ".($r_360['masdeuno']?'checked ':'')." disabled='true' ></td>";
	else echo "<input type = 'checkbox' name='masdeuno' tabindex='12' value ='".($accion=='Anadir'?"1   'checked'":$r_360['masdeuno'])."' ".(($r_360['masdeuno'])?'checked ':'')." >";
	echo '</tr><tr>';

    echo '<td >Generar Planillas</td><td align="left">';
	if ($accion=='Ver')
		echo "<input type='checkbox' ".($r_360['genera_pl']?'checked ':'')." disabled='true' ></td>";
	else echo "<input type = 'checkbox' name='genera_pl' tabindex='13' value ='".($accion=='Anadir'?"1   'checked'":$r_360['genera_pl'])."' ".(($r_360['genera_pl'])?'checked ':'')." >";

    echo '<td>Nro.de Copias</td><td align="left">';
	if ($accion=='Ver')
		echo number_format($r_360['copias'],0,$sep_decimal,$sep_miles).'</td>';
	else {
		echo '<select id="copias" name="copias" size="1">';
		$maximo=5;
		for ($laposicion=1;$laposicion <= $maximo;$laposicion++) {
			echo '<option value="'.$laposicion.($laposicion==$maximo?" selected ":"").'" >'.$laposicion.' </option>'; }
		echo '</select>'; 
		}
//	else echo "<input type = 'text' size='12' maxlength='12' name='copias' tabindex='14' value ='".$r_360['copias']."'>";
    echo '<td >Tope por U.T.</td><td align="left">';
	if ($accion=='Ver')
		echo "<input type='checkbox' ".($r_360['tope_ut']?'checked ':'')." disabled='true' ></td>";
	else echo "<input type = 'checkbox' name='tope_ut' tabindex='15' value ='".($accion=='Anadir'?"1   'checked'":$r_360['tope_ut'])."' ".(($r_360['tope_ut'])?'checked ':'')." >";
	echo '</tr><tr>';

    echo '<td>Nro.de U.T.</td><td align="left">';
	if ($accion=='Ver')
		echo number_format($r_360['factor_ut'],$deci,$sep_decimal,$sep_miles).'</td>';
	else echo "<input type = 'text' size='12' maxlength='12' name='factor_ut' tabindex='16' value =".number_format($r_360['factor_ut'],$deci,$sepdecimal,'').">";
    echo '<td >Tope en Monto</td><td align="left">';
	if ($accion=='Ver')
		echo number_format($r_360['tope_monto'],$deci,$sep_decimal,$sep_miles).'</td>';
	else echo "<input type = 'text' size='12' maxlength='12' name='tope_monto' tabindex='17' value =".number_format($r_360['tope_monto'],$deci,$sepdecimal,'').">";

    echo '<td>Genera Comprobante</td><td align="left">';
	if ($accion=='Ver')
		echo "<input type='checkbox' ".($r_360['genera_com']?'checked ':'')." disabled='true' ></td>";
	else echo "<input type = 'checkbox' name='genera_com' tabindex='18' value ='".($accion=='Anadir'?"1   'checked'":$r_360['genera_com'])."' ".(($r_360['genera_com'])?'checked ':'')." >";

	echo '</tr><tr>';
    echo '<td >Restar Otros Pres.Renov.</td><td align="left">';
	if ($accion=='Ver')
		echo "<input type='checkbox' ".($r_360['restar_otros']?'checked ':'')." disabled='true' ></td>";
	else echo "<input type = 'checkbox' name='restar_otros' tabindex='19' value ='".($accion=='Anadir'?"1   'checked'":$r_360['restar_otros'])."' ".(($r_360['restar_otros'])?'checked ':'')." >";

    echo '<td>Incluir este en renovaciones</td><td align="left">';
	if ($accion=='Ver')
		echo "<input type='checkbox' ".($r_360['incluir_otros']?'checked ':'')." disabled='true' ></td>";
	else echo "<input type = 'checkbox' name='incluir_otros' tabindex='20' value ='".($accion=='Anadir'?"1   'checked'":$r_360['incluir_otros'])."' ".(($r_360['incluir_otros'])?'checked ':'')." >";
    echo '<td >Permitir Inicial</td><td align="left">';
	if ($accion=='Ver')
		echo "<input type='checkbox' ".($r_360['inicial']?'checked ':'')." disabled='true' ></td>";
	else echo "<input type = 'checkbox' name='inicial' tabindex='21' value ='".($accion=='Anadir'?"1   'checked'":$r_360['inicial'])."' ".(($r_360['inicial'])?'checked ':'')." >";
	echo '</tr><tr>';

    echo '<td >Descripcion Corta</td><td align="left">';
	if ($accion=='Ver')
		echo number_format($r_360['desc_cor'],0,$sep_decimal,$sep_miles).'</td>';
	else echo "<input type = 'text' size='10' maxlength='10' name='desc_cor' tabindex='20' value ='".$r_360['desc_cor']."'>*";
    echo '<td >Tipo de Registro</td><td align="left">';
	if ($accion=='Ver')
		echo $r_360['tipo'].'</td>';
	else {
			$eltipo=$r_360['tipo'];
			echo '<select name="tipo" size="1">';
			$sql="select nombre from sgcaf000 where tipo='TipoReg' order by nombre";
			$resultado=mysql_query($sql);
			while ($fila2 = mysql_fetch_assoc($resultado)) {
				echo '<option value="'.$fila2['nombre'].'" '.(($eltipo==$fila2['nombre'])?'selected':'').'>'.$fila2['nombre'].'</option>';}
	 	echo '</select> '; 
	}

    echo '<td >Tipo de Interes</td><td colspan="1" align="left">';
	if ($accion=='Ver')
		echo $r_360['tipo_interes'].'</td>';
	else {
		$eltipo=$r_360['tipo_interes'];
		echo '<select name="tipo_interes" size="1">';
		$sql="select nombre from sgcaf000 where tipo='TipoInt' order by nombre";
		$resultado=mysql_query($sql);
		while ($fila2 = mysql_fetch_assoc($resultado)) {
			echo '<option value="'.$fila2['nombre'].'" '.(($eltipo==$fila2['nombre'])?'selected':'').'>'.$fila2['nombre'].'</option>';}
 	echo '</select> '; 
	}
	echo '</tr><tr>';

    echo '<td >Garantia</td><td colspan="1" align="left">';
	if ($accion=='Ver')
//		echo ($r_360['garantia']==1?'Disponibilidad':($r_360['garantia']==2?'Fiador(es)':'Hipoteca')).'</td>';
	{
		$sql="select nombre from sgcaf000 where tipo='garantia' and substr(nombre,1,1)='".$r_360['garantia']."'";
		$resultado=mysql_query($sql);
		$fila2 = mysql_fetch_assoc($resultado);
		echo $fila2['nombre'];
	}
	else {
		$eltipo=$r_360['garantia'];
		echo '<select name="garantia" size="1">';
		$sql="select nombre from sgcaf000 where tipo='garantia' order by nombre";
		$resultado=mysql_query($sql);
		while ($fila2 = mysql_fetch_assoc($resultado)) {
			echo '<option value="'.$fila2['nombre'].'" '.(($eltipo==$fila2['nombre'])?'selected':'').'>'.$fila2['nombre'].'</option>';}
 	echo '</select></td> ';
	}
	
	echo '<td>Mayor de Préstamos</td><td>';
	if ($accion=='Ver') 
		echo "<a target=\"_blank\" href=\"extractoctas3.php?cuenta=".$r_360["cuent_pres"]."&datos='no'\">".$r_360["cuent_pres"]."</a>";
	else {
		echo "<input type='text' size='20' tabindex='25' name='cuent_pres' id='inputString' onKeyUp='lookup(this.value);' onBlur='fill();' value = '".$r_360["cuent_pres"]."'; autocomplete='off'/>";
//	echo '<div class="suggestionsBox" id="suggestions" style="display: none;">';
		echo '<div class="suggestionsBox" id="suggestions" style="display: none; position: absolute; left: 80px; top: 300px; width: 300; height: 272px; z-index: 1; visibility: visible; overflow: visible"> ';
		echo '<img src="upArrow.png" style="position: relative; top: -0px; left: 70px; "  alt="upArrow" />';
		echo '<div class="suggestionList" id="autoSuggestionsList">';
		echo '</div>';
		echo '</div>';
		echo '</div>';
	}
	echo '</td><td>Intereses Diferidos</td><td>';
	if ($accion=='Ver') 
		echo "<a target=\"_blank\" href=\"extractoctas3.php?cuenta=".$r_360["cuent_int"]."&datos='no'\">".$r_360["cuent_int"]."</a>";
	else {
		echo "<input type='text' size='20' tabindex='26' name='cuent_int' id='inputString2' onKeyUp='lookup2(this.value);' onBlur='fill2();' value ='".$r_360["cuent_int"]."'; autocomplete='off'/>";
//	echo '<div class="suggestionsBox2" id="suggestions2" style="display: none;">';
		echo '<div class="suggestionsBox2" id="suggestions2" style="display: none; position: absolute; left: 180px; top: 300px; width: 300; height: 272px; z-index: 1; visibility: visible; overflow: visible"> ';
		echo '<img src="upArrow.png" style="position: relative; top: -12px; left: 70px; "  alt="upArrow" />';
		echo '<div class="suggestionList2" id="autoSuggestionsList2">';
		echo '</div></div></div>';
	}
	echo '</td><tr>';

	echo '<td>Intereses Ganados</td><td>';
	if ($accion=='Ver') 
		echo "<a target=\"_blank\" href=\"extractoctas3.php?cuenta=".$r_360["otro_int"]."&datos='no'\">".$r_360["otro_int"]."</a>";
	else {
		echo "<input type='text' size='20' tabindex='27' name='otro_int' id='inputString6' onKeyUp='lookup6(this.value);' onBlur='fill6();' value = '".$r_360["otro_int"]."'; autocomplete='off'/>";
//	echo '<div class="suggestionsBox6" id="suggestions6" style="display: none;">';
		echo '<div class="suggestionsBox6" id="suggestions6" style="display: none; position: absolute; left: 80px; top: 300px; width: 800; height: 272px; z-index: 1; visibility: visible; overflow: visible"> ';
		echo '<img src="upArrow.png" style="position: relative; top: -0px; left: 70px; "  alt="upArrow" />';
		echo '<div class="suggestionList6" id="autoSuggestionsList6">';
		echo '</div>';
		echo '</div>';
		echo '</div>';
	}
	echo '</tr>';
	echo '</table>';
	if ($accion != 'Ver') echo "<input type = 'submit' value = 'Grabar Datos'></form>\n"; 
//	echo '<br><br><br><br><br>';
	echo '</fieldset>';
}	

function nuevo_codigo()
{
	$sql="SELECT cod_pres FROM sgcaf360 ORDER BY cod_pres DESC limit 1";
	$resulta2=mysql_query($sql);
	$fila2 = mysql_fetch_assoc($resulta2);
	$ultimo=$fila2['cod_pres'];
	$digitos=3;
	$ultimo++;
	$ultimo=ceroizq($ultimo,$digitos);
	return $ultimo;
}

/*
ALTER TABLE `sgcaf360` ADD `ip` VARCHAR( 40 ) NOT NULL ;
ALTER TABLE `sgcaf360` ADD `copias` INT( 2 ) NOT NULL ;
  */
?>
