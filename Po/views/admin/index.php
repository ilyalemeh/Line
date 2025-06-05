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
		<h1>Все заявки</h1>
		<div class="bids">
			<?php if (empty($bids)): ?>
				<p class="bids__empty">У вас нет заявок.</p>
			<?php else: ?>
				<div class="bids__table-wrapper">
					<!-- Задание: Заявка на уборку -->
					<table class="bids__table">
						<thead>
							<tr>
								<th>ID</th>
								<th>Пользователь</th>
								<th>Статус</th>
								<th>Телефон</th>
								<th>Адрес</th>
								<th>Дата получения</th>
								<th>Время получения</th>
								<th>Тип услуги</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($bids as $bid): ?>
								<tr>
									<td><?= htmlspecialchars($bid['bid_id']) ?></td>
									<td><?= htmlspecialchars($bid['full_name']) ?></td>
									<td>
										<form action="/bids/update" class="admin__form" method="POST">
											<input type="hidden" name="bidId" value="<?= htmlspecialchars($bid['bid_id']) ?>">
											<select name="status" required>
												<option value="pending" <?= $bid['status'] === 'pending' ? 'selected' : '' ?>>В работе</option>
												<option value="approved" <?= $bid['status'] === 'approved' ? 'selected' : '' ?>>Выполнено</option>
												<option value="rejected" <?= $bid['status'] === 'rejected' ? 'selected' : '' ?>>Отменено</option>
											</select>
											<input type="text" name="cancellation_reason" placeholder="Причина отмены" value="<?= htmlspecialchars($bid['cancellation_reason']) ?>" <?= $bid['status'] !== 'rejected' ? 'style="display:none;"' : '' ?>>
											<button type="submit" class="button">
												Сменить статус
											</button>
										</form>
									</td>
									<td><?= htmlspecialchars($bid['phone']) ?></td>
									<td><?= htmlspecialchars($bid['address']) ?></td>
									<td><?= htmlspecialchars($bid['receipt_date']) ?></td>
									<td><?= htmlspecialchars($bid['receipt_time']) ?></td>
									<td><?= htmlspecialchars($bid['service_type']) ?></td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>

					<!-- Задание: Заявка на перевозку груза -->
					<!-- <table class="bids__table">
						<thead>
							<tr>
								<th>ID</th>
								<th>Статус</th>
								<th>Телефон</th>
								<th>Откуда</th>
								<th>Куда</th>
								<th>Дата перевозки</th>
								<th>Время перевозки</th>
								<th>Тип груза</th>
								<th>Вес</th>
								<th>Размеры</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($bids as $bid): ?>
								<tr>
									<td><?= htmlspecialchars($bid['bid_id']) ?></td>
									<td>
										<form action="/bids/update" class="admin__form" method="POST">
											<input type="hidden" name="bidId" value="<?= htmlspecialchars($bid['bid_id']) ?>">
											<select name="status" required>
												<option value="pending" <?= $bid['status'] === 'pending' ? 'selected' : '' ?>>В работе</option>
												<option value="approved" <?= $bid['status'] === 'approved' ? 'selected' : '' ?>>Выполнено</option>
												<option value="rejected" <?= $bid['status'] === 'rejected' ? 'selected' : '' ?>>Отменено</option>
											</select>
											<input type="text" name="custom_cargo" placeholder="Причина отмены" value="<?= htmlspecialchars($bid['custom_cargo']) ?>" <?= $bid['status'] !== 'rejected' ? 'style="display:none;"' : '' ?>>
											<button type="submit" class="button">
												Сменить статус
											</button>
										</form>
									</td>
									<td><?= htmlspecialchars($bid['phone']) ?></td>
									<td><?= htmlspecialchars($bid['from_address']) ?></td>
									<td><?= htmlspecialchars($bid['to_address']) ?></td>
									<td><?= htmlspecialchars($bid['transport_date']) ?></td>
									<td><?= htmlspecialchars($bid['transport_time']) ?></td>
									<td><?= htmlspecialchars($bid['cargo_type']) ?></td>
									<td><?= htmlspecialchars($bid['weight']) ?></td>
									<td><?= htmlspecialchars($bid['dimensions']) ?></td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table> -->

					<!-- Задание: Заявка на тест-драйв -->
					<!-- <table class="bids__table">
						<thead>
							<tr>
								<th>ID</th>
								<th>Статус</th>
								<th>Телефон</th>
								<th>Адрес</th>
								<th>Дата тест-драйва</th>
								<th>Время тест-драйва</th>
								<th>Марка автомобиля</th>
								<th>Модель автомобиля</th>
								<th>Тип оплаты</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($bids as $bid): ?>
								<tr>
									<td><?= htmlspecialchars($bid['bid_id']) ?></td>
									<td><?= htmlspecialchars($bid['status']) ?></td>
									<td><?= htmlspecialchars($bid['phone']) ?></td>
									<td><?= htmlspecialchars($bid['address']) ?></td>
									<td><?= htmlspecialchars($bid['desired_date']) ?></td>
									<td><?= htmlspecialchars($bid['desired_time']) ?></td>
									<td><?= htmlspecialchars($bid['car_brand']) ?></td>
									<td><?= htmlspecialchars($bid['car_model']) ?></td>
									<td><?= htmlspecialchars($bid['payment_type']) ?></td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table> -->
				</div>
			<?php endif; ?>
		</div>
	</div>
</main>