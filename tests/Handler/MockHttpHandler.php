<?php
/**
 * Created by PhpStorm.
 * User: rezets
 * Date: 5/4/2018
 * Time: 21:16
 */

namespace App\Tests\Handler;


use App\Kernel;
use GuzzleHttp\Handler\MockHandler;
use function GuzzleHttp\Psr7\build_query;
use Psr\Http\Message\RequestInterface;
use Symfony\Component\HttpFoundation\Request;

class MockHttpHandler extends MockHandler
{

	private $queue = [];
	private $lastRequest;
	private $lastOptions;
	private $onFulfilled;
	private $onRejected;

	public function __invoke(RequestInterface $request, array $options)
	{
		$this->lastRequest = $request;
		$this->lastOptions = $options;

		$kernel = new Kernel( 'dev', true);
		$server = [];
		if(isset($options['auth'])) {
			//$auth = ['user' => $options['auth'][0], 'pass' => $options['auth'][1]];
			$server['PHP_AUTH_USER'] = $options['auth'][0];
			$server['PHP_AUTH_PW'] = $options['auth'][1];
		}
		$sRequest = Request::create(
			$request->getUri(),
			$request->getMethod(),
			$options,
			[],
			[],
			$server,
			$request->getBody()
		);
		$sResponse = $kernel->handle($sRequest);
		$kernel->terminate($sRequest, $sResponse);

		$response = new \GuzzleHttp\Psr7\Response(
			$sResponse->getStatusCode(),
			$sResponse->headers->all(),
			$sResponse->getContent()
		);
		$response = $response instanceof \Exception
			? \GuzzleHttp\Promise\rejection_for($response)
			: \GuzzleHttp\Promise\promise_for($response);
		return $response;
	}
}