# PSR-18 adapter for Guzzle 6

A PSR-18 adapter for [Guzzle](https://github.com/guzzle/guzzle) 6 client.

## Requirements

PHP 7.0 or above.

## Installation

```bash
composer require mjelamanov/psr18-guzzle
```

## Example

```php
require_once __DIR__ . '/vendor/autoload.php';

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\Request;
use Mjelamanov\GuzzlePsr18\Client;

$yourOptionalConfig = [];

$guzzleClient = new GuzzleClient($yourOptionalConfig);

$client = new Client($guzzleClient); // create an adapter instance
$request = new Request('GET', 'http://example.com'); // A PSR-7 request instance

$response = $client->sendRequest($request); // Sending request

var_dump($response->getStatusCode(), (string) $response->getBody()); // Prints response
```

## Error handling

How PSR-18 clients should handle errors, see [here](https://www.php-fig.org/psr/psr-18/#error-handling).
This library tries to follow to the that recommendation but Guzzle's custom handlers may break this compatibility.
In this case of you can tell me through "new issue" or send me a "pull request".
By default Guzzle client throws exceptions for HTTP 400 and 500 errors and this adapter is caught them and return
response as is. You can disable this behavior by ``` http_errors ``` option but the result will be the same.

```php
...

use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\NetworkExceptionInterface;
use Psr\Http\Client\RequestExceptionInterface;

...

try {
    $response = $client->sendRequest($request);
} catch (NetworkExceptionInterface $e) {
    // Network issues
} catch (RequestExceptionInterface $e) {
    // When request is invalid
} catch (ClientExceptionInterface $e) {
    // In all other cases
}
```

## Test

```bash
composer test
```

## License

The MIT license. Read [LICENSE file](https://github.com/mjelamanov/psr18-guzzle/blob/master/LICENSE).