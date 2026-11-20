<?php
//Connect to database (local MySQL/XAMPP or hosted TiDB Cloud)
//
// Connection settings are read (in priority order) from:
//   1. real environment variables (set in the hosting control panel)
//   2. a .env.local file located in the web root, the project root or this folder
// If nothing is configured the function falls back to a local XAMPP MySQL server.
//
// The definitions are guarded because several pages include this file with the
// non-"once" variant, which would otherwise redeclare the functions.

if (!function_exists('db_config')) {

/**
 * Parse a simple KEY=VALUE .env style file.
 */
function db_load_env_file($path)
{
	$vars = array();
	if (!is_file($path) || !is_readable($path)) {
		return $vars;
	}
	$lines = @file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
	if ($lines === false) {
		return $vars;
	}
	foreach ($lines as $line) {
		$line = trim($line);
		if ($line === '' || $line[0] === '#' || strpos($line, '=') === false) {
			continue;
		}
		list($key, $value) = explode('=', $line, 2);
		$key = trim($key);
		$value = trim($value);
		if (strlen($value) > 1 && ($value[0] === '"' || $value[0] === "'") && substr($value, -1) === $value[0]) {
			$value = substr($value, 1, -1);
		}
		$vars[$key] = $value;
	}
	return $vars;
}

/**
 * Resolve the database configuration once per request.
 */
function db_config()
{
	static $config = null;
	if ($config !== null) {
		return $config;
	}

	// Web root (deployed .env.local) -> project root -> this folder
	$candidates = array(
		__DIR__ . '/../.env.local',
		__DIR__ . '/../../.env.local',
		__DIR__ . '/.env.local',
	);
	$env = array();
	foreach ($candidates as $path) {
		$loaded = db_load_env_file($path);
		if (!empty($loaded)) {
			$env = $loaded;
			break;
		}
	}

	$get = function ($key, $default = '') use ($env) {
		$value = getenv($key);
		if ($value !== false && $value !== '') {
			return $value;
		}
		return (isset($env[$key]) && $env[$key] !== '') ? $env[$key] : $default;
	};

	$host = $get('DB_HOST');
	if ($host === '') {
		// No environment configured -> local development server (XAMPP)
		$config = array(
			'host' => 'localhost',
			'port' => 3306,
			'user' => 'root',
			'password' => '',
			'database' => 'lawyermanagement',
			'ssl' => false,
			'ca' => null,
		);
		return $config;
	}

	$ssl = filter_var($get('DB_SSL', 'false'), FILTER_VALIDATE_BOOLEAN);

	// Resolve the SSL CA certificate. The bundled copy makes this work on any host.
	$ca = null;
	$caValue = $get('DB_CA_PATH');
	if ($caValue !== '') {
		$paths = array(
			$caValue,
			__DIR__ . '/' . basename($caValue),
			__DIR__ . '/../' . $caValue,
			__DIR__ . '/../../' . $caValue,
		);
		foreach ($paths as $path) {
			if ($path !== '' && is_file($path)) {
				$ca = $path;
				break;
			}
		}
	}
	if ($ca === null && is_file(__DIR__ . '/isrgrootx1.pem')) {
		$ca = __DIR__ . '/isrgrootx1.pem';
	}
	if ($ca !== null) {
		$ssl = true;
	}

	$config = array(
		'host' => $host,
		'port' => (int) $get('DB_PORT', 3306),
		'user' => $get('DB_USER'),
		'password' => $get('DB_PASSWORD'),
		'database' => $get('DB_NAME', 'lawyermanagement'),
		'ssl' => $ssl,
		'ca' => $ca,
	);
	return $config;
}

function connect($setup = FALSE)
{
	$cfg = db_config();

	// Create connection
	mysqli_report(MYSQLI_REPORT_OFF);

	$con = mysqli_init();
	if (!$con) {
		return false;
	}
	@mysqli_options($con, MYSQLI_OPT_CONNECT_TIMEOUT, 15);

	$flags = 0;
	if (!empty($cfg['ssl'])) {
		if (!empty($cfg['ca'])) {
			@mysqli_ssl_set($con, NULL, NULL, $cfg['ca'], NULL, NULL);
		}
		$flags = MYSQLI_CLIENT_SSL;
	}

	$database = $setup ? NULL : $cfg['database'];

	$connected = @mysqli_real_connect(
		$con,
		$cfg['host'],
		$cfg['user'],
		$cfg['password'],
		$database,
		$cfg['port'],
		NULL,
		$flags
	);

	// Check connection
	if (!$connected || mysqli_connect_errno()) {
		return false;
	}

	@mysqli_set_charset($con, 'utf8mb4');
	return $con;
	//echo "Connected successfully";
}

} // end function guard
