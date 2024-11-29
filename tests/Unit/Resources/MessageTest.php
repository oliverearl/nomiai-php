<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

use Nomiai\PhpSdk\Resources\Message;

it('can be made into an array', function (): void {
    $message = new Message(
        uuid: $this->faker->uuid(),
        text: $this->faker->sentence(),
        sent: new DateTimeImmutable(),
    );

    expect($message->toArray())
        ->toEqual([
            'uuid' => $message->uuid,
            'text' => $message->text,
            'sent' => $message->sent->format(Message::ISO8601),
        ]);
});
