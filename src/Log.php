<?php
namespace DigitalMx\jotr;
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\Utilities as U;



use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Processor\IntrospectionProcessor;

use Monolog\Level;

// use Symfony\Component\Mime\Email;
// use Symfony\Component\Mailer\Mailer;
// use Symfony\Component\Mailer\Transport;
// use Monolog\Handler\SymfonyMailerHandler;


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
		$dir = LOG_DIR;
		$log = $dir . '/today.log';

		if (!file_exists($dir)){
			mkdir($dir, 0777, true);
		}



		$logger = new Logger('Today');
#		$logger->reset();

		$outputFormat = "[%datetime%] %level_name% > %message% %context% %extra%\n";
		$dateFormat = 'Y-m-d H:i';
		$line_format = new LineFormatter($outputFormat, $dateFormat);

		$stream = new StreamHandler($log, Level::Debug);
		$stream->setFormatter($line_format);

		$rotateHandler = new RotatingFileHandler($log,5,Level::Debug);
		$rotateHandler->setFormatter($line_format);

		$logger->pushHandler($rotateHandler);

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

