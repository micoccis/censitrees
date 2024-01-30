<?php
session_start();
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
// includiamo database.php e libro.php per poterli usare
include_once '../../config/database.php';
include_once '../../models/pianta.php';
// creiamo un nuovo oggetto Database e ci colleghiamo al nostro database
$database = new Database();
$db = $database->getConnection();
// Creiamo un nuovo oggetto Libro
$pianta = new Pianta($db);

// query products
//$specie = $_GET['specie'];
//$tipoluogo = $_GET['tipoluogo'];
//$luogo =$_GET['luogo'];

//$stmt = $pianta->read($fspecie,$ftipoluogo,$fluogo);
$stmt = $pianta->readcounts();
$stmtall = $pianta->readallcounts();
$num = $stmt->rowCount();
//echo $num;
//echo $fspecie;
//echo $ftipoluogo;
//echo $fluogo;


// se vengono trovati ecotipi nel database
if($num>0){
    // array di ecotipi
    $piante_arr = array();
    $num_piante = $stmtall->fetch(PDO::FETCH_ASSOC);
   //$articoli_arr["records"] = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        $pianta_item = array(
            "localita" => $localita,
            "ecotipo" => $ecotipo,
            "numero_ecotipo" => $numero_ecotipo
        );
       //array_push($articoli_arr["records"], $articolo_item);
        array_push($piante_arr, $pianta_item);

}
$num_piante_arr = array(
    "localita" =>"piante totali",
    "ecotipo" => " ",
    "numero_ecotipo" => $num_piante['numero_piante']
);
    array_push($piante_arr,$num_piante_arr);

    echo json_encode($piante_arr);
}else{
    echo json_encode(
        //array("message" => "nessuna pianta in db.")
        array( [
            "numero_piante" => " nessuna",
        ] )
    );
}

//$stmtall = $pianta->readallcounts();
//$numall = $stmtall->rowCount();
//echo 'numero di piante ' . $numall

//$num_piante = $stmtall->fetch(PDO::FETCH_ASSOC);
//$c = extract($num_piante);
//echo json_encode($num_piante['numero_piante'])

?>