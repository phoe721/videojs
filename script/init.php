<?
	/* Display Errors */
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	/* Constants */
	define('ROOT', '/var/www/html/phoe721.com/project/videojs/');
	define('IMG_DIR', ROOT . 'img/');
	define('LOG_FILE', ROOT . 'log/videojs.log');
	define('MAX_TIME', 1000);
	define('NUM_OF_SCREENSHOTS', 4);
?>