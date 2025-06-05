<?php

class Controller
{
	/**
	 * Проверка авторизации пользователя
	 */
	protected function isAuthenticated(): bool
	{
		return isset($_SESSION['user']);
	}

	/**
	 * Проверка, является ли пользователь администратором
	 */
	protected function isAdmin(): bool
	{
		return $this->isAuthenticated() && ($_SESSION['user']['role'] ?? '') === 'admin';
	}

	/**
	 * Редирект на указанный путь
	 */
	protected function redirect(string $path): void
	{
		header("Location: $path");
		exit;
	}

	/**
	 * Редирект обратно (если нет REFERER — на /)
	 */
	protected function redirectBack(): void
	{
		$this->redirect($_SERVER['HTTP_REFERER'] ?? '/');
	}

	/**
	 * Редирект обратно с ошибкой
	 */
	protected function redirectBackWithError(string $message): void
	{
		$this->flash('error', $message);
		$this->redirectBack();
	}

	/**
	 * Упрощённый вызов View с layout'ом
	 */
	protected function view(
		string $viewPath,
		array $data = [],
		bool $withHeader = true,
		bool $withFooter = true
	): void {
		extract($data);
		$view = $viewPath;
		require 'views/layout.php';
	}

	/**
	 * Установить flash-сообщение (например, для уведомлений)
	 */
	protected function flash(string $key, mixed $value): void
	{
		if (!isset($_SESSION['flash'])) {
			$_SESSION['flash'] = [];
		}

		$_SESSION['flash'][$key] = $value;
	}

	/**
	 * Получить flash-сообщение и удалить
	 */
	protected function getFlash(string $key): mixed
	{
		if (!isset($_SESSION['flash'][$key])) {
			return null;
		}

		$value = $_SESSION['flash'][$key];
		unset($_SESSION['flash'][$key]);
		return $value;
	}
}
