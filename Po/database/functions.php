<?php

/**
 * Проверяет, существует ли таблица в базе данных
 */
function tableExists(PDO $pdo, string $tableName): bool
{
	$result = $pdo->query(
		"SHOW TABLES LIKE " . $pdo->quote($tableName)
	);

	return $result && $result->rowCount() > 0;
}

/**
 * Проверяет, пустая ли таблица
 */
function isTableEmpty(PDO $pdo, string $tableName): bool
{
	$stmt = $pdo->query(
		"SELECT COUNT(*) FROM `$tableName`"
	);

	return $stmt->fetchColumn() == 0;
}

/**
 * Выполняет SQL-запросы на создание таблиц.
 * Также создаёт вспомогательные таблицы с колонками user_id и {column}_id,
 * с внешними ключами на соответствующие таблицы.
 *
 * @param PDO $pdo Подключение к базе данных
 * @param array $baseQueries Массив SQL-запросов CREATE TABLE
 * @param array $extraTables Ассоциативный массив:
 *        название таблицы => ['column' => 'название_сущности', 'table' => 'название_таблицы_сущности']
 */
function createTables(
	PDO $pdo,
	array $baseQueries,
	array $extraTables = []
): void {
	$queries = $baseQueries;

	foreach ($extraTables as $table => $config) {
		if (!preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $table)) {
			die("Недопустимое имя таблицы: $table");
		}

		if (
			!is_array($config) ||
			!isset($config['column'], $config['table']) ||
			!preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $config['column']) ||
			!preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $config['table'])
		) {
			die("Неверная конфигурация для таблицы: $table");
		}

		$column = $config['column'];
		$refTable = $config['table'];
		$columnId = "{$column}_id";

		$queries[] = "CREATE TABLE IF NOT EXISTS `$table` (
			id INT AUTO_INCREMENT PRIMARY KEY,
			user_id INT NOT NULL,
			`$columnId` INT NOT NULL,
			UNIQUE KEY unique_user_item (user_id, `$columnId`),
			FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
			FOREIGN KEY (`$columnId`) REFERENCES `$refTable`(id) ON DELETE CASCADE
		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
	}

	foreach ($queries as $query) {
		try {
			$pdo->exec($query);
		} catch (PDOException $e) {
			die("Ошибка создания таблицы: " . $e->getMessage());
		}
	}
}

/**
 * Добавляет начальные данные в несколько таблиц,
 * если они существуют и пусты.
 *
 * @param PDO $pdo Подключение к базе данных
 * @param array $dataSet Ассоциативный массив:
 *        имя_таблицы => [
 *            'columns' => [...],
 *            'data' => [[...], [...]]
 *        ]
 */
function addInitialData(PDO $pdo, array $dataSet): void
{
	foreach ($dataSet as $tableName => $config) {
		if (
			!isset($config['columns'], $config['data']) ||
			!is_array($config['columns']) ||
			!is_array($config['data'])
		) continue;

		if (!tableExists($pdo, $tableName)) continue;
		if (!isTableEmpty($pdo, $tableName)) continue;

		$columns = $config['columns'];
		$data = $config['data'];

		$placeholders = implode(', ', array_fill(0, count($columns), '?'));
		$columnsList = implode(', ', $columns);
		$insertQuery = "INSERT INTO `$tableName` ($columnsList) VALUES ($placeholders)";

		$stmt = $pdo->prepare($insertQuery);

		try {
			$pdo->beginTransaction();

			foreach ($data as $row) {
				$stmt->execute($row);
			}

			$pdo->commit();
		} catch (PDOException $e) {
			$pdo->rollBack();
			die("Ошибка при вставке в '$tableName': " . $e->getMessage());
		}
	}
}
