<?php

include('config.php');

if (!defined('DB_URL')) {
	define('DB_URL', 'http://localhost:8086');
}

if (!defined('DB_NAME')) {
	define('DB_NAME', 'meteo');
}

if (!defined('DB_SERIES')) {
	define('DB_SERIES', 'weather');
}

$content = json_encode($_GET);

# Save data for verification
file_put_contents('latest.json', $content);

function storeData($content, $dbURL, $dbName, $dbSeries) {
	$opts = [
		'http' => [
			'method' => "POST",
			'content' => $dbSeries . " " . http_build_query($content, '', ','),
		]
	];

	$params = [
		"db" => $dbName
	];

	file_get_contents(
		$dbURL . '/write?' . http_build_query($params),
		false,
		stream_context_create($opts)
	);
}

storeData($content, DB_URL, DB_NAME, DB_SERIES);
?>
