<?php 
	/**
	* Database Connection
	*/
	class DBCONNECT {
		private $server = '128.199.74.21';
		private $dbname = 'paperfly_db';
		private $user = 'pf_rm_usr';
		private $pass = '!@#4paperfly_db$';

		public function connect() {
			try {
				$conn = new PDO('mysql:host=' .$this->server .';dbname=' . $this->dbname, $this->user, $this->pass);
				$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				return $conn;
			} catch (\Exception $e) {
				echo "Database Error: " . $e->getMessage();
			}
		}
	}
	
 ?>