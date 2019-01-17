<?php

namespace Mjelamanov\GuzzlePsr18\Exception;

use Psr\Http\Message\RequestInterface;
use Throwable;

/**
 * Trait WithRequest
 *
 * @package Mjelamanov\GuzzlePsr18\Exception
 */
trait WithRequest
{
    /**
     * @var \Psr\Http\Message\RequestInterface
     */
    protected $request;

    /**
     * @inheritDoc
     */
    public function __construct(RequestInterface $request, string $message = '', Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);

        $this->request = $request;
    }

    /**
     * @inheritDoc
     */
    public function getRequest(): RequestInterface
    {
        return $this->request;
    }
}
