<?php

namespace App\Enums;

use App\Enums\EnumToArray;

enum ProjectPropositionsStatus: string
{
    use EnumToArray;
    case VALIDATED = "validated";
    case REJECTED = "rejected";
    case PENDING = "pending";
}
