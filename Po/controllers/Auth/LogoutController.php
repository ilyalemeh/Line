<?php

namespace Auth;

require_once __DIR__ . '/../Controller.php';

class LogoutController extends \Controller
{
	public function store()
	{
		if (session_status() === PHP_SESSION_NONE) {
			session_start();
		}

		session_unset();
		session_destroy();

		$this->redirect('/');
	}
}
