<?php

declare(strict_types=1);

namespace Nomiai\PhpSdk\Actions;

use Nomiai\PhpSdk\Exceptions\ValidationException;
use Nomiai\PhpSdk\Resources\MessageSet;
use Nomiai\PhpSdk\Resources\Nomi;

/** @mixin \Nomiai\PhpSdk\Traits\MakesHttpRequests */
trait ManagesChats
{
    /**
     * Maximum message length for Nomi chats (800 for subscribed, 400 for free).
     */
    private const int MAX_MESSAGE_LENGTH = 800;

    /**
     * Send a message to a given Nomi.
     *
     * @throws \Nomiai\PhpSdk\Exceptions\ValidationException
     *
     * @see https://api.nomi.ai/docs/reference/post-v1-nomis-id-chat
     */
    public function sendMessageToNomi(Nomi $nomi, string $message): MessageSet
    {
        return $this->sendMessage($nomi->uuid, $message);
    }

    /**
     * Send a message to a given ID.
     *
     * @throws \Nomiai\PhpSdk\Exceptions\ValidationException
     *
     * @see https://api.nomi.ai/docs/reference/post-v1-nomis-id-chat
     */
    public function sendMessage(string $id, string $message): MessageSet
    {
        $this->validateMessageLength($message, self::MAX_MESSAGE_LENGTH);

        $response = $this->post("/nomis/{$id}/chat", ['messageText' => $message]);

        return MessageSet::make($response);
    }

    /**
     * Validates that a message does not exceed the maximum length.
     *
     * @throws \Nomiai\PhpSdk\Exceptions\ValidationException
     */
    private function validateMessageLength(string $message, int $maxLength): void
    {
        $length = mb_strlen($message);

        if ($length > $maxLength) {
            throw ValidationException::nomiMessageTooLong($length, $maxLength);
        }
    }
}
