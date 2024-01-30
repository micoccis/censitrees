<?php
class Pianta
	{
	private $conn;
	private $table_name = "piante";
	//private $filter = " Italia";
	// proprietà di un libro
	public $id_pianta;
	public $specie;
	public $stima_altezza;
	public $stima_larghezza;
	public $diametro;
	public $ecotipo;
	public $stato_fito;
	public $associazione;
	public $lat;
	public $lon;
	public $localita;
	public $comune;
	public $provincia;
	public $nome;
	public $cognome;
	public $url_file;
	public $nota;

	// per censimento mio castagneto
	public $qrcode;
	public $settore;

	// COSTRUTTORE
	public function __construct($db)
		{
		$this->conn = $db;
		}
	// READ ID PIANTA
	function readid($id_pianta)
		{
		//function read($fspecie,$fluogo,$ftipoluogo)
		// select all
		//$myf = "Italia";
		$query = "SELECT
						a.*
					FROM
				" . $this->table_name . " a " . "WHERE id_pianta = " . $id_pianta;
		$stmt = $this->conn->prepare($query);
		// execute query
		$stmt->execute();
		return $stmt;
		}
	
	// READ PIANTA FILTRO
	function read($specie,$tipoluogo,$luogo)
		{
		//function read($fspecie,$fluogo,$ftipoluogo)
		// select all
		//$myf = "Italia";
		$query = "SELECT
                        a.*
                    FROM
				   " . $this->table_name . " a " . "WHERE specie LIKE '" . $specie . "%' AND " . $tipoluogo . " LIKE  '" . $luogo . "%' ORDER BY a.localita";
		$stmt = $this->conn->prepare($query);
		// execute query
		$stmt->execute();
		return $stmt;
		}
	
	// READ COORDINATESfunction read($specie,$tipoluogo,$luogo)
	function readcoord()
		//function read($fspecie,$fluogo,$ftipoluogo)
		{
		// select all
		//$myf = "Italia";
		$query = "SELECT
                        a.*
                    FROM
				   " . $this->table_name . " a " . " ORDER BY a.localita DESC";
		$stmt = $this->conn->prepare($query);
		// execute query
		$stmt->execute();
		return $stmt;
		}
	

	//COUNTS ECOTIPS
	function readcounts()
		//function read($fspecie,$fluogo,$ftipoluogo)
		{
		// select all
		//$myf = "Italia";
		// "SELECT `ecotipo`, COUNT(`id_pianta`) FROM `piante` GROUP BY `ecotipo`";
		$query = "SELECT
                     ecotipo, GROUP_CONCAT(localita SEPARATOR '<br>') AS localita, COUNT(id_pianta) AS numero_ecotipo
                    FROM
				   " . $this->table_name . " GROUP BY ecotipo ASC";
		$stmt = $this->conn->prepare($query);
		// execute query
		$stmt->execute();
		return $stmt;
		}

	// COUNT ENTRIES
	function readallcounts()
		//function read($fspecie,$fluogo,$ftipoluogo)
		{
		// select all
		//$myf = "Italia";
		// "SELECT `ecotipo`, COUNT(`id_pianta`) FROM `piante` GROUP BY `ecotipo`";
		$query = "SELECT
                        COUNT(id_pianta) AS numero_piante
                    FROM
				   " . $this->table_name ;
		$stmt = $this->conn->prepare($query);
		// execute query
		$stmt->execute();
		return $stmt;
		}

	// CREARE PIANTA
	function create(){
		$query = "INSERT INTO
					" . $this->table_name . "
				SET
				specie=:specie, stima_altezza=:stima_altezza, stima_larghezza=:stima_larghezza,
				diametro=:diametro, ecotipo=:ecotipo, stato_fito=:stato_fito,
				associazione=:associazione, lat=:lat, lon=:lon, localita=:localita, comune=:comune, provincia=:provincia,
				nome=:nome, cognome=:cognome,url_file=:url_file, nota=:nota";

		$stmt = $this->conn->prepare($query);
		
		$this->specie = htmlspecialchars(strip_tags($this->specie));
		$this->stima_altezza = htmlspecialchars(strip_tags($this->stima_altezza));
		$this->stima_larghezza = htmlspecialchars(strip_tags($this->stima_larghezza));
		$this->diametro = htmlspecialchars(strip_tags($this->diametro));
		$this->ecotipo = htmlspecialchars(strip_tags($this->ecotipo));
		$this->stato_fito = htmlspecialchars(strip_tags($this->stato_fito));
		$this->associazione = htmlspecialchars(strip_tags($this->associazione));

		$this->lat = htmlspecialchars(strip_tags($this->lat));
		$this->lon = htmlspecialchars(strip_tags($this->lon));
		$this->localita = htmlspecialchars(strip_tags($this->localita));
		$this->comune = htmlspecialchars(strip_tags($this->comune));
		$this->provincia = htmlspecialchars(strip_tags($this->provincia));
		$this->nome = htmlspecialchars(strip_tags($this->nome));
		$this->cognome = htmlspecialchars(strip_tags($this->cognome));
		$this->url_file = htmlspecialchars(strip_tags($this->url_file));
		$this->nota = htmlspecialchars(strip_tags($this->nota));
	
		// binding
		$stmt->bindParam(':specie', $this->specie);
		$stmt->bindParam(':stima_altezza', $this->stima_altezza);
		$stmt->bindParam(':stima_larghezza', $this->stima_larghezza);
		$stmt->bindParam(':diametro', $this->diametro);
		$stmt->bindParam(':ecotipo', $this->ecotipo);
		$stmt->bindParam(':stato_fito', $this->stato_fito);
		$stmt->bindParam(':associazione', $this->associazione);
		$stmt->bindParam(':lat', $this->lat);
		$stmt->bindParam(':lon', $this->lon);
		$stmt->bindParam(':localita', $this->localita);
		$stmt->bindParam(':comune', $this->comune);
		$stmt->bindParam(':provincia', $this->provincia);
		$stmt->bindParam(':nome', $this->nome);
		$stmt->bindParam(':cognome', $this->cognome);
		$stmt->bindParam(':url_file', $this->url_file);
		$stmt->bindParam(':nota', $this->nota);

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