<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
include('dbconfig.php');
echo '<br>Iniciando ';
$db_con->beginTransaction();
$sql = "truncate table reconversion_2021";
$con1 = $db_con->prepare($sql);
$con1->execute(array(
));
echo '<br>valorantes valorluego --> luegodelpunto';

$sql = "select * from ".socios;
$con1 = $db_con->prepare($sql);
$con1->execute(array(
));
while ($puntos = $con1->fetch(PDO::FETCH_ASSOC))
{
	reconvertir($db_con, socios, 'hab_f_empr', "cod_prof = '".$puntos['cod_prof']."'");
	reconvertir($db_con, socios, 'hab_f_prof', "cod_prof = '".$puntos['cod_prof']."'");
	reconvertir($db_con, socios, 'hab_f_extr', "cod_prof = '".$puntos['cod_prof']."'");
	reconvertir($db_con, socios, 'hab_opsu', "cod_prof = '".$puntos['cod_prof']."'");
}
$sql = "select * from ".prestamos. " where stapre_sdp='A' and renovado = 0";
$con1 = $db_con->prepare($sql);
$con1->execute(array(
));
while ($puntos = $con1->fetch(PDO::FETCH_ASSOC))
{
	reconvertir($db_con, prestamos, 'monpre_sdp', "registro = '".$puntos['registro']."'");
	reconvertir($db_con, prestamos, 'monpag_sdp', "registro = '".$puntos['registro']."'");
	reconvertir($db_con, prestamos, 'cuota', "registro = '".$puntos['registro']."'");
	reconvertir($db_con, prestamos, 'cuota_ucla', "registro = '".$puntos['registro']."'");
}
$sql = "select * from ".fianzas. " where tipmov_fia='F'";
$con1 = $db_con->prepare($sql);
$con1->execute(array(
));
while ($puntos = $con1->fetch(PDO::FETCH_ASSOC))
{
	reconvertir($db_con, fianzas, 'monto_fia', "registro = '".$puntos['registro']."'");
	reconvertir($db_con, fianzas, 'monlib_fia', "registro = '".$puntos['registro']."'");
}

$sql = "select * from ".cuentas;
$con1 = $db_con->prepare($sql);
$con1->execute(array(
));
while ($puntos = $con1->fetch(PDO::FETCH_ASSOC))
{
	reconvertir($db_con, cuentas, 'cue_saldo', "cue_codigo = '".$puntos['cue_codigo']."'");
}

$sql = "select * from ".activos. " where fechades = '0000-00-00' or ((costo-depacfecha) > 0)";
$con1 = $db_con->prepare($sql);
$con1->execute(array(
));
while ($puntos = $con1->fetch(PDO::FETCH_ASSOC))
{
	reconvertir($db_con, activos, 'costo', "cta_contab = '".$puntos['cta_contab']."'");
	reconvertir($db_con, activos, 'depacfecha', "cta_contab = '".$puntos['cta_contab']."'");
	reconvertir($db_con, activos, 'valoract', "cta_contab = '".$puntos['cta_contab']."'");
	reconvertir($db_con, activos, 'depmensual', "cta_contab = '".$puntos['cta_contab']."'");
	reconvertir($db_con, activos, 'depanual', "cta_contab = '".$puntos['cta_contab']."'");
}

set_time_limit(3000);
reconvertir_saldo_cuota($db_con);
sacar_activos($db_con);
pasar_saldo_ahorro_historico($db_con);
echo '<br>Finalizado ';
$db_con->commit();

function pasar_saldo_ahorro_historico($db_con)
{
	$sql = "select * from ".socios;
	$con1 = $db_con->prepare($sql);
	$con1->execute(array(
	));
	while ($puntos = $con1->fetch(PDO::FETCH_ASSOC))
	{
		$sql = "insert into fhis200 (cod_prof, hab_prof, hab_ucla, fecha, total_ahor, descri, pago, ip) values (:cod_prof, :hab_prof, :hab_ucla, :fecha, :total_ahor, :descri, :pago, :ip)";
		$con2 = $db_con->prepare($sql);
		$con2->execute(array(
			":cod_prof"=>$puntos['cod_prof'],
			":hab_prof"=>$puntos['hab_f_prof'],
						":hab_ucla"=>$puntos['hab_f_empr'],
			":fecha"=>'2021-09-30',
			":total_ahor"=>$puntos['hab_f_empr']+$puntos['hab_f_prof'],
			":descri"=>'Reconversion Monetaria 2021',
			":pago"=>'2019-09-30',
			":ip"=>'localhost',
		));
	}
}

function sacar_activos($db_con)
{
	$pequeno = 500000;
	$sql = "select * from ".activos. " where fechades = '0000-00-00' or ((costo-depacfecha) < 0.01)";
	$con1 = $db_con->prepare($sql);
	$con1->execute(array(
	));
	while ($puntos = $con1->fetch(PDO::FETCH_ASSOC))
	{
		$saldo = $puntos['costo']-$puntos['depacfecha'];
		if ($saldo < 0)
		{
			$sql = "update ".activos. " set fechades = '2021-09-30', motivodes = 'DESINC. RECONVERSION' where cta_contab = :cuenta and nidentif = :identificacion";
			    $con2 = $db_con->prepare($sql);
				$con2->execute(array(
					":cuenta"=>$puntos['cta_contab'],
					":identificacion"=>$puntos['nidentif'],
				));
		}
	}	
}

