<?php

//$resul: total de Asientos   $conta: Asiento actual+1  $num: Asientos por pg, $que: Asiento...
function pagina1 ($resul, $conta, $num, $que, $ord,$codigo,$fechai, $fechaf) {

	echo "<div class='noimpri' style='font-size:80%'><span class = 'verdeb'>Página</span>";
	$pag = ceil($resul/$num);
	$pagactu = ceil($conta/$num);

	$i = $pagactu - 5;

	if ($i > 1) {echo " <A HREF=?conta=1&ord=$ord&codigo=$codigo&aportespagos=1&accion=fechaa&fechai=$fechai&fechaf=$fechaf><<</a> <a href='?conta=".($conta-$num)."&ord=$ord&codigo=$codigo&aportespagos=1&accion=fechaa&fechai=$fechai&fechaf=$fechaf'><</a>";}

 
	while ($i < $pagactu) {

		if ($i > 0) {echo " <a href=?conta=".((($i-1)*$num)+1)."&ord=$ord&codigo=$codigo&aportespagos=1&accion=fechaa&fechai=$fechai&fechaf=$fechaf>$i</a>";}
		$i++;

	}

	$i++;

	echo " [<span class = 'verdeb'>$pagactu</span>]";

	$n = $pagactu + 7;

	while ($i < $n) {

		if ($i <= $pag) {echo " <a href=?conta=".((($i-1)*$num)+1)."&ord=$ord&codigo=$codigo&aportespagos=1&accion=fechaa&fechai=$fechai&fechaf=$fechaf>$i</a> ";}
		$i++;

	}

	if ($i <= $pag) {
		echo "<a href='?conta=".($conta+$num)."&ord=$ord&codigo=$codigo&aportespagos=1&accion=fechaa&fechai=$fechai&fechaf=$fechaf'>></a> <a href='?conta=".((((int)($resul/$num))*$num)+1)."&ord=$ord'>>></a>";
	}

	echo " de ".ceil($resul/$num)." (".$resul." $que)</div>";

	return $pagactu == ceil($resul/$num);

}
?>
<?php

//$resul: total de Asientos   $conta: Asiento actual+1  $num: Asientos por pg, $que: Asiento...
function pagina2 ($resul, $conta, $num, $que, $ord,$codigo,$fechai, $fechaf) {

	echo "<div class='noimpri' style='font-size:80%'><span class = 'verdeb'>Página</span>";
	$pag = ceil($resul/$num);
	$pagactu = ceil($conta/$num);

	$i = $pagactu - 5;

	if ($i > 1) {echo " <A HREF=?conta=1&ord=$ord&codigo=$codigo&aportespagos=2&accion=fechad&fechai=$fechai&fechaf=$fechaf><<</a> <a href='?conta=".($conta-$num)."&ord=$ord&codigo=$codigo&aportespagos=2&accion=fechad&fechai=$fechai&fechaf=$fechaf'><</a>";}


	while ($i < $pagactu) {

		if ($i > 0) {echo " <a href=?conta=".((($i-1)*$num)+1)."&ord=$ord&codigo=$codigo&aportespagos=2&accion=fechad&fechai=$fechai&fechaf=$fechaf>$i</a>";}
		$i++;

	}

	$i++;

	echo " [<span class = 'verdeb'>$pagactu</span>]";

	$n = $pagactu + 7;

	while ($i < $n) {

		if ($i <= $pag) {echo " <a href=?conta=".((($i-1)*$num)+1)."&ord=$ord&codigo=$codigo&aportespagos=2&accion=fechad&fechai=$fechai&fechaf=$fechaf>$i</a> ";}
		$i++;

	}

	if ($i <= $pag) {
		echo "<a href='?conta=".($conta+$num)."&ord=$ord&codigo=$codigo&aportespagos=2&accion=fechad&fechai=$fechai&fechaf=$fechaf'>></a> <a href='?conta=".((((int)($resul/$num))*$num)+1)."&ord=$ord'>>></a>";
	}

	echo " de ".ceil($resul/$num)." (".$resul." $que)</div>";

	return $pagactu == ceil($resul/$num);

}
?>
<?php

//$resul: total de Asientos   $conta: Asiento actual+1  $num: Asientos por pg, $que: Asiento...
function pagina3 ($resul, $conta, $num, $que, $ord,$codigo) {

	echo "<div class='noimpri' style='font-size:80%'><span class = 'verdeb'>Página</span>";
	$pag = ceil($resul/$num);
	$pagactu = ceil($conta/$num);

	$i = $pagactu - 5;

	if ($i > 1) {echo " <A HREF=?conta=1&ord=$ord&codigo=$codigo&aportespagos=2&accion=fechaxxx><<</a> <a href='?conta=".($conta-$num)."&ord=$ord&codigo=$codigo&aportespagos=2&accion=fechaxxx'><</a>";}


	while ($i < $pagactu) {

		if ($i > 0) {echo " <a href=?conta=".((($i-1)*$num)+1)."&ord=$ord&codigo=$codigo&aportespagos=2&accion=fechaxxx>$i</a>";}
		$i++;

	}

	$i++;

	echo " [<span class = 'verdeb'>$pagactu</span>]";

	$n = $pagactu + 7;

	while ($i < $n) {

		if ($i <= $pag) {echo " <a href=?conta=".((($i-1)*$num)+1)."&ord=$ord&codigo=$codigo&aportespagos=2&accion=fechaxxx>$i</a> ";}
		$i++;

	}

	if ($i <= $pag) {
		echo "<a href='?conta=".($conta+$num)."&ord=$ord&codigo=$codigo&aportespagos=2&accion=fechaxxx'>></a> <a href='?conta=".((((int)($resul/$num))*$num)+1)."&ord=$ord'>>></a>";
	}

	echo " de ".ceil($resul/$num)." (".$resul." $que)</div>";

	return $pagactu == ceil($resul/$num);

}
?>