<?php

$host = 'localhost';
$dbname = 'demo';
$username = 'root';
$password = 'root';

$charset = 'utf8mb4';

$dsnWithoutDb = "mysql:host=$host;charset=$charset";
$dsnWithDb = "mysql:host=$host;dbname=$dbname;charset=$charset";

$options = [
	PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
	PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
	PDO::ATTR_EMULATE_PREPARES => false,
];

try {
	$pdo = new PDO($dsnWithoutDb, $username, $password, $options);

	$stmt = $pdo->query("SHOW DATABASES LIKE " . $pdo->quote($dbname));
	$dbExists = $stmt->fetch();

	if (!$dbExists) {
		$pdo->exec(
			"CREATE DATABASE `$dbname` CHARACTER SET $charset COLLATE utf8mb4_unicode_ci"
		);
	}

	$pdo = new PDO($dsnWithDb, $username, $password, $options);
} catch (PDOException $e) {
	die("Ошибка подключения к базе данных: " . $e->getMessage());
}
