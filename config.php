<?php 
	//directorio de instalación
	function url(){
		return 'http://localhost/mercurio';
	}

	//conexión con base de datos
	$server = 'localhost';
	$user = 'root';
	$pass = '';
	$db = 'db';

	$dsn = "mysql:host=$server;dbname=$db;charset=utf8mb4";
	$options = [
    	PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
   		PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
		PDO::ATTR_EMULATE_PREPARES => false,
	];

	try {
		$conn = new PDO($dsn, $user, $pass, $options);
	} catch (\PDOException $e) {
    	throw new \PDOException($e->getMessage(), (int)$e->getCode());
	}

	//funciones
	include 'inc/php/functions.php';

	//fingerprint
	
?>