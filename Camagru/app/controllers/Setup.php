<?php
class Setup extends Controller
{

	public function index()
	{
		try {
			$options = array(
				PDO::ATTR_PERSISTENT => true,
				PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
			);
			$dsn = 'mysql:host=' . DB_HOST . ';';
			$cn = new PDO($dsn, DB_USER, DB_PASS, $options);
			$sql = "DROP DATABASE IF EXISTS " . DB_NAME . ";";
			$cn->exec($sql);
			echo "Removing any pre-existing 'camagru' database\n";
			$sql = "CREATE DATABASE IF NOT EXISTS " . DB_NAME . ";";
			$cn->exec($sql);
			echo "Fresh database 'camagru' successfully created\n";
			$cn->exec('use ' . DB_NAME . ';');
			echo "Switching to " . DB_NAME . "\n";
			$sql = file_get_contents(__DIR__.'/../config/Camagru.sql');
			$cn->exec($sql);
			echo "Database schema imported\n";
			echo "OK -> Ready to roll !\n";
		} catch (PDOException $e) {
			echo 'Error: ' . $e->getMessage() . '\n';
			die();
		}
	}
}
