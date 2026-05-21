<?php
$mostrarerrores=1;
error_reporting(E_ALL);
ini_set('display_errors',$mostrarerrores);

$codigo = $_POST['codigo'];

$jsondata = array();
require('../../conexdb.php');
include_once('../../funciones.php');
$sql = "select * from sgcaf200 where cod_prof='$codigo'";
$result = ejecutar_query($link, $sql);
if ($result->rowCount() > 0)
{
    $row = $result->fetch(PDO::FETCH_ASSOC);
    $response = array(
        'codigo' => 1,
        'mensaje' => "Datos",
        'data' => $row
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
/*
if( isset($_GET['param']) ) {

    if( $_GET['param'] == 'valor' ) {

        $jsondata['success'] = true;
        $jsondata['message'] = 'Hola! El valor recibido es correcto.';

    } else {

        $jsondata['success'] = false;
        $jsondata['message'] = 'Hola! El valor recibido no es correcto.';

    }

    //Aunque el content-type no sea un problema en la mayoría de casos, es recomendable especificarlo
    header('Content-type: application/json; charset=utf-8');
    echo json_encode($jsondata);
    exit();
}
*/
    header('Content-type: application/json; charset=utf-8');
    echo json_encode($response);
