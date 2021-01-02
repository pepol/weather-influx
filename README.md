# weather-influx

PHP service to capture Weather Station data and store them in InfluxDB.

## Configuration

To configure, copy `config.default.php` into `config.php` and set constant
definitions according to your setup:

 - `DB_URL` - the URL to InfluxDB v1 API (default: `http://localhost:8086`)
 - `DB_NAME` - the name of InfluxDB database to use (default: `meteo`)
 - `DB_SERIES` - the name of InfluxDB series to use (default: `weather`)
