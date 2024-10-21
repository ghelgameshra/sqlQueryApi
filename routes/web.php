<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'message' => 'welcome to ' . env('APP_NAME') . ' API'
    ]);
});
