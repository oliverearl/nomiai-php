<?php

declare(strict_types=1);

namespace Nomiai\PhpSdk\Enums;

enum RelationshipType: string
{
    case MENTOR = 'Mentor';
    case FRIEND = 'Friend';
    case ROMANTIC = 'Romantic';
}
