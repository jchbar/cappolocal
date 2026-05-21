<?php
include("head.php");
include("paginar.php");

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
$cedula = $_GET['cedula'];
$ip = $_SERVER['HTTP_CLIENT_IP'];
if (!$ip) {$ip = $_SERVER['REMOTE_ADDR'];}
?>
<?php
     echo "<a target=\"_blank\" href=\"habsocpdf.php?ord=cod_prof\" onClick=\"info.html\', \'\',\'width=250, height=190\')\">Imprimir   </a>";
	
	 echo "<h3>[  <a target=\"_blank\" href=habsocpdf?ord=cod_prof>Ordenar por el Código</a> ]";
	 echo "   [   <a target=\"_blank\" href=habsocpdf?ord=ced_prof>Ordenar por la Cédula</a>  ]";
     echo "   [   <a target=\"_blank\" href=habsocpdf?ord=ape_prof,nombr_prof>Ordenar por el Nombre</a>  ]</h3>";
     echo "<p/ >";
	echo "<table class='basica 100 hover' width=''></th>";
	echo "<th colspan='2'>   </th></th><th width='65'><a href=?ord=cod_prof>Código</a></th><th width='65'><a href=?ord=ced_prof>Cédula</a></th><th width='200'><a href=?ord=ape_prof>Apellidos y Nombres</a></th><th width='70'>Haberes Socio</th><th width='70'>Haberes <br />Patrono</th><th width='70'>Haberes <br /> Voluntario</th><th width='70'>Haberes <br />Capitalizable</th><th width='70'>Total</th>";	
	
	$ord = $_GET['ord'];
	if (!$ord) $ord='cod_prof';
	else if ($ord=='ape_prof') $ord='ape_prof,nombr_prof';
//	echo 'orden '.$orden;
//	echo 'ord '.$ord;
//	$ord= $orden;
	$conta = $_GET['conta'];
	if (!$_GET['conta']) {
		$conta = 1;
	}
	
$sql = "SELECT COUNT(cod_prof) AS cuantos FROM sgcaf200 WHERE hab_f_prof!=0 and hab_f_empr!=0";
	$rs = mysql_query($sql);
	$row= mysql_fetch_array($rs);
	$numasi = $row[cuantos]; 
	//echo $sql;
	
$sql = "SELECT cod_prof, ced_prof, ape_prof, nombr_prof, hab_f_prof, hab_f_empr, hab_f_extr, hab_f_capi FROM sgcaf200 WHERE hab_f_prof!=0 and hab_f_empr!=0 ORDER BY $ord "." LIMIT ".($conta-1).", 20";
	$rs = mysql_query($sql);
//echo $sql;

	if (pagina($numasi, $conta, 20, "Asociados", $ord)) {$fin = 1;}

// bucle de listado
	$decimales=$_SESSION['deci'];
	while($row=mysql_fetch_array($rs)) {
		echo "<tr>";
		echo "<td><a href='edocta.php?accion=Editar&cedula=".$row['ced_prof']."'><img src='imagenes/socioweb2.PNG' width='16' height='16' border='0' title='Estado de Cuenta' alt='Estado de Cuenta'  /></a></td>";
		echo "<td><a href='hishab.php?accion=Editar&codigo=".$row['cod_prof']."&aportespagos=2&accion=fechaxxx'><img src='imagenes/6zx8r.gif' width='16' height='16' border='0' title='Históricos de Haberes' alt='Históricos de Haberes' /></a></td>";
		echo "<td class='centro'>";
		echo $row['cod_prof']."</a></td>";
		echo "<td class='centro'>";
		echo $row['ced_prof']."</a></td>";
		echo "<td class='centro'>";
		echo trim($row['ape_prof']). ' '.trim($row['nombr_prof'])."</a></td>";
		echo "<td class='dcha'>";
		echo number_format($row['hab_f_prof'],2,'.',',')."</td>";
		echo "<td class='dcha'>";
		echo number_format($row['hab_f_empr'],2,'.',',')."</td>";
		echo "<td class='dcha'>";
		echo number_format($row['hab_f_extr'],2,'.',',')."</td>";
		echo "<td class='dcha'>";
		echo number_format($row['hab_f_capi'],2,'.',',')."</td>";
		echo "<td class='dcha'>";
		$t1=$row["hab_f_prof"]+$row["hab_f_empr"]+$row["hab_f_extr"]+$row["hab_f_capi"];
		echo number_format($t1,2,'.',',')."</td>";
				}
echo "</table>";
	pagina($numasi, $conta, 20, "Asociados", $ord);
?>
