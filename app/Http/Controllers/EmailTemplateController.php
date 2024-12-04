<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Http\Controllers\Api\BaseController;
use App\Models\EmailTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class EmailTemplateController extends BaseController
{
    function all(Request $request)
    {
        $user = $request->user("sanctum");
        if ($user->role !== UserRole::ADMIN && $user->role !== UserRole::OWNER) {
            return $this->sendError("Unauthorized", Response::HTTP_UNAUTHORIZED);
        }

        $emailTemplates = EmailTemplate::all();
        return $this->sendResponse([
            "email_templates" => $emailTemplates,
        ], Response::HTTP_OK);
    }

    function one(Request $request)
    {
        $user = $request->user("sanctum");
        if ($user->role !== UserRole::ADMIN && $user->role !== UserRole::OWNER) {
            return $this->sendError("Unauthorized", Response::HTTP_UNAUTHORIZED);
        }

        $id = (int)$request->route("id", 0);
        if ($id < 0) {
            return $this->sendError("Invalid email id", Response::HTTP_FORBIDDEN);
        }

        $emailTemplate = EmailTemplate::where("id", "=", $id)->fist();
        if ($emailTemplate === null) {
            return $this->sendError("Email template not found", Response::HTTP_NOT_FOUND);
        }

        return $this->sendResponse([
            "email_template" => $emailTemplate
        ], Response::HTTP_OK);
    }

    function create(Request $request)
    {
        $user = $request->user("sanctum");
        if ($user->role !== UserRole::ADMIN && $user->role !== UserRole::OWNER) {
            return $this->sendError("Unauthorized", Response::HTTP_UNAUTHORIZED);
        }

        $body = $request->all();
        $validator = Validator::make($body, [
            "subject" => ["required", "string", "max:255"],
            "content" => ["required", "string"],
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', Response::HTTP_BAD_REQUEST, $validator->errors());
        }

        EmailTemplate::create([
            "subject" => $body["subject"],
            "content" => $body["content"]
        ]);
        return $this->sendResponse(null, Response::HTTP_OK);
    }

    function update(Request $request)
    {
        $user = $request->user("sanctum");
        if ($user->role !== UserRole::ADMIN && $user->role !== UserRole::OWNER) {
            return $this->sendError("Unauthorized", Response::HTTP_UNAUTHORIZED);
        }

        $id = (int)$request->route("id", 0);
        if ($id < 0) {
            return $this->sendError("Invalid email id", Response::HTTP_FORBIDDEN);
        }

        $emailTemplate = EmailTemplate::where("id", "=", $id)->first();
        if ($emailTemplate === null) {
            return $this->sendError("Email template not found", Response::HTTP_NOT_FOUND);
        }

        $body = $request->all();
        $validator = Validator::make($body, [
            "subject" => ["required", "string", "max:255"],
            "content" => ["required", "string"],
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', Response::HTTP_BAD_REQUEST, $validator->errors());
        }

        $emailTemplate->update([
            "subject" => $body["subject"],
            "content" => $body["content"]
        ]);
        return $this->sendResponse(null, Response::HTTP_OK);
    }

    function delete(Request $request)
    {
        $user = $request->user("sanctum");
        if ($user->role !== UserRole::ADMIN && $user->role !== UserRole::OWNER) {
            return $this->sendError("Unauthorized", Response::HTTP_UNAUTHORIZED);
        }

        $id = (int)$request->route("id", 0);
        if ($id < 0) {
            return $this->sendError("Invalid email id", Response::HTTP_FORBIDDEN);
        }

        $emailTemplate = EmailTemplate::where("id", "=", $id)->fist();
        if ($emailTemplate === null) {
            return $this->sendError("Email template not found", Response::HTTP_NOT_FOUND);
        }

        $emailTemplate->delete();
        return $this->sendResponse(null, Response::HTTP_OK);
    }
}
