<?php
namespace DigitalMx\jotr;

require_once  '/Users/john/Sites/jotr/vendor/autoload.php';

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;
use Monolog\Processor\ProcessIdProcessor;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Processor\IntrospectionProcessor;
use DigitalMx\jotr\LogProcessor;


	$logger = new Logger('today');
	$logfile = dirname(__DIR__) . '/logs/today.log';

	$stream = new StreamHandler($logfile, Logger::DEBUG);
$logger->info('first');
exit;
