<?php

namespace Auth;

require_once __DIR__ . '/../Controller.php';
require_once __DIR__ . '/../../database/config.php';

class RegisterController extends \Controller
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
			'views/auth/register.php',
			['title' => 'Регистрация'],
		);
	}

	public function store()
	{
		global $pdo;

		$data = [
			'full_name' => trim($_POST['full_name'] ?? ''),
			'phone' => trim($_POST['phone'] ?? ''),
			'login' => trim($_POST['login'] ?? ''),
			'email' => trim($_POST['email'] ?? ''),
			'password' => $_POST['password'] ?? '',
			'password_confirm' => $_POST['password_confirm'] ?? '',
		];

		$errors = $this->validate($data);

		if (!empty($errors)) {
			$this->flash('errors', $errors);
			$this->flash('old', $data);

			$this->redirectBack();
		}

		$stmt = $pdo->prepare(
			"SELECT COUNT(*) FROM users WHERE login = :login"
		);

		$stmt->execute(['login' => $data['login']]);
		$count = $stmt->fetchColumn();

		if ($count > 0) {
			$this->flash('errors', ['login' => 'Логин уже занят']);
			$this->flash('old', $data);

			$this->redirectBack();
		}

		$hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);

		$stmt = $pdo->prepare(
			"INSERT INTO users (full_name, phone, login, email, password)
		 	VALUES (:full_name, :phone, :login, :email, :password)"
		);

		$stmt->execute([
			'full_name' => $data['full_name'],
			'phone' => $data['phone'],
			'login' => $data['login'],
			'email' => $data['email'],
			'password' => $hashedPassword,
		]);

		$userId = $pdo->lastInsertId();

		$stmt = $pdo->prepare(
			"SELECT id, full_name, phone, login, email FROM users WHERE id = :id"
		);

		$stmt->execute(['id' => $userId]);
		$user = $stmt->fetch();

		$_SESSION['user'] = $user;

		$this->redirect('/');
	}

	private function validate(array $data): array
	{
		$errors = [];

		$fullName = trim($data['full_name'] ?? '');

		if ($fullName === '') {
			$errors['full_name'] = 'Введите ФИО.';
		} elseif (!is_string($fullName)) {
			$errors['full_name'] = 'ФИО должно быть строкой.';
		} elseif (mb_strlen($fullName) > 255) {
			$errors['full_name'] = 'ФИО не должно превышать 255 символов.';
		} elseif (!preg_match('/^[А-ЯЁ][а-яё]+ [А-ЯЁ][а-яё]+( [А-ЯЁ][а-яё]+)?$/u', $fullName)) {
			$errors['full_name'] = 'ФИО должно быть в формате: Имя Фамилия Отчество (отчество опционально, только кириллица).';
		}

		$phone = trim($data['phone'] ?? '');

		if ($phone === '') {
			$errors['phone'] = 'Введите номер телефона.';
		} elseif (!is_string($phone)) {
			$errors['phone'] = 'Телефон должен быть строкой.';
		} elseif (!preg_match('/^\+7\(\d{3}\)-\d{3}-\d{2}-\d{2}$/', $phone)) {
			$errors['phone'] = 'Телефон должен быть в формате +7(XXX)-XXX-XX-XX.';
		}

		$login = trim($data['login'] ?? '');

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
		// 	$errors['login'] = 'Логин должен содержать только символы кириллицы.';
		// }

		$email = trim($data['email'] ?? '');

		if ($email === '') {
			$errors['email'] = 'Введите почту.';
		} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$errors['email'] = 'Некорректный формат почты.';
		} elseif (mb_strlen($email) > 255) {
			$errors['email'] = 'Почта не должна превышать 255 символов.';
		}

		$password = $data['password'] ?? '';
		$passwordConfirm = $data['password_confirm'] ?? '';

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
		// 	$errors['password'] = 'Пароль должен содержать как минимум одну заглавную букву, одну строчную и один специальный символ.';
		// }

		if ($password !== $passwordConfirm) {
			$errors['password_confirm'] = 'Пароли не совпадают.';
		}

		return $errors;
	}
}
