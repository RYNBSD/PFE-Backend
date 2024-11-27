<?php

namespace App\Enums;

enum UserRole: string
{
    use EnumToArray;
    case STUDENT = "student";
    case TEACHER = "teacher";
    case COMPANY = "company";
    case ADMIN = "admin";
    case OWNER = "owner";
}
