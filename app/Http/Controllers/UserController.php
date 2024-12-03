<?php

namespace App\Http\Controllers;

use App\Enums\StudentMajor;
use App\Enums\TeacherGrade;
use App\Enums\UserRole;
use App\Http\Controllers\Api\BaseController;
use App\Models\Company;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

class UserController extends BaseController
{
    function all(Request $request)
    {
        $user = $request->user("sanctum");
        if ($user->role !== UserRole::ADMIN || $user->role !== UserRole::OWNER) {
            return $this->sendError("Unauthorized", Response::HTTP_UNAUTHORIZED);
        }

        $users = User::all();
        $users->load("student", "teacher", "company", "admin");
        return $this->sendResponse([
            "users" => $users
        ], Response::HTTP_OK);
    }

    function profile(Request $request)
    {
        $user = $request->user("sanctum");
        $user->load("student", "teacher", "company", "admin");
        return $this->sendResponse([
            "user" => $user
        ], Response::HTTP_OK);
    }

    function create(Request $request)
    {
        $user = $request->user("sanctum");
        // if ($user->role !== UserRole::ADMIN || $user->role !== UserRole::OWNER) {
        //     return $this->sendError("Unauthorized", Response::HTTP_UNAUTHORIZED);
        // }

        $body = $request->all();
        $validator = Validator::make($body, [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'role' => ['required', Rule::enum(UserRole::class)],
            'email' => 'required|email|max:255',
            'password' => ["nullable", "string", "max:255"],
            'password_confirmation' => 'same:password',
            // Student
            "student_major" => ["nullable", "string", "max:255", Rule::enum(StudentMajor::class)],
            "student_average_score" => ["nullable", "numeric"],
            // Teacher
            "teacher_recruitment_date" => ["nullable", "date"],
            "teacher_grade" => ["nullable", "string", "max:255", Rule::enum(TeacherGrade::class)],
            // Company
            "company_name" => ["nullable", "string", "max:255"],
            "company_number" => ["nullable", "string", "max:255"],
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', Response::HTTP_BAD_REQUEST, $validator->errors());
        }

        $body["password"] ??= null;
        if ($body["password"] !== null && strlen($body["password"]) > 0) {
            $body["password"] = Hash::make($body["password"]);
        }

        $createdUser = User::create([
            'first_name' => $body["first_name"],
            'last_name' => $body["last_name"],
            'role' => $body["role"],
            'email' => $body["email"],
            'password' => $body["password"],
        ]);
        if ($createdUser->role === UserRole::STUDENT) {
            Student::create([
                "major" => $body["student_major"],
                "average_score" => $body["student_average_score"],
            ]);
        } else if ($createdUser->role === UserRole::TEACHER) {
            Teacher::create([
                "grade" => $body["teacher_grade"],
                "recruitment_date" => $body["teacher_recruitment_date"],
            ]);
        } else if ($createdUser->role === UserRole::COMPANY) {
            Company::create([
                "name" => $body["company_name"],
                "number" => $body["company_number"],
            ]);
        }

        return $this->sendResponse(null, Response::HTTP_CREATED);
    }

    function update(Request $request)
    {
        $body = $request->all();
        $validator = Validator::make($body, [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            // Student
            "student_major" => ["nullable", "string", "max:255", Rule::enum(StudentMajor::class)],
            "student_average_score" => ["nullable", "numeric"],
            // Teacher
            "teacher_recruitment_date" => ["nullable", "date"],
            "teacher_grade" => ["nullable", "string", "max:255", Rule::enum(TeacherGrade::class)],
            // Company
            "company_name" => ["nullable", "string", "max:255"],
            "company_number" => ["nullable", "string", "max:255"],
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', Response::HTTP_BAD_REQUEST, $validator->errors());
        }

        $user = $request->user("sanctum");
        User::where("id", "=", $user->id)->update([
            'first_name' => $body["first_name"],
            'last_name' => $body["last_name"],
        ]);
        if ($user->role === UserRole::STUDENT) {
            Student::where("user_id", "=", $user->id)->update([
                "major" => $body["student_major"],
                "average_score" => $body["student_average_score"],
            ]);
        } else if ($user->role === UserRole::TEACHER) {
            Teacher::where("user_id", "=", $user->id)->update([
                "grade" => $body["teacher_grade"],
                "recruitment_date" => $body["teacher_recruitment_date"],
            ]);
        } else if ($user->role === UserRole::COMPANY) {
            Company::where("user_id", "=", $user->id)->update([
                "name" => $body["company_name"],
                "number" => $body["company_number"],
            ]);
        }


        return $this->sendResponse(null, Response::HTTP_OK);
    }

    function delete(Request $request)
    {
        $user = $request->user("sanctum");
        $user->delete();
        return $this->sendResponse(null, Response::HTTP_OK);
    }
}
