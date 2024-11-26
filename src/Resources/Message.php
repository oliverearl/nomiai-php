<?php

declare(strict_types=1);

namespace Nomiai\PhpSdk\Resources;

use DateTimeImmutable;
use RuntimeException;

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
            : (
                DateTimeImmutable::createFromFormat(DateTimeImmutable::ATOM, $sent)
                ?: throw new RuntimeException('The provided creation date is invalid!')
            );
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
            'sent' => $this->sent->format(DateTimeImmutable::ATOM),
        ];
    }
}
