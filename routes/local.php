<?php

use Illuminate\Support\Facades\Route;

Route::middleware('api')->group(function () {
    Route::get('post-comment', function () {
        return response()->json(['message' => 'This is a local route!']);
    });
});

Route::middleware('web')->group(function () {

});