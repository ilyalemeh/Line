<?php

require_once __DIR__ . '/Controller.php';

class ErrorController extends Controller
{
	public function __construct()
	{
		if (session_status() === PHP_SESSION_NONE) {
			session_start();
		}
	}

	public function show(int $code, string $message)
	{
		http_response_code($code);

		$title = "Ошибка $code";
		$errorMessage = $message;

		$this->view(
			'views/error.php',
			[
				'title' => $title,
				'code' => $code,
				'errorMessage' => $errorMessage,
			],
		);

		exit;
	}
}
