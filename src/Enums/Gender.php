<?php

declare(strict_types=1);

namespace Nomiai\PhpSdk\Enums;

enum Gender: string
{
    case MALE = 'Male';
    case FEMALE = 'Female';
    case NON_BINARY = 'Non Binary';
}
