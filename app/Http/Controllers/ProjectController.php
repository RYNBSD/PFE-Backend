<?php

namespace App\Http\Controllers;

use App\Enums\ProjectPropositionsStatus;
use App\Enums\ProjectStatus;
use App\Enums\ProjectType;
use App\Enums\UserRole;
use App\Http\Controllers\Api\BaseController;
use App\Models\Project;
use App\Models\ProjectProposition;
use App\Models\ProjectPropositionFeedback;
use App\Models\ProjectRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Validation\Rule;

class ProjectController extends BaseController
{
    function all()
    {
        $projects = Project::with(["project_proposition", "project_note", "project_jury"])->get();
        return $this->sendResponse([
            "projects" => $projects
        ], Response::HTTP_OK);
    }

    function archive()
    {
        $projects = Project::onlyTrashed()->with(["project_proposition", "project_note", "project_jury"])->get();
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

        $project = Project::with(["project_proposition", "project_note", "project_jury"])->where("id", "=", $id)->first();
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
        // if ($user->role === UserRole::STUDENT) {
        //     return $this->sendError("Unauthorized", Response::HTTP_UNAUTHORIZED);
        // }

        $body = $request->all();
        $validator = Validator::make($body, [
            "title" => "required|string|max:255",
            "description" => "required|string",
            "type" => ["required", "string", Rule::enum(ProjectType::class)],
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
            return $this->sendError("Project Not found", Response::HTTP_NOT_FOUND);
        }

        $project = Project::find($id)->first();
        if (!isset($project)) {
            return $this->sendError("Project not found", Response::HTTP_NOT_FOUND);
        }

        $project->delete();
        return $this->sendResponse(null, Response::HTTP_OK);
    }

    // ProjectProposition

    function validate(Request $request)
    {
        $user = $request->user("sanctum");
        if ($user->role !== UserRole::ADMIN && $user->role !== UserRole::OWNER) {
            return $this->sendError("Unauthorized", Response::HTTP_UNAUTHORIZED);
        }

        $body = $request->all();
        $validator = Validator::make($body, [
            "registration_start" => "required|string|date",
            "registration_end" => "required|string|date",
            "presentation_start" => "required|string|date",
            "presentation_end" => "required|string|date",
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', Response::HTTP_BAD_REQUEST, $validator->errors());
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

        $project->update([
            "status" => ProjectStatus::APPROVED,
        ]);
        $projectProposition->update([
            "status" => ProjectPropositionsStatus::VALIDATED,
            "validated_by" => $user->id
        ]);
        ProjectRegistration::create([
            "project_id" => $project->id,
            "start_date" => $body["registration_start"],
            "end_date" => $body["registration_end"]
        ]);

        return $this->sendResponse(null, Response::HTTP_OK);
    }

    function reject(Request $request)
    {
        $user = $request->user("sanctum");
        if ($user->role !== UserRole::ADMIN && $user->role !== UserRole::OWNER) {
            return $this->sendError("Unauthorized", Response::HTTP_UNAUTHORIZED);
        }

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

        $projectProposition = ProjectProposition::where("project_id", "=", $project->id)->first();
        if ($projectProposition->status === ProjectPropositionsStatus::VALIDATED) {
            return $this->sendError("Project already validated", Response::HTTP_FORBIDDEN);
        }

        $projectProposition->update([
            "status" => ProjectPropositionsStatus::REJECTED
        ]);
        ProjectPropositionFeedback::create([
            "project_proposition_id" => $projectProposition->id,
            "feedback" => $body["feedback"]
        ]);
        return $this->sendResponse(null, Response::HTTP_OK);
    }
}
