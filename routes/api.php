<?php

use Illuminate\Http\Request;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

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
