<?php

namespace App\Enums;

enum TeacherGrade: string
{
    use EnumToArray;
    case MAA = "maa";
    case MAB = "mab";
    case MCA = "mca";
    case MCB = "mcb";
    case PROFESSOR = "professor";
}
