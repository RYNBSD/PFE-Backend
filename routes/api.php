<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;
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
    Route::get("/profile", [UserController::class, "profile"])->name("profile");
    Route::post("/create", [UserController::class, "create"])->name("create");
    Route::put("/update", [UserController::class, "update"])->name("update");
    Route::delete("/delete", [UserController::class, "delete"])->name("delete");
})->middleware('auth:sanctum');

// Route::prefix("project")->name("project.")->group(function () {
//     Route::get("/all", function () {})->name("all");
//     Route::get("/{id}", function () {})->name("preview")->where("id", "[0-9]+");
//     Route::post("/{id}", function () {})->name("create")->where("id", "[0-9]+");
//     Route::put("/{id}", function () {})->name("update")->where("id", "[0-9]+");
//     Route::delete("/{id}", function () {})->name("delete")->where("id", "[0-9]+");
// })->middleware("auth:sanctum");

Route::prefix("admin")->name("admin")->group(function () {});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get("/test-email", function () {
    $mailable = new Mailable();

    $mailable
        ->from('rynbsd04@gmail.com')
        ->to('bbfgdh@gmail.com')
        ->subject('test subject')
        ->html('my first message');

    $result = Mail::send($mailable);

    return response(["ok" => true], 200);
});
