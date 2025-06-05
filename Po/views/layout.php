<!DOCTYPE html>
<html lang="ru" data-theme="light">

<head>
	<meta charset="utf-8" />
	<meta
		name="viewport"
		content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

	<title><?= htmlspecialchars($title ?? '') ?></title>

	<meta name="google" content="notranslate" />

	<link rel="shortcut icon" href="./../assets/logo.png" />

	<meta name="description" content="" />
	<meta name="keywords" content="" />
	<meta name="author" content="" />

	<link rel="stylesheet" href="./../assets/css/style.css" />
</head>

<body translate="no">
	<?php if (!empty($withHeader)) include 'views/includes/header.php'; ?>

	<?php if (!empty($view)) include $view; ?>

	<?php if (!empty($withFooter)) include 'views/includes/footer.php'; ?>

	<script src="./../assets/js/main.js"></script>
</body>

</html>