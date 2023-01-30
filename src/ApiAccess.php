<?php
namespace DigitalMx\jotr;
use DigitalMx\jotr\Utilities as U;
use DigitalMx\jotr\CacheSettings as CS;

/* api call class

*/


use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
// use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

class ApiAccess {


	private $source;

	public function __construct($source){
		$this-source = $source;
		$url = CS::getURL($source);

		$client = new GuzzleHttp\Client(['base_uri' => $url,'timeout' => 2,]);
	}

	public function apiRequest($params) {
		// bu9ld thee request

	}

}