function reconvertir_saldo_cuota($db_con)
{
	try {
		echo '<br>buscando saldo pequenos';
		$pequeno = 5;
		$sql = "select registro, nropre_sdp, (monpre_sdp-monpag_sdp) as saldo from sgcaf310 where (monpre_sdp-monpag_sdp) < ".$pequeno. " and stapre_sdp = 'A' and ! renovado";
	    $con = $db_con->prepare($sql);
	   	echo $sql;
	    $con->execute(array(
        ));
	    while ($rdetalle = $con->fetch(PDO::FETCH_ASSOC))
    	{
    		$saldo = $rdetalle['saldo'];
    		$registro = $rdetalle['registro'];
    		$sql = "update sgcaf310 set cuota = :saldo, cuota_ucla = :saldo where registro = :registro";
    		// echo $sql. ' '.$saldo;
			echo '<br>registro'.$registro;
		    $stmt = $db_con->prepare($sql);
		    // echo $sql;
		    $stmt->execute(array(
		    	":registro"=>$registro,
		    	":saldo"=>$saldo,
	        ));
    	}
		$sql = "update sgcaf310 set stapre_sdp='C', renovado = 1, renova_por='Reconv' where renovado = 0 and ((monpre_sdp-monpag_sdp) < 0.01)";
	    $con = $db_con->prepare($sql);
	    // echo $sql;
	    $con->execute(array(
        ));
	} catch (Exception $e) {
		die($e->getMessage());
	}
}
function reconvertir($db_con, $tabla, $campo, $condicion)
{
	try {
		echo '<br>Procesando '.$tabla;
		$sql = "select ".$campo. " from ".$tabla. " where ".$condicion;
	    $con = $db_con->prepare($sql);
	    // echo $sql;
	    $con->execute(array(
        ));
	    while ($rdetalle = $con->fetch(PDO::FETCH_ASSOC))
    	{
    		// valorantes = 19500
    		//	valorantes = 0.195
    		$valorantes = $rdetalle[$campo];
    		$valorluego = trim(abs($valorantes)/1000000);
    		$valorsumar = 0;
    		if ($valorluego >= 0.01)
    		{
	    		$tamano = strlen($valorluego);
	    		$elpunto = strpos($valorluego, '.');
	    		if ($elpunto > 0)
		    		{
		    		// echo $elpunto;
		    		$luegodelpunto = substr($valorluego,($elpunto+3),$tamano);
		    		$valorluego = substr($valorluego,0,($elpunto+3));
		    		// echo 'antes'.$valorantes.'luegodelpunto'.$luegodelpunto.'valorluego'.$valorluego;
		    		if ($rdetalle[$campo] < 0)
		    			$valorluego *= (-1);
		    		if ($luegodelpunto >= 5)
			    		if ($rdetalle[$campo] < 0)
			    			$valorsumar=0.01;
			    		else 
			    			$valorsumar=0.01;
		    		$valorluego += $valorsumar;
		    	}
		    	else
		    		$luegodelpunto =0;
		    }
		    else 
		    	{
		    		$luegodelpunto = $valorantes = $rdetalle[$campo];;
		    		$valorluego = 0;
		    	}
    		echo '<br>'.$valorantes .'/'.$valorluego. ' -->  '.$luegodelpunto;

    		$sql2 = "insert into reconversion_2021 (tabla, valorantes, valorluego, condicion, desechado, campo) values (:tabla, :valorantes, :valorluego, :condicion, :desechado, :campo)";
    		$con2=$db_con->prepare($sql2);
		    $con2->execute(array(
		    	":tabla"=>$tabla,
		    	":valorantes"=>$valorantes,
		    	":valorluego"=>$valorluego,
		    	":desechado"=>$luegodelpunto,
		    	":condicion"=>$condicion,
		    	":campo"=>$campo,
    	    ));
    	    $sql2 = 'update '.$tabla. ' set '.$campo.' = '.$valorluego.' where '.$condicion;
    		$con2=$db_con->prepare($sql2);
		    $con2->execute(array());
    	}
	} catch (Exception $e) {
		die($e->getMessage());
	}
}

/*

DROP TABLE IF EXISTS `reconversion_2021`;
CREATE TABLE `reconversion` (
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `tabla` varchar(60) COLLATE latin1_spanish_ci NOT NULL,
  `valorantes` decimal(12,2) NOT NULL,
  `valorluego` decimal(12,2) NOT NULL,
  `condicion` varchar(200) COLLATE latin1_spanish_ci NOT NULL,
  `desechado` varchar(20) COLLATE latin1_spanish_ci NOT NULL,
  `registro` bigint(10) UNSIGNED ZEROFILL NOT NULL,
  `campo` varchar(60) COLLATE latin1_spanish_ci NOT NULL
) ENGINE=myisam DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `reconversion`
--
ALTER TABLE `reconversion_2021`
  ADD PRIMARY KEY (`registro`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `reconversion_2021`
--
ALTER TABLE `reconversion`
  MODIFY `registro` bigint(10) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT;
 */