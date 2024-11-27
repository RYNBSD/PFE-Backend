<?php

namespace App\Enums;

enum StudentMajor: string
{
    use EnumToArray;
    case GL = "gl";
    case IA = "ia";
    case SIC = "sic";
    case RSD = "rsd";
}
