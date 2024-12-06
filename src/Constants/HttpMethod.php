<?php

declare(strict_types=1);

namespace Nomiai\PhpSdk\Constants;

abstract readonly class HttpMethod
{
    public const string GET = 'GET';
    public const string POST = 'POST';
    public const string PUT = 'PUT';
    public const string PATCH = 'PATCH';
    public const string DELETE = 'DELETE';
}
