<?php
class ajax {
	var $queryParam = array();
	var $result = 0;
	var $num1 = 0;
	var $num2 = 0;

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
		if (array_key_exists("num1",$this->queryParam) & array_key_exists("num2",$this->queryParam)) {
			$this->result = $this->queryParam["num1"] * $this->queryParam["num2"];
			$this->num1 = $this->queryParam["num1"];
			$this->num2 = $this->queryParam["num2"];
		}
	}
}
?>