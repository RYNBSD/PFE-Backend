<?php

namespace App\Enums;

enum ProjectStatus: string
{
    use EnumToArray;
    case PROPOSED = "proposed";
    case APPROVED = "approved";
    case ASSIGNED = "assigned";
    case COMPLETED = "completed";
}
