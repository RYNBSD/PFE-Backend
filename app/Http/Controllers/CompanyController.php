<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Http\Controllers\Api\BaseController;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CompanyController extends BaseController
{
    function all(Request $request)
    {
        $user = $request->user("sanctum");
        if ($user->role !== UserRole::ADMIN || $user->role !== UserRole::OWNER) {
            return $this->sendError("Unauthorized", Response::HTTP_UNAUTHORIZED);
        }

        $companies = User::with(["company"])->where("role", "=", UserRole::COMPANY)->get();
        return $this->sendResponse([
            "companies" => $companies
        ], Response::HTTP_OK);
    }
}
