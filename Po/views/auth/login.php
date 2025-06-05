<?php

$errors = $_SESSION['flash']['errors'] ?? [];
$old = $_SESSION['flash']['old'] ?? [];
$errorMessage = $_SESSION['flash']['error'] ?? null;

unset(
	$_SESSION['errors'],
	$_SESSION['old'],
	$_SESSION['error'],
	$_SESSION['flash']
);

?>

<main>
	<div class="wrapper">
		<form class="form" action="/login" method="POST">
			<h1>Авторизация</h1>
			<div class="form__group">
				<input type="text" name="login" required minlength="6" maxlength="255" placeholder="Логин" autocomplete="login" value="<?= htmlspecialchars($old['login'] ?? '') ?>" />
				<!-- pattern="^[А-Яа-яЁё]+$"
				title="Только кириллические буквы" -->
				<?php if ($errorMessage): ?>
					<p class="error"><?= htmlspecialchars($errorMessage) ?></p>
				<?php endif; ?>

				<?php if (!empty($errors['login'])): ?>
					<p class="error"><?= htmlspecialchars($errors['login']) ?></p>
				<?php endif; ?>
			</div>
			<div class="form__group">
				<input type="password" name="password" minlength="6" maxlength="255" placeholder="Пароль" autocomplete="current-password" required />
				<!-- pattern="(?=.*[\p{Ll}])(?=.*[\p{Lu}])(?=.*[\W_]).+"
				title="Пароль должен содержать хотя бы одну строчную и одну заглавную букву (русскую или латинскую), а также один специальный символ." -->
				<?php if (!empty($errors['password'])): ?>
					<p class="error"><?= htmlspecialchars($errors['password']) ?></p>
				<?php endif; ?>
			</div>
			<button type="submit" class="button">Войти</button>
		</form>
	</div>
</main>