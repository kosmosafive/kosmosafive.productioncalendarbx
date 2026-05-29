<?php

declare(strict_types=1);

namespace Kosmosafive\ProductionCalendarBx\Http\Message;

use Bitrix\Main\Web\Http\Request;
use Bitrix\Main\Web\Uri;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;

class RequestFactory implements RequestFactoryInterface
{

    public function createRequest(string $method, $uri): RequestInterface
    {
        if (!($uri instanceof UriInterface)) {
            $uri = new Uri($uri);
        }

        return new Request($method, $uri);
    }
}