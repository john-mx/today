<?php
namespace DigitalMx\jotr;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Processor\IntrospectionProcessor;
use DigitalMx\jotr\LogProcessor;
use Monolog\Level;

class TLog //Today Log
{


	public function __construct($c) {
		if ($this->logger !== null) {
      return $this->logger;
   }

		$logfile = dirname(__DIR__) . '/logs/today.log';
		$logger = $this->build_logger($logfile);
		$logger->info('testlog');

		$this->logger=$logger;


	}

	private function build_logger($logfile) {
		$logger = new Logger('today_app');

		$output = " %level_name% %datetime% [%file_info%] > %message% %context% %extra%\n";
		$dateFormat = "Y-m-d H:i";
		$formatter = new LineFormatter($output, $dateFormat);

		// Create a handler
		#
		$fileHandler = new RotatingFileHandler($logfile);
		$fileHandler->setFormatter($formatter);

		$errhandler = new StreamHandler('php://stderr');

		$stream = new StreamHandler($logfile, Level::Debug);
		$stream->setFormatter($formatter);

		// Create the logger

		// Now add some handlers
		$logger->pushHandler($fileHandler);
		$logger->pushHandler($errhandler);
		$logger->pushHandler($stream);


		#$logger->pushProcessor(new LogProcessor());



		#$logger->pushProcessor(new \Monolog\Processor\IntrospectionProcessor(Logger::DEBUG, array()));

		// You can now use your logger

		return $logger;
	}

 public function findFile() {
      $debug = debug_backtrace();
      return [
        'file' => $debug[3] ? basename($debug[3]['file']) : '',
        'line' => $debug[3] ? $debug[3]['line'] : ''
      ];
    }
}
