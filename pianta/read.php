<?php
session_start();
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
// includiamo database.php e libro.php per poterli usare
include_once '../config/database.php';
include_once '../models/pianta.php';
// creiamo un nuovo oggetto Database e ci colleghiamo al nostro database
$database = new Database();
$db = $database->getConnection();
// Creiamo un nuovo oggetto Libro
$pianta = new Pianta($db);

// query products
$specie = $_GET['specie'];
$tipoluogo = $_GET['tipoluogo'];
$luogo =$_GET['luogo'];

//$stmt = $pianta->read($fspecie,$ftipoluogo,$fluogo);
$stmt = $pianta->read($specie,$tipoluogo,$luogo);

$num = $stmt->rowCount();
//echo $num;
//echo $fspecie;
//echo $ftipoluogo;
//echo $fluogo;

// se vengono trovati libri nel database
if($num>0){
    // array di libri
    $piante_arr = array();
   //$articoli_arr["records"] = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        $pianta_item = array(
            "id_pianta" => $id_pianta,
	        "specie" => $specie,
	        "stima_altezza" => $stima_altezza,
	        "stima_larghezza" => $stima_larghezza,
	        "diametro" => $diametro,
	        "ecotipo" => $ecotipo,
	        "stato_fito" => $stato_fito,
	        "associazione" => $associazione,
	        "nota" => $nota,
            "lat" => $lat,
            "lon" => $lon,
            "localita" => $localita,
            "comune" => $comune,
            "provincia" => $provincia,
            "nome" => $nome,
            "cognome" => $cognome,
            "url_file" => $url_file
        );
       //array_push($articoli_arr["records"], $articolo_item);
        array_push($piante_arr, $pianta_item);

}
    echo json_encode($piante_arr);
}else{
    echo json_encode(
        //array("message" => "Nessun Libro Trovato.")
        array( [
            "id_pianta" => " nessuno",
	        "specie" => "",
	        "stima_altezza" => "",
	        "stima_larghezza"=> "",
	        "diametro"=> "",
	        "ecotipo" => "",
	        "stato_fito"=> "",
	        "associazione"=> "",
	        "nota"=> "",
            "lat"=> "",
            "lon" => "",
            "localita"=>"" ,
            "comune"=> "",
            "provincia"=> "",
            "nome"=> "",
            "cognome" => "",
            "url_file"=> ""
        ] )
    );
}
?>