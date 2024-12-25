<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Http\Controllers\Api\BaseController;
use App\Models\Group;
use App\Models\GroupMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class GroupController extends BaseController
{
    public function all()
    {
        $groups = Group::with(["group_member", "project_submit"])->get();
        return $this->sendResponse([
            "groups" => $groups
        ], Response::HTTP_OK);
    }

    public function archive()
    {
        $groups = Group::onlyTrashed()->with(["group_member", "project_submit"])->get();
        return $this->sendResponse([
            "groups" => $groups
        ], Response::HTTP_OK);
    }

    public function one(Request $request, $id)
    {
        $id = (int)$request->route("id", 0);
        if ($id <= 0) {
            return $this->sendError("Invalid group id", Response::HTTP_FORBIDDEN);
        }

        $group = Group::where("id", "=", $id)->with(["group_member", "project_submit"])->first();
        if ($group === null) {
            return $this->sendError("Group not found", Response::HTTP_NOT_FOUND);
        }

        return $this->sendResponse([
            "group" => $group
        ], Response::HTTP_OK);
    }

    public function create(Request $request)
    {
        $user = $request->user("sanctum");
        if ($user->role !== UserRole::STUDENT) {
            return $this->sendError("Unauthorized", Response::HTTP_UNAUTHORIZED);
        }

        $body = $request->input();
        $validator = Validator::make($body, [
            "student_ids" => ["required", "array", "min:2"],
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', Response::HTTP_BAD_REQUEST, $validator->errors());
        }

        $existsGroups = GroupMember::whereIn("student_id", $body["student_ids"])->get();
        if ($existsGroups->count() > 0) {
            return $this->sendError("User already in group", Response::HTTP_CONFLICT);
        }

        $group = Group::create([]);

        $newGroups = [];
        foreach ($body["student_ids"] as $studentId) {
            $newGroups[] = [
                "student_id" => $studentId,
                "group_id" => $group->id
            ];
        }
        GroupMember::insert($newGroups);

        return $this->sendResponse(null, Response::HTTP_CREATED);
    }

    public function update(Request $request, $id)
    {
        $user = $request->user("sanctum");
        if ($user->role !== UserRole::STUDENT) {
            return $this->sendError("Unauthorized", Response::HTTP_UNAUTHORIZED);
        }

        $id = (int)$request->route("id", 0);
        if ($id <= 0) {
            return $this->sendError("Invalid group id", Response::HTTP_FORBIDDEN);
        }

        $body = $request->input();
        $validator = Validator::make($body, [
            "student_ids" => ["required", "array", "min:2"],
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', Response::HTTP_BAD_REQUEST, $validator->errors());
        }

        GroupMember::where("group_id", "=", $id)->delete();

        $newGroups = [];
        foreach ($body["student_ids"] as $studentId) {
            $newGroups[] = [
                "student_id" => $studentId,
                "group_id" => $id
            ];
        }
        GroupMember::insert($newGroups);

        return $this->sendResponse(null, Response::HTTP_OK);
    }

    public function delete(Request $request)
    {
        $id = (int)$request->route("id", 0);
        if ($id <= 0) {
            return $this->sendError("Invalid group id", Response::HTTP_FORBIDDEN);
        }

        $group = Group::where("id", "=", $id)->first();
        if ($group === null) {
            return $this->sendError("Group not found", Response::HTTP_NOT_FOUND);
        }

        $group->delete();
        return $this->sendResponse(null, Response::HTTP_OK);
    }
}
