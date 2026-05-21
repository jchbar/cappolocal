<?php
session_start();
extract($_GET);
extract($_POST);
extract($_SESSION);

include("conex.php");

/*
include("ajaxClass.php");

$objSem = new ajax;
$objSem->readURLParameters();
$objSem->staticExample();
echo $objSem->result;
*/

$p_interes=$_GET["p_interes"];
$num_cuotas=$_GET["num_cuotas"];
$montoprestamo=$_GET["montoprestamo"];
$divisible=$_GET["divisible"];
$p_interes=$_GET["p_interes"];
$tipo_interes=strtoupper($_GET["tipo_interes"]);
$descontar_interes=$_GET["descontar_interes"];
$monto_futuro=$_GET["monto_futuro"];
if (($tipo_interes)=='NOAPLICA') {
	$cuota = number_format(($montoprestamo/$num_cuotas),2,'.',''); 
	$interes = 0.00;
}
else if (($tipo_interes)=='DIRECTOFUTURO') {
//	$interes = number_format(directo($p_interes,$num_cuotas,$montoprestamo,$divisible),2,'.','');
	$interes= number_format(($montoprestamo*($p_interes/100)),2,'.','');
	$cuota = number_format((($montoprestamo+$interes)/$num_cuotas),2,'.',''); 
}
else if (($tipo_interes)=='DIRECTO') {
	$cuota = number_format(($montoprestamo/$num_cuotas),2,'.',''); 
//	$interes = number_format(directo($p_interes,$num_cuotas,$montoprestamo,$divisible),2,'.','');
	$interes= number_format(calint($montoprestamo,$p_interes,$num_cuotas,$divisible,$cuota),2,'.','');
}
else if (($tipo_interes)=='AMORTIZADA') {
	$cuota = number_format(cal2int($p_interes,$num_cuotas,$montoprestamo,$divisible),2,'.','');
	$interes= number_format(calint($montoprestamo,$p_interes,$num_cuotas,$divisible),2,'.','');
}
else {
	$cuota = number_format(cal2int($p_interes,$num_cuotas,$montoprestamo,$divisible),2,'.','');
	$interes= number_format(calint($montoprestamo,$p_interes,$num_cuotas,$divisible),2,'.','');
}
if ((($descontar_interes == 0) and ($tipo_interes)=='AMORTIZADA') or (($descontar_interes == 0) and ($tipo_interes)=='NO APLICA'))
	$interes = 0.00;

// $cuota = number_format(cal2int($p_interes,$num_cuotas,$montoprestamo,$divisible),2,'.','');
// $interes= number_format(calint($montoprestamo,$p_interes,$num_cuotas,$divisible),2,'.','');
$gtoadm=restaradministrativos($montoprestamo);
$neto=($montoprestamo-$interes)-$gtoadm;
/*
if (($monto_futuro)!=0) {
	$cuota=$montoprestamo/$num_cuotas;
}
*/
if (($tipo_interes)=='DIRECTOFUTURO') {
	$interes = 0.00;
	$neto=(($montoprestamo)-$gtoadm);
}
//echo '<?xml version="1.0">'; //  encoding="utf-8">';
// echo '<?xml version="1.0" encoding="ISO-8859-1">';
// echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?".">"; 
header("Content-Type: text/xml");
echo '<?xml version="1.0" encoding="utf-8"?>';
echo "<resultados>";
echo utf8_encode("<cuota>$cuota</cuota>");		// sirve asi y como esta abajo tambien
//echo "<cuota>".$interes."</cuota>";
echo "<interes_diferido>".$interes."</interes_diferido>";
echo "<montoneto>".$neto."</montoneto>";
echo "<gastosadministrativos>".$gtoadm."</gastosadministrativos>";
echo "</resultados>";
	


	function cal2int($interes,$mcuotas,$mmonpre_sdp,$factor_divisible = 12,$z=0,$i2=0)
	{
		if ($interes > 0) {
			$i = ((($interes / 100)) / $factor_divisible);
//			echo 'i = '.$i.'<br>';
			$i2 = $i;
//			$_SESSION['i2']=$i2;
			$i_ = 1 + $i;
//			echo 'i_ = '.$i_.'<br>';
			$i_ = pow($i_,$mcuotas); 	// exponenciacion 
			$i_ = 1 / $i_;
			$i__ = 1 - $i_;
//			echo 'i__ = '.$i__.'<br>';
			$i___ = $i / $i__;
//			echo 'i___ = '.$i___.'<br>';
			$z = $mmonpre_sdp * $i___;
			}
		if ($interes ==0)
			$z = $mmonpre_sdp / $mcuotas;
/*
	    ((1 + i)^n) - 1
	i =-----------------
	           i
*/
//		$this->result=$z;
//		$_SESSION['z']=$z;
		return $z;
	}

	function directo($interes,$mcuotas,$mmonpre_sdp,$factor_divisible = 12)
	{
		if ($interes > 0) {
			$_interes=$mmonpre_sdp * ($interes / 100);
			$z = ($mmonpre_sdp + $_interes) / $mcuotas; 
			}
		if ($interes ==0)
			$z = $mmonpre_sdp / $mcuotas;
		return $z;
	}

	function revertido($interes,$mcuotas,$mmonpre_sdp,$factor_divisible = 12)
	{
		if ($interes > 0) {
			$_interes=$mmonpre_sdp / ($interes / 100);
			$z = ($mmonpre_sdp + $_interes) / $mcuotas; 
			}
		if ($interes ==0)
			$z = $mmonpre_sdp / $mcuotas;
		return $z;
	}
	
	function calint($monto, $interes, $mcuotas,$factor_divisible = 12,$cuota2=0)
	{
		$y=cal2int($interes,$mcuotas,$monto,$factor_divisible,&$z,&$i2);
		if ($cuota2 != 0) $z=$cuota2;
//		echo $z.'------------'. $i2.'<br>';
		$k = $ia = $cu22 = $ac = $tc = $ta = 0;
		$_c1 = $monto;
		$i1 = $interes;
		$n = $mcuotas;
//		echo $z.'<br>';
		for ($k=0;$k<$n;$k++)
		{
			$i1 = $_c1*$i2;
			$cu22 = $z - $i1;
			$_c1 = $_c1-$cu22;
			$ia = $ia + $i1;
			$ac = $ac + $cu22;
			$ta = $ta+ $z;
//			echo $_c1.' - '.$ac.' - '.$ta.' - '.$i1.' - '.$ia.' - '.$ac.'<br>';
		}
		return $ia;
	}
	
