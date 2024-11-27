<?php

declare(strict_types=1);

namespace Nomiai\PhpSdk\Resources;

readonly class MessageSet extends Resource
{
    /**
     * MessageSet constructor.
     */
    public function __construct(public Message $sent, public Message $reply) {}

    /** @inheritDoc */
    public static function make(array $response): static
    {
        return new self(
            sent: Message::make($response['sentMessage']),
            reply: Message::make($response['replyMessage']),
        );
    }

    /** @inheritDoc */
    public function toArray(): array
    {
        return [
            'sentMessage' => $this->sent->toArray(),
            'replyMessage' => $this->reply->toArray(),
        ];
    }
}
