<?php

namespace App\Http\Controllers;

use App\Enums\ProjectPropositionsStatus;
use App\Enums\ProjectStatus;
use App\Enums\ProjectType;
use App\Enums\UserRole;
use App\Http\Controllers\Api\BaseController;
use App\Models\Project;
use App\Models\ProjectProposition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Validation\Rule;

class ProjectController extends BaseController
{
    function all()
    {
        $projects = Project::with(["project_proposition", "project_note", "project_jury"])::all();
        return $this->sendResponse([
            "projects" => $projects
        ], Response::HTTP_OK);
    }

    function one(Request $request)
    {
        $id = (int)$request->route("id", 0);
        if ($id <= 0) {
            return $this->sendError("Invalid project id", Response::HTTP_FORBIDDEN);
        }

        $project = Project::with([])->where("id", "=", $id)->first();
        if ($project === null) {
            return $this->sendError("Project not found", Response::HTTP_NOT_FOUND);
        }

        return $this->sendResponse([
            "project" => $project
        ], Response::HTTP_OK);
    }

    function create(Request $request)
    {
        $user = $request->user("sanctum");
        if ($user->role === UserRole::STUDENT) {
            return $this->sendError("Unauthorized", Response::HTTP_UNAUTHORIZED);
        }

        $body = $request->all();
        $validator = Validator::make($body, [
            "title" => "required|string|max:255",
            "description" => "required|string",
            "status" => ["required", "string", Rule::enum(ProjectStatus::class)]
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', Response::HTTP_BAD_REQUEST, $validator->errors());
        }

        $project = Project::create([
            "title" => $body["title"],
            "description" => $body["description"],
            "type" => $body["type"],
            "status" => ProjectStatus::PROPOSED,
        ]);
        ProjectProposition::create([
            "user_id" => $user->id,
            "project_id" => $project->id,
            "status" => ProjectPropositionsStatus::PENDING
        ]);

        $this->sendResponse(null, Response::HTTP_CREATED);
    }

    function update(Request $request)
    {
        $id = (int)$request->route("id", 0);
        if ($id <= 0) {
            return $this->sendError("Invalid project id", Response::HTTP_FORBIDDEN);
        }

        $project = Project::where("id", "=", $id)->first();
        if ($project === null) {
            return $this->sendError("Project not found", Response::HTTP_NOT_FOUND);
        }
        if ($project->status === ProjectStatus::ASSIGNED || $project->status === ProjectStatus::COMPLETED) {
            return $this->sendError("Can't update", Response::HTTP_FORBIDDEN);
        }

        $user = $request->user("sanctum");
        $projectProposition = ProjectProposition::where("user_id", "=", $user->id)->where("project_id", "=", $id)->first();
        if ($projectProposition === null) {
            return $this->sendError("Not found", Response::HTTP_NOT_FOUND);
        }

        $body = $request->all();
        $validator = Validator::make($body, [
            "title" => "required|string|max:255",
            "description" => "required|string",
            "type" => ["required", "string", Rule::enum(ProjectType::class)],
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', Response::HTTP_BAD_REQUEST, $validator->errors());
        }

        Project::where("id", "=", $id)->update([
            "title" => $body["title"],
            "description" => $body["description"],
            "type" => $body["type"],
        ]);
        $projectProposition->update([
            "status" => ProjectPropositionsStatus::PENDING
        ]);

        return $this->sendResponse(null, Response::HTTP_OK);
    }


    function delete(Request $request)
    {
        $id = (int)$request->route("id", 0);
        if ($id <= 0) {
            return $this->sendError("Invalid project id", Response::HTTP_FORBIDDEN);
        }

        $user = $request->user("sanctum");
        $projectProposition = ProjectProposition::where("user_id", "=", $user->id)->where("project_id", "=", $id)->first();
        if ($projectProposition === null) {
            return $this->sendError("Not found", Response::HTTP_NOT_FOUND);
        }

        $project = Project::find($id);
        if (!isset($project)) {
            return $this->sendError("Project not found", Response::HTTP_NOT_FOUND);
        }

        $project->delete();
        return $this->sendResponse(null, Response::HTTP_OK);
    }

    // ProjectProposition

    function getValidated(Request $request) {

    }

    function validate(Request $request)
    {
        $user = $request->user("sanctum");
        if ($user->role !== UserRole::ADMIN && $user->role !== UserRole::OWNER) {
            return $this->sendError("Unauthorized", Response::HTTP_UNAUTHORIZED);
        }

        $id = (int)$request->route("id", 0);
        if ($id <= 0) {
            return $this->sendError("Invalid project id", Response::HTTP_FORBIDDEN);
        }

        $project = Project::where("id", "=", $id)->first();
        if ($project === null) {
            return $this->sendError("Project not found", Response::HTTP_NOT_FOUND);
        }

        $projectProposition = ProjectProposition::where("project_id", "=", $project->id)->first();
        if ($projectProposition->status !== ProjectPropositionsStatus::PENDING) {
            return $this->sendError("Project can\'t be validated", Response::HTTP_FORBIDDEN);
        }


        $project->update([
            "status" => ProjectStatus::APPROVED,
        ]);
        $projectProposition->update([
            "status" => ProjectPropositionsStatus::VALIDATED,
            "validated_by" => $user->id
        ]);

        return $this->sendResponse(null, Response::HTTP_OK);
    }

    function reject(Request $request)
    {
        $id = (int)$request->route("id", 0);
        if ($id <= 0) {
            return $this->sendError("Invalid project id", Response::HTTP_FORBIDDEN);
        }

        $body = $request->all();
        $validator = Validator::make($body, [
            "feedback" => "required|string|max:255",
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', Response::HTTP_BAD_REQUEST, $validator->errors());
        }

        $project = Project::where("id", "=", $id)->first();
        if ($project === null) {
            return $this->sendError("Project not found", Response::HTTP_NOT_FOUND);
        }
        if ($project->status !== ProjectStatus::PROPOSED) {
            return $this->sendError("Project can\'t be rejected", Response::HTTP_FORBIDDEN);
        }

        $project->update([
            "status" => ProjectStatus::PROPOSED
        ]);
        ProjectProposition::where("project_id", "=", $project->id)->update([
            "status" => ProjectPropositionsStatus::REJECTED
        ]);
    }
}
