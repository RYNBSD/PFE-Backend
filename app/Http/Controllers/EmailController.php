<?php

namespace App\Http\Controllers;

use App\Enums\EmailStatus;
use App\Enums\UserRole;
use App\Http\Controllers\Api\BaseController;
use App\Models\Email;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class EmailController extends BaseController
{
    function all(Request $request)
    {
        $user = $request->user("sanctum");
        if ($user->role !== UserRole::ADMIN && $user->role !== UserRole::OWNER) {
            return $this->sendError("Unauthorized", Response::HTTP_UNAUTHORIZED);
        }

        $emails = Email::with(["user", "admin"])->all();
        return $this->sendResponse([
            "emails" => $emails
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

        $email = Email::with(["user", "admin"])->where("id", "=", $id)->first();
        if ($email === null) {
            return $this->sendError("Email not found", Response::HTTP_NOT_FOUND);
        }

        return $this->sendResponse([
            "email" => $email
        ], Response::HTTP_OK);
    }

    function send(Request $request)
    {
        $user = $request->user("sanctum");
        if ($user->role !== UserRole::ADMIN && $user->role !== UserRole::OWNER) {
            return $this->sendError("Unauthorized", Response::HTTP_UNAUTHORIZED);
        }

        $body = $request->all();
        $validator = Validator::make($body, [
            "to" => ["required", "string", "email", "max:255"],
            "subject" => ["required", "string", "max:255"],
            "content" => ["required", "string"],
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', Response::HTTP_BAD_REQUEST, $validator->errors());
        }

        $receiver = User::where("email", "=", $body["to"])->first();
        if ($receiver === null || $receiver === $user->email) {
            return $this->sendError("Invalid receiver", Response::HTTP_FORBIDDEN);
        }

        $mailable = new Mailable();
        $mailable->to($body["to"])->subject($body["subject"])->html($body["content"]);

        $isSent = false;
        try {
            Mail::send($mailable);
            $isSent = true;
        } catch (Exception $e) {
            $isSent = false;
        }

        Email::create([
            "subject" => $body["subject"],
            "content" => $body["content"],
            "status" => EmailStatus::SENT,
            "receiver_id" => $receiver->id,
            "admin_id" => $user->id,
        ]);
        return $this->sendResponse(null, Response::HTTP_CREATED);
    }
}
