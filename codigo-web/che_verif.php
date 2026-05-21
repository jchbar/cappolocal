<?php
include("head.php");
include("paginar.php");
//include("popcalendario/escribe_formulario.php");
if (!$link OR !$_SESSION['empresa']) {
	include("noempresa.php");
	exit;
}
if ($accionIn == 'Anadir') 
	$onload="onload=\"foco('cta')\""; 
else
	$onload="onload=\"foco('nactivo')\"";
?>

<body <?php if (!$bloqueo) {echo $onload;}?>>

<?php
 
$readonly=" readonly='readonly'";
include("arriba.php");
$menu61=1;include("menusizda.php");
$cta = $_GET['cta'];
$nactivo=$_GET['nactivo'];
$ip = $_SERVER['HTTP_CLIENT_IP'];
if (!$ip) {$ip = $_SERVER['REMOTE_ADDR'];}
?>
<?php 

if ($accionIn=="Verificar") 
{
   // echo '<div id="div1">';
/*
   echo $codigo; 
   echo $nombre; 
*/   
	echo "<form action='che_verif.php?accionIn=Verificar1' name='form1' method='post' onsubmit='return explicacion_cheque(form1)'>";  
	$sql= "SELECT nombre_ban, nro_cta_ba FROM sgcaf843 where cod_banco='$codigo' and emitircheque='1'"; 
	$resultado=mysql_query($sql);
	$bb= mysql_fetch_assoc($resultado);
	$nro=$bb['nro_cta_ba']; 
	$nombre=$bb['nombre_ban']; 
	$sql= "SELECT *, date_format(mche_fecha, '%d/%m/%Y') AS fecha FROM sgcaf840, sgcaf843 where cod_banco='$codigo' and  mche_statu='L' and mche_banco='$codigo' and verificado='0' ORDER BY mche_orden"; 
	$result=mysql_query($sql);
//	echo $sql; 
    pantalla_verificar($result,$accionIn,$codigo,$nombre,$nro);
	
}
?>

<?php 

if (!$accionIn) {
//	echo "<div id='div1'>";
	echo "<form action='che_verif.php?accionIn=Verificar' name='form1' method='post' onsubmit='return explicacion_cheque(form1)'>";
   
    $sql="SELECT * FROM sgcaf840, sgcaf843 where mche_statu='L' and mche_banco= cod_banco and verificado='0' and emitircheque='1' group BY mche_banco "; 
	//echo $sql; 
    echo 'Banco';
	echo '<select name="codigo" size="1">';
	 		$resultado=mysql_query($sql);
			while ($fila2 = mysql_fetch_assoc($resultado)) 
			{
			echo '<option value="'.$fila2['cod_banco'].'" '.(($banco==$fila2['cod_banco'])?'selected':'').'>'.$fila2['nombre_ban'].''.$fila2['nro_cta_ba'].'</option>';
		    }
			echo '</select> ';
			echo "<input type = 'submit' value = 'Buscar'>";
	echo '</form>';
}

?>


<?php 
function pantalla_verificar($result,$accionIn,$codigo,$nombre,$nro){
$deci=$_SESSION['deci'];
$sep_decimal=$_SESSION['sep_decimal'];
$sep_miles=$_SESSION['sep_miles'];
//echo $sql; 
if ($accionIn == 'Verificar') { $lectura = 'readonly = "readonly"'; $activada="disabled" ; } else {$lectura=''; $activada='';}
?>
<label><fieldset><legend>VERIFICACIÓN DE CHEQUES del Banco <?php echo $nombre?></legend>
  	<table width="350" border="3">
	<?php 
	echo "<table class='basica 100 hover' width='100%'><tr>";
	echo '<th>Nro.de Cheque';
	echo '<th>Descripción  '; 
	echo '</th><th><a href=?ord=mche_fecha>Fecha</th></th><th> </th>';
    echo "<tr>";
	$e=a; 
	$on='1'; 
	while($row1=mysql_fetch_array($result)) 
	{
	echo '<td  class="centro negro b" width="50">'.$row1['mche_orden'].' </td> 
	</td>' .'<td  class="izq negro b" width="190">'.$row1['mche_nombr'].' </td><td  class="negro b dcha" width="30"> '.$row1['fecha'].'</td>';
	echo "<td><a href='cheact.php?accionIn=Consultar&codigo=".$row1['nro_cta_ba']."&numero=".$row1['mche_orden']."&nombre=".$row1['nombre_ban']."&status=".$row1['mche_statu']."&e=".$e."&verificacion=".$on."&cod=".$codigo."'><img src='imagenes/animadas/checklist_sm_wht.gif' width='16' height='16' border='0' title='Verificar Cheques' alt='Verificar Cheques' /></a></td></tr>";
	}
	?>
	<input type="hidden" name="nro" value="<?php echo $nro;?>">
	<input type="hidden" name="codigo" value="<?php echo $codigo;?>">
	<input type="hidden" name="nombre" value="<?php echo $nombre;?>">
</table>
	
		&nbsp;</td></tr> 

<?php 
}
?>