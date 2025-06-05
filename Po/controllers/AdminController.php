<?php

require_once __DIR__ . '/Controller.php';

class AdminController extends Controller
{
	protected $pdo;

	public function __construct()
	{
		if (session_status() === PHP_SESSION_NONE) {
			session_start();
		}

		if (!$this->isAdmin()) {
			$this->redirect('/');
		}

		global $pdo;
		$this->pdo = $pdo;
	}

	public function index()
	{
		$title = 'Админ панель';

		// Задание: Заявка на уборку
		$stmt = $this->pdo->query(
			"SELECT 
				bids.id AS bid_id,
				bids.status,
				bids.phone,
				bids.address,
				bids.receipt_date,
				bids.receipt_time,
				bids.service_type,
				bids.cancellation_reason,
				users.full_name
			FROM 
				bids
			JOIN 
				users ON bids.user_id = users.id
			ORDER BY 
				bids.id DESC"
		);

		// Задание: Заявка на перевозку груза
		// $stmt = $this->pdo->query(
		// 	"SELECT 
		// 		bids.id AS bid_id,
		// 		bids.status,
		// 		bids.phone,
		// 		bids.weight,
		// 		bids.dimensions,
		// 		bids.from_address,
		// 		bids.to_address,
		// 		bids.transport_date,
		// 		bids.transport_time,
		// 		bids.fragile,
		// 		bids.custom_cargo,
		// 		users.full_name
		// 	FROM 
		// 		bids
		// 	JOIN 
		// 		users ON bids.user_id = users.id
		// 	ORDER BY 
		// 		bids.id DESC"
		// );

		// Задание: Заявка на тест-драйв
		// $stmt = $this->pdo->query(
		// 	"SELECT 
		// 		bids.id AS bid_id,
		// 		bids.status,
		// 		bids.phone,
		// 		bids.address,
		// 		bids.desired_date,
		// 		bids.desired_time,
		// 		bids.license_series,
		// 		bids.license_number,
		// 		bids.license_issued_at,
		// 		bids.car_brand,
		// 		bids.car_model,
		// 		bids.payment_type,
		// 		bids.cancellation_reason,
		// 		users.full_name
		// 	FROM 
		// 		bids
		// 	JOIN 
		// 		users ON bids.user_id = users.id
		// 	ORDER BY 
		// 		bids.id DESC"
		// );

		$bids = $stmt->fetchAll(PDO::FETCH_ASSOC);

		$this->view(
			'views/admin/index.php',
			[
				'title' => $title,
				'bids' => $bids,
			]
		);
	}
}
