<?php

require_once __DIR__ . '/Controller.php';

class BidController extends Controller
{
	protected $pdo;

	public function __construct()
	{
		if (session_status() === PHP_SESSION_NONE) {
			session_start();
		}

		if (!$this->isAuthenticated()) {
			$this->redirect('/login');
		}

		global $pdo;
		$this->pdo = $pdo;
	}

	public function index(): void
	{
		$userId = $_SESSION['user']['id'];

		$stmt = $this->pdo->prepare(
			"SELECT * FROM bids WHERE user_id = :user_id ORDER BY id DESC"
		);

		$stmt->execute(['user_id' => $userId]);
		$bids = $stmt->fetchAll(\PDO::FETCH_ASSOC);

		$this->view(
			'views/bids/index.php',
			[
				'title' => 'Мои заявки',
				'bids' => $bids,
			]
		);
	}

	public function create(): void
	{
		$this->view(
			'views/bids/create.php',
			['title' => 'Создать заявку']
		);
	}

	public function store(): void
	{
		$userId = $_SESSION['user']['id'];

		// Задание: Заявка на уборку
		$data = [
			'phone' => trim($_POST['phone'] ?? ''),
			'address' => trim($_POST['address'] ?? ''),
			'receipt_date' => trim($_POST['receipt_date'] ?? ''),
			'receipt_time' => trim($_POST['receipt_time'] ?? ''),
			'service_type' => trim($_POST['service_type'] ?? ''),
			'custom_service' => trim($_POST['custom_service'] ?? null),
			'payment_type' => trim($_POST['payment_type'] ?? ''),
		];

		$errors = $this->validateStoreRequest($data);

		if (!empty($errors)) {
			$this->flash('errors', $errors);
			$this->flash('old', $data);
			$this->redirectBack();
		}

		$stmt = $this->pdo->prepare("
            INSERT INTO bids (
                user_id, status, phone, address, receipt_date, receipt_time,
                service_type, custom_service, payment_type, cancellation_reason
            ) VALUES (
                :user_id, 'pending', :phone, :address, :receipt_date, :receipt_time,
                :service_type, :custom_service, :payment_type, NULL
            )
        ");

		$stmt->execute([
			'user_id' => $userId,
			'phone' => $data['phone'],
			'address' => $data['address'],
			'receipt_date' => $data['receipt_date'],
			'receipt_time' => $data['receipt_time'],
			'service_type' => $data['service_type'],
			'custom_service' => $data['custom_service'],
			'payment_type' => $data['payment_type'],
		]);

		// Задание: Заявка на перевозку груза
		/*
        $data = [
            'phone' => trim($_POST['phone'] ?? ''),
            'weight' => trim($_POST['weight'] ?? ''),
            'dimensions' => trim($_POST['dimensions'] ?? ''),
            'from_address' => trim($_POST['from_address'] ?? ''),
            'to_address' => trim($_POST['to_address'] ?? ''),
            'transport_date' => trim($_POST['transport_date'] ?? ''),
            'transport_time' => trim($_POST['transport_time'] ?? ''),
            'cargo_type' => trim($_POST['cargo_type'] ?? ''),
            'custom_cargo' => trim($_POST['custom_cargo'] ?? null),
        ];

        $errors = $this->validateStoreRequest($data);

        if (!empty($errors)) {
            $this->flash('errors', $errors);
            $this->flash('old', $data);
            $this->redirectBack();
        }

        $stmt = $this->pdo->prepare("
            INSERT INTO bids (
                user_id, status, phone, weight, dimensions, from_address, to_address,
                transport_date, transport_time, cargo_type, custom_cargo, cancellation_reason
            ) VALUES (
                :user_id, 'pending', :phone, :weight, :dimensions, :from_address, :to_address,
                :transport_date, :transport_time, :cargo_type, :custom_cargo, NULL
            )
        ");

        $stmt->execute([
            'user_id' => $userId,
            'phone' => $data['phone'],
            'weight' => $data['weight'],
            'dimensions' => $data['dimensions'],
            'from_address' => $data['from_address'],
            'to_address' => $data['to_address'],
            'transport_date' => $data['transport_date'],
            'transport_time' => $data['transport_time'],
            'cargo_type' => $data['cargo_type'],
            'custom_cargo' => $data['custom_cargo'],
        ]);
        */

		// Задание: Заявка на тест-драйв
		/*
        $data = [
            'phone' => trim($_POST['phone'] ?? ''),
            'address' => trim($_POST['address'] ?? ''),
            'desired_date' => trim($_POST['desired_date'] ?? ''),
            'desired_time' => trim($_POST['desired_time'] ?? ''),
            'license_series' => trim($_POST['license_series'] ?? ''),
            'license_number' => trim($_POST['license_number'] ?? ''),
            'license_issued_at' => trim($_POST['license_issued_at'] ?? ''),
            'car_brand' => trim($_POST['car_brand'] ?? ''),
            'car_model' => trim($_POST['car_model'] ?? ''),
            'payment_type' => trim($_POST['payment_type'] ?? ''),
        ];

        $errors = $this->validateStoreRequest($data);

        if (!empty($errors)) {
            $this->flash('errors', $errors);
            $this->flash('old', $data);
            $this->redirectBack();
        }

        $stmt = $this->pdo->prepare("
            INSERT INTO bids (
                user_id, status, phone, address, desired_date, desired_time,
                license_series, license_number, license_issued_at,
                car_brand, car_model, payment_type, cancellation_reason
            ) VALUES (
                :user_id, 'pending', :phone, :address, :desired_date, :desired_time,
                :license_series, :license_number, :license_issued_at,
                :car_brand, :car_model, :payment_type, NULL
            )
        ");

        $stmt->execute([
            'user_id' => $userId,
            'phone' => $data['phone'],
            'address' => $data['address'],
            'desired_date' => $data['desired_date'],
            'desired_time' => $data['desired_time'],
            'license_series' => $data['license_series'],
            'license_number' => $data['license_number'],
            'license_issued_at' => $data['license_issued_at'],
            'car_brand' => $data['car_brand'],
            'car_model' => $data['car_model'],
            'payment_type' => $data['payment_type'],
        ]);
        */

		$this->redirect('/bids');
	}

	public function update(): void
	{
		$bidId = $_POST['bidId'] ?? null;

		if ($bidId === null) {
			$this->flash('errors', ['Заявка не найдена']);
			$this->redirectBack();
		}

		$stmt = $this->pdo->prepare(
			"SELECT * FROM bids WHERE id = :id"
		);

		$stmt->execute(['id' => $bidId]);
		$bid = $stmt->fetch(\PDO::FETCH_ASSOC);

		if (!$bid) {
			$this->flash('errors', ['Заявка не найдена']);
			$this->redirectBack();
		}

		$data = [
			'status' => $_POST['status'] ?? '',
			'cancellation_reason' => trim($_POST['cancellation_reason'] ?? ''),
		];

		$errors = $this->validateUpdateRequest($data);

		if (!empty($errors)) {
			$this->flash('errors', $errors);
			$this->redirectBack();
		}

		$cancellationReason = ($data['status'] === 'rejected') ? $data['cancellation_reason'] : null;

		$stmt = $this->pdo->prepare("
			UPDATE bids
			SET status = :status,
				cancellation_reason = :cancellation_reason
			WHERE id = :id
		");

		$stmt->execute([
			'status' => $data['status'],
			'cancellation_reason' => $cancellationReason,
			'id' => $bidId,
		]);

		$this->redirectBack();
	}

	protected function validateStoreRequest(array $data): array
	{
		$errors = [];

		if (empty($data['phone'])) {
			$errors['phone'] = 'Укажите номер телефона.';
		} elseif (!preg_match('/^\+7\(\d{3}\)-\d{3}-\d{2}-\d{2}$/', $data['phone'])) {
			$errors['phone'] = 'Телефон должен быть в формате +7(XXX)-XXX-XX-XX.';
		}

		// Задание: Заявка на уборку
		if (empty($data['address'])) {
			$errors['address'] = 'Введите адрес.';
		} elseif (!is_string($data['address']) || mb_strlen($data['address']) > 255) {
			$errors['address'] = 'Адрес не должен превышать 255 символов.';
		}

		if (empty($data['receipt_date'])) {
			$errors['receipt_date'] = 'Укажите дату получения услуги.';
		} elseif (!$this->validateDate($data['receipt_date'])) {
			$errors['receipt_date'] = 'Дата получения услуги должна быть корректной датой.';
		}

		if (empty($data['receipt_time'])) {
			$errors['receipt_time'] = 'Укажите время получения услуги.';
		} elseif (!$this->validateTimeFormat($data['receipt_time'])) {
			$errors['receipt_time'] = 'Время должно быть в формате ЧЧ:ММ.';
		}

		$validServiceTypes = ['general', 'deep_cleaning', 'post_renovation', 'dry_cleaning', 'custom'];
		if (!in_array($data['service_type'], $validServiceTypes, true)) {
			$errors['service_type'] = 'Выбранный вид услуги недоступен.';
		}

		if (($data['service_type'] ?? '') === 'custom' && empty(trim($data['custom_service'] ?? ''))) {
			$errors['custom_service'] = 'Опишите требуемую услугу, если выбрали "Иная услуга".';
		} elseif (!empty($data['custom_service']) && (!is_string($data['custom_service']) || mb_strlen($data['custom_service']) > 255)) {
			$errors['custom_service'] = 'Описание услуги не должно превышать 255 символов.';
		}

		$validPaymentTypes = ['cash', 'card'];
		if (empty($data['payment_type'])) {
			$errors['payment_type'] = 'Выберите тип оплаты.';
		} elseif (!in_array($data['payment_type'], $validPaymentTypes, true)) {
			$errors['payment_type'] = 'Выбранный тип оплаты недоступен.';
		}

		// Задание: Заявка на перевозку груза
		// if (empty($data['weight'])) {
		// 	$errors['weight'] = 'Укажите вес груза.';
		// } elseif (!is_string($data['weight']) || mb_strlen($data['weight']) > 255) {
		// 	$errors['weight'] = 'Вес груза должен быть строкой длиной не более 255 символов.';
		// }

		// if (empty($data['dimensions'])) {
		// 	$errors['dimensions'] = 'Укажите габариты груза.';
		// } elseif (!is_string($data['dimensions']) || mb_strlen($data['dimensions']) > 255) {
		// 	$errors['dimensions'] = 'Габариты груза должны быть строкой длиной не более 255 символов.';
		// }

		// if (empty($data['from_address'])) {
		// 	$errors['from_address'] = 'Укажите адрес отправления.';
		// } elseif (!is_string($data['from_address']) || mb_strlen($data['from_address']) > 255) {
		// 	$errors['from_address'] = 'Адрес отправления должен быть строкой длиной не более 255 символов.';
		// }

		// if (empty($data['to_address'])) {
		// 	$errors['to_address'] = 'Укажите адрес назначения.';
		// } elseif (!is_string($data['to_address']) || mb_strlen($data['to_address']) > 255) {
		// 	$errors['to_address'] = 'Адрес назначения должен быть строкой длиной не более 255 символов.';
		// }

		// if (empty($data['transport_date'])) {
		// 	$errors['transport_date'] = 'Укажите дату транспортировки.';
		// } elseif (!$this->validateDate($data['transport_date'])) {
		// 	$errors['transport_date'] = 'Дата транспортировки должна быть корректной датой.';
		// }

		// if (empty($data['transport_time'])) {
		// 	$errors['transport_time'] = 'Укажите время транспортировки.';
		// } elseif (!$this->validateTimeFormat($data['transport_time'])) {
		// 	$errors['transport_time'] = 'Время транспортировки должно быть в формате ЧЧ:ММ.';
		// }

		// $validCargoTypes = ['fragile', 'perishable', 'refrigerated', 'animals', 'liquid', 'furniture', 'waste', 'custom'];
		// if (!in_array($data['cargo_type'], $validCargoTypes, true)) {
		// 	$errors['cargo_type'] = 'Выбранный тип груза недоступен.';
		// }

		// if (($data['cargo_type'] ?? '') === 'custom' && empty(trim($data['custom_cargo'] ?? ''))) {
		// 	$errors['custom_cargo'] = 'Опишите груз, если выбрали "Другой груз".';
		// } elseif (!empty($data['custom_cargo']) && (!is_string($data['custom_cargo']) || mb_strlen($data['custom_cargo']) > 255)) {
		// 	$errors['custom_cargo'] = 'Описание груза не должно превышать 255 символов.';
		// }

		// Задание: Заявка на тест-драйв
		// if (empty($data['address'])) {
		// 	$errors['address'] = 'Введите адрес.';
		// } elseif (!is_string($data['address']) || mb_strlen($data['address']) > 255) {
		// 	$errors['address'] = 'Адрес не должен превышать 255 символов.';
		// }

		// if (empty($data['desired_date'])) {
		// 	$errors['desired_date'] = 'Укажите желаемую дату.';
		// } elseif (!$this->validateDate($data['desired_date'])) {
		// 	$errors['desired_date'] = 'Желаемая дата должна быть корректной датой.';
		// }

		// if (empty($data['desired_time'])) {
		// 	$errors['desired_time'] = 'Укажите желаемое время.';
		// } elseif (!$this->validateTimeFormat($data['desired_time'])) {
		// 	$errors['desired_time'] = 'Желаемое время должно быть в формате ЧЧ:ММ.';
		// }

		// if (empty($data['license_series'])) {
		// 	$errors['license_series'] = 'Укажите серию водительского удостоверения.';
		// } elseif (!is_string($data['license_series']) || mb_strlen($data['license_series']) > 255) {
		// 	$errors['license_series'] = 'Серия водительского удостоверения не должна превышать 255 символов.';
		// }

		// if (empty($data['license_number'])) {
		// 	$errors['license_number'] = 'Укажите номер водительского удостоверения.';
		// } elseif (!is_string($data['license_number']) || mb_strlen($data['license_number']) > 255) {
		// 	$errors['license_number'] = 'Номер водительского удостоверения не должен превышать 255 символов.';
		// }

		// if (empty($data['license_issued_at'])) {
		// 	$errors['license_issued_at'] = 'Укажите дату выдачи водительского удостоверения.';
		// } elseif (!$this->validateDate($data['license_issued_at'])) {
		// 	$errors['license_issued_at'] = 'Дата выдачи водительского удостоверения должна быть корректной датой.';
		// }

		// $validCarBrands = ['Toyota', 'BMW'];
		// if (!in_array($data['car_brand'], $validCarBrands, true)) {
		// 	$errors['car_brand'] = 'Выбранный бренд автомобиля недоступен.';
		// }

		// $validCarModels = ['Camry', 'Corolla', 'X5', '3 Series'];
		// if (!in_array($data['car_model'] ?? '', $validCarModels, true)) {
		// 	$errors['car_model'] = 'Выбранная модель автомобиля недоступна.';
		// }

		// Задание: Заявка на уборку / тест-драйв
		$validPaymentTypes = ['cash', 'card'];
		if (empty($data['payment_type'])) {
			$errors['payment_type'] = 'Выберите тип оплаты.';
		} elseif (!in_array($data['payment_type'], $validPaymentTypes, true)) {
			$errors['payment_type'] = 'Выбранный тип оплаты недоступен.';
		}

		return $errors;
	}

	protected function validateUpdateRequest(array $data): array
	{
		$errors = [];

		$validStatuses = ['pending', 'approved', 'rejected'];

		if ($data['status'] === '') {
			$errors['status'] = 'Статус обязателен';
		} elseif (!in_array($data['status'], $validStatuses, true)) {
			$errors['status'] = 'Недопустимый статус';
		}

		if ($data['status'] === 'rejected' && $data['cancellation_reason'] === '') {
			$errors['cancellation_reason'] = 'Причина отмены обязательна при отклонении заявки';
		} elseif (mb_strlen($data['cancellation_reason']) > 500) {
			$errors['cancellation_reason'] = 'Причина отмены слишком длинная (максимум 500 символов)';
		}

		return $errors;
	}

	/**
	 * Проверка даты в формате Y-m-d (или любой другой нужный формат)
	 */
	protected function validateDate(string $date): bool
	{
		$d = \DateTime::createFromFormat('Y-m-d', $date);
		return $d && $d->format('Y-m-d') === $date;
	}

	/**
	 * Проверка времени в формате H:i
	 */
	protected function validateTimeFormat(string $time): bool
	{
		$t = \DateTime::createFromFormat('H:i', $time);
		return $t && $t->format('H:i') === $time;
	}
}
