<?php

namespace App\Enums;

enum ProjectJuriesRole: string
{
    use EnumToArray;
    case SUPERVISOR = "supervisor";
    case EXAMINER = "examiner";
    case PRESIDENT = "president";
}