/*
	function readURLParameters() {
		$qstr = explode("&", $_SERVER['QUERY_STRING']);
		foreach ($qstr as $value) {
			$paramVal = explode("=",$value);
			if (array_key_exists(1,$paramVal)) {
				$this->queryParam[$paramVal[0]] = $paramVal[1];
			}
		}
	}

	function staticExample() {
		if (array_key_exists("montoprestamo",$this->queryParam) & array_key_exists("num_cuotas",$this->queryParam)) 
			if (strtoupper($this->queryParam["tipo_interes"])=='NO APLICA')
				$this->result = number_format($this->queryParam["montoprestamo"]/$this->queryParam["num_cuotas"],2,'.','');
			else {
				if (strtoupper($this->queryParam["f_ajax"])=='CAL2INT') {
				// 		funciona bien para un solo valor 
//					if ($this->queryParam["cual"] == 1)
//						$this->result = number_format($this->cal2int($this->queryParam["p_interes"],$this->queryParam["num_cuotas"],$this->queryParam["montoprestamo"],$this->queryParam["divisible"]),2,'.','');
//					else 
//					$this->result = number_format($this->calint($this->queryParam["montoprestamo"],$this->queryParam["p_interes"],$this->queryParam["num_cuotas"],$this->queryParam["divisible"]),2,'.','');
					//
					$this->cuota= number_format($this->cal2int($this->queryParam["p_interes"],$this->queryParam["num_cuotas"],$this->queryParam["montoprestamo"],$this->queryParam["divisible"]),2,'.','');
					$this->interes= number_format($this->calint($this->queryParam["montoprestamo"],$this->queryParam["p_interes"],$this->queryParam["num_cuotas"],$this->queryParam["divisible"]),2,'.','');
					
					$this->result="<?xml version='1.0' encoding='ISO-8859-1'?>";
					$this->result.="<resultados>";
					$this->result.="<cuota>".$this->cuota."</cuota>";
					$this->result.="<interes_diferido>".$this->interes."</interes_diferido>";
					$this->result.="</resultados>";
//					echo $this->result;
					echo "<?xml version='1.0' encoding='ISO-8859-1'?>";
					echo "<resultados>";
					echo "<cuota>".$this->cuota."</cuota>";
					echo "<interes_diferido>".$this->interes."</interes_diferido>";
					echo "<neto>".$this->queryParam["montoprestamo"]-$this->interes."</neto>";
					echo "</resultados>";
					}
				if (strtoupper($this->queryParam["f_ajax"])=='DIRECTO') 	// monto * porcentaje / nrocuotas
					$this->result = number_format($this->directo($this->queryParam["p_interes"],$this->queryParam["num_cuotas"],$this->queryParam["montoprestamo"],$this->queryParam["divisible"]),2,'.','');
				if (strtoupper($this->queryParam["f_ajax"])=='REVERTIDO') 	// monto * porcentaje / nrocuotas
					$this->result = number_format($this->revertido($this->queryParam["p_interes"],$this->queryParam["num_cuotas"],$this->queryParam["montoprestamo"],$this->queryParam["divisible"]),2,'.','');
				}
	}
	
*/

function restaradministrativos($montoprestamo)
{
	$sql_deduccion="select * from sgcaf311 where activar = 1";
	$a_deduccion=mysql_query($sql_deduccion);
	$d_obligatorias=0;
	while($r_deduccion=mysql_fetch_assoc($a_deduccion)) {
		if ($r_deduccion['porcentaje'] == 0)
			$monto_deduccion=$r_deduccion['monto'];
		else $monto_deduccion=($montoprestamo)*($r_deduccion['porcentaje']/100);
		$d_obligatorias+=$monto_deduccion;
		}
	return $d_obligatorias;
}
	

//			$calcular='$this->'.$this->queryParam["f_ajax"].'($this->queryParam["p_interes"],$this->queryParam["num_cuotas"],$this->queryParam["montoprestamo"],$this->queryParam["divisible"])';
//			$funcionllamar=$this->queryParam["f_ajax"];
//			call_user_func($funcionllamar,$this->queryParam["p_interes"],$this->queryParam["num_cuotas"],$this->queryParam["montoprestamo"],$this->queryParam["divisible"]);

//			$calcular="'".$calcular."'"; 
//			echo $calcular;
//			$calcular(); // number_format($calcular,2,'.','');
//						                  $this->cal2int($this->queryParam["p_interes"],$this->queryParam["num_cuotas"],$this->queryParam["montoprestamo"],$this->queryParam["divisible"])

?>