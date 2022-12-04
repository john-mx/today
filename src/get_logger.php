<?php

function get_logger() {
   static $logger;

   if ($logger !== null) {
      return $logger;
   }

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

/*
You could put this in a file that you require_once, and in each of these places where you need access to the logger you can simply do:

 get_logger()->info('write to the log!')*/
