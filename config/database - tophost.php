<?php
class Database
	{
	// credenziali

	// private $host = "localhost";
	// private $db_name = "Biblioteca";
	// private $username = "root";
	// private $password = "";
	public $conn;
	/* 
	 switch ($_SERVER['SERVER_NAME']) {
		case 'www.micocci.eu':
			$server = 'sql.micocci.eu';
			$user = 'micoccie32733';
			$pwd = 'ilsemanet';
			$db = 'micoccie32733';
		break;
		case 'micocci.eu':
			$server = 'sql.micocci.eu';
			$user = 'micoccie32733';
			$pwd = 'ilsemanet';
			$db = 'micoccie32733';
		break;

		default:
			$server = '127.0.0.1';
			$user = 'root';
			$pwd = '';
			$db = 'micoccie32733';
		break;
	} */
	public $server = 'sql.micocci.eu';
	public	$user = 'micoccie32733';
	public	$pwd = 'ilsemanet';
	public $db = 'micoccie32733';

	// connessione al database
	public function getConnection()
		{
		$this->conn = null;
		try
			{
			$this->conn = new PDO("mysql:host=" . $this->server . ";dbname=" . $this->db, $this->user, $this->pwd);
			$this->conn->exec("set names utf8");
			}
		catch(PDOException $exception)
			{
			echo "Errore di connessione: " . $exception->getMessage();
			}
		return $this->conn;
		}
	}
?>