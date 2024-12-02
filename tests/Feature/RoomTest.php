<?php

declare(strict_types=1);

use Nomiai\PhpSdk\Constants\HttpMethod;
use Nomiai\PhpSdk\Constants\HttpStatus;
use Nomiai\PhpSdk\Resources\Room;

describe('rooms', function (): void {
    it('can get a list of rooms associated with an account', function (): void {
        $room = $this->room();

        $api = $this->dummy(
            uri: '/v1/rooms',
            method: HttpMethod::GET,
            status: HttpStatus::OK,
            body: [
                'rooms' => [$room],
            ],
        );

        $retrievedRooms = $api->getRooms();

        expect($retrievedRooms)
            ->toBeArray()
            ->toContainOnlyInstancesOf(Room::class);

        $retrievedRoom = reset($retrievedRooms);

        expect($retrievedRoom)
            ->toBeInstanceOf(Room::class)
            ->toArray()->toEqual($room->toArray());
    });

    it('can get a specific room by id', function (): void {
        $room = $this->room();

        $api = $this->dummy(
            uri: "/v1/rooms/{$room->uuid}",
            method: HttpMethod::GET,
            status: HttpStatus::OK,
            body: $room->toArray(),
        );

        $retrievedRoom = $api->getRoom($room->uuid);

        expect($retrievedRoom)
            ->toBeInstanceOf(Room::class)
            ->toArray()->toEqual($room->toArray());
    });

    it('can create a room', function (): void {})->todo();

    it('can update a room', function (): void {})->todo();

    it('can delete a room', function (): void {})->todo();
});

describe('room messages', function (): void {
    it('can send a message to a room', function (): void {})->todo();

    it('can request a nomi post a message to a room', function (): void {})->todo();
});
