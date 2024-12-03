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

    // Room-related
    public const string INSUFFICIENT_PLAN = 'InsufficientPlan';
    public const string EXCEEDED_ROOM_LIMIT = 'ExceededRoomLimit';
    public const string ROOM_NOMI_COUNT_TOO_SMALL = 'RoomNomiCountTooSmall';
    public const string ROOM_NOMI_COUNT_TOO_LARGE = 'RoomNomiCountTooLarge';
    public const string NOTE_NOT_ACCEPTED = 'NoteNotAccepted';
    public const string NO_RESPONSE_FROM_SERVER = 'NoResponseFromServer';
    public const string ROOM_NOT_FOUND = 'RoomNotFound';
}
