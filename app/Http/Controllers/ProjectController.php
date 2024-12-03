<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\BaseController;
use App\Models\Project;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProjectController extends BaseController
{
    function all()
    {
        $projects = Project::with([])::all();
        return $this->sendResponse([
            "projects" => $projects
        ], Response::HTTP_OK);
    }


    function delete(Request $request)
    {
        $id = $request->route("id", 0);
        if ($id <= 0) {
            return $this->sendError("Invalid project id", Response::HTTP_BAD_REQUEST);
        }

        $project = Project::find($id);
        if (!isset($project)) {
            return $this->sendError("Project not found", Response::HTTP_NOT_FOUND);
        }

        $project->delete();
        return $this->sendResponse(null, Response::HTTP_OK);
    }
}
