<?php
session_start();
//headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../models/pianta.php';

$database = new Database();
$db = $database->getConnection();
$pianta = new Pianta($db);
//$data = json_decode(file_get_contents("php://input"));

//SET VALUES

$data = new stdClass();
//$data -> titolo = $_POST['titolo'];
//$data -> categoria  =$_POST['categoria'];
$data->specie = $_GET['specie'];
$data->stima_altezza  =  $_GET['stima_altezza'];
$data->stima_larghezza  =  $_GET['stima_larghezza'];
$data->diametro  =  $_GET['diametro'];
$data->ecotipo  =  $_GET['ecotipo'];
$data->stato_fito  =  $_GET['stato_fito'];
$data->associazione  =  $_GET['associazione'];

$data->lat  =  $_GET['lat'];
$data->lon  =  $_GET['lon'];
$data->localita  =  $_GET['localita'];
$data->comune =  $_GET['comune'];
$data->provincia  =  $_GET['provincia'];
$data->nome  =  $_GET['nome'];
$data->cognome  =  $_GET['cognome'];
$data->url_file  =  $_GET['url_file'];
$data->nota  =  $_GET['nota'];


//$jdata =json_encode($data);
//echo $jdata->titolo
//echo $data->titolo;

if(
    !empty($data->specie) &&
    !empty($data->stima_altezza)
){
    $pianta->specie = $data->specie;
    $pianta->stima_altezza = $data->stima_altezza;
    $pianta->stima_larghezza = $data->stima_larghezza;
    $pianta->diametro = $data->diametro;
    $pianta->ecotipo = $data->ecotipo;
    $pianta->stato_fito = $data->stato_fito;
    $pianta->associazione = $data->associazione;

    $pianta->lat = $data->lat;
    $pianta->lon = $data->lon;
    $pianta->localita = $data->localita;
    $pianta->comune = $data->comune;
    $pianta->provincia = $data->provincia;
    $pianta->nome = $data->nome;
    $pianta->cognome = $data->cognome;
    $pianta->url_file = $data->url_file;
    $pianta->nota = $data->nota;


    if($pianta->create()){
        //http_response_code(201);
       echo json_encode(array("message" => "Scheda inserita correttamente."));   
       //echo json_encode(array("message" => $articolo->titolo));   


    }
    else{
        //503 servizio non disponibile
        http_response_code(503);
        echo json_encode(array("message" => "Impossibile creare la scheda."));
    }
}
else{
    //400 bad request
    http_response_code(400);
    echo json_encode(array("message" => "Impossibile creare la nuova scheda i dati sono incompleti."));
}
?>