<?php
include("head.php");
if (!$link OR !$_SESSION['empresa']) {
	include("noempresa.php");
	exit;
}
	$update = "update sgcaf810 set cue_saldo = 0";
	$actual=mysql_query($update);

	$sqlano="SELECT cue_codigo, cue_saldo from histf810 where substr(cue_codig2,1,2)='19'";
	$sqlfano=mysql_query($sqlano);
//	$sqlrano=mysql_fetch_assoc($sqlfano);
	// $sqlrano=mysql_fetch_all($sqlfano);
set_time_limit(3000);
	while ($sqlrano = mysql_fetch_assoc($sqlfano))
	{
		$codigo = $sqlrano['cue_codigo'];
		$tamano = strlen(trim($codigo));
		$sql = "select sum(com_monto1) as debe, sum(com_monto2) as haber from histf820 where com_fecha between '2019-01-01' and '2019-12-31' and substr(com_cuenta,1,'$tamano') = '$codigo' group by com_cuenta";
		// echo $sql;
		$mov=mysql_query($sql);
		if (mysql_num_rows($mov) > 0)
		{
			$elmov = mysql_fetch_assoc($mov);
			$sql = "select sum(cue_saldo) as cue_saldo from sgcaf810 where cue_codigo = '$codigo'";
			$actual=mysql_query($sql);
			$actual = mysql_fetch_assoc($actual);
			$anterior = ($sqlrano['cue_saldo']) + ($elmov['debe'] - $elmov['haber']);
			$actual = $actual['cue_saldo'];
			$update = "update sgcaf810 set cue_saldo = '$anterior' where cue_codigo='$codigo'";
			$actual=mysql_query($update);
			echo $codigo.'<br>';
			flush();
			// ob_flush();
			/*
			echo 'codigo '.$codigo. ' saldo '.$anterior.' actual '.$actual;
			if ($actual <> $anterior)
				echo '<h2>Diferente</h2>';
			echo '<br>';
			//
			*/
		}
		// else 
		//	echo 'no hay movimiento<br>';
	}

?>
