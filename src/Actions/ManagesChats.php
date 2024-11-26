<?php

declare(strict_types=1);

namespace Nomiai\PhpSdk\Actions;

use Nomiai\PhpSdk\Resources\MessageSet;

/** @mixin \Nomiai\PhpSdk\Traits\MakesHttpRequests */
trait ManagesChats
{
    /**
     * Send a message to a given Nomi.
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \JsonException
     */
    public function sendMessage(string $id, string $message): MessageSet
    {
        $response = $this->post("/v1/chats/{$id}", ['messageText' => $message]);

        return MessageSet::make($response);
    }
}
