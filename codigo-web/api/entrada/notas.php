<?php 
	include_once('../../funciones.php');
	include_once('../../conex.php');
	$comando = "SELECT usuario, asunto, nota FROM sgcanota WHERE fecha = '".date('Y-m-d')."' OR fecha = '00/00/00' ORDER BY id DESC";
	$result = ejecutar_query($link, $comando);
	if ($result->rowCount() > 0)
	{
	    while($row = $result->fetch(PDO::FETCH_ASSOC))
	    {
	    	$data[] = $row;
		}

    	$response = array(
			'codigo' => 1,
			'mensaje' => "Datos",
			'data' => $data
		);
	} 
	else 
	{
		$data = array();
    	$response = array(
			'codigo' => 0,
			'mensaje' => "Datos no encontrados",
			'data' => $data
		);
	}

	echo json_encode($response);

?>