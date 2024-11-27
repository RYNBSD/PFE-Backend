<?php

use App\Enums\EnumToArray;

enum EmailStatus: string
{
    use EnumToArray;

    case SENT = "sent";
    case PENDING = "pending";
    const FAILED = "failed";
}
