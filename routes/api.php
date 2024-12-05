<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\EmailTemplateController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::prefix("auth")->name("auth.")->group(function () {
    Route::post("/register", [AuthController::class, "register"])->name("register");
    Route::post("/login", [AuthController::class, "login"])->name("login");
    Route::post("/logout", [AuthController::class, "logout"])->name("logout")->middleware('auth:sanctum');
    Route::post("/one-time-password", [AuthController::class, "oneTimePassword"])->name("one-time-password");
    Route::post("/forget-password", [AuthController::class, "forgetPassword"])->name("forget-password");
    Route::post("/reset-password", [AuthController::class, "resetPassword"])->name("reset-password");
    Route::get("/status", [AuthController::class, "status"])->name("status");
});

Route::prefix("user")->name("user.")->group(function () {
    Route::get("/all", [UserController::class, "all"])->name("all");
    Route::get("/", [UserController::class, "profile"])->name("profile");
    Route::post("/", [UserController::class, "create"])->name("create");
    Route::put("/", [UserController::class, "update"])->name("update");
    Route::delete("/", [UserController::class, "delete"])->name("delete");
})->middleware('auth:sanctum');

Route::prefix("student")->name("student.*")->group(function () {
    Route::get("/all", [StudentController::class, "all"])->name("all");
})->middleware('auth:sanctum');

Route::prefix("teacher")->name("teacher.*")->group(function () {
    Route::get("/all", [TeacherController::class, "all"])->name("all");
})->middleware('auth:sanctum');

Route::prefix("company")->name("company.*")->group(function () {
    Route::get("/all", [CompanyController::class, "all"])->name("all");
})->middleware('auth:sanctum');

Route::prefix("admin")->name("admin.*")->group(function () {
    Route::get("/all", [AdminController::class, "all"])->name("all");
    Route::post("/", [AdminController::class, "create"])->name("create");
    Route::delete("/{id}", [AdminController::class, "delete"])->name("delete")->where("id", "[0-9]+");
})->middleware("auth:sanctum");

Route::prefix("project")->name("project.")->group(function () {
    Route::get("/all", [ProjectController::class, "all"])->name("all");
    Route::get("/proposal", [ProjectController::class, "proposal"])->name("proposal");

    // CRUD
    Route::get("/{id}", [ProjectController::class, "one"])->name("preview")->where("id", "[0-9]+");
    Route::post("/{id}", [ProjectController::class, "create"])->name("create")->where("id", "[0-9]+");
    Route::put("/{id}", [ProjectController::class, "update"])->name("update")->where("id", "[0-9]+");
    Route::delete("/{id}", [ProjectController::class, "delete"])->name("delete")->where("id", "[0-9]+");

    // Proposition
    Route::get("/validate", [ProjectController::class, "validate"])->name("validate");
    Route::patch("/validate/{id}", [ProjectController::class, "validate"])->name("validate")->where("id", "[0-9}+");
    Route::patch("/reject", [ProjectController::class, "reject"])->name("validate")->where("id", "[0-9}+");
})->middleware("auth:sanctum");


Route::prefix("owner")->name("owner.")->group(function () {})->middleware("auth:sanctum");

Route::prefix("room")->name("room.")->group(function () {
    Route::get("/all", [RoomController::class, "all"])->name("all");
    Route::get("/{id}", [RoomController::class, "one"])->name("one")->where("id", "[0-9}+");
    Route::post("/", [RoomController::class, "create"])->name("create");
    Route::put("/{id}", [RoomController::class, "update"])->name("update")->where("id", "[0-9}+");
    Route::delete("/{id}", [RoomController::class, "delete"])->name("delete")->where("id", "[0-9}+");
})->middleware("auth:sanctum");

Route::prefix("email")->name("email.")->group(function () {
    Route::get("/all", [EmailController::class, "all"])->name("all");
    Route::get("/{id}", [EmailController::class, "one"])->name("one")->where("id", "[0-9}+");
    Route::post("/send", [EmailController::class, "send"])->name("send");
    Route::prefix("template")->name("template.")->group(function () {
        Route::get("/all", [EmailTemplateController::class, "all"])->name("all");
        Route::get("/{id}", [EmailTemplateController::class, "one"])->name("one")->where("id", "[0-9}+");
        Route::post("/", [EmailTemplateController::class, "create"])->name("create")->where("id", "[0-9}+");
        Route::put("/{id}", [EmailTemplateController::class, "update"])->name("update")->where("id", "[0-9}+");
        Route::delete("/{id}", [EmailTemplateController::class, "delete"])->name("delete")->where("id", "[0-9}+");
    });
})->middleware("auth:sanctum");

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

// Route::get("/test-email", function () {
//     $mailable = new Mailable();

//     $mailable
//         ->from('rynbsd04@gmail.com')
//         ->to('bbfgdh@gmail.com')
//         ->subject('test subject')
//         ->html('my first message');

//     $result = Mail::send($mailable);

//     return response(["ok" => true], 200);
// });
