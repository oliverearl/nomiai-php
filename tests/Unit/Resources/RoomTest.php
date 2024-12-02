<?php

declare(strict_types=1);

use Nomiai\PhpSdk\Enums\RoomStatus;
use Nomiai\PhpSdk\Resources\Room;

it('can be made into an array', function (): void {
    $data = [
        'uuid' => $this->faker->uuid(),
        'name' => $this->faker->company(),
        'created' => (new DateTimeImmutable())->format(Room::ISO8601),
        'updated' => (new DateTimeImmutable())->format(Room::ISO8601),
        'status' => $this->faker->randomElement(RoomStatus::cases())->value,
        'backchannelingEnabled' => $this->faker->boolean(),
        'note' => $this->faker->realText(),
        'nomis' => [
            $this->nomi()->toArray(),
            $this->nomi()->toArray(),
        ],
    ];

    $room = Room::make($data);

    expect($room->toArray())->toEqual($data);
});
