<?php

declare(strict_types=1);

namespace Nomiai\PhpSdk\Exceptions;

use Nomiai\PhpSdk\Constants\HttpStatus;
use RuntimeException;

class NomiException extends RuntimeException
{
    /**
     * Additional data that might be attached to the exception itself.
     *
     * @var array<string, mixed>
     */
    private array $data = [];

    /**
     * The default exception message.
     *
     * @var string
     */
    public $message = 'An unknown error occurred when interacting with the Nomi.ai API.';

    /**
     * Exception caused by API rate limiting.
     *
     * @see https://api.nomi.ai/docs/reference/general#rate-limits
     */
    public static function rateLimitExceeded(): self
    {
        return new self('You are making too many requests. Please try again later.', HttpStatus::TOO_MANY_REQUESTS);
    }

    /**
     * Exception caused by not being able to locate a specified Nomi.
     *
     * @see https://api.nomi.ai/docs/reference/get-v1-nomis-id
     */
    public static function nomiNotFound(): self
    {
        return new self(
            'The specified Nomi was not found. It may not exist or may not be associated with this account.',
            HttpStatus::NOT_FOUND,
        );
    }

    /**
     * Exception caused by an identifier not being a valid UUID.
     *
     * @see https://api.nomi.ai/docs/reference/get-v1-nomis-id
     */
    public static function invalidRouteParams(): self
    {
        return new self('The `id` parameter is not a valid UUID.', HttpStatus::BAD_REQUEST);
    }

    /**
     * Exception caused by an incorrect header configuration.
     *
     * @see https://api.nomi.ai/docs/reference/post-v1-nomis-id-chat
     */
    public static function invalidContentType(): self
    {
        return new self('The `Content-Type` header is not `application/json`.', HttpStatus::UNSUPPORTED_MEDIA_TYPE);
    }

    /**
     * A rare exception caused by a server or AI malfunction.
     *
     * @see https://api.nomi.ai/docs/reference/post-v1-nomis-id-chat
     */
    public static function noReply(): self
    {
        return new self(
            'The Nomi did not reply to this message. This is rare but will occur if there is a server issue or if the
            Nomi does not respond within 30 seconds.',
            HttpStatus::INTERNAL_SERVER_ERROR,
        );
    }

    /**
     * An exception caused by trying to communicate with a Nomi prematurely.
     *
     * @see https://api.nomi.ai/docs/reference/post-v1-nomis-id-chat
     */
    public static function nomiStillResponding(): self
    {
        return new self(
            'The Nomi is already replying to a user message (either made through the UI or a different API call) and so
            cannot reply to this message.',
            HttpStatus::CONFLICT,
        );
    }

    /**
     * An exception caused by trying to communicate with a Nomi before it has finished initialisation.
     *
     * @see https://api.nomi.ai/docs/reference/post-v1-nomis-id-chat
     */
    public static function nomiNotReady(): self
    {
        return new self(
            'Immediately after the creation of a Nomi, there is a short period of several seconds before it is possible
            to send messages.',
            HttpStatus::SERVICE_UNAVAILABLE,
        );
    }

    /**
     * An exception caused by trying to communicate with a Nomi in a voice call.
     *
     * @see https://api.nomi.ai/docs/reference/post-v1-nomis-id-chat
     */
    public static function ongoingVoiceCallDetected(): self
    {
        return new self('The Nomi is currently in a voice call and cannot respond to messages.', HttpStatus::CONFLICT);
    }

    /**
     * An exception caused by a message being too long in length.
     *
     * @see https://api.nomi.ai/docs/reference/post-v1-nomis-id-chat
     */
    public static function messageLengthLimitExceeded(): self
    {
        return new self(
            'The provided `messageText` is too long. Maximum message length is 400 for free accounts and 600 for users
            with a subscription.',
            HttpStatus::PAYLOAD_TOO_LARGE,
        );
    }

    /**
     * An exception caused by a user going over their daily message quota.
     *
     * @see https://api.nomi.ai/docs/reference/post-v1-nomis-id-chat
     */
    public static function limitExceeded(): self
    {
        return new self(
            'Cannot send the message because the user has exhausted their daily message quota.',
            HttpStatus::TOO_MANY_REQUESTS,
        );
    }

    /**
     * An exception caused by an explicitly bad request.
     *
     * @see https://api.nomi.ai/docs/reference/post-v1-nomis-id-chat
     */
    public static function invalidBody(array $body): self
    {
        $exception = new self(
            'There was something wrong with the request, the details to which have been attached to the exception.',
            HttpStatus::BAD_REQUEST,
        );

        $exception->setData($body);

        return $exception;
    }

    /**
     * Appends additional data to the exception object.
     *
     * @param array<string, mixed> $data
     */
    public function setData(array $data): void
    {
        $this->data = $data;
    }

    /**
     * Returns the additional data, if any, attached to the object.
     *
     * @return array<string, mixed>
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * Returns whether the exception has additional data attached.
     */
    public function hasData(): bool
    {
        return ! empty($this->data);
    }
}
