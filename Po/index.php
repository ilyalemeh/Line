<?php

require_once __DIR__ . "/routes.php";
require_once __DIR__ . "/database/setup.php";

setupDatabase($pdo);
// clearTables($pdo);

function renderErrorPage(int $code, string $message)
{
	require_once __DIR__ . "/controllers/ErrorController.php";

	$errorController = new ErrorController();
	$errorController->show($code, $message);
}

$uri = rtrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/') ?: '/';
$method = $_SERVER['REQUEST_METHOD'];

$matched = false;

if (isset($routes[$method])) {
	foreach ($routes[$method] as $routePattern => $handler) {
		$regex = preg_replace('#\{[a-zA-Z_]+\}#', '([^/]+)', $routePattern);
		$regex = "#^{$regex}$#";

		if (preg_match($regex, $uri, $matches)) {
			array_shift($matches);

			[$controller, $action] = explode('@', $handler);

			$controllerPath = str_replace(
				'\\',
				DIRECTORY_SEPARATOR,
				$controller
			);

			require_once __DIR__ . "/controllers/{$controllerPath}.php";

			try {
				$controllerInstance = new $controller();
				$controllerInstance->$action(...$matches);
			} catch (Throwable $e) {
				renderErrorPage(
					500,
					"Внутренняя ошибка сервера: " . $e->getMessage()
				);
			}

			$matched = true;

			break;
		}
	}
}

if (!$matched) {
	renderErrorPage(404, "Страница не найдена");
}
