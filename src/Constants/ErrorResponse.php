<?php

declare(strict_types=1);

namespace Nomiai\PhpSdk\Constants;

abstract readonly class ErrorResponse
{
    // Generic types
    public const string RATE_LIMIT_EXCEEDED = 'RateLimitExceeded';
    public const string INVALID_ROUTE_PARAMS = 'InvalidRouteParams';
    public const string INVALID_CONTENT_TYPE = 'InvalidContentType';
    public const string INVALID_BODY = 'InvalidBody';

    // Nomi-related
    public const string NOMI_NOT_FOUND = 'NomiNotFound';
    public const string NO_REPLY = 'NoReply';
    public const string NOMI_STILL_RESPONDING = 'NomiStillResponding';
    public const string NOMI_NOT_READY = 'NomiNotReady';
    public const string ONGOING_VOICE_CALL_DETECTED = 'OngoingVoiceCallDetected';
    public const string MESSAGE_LENGTH_LIMIT_EXCEEDED = 'MesageLengthLimitExceeded';
    public const string LIMIT_EXCEEDED = 'LimitExceeded';

    // Avatar-related
    public const string NOT_FOUND = 'NotFound';
}
