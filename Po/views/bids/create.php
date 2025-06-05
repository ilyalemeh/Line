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
		<form class="form" action="/bids" method="POST">
			<h1>Оформление заявки</h1>
			<div class="form__group">
				<input type="text" name="phone" pattern="^\+7\(\d{3}\)-\d{3}-\d{2}-\d{2}$" title="Телефон должен быть в формате: +7(XXX)-XXX-XX-XX" value="<?= htmlspecialchars($old['phone'] ?? '') ?>" placeholder="Телефон (+7XXX-XXX-XX-XX)" required />
				<?php if (!empty($errors['phone'])): ?>
					<p class="error"><?= htmlspecialchars($errors['phone']) ?></p>
				<?php endif; ?>
			</div>

			<!-- Задание: Заявка на уборку -->
			<div class="form__group">
				<input type="text" name="address" maxlength="255" value="<?= htmlspecialchars($old['address'] ?? '') ?>" placeholder="Адрес" required />
				<?php if (!empty($errors['address'])): ?>
					<p class="error"><?= htmlspecialchars($errors['address']) ?></p>
				<?php endif; ?>
			</div>
			<div class="form__group">
				<input type="date" name="receipt_date" value="<?= htmlspecialchars($old['receipt_date'] ?? '') ?>" required />
				<?php if (!empty($errors['receipt_date'])): ?>
					<p class="error"><?= htmlspecialchars($errors['receipt_date']) ?></p>
				<?php endif; ?>
			</div>
			<div class="form__group">
				<input type="time" name="receipt_time" value="<?= htmlspecialchars($old['receipt_time'] ?? '') ?>" required />
				<?php if (!empty($errors['receipt_time'])): ?>
					<p class="error"><?= htmlspecialchars($errors['receipt_time']) ?></p>
				<?php endif; ?>
			</div>
			<div class="form__group">
				<select name="service_type" required>
					<option value="">Выберите тип услуги</option>
					<option value="general" <?= (isset($old['service_type']) && $old['service_type'] === 'general') ? 'selected' : '' ?>>Общее</option>
					<option value="deep_cleaning" <?= (isset($old['service_type']) && $old['service_type'] === 'deep_cleaning') ? 'selected' : '' ?>>Глубокая уборка</option>
					<option value="post_renovation" <?= (isset($old['service_type']) && $old['service_type'] === 'post_renovation') ? 'selected' : '' ?>>После ремонта</option>
					<option value="dry_cleaning" <?= (isset($old['service_type']) && $old['service_type'] === 'dry_cleaning') ? 'selected' : '' ?>>Сухая уборка</option>
					<option value="custom" <?= (isset($old['service_type']) && $old['service_type'] === 'custom') ? 'selected' : '' ?>>Иная услуга</option>
				</select>
				<?php if (!empty($errors['service_type'])): ?>
					<p class="error"><?= htmlspecialchars($errors['service_type']) ?></p>
				<?php endif; ?>
			</div>
			<div class="form__group">
				<input type="text" name="custom_service" maxlength="255" value="<?= htmlspecialchars($old['custom_service'] ?? '') ?>" placeholder="Опишите услугу, если выбрали 'Иная услуга'" />
				<?php if (!empty($errors['custom_service'])): ?>
					<p class="error"><?= htmlspecialchars($errors['custom_service']) ?></p>
				<?php endif; ?>
			</div>

			<!-- Задание: Заявка на перевозку груза -->
			<!-- <div class="form__group">
				<input type="number" name="weight" min="1" step="1" value="<?= htmlspecialchars($old['weight'] ?? '') ?>" placeholder="Вес груза в килограммах" required />
				<?php if (!empty($errors['weight'])): ?>
					<p class="error"><?= htmlspecialchars($errors['weight']) ?></p>
				<?php endif; ?>
			</div>
			<div class="form__group">
				<input type="text" name="dimensions" pattern="^\d+x\d+x\d+$" title="Формат: ДлинаxШиринаxВысота, например 120x80x60" value="<?= htmlspecialchars($old['dimensions'] ?? '') ?>" placeholder="Габариты груза (ДxШxВ)" required />
				<?php if (!empty($errors['dimensions'])): ?>
					<p class="error"><?= htmlspecialchars($errors['dimensions']) ?></p>
				<?php endif; ?>
			</div>
			<div class="form__group">
				<input type="text" name="from_address" maxlength="255" value="<?= htmlspecialchars($old['from_address'] ?? '') ?>" placeholder="Адрес отправления" required />
				<?php if (!empty($errors['from_address'])): ?>
					<p class="error"><?= htmlspecialchars($errors['from_address']) ?></p>
				<?php endif; ?>
			</div>
			<div class="form__group">
				<input type="text" name="to_address" maxlength="255" value="<?= htmlspecialchars($old['to_address'] ?? '') ?>" placeholder="Адрес назначения" required />
				<?php if (!empty($errors['to_address'])): ?>
					<p class="error"><?= htmlspecialchars($errors['to_address']) ?></p>
				<?php endif; ?>
			</div>
			<div class="form__group">
				<input type="date" name="transport_date" value="<?= htmlspecialchars($old['transport_date'] ?? '') ?>" required />
				<?php if (!empty($errors['transport_date'])): ?>
					<p class="error"><?= htmlspecialchars($errors['transport_date']) ?></p>
				<?php endif; ?>
			</div>
			<div class="form__group">
				<input type="time" name="transport_time" value="<?= htmlspecialchars($old['transport_time'] ?? '') ?>" required />
				<?php if (!empty($errors['transport_time'])): ?>
					<p class="error"><?= htmlspecialchars($errors['transport_time']) ?></p>
				<?php endif; ?>
			</div>
			<div class="form__group">
				<select name="cargo_type" required>
					<option value="">Выберите тип груза</option>
					<option value="fragile" <?= (isset($old['cargo_type']) && $old['cargo_type'] === 'fragile') ? 'selected' : '' ?>>Хрупкий</option>
					<option value="perishable" <?= (isset($old['cargo_type']) && $old['cargo_type'] === 'perishable') ? 'selected' : '' ?>>Скоропортящийся</option>
					<option value="refrigerated" <?= (isset($old['cargo_type']) && $old['cargo_type'] === 'refrigerated') ? 'selected' : '' ?>>Рефрижераторный</option>
					<option value="animals" <?= (isset($old['cargo_type']) && $old['cargo_type'] === 'animals') ? 'selected' : '' ?>>Животные</option>
					<option value="liquid" <?= (isset($old['cargo_type']) && $old['cargo_type'] === 'liquid') ? 'selected' : '' ?>>Жидкость</option>
					<option value="furniture" <?= (isset($old['cargo_type']) && $old['cargo_type'] === 'furniture') ? 'selected' : '' ?>>Мебель</option>
					<option value="waste" <?= (isset($old['cargo_type']) && $old['cargo_type'] === 'waste') ? 'selected' : '' ?>>Отходы</option>
					<option value="custom" <?= (isset($old['cargo_type']) && $old['cargo_type'] === 'custom') ? 'selected' : '' ?>>Иной груз</option>
				</select>
				<?php if (!empty($errors['cargo_type'])): ?>
					<p class="error"><?= htmlspecialchars($errors['cargo_type']) ?></p>
				<?php endif; ?>
			</div>
			<div class="form__group">
				<input type="text" name="custom_cargo" maxlength="255" value="<?= htmlspecialchars($old['custom_cargo'] ?? '') ?>" placeholder="Опишите груз, если выбрали 'Иной груз'" />
				<?php if (!empty($errors['custom_cargo'])): ?>
					<p class="error"><?= htmlspecialchars($errors['custom_cargo']) ?></p>
				<?php endif; ?>
			</div> -->

			<!-- Задание: Заявка на тест-драйв -->
			<!-- <div class="form__group">
				<input type="text" name="address" maxlength="255" value="<?= htmlspecialchars($old['address'] ?? '') ?>" placeholder="Адрес" required />
				<?php if (!empty($errors['address'])): ?>
					<p class="error"><?= htmlspecialchars($errors['address']) ?></p>
				<?php endif; ?>
			</div>
			<div class="form__group">
				<input type="date" name="desired_date" value="<?= htmlspecialchars($old['desired_date'] ?? '') ?>" required />
				<?php if (!empty($errors['desired_date'])): ?>
					<p class="error"><?= htmlspecialchars($errors['desired_date']) ?></p>
				<?php endif; ?>
			</div>
			<div class="form__group">
				<input type="time" name="desired_time" value="<?= htmlspecialchars($old['desired_time'] ?? '') ?>" required />
				<?php if (!empty($errors['desired_time'])): ?>
					<p class="error"><?= htmlspecialchars($errors['desired_time']) ?></p>
				<?php endif; ?>
			</div>
			<div class="form__group">
				<input type="text" name="license_series" maxlength="10" value="<?= htmlspecialchars($old['license_series'] ?? '') ?>" placeholder="Серия водительского удостоверения" required />
				<?php if (!empty($errors['license_series'])): ?>
					<p class="error"><?= htmlspecialchars($errors['license_series']) ?></p>
				<?php endif; ?>
			</div>
			<div class="form__group">
				<input type="text" name="license_number" maxlength="10" value="<?= htmlspecialchars($old['license_number'] ?? '') ?>" placeholder="Номер водительского удостоверения" required />
				<?php if (!empty($errors['license_number'])): ?>
					<p class="error"><?= htmlspecialchars($errors['license_number']) ?></p>
				<?php endif; ?>
			</div>
			<div class="form__group">
				<input type="date" name="license_issued_at" maxlength="255" value="<?= htmlspecialchars($old['license_issued_at'] ?? '') ?>" placeholder="Кем выдано удостоверение" required />
				<?php if (!empty($errors['license_issued_at'])): ?>
					<p class="error"><?= htmlspecialchars($errors['license_issued_at']) ?></p>
				<?php endif; ?>
			</div>
			<div class="form__group">
				<select name="car_brand" required>
					<option value="">Выберите марку автомобиля</option>
					<option value="Toyota" <?= (isset($old['car_brand']) && $old['car_brand'] === 'Toyota') ? 'selected' : '' ?>>Toyota</option>
					<option value="BMW" <?= (isset($old['car_brand']) && $old['car_brand'] === 'BMW') ? 'selected' : '' ?>>BMW</option>
				</select>
				<?php if (!empty($errors['car_brand'])): ?>
					<p class="error"><?= htmlspecialchars($errors['car_brand']) ?></p>
				<?php endif; ?>
			</div>
			<div class="form__group">
				<select name="car_model" required>
					<option value="">Выберите модель автомобиля</option>
					<option value="Camry" <?= (isset($old['car_model']) && $old['car_model'] === 'Camry') ? 'selected' : '' ?>>Camry</option>
					<option value="Corolla" <?= (isset($old['car_model']) && $old['car_model'] === 'Corolla') ? 'selected' : '' ?>>Corolla</option>
					<option value="X5" <?= (isset($old['car_model']) && $old['car_model'] === 'X5') ? 'selected' : '' ?>>X5</option>
					<option value="3 Series" <?= (isset($old['car_model']) && $old['car_model'] === '3 Series') ? 'selected' : '' ?>>3 Series</option>
				</select>
				<?php if (!empty($errors['car_model'])): ?>
					<p class="error"><?= htmlspecialchars($errors['car_model']) ?></p>
				<?php endif; ?>
			</div> -->

			<!-- Задание: Заявка на уборку / тест-драйв -->
			<div class="form__group">
				<select name="payment_type" required>
					<option value="">Выберите тип оплаты</option>
					<option value="cash" <?= (isset($old['payment_type']) && $old['payment_type'] === 'cash') ? 'selected' : '' ?>>Наличными</option>
					<option value="card" <?= (isset($old['payment_type']) && $old['payment_type'] === 'card') ? 'selected' : '' ?>>Картой</option>
				</select>
				<?php if (!empty($errors['payment_type'])): ?>
					<p class="error"><?= htmlspecialchars($errors['payment_type']) ?></p>
				<?php endif; ?>
			</div>
			<button type="submit" class="button">Оформить заявку</button>
		</form>
	</div>
</main>