<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

use Nomiai\PhpSdk\Resources\Message;
use Nomiai\PhpSdk\Resources\MessageSet;

it('can be made into an array', function (): void {
    $messageSet = new MessageSet(
        sent: new Message($this->faker->uuid(), $this->faker->sentence(), new DateTimeImmutable()),
        reply: new Message($this->faker->uuid(), $this->faker->sentence(), new DateTimeImmutable()),
    );

    expect($messageSet->toArray())
        ->toEqual([
            'sentMessage' => [
                'uuid' => $messageSet->sent->uuid,
                'text' => $messageSet->sent->text,
                'sent' => $messageSet->sent->sent->format(MessageSet::ISO8601),
            ],
            'replyMessage' => [
                'uuid' => $messageSet->reply->uuid,
                'text' => $messageSet->reply->text,
                'sent' => $messageSet->reply->sent->format(MessageSet::ISO8601),
            ],
        ]);
});
