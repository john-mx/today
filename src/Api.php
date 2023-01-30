<?php
namespace DigitalMx\jotr;
use DigitalMx\jotr\Utilities as U;
use DigitalMx\jotr\CacheSettings as CS;

/* api call class

*/


use GuzzleHttp\Client as Client;
use GuzzleHttp\Exception\RequestException;
// use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

class Api {

	private $client;

	private $source;
	private $httpHeaders;

	public function __construct(string $base,array $rheaders=[]){
		// $this-source = $source;
// 		$url = CS::getURL($source);


		$httpHeaders = array(
			'User-Agent' => 'nps-jotr Today/' . VERSION,
			 'Accept'     => 'application/json',
			);
		$headers = array_merge ($httpHeaders,$rheaders);
		$this->client = new Client(['base_uri' => $base,'timeout' => 2, 'headers' => $headers]);
	}

	public function apiRequest($relurl,array $headers=[]) {
		// bu9ld thee request
			echo "relurl: $relurl" . BR;
	try {
		$request = new Request('GET',$relurl);
		$response = $this->client->send($request);
		// foreach ($response->getHeaders() as $name => $values) {
//    		 echo $name . ': ' . implode(', ', $values) . "\r\n";
// 		}
	echo
		'Status: '
		. $response->getStatusCode()
		. ' ('
		. $response->getStatusReason()
		. ')' . BR;
	echo 'Length: ' . $response->getHeader('Content-Length')[0] . BR;

		} catch (ClientException $e) {
    		echo Psr7\Message::toString($e->getRequest());
		}

		$result = json_decode( $response->getBody(),true);
		U::echor($result); exit;
		return $result;

	}

}
