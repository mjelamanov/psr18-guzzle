<?php

namespace Mjelamanov\GuzzlePsr18\Exception;

use Psr\Http\Client\NetworkExceptionInterface;

/**
 * Class NetworkException
 *
 * @package Mjelamanov\GuzzlePsr18\Exception
 */
class NetworkException extends GuzzleException implements NetworkExceptionInterface
{
    use WithRequest;
}
