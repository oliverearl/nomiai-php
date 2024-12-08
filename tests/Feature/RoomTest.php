<?php

declare(strict_types=1);

use Nomiai\PhpSdk\Constants\HttpMethod;
use Nomiai\PhpSdk\Constants\HttpStatus;
use Nomiai\PhpSdk\Requests\RoomRequest;
use Nomiai\PhpSdk\Resources\Message;
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

    it('can create a room', function (): void {
        $roomData = $this->room()->toArray();

        $api = $this->dummy(
            uri: '/v1/rooms',
            method: HttpMethod::POST,
            status: HttpStatus::CREATED,
            body: $roomData,
        );

        $room = $api->createRoom([
            'name' => $roomData['name'],
            'note' => $roomData['note'],
            'backchannelingEnabled' => $roomData['backchannelingEnabled'],
            'nomiUuids' => array_map(fn(array $nomi): string => $nomi['uuid'], $roomData['nomis']),
        ]);

        // This obviously assumes the Nomis are already present on the API.
        expect($room)
            ->toBeInstanceOf(Room::class)
            ->name->toEqual($roomData['name'])
            ->note->toEqual($roomData['note'])
            ->backchannelingEnabled->toEqual($roomData['backchannelingEnabled']);
    });

    it('cannot create an empty room', function (): void {
        $api = $this->dummy(
            uri: '/v1/rooms',
            method: HttpMethod::POST,
            status: HttpStatus::CREATED,
        );

        $api->createRoom([]);
    })->throws(InvalidArgumentException::class);

    it('can update a room', function (): void {
        // Assumes the room already exists on the API.
        $room = $this->room();
        $newName = $this->faker->company();
        $newRoomData = Room::make(array_merge($room->toArray(), ['name' => $newName]))->toArray();

        $api = $this->dummy(
            uri: "/v1/rooms/{$room->uuid}",
            method: HttpMethod::PUT,
            status: HttpStatus::OK,
            body: $newRoomData,
        );

        $requests = [
            $api->updateRoom($room, ['name' => $newName]),
            $api->updateRoomById($room->uuid, ['name' => $newName]),
        ];

        foreach ($requests as $request) {
            expect($request)
                ->toBeInstanceOf(Room::class)
                ->toArray()->toEqual($newRoomData);
        }
    });

    it('cannot send an empty update', function (): void {
        $room = $this->room();
        $update = new RoomRequest();

        $api = $this->dummy(
            uri: "/v1/rooms/{$room->uuid}",
            method: HttpMethod::PUT,
            status: HttpStatus::OK,
        );


       $api->updateRoom($room, $update);
    })->throws(InvalidArgumentException::class);

    it('can delete a room', function (): void {
        // Assumes the room already exists on the API.
        $room = $this->room();

        $api = $this->dummy(
            uri: "/v1/rooms/{$room->uuid}",
            method: HttpMethod::DELETE,
            status: HttpStatus::NO_CONTENT,
        );

        $requests = [
            $api->deleteRoom($room),
            $api->deleteRoomById($room->uuid),
        ];

        foreach ($requests as $request) {
            expect($request)->toBeTrue();
        }
    });
});

describe('room messages', function (): void {
    it('can send a message to a room', function (): void {
        $room = $this->room();
        $message = $this->faker->sentence();

        $api = $this->dummy(
            uri: "/v1/rooms/{$room->uuid}/chat",
            method: HttpMethod::POST,
            status: HttpStatus::CREATED,
            body: [
                'sentMessage' => [
                    'uuid' => $this->faker->uuid(),
                    'text' => $message,
                    'sent' => (new DateTimeImmutable())->format(Room::ISO8601),
                ],
            ],
        );

        $replies = [
            $api->sendMessageToRoom($room, $message),
            $api->sendMessageToRoomById($room->uuid, $message),
        ];

        foreach ($replies as $reply) {
            expect($reply)
                ->toBeInstanceOf(Message::class)
                ->text->toEqual($message);
        }
    });

    it('can request a nomi post a message to a room', function (): void {
        $room = $this->room();
        $nomiToRequest = $room->nomis[0];
        $message = $this->faker->sentence();

        $api = $this->dummy(
            uri: "/v1/rooms/{$room->uuid}/chat/request",
            method: HttpMethod::POST,
            status: HttpStatus::OK,
            body: [
                'replyMessage' => [
                    'uuid' => $this->faker->uuid(),
                    'text' => $message,
                    'sent' => (new DateTimeImmutable())->format(Room::ISO8601),
                ],
            ],
        );

        $requests = [
            $api->requestNomiToMessageRoom($room, $nomiToRequest),
            $api->requestNomiByIdToMessageRoom($room, $nomiToRequest->uuid),
            $api->requestNomiByIdToMessageRoomById($room->uuid, $nomiToRequest->uuid),
        ];

        foreach ($requests as $request) {
            expect($request)
                ->toBeInstanceOf(Message::class)
                ->text->toEqual($message);
        }
    });
});
