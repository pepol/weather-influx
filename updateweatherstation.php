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

$stationID = $_GET['ID'];
// TODO(pp): Include station password verification.
unset($_GET['ID']);
unset($_GET['PASSWORD']);

// These 2 values have specific meaning for InfluxDB, so we can't pass them on.
unset($_GET['dateutc']);
unset($_GET['action']);

$content = json_encode($_GET);

# Save data for verification
file_put_contents('latest.json', $content);

function storeData($content, $stationID, $dbURL, $dbUsername, $dbPassword, $dbName, $dbSeries) {
  $url = $dbURL . '/write?db=' . $dbName;
  if ($dbUsername != '' && $dbPassword != '') {
    $url = $url . '&u=' . $dbUsername . '&p=' . $dbPassword;
  }
  $postContent = $dbSeries . ',id=' . $stationID . ' ' . http_build_query($content, '', ',');

  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $postContent);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

  $response = curl_exec($ch);

  curl_close($ch);
}

storeData(json_decode($content), $stationID, DB_URL, DB_USERNAME, DB_PASSWORD, DB_NAME, DB_SERIES);
?>
