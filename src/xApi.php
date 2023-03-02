<?php
namespace DigitalMx\jotr;
use DigitalMx\jotr\Utilities as U;
use DigitalMx\jotr\CacheSettings as CS;

/* api call class

Start with client = new Client(base_uri, headers)
This will create a client object in the class.
Then call apiRequest (relurl,headers).
The rel url is tacked onto the base url.
Each reuest is tried up to 3 times to get a result.
If no result, returns [];

Call thee request with the loginfo (usually src:loc),
the relative url to use, a query array, and optionally
the number of retries bewfore giving up.  Default is 3.
If you put in 0, then it will try once and if it fails its done.



*/


use GuzzleHttp\Client as Client;
use GuzzleHttp\Exception;
// use GuzzleHttp\Pool;
use GuzzleHttp\Psr7 as Psr7;
use GuzzleHttp\Psr7\Response as Response;
use GuzzleHttp\Psr7\Request as Request;


class Api {

	private $client;

	private $source;
	private $httpHeaders;
	private $base_uri;
	private $headers;
	public function __construct(string $apicode,array $headers=[]){

		$this->base_uri = CS::getURL($apicode);
//echo $this->base_uri . BR;
		$httpHeaders = array(
			'User-Agent' => 'nps-jotr Today/' . VERSION,
			 //'Accept'     => 'application/json',
			);
		$this->headers = $headers;
		$headers = array_merge ($httpHeaders,$headers);

		$this->client = new Client(['base_uri' => $this->base_uri,'timeout' => 2, 'headers' => $headers]);
	}

	public function apiRequest($loginfo,string $relurl='',array $query=[],$retries = 3) {
		// bu9ld thee request
			LOG::info("Starting apiRequest $loginfo");
			$request = new Request('GET',$relurl);
		$tries = 0;
		if (0) {
			echo "base: " . $base_uri . BR;
			echo "rel: $relurl" .BR;
			U::echor($query,'query');
		}
		while ($tries <= $retries) {
			try {
				$response = $this->client->send($request,['query' => $query]);
				break;
			} catch(GuzzleHttp\Exception\ServerException $e) {

				$einfo = $this->buildInfo($relurl,$query,$response,$e);
					Log::info ("Server Exception $loginfo",$einfo);
			} catch (GuzzleHttp\Exception\RequestException $e) {
				$einfo = $this->buildInfo($relurl,$query,$response,$e);
					Log::info("Api request exception $loginfo; try $tries.",$einfo);
			} catch(GuzzleHttp\Exception\ConnectException $e) {
				$einfo = $this->buildInfo($relurl,$query,$response,$e);
					Log::info ("Connect Exception $loginfo",$einfo);
			} catch (\RuntimeException $e) {
				$einfo = $this->buildInfo($relurl,$query,$response,$e);
					Log::info("uncaught api error",$einfo);

			} finally {
				++$tries;
				sleep(1);
				if ($tries >2) {
						Log::error ("Api failed multiple:$loginfo");
						return [];

				}
			}
		} #end while loop

		if (!$response) {
			Log::notice("No api response for $loginfo");
			echo "No response" . BR;
			return [];
		}
		$result = json_decode( $response->getBody(),true);
		// U::echor($result); exit;
		return $result;
	}

private function buildInfo($relurl,$query,&$response,&$e) {
	$info = array_merge(
		[
			'url' => $this->base_uri,
			'headers' => $this->headers,
			'relurl' => $relurl,
			'query' => $query,
		],
		$this->getCode($response),
		$this->getEmessage($e)
	);
	return $info;
}

private function getCode(&$response) {
		if ($response) {
			$code = ['code' =>
		'Status: '
		. $response->getStatusCode()
		. ' ('
		. $response->getReasonPhrase()
		. ')'
		];
		}
		else {
			$code = ['code' =>'No response'];
		}
		return $code;

	}

	private function getEmessage(&$e){
		// return array with error message
		if ($e){
		$resp = [
			'request' => Psr7\Message::toString($e->getRequest()),
			'response' => Psr7\Message::toString($e->getResponse())
			];
		} else {
			$resp = ['resp' =>"No error"];
		}
		return $resp;
	}
}
