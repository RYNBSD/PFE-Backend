<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Http\Controllers\Api\BaseController;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StudentController extends BaseController
{
    function all(Request $request)
    {
        $user = $request->user("sanctum");
        if ($user->role !== UserRole::ADMIN || $user->role !== UserRole::OWNER) {
            return $this->sendError("Unauthorized", Response::HTTP_UNAUTHORIZED);
        }

        $students = User::all()->where("role", "=", UserRole::STUDENT);
        $students->load("student");
        return $this->sendResponse([
            "students" => $students
        ], Response::HTTP_OK);
    }
}
