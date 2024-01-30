<?php
class Articolo
	{
	private $conn;
	private $table_name = "geomagazine";
	//private $filter = " Italia";
	// proprietà di un libro
	public $id_titolo;
	public $titolo;
	public $tipo_magazine;
	public $data_magazine;
	public $anno_magazine;
	public $categoria;
	public $citta;
	public $regione;
	public $stato;
	public $url_file;
	public $folder_magazine;

	// COSTRUTTORE
	public function __construct($db)
		{
		$this->conn = $db;
		}
	// READ ARTICOLO
	function read($fcategoria, $ftipoluogo, $fluogo)
		{
		// select all
		//$myf = "Italia";
		$query = "SELECT
                        a.*
                    FROM
				   " . $this->table_name . " a " . "WHERE categoria LIKE '" . $fcategoria . "%' AND " . $ftipoluogo . " LIKE '" . $fluogo . "%' ORDER BY a.id_titolo DESC" ;
		$stmt = $this->conn->prepare($query);
		// execute query
		$stmt->execute();
		return $stmt;
		}
	
	// CREARE LIBRO
	function create(){
		// titolo=:titolo, citta=:citta, regione=:regione, stato=:stato, categoria=:categoria, tipomagazine=:tipomagazine, data_magazine=:data_magazine, url_file=:url_file";

		$query = "INSERT INTO
					" . $this->table_name . "
				SET
				titolo=:titolo, categoria=:categoria, citta=:citta,
				regione=:regione, stato=:stato, tipo_magazine=:tipomagazine,
				data_magazine=:data_magazine, url_file=:url_file";

		$stmt = $this->conn->prepare($query);
		
		$this->titolo = htmlspecialchars(strip_tags($this->titolo));
		$this->categoria = htmlspecialchars(strip_tags($this->categoria));
		$this->citta = htmlspecialchars(strip_tags($this->citta));
		$this->regione = htmlspecialchars(strip_tags($this->regione));
		$this->stato = htmlspecialchars(strip_tags($this->stato));
		$this->tipo_magazine = htmlspecialchars(strip_tags($this->tipo_magazine));
		$this->data_magazine = htmlspecialchars(strip_tags($this->data_magazine));
		$this->url_file = htmlspecialchars(strip_tags($this->url_file));

		/* $this->citta = htmlspecialchars(strip_tags($this->citta));
		$this->regione = htmlspecialchars(strip_tags($this->regione));
		$this->stato = htmlspecialchars(strip_tags($this->stato));
		$this->categoria = htmlspecialchars(strip_tags($this->categoria));
		$this->tipomagazine = htmlspecialchars(strip_tags($this->tipomagazine));
		$this->data_magazine = htmlspecialchars(strip_tags($this->data_magazine));
		$this->url_file = htmlspecialchars(strip_tags($this->url_file)); */

	
		// binding
		$stmt->bindParam(':titolo', $this->titolo);
		$stmt->bindParam(':categoria', $this->categoria);
		$stmt->bindParam(':citta', $this->citta);
		$stmt->bindParam(':regione', $this->regione);
		$stmt->bindParam(':stato', $this->stato);
		$stmt->bindParam(':tipomagazine', $this->tipo_magazine);
		$stmt->bindParam(':data_magazine', $this->data_magazine);
		$stmt->bindParam(':url_file', $this->url_file);

	/* 	$stmt->bindParam(':titolo', $this->titolo);
		$stmt->bindParam(':citta', $this->citta);
		$stmt->bindParam(':regione', $this->regione);
		$stmt->bindParam(':stato', $this->stato);
		$stmt->bindParam(':categoria', $this->categoria);
		$stmt->bindParam(':tipomagazine', $this->tipomagazine);
		$stmt->bindParam(':data_magazine', $this->data_magazine);
		$stmt->bindParam(':url_file', $this->url_file);
 */
	
		// execute query
		if($stmt->execute()){
			return true;
		}
	
		return false;
	
	}

	// AGGIORNARE LIBRO
	// CANCELLARE LIBRO
	}
?>