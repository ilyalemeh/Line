<main>
	<div class="wrapper">
		<h1>Мои заявки</h1>
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
									<td><?= htmlspecialchars($bid['id']) ?></td>
									<td><?= htmlspecialchars($bid['status']) ?></td>
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
									<td><?= htmlspecialchars($bid['id']) ?></td>
									<td><?= htmlspecialchars($bid['status']) ?></td>
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
									<td><?= htmlspecialchars($bid['id']) ?></td>
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