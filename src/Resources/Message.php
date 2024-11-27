<?php

declare(strict_types=1);

namespace Nomiai\PhpSdk\Resources;

use DateTimeImmutable;

readonly class Message extends Resource
{
    /**
     * Unique identifier
     * TSDoc UUID
     */
    public string $uuid;

    /**
     * Body of the message
     * TSDoc String
     */
    public string $text;

    /**
     * Date of when the message was sent
     * TSDoc "ISODateString"
     */
    public DateTimeImmutable $sent;

    /**
     * Message constructor.
     *
     * @throws \RuntimeException
     * @throws \DateMalformedStringException
     */
    public function __construct(
        string $uuid,
        string $text,
        string|DateTimeImmutable $sent,
    ) {
        $this->uuid = $uuid;
        $this->text = $text;
        $this->sent = $sent instanceof DateTimeImmutable
            ? $sent
            : new DateTimeImmutable($sent);
    }

    /** @inheritDoc */
    public static function make(array $response): static
    {
        return new self(
            uuid: $response['uuid'],
            text: $response['text'],
            sent: $response['sent'],
        );
    }

    /** @inheritDoc */
    public function toArray(): array
    {
        return [
            'uuid' => $this->uuid,
            'text' => $this->text,
            'sent' => $this->sent->format(self::ISO8601),
        ];
    }
}
