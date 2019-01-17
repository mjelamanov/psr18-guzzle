<?php

namespace Mjelamanov\GuzzlePsr18\Exception;

use Psr\Http\Client\ClientExceptionInterface;
use RuntimeException;

/**
 * Class GuzzleException
 *
 * @package Mjelamanov\GuzzlePsr18\Exception
 */
class GuzzleException extends RuntimeException implements ClientExceptionInterface
{

}
