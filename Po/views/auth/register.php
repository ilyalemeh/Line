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
		<form class="form" action="/register" method="POST">
			<h1>Регистрация</h1>
			<div class="form__group">
				<input type="text" name="full_name" pattern="^[А-ЯЁ][а-яё]+ [А-ЯЁ][а-яё]+( [А-ЯЁ][а-яё]+)?$" title="ФИО должно быть в формате: Имя Фамилия Отчество (отчество опционально, только кириллица)" maxlength="255" value="<?= htmlspecialchars($old['full_name'] ?? '') ?>" placeholder="ФИО" required />
				<?php if (!empty($errors['full_name'])): ?>
					<p class="error"><?= htmlspecialchars($errors['full_name']) ?></p>
				<?php endif; ?>
			</div>
			<div class="form__group">
				<input type="text" name="phone" pattern="^\+7\(\d{3}\)-\d{3}-\d{2}-\d{2}$" title="Телефон должен быть в формате: +7(XXX)-XXX-XX-XX" value="<?= htmlspecialchars($old['phone'] ?? '') ?>" placeholder="Телефон (+7XXX-XXX-XX-XX)" required />
				<?php if (!empty($errors['phone'])): ?>
					<p class="error"><?= htmlspecialchars($errors['phone']) ?></p>
				<?php endif; ?>
			</div>
			<div class="form__group">
				<input type="text" name="login" minlength="6" maxlength="255" value="<?= htmlspecialchars($old['login'] ?? '') ?>" placeholder="Логин" autocomplete="login" required />
				<!-- pattern="^[А-Яа-яЁё]+$"
				title="Только кириллические буквы" -->
				<?php if (!empty($errors['login'])): ?>
					<p class="error"><?= htmlspecialchars($errors['login']) ?></p>
				<?php endif; ?>
			</div>
			<div class="form__group">
				<input type="email" name="email" maxlength="255" value="<?= htmlspecialchars($old['email'] ?? '') ?>" placeholder="Электронная почта" required />
				<?php if (!empty($errors['email'])): ?>
					<p class="error"><?= htmlspecialchars($errors['email']) ?></p>
				<?php endif; ?>
			</div>
			<div class="form__group">
				<input type="password" name="password" minlength="6" maxlength="255" placeholder="Пароль" autocomplete="new-password" required />
				<!-- pattern="(?=.*[\p{Ll}])(?=.*[\p{Lu}])(?=.*[\W_]).+"
				title="Пароль должен содержать хотя бы одну строчную и одну заглавную букву (русскую или латинскую), а также один специальный символ." -->
				<?php if (!empty($errors['password'])): ?>
					<p class="error"><?= htmlspecialchars($errors['password']) ?></p>
				<?php endif; ?>
			</div>
			<div class="form__group">
				<input type="password" name="password_confirm" minlength="6" maxlength="255" placeholder="Подтвердите пароль" autocomplete="new-password" required />
				<!-- pattern="(?=.*[\p{Ll}])(?=.*[\p{Lu}])(?=.*[\W_]).+"
				title="Пароль должен содержать хотя бы одну строчную и одну заглавную букву (русскую или латинскую), а также один специальный символ." -->
				<?php if (!empty($errors['password_confirm'])): ?>
					<p class="error"><?= htmlspecialchars($errors['password_confirm']) ?></p>
				<?php endif; ?>
			</div>
			<button type="submit" class="button">Зарегистрироваться</button>
		</form>
	</div>
</main>