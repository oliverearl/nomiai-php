<?php

declare(strict_types=1);

namespace Nomiai\PhpSdk\Exceptions;

use InvalidArgumentException;

class ValidationException extends InvalidArgumentException
{
    /**
     * Exception thrown when a message to a Nomi exceeds the character limit.
     */
    public static function nomiMessageTooLong(int $length, int $maxLength = 800): self
    {
        return new self(
            "Message text is {$length} characters, but the maximum allowed is {$maxLength} characters (400 for free accounts, 800 for subscribed accounts).",
        );
    }

    /**
     * Exception thrown when a message to a room exceeds the character limit.
     */
    public static function roomMessageTooLong(int $length, int $maxLength = 800): self
    {
        return new self(
            "Message text is {$length} characters, but the maximum allowed for room messages is {$maxLength} characters.",
        );
    }

    /**
     * Exception thrown when a room name exceeds the character limit.
     */
    public static function roomNameTooLong(int $length, int $maxLength = 100): self
    {
        return new self(
            "Room name is {$length} characters, but the maximum allowed is {$maxLength} characters.",
        );
    }

    /**
     * Exception thrown when a room note exceeds the character limit.
     */
    public static function roomNoteTooLong(int $length, int $maxLength = 1000): self
    {
        return new self(
            "Room note is {$length} characters, but the maximum allowed is {$maxLength} characters.",
        );
    }
}
