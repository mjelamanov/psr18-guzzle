<?php

namespace Mjelamanov\GuzzlePsr18;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\TooManyRedirectsException;
use GuzzleHttp\Exception\TransferException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\NetworkExceptionInterface;
use Psr\Http\Client\RequestExceptionInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Class ClientTest.
 */
class ClientTest extends TestCase
{
    /**
     * @return void
     *
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function testSendRequest()
    {
        $guzzleClient = new GuzzleClient([
            'handler' => MockHandler::createWithMiddleware([new Response()]),
        ]);

        $request = new Request('GET', 'http://example.com');

        $client = new Client($guzzleClient);

        $response = $client->sendRequest($request);

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @return void
     *
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function testSendRequestWhenItThrowsConnectException()
    {
        $request = new Request('POST', 'http://example.com/create');

        $guzzleClient = new GuzzleClient([
            'handler' => MockHandler::createWithMiddleware([function () use ($request) {
                throw new ConnectException('Could not connect to the target', $request);
            }]),
        ]);

        $client = new Client($guzzleClient);

        $this->expectException(NetworkExceptionInterface::class);

        $client->sendRequest($request);
    }

    /**
     * @param string $exception
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param int $status
     *
     * @return void
     *
     * @throws \Psr\Http\Client\ClientExceptionInterface
     *
     * @dataProvider httpExceptionsProvider
     */
    #[DataProvider('httpExceptionsProvider')]
    public function testSendRequestWhenItThrowsHttpException(
        string $exception,
        ResponseInterface $response,
        int $status
    ) {
        $request = new Request('POST', 'http://example.com/create');

        $guzzleClient = new GuzzleClient([
            'handler' => MockHandler::createWithMiddleware([
                function () use ($response, $request, $exception) {
                    throw new $exception('Test exception', $request, $response);
                },
            ]),
        ]);

        $client = new Client($guzzleClient);

        $response1 = $client->sendRequest($request);

        $this->assertEquals($status, $response1->getStatusCode());
    }

    /**
     * @return void
     *
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function testSendRequestWhenItThrowsRequestExceptionWithoutResponse()
    {
        $request = new Request('POST', 'http://example.com/create');

        $guzzleClient = new GuzzleClient([
            'handler' => MockHandler::createWithMiddleware([function () use ($request) {
                throw new RequestException('Test exception', $request);
            }]),
        ]);

        $client = new Client($guzzleClient);

        $this->expectException(RequestExceptionInterface::class);

        $client->sendRequest($request);
    }

    /**
     * @return void
     *
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function testSendRequestWhenItThrowsTransferException()
    {
        $request = new Request('PUT', 'http://example.com/update');

        $guzzleClient = new GuzzleClient([
            'handler' => MockHandler::createWithMiddleware([function () use ($request) {
                throw new TransferException('Test exception');
            }]),
        ]);

        $client = new Client($guzzleClient);

        $this->expectException(ClientExceptionInterface::class);

        $client->sendRequest($request);
    }

    /**
     * @return void
     *
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function testSendRequestWhenItThrowsExceptionThatConvertsToPsrClientException()
    {
        $request = new Request('DELETE', 'http://example.com/delete');

        $guzzleClient = new GuzzleClient([
            'handler' => MockHandler::createWithMiddleware([function () use ($request) {
                throw new InvalidArgumentException('Request is invalid');
            }]),
        ]);

        $client = new Client($guzzleClient);

        $this->expectException(ClientExceptionInterface::class);

        $client->sendRequest($request);
    }

    /**
     * @return array
     */
    public static function httpExceptionsProvider(): array
    {
        return [
            [TooManyRedirectsException::class, new Response(302), 302],
            [ClientException::class, new Response(400), 400],
            [ServerException::class, new Response(500), 500],
        ];
    }
}
