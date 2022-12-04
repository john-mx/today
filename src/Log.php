<?php
namespace DigitalMx\jotr;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Processor\IntrospectionProcessor;
use Monolog\Level;



class Log {

	protected static $instance;

	/**
	 * Method to return the Monolog instance
	 *
	 * @return \Monolog\Logger
	 */
	static public function getLogger()
	{
		if (! self::$instance) {
			self::configureInstance();
		}

		return self::$instance;
	}

	/**
	 * Configure Monolog to use a rotating files system.
	 *
	 * @return Logger
	 */
	protected static function configureInstance()
	{
		$dir = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'logs';
		$log = $dir . '/today.log';

		if (!file_exists($dir)){
			mkdir($dir, 0777, true);
		}

		$fileHandler = new RotatingFileHandler($log,5);
		$stream = new StreamHandler($log, Level::Debug);
		$output = "%datetime% %level_name% > %message% %context% %extra%\n";
		$dateFormat = "Y-m-d H:i";
		$formatter = new LineFormatter($output, $dateFormat);
		$stream->setFormatter($formatter);

		$errhandler = new StreamHandler('php://stderr');
$logger = new Logger('Today');
		$logger->pushHandler($stream);

		$logger->pushHandler($fileHandler);
		$logger->pushHandler($errhandler);


		self::$instance = $logger;
	}

	public static function debug($message, array $context = []){
		self::getLogger()->debug($message, $context);
	}

	public static function info($message, array $context = []){
		self::getLogger()->info($message, $context);
	}

	public static function notice($message, array $context = []){
		self::getLogger()->notice($message, $context);
	}

	public static function warning($message, array $context = []){
		self::getLogger()->warning($message, $context);
	}

	public static function error($message, array $context = []){
		self::getLogger()->error($message, $context);
	}

	public static function critical($message, array $context = []){
		self::getLogger()->critical($message, $context);
	}

	public static function alert($message, array $context = []){
		self::getLogger()->alert($message, $context);
	}

	public static function emergency($message, array $context = []){
		self::getLogger()->addEmergency($message, $context);
	}

}
