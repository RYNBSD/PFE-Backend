<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Http\Controllers\Api\BaseController;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends BaseController
{
    function all(Request $request)
    {
        $user = $request->user("sanctum");
        if ($user->role !== UserRole::ADMIN || $user->role !== UserRole::OWNER) {
            return $this->sendError("Unauthorized", Response::HTTP_UNAUTHORIZED);
        }

        $admins = User::with(["admin"])->where("role", "=", UserRole::ADMIN)->get();
        return $this->sendResponse([
            "admins" => $admins
        ], Response::HTTP_OK);
    }


    function create(Request $request)
    {
        $user = $request->user("sanctum");
        if ($user->role !== UserRole::OWNER) {
            return $this->sendError("Unauthorized", Response::HTTP_UNAUTHORIZED);
        }

        $body = $request->all();
        $validator = Validator::make($body, [
            "user_id" =>  ["required", "numeric"]
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', Response::HTTP_BAD_REQUEST, $validator->errors());
        }

        Admin::create([
            "user_id" => $body["user_id"]
        ]);
        return $this->sendResponse(null, Response::HTTP_CREATED);
    }

    function delete(Request $request)
    {
        $user = $request->user("sanctum");
        if ($user->role !== UserRole::OWNER) {
            return $this->sendError("Unauthorized", Response::HTTP_UNAUTHORIZED);
        }

        $body = $request->all();
        $validator = Validator::make($body, [
            "user_id" =>  ["required", "numeric"]
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', Response::HTTP_BAD_REQUEST, $validator->errors());
        }

        $admin = Admin::find($body["user_id"])->first();
        if ($admin === null) {
            return $this->sendError("Admin not found", Response::HTTP_NOT_FOUND);
        }
        $admin->delete();
        return $this->sendResponse(null, Response::HTTP_CREATED);
    }
}
