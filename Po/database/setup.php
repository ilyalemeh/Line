<?php

require_once __DIR__ . "/config.php";
require_once __DIR__ . "/functions.php";

function setupDatabase(PDO $pdo): void
{
	$baseQueries = [
		"CREATE TABLE IF NOT EXISTS users (
			id INT AUTO_INCREMENT PRIMARY KEY,
			full_name VARCHAR(255) NOT NULL,
			phone VARCHAR(255) NOT NULL,
			login VARCHAR(255) NOT NULL UNIQUE,
			email VARCHAR(255) NOT NULL UNIQUE,
			password VARCHAR(255) NOT NULL,
			role ENUM('client', 'admin') NOT NULL DEFAULT 'client'
		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

		// Задание: Заявка на уборку
		"CREATE TABLE IF NOT EXISTS bids (
			id INT AUTO_INCREMENT PRIMARY KEY,
			user_id INT NOT NULL,
			status ENUM('pending', 'approved', 'rejected') NOT NULL DEFAULT 'pending',
			phone VARCHAR(255) NOT NULL,
			address VARCHAR(255) NOT NULL,
			receipt_date DATE NOT NULL,
			receipt_time TIME NOT NULL,
			service_type ENUM('general', 'deep_cleaning', 'post_renovation', 'dry_cleaning', 'custom') NOT NULL,
			custom_service TEXT DEFAULT NULL,
			payment_type ENUM('cash', 'card') NOT NULL,
			cancellation_reason TEXT DEFAULT NULL,
			CONSTRAINT fk_bids_user_id FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

		// Задание: Заявка на перевозку груза
		// "CREATE TABLE IF NOT EXISTS bids (
		//     id INT AUTO_INCREMENT PRIMARY KEY,
		//     user_id INT NOT NULL,
		//     status ENUM('pending', 'approved', 'rejected') NOT NULL DEFAULT 'pending',
		//     phone VARCHAR(255) NOT NULL,
		//     weight VARCHAR(255) NOT NULL,
		//     dimensions VARCHAR(255) NOT NULL,
		//     from_address VARCHAR(255) NOT NULL,
		//     to_address VARCHAR(255) NOT NULL,
		//     transport_date DATE NOT NULL,
		//     transport_time TIME NOT NULL,
		//     cargo_type ENUM('fragile', 'perishable', 'refrigerated', 'animals', 'liquid', 'furniture', 'waste', 'custom') NOT NULL,
		//     custom_cargo TEXT DEFAULT NULL,
		//     CONSTRAINT fk_bids_user_id FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
		// ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

		// Задание: Заявка на тест-драйв
		// "CREATE TABLE IF NOT EXISTS bids (
		//     id INT AUTO_INCREMENT PRIMARY KEY,
		//     user_id INT NOT NULL,
		//     status ENUM('pending', 'approved', 'rejected') NOT NULL DEFAULT 'pending',
		//     phone VARCHAR(255) NOT NULL,
		//     address VARCHAR(255) NOT NULL,
		//     desired_date DATE NOT NULL,
		//     desired_time TIME NOT NULL,
		//     license_series VARCHAR(255) NOT NULL,
		//     license_number VARCHAR(255) NOT NULL,
		//     license_issued_at DATE NOT NULL,
		//     car_brand ENUM('Toyota', 'BMW') NOT NULL,
		//     car_model ENUM('Camry', 'Corolla', 'X5', '3 Series') NOT NULL,
		//     payment_type ENUM('cash', 'card') NOT NULL,
		//     cancellation_reason TEXT DEFAULT NULL,
		//     CONSTRAINT fk_bids_user_id FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
		// ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;"
	];

	$extraTables = [
		// 'favorites' => [
		// 	'column' => 'product',
		// 	'table' => 'products'
		// ],
		// 'cart' => [
		// 	'column' => 'product',
		// 	'table' => 'products'
		// ]
	];

	createTables($pdo, $baseQueries, $extraTables);

	addInitialData($pdo, [
		'users' => [
			'columns' => [
				'full_name',
				'phone',
				'login',
				'email',
				'password',
				'role'
			],
			'data' => [
				[
					'Admin Adminov Adminovich',
					'+7(123)-456-78-91',
					'adminka',
					'admin@admin.admin',
					password_hash('password', PASSWORD_DEFAULT),
					'admin'
				],
			]
		],
	]);
}

function clearTables(PDO $pdo): void
{
	$stmt = $pdo->query("SELECT DATABASE()");
	$database = $stmt->fetchColumn();

	if (!$database) {
		die("Не удалось определить имя базы данных.");
	}

	$tablesStmt = $pdo->prepare(
		"SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_SCHEMA = :db"
	);
	$tablesStmt->execute(['db' => $database]);
	$tables = $tablesStmt->fetchAll(PDO::FETCH_COLUMN);

	if (!$tables) return;

	$pdo->exec("SET FOREIGN_KEY_CHECKS = 0");

	foreach ($tables as $table) {
		try {
			$pdo->exec("TRUNCATE TABLE `$table`");
		} catch (PDOException $e) {
			die("Ошибка очистки таблицы `$table`: " . $e->getMessage());
		}
	}

	$pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
}
