<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Http\Controllers\Api\BaseController;
use App\Models\Room;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoomController extends BaseController
{
    function all(Request $request)
    {
        $user = $request->user("sanctum");
        if ($user->role !== UserRole::ADMIN && $user->role !== UserRole::OWNER) {
            return $this->sendError("Unauthorized", Response::HTTP_UNAUTHORIZED);
        }

        $rooms = Room::all();
        return $this->sendResponse([
            "rooms" => $rooms
        ], Response::HTTP_OK);
    }

    function one(Request $request)
    {
        $user = $request->user("sanctum");
        if ($user->role !== UserRole::ADMIN && $user->role !== UserRole::OWNER) {
            return $this->sendError("Unauthorized", Response::HTTP_UNAUTHORIZED);
        }

        $id = (int)$request->route("id", 0);
        if ($id <= 0) {
            return $this->sendError("Invalid room id", Response::HTTP_FORBIDDEN);
        }

        $room = Room::where("id", "=", $id)->first();
        if ($room === null) {
            return $this->sendError("Room not found", Response::HTTP_NOT_FOUND);
        }

        return $this->sendResponse([
            "room" => $room
        ], Response::HTTP_OK);
    }

    function create(Request $request)
    {
        $user = $request->user("sanctum");
        if ($user->role !== UserRole::ADMIN && $user->role !== UserRole::OWNER) {
            return $this->sendError("Unauthorized", Response::HTTP_UNAUTHORIZED);
        }
    }

    function update(Request $request)
    {
        $user = $request->user("sanctum");
        if ($user->role !== UserRole::ADMIN && $user->role !== UserRole::OWNER) {
            return $this->sendError("Unauthorized", Response::HTTP_UNAUTHORIZED);
        }

        $id = (int)$request->route("id", 0);
        if ($id <= 0) {
            return $this->sendError("Invalid room id", Response::HTTP_FORBIDDEN);
        }

        $room = Room::where("id", "=", $id)->first();
        if ($room === null) {
            return $this->sendError("Room not found", Response::HTTP_NOT_FOUND);
        }
    }

    function delete(Request $request)
    {
        $user = $request->user("sanctum");
        if ($user->role !== UserRole::ADMIN && $user->role !== UserRole::OWNER) {
            return $this->sendError("Unauthorized", Response::HTTP_UNAUTHORIZED);
        }

        $id = (int)$request->route("id", 0);
        if ($id <= 0) {
            return $this->sendError("Invalid room id", Response::HTTP_FORBIDDEN);
        }

        $room = Room::where("id", "=", $id)->first();
        if ($room === null) {
            return $this->sendError("Room not found", Response::HTTP_NOT_FOUND);
        }

        $room->delete();
        return $this->sendResponse(null, Response::HTTP_OK);
    }
}
