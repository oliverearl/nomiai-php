<?php

declare(strict_types=1);

namespace Nomiai\PhpSdk\Actions;

use Nomiai\PhpSdk\Constants\HttpMethod;
use Nomiai\PhpSdk\Resources\Avatar;
use Nomiai\PhpSdk\Resources\Nomi;

/** @mixin \Nomiai\PhpSdk\Traits\MakesHttpRequests */
trait ManagesAvatars
{
    /**
     * Retrieves the current avatar from a given Nomi.
     */
    public function getAvatarFromNomi(Nomi $nomi): Avatar
    {
        return $this->getAvatar($nomi->uuid);
    }

    /**
     * Retrieves the current avatar from a given Nomi ID.
     */
    public function getAvatar(string $id): Avatar
    {
        $data = $this->rawRequest(HttpMethod::GET, "/v1/nomis/{$id}/avatar");

        return Avatar::make([$data]);
    }
}
