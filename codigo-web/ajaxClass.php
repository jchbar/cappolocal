<?php
class ajax {
	var $queryParam = array();
	var $result =  '';
	var $montoprestamo = 0;
	var $num_cuotas = 0;
	var $p_interes = 0;
	var $divisible = 0;
	var $tipo_interes = '';
	var $f_ajax = '';
	var $calcular = '';
	var $cuota=0;
	var $interes=0;

	function cal2int($interes,$mcuotas,$mmonpre_sdp,$factor_divisible = 12,&$z=0,&$i2=0)
	{
		if ($interes > 0) {
			$i = ((($interes / 100)) / $factor_divisible);
			$i2 = $i;
//			$_SESSION['i2']=$i2;
			$i_ = 1 + $i;
			$i_ = pow($i_,$mcuotas); 	// exponenciacion 
			$i_ = 1 / $i_;
			$i__ = 1 - $i_;
			$i___ = $i / $i__;
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
	
	function calint($monto, $interes, $mcuotas,$factor_divisible = 12)
	{
		$y=$this->cal2int($interes,$mcuotas,$monto,$factor_divisible,$z,$i2);
		echo '------------';
		$k = $ia = $cu22 = $ac = $tc = $ta = 0;
		$_c1 = $monto;
		$i1 = $interes;
		$n = $mcuotas;
		for ($k=0;$k<$n;$k++)
		{
			$i1 = $_c1*$i2;
			$cu22 = $z - $i1;
			$_c1 = $_c1-$cu22;
			$ia = $ia + $i1;
			$ac = $ac + $cu22;
			$ta = $ta+ $z;
		}
		return $ia;
	}
	
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
				/* 		funciona bien para un solo valor 
					if ($this->queryParam["cual"] == 1)
						$this->result = number_format($this->cal2int($this->queryParam["p_interes"],$this->queryParam["num_cuotas"],$this->queryParam["montoprestamo"],$this->queryParam["divisible"]),2,'.','');
					else 
					$this->result = number_format($this->calint($this->queryParam["montoprestamo"],$this->queryParam["p_interes"],$this->queryParam["num_cuotas"],$this->queryParam["divisible"]),2,'.','');
					*/
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
					echo "</resultados>";
					}
				if (strtoupper($this->queryParam["f_ajax"])=='DIRECTO') 	// monto * porcentaje / nrocuotas
					$this->result = number_format($this->directo($this->queryParam["p_interes"],$this->queryParam["num_cuotas"],$this->queryParam["montoprestamo"],$this->queryParam["divisible"]),2,'.','');
				if (strtoupper($this->queryParam["f_ajax"])=='REVERTIDO') 	// monto * porcentaje / nrocuotas
					$this->result = number_format($this->revertido($this->queryParam["p_interes"],$this->queryParam["num_cuotas"],$this->queryParam["montoprestamo"],$this->queryParam["divisible"]),2,'.','');
				}
	}
	
}

//			$calcular='$this->'.$this->queryParam["f_ajax"].'($this->queryParam["p_interes"],$this->queryParam["num_cuotas"],$this->queryParam["montoprestamo"],$this->queryParam["divisible"])';
//			$funcionllamar=$this->queryParam["f_ajax"];
//			call_user_func($funcionllamar,$this->queryParam["p_interes"],$this->queryParam["num_cuotas"],$this->queryParam["montoprestamo"],$this->queryParam["divisible"]);

//			$calcular="'".$calcular."'"; 
//			echo $calcular;
//			$calcular(); // number_format($calcular,2,'.','');
//						                  $this->cal2int($this->queryParam["p_interes"],$this->queryParam["num_cuotas"],$this->queryParam["montoprestamo"],$this->queryParam["divisible"])

?>