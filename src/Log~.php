<?php
namespace DigitalMx\jotr;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\FirePHPHandler;
use Monolog\Formatter\LineFormatter;
use Monolog\Processor\ProcessIdProcessor;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Processor\IntrospectionProcessor;
use DigitalMx\jotr\LogProcessor;

class Log //Today Log
{

	public function __construct() {

		$logdir = dirname(__DIR__) . '/logs';
		$output = "%datetime% > %level_name% > %message% %context% %extra%\n";
		$dateFormat = "Y-m-d H:i";
		$formatter = new LineFormatter($output, $dateFormat);

		// Create a handler
		#
		$fileHandler = new RotatingFileHandler($logdir .'/today_app.log');
		$fileHandler->setFormatter($formatter);

		$errhandler = new StreamHandler('php://stderr');

		$stream = new StreamHandler($logdir .'/today_app.log', Logger::DEBUG);
		$stream->setFormatter($formatter);

		// Create the logger
		$logger = new Logger('today_app');
		// Now add some handlers
		$logger->pushHandler($fileHandler);
		$logger->pushHandler($errhandler);
		$logger->pushHandler($stream);
		$logger->pushProcessor(new LogProcessor() );


		#$logger->pushProcessor(new \Monolog\Processor\IntrospectionProcessor(Logger::DEBUG, array()));

		// You can now use your logger

		return $logger;
	}

}
