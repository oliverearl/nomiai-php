<?php

declare(strict_types=1);

namespace Nomiai\PhpSdk\Actions;

use Nomiai\PhpSdk\Resources\MessageSet;
use Nomiai\PhpSdk\Resources\Nomi;

/** @mixin \Nomiai\PhpSdk\Traits\MakesHttpRequests */
trait ManagesChats
{
    /**
     * Send a message to a given Nomi.
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
     * @see https://api.nomi.ai/docs/reference/post-v1-nomis-id-chat
     */
    public function sendMessage(string $id, string $message): MessageSet
    {
        $response = $this->post("/v1/nomis/{$id}/chat", ['messageText' => $message]);

        return MessageSet::make($response);
    }
}
