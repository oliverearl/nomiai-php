<?php

declare(strict_types=1);

namespace Nomiai\PhpSdk\Actions;

use InvalidArgumentException;
use Nomiai\PhpSdk\Exceptions\ValidationException;
use Nomiai\PhpSdk\Requests\RoomRequest;
use Nomiai\PhpSdk\Resources\Message;
use Nomiai\PhpSdk\Resources\Nomi;
use Nomiai\PhpSdk\Resources\Room;

/** @mixin \Nomiai\PhpSdk\Traits\MakesHttpRequests */
trait ManagesRooms
{
    /**
     * Maximum message length for room chats.
     */
    private const int MAX_ROOM_MESSAGE_LENGTH = 800;

    /**
     * Maximum room name length.
     */
    private const int MAX_ROOM_NAME_LENGTH = 100;

    /**
     * Maximum room note length.
     */
    private const int MAX_ROOM_NOTE_LENGTH = 1000;

    /**
     * Returns all the rooms that are associated with your account.
     *
     * @see https://api.nomi.ai/docs/reference/get-v1-rooms
     *
     * @return array<int, \Nomiai\PhpSdk\Resources\Room>
     */
    public function getRooms(): array
    {
        $response = $this->get('/rooms');

        return array_map(fn(array $n): Room => Room::make($n), $response['rooms']);
    }

    /**
     * Return the details of a specific room associated to your account.
     *
     * @see https://api.nomi.ai/docs/reference/get-v1-rooms-id
     */
    public function getRoom(string $id): Room
    {
        $response = $this->get("/rooms/{$id}");

        return Room::make($response);
    }

    /**
     * Create a new room, with the provided details.
     *
     * @param array<string, mixed> $request
     *
     * @throws \InvalidArgumentException
     * @throws \Nomiai\PhpSdk\Exceptions\ValidationException
     *
     * @see https://api.nomi.ai/docs/reference/post-v1-rooms
     */
    public function createRoom(array|RoomRequest $request): Room
    {
        $response = $this->post('/rooms', $this->validateUpdateData($request));

        return Room::make($response);
    }

    /**
     * Update an existing room with new information or Nomis.
     *
     * @param array<string, mixed> $update
     *
     * @throws \Nomiai\PhpSdk\Exceptions\ValidationException
     *
     * @see https://api.nomi.ai/docs/reference/put-v1-rooms-id
     */
    public function updateRoom(Room $room, array|RoomRequest $update): Room
    {
        return $this->updateRoomById($room->uuid, $update);
    }

    /**
     * Update an existing room by ID with new information or Nomis.
     *
     * @param array<string, mixed> $update
     *
     * @throws \InvalidArgumentException
     * @throws \Nomiai\PhpSdk\Exceptions\ValidationException
     *
     * @see https://api.nomi.ai/docs/reference/put-v1-rooms-id
     */
    public function updateRoomById(string $id, array|RoomRequest $update): Room
    {
        $response = $this->put("/rooms/{$id}", $this->validateUpdateData($update));

        return Room::make($response);
    }

    /**
     * Deletes the specified room associated with your account.
     *
     * @see https://api.nomi.ai/docs/reference/delete-v1-rooms-id
     */
    public function deleteRoom(Room $room): true
    {
        return $this->deleteRoomById($room->uuid);
    }

    /**
     * Deletes the specified room by its ID from your account.
     *
     * @see https://api.nomi.ai/docs/reference/delete-v1-rooms-id
     */
    public function deleteRoomById(string $id): true
    {
        $this->delete("/rooms/{$id}");

        return true;
    }

    /**
     * Sends a message to a given room.
     *
     * @throws \Nomiai\PhpSdk\Exceptions\ValidationException
     *
     * @see https://api.nomi.ai/docs/reference/post-v1-rooms-id-chat
     */
    public function sendMessageToRoom(Room $room, string $message): Message
    {
        return $this->sendMessageToRoomById($room->uuid, $message);
    }

    /**
     * Sends a message to a given room by its ID.
     *
     * @throws \Nomiai\PhpSdk\Exceptions\ValidationException
     *
     * @see https://api.nomi.ai/docs/reference/post-v1-rooms-id-chat
     */
    public function sendMessageToRoomById(string $id, string $message): Message
    {
        $length = mb_strlen($message);
        if ($length > self::MAX_ROOM_MESSAGE_LENGTH) {
            throw ValidationException::roomMessageTooLong($length, self::MAX_ROOM_MESSAGE_LENGTH);
        }

        $response = $this->post("/rooms/{$id}/chat", ['messageText' => $message]);

        return Message::make($response['sentMessage']);
    }

    /**
     * Requests a Nomi within a given room to post a message.
     *
     * @see https://api.nomi.ai/docs/reference/post-v1-rooms-id-chat-request
     */
    public function requestNomiToMessageRoom(Room $room, Nomi $nomi): Message
    {
        return $this->requestNomiByIdToMessageRoomById($room->uuid, $nomi->uuid);

    }

    /**
     * Requests a Nomi within a given room by their ID to post a message.
     *
     * @see https://api.nomi.ai/docs/reference/post-v1-rooms-id-chat-request
     */
    public function requestNomiByIdToMessageRoom(Room $room, string $nomiId): Message
    {
        return $this->requestNomiByIdToMessageRoomById($room->uuid, $nomiId);
    }

    /**
     * Requests a Nomi by their ID to post a message in a room by its ID.
     *
     * @see https://api.nomi.ai/docs/reference/post-v1-rooms-id-chat-request
     */
    public function requestNomiByIdToMessageRoomById(string $roomId, string $nomiId): Message
    {
        $response = $this->post("/rooms/{$roomId}/chat/request", ['nomiUuid' => $nomiId]);

        return Message::make($response['replyMessage']);
    }

    /**
     * Ensures that a request is not empty.
     *
     * @param array<string, mixed>|\Nomiai\PhpSdk\Requests\RoomRequest $request
     *
     * @throws \InvalidArgumentException
     * @throws \Nomiai\PhpSdk\Exceptions\ValidationException
     *
     * @return array<string, mixed>
     */
    private function validateUpdateData(array|RoomRequest $request): array
    {
        $data = $request instanceof RoomRequest
            ? $request->toArray()
            : $request;

        if ($data === []) {
            throw new InvalidArgumentException('Request cannot be empty.');
        }

        // Validate room name length
        if (isset($data['name'])) {
            $nameLength = mb_strlen($data['name']);
            if ($nameLength > self::MAX_ROOM_NAME_LENGTH) {
                throw ValidationException::roomNameTooLong($nameLength, self::MAX_ROOM_NAME_LENGTH);
            }
        }

        // Validate room note length
        if (isset($data['note'])) {
            $noteLength = mb_strlen($data['note']);
            if ($noteLength > self::MAX_ROOM_NOTE_LENGTH) {
                throw ValidationException::roomNoteTooLong($noteLength, self::MAX_ROOM_NOTE_LENGTH);
            }
        }

        return $data;
    }
}
