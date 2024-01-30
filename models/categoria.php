<?php
class Categoria
	{
	private $conn;
	private $table_name = "geocategorie";
	// proprietà di un libro
	public $id_categoria;
	public $tipo_categoria;
	public $des_categoria;
	

	// costruttore
	public function __construct($db)
		{
		$this->conn = $db;
		}
	// READ libri
	function read()
		{
		// select all
		$query = "SELECT
                        a.id_categoria, a.tipo_categoria, a.des_categoria
                    FROM
                   " . $this->table_name . " a " . "ORDER BY  a.tipo_categoria ASC";
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