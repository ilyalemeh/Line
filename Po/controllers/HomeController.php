<?php

require_once __DIR__ . '/Controller.php';

class HomeController extends Controller
{
	public function __construct()
	{
		if (session_status() === PHP_SESSION_NONE) {
			session_start();
		}
	}

	public function index()
	{
		$title = 'Главная';

		$this->view(
			'views/home.php',
			['title' => $title]
		);
	}
}
