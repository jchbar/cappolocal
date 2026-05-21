<?php 
	include_once('../../funciones.php');
	include_once('../../conex.php');
	$comando = "SELECT cod_prof, ced_prof, concat(ape_prof, ' ', nombr_prof) as nombrecompleto, upper(statu_prof) as status FROM sgcaf200 order by cod_prof";
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