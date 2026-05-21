<?php

    include("conex.php");
    include('funciones.php');
	mysql_select_db('sica', $link);

	$arreglo = array('18332814','00770342','02608304','16750744','07415285','12534356',
		'04803744','02543654','19850185','10848158','11792127','07419866','05257727','07331943','20542804','10121866','12848719','10764554','05255641','08734286','05249817','10784623','07310959','09627150','14335445','07332734','11786432','11783201','05921128','10840266','13179920','11787653','09553047');
	foreach ($arreglo as $key => $value) {
		$cedula = $value;
		$cedulap = "V-".substr($cedula,0,2).'.'.substr($cedula,2,3).'.'.substr($cedula,5,3);
		$cedula = "V-".$value;
		$sql = "select cod_prof from sgcaf200 where ced_prof='$cedula'";
		$query = mysql_query($sql);
		$socio = mysql_fetch_array($query);
		$codigo = $socio['cod_prof'];

		$sql = "select nropre_sdp from sgcaf310 where codsoc_sdp='$codigo' and stapre_sdp='A' order by nropre_sdp desc limit 1";
		$query = mysql_query($sql);
		$nro = mysql_fetch_array($query);
		$nro = $nro['nropre_sdp'];
		$nro++;
		$nro = ceroizq($nro,8);
		$sigo = true;
		while ($sigo)
		{
			$sql = "select nropre_sdp from sgcaf310 where nropre_sdp='$nro'";
			$query = mysql_query($sql);
			// $numeros = mysql_fetch_array($query);
			if (mysql_num_rows($query) > 0)
			{
				// $nro = $nro['nropre_sdp'];
				$nro++;
				$nro = ceroizq($nro,8);
			}
			else 
				$sigo = false;
		}
		var_dump($nro);
		$sql="insert into sgcaf310 (codsoc_sdp, cedsoc_sdp, nropre_sdp, codpre_sdp, f_soli_sdp, f_1cuo_sdp, monpre_sdp, monpag_sdp, nrofia_sdp, stapre_sdp, tipo_fianz, cuota, nrocuotas, interes_sd, cuota_ucla, netcheque, nro_acta, fecha_acta, ip, inicial, intereses, quien) values ('$codigo', '$cedulap', '$nro','RIF','2023-11-30', '2023-11-30', 114, 0, 0, 'C', '',114, 1, 0, 114, 114, '', '', 'NINGUNA', 0, 0, '".$_SERVER['REMOTE_ADDR']."')";
		$query = mysql_query($sql);
	}



// INSERT INTO sgcaf310 (codsoc_sdp, cedsoc_sdp, nropre_sdp, codpre_sdp, f_soli_sdp, f_1cuo_sdp, ultcan_sdp, monpre_sdp, monpag_sdp, monfia_sdp, nrofia_sdp, stapre_sdp, tipo_fianz, cuota, nrocuotas, monint, interes_sd, cuota_ucla, pag_ucla, renovado, renova_por, paga_hasta, ultcan_pro, monpag_pro, stapre_pro, monint_pro, aplicado, f_pago, vige_hasta, vige_desde, hipo_hasta, protocolo, seguro, c1, c2, c3, c4, c5, c6, c7, c8, c9, c10, c11, c12, c13, c14, netcheque, ctaprestamo, ctaodeduc, ctaindebidos, otroreintegro, nro_acta, fecha_acta, ip, inicial, intereses, quien) VALUES ('', 'V-02.598.872', '', 'RIF', '2023-11-14', '2023-11-25', 0, 118.00, 0.00, 0.00, 0, 'A', '', 114, 1, 0.00, 0.00, 114, 0.00, 0, '', '1990-01-01', 0, 0.00, '', 0.000000, '', '1990-01-01', '1990-01-01', '1990-01-01', '1990-01-01', '1990-01-01', '', 0.000000, 0.000000, 0.000000, 0.000000, 0.000000, '', '', '', '', '1990-01-01', '', '', '', '', 0.0000000, '', '', '', '', '', '2023-11-13', '192.168.100.14', 0.00, 0.00, '192.168.100.14');


?>
