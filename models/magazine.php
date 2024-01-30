<?php
class Magazine
	{
	private $conn;
	private $table_name = "geotipomagazine";
	// proprietà di un libro
	public $id_magazine;
	public $tipo_magazine;
	public $des_magazine;
	

	// costruttore
	public function __construct($db)
		{
		$this->conn = $db;
		}
	// READ magazine
	function read()
		{
		// select all
		$query = "SELECT
                        a.id_magazine, a.tipo_magazine, a.des_magazine
                    FROM
                   " . $this->table_name . " a " . "ORDER BY  a.tipo_magazine ASC";
		$stmt = $this->conn->prepare($query);
		// execute query
		$stmt->execute();
		return $stmt;
		}
	// CREARE LIBRO
	// AGGIORNARE LIBRO
	// CANCELLARE LIBRO
	}
?>