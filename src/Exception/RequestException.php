<?php

namespace Mjelamanov\GuzzlePsr18\Exception;

use Psr\Http\Client\RequestExceptionInterface;

/**
 * Class RequestException
 *
 * @package Mjelamanov\GuzzlePsr18\Exception
 */
class RequestException extends GuzzleException implements RequestExceptionInterface
{
    use WithRequest;
}
