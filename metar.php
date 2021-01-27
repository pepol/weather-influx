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

if (!defined('DB_USERNAME')) {
  define('DB_USERNAME', '');
}

if (!defined('DB_PASSWORD')) {
  define('DB_PASSWORD', '');
}

function getData($dbURL, $dbUsername, $dbPassword, $dbName, $dbSeries) {
  $url = $dbURL . '/query?db=' . $dbName;
  if ($dbUsername != '' && $dbPassword != '') {
    $url = $url . '&u=' . $dbUsername . '&p=' . $dbPassword;
  }
  $getContent = urlencode('q=SELECT * FROM ' . $dbSeries . ' GROUP BY id ORDER BY time DESC LIMIT 1');

  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $getContent);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

  $response = curl_exec($ch);

  curl_close($ch);

  return $response;
}

$content = getData(DB_URL, DB_USERNAME, DB_PASSWORD, DB_NAME, DB_SERIES);

# Save data for verification
file_put_contents('latest.json', $content);

$data = json_decode($content);

var_dump($data);
?>
