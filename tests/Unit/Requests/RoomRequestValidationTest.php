<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

use Nomiai\PhpSdk\Constants\HttpMethod;
use Nomiai\PhpSdk\Constants\HttpStatus;
use Nomiai\PhpSdk\Exceptions\ValidationException;
use Nomiai\PhpSdk\Requests\RoomRequest;

describe('room request validation', function (): void {
    it('throws validation exception for room name exceeding 100 characters', function (): void {
        $longName = str_repeat('a', 101);
        $nomiUuid = $this->faker->uuid();

        $api = $this->dummy(
            uri: '/rooms',
            method: HttpMethod::POST,
            status: HttpStatus::OK,
            body: [],
        );

        $request = new RoomRequest(
            name: $longName,
            nomiUuids: [$nomiUuid],
        );

        $api->createRoom($request);
    })->throws(ValidationException::class);

    it('throws validation exception for room note exceeding 1000 characters', function (): void {
        $longNote = str_repeat('a', 1001);
        $nomiUuid = $this->faker->uuid();

        $api = $this->dummy(
            uri: '/rooms',
            method: HttpMethod::POST,
            status: HttpStatus::OK,
            body: [],
        );

        $request = new RoomRequest(
            name: 'Test Room',
            note: $longNote,
            nomiUuids: [$nomiUuid],
        );

        $api->createRoom($request);
    })->throws(ValidationException::class);

    it('allows room name with exactly 100 characters', function (): void {
        $maxName = str_repeat('a', 100);
        $nomiUuid = $this->faker->uuid();

        $room = $this->room();

        $api = $this->dummy(
            uri: '/rooms',
            method: HttpMethod::POST,
            status: HttpStatus::CREATED,
            body: $room->toArray(),
        );

        $request = new RoomRequest(
            name: $maxName,
            nomiUuids: [$nomiUuid],
        );

        expect($api->createRoom($request))->not->toBeNull();
    });

    it('allows room note with exactly 1000 characters', function (): void {
        $maxNote = str_repeat('a', 1000);
        $nomiUuid = $this->faker->uuid();

        $room = $this->room();

        $api = $this->dummy(
            uri: '/rooms',
            method: HttpMethod::POST,
            status: HttpStatus::CREATED,
            body: $room->toArray(),
        );

        $request = new RoomRequest(
            name: 'Test Room',
            note: $maxNote,
            nomiUuids: [$nomiUuid],
        );

        expect($api->createRoom($request))->not->toBeNull();
    });

    it('validates room name length on update', function (): void {
        $room = $this->room();
        $longName = str_repeat('a', 101);

        $api = $this->dummy(
            uri: "/rooms/{$room->uuid}",
            method: HttpMethod::PUT,
            status: HttpStatus::OK,
            body: [],
        );

        $api->updateRoom($room, ['name' => $longName]);
    })->throws(ValidationException::class);

    it('validates room note length on update', function (): void {
        $room = $this->room();
        $longNote = str_repeat('a', 1001);

        $api = $this->dummy(
            uri: "/rooms/{$room->uuid}",
            method: HttpMethod::PUT,
            status: HttpStatus::OK,
            body: [],
        );

        $api->updateRoom($room, ['note' => $longNote]);
    })->throws(ValidationException::class);
});
