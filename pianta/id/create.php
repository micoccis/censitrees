<?php
session_start();
//headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../models/articolo.php';

$database = new Database();
$db = $database->getConnection();
$articolo = new Articolo($db);
//$data = json_decode(file_get_contents("php://input"));

//SET VALUES

$data = new stdClass();
//$data -> titolo = $_POST['titolo'];
//$data -> categoria  =$_POST['categoria'];
$data->titolo = $_GET['titolo'];
$data->citta  =  $_GET['citta'];
$data->regione  =  $_GET['regione'];
$data->stato  =  $_GET['stato'];
$data->categoria  =  $_GET['categoria'];
$data->tipo_magazine  =  $_GET['tipo_magazine'];
$data->data_magazine  =  $_GET['data_magazine'];
$data->url_file  =  $_GET['url_file'];

//$jdata =json_encode($data);
//echo $jdata->titolo
//echo $data->titolo;

if(
    !empty($data->titolo) &&
    !empty($data->categoria)
){
    $articolo->titolo = $data->titolo;
    $articolo->citta = $data->citta;
    $articolo->regione = $data->regione;
    $articolo->stato = $data->stato;
    $articolo->categoria = $data->categoria;
    $articolo->tipo_magazine = $data->tipo_magazine;
    $articolo->data_magazine = $data->data_magazine;
    $articolo->url_file = $data->url_file;

    if($articolo->create()){
        //http_response_code(201);
       echo json_encode(array("message" => "Libro creato correttamente."));   
       //echo json_encode(array("message" => $articolo->titolo));   


    }
    else{
        //503 servizio non disponibile
        http_response_code(503);
        echo json_encode(array("message" => "Impossibile creare il libro."));
    }
}
else{
    //400 bad request
    http_response_code(400);
    echo json_encode(array("message" => "Impossibile creare il libro i dati sono incompleti."));
}
?>