<?php

$currentPath = rtrim(parse_url(
	$_SERVER['REQUEST_URI'],
	PHP_URL_PATH
), '/') ?: '/';

$isLoggedIn = isset($_SESSION['user']);
$isAdmin = $isLoggedIn && ($_SESSION['user']['role'] ?? '') === 'admin';

?>

<header class="header">
	<div class="wrapper">
		<nav class="header__nav">
			<div class="header__inner">
				<div class="header__branding">
					<img src="./../../assets/logo.png">
					<a href="/" class="header__logo" aria-label="Мой Не Сам">
						Мой Не Сам
					</a>
					<div class="header__branding-spacer"></div>
				</div>
				<div class="header__toggle-wrapper">
					<button type="button" class="header__toggle-btn" id="header-toggle" aria-expanded="false" aria-controls="header-menu" aria-label="Открыть меню">
						<span class="toggle-btn__bar"></span>
						<span class="toggle-btn__bar"></span>
						<span class="toggle-btn__bar"></span>
					</button>
				</div>
			</div>
			<div class="header__menu" id="header-menu">
				<div class="header__menu-inner">
					<a href="/" class="nav__link <?= $currentPath === '/' ? 'nav__link-active' : '' ?>">Главная</a>

					<?php if ($isLoggedIn): ?>
						<a href="/bids" class="nav__link <?= $currentPath === '/bids' ? 'nav__link-active' : '' ?>">Заявки</a>
						<a href="/bids/create" class="nav__link <?= $currentPath === '/bids/create' ? 'nav__link-active' : '' ?>">Создать заявку</a>
					<?php endif; ?>

					<?php if ($isAdmin): ?>
						<a href="/admin" class="nav__link <?= $currentPath === '/admin' ? 'nav__link-active' : '' ?>">Панель администратора</a>
					<?php endif; ?>

					<?php if (!$isLoggedIn): ?>
						<a href="/login" class="nav__link <?= $currentPath === '/login' ? 'nav__link-active' : '' ?>">Вход</a>
						<a href="/register" class="nav__link <?= $currentPath === '/register' ? 'nav__link-active' : '' ?>">Регистрация</a>
					<?php endif; ?>

					<?php if ($isLoggedIn): ?>
						<form action="/logout" method="POST" class="nav__link-form">
							<button type="submit" class="nav__link" style="color: red;">
								Выход
							</button>
						</form>
					<?php endif; ?>
				</div>
			</div>
		</nav>
	</div>
</header>