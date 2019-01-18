<?php

namespace Mjelamanov\GuzzlePsr18\Exception;

use Psr\Http\Message\RequestInterface;

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
    public function getRequest(): RequestInterface
    {
        return $this->request;
    }
}
