<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Http\Controllers\Api\BaseController;
use App\Models\Email;
use App\Models\EmailTemplate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EmailController extends BaseController
{
    function all(Request $request) {
        $user = $request->user("sanctum");
        if ($user->role !== UserRole::ADMIN && $user->role !== UserRole::OWNER) {
            return $this->sendError("Unauthorized", Response::HTTP_UNAUTHORIZED);
        }

        $emails = EmailTemplate::all();
        return $this->sendResponse([
            "emails" => $emails
        ], Response::HTTP_OK);
    }

    function one(Request $request) {}

    function create(Request $request) {}

    function update(Request $request) {}

    function delete(Request $request) {}
}
