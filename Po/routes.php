<?php

$routes = [
	'GET' => [
		'/' => 'HomeController@index',

		'/login' => 'Auth\\LoginController@index',
		'/register' => 'Auth\\RegisterController@index',

		'/bids' => 'BidController@index',
		'/bids/create' => 'BidController@create',

		'/admin' => 'AdminController@index',
	],

	'POST' => [
		'/login' => 'Auth\\LoginController@store',
		'/register' => 'Auth\\RegisterController@store',
		'/logout' => 'Auth\\LogoutController@store',

		'/bids' => 'BidController@store',
		'/bids/update' => 'BidController@update',
	],
];
