<?php

declare(strict_types=1);

namespace Nomiai\PhpSdk\Enums;

enum RoomStatus: string
{
    case CREATING = 'Creating';
    case DEFAULT = 'Default';
    case WAITING = 'Waiting';
    case TYPING = 'Typing';
    case INITIAL_NOTE_ERROR = 'InitialNoteError';
    case MANUAL = 'Manual';
}
