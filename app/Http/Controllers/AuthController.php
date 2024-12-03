<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\BaseController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends BaseController
{
    function register(Request $request)
    {
        if (!app()->hasDebugModeEnabled()) {
            return $this->sendError("Unauthorized", Response::HTTP_UNAUTHORIZED);
        }
        $body = $request->all();
        $validator = Validator::make($body, [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'role' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string',
            'password_confirmation' => 'required|same:password',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', Response::HTTP_BAD_REQUEST, $validator->errors());
        }
        $body['password'] = Hash::make($body['password']);
        $user = User::create($body);
        return $this->sendResponse([
            "token" => $user->createToken('MyApp')->plainTextToken,
            "user" => $user
        ], Response::HTTP_CREATED);
    }

    function login(Request $request)
    {
        $body = $request->all();
        $validator = Validator::make($body, [
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
            ],
            'password' => [
                'nullable',
                'string',
                'max:255',
            ],
            'remember' => [
                'boolean',
            ],
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', Response::HTTP_BAD_REQUEST, $validator->errors());
        }
        $body["remember"] ??= false;

        // one time password
        if (!isset($body["password"])) {
            $user = User::where("email", $body["email"])->first();
            if (!isset($user)) {
                return $this->sendError("User not found", Response::HTTP_NOT_FOUND);
            }

            $code = Str::password(6, true, true, false, false);

            $mailable = new Mailable();
            $mailable->to($body["email"])->subject("One time password code")->html("Code: " . $code);

            Mail::send($mailable);
            return $this->sendResponse([
                "code" => Crypt::encryptString($code),
            ], Response::HTTP_OK);
        }

        if (Auth::attempt(["email" => $body["email"], "password" => $body["password"]], $body["remember"])) {
            $user = Auth::user();
            $user->load("student", "teacher", "company", "admin");
            return $this->sendResponse([
                "token" => $user->createToken('MyApp')->plainTextToken,
                "user" => $user
            ], Response::HTTP_OK);
        }
        return $this->sendError("Unauthorized", Response::HTTP_UNAUTHORIZED);
    }

    function logout(Request $request)
    {
        $request?->user()?->currentAccessToken()?->delete();
        return $this->sendResponse(null, Response::HTTP_OK);
    }

    function oneTimePassword(Request $request)
    {
        $body = $request->all();
        $validator = Validator::make($body, [
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
            ],
            "code" => ["required", "string"],
            "encrypted_code" => ["required", "string"]
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', Response::HTTP_BAD_REQUEST, $validator->errors());
        }

        $user = User::where("email", $body["email"])->first();
        if (!isset($user)) {
            return $this->sendError("User not found", Response::HTTP_NOT_FOUND);
        }
        $user->load("student", "teacher", "company", "admin");

        $code = Crypt::decryptString($body["encrypted_code"]);
        if ($code !== $body["code"]) {
            return $this->sendError("Invalid code.", Response::HTTP_FORBIDDEN);
        }

        return $this->sendResponse([
            "token" => $user->createToken('MyApp')->plainTextToken,
            "user" => $user
        ], Response::HTTP_OK);
    }

    function forgetPassword(Request $request)
    {
        $body = $request->all();
        $validator = Validator::make($body, [
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
            ],
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', Response::HTTP_BAD_REQUEST, $validator->errors());
        }

        $user = User::where("email", $body["email"])->first();
        if (!isset($user)) {
            return $this->sendError("User not found", Response::HTTP_NOT_FOUND);
        }

        $code = Str::password(6, true, true, false, false);

        $mailable = new Mailable();
        $mailable->to($body["email"])->subject("Forgot password code")->html("Code: " . $code);

        Mail::send($mailable);
        return $this->sendResponse([
            "code" => Crypt::encryptString($code),
        ], Response::HTTP_OK);
    }

    function resetPassword(Request $request)
    {
        $body = $request->all();
        $validator = Validator::make($body, [
            'email' => ["required", "email", "max:255"],
            'password' => ["required", "string", "max:255"],
            'password_confirmation' => ["required", "string", "same:password"],
            "code" => ["required", "string"],
            "encrypted_code" => ["required", "string"]
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', Response::HTTP_BAD_REQUEST, $validator->errors());
        }

        $user = User::where("email", $body["email"])->first();
        if (!isset($user)) {
            return $this->sendError("User not found", Response::HTTP_NOT_FOUND);
        }

        $code = Crypt::decryptString($body["encrypted_code"]);
        if ($code !== $body["code"]) {
            return $this->sendError("Invalid code.", Response::HTTP_FORBIDDEN);
        }

        User::where("email", $body["email"])->update(["password" => Hash::make($body["password"])]);

        return $this->sendResponse(null, Response::HTTP_OK);
    }

    function status(Request $request)
    {
        $user = $request->user('sanctum');
        $user?->load("student", "teacher", "company", "admin");
        return $this->sendResponse(["user" => $user], isset($user) ? Response::HTTP_OK : Response::HTTP_UNAUTHORIZED);
    }
}
