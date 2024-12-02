<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    public function sendResponse(mixed $data, int $code)
    {
        return response()->json([
            "ok" => true,
            "data" => $data
        ], $code);
    }

    public function sendError(string $message, int $code, mixed $reasons = [])
    {
        return response()->json([
            "ok" => false,
            "message" => $message,
            "reasons" => $reasons
        ], $code);
    }
}
