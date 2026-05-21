<?php
// ALTER TABLE `t_his200` ADD `comision` DECIMAL( 18, 3 ) NOT NULL DEFAULT '0';
include("head.php");
include("paginar.php");

extract($_GET);
extract($_POST);
extract($_SESSION);


if (!$link OR !$_SESSION['empresa']) {
	include("noempresa.php");
	exit;
}
?>
<script language="javascript">
//Creo una función que imprimira en la hoja el valor del porcentanje asi como el relleno de la barra de progreso
function callprogress(vValor){
 document.getElementById("getprogress").innerHTML = vValor;
 document.getElementById("getProgressBarFill").innerHTML = '<div class="ProgressBarFill" style="width: '+vValor+'%;"></div>';
}
</script>
<style type="text/css">
/* Ahora creo el estilo que hara que aparesca el porcentanje y relleno del mismoo*/
      .ProgressBar     { width: 16em; border: 1px solid black; background: #eef; height: 1.25em; display: block; }
      .ProgressBarText { position: absolute; font-size: 1em; width: 16em; text-align: center; font-weight: normal; }
      .ProgressBarFill { height: 100%; background: #aae; display: block; overflow: visible; }
    </style>
</script>

<body <?php if (!$bloqueo) {echo $onload;}?>>

<?php

$readonly=" readonly='readonly'";
include("arriba.php");
$menu61=1;include("menusizda.php");
$ip = $_SERVER['HTTP_CLIENT_IP'];
if (!$ip) {$ip = $_SERVER['REMOTE_ADDR'];}
if (!$accion) {
	$sql = "select fecha from sgcafnah order by fecha desc limit 1";
	$result=mysql_query($sql) or die (mysql_error());
	$row = mysql_fetch_array($result);
	pantalla_ingreso($row);
?>
<?php 
}	// if (!$accion) 


if ($accion=='ProcesarArchivos') {
	extract($_POST);
	// phpinfo();
	// $fecha_nomina='2023-02-15';
	$fecha_nomina = $_POST['fechaaporte'];

	echo '<table>';
		echo '<tr>';
			echo '<td>';
				echo "<h2><a target=\"_blank\" href=\"cierre_ahorros_pdf.php?fechaaporte=$fecha_nomina\" onClick=\"info.html\', \'\',\'width=250, height=190\')\">Generar PDF</a><br></h2>"; 
			echo '</td>';
		echo '</tr>';
	echo '</table>';

	// listado_cambio_status($fecha_nomina);
}


function pantalla_ingreso($row)
{
?>
	<div id='div1'>
		<form action='cierre_ahorros.php?accion=ProcesarArchivos' name='form1' method='post' enctype='multipart/form-data' onsubmit='return realizar_abono(form1)'>
			<fieldset>
				<legend>Cierre Mensual Ahorros </legend>
				<table>
					<tr>
						<td>
							Fecha de Último Ultimo Cierre Ahorros
						</td>
						<td>
							<?php echo $row['fecha'] ?>
						</td>
					</tr>

					<tr>
						<td><input type="hidden" name="aportespagos" value = "on"/>
						Fecha de Cierre Ahorros 
						<input type="hidden" name="fechaaporte" id="fechaaporte" value="<?php echo $row['fecha'] ?>">
					</td>
					<td>
						<span style="background-color: #ff8; cursor: default;"
						onmouseover="this.style.backgroundColor='#ff0';"
						onmouseout="this.style.backgroundColor='#ff8';"
						id="show_d3" 
   						><?php  echo $row['fecha'] ?></span>
						<script type="text/javascript">
						    Calendar.setup({
						        inputField     :    "fechaaporte",     // id of the input field
						        // ifFormat       :    "%d/%m/%Y",     // format of the input field (even if hidden, this format will be honored)
						        ifFormat       :    "%Y-%m-%d",     // format of the input field (even if hidden, this format will be honored)
						        displayArea    :    "show_d3",       // ID of the span where the date is to be shown
						        daFormat       :    "%A, %B %d, %Y",// format of the displayed date
						        align          :    "Tl",           // alignment (defaults to "Bl")
						        singleClick    :    true,
								weekNumbers    :    false,  
								dateStatusFunc :    function (date) 
								{ 
									var today = new Date();
									// return (
									  // (date.getTime() > today.getTime()-(1*24*60*60*1000)) 
													  // ) ? true : false;  
								}
							});
						</script>
						</td>
					</tr>
					<tr>
						<td>
							<input type="submit" name="Submit" value="Procesar">
						</td>
					</tr>
				</table>
			</fieldset>
		</form>
	</div>
<?php
}

?>

</body></html>

