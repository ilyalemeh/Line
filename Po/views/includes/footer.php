<?php

$currentPath = rtrim(parse_url(
	$_SERVER['REQUEST_URI'],
	PHP_URL_PATH
), '/') ?: '/';

$isLoggedIn = isset($_SESSION['user']);
$isAdmin = $isLoggedIn && ($_SESSION['user']['role'] ?? '') === 'admin';

?>

<footer class="footer">
	<div class="wrapper">
		<div class="footer__inner">
			<div class="footer__branding">
				<a href="/" class="footer__logo" aria-label="Мой Не Сам">
					Мой Не Сам
				</a>
				<p>
					Lorem ipsum dolor sit amet consectetur adipisicing elit. Delectus nemo odio suscipit laudantium, dolore voluptates eveniet labore cum sit eum molestias!
				</p>
			</div>
			<nav class="footer__nav">
				<h4>Ссылки</h4>
				<a href="/" class="footer__link">Главная</a>

				<?php if ($isLoggedIn): ?>
					<a href="/bids" class="footer__link">Заявки</a>
					<a href="/bids/create" class="footer__link">Создать заявку</a>
				<?php endif; ?>

				<?php if ($isAdmin): ?>
					<a href="/admin" class="footer__link">Панель администратора</a>
				<?php endif; ?>
			</nav>
			<nav class="footer__nav">
				<h4>Действия</h4>
				<?php if (!$isLoggedIn): ?>
					<a href="/login" class="footer__link">Вход</a>
					<a href="/register" class="footer__link">Регистрация</a>
				<?php endif; ?>

				<?php if ($isLoggedIn): ?>
					<form action="/logout" method="POST" class="footer__form">
						<button type="submit" class="footer__link">Выход</button>
					</form>
				<?php endif; ?>
			</nav>
		</div>
	</div>
</footer>