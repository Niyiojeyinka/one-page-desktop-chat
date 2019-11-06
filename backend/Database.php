<?php
/**
 * @Description: This handles the database connection
 * and serve as a mini query builder
 */
require 'constants.php';
class Database {
	public $conn;
	public function __construct() {
		
		$hostname = $dbData['hostname'];
		$username = $dbData['username'];
		$password = $dbData['password'];
		$database = $dbData['database'];


		// Create connection
		$this->conn = new mysqli($hostname, $username, $password, $database);

		// Check connection
		if ($this->conn->connect_error) {
			die("Connection failed: " . $this->conn->connect_error);
		}
		//echo "Connection was successfully established!";
  }
	
	public function query($sql) {
		if ($this->conn->query($sql) === true) {
			return true;
		}
		return false;
	}

  public function select($sql) {
		$result = $this->conn->query($sql);

		if ($result->num_rows > 0 && $result->num_rows != 1 ) {
			$resultToReturn = [];
			while ($row = $result->fetch_assoc()) {
				array_push($resultToReturn, $row);
			}
			return $resultToReturn;
		}elseif ( $result->num_rows == 1 ) {
            while ($row = $result->fetch_assoc()) {
				return $row;
			}
		}
		return 0;
	}
	

  public function close() {
    $this->conn->close();
  }
}

$db = new Database;