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

if (!$link OR !$_SESSION['empresa']) {
	include("noempresa.php");
	exit;
}
?>

<body <?php if (!$cuenta AND !$bloqueo) {echo "onload=\"foco('cuenta')\"";}?>>

<?php
include("arriba.php");
$menu12=2;include("menusizda.php");
if (!$accion) {
	echo "<form enctype='multipart/form-data' action='salini.php?accion=empezar' method='post' name='form1'> \n";
	echo "<br>";
/*
	echo '<fieldset><legend> Primer Trimestre </legend>';
	echo '<input name="enero" type="checkbox" tabindex="1" value="1" >'.$validado.'Enero';
	echo '<input name="febrero" type="checkbox" tabindex="2" value="1"'.$validado.'>Febrero';
	echo '<input name="marzo" type="checkbox" tabindex="3" value="1"'.$validado.'>Marzo';
	echo '</fieldset>';

	echo '<fieldset><legend> Segundo Trimestre </legend>';
	echo '<input name="abril" type="checkbox" tabindex="4" value="1"'.$validado.'>Abril';
	echo '<input name="mayo" type="checkbox" tabindex="5" value="1"'.$validado.'>Mayo';
	echo '<input name="junio" type="checkbox" tabindex="6" value="1"'.$validado.'>Junio';
	echo '</fieldset>';

	echo '<fieldset><legend> Tercer Trimestre </legend>';
	echo '<input name="julio" type="checkbox" tabindex="7" value="1"'.$validado.'>Julio';
	echo '<input name="agosto" type="checkbox" tabindex="8" value="1"'.$validado.'>Agosto';
	echo '<input name="septiembre" type="checkbox" tabindex="9" value="1"'.$validado.'>Septiembre';
	echo '</fieldset>';

	echo '<fieldset><legend> Cuarto Trimestre </legend>';
	echo '<input name="octubre" type="checkbox" tabindex="10" value="1"'.$validado.'>Octubre';
	echo '<input name="noviembre" type="checkbox" tabindex="11" value="1"'.$validado.'>Noviembre';
	echo '<input name="diciembre" type="checkbox" tabindex="12" value="1"'.$validado.'>Diciembre <br>';
*/
	echo '</fieldset>';
	
	echo "<input type='submit' value='Recalcular'></form> \n";
	echo '</form>';
//	include("pie.php");
//	echo "</div></body></html>";
//	exit;

}

if ($accion == 'empezar'){
	$losniveles = mysql_query("SELECT * FROM sgcafniv order by con_nivel"); 
	if (mysql_num_rows($losniveles) == 0) {
		die("<p /><br /><p />No se han definido los niveles<span class='b'> error Niv-1</span> en la tabla");
		exit;
	}
/*
	$elnivel=0;
//	$row=mysql_fetch_assoc($losniveles);
	while($row=mysql_fetch_assoc($losniveles)) {
		$elnivel++;
		$tamano=$row['con_nivel'];
		if ($elnivel < mysql_num_rows($losniveles)) {
			$sql="select cue_codigo, sum(cue_saldo) as eltotal from sgcaf810 where LENGTH(trim(cue_codigo)) = ".$tamano." group by cue_codigo order by cue_codigo";
//			$sql="select cue_codigo, sum(cue_saldo) as eltotal from sgcaf810 where (trim(cue_codigo)) = substr(cue_codigo,1,".$tamano.") and cue_nivel='7' group by cue_codigo order by cue_codigo";
			echo $sql.'<br>';
			$result=mysql_query($sql);
			while($r_810=mysql_fetch_assoc($result)) {
				$codigo=$r_810['cue_codigo'];
				$sql="update sgcaf810 set cue_nivel = '".$elnivel."', cue_saldo = ".$r_810['eltotal']." where cue_codigo ='$codigo'";
				echo $sql.'<br>';
				if (!mysql_query($sql))
					echo mysql_error();
			}
		}
	}
*/
	$sql="SELECT cue_codigo, sum(cue_saldo) as monto from sgcaf810 WHERE cue_nivel = '7' GROUP BY cue_codigo ORDER BY cue_codigo ";
//	$f810=mysql_query($sql);
	$sql="select cue_codigo from sgcaf810 where cue_nivel < '7' order by cue_codigo";
	echo $sql;
	$res2=mysql_query($sql);
	set_time_limit(mysql_num_rows($res2));

// 	$fnivel=mysql_query($sql);
	while($row=mysql_fetch_assoc($res2)) {
		$elcodigo=trim($row['cue_codigo']);
		echo $elcodigo.'<br>';
		$tamano=strlen($elcodigo);
//		$sql2="select sum(cue_saldo) as eltotal from sgcaf810 where LEFT(cue_codigo,$tamano)='$elcodigo'";
//		$sql="SELECT cue_codigo, sum(cue_saldo) as monto from sgcaf810 WHERE substr(cue_codigo,1,$tamano) = '$elcodigo' and cue_nivel = '7' GROUP BY cue_codigo ";
		$sql="SELECT sum(cue_saldo) as monto from sgcaf810 WHERE substr(cue_codigo,1,$tamano) = '$elcodigo' and cue_nivel = '7' ";
		echo $sql;
		$r_810=mysql_query($sql);
		$r_810=mysql_fetch_assoc($r_810);
		$sqlx="update sgcaf810 set cue_saldo = ".$r_810['monto']." where cue_codigo ='$elcodigo'";
		$res=mysql_query($sqlx);
	}
}

include("pie.php");?>
</body></html>
