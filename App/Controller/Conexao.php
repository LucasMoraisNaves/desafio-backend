<?php
namespace App\Controller;
use PDO;
use Exception;

class Conexao{
	private $_dbHostname = "localhost";
	private $_dbName = "desafio_backend";
	private $_dbUsername = "root";
	private $_dbPassword = "";
	private $_con;

	function __construct()
	{
		try {
			$this->_con = new PDO("mysql:host=$this->_dbHostname;dbname=$this->_dbName",
			$this->_dbUsername,
			$this->_dbPassword);
			$this->_con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch(Exception $e) {
			echo "Falha de conexão: " . $e->getMessage();
		}
	}

	public function returnConsulta($sql) {
		try {
			$stmt = $this->_con->prepare($sql);
			$stmt->execute();
			$stmt->setFetchMode(PDO::FETCH_ASSOC);
			return $stmt->fetchAll();
		} catch(Exception $e) {
			return  "Falha ao buscar: " . $e->getMessage();
		}
	}

	public function returnAdd($sql) {
		try {
			return $this->_con->exec($sql);
		} catch(Exception $e) {
			return  "Falha na operação: " . $e->getMessage();
		}
	}

}