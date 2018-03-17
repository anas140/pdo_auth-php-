<?php 
	// Credentials
	define('DB_SERVER', 'localhost');
	define('DB_USERNAME', 'root');
	define('DB_PASSWORD', 'root');
	define('DB_NAME', 'php_login_app');

	// Attempt to Connect mysql_close
	try {
		// $pdo = new PDO("mysql:host=". DB_SERVER . ";dbname=" . DB_NAME, DB_USERNAME, DB_PASSWORD )
		$pdo = new PDO("mysql:host=localhost;dbname=php_login_app", 'root', 'root');
		// echo "connected";
	} catch(PDOException $e) {
		echo "Error: " . $e->getMessage() . "</br>";
	}