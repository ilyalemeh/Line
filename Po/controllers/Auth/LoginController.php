<?php

namespace Auth;

require_once __DIR__ . '/../Controller.php';
require_once __DIR__ . '/../../database/config.php';

class LoginController extends \Controller
{
	public function __construct()
	{
		if (session_status() === PHP_SESSION_NONE) {
			session_start();
		}

		if ($this->isAuthenticated()) {
			$this->redirect('/');
		}
	}

	public function index()
	{
		$this->view(
			'views/auth/login.php',
			['title' => 'Авторизация'],
		);
	}

	public function store()
	{
		global $pdo;

		$data = [
			'login' => trim($_POST['login'] ?? ''),
			'password' => $_POST['password'] ?? '',
		];

		$errors = $this->validate($data);

		if (!empty($errors)) {
			$this->flash('errors', $errors);
			$this->flash('old', $data);

			$this->redirectBack();
		}

		$stmt = $pdo->prepare("SELECT * FROM users WHERE login = :value");
		$stmt->execute(['value' => $data['login']]);
		$user = $stmt->fetch();

		if ($user && password_verify($data['password'], $user['password'])) {
			unset($user['password']);
			$_SESSION['user'] = $user;

			$this->redirect('/');
		}

		$this->redirectBackWithError('Неверный логин или пароль');
	}

	private function validate(array $data): array
	{
		$errors = [];

		$login = trim($data['login'] ?? '');
		$password = $data['password'] ?? '';

		if ($login === '') {
			$errors['login'] = 'Введите логин.';
		} elseif (!is_string($login)) {
			$errors['login'] = 'Логин должен быть строкой.';
		} elseif (mb_strlen($login) < 6) {
			$errors['login'] = 'Логин должен содержать минимум 6 символов.';
		} elseif (mb_strlen($login) > 255) {
			$errors['login'] = 'Логин не должен превышать 255 символов.';
		}
		// elseif (!preg_match('/^[А-Яа-яЁё]+$/u', $login)) {
		//     $errors['login'] = 'Логин должен содержать только символы кириллицы.';
		// }

		if ($password === '') {
			$errors['password'] = 'Введите пароль.';
		} elseif (!is_string($password)) {
			$errors['password'] = 'Пароль должен быть строкой.';
		} elseif (mb_strlen($password) < 6) {
			$errors['password'] = 'Пароль должен содержать минимум 6 символов.';
		} elseif (mb_strlen($password) > 255) {
			$errors['password'] = 'Пароль не должен превышать 255 символов.';
		}
		// elseif (!preg_match('/^(?=.*[\p{Ll}])(?=.*[\p{Lu}])(?=.*[\W_]).+$/u', $password)) {
		//     $errors['password'] = 'Пароль должен содержать как минимум одну заглавную букву, одну строчную и один специальный символ.';
		// }

		return $errors;
	}
}
